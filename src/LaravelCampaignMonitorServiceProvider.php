<?php

namespace Bernskiold\LaravelCampaignMonitor;

use Bernskiold\LaravelCampaignMonitor\Api\CampaignMonitor;
use Bernskiold\LaravelCampaignMonitor\Commands\ActivateWebhookCommand;
use Bernskiold\LaravelCampaignMonitor\Commands\CreateWebhookCommand;
use Bernskiold\LaravelCampaignMonitor\Commands\DeactivateWebhookCommand;
use Bernskiold\LaravelCampaignMonitor\Commands\DeleteWebhookCommand;
use Bernskiold\LaravelCampaignMonitor\Commands\ImportSubscribersCommand;
use Bernskiold\LaravelCampaignMonitor\Commands\ListWebhooksCommand;
use Bernskiold\LaravelCampaignMonitor\Commands\TestWebhookCommand;
use Bernskiold\LaravelCampaignMonitor\Exceptions\CampaignMonitorException;
use Illuminate\Foundation\Console\AboutCommand;
use Illuminate\Support\ServiceProvider;

use function config;

class LaravelCampaignMonitorServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        AboutCommand::add('Laravel Campaign Monitor', fn () => ['Version' => '1.0.0']);

        $this->publishes([
            __DIR__.'/../config/campaign-monitor.php' => config_path('campaign-monitor.php'),
        ], 'laravel-campaign-monitor-config');

        if ($this->app->runningInConsole()) {
            $this->commands([
                ActivateWebhookCommand::class,
                CreateWebhookCommand::class,
                DeactivateWebhookCommand::class,
                DeleteWebhookCommand::class,
                ListWebhooksCommand::class,
                TestWebhookCommand::class,

                ImportSubscribersCommand::class,
            ]);
        }

        // Load the routes if webhooks are enabled.
        if (config('campaign-monitor.webhooks.enabled', false) === true) {
            $this->loadRoutesFrom(__DIR__.'/../routes/webhooks.php');
        }
    }

    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/campaign-monitor.php', 'campaign-monitor'
        );

        $this->app->bind(CampaignMonitor::class, function () {
            $apiKey = config('campaign-monitor.apiKey');

            if (empty($apiKey)) {
                throw CampaignMonitorException::missingApiKey();
            }

            return new CampaignMonitor($apiKey);
        });

        $this->app->alias(CampaignMonitor::class, 'laravel-campaign-monitor');
    }
}

<?php

namespace BernskioldMedia\LaravelCampaignMonitor;

use BernskioldMedia\LaravelCampaignMonitor\Api\CampaignMonitor;
use BernskioldMedia\LaravelCampaignMonitor\Exceptions\CampaignMonitorException;
use Illuminate\Foundation\Console\AboutCommand;
use Illuminate\Support\ServiceProvider;

class LaravelCampaignMonitorServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        AboutCommand::add('Laravel Campaign Monitor', fn () => ['Version' => '1.0.0']);

        $this->publishes([
            __DIR__.'/../config/campaign-monitor.php' => config_path('campaign-monitor.php'),
        ], 'laravel-campaign-monitor-config');
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

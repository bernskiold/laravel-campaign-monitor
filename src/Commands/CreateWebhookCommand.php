<?php

namespace BernskioldMedia\LaravelCampaignMonitor\Commands;

use BernskioldMedia\LaravelCampaignMonitor\Actions\Webhooks\CreateWebhook;
use BernskioldMedia\LaravelCampaignMonitor\Enum\WebhookEvent;
use Illuminate\Console\Command;
use Throwable;

use function config;
use function Laravel\Prompts\multiselect;
use function Laravel\Prompts\text;

class CreateWebhookCommand extends Command
{
    protected $signature = 'campaign-monitor:create-webhook';

    protected $description = 'Creates a new webhook in Campaign Monitor for a list.';

    public function handle(CreateWebhook $createAction)
    {
        $listId = text(
            label: 'List ID',
            required: true,
            hint: 'The list ID can be found in the Campaign Monitor dashboard under the list settings.'
        );

        $url = text(
            label: 'Webhook URL',
            required: true,
            placeholder: url(config('campaign-monitor.webhooks.routePrefix')),
            hint: 'This is the URL of this application that will receive the webhook.'
        );

        $events = multiselect(
            label: 'What events should trigger the webhook?',
            options: WebhookEvent::asSelectArray(),
            required: true,
        );

        try {
            $webhookId = $createAction->execute(
                listId: $listId,
                url: $url,
                events: $events,
            );
        } catch (Throwable $e) {
            $this->error($e->getMessage());

            return self::FAILURE;
        }

        $this->info('The webhook was created.');
        $this->line('Webhook ID: '.$webhookId);

        $this->line('To activate the webhook, run:');
        $this->line("php artisan campaign-monitor:activate-webhook $listId $webhookId");

        return self::SUCCESS;
    }
}

<?php

namespace BernskioldMedia\LaravelCampaignMonitor\Commands;

use BernskioldMedia\LaravelCampaignMonitor\Facades\CampaignMonitor;
use Illuminate\Console\Command;
use Throwable;

use function Laravel\Prompts\text;

class TestWebhookCommand extends Command
{
    protected $signature = 'campaign-monitor:test-webhook {listId?} {webhookId?}';

    protected $description = 'Sends a test payload to the endpoint specified for the given webhook.';

    public function handle()
    {
        $listId = $this->argument('listId');
        $webhookId = $this->argument('webhookId');

        if (empty($listId)) {
            $listId = text(
                label: 'List ID',
                required: true,
                hint: 'The list ID can be found in the Campaign Monitor dashboard under the list settings.'
            );
        }

        if (empty($webhookId)) {
            $webhookId = text(
                label: 'Webhook ID',
                required: true,
                hint: 'The webhook ID will be returned when you create a new webhook using the create-webhook command.'
            );
        }

        try {
            CampaignMonitor::lists($listId)->test_webhook($webhookId);
        } catch (Throwable $e) {
            $this->error($e->getMessage());

            return self::FAILURE;
        }

        $this->info('A test payload has been sent for all events in the webhook.');

        return self::SUCCESS;
    }
}

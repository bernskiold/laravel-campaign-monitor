<?php

namespace Bernskiold\LaravelCampaignMonitor\Commands;

use Bernskiold\LaravelCampaignMonitor\Actions\Webhooks\ActivateWebhook;
use Illuminate\Console\Command;
use Throwable;

use function Laravel\Prompts\text;

class ActivateWebhookCommand extends Command
{
    protected $signature = 'campaign-monitor:activate-webhook {listId?} {webhookId?}';

    protected $description = 'Activate a webhook for a given list.';

    public function handle(ActivateWebhook $activateAction)
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
            $activateAction->execute($listId, $webhookId);
        } catch (Throwable $e) {
            $this->error($e->getMessage());

            return self::FAILURE;
        }

        $this->info('Webhook activated.');

        return self::SUCCESS;
    }
}

<?php

namespace Bernskiold\LaravelCampaignMonitor\Commands;

use Bernskiold\LaravelCampaignMonitor\Actions\Webhooks\GetWebhooks;
use Illuminate\Console\Command;
use Throwable;

use function Laravel\Prompts\table;
use function Laravel\Prompts\text;

class ListWebhooksCommand extends Command
{
    protected $signature = 'campaign-monitor:list-webhooks {listId?}';

    protected $description = 'Lists all the webhooks for a given list in Campaign Monitor.';

    public function handle(GetWebhooks $getAction)
    {
        $listId = $this->argument('listId');

        if (empty($listId)) {
            $listId = text(
                label: 'List ID',
                required: true,
                hint: 'The list ID can be found in the Campaign Monitor dashboard under the list settings.'
            );
        }

        try {
            $webhooks = $getAction->execute($listId);
        } catch (Throwable $e) {
            $this->error($e->getMessage());

            return self::FAILURE;
        }

        table(
            headers: ['ID', 'Url', 'Events', 'Status', 'Format'],
            rows: $webhooks
                ->map(function (array $webhook) {
                    return [
                        $webhook['id'],
                        $webhook['url'],
                        $webhook['events']->join(', '),
                        $webhook['status'],
                        $webhook['format'],
                    ];
                })
                ->all()
        );

        return self::SUCCESS;
    }
}

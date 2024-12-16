<?php

namespace BernskioldMedia\LaravelCampaignMonitor\Actions\Webhooks;

use BernskioldMedia\LaravelCampaignMonitor\Exceptions\CampaignMonitorException;
use BernskioldMedia\LaravelCampaignMonitor\Facades\CampaignMonitor;

class DeactivateWebhook
{
    public function execute(string $listId, string $webhookId): mixed
    {
        $response = CampaignMonitor::lists($listId)->deactivate_webhook($webhookId);

        if (! $response->was_successful()) {
            throw CampaignMonitorException::fromResponse($response);
        }

        return $response->response;
    }
}

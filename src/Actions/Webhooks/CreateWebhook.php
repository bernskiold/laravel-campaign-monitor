<?php

namespace Bernskiold\LaravelCampaignMonitor\Actions\Webhooks;

use Bernskiold\LaravelCampaignMonitor\Exceptions\CampaignMonitorException;
use Bernskiold\LaravelCampaignMonitor\Facades\CampaignMonitor;

class CreateWebhook
{
    public function execute(string $listId, string $url, array $events = []): mixed
    {
        $response = CampaignMonitor::lists($listId)->create_webhook([
            'Events' => $events,
            'Url' => $url,
            'PayloadFormat' => 'json',
        ]);

        if (! $response->was_successful()) {
            throw CampaignMonitorException::fromResponse($response);
        }

        return $response->response;
    }
}

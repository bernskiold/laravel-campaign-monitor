<?php

namespace Bernskiold\LaravelCampaignMonitor\Actions\Subscribers;

use Bernskiold\LaravelCampaignMonitor\Exceptions\CampaignMonitorException;
use Bernskiold\LaravelCampaignMonitor\Facades\CampaignMonitor;

class Unsubscribe
{
    public function execute(
        string $listId,
        string $email,
    ): mixed {
        $response = CampaignMonitor::subscribers($listId)->unsubscribe($email);

        if (! $response->was_successful()) {
            throw CampaignMonitorException::fromResponse($response);
        }

        return $response->response;
    }
}

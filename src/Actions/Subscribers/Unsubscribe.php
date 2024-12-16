<?php

namespace BernskioldMedia\LaravelCampaignMonitor\Actions\Subscribers;

use BernskioldMedia\LaravelCampaignMonitor\Exceptions\CampaignMonitorException;
use BernskioldMedia\LaravelCampaignMonitor\Facades\CampaignMonitor;

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

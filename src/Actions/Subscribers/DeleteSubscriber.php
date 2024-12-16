<?php

namespace BernskioldMedia\LaravelCampaignMonitor\Actions\Subscribers;

use BernskioldMedia\LaravelCampaignMonitor\Exceptions\CampaignMonitorException;
use BernskioldMedia\LaravelCampaignMonitor\Facades\CampaignMonitor;

class DeleteSubscriber
{
    public function execute(
        string $listId,
        string $email,
    ): mixed {
        $response = CampaignMonitor::subscribers($listId)
            ->delete($email);

        if (! $response->was_successful()) {
            throw CampaignMonitorException::fromResponse($response);
        }

        return $response->response;
    }
}

<?php

namespace Bernskiold\LaravelCampaignMonitor\Actions\Subscribers;

use Bernskiold\LaravelCampaignMonitor\Exceptions\CampaignMonitorException;
use Bernskiold\LaravelCampaignMonitor\Facades\CampaignMonitor;

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

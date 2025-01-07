<?php

namespace Bernskiold\LaravelCampaignMonitor\Actions\Subscribers;

use Bernskiold\LaravelCampaignMonitor\Exceptions\CampaignMonitorException;
use Bernskiold\LaravelCampaignMonitor\Facades\CampaignMonitor;

class ImportSubscribers
{
    public function execute(
        string $listId,
        array $subscriberData,
        bool $resubscribe = false,
        bool $queueWorkflows = false,
        bool $restartWorkflows = false
    ): mixed {
        $response = CampaignMonitor::subscribers($listId)->import(
            $subscriberData,
            $resubscribe,
            $queueWorkflows,
            $restartWorkflows,
        );

        if (! $response->was_successful()) {
            throw CampaignMonitorException::fromResponse($response);
        }

        return $response->response;
    }
}

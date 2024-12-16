<?php

namespace BernskioldMedia\LaravelCampaignMonitor\Actions\Subscribers;

use BernskioldMedia\LaravelCampaignMonitor\Exceptions\CampaignMonitorException;
use BernskioldMedia\LaravelCampaignMonitor\Facades\CampaignMonitor;

class Subscribe
{
    public function execute(
        string $listId,
        array $data,
        bool $resubscribe = false,
        bool $restartWorkflows = false
    ): mixed {
        $details = array_merge(
            [
                'Resubscribe' => $resubscribe,
                'RestartSubscriptionBasedAutoresponders' => $restartWorkflows,
            ],
            $data,
        );

        $response = CampaignMonitor::subscribers($listId)->add($details);

        if (! $response->was_successful()) {
            throw CampaignMonitorException::fromResponse($response);
        }

        return $response->response;
    }
}

<?php

namespace BernskioldMedia\LaravelCampaignMonitor\Actions\Subscribers;

use BernskioldMedia\LaravelCampaignMonitor\Exceptions\CampaignMonitorException;
use BernskioldMedia\LaravelCampaignMonitor\Facades\CampaignMonitor;

class UpdateSubscriber
{
    public function execute(
        string $listId,
        string $email,
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

        $response = CampaignMonitor::subscribers($listId)->update($email, $details);

        if (! $response->was_successful()) {
            throw CampaignMonitorException::fromResponse($response);
        }

        return $response->response;
    }
}

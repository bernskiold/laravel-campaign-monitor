<?php

namespace Bernskiold\LaravelCampaignMonitor\Actions\Subscribers;

use Bernskiold\LaravelCampaignMonitor\Exceptions\CampaignMonitorException;
use Bernskiold\LaravelCampaignMonitor\Facades\CampaignMonitor;

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

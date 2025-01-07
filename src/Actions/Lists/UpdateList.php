<?php

namespace Bernskiold\LaravelCampaignMonitor\Actions\Lists;

use Bernskiold\LaravelCampaignMonitor\Exceptions\CampaignMonitorException;
use Bernskiold\LaravelCampaignMonitor\Facades\CampaignMonitor;

class UpdateList
{
    public function execute(string $listId, array $data): string
    {
        $response = CampaignMonitor::lists($listId)->update($data);

        if (! $response->was_successful()) {
            throw CampaignMonitorException::fromResponse($response);
        }

        // Return the list ID.
        return $response->response;
    }
}

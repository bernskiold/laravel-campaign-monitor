<?php

namespace Bernskiold\LaravelCampaignMonitor\Actions\Lists;

use Bernskiold\LaravelCampaignMonitor\Exceptions\CampaignMonitorException;
use Bernskiold\LaravelCampaignMonitor\Facades\CampaignMonitor;

class DeleteList
{
    public function execute(string $listId): string
    {
        $response = CampaignMonitor::lists($listId)->delete();

        if (! $response->was_successful()) {
            throw CampaignMonitorException::fromResponse($response);
        }

        return $response->response;
    }
}

<?php

namespace BernskioldMedia\LaravelCampaignMonitor\Actions\Lists;

use BernskioldMedia\LaravelCampaignMonitor\Exceptions\CampaignMonitorException;
use BernskioldMedia\LaravelCampaignMonitor\Facades\CampaignMonitor;

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

<?php

namespace BernskioldMedia\LaravelCampaignMonitor\Actions\CustomFields;

use BernskioldMedia\LaravelCampaignMonitor\Exceptions\CampaignMonitorException;
use BernskioldMedia\LaravelCampaignMonitor\Facades\CampaignMonitor;

class CreateCustomField
{
    public function execute(string $listId, array $data): mixed
    {
        $response = CampaignMonitor::lists($listId)->create_custom_field($data);

        if (! $response->was_successful()) {
            throw CampaignMonitorException::fromResponse($response);
        }

        return $response->response;
    }
}

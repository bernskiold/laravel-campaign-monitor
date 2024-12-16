<?php

namespace BernskioldMedia\LaravelCampaignMonitor\Actions\CustomFields;

use BernskioldMedia\LaravelCampaignMonitor\Exceptions\CampaignMonitorException;
use BernskioldMedia\LaravelCampaignMonitor\Facades\CampaignMonitor;

class UpdateCustomField
{
    public function execute(string $listId, string $fieldKey, array $data): mixed
    {
        $response = CampaignMonitor::lists($listId)->update_field_options($fieldKey, $data);

        if (! $response->was_successful()) {
            throw CampaignMonitorException::fromResponse($response);
        }

        return $response->response;
    }
}

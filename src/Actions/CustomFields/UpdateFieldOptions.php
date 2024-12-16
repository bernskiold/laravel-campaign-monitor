<?php

namespace BernskioldMedia\LaravelCampaignMonitor\Actions\CustomFields;

use BernskioldMedia\LaravelCampaignMonitor\Exceptions\CampaignMonitorException;
use BernskioldMedia\LaravelCampaignMonitor\Facades\CampaignMonitor;

class UpdateFieldOptions
{
    public function execute(string $listId, string $fieldKey, array $options, bool $keepExistingRecords = false): mixed
    {
        $response = CampaignMonitor::lists($listId)
            ->update_field_options(
                $fieldKey,
                $options,
                $keepExistingRecords
            );

        if (! $response->was_successful()) {
            throw CampaignMonitorException::fromResponse($response);
        }

        return $response->response;
    }
}

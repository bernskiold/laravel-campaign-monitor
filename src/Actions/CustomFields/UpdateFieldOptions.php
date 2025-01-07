<?php

namespace Bernskiold\LaravelCampaignMonitor\Actions\CustomFields;

use Bernskiold\LaravelCampaignMonitor\Exceptions\CampaignMonitorException;
use Bernskiold\LaravelCampaignMonitor\Facades\CampaignMonitor;

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

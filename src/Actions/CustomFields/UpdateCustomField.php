<?php

namespace Bernskiold\LaravelCampaignMonitor\Actions\CustomFields;

use Bernskiold\LaravelCampaignMonitor\Exceptions\CampaignMonitorException;
use Bernskiold\LaravelCampaignMonitor\Facades\CampaignMonitor;

class UpdateCustomField
{
    public function execute(string $listId, string $fieldKey, array $data): mixed
    {
        $response = CampaignMonitor::lists($listId)->update_custom_field($fieldKey, $data);

        if (! $response->was_successful()) {
            throw CampaignMonitorException::fromResponse($response);
        }

        return $response->response;
    }
}

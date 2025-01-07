<?php

namespace Bernskiold\LaravelCampaignMonitor\Tests\Fixtures;

use Bernskiold\LaravelCampaignMonitor\Concerns\SyncsWithCampaignMonitorList;
use Bernskiold\LaravelCampaignMonitor\Contracts\CampaignMonitorList;
use Bernskiold\LaravelCampaignMonitor\Data\ListCustomFields;
use Bernskiold\LaravelCampaignMonitor\Data\ListDetails;
use Illuminate\Database\Eloquent\Model;

class ListModel extends Model implements CampaignMonitorList
{
    use SyncsWithCampaignMonitorList;

    protected $guarded = [];

    public function getCampaignMonitorListDetails(): ListDetails
    {
        return ListDetails::make();
    }

    public function getCampaignMonitorCustomFields(): ListCustomFields
    {
        return ListCustomFields::make();
    }

    public function getCampaignMonitorListId(): ?string
    {
        return null;
    }
}

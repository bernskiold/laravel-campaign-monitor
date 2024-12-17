<?php

namespace BernskioldMedia\LaravelCampaignMonitor\Tests\Fixtures;

use BernskioldMedia\LaravelCampaignMonitor\Concerns\SyncsWithCampaignMonitorList;
use BernskioldMedia\LaravelCampaignMonitor\Contracts\CampaignMonitorList;
use BernskioldMedia\LaravelCampaignMonitor\Data\ListCustomFields;
use BernskioldMedia\LaravelCampaignMonitor\Data\ListDetails;
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

<?php

namespace BernskioldMedia\LaravelCampaignMonitor\Tests\Fixtures;

use BernskioldMedia\LaravelCampaignMonitor\Concerns\SyncsWithCampaignMonitorSubscribers;
use BernskioldMedia\LaravelCampaignMonitor\Contracts\CampaignMonitorSubscriber;
use BernskioldMedia\LaravelCampaignMonitor\Data\SubscriberDetails;
use Illuminate\Database\Eloquent\Model;

class SubscriberModel extends Model implements CampaignMonitorSubscriber
{
    use SyncsWithCampaignMonitorSubscribers;

    protected $guarded = [];

    public function getCampaignMonitorSubscriberDetails(): SubscriberDetails
    {
        return SubscriberDetails::make();
    }
}

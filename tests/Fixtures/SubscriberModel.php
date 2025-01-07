<?php

namespace Bernskiold\LaravelCampaignMonitor\Tests\Fixtures;

use Bernskiold\LaravelCampaignMonitor\Concerns\SyncsWithCampaignMonitorSubscribers;
use Bernskiold\LaravelCampaignMonitor\Contracts\CampaignMonitorSubscriber;
use Bernskiold\LaravelCampaignMonitor\Data\SubscriberDetails;
use Illuminate\Database\Eloquent\Model;

class SubscriberModel extends Model implements CampaignMonitorSubscriber
{
    use SyncsWithCampaignMonitorSubscribers;

    protected $guarded = [];

    public function getCampaignMonitorSubscriberDetails(): SubscriberDetails
    {
        return SubscriberDetails::make();
    }

    public function getCampaignMonitorListIds(): array
    {
        return [];
    }
}

<?php

namespace BernskioldMedia\LaravelCampaignMonitor\Contracts;

use BernskioldMedia\LaravelCampaignMonitor\Data\SubscriberDetails;

interface CampaignMonitorSubscriber
{
    public function getCampaignMonitorSubscriberDetails(): SubscriberDetails;

    public function getCampaignMonitorUniqueJobIdentifier(): string;

    public function shouldSyncWithCampaignMonitor(): bool;
}

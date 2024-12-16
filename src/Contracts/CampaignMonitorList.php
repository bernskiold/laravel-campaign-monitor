<?php

namespace BernskioldMedia\LaravelCampaignMonitor\Contracts;

use BernskioldMedia\LaravelCampaignMonitor\Data\ListCustomFields;
use BernskioldMedia\LaravelCampaignMonitor\Data\ListDetails;

interface CampaignMonitorList
{
    public function getCampaignMonitorUniqueJobIdentifier(): string;

    public function shouldSyncWithCampaignMonitor(): bool;

    public function getCampaignMonitorListDetails(): ListDetails;

    public function getCampaignMonitorCustomFields(): ListCustomFields;

    public function getCampaignMonitorListId(): ?string;
}

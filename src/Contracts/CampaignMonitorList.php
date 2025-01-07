<?php

namespace Bernskiold\LaravelCampaignMonitor\Contracts;

use Bernskiold\LaravelCampaignMonitor\Data\ListCustomFields;
use Bernskiold\LaravelCampaignMonitor\Data\ListDetails;

interface CampaignMonitorList
{
    public function getCampaignMonitorUniqueJobIdentifier(): string;

    public function shouldSyncWithCampaignMonitor(): bool;

    public function getCampaignMonitorListDetails(): ListDetails;

    public function getCampaignMonitorCustomFields(): ListCustomFields;

    public function getCampaignMonitorListId(): ?string;
}

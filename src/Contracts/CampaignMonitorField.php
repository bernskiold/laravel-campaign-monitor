<?php

namespace BernskioldMedia\LaravelCampaignMonitor\Contracts;

use Illuminate\Contracts\Support\Arrayable;

interface CampaignMonitorField
{
    public function getCampaignMonitorFieldKey(): string;

    public function getCampaignMonitorOptions(): Arrayable|array;

    public function getCampaignMonitorUniqueJobIdentifier(): string;

    public function shouldSyncWithCampaignMonitor(): bool;
}

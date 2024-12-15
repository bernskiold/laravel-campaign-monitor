<?php

namespace BernskioldMedia\LaravelCampaignMonitor\Concerns;

trait SyncsWithCampaignMonitor
{
    public function getCampaignMonitorUniqueJobIdentifier(): string
    {
        return str($this->getMorphClass())
            ->append('-')
            ->append($this->getKey())
            ->slug()
            ->toString();
    }

    public function shouldSyncWithCampaignMonitor(): bool
    {
        return true;
    }
}

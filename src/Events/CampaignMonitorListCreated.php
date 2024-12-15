<?php

namespace BernskioldMedia\LaravelCampaignMonitor\Events;

use BernskioldMedia\LaravelCampaignMonitor\Contracts\CampaignMonitorList;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CampaignMonitorListCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public CampaignMonitorList $model,
        public string $listId,
    ) {}
}

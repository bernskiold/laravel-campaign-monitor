<?php

namespace Bernskiold\LaravelCampaignMonitor\Events;

use Bernskiold\LaravelCampaignMonitor\Contracts\CampaignMonitorList;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CampaignMonitorListCreatedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public CampaignMonitorList $model,
        public string $listId,
    ) {}
}

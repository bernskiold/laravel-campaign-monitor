<?php

namespace Bernskiold\LaravelCampaignMonitor\Events;

use Carbon\CarbonInterface;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CampaignMonitorSubscriberSubscribedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public string $listId,
        public string $email,
        public ?string $name,
        public CarbonInterface $date,
        public ?string $ipAddress = null,
        public array $customFields = [],
    ) {}
}

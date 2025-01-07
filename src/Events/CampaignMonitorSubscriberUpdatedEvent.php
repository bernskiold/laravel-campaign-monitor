<?php

namespace Bernskiold\LaravelCampaignMonitor\Events;

use Carbon\CarbonInterface;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CampaignMonitorSubscriberUpdatedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public string $listId,
        public string $email,
        public string $previousEmail,
        public ?string $name,
        public string $state,
        public CarbonInterface $date,
        public array $customFields = [],
    ) {}
}

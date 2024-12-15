<?php

namespace BernskioldMedia\LaravelCampaignMonitor\Api;

use CS_REST_General;
use CS_REST_Lists;
use CS_REST_Subscribers;

use function config;

class CampaignMonitor
{
    protected array $authentication = [];

    public function __construct(string $apiKey)
    {
        $this->authentication = [
            'api_key' => $apiKey,
        ];
    }

    public function isActive(): bool
    {
        return config('campaign-monitor.active', false) === true;
    }

    public function account(): CS_REST_General
    {
        return new CS_REST_General($this->authentication);
    }

    public function lists(?string $listId = null): CS_REST_Lists
    {
        return new CS_REST_Lists($listId, $this->authentication);
    }

    public function subscribers(string $listId): CS_REST_Subscribers
    {
        return new CS_REST_Subscribers($listId, $this->authentication);
    }
}

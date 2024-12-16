<?php

namespace BernskioldMedia\LaravelCampaignMonitor\Api;

use CS_REST_Campaigns;
use CS_REST_Clients;
use CS_REST_General;
use CS_REST_Journeys;
use CS_REST_Lists;
use CS_REST_Segments;
use CS_REST_Subscribers;
use CS_REST_Templates;
use CS_REST_Transactional_SmartEmail;

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

    public function campaigns(?string $campaignId = null): CS_REST_Campaigns
    {
        return new CS_REST_Campaigns($campaignId, $this->authentication);
    }

    public function clients(?string $clientId = null): CS_REST_Clients
    {
        return new CS_REST_Clients($clientId, $this->authentication);
    }

    public function journeys(?string $journeyId = null): CS_REST_Journeys
    {
        return new CS_REST_Journeys($journeyId, $this->authentication);
    }

    public function lists(?string $listId = null): CS_REST_Lists
    {
        return new CS_REST_Lists($listId, $this->authentication);
    }

    public function segments(?string $segmentId = null): CS_REST_Segments
    {
        return new CS_REST_Segments($segmentId, $this->authentication);
    }

    public function subscribers(string $listId): CS_REST_Subscribers
    {
        return new CS_REST_Subscribers($listId, $this->authentication);
    }

    public function templates(?string $templateId = null): CS_REST_Templates
    {
        return new CS_REST_Templates($templateId, $this->authentication);
    }

    public function transactional(?string $transactionalId = null): CS_REST_Transactional_SmartEmail
    {
        return new CS_REST_Transactional_SmartEmail($transactionalId, $this->authentication);
    }
}

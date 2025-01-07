<?php

namespace Bernskiold\LaravelCampaignMonitor\Facades;

use CS_REST_Campaigns;
use CS_REST_Clients;
use CS_REST_General;
use CS_REST_Journeys;
use CS_REST_Lists;
use CS_REST_Segments;
use CS_REST_Subscribers;
use CS_REST_Templates;
use CS_REST_Transactional_SmartEmail;
use Illuminate\Support\Facades\Facade;

/**
 * @method static CS_REST_General account()
 * @method static CS_REST_Campaigns campaigns(?string $campaignId = null)
 * @method static CS_REST_Clients clients(?string $clientId = null)
 * @method static CS_REST_Journeys journeys(?string $journeyId = null)
 * @method static CS_REST_Lists lists(?string $listId = null)
 * @method static CS_REST_Segments segments(?string $segmentId = null)
 * @method static CS_REST_Subscribers subscribers(string $listId)
 * @method static CS_REST_Templates templates(?string $templateId = null)
 * @method static CS_REST_Transactional_SmartEmail transactional(?string $transactionalId = null)
 * @method static bool isActive()
 */
class CampaignMonitor extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'laravel-campaign-monitor';
    }
}

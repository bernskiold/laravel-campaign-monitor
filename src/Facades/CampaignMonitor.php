<?php

namespace BernskioldMedia\LaravelCampaignMonitor\Facades;

use CS_REST_General;
use CS_REST_Lists;
use CS_REST_Subscribers;
use Illuminate\Support\Facades\Facade;

/**
 * @method static CS_REST_General account()
 * @method static CS_REST_Lists lists(?string $listId = null)
 * @method static CS_REST_Subscribers subscribers(string $listId)
 * @method static bool isActive()
 */
class CampaignMonitor extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'laravel-campaign-monitor';
    }
}

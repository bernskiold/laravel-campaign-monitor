<?php

use BernskioldMedia\LaravelCampaignMonitor\Controllers\IncomingWebhooks\HandleCreatedSubscriber;
use BernskioldMedia\LaravelCampaignMonitor\Controllers\IncomingWebhooks\HandleDeactivatedSubscriber;
use BernskioldMedia\LaravelCampaignMonitor\Controllers\IncomingWebhooks\HandleUpdatedSubscriber;

return [

    'apiKey' => env('CAMPAIGN_MONITOR_API_KEY'),

    'clientId' => env('CAMPAIGN_MONITOR_CLIENT_ID'),

    /**
     * Control whether the Campaign Monitor integration is active.
     * This can be used to disable the integration
     * for example in a testing environment.
     */
    'active' => env('CAMPAIGN_MONITOR_ACTIVE', false),

    'webhooks' => [
        'enabled' => false,
        'routePrefix' => 'webhooks/campaign-monitor',

        'middleware' => [
            'web',
        ],

        'routes' => [
            'updatedSubscriber' => [
                'enabled' => false,
                'route' => 'updated-subscriber',
                'controller' => HandleUpdatedSubscriber::class,
            ],

            'createdSubscriber' => [
                'enabled' => false,
                'route' => 'created-subscriber',
                'controller' => HandleCreatedSubscriber::class,
            ],

            'deactivatedSubscriber' => [
                'enabled' => false,
                'route' => 'deactivated-subscriber',
                'controller' => HandleDeactivatedSubscriber::class,
            ],
        ],

    ],

];

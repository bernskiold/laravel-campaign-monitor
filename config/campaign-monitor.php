<?php

use BernskioldMedia\LaravelCampaignMonitor\Controllers\HandleCreatedSubscriber;
use BernskioldMedia\LaravelCampaignMonitor\Controllers\HandleDeactivatedSubscriber;
use BernskioldMedia\LaravelCampaignMonitor\Controllers\HandleUpdatedSubscriber;

return [

    /**
     * The API Key for Campaign Monitor.
     *
     * This can be found in your Campaign Monitor account under Account Settings.
     */
    'apiKey' => env('CAMPAIGN_MONITOR_API_KEY'),

    /**
     * The Client ID for Campaign Monitor.
     *
     * For agency accounts, this is the client ID of the client you are working with.
     */
    'clientId' => env('CAMPAIGN_MONITOR_CLIENT_ID'),

    /**
     * Control whether the Campaign Monitor integration is active.
     * This can be used to disable the integration
     * for example in a testing environment.
     */
    'active' => env('CAMPAIGN_MONITOR_ACTIVE', false),

    'webhooks' => [

        /**
         * Control whether the application should handle
         * incoming webhooks from Campaign Monitor.
         */
        'enabled' => false,

        /**
         * The prefix for the incoming webhook routes.
         */
        'routePrefix' => 'webhooks/campaign-monitor',

        /**
         * The middleware to apply to the incoming webhook routes.
         */
        'middleware' => [
            'web',
        ],

        /**
         * The routes to handle incoming webhooks.
         */
        'routes' => [

            /**
             * Handle the updated subscriber webhook.
             */
            'updatedSubscriber' => [
                'enabled' => false,
                'route' => 'updated-subscriber',
                'controller' => HandleUpdatedSubscriber::class,
            ],

            /**
             * Handle the created subscriber webhook.
             */
            'createdSubscriber' => [
                'enabled' => false,
                'route' => 'created-subscriber',
                'controller' => HandleCreatedSubscriber::class,
            ],

            /**
             * Handle the deactivated subscriber webhook.
             */
            'deactivatedSubscriber' => [
                'enabled' => false,
                'route' => 'deactivated-subscriber',
                'controller' => HandleDeactivatedSubscriber::class,
            ],
        ],

    ],

];

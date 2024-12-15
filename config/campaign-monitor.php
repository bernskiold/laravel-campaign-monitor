<?php

return [

    'apiKey' => env('CAMPAIGN_MONITOR_API_KEY'),

    'clientId' => env('CAMPAIGN_MONITOR_CLIENT_ID'),

    /**
     * Control whether the Campaign Monitor integration is active.
     * This can be used to disable the integration
     * for example in a testing environment.
     */
    'active' => env('CAMPAIGN_MONITOR_ACTIVE', false),

];

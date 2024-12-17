<?php

use BernskioldMedia\LaravelCampaignMonitor\Actions\Webhooks\CreateWebhook;
use BernskioldMedia\LaravelCampaignMonitor\Facades\CampaignMonitor;
use Illuminate\Support\Facades\Config;

beforeEach(function () {
    Config::set('campaign-monitor.apiKey', 'test-api');
});

it('returns the response on success', function () {
    CampaignMonitor::shouldReceive('lists->create_webhook')
        ->andReturn(new CS_REST_Wrapper_Result(
            response: 'test',
            code: 200,
        ));

    $response = app(CreateWebhook::class)->execute(
        listId: 'list-id',
        url: 'https://example.org/webhook',
        events: [],
    );

    expect($response)->toBe('test');
});

it('returns an exception if not successful', function () {
    CampaignMonitor::shouldReceive('lists->create_webhook')
        ->andReturn(new CS_REST_Wrapper_Result(
            response: 'test',
            code: 400,
        ));

    app(CreateWebhook::class)->execute(
        listId: 'list-id',
        url: 'https://example.org/webhook',
        events: [],
    );
})
    ->throws(BernskioldMedia\LaravelCampaignMonitor\Exceptions\CampaignMonitorException::class);

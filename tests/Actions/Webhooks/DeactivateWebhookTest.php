<?php

use Bernskiold\LaravelCampaignMonitor\Actions\Webhooks\DeactivateWebhook;
use Bernskiold\LaravelCampaignMonitor\Facades\CampaignMonitor;
use Illuminate\Support\Facades\Config;

beforeEach(function () {
    Config::set('campaign-monitor.apiKey', 'test-api');
});

it('returns the response on success', function () {
    CampaignMonitor::shouldReceive('lists->deactivate_webhook')
        ->andReturn(new CS_REST_Wrapper_Result(
            response: 'test',
            code: 200,
        ));

    $response = app(DeactivateWebhook::class)->execute(
        listId: 'list-id',
        webhookId: 'webhook-id',
    );

    expect($response)->toBe('test');
});

it('returns an exception if not successful', function () {
    CampaignMonitor::shouldReceive('lists->deactivate_webhook')
        ->andReturn(new CS_REST_Wrapper_Result(
            response: 'test',
            code: 400,
        ));

    app(DeactivateWebhook::class)->execute(
        listId: 'list-id',
        webhookId: 'webhook-id',
    );
})
    ->throws(Bernskiold\LaravelCampaignMonitor\Exceptions\CampaignMonitorException::class);

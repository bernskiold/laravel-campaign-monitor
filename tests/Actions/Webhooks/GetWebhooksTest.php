<?php

use Bernskiold\LaravelCampaignMonitor\Actions\Webhooks\GetWebhooks;
use Bernskiold\LaravelCampaignMonitor\Enum\WebhookEvent;
use Bernskiold\LaravelCampaignMonitor\Facades\CampaignMonitor;
use Illuminate\Support\Facades\Config;

beforeEach(function () {
    Config::set('campaign-monitor.apiKey', 'test-api');
});

it('returns the response on success', function () {
    CampaignMonitor::shouldReceive('lists->get_webhooks')
        ->andReturn(new CS_REST_Wrapper_Result(
            response: [
                (object) [
                    'WebhookID' => 'webhook-id',
                    'Url' => 'https://example.org/webhook',
                    'Events' => [WebhookEvent::Subscribe->value],
                    'Status' => 'Active',
                    'PayloadFormat' => 'json',
                ],
            ],
            code: 200,
        ));

    $response = app(GetWebhooks::class)->execute(
        listId: 'list-id',
    );

    expect($response)
        ->toBeCollection()
        ->toHaveCount(1)
        ->first()
        ->toEqual([
            'id' => 'webhook-id',
            'url' => 'https://example.org/webhook',
            'events' => collect([WebhookEvent::Subscribe]),
            'status' => 'Active',
            'format' => 'json',
        ]);
});

it('returns an exception if not successful', function () {
    CampaignMonitor::shouldReceive('lists->get_webhooks')
        ->andReturn(new CS_REST_Wrapper_Result(
            response: 'test',
            code: 400,
        ));

    app(GetWebhooks::class)->execute(
        listId: 'list-id',
    );
})
    ->throws(Bernskiold\LaravelCampaignMonitor\Exceptions\CampaignMonitorException::class);

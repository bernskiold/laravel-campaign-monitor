<?php

use Bernskiold\LaravelCampaignMonitor\Actions\Webhooks\GetWebhooks;

it('asks for list ID if not provided', function () {
    $this->mock(GetWebhooks::class)
        ->shouldReceive('execute')
        ->andReturn(true);

    $this->artisan('campaign-monitor:list-webhooks')
        ->expectsQuestion('List ID', 'list-id');
});

it('uses provided list ID', function () {
    $this->mock(GetWebhooks::class)
        ->shouldReceive('execute')
        ->andReturn(collect([
            [
                'id' => 'webhook-id',
                'url' => 'https://example.org/webhook',
                'events' => collect(['subscribe', 'unsubscribe']),
                'status' => 'active',
                'format' => 'json',
            ],
        ]));

    $this->artisan('campaign-monitor:list-webhooks list-id')
//        ->expectsTable(['ID', 'Url', 'Events', 'Status', 'Format'], [
//            [
//                'webhook-id',
//                'https://example.org/webhook',
//                'subscribe, unsubscribe',
//                'active',
//                'json'
//            ],
//        ])
        ->assertSuccessful();
});

it('handles exceptions', function () {
    $this->mock(GetWebhooks::class)
        ->shouldReceive('execute')
        ->andThrow(new Exception('Test Exception'));

    $this->artisan('campaign-monitor:list-webhooks list-id')
        ->expectsOutput('Test Exception')
        ->assertFailed();
});

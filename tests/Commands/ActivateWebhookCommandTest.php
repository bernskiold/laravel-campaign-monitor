<?php

use Bernskiold\LaravelCampaignMonitor\Actions\Webhooks\ActivateWebhook;

it('asks for list ID and webhook ID if not provided', function () {
    $this->mock(ActivateWebhook::class)
        ->shouldReceive('execute')
        ->andReturn(true);

    $this->artisan('campaign-monitor:activate-webhook')
        ->expectsQuestion('List ID', 'list-id')
        ->expectsQuestion('Webhook ID', 'webhook-id');
});

it('uses provided list ID and webhook ID', function () {
    $this->mock(ActivateWebhook::class)
        ->shouldReceive('execute')
        ->andReturn(true);

    $this->artisan('campaign-monitor:activate-webhook list-id webhook-id')
        ->expectsOutput('Webhook activated.')
        ->assertSuccessful();
});

it('handles exceptions', function () {
    $this->mock(ActivateWebhook::class)
        ->shouldReceive('execute')
        ->andThrow(new Exception('Test Exception'));

    $this->artisan('campaign-monitor:activate-webhook list-id webhook-id')
        ->expectsOutput('Test Exception')
        ->assertFailed();
});

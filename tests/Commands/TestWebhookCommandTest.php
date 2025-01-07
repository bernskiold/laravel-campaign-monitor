<?php

use Bernskiold\LaravelCampaignMonitor\Actions\Webhooks\TestWebhook;

it('asks for list ID and webhook ID if not provided', function () {
    $this->mock(TestWebhook::class)
        ->shouldReceive('execute')
        ->andReturn(true);

    $this->artisan('campaign-monitor:test-webhook')
        ->expectsQuestion('List ID', 'list-id')
        ->expectsQuestion('Webhook ID', 'webhook-id');
});

it('uses provided list ID and webhook ID', function () {
    $this->mock(TestWebhook::class)
        ->shouldReceive('execute')
        ->andReturn(true);

    $this->artisan('campaign-monitor:test-webhook list-id webhook-id')
        ->expectsOutput('A test payload has been sent for all events in the webhook.')
        ->assertSuccessful();
});

it('handles exceptions', function () {
    $this->mock(TestWebhook::class)
        ->shouldReceive('execute')
        ->andThrow(new Exception('Test Exception'));

    $this->artisan('campaign-monitor:test-webhook list-id webhook-id')
        ->expectsOutput('Test Exception')
        ->assertFailed();
});

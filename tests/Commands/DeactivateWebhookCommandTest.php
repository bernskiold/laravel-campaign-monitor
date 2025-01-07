<?php

use Bernskiold\LaravelCampaignMonitor\Actions\Webhooks\DeactivateWebhook;

it('asks for list ID and webhook ID if not provided', function () {
    $this->mock(DeactivateWebhook::class)
        ->shouldReceive('execute')
        ->andReturn(true);

    $this->artisan('campaign-monitor:deactivate-webhook')
        ->expectsQuestion('List ID', 'list-id')
        ->expectsQuestion('Webhook ID', 'webhook-id');
});

it('uses provided list ID and webhook ID', function () {
    $this->mock(DeactivateWebhook::class)
        ->shouldReceive('execute')
        ->andReturn(true);

    $this->artisan('campaign-monitor:deactivate-webhook list-id webhook-id')
        ->expectsOutput('Webhook deactivated.')
        ->assertSuccessful();
});

it('handles exceptions', function () {
    $this->mock(DeactivateWebhook::class)
        ->shouldReceive('execute')
        ->andThrow(new Exception('Test Exception'));

    $this->artisan('campaign-monitor:deactivate-webhook list-id webhook-id')
        ->expectsOutput('Test Exception')
        ->assertFailed();
});

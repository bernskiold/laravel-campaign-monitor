<?php

use BernskioldMedia\LaravelCampaignMonitor\Actions\Webhooks\DeleteWebhook;

it('asks for list ID and webhook ID if not provided', function () {
    $this->mock(DeleteWebhook::class)
        ->shouldReceive('execute')
        ->andReturn(true);

    $this->artisan('campaign-monitor:delete-webhook')
        ->expectsQuestion('List ID', 'list-id')
        ->expectsQuestion('Webhook ID', 'webhook-id');
});

it('uses provided list ID and webhook ID', function () {
    $this->mock(DeleteWebhook::class)
        ->shouldReceive('execute')
        ->andReturn(true);

    $this->artisan('campaign-monitor:delete-webhook list-id webhook-id')
        ->expectsOutput('Webhook deleted.')
        ->assertSuccessful();
});

it('handles exceptions', function () {
    $this->mock(DeleteWebhook::class)
        ->shouldReceive('execute')
        ->andThrow(new Exception('Test Exception'));

    $this->artisan('campaign-monitor:delete-webhook list-id webhook-id')
        ->expectsOutput('Test Exception')
        ->assertFailed();
});

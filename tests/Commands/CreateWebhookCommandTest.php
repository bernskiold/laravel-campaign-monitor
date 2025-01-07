<?php

use Bernskiold\LaravelCampaignMonitor\Actions\Webhooks\CreateWebhook;
use Bernskiold\LaravelCampaignMonitor\Enum\WebhookEvent;

it('asks for required parameters', function () {
    $this->mock(CreateWebhook::class)
        ->shouldReceive('execute')
        ->andReturn('webhook-id');

    $this->artisan('campaign-monitor:create-webhook')
        ->expectsQuestion('List ID', 'list-id')
        ->expectsQuestion('Webhook URL', 'url')
        ->expectsChoice('What events should trigger the webhook?', [WebhookEvent::Subscribe->value], WebhookEvent::asSelectArray())
        ->expectsOutput('The webhook was created.')
        ->expectsOutput('Webhook ID: webhook-id')
        ->expectsOutput('To activate the webhook, run:')
        ->expectsOutput('php artisan campaign-monitor:activate-webhook list-id webhook-id')
        ->assertSuccessful();
});

it('handles exceptions', function () {
    $this->mock(CreateWebhook::class)
        ->shouldReceive('execute')
        ->andThrow(new Exception('Test Exception'));

    $this->artisan('campaign-monitor:create-webhook')
        ->expectsQuestion('List ID', 'list-id')
        ->expectsQuestion('Webhook URL', 'url')
        ->expectsChoice('What events should trigger the webhook?', [WebhookEvent::Subscribe->value], WebhookEvent::asSelectArray())
        ->expectsOutput('Test Exception')
        ->assertFailed();
});

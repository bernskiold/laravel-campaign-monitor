<?php

use BernskioldMedia\LaravelCampaignMonitor\Enum\WebhookEvent;
use BernskioldMedia\LaravelCampaignMonitor\Events\CampaignMonitorSubscriberSubscribedEvent;
use Illuminate\Support\Facades\Event;

use function Pest\Laravel\postJson;
use function Pest\Laravel\withoutMiddleware;

beforeEach(function () {
    withoutMiddleware();
    Event::fake();
});

it('handles a successful creation event', function () {
    $data = [
        'ListID' => 'test-list-id',
        'Events' => [
            [
                'Type' => WebhookEvent::Subscribe->value,
                'EmailAddress' => 'test@example.com',
                'Name' => 'Test User',
                'Date' => now()->toIso8601String(),
                'SignupIpAddress' => '127.0.0.1',
                'CustomFields' => [],
            ],
        ],
    ];

    postJson('/webhooks/campaign-monitor/created-subscriber', $data)
        ->assertSuccessful();

    Event::assertDispatched(CampaignMonitorSubscriberSubscribedEvent::class, function ($event) use ($data) {
        return $event->listId === $data['ListID'] &&
            $event->email === $data['Events'][0]['EmailAddress'] &&
            $event->name === $data['Events'][0]['Name'];
    });
});

it('filters non-created events', function () {
    $data = [
        'ListID' => 'test-list-id',
        'Events' => [
            [
                'Type' => 'Non-Subscribe',
                'EmailAddress' => 'test@example.com',
                'Name' => 'Test User',
                'Date' => now()->toIso8601String(),
                'CustomFields' => [],
            ],
        ],
    ];

    postJson('/webhooks/campaign-monitor/created-subscriber', $data)
        ->assertSuccessful();

    Event::assertNotDispatched(CampaignMonitorSubscriberSubscribedEvent::class);
});

it('filters events with empty email addresses', function () {
    $data = [
        'ListID' => 'test-list-id',
        'Events' => [
            [
                'Type' => WebhookEvent::Subscribe->value,
                'EmailAddress' => '',
                'Name' => 'Test User',
                'Date' => now()->toIso8601String(),
                'CustomFields' => [],
            ],
        ],
    ];

    postJson('/webhooks/campaign-monitor/created-subscriber', $data)
        ->assertSuccessful();

    Event::assertNotDispatched(CampaignMonitorSubscriberSubscribedEvent::class);
});


<?php

use Bernskiold\LaravelCampaignMonitor\Enum\WebhookEvent;
use Bernskiold\LaravelCampaignMonitor\Events\CampaignMonitorSubscriberDeactivatedEvent;
use Illuminate\Support\Facades\Event;

use function Pest\Laravel\postJson;
use function Pest\Laravel\withoutMiddleware;

beforeEach(function () {
    withoutMiddleware();
    Event::fake();
});

it('handles a successful deactivation event', function () {
    $data = [
        'ListID' => 'test-list-id',
        'Events' => [
            [
                'Type' => WebhookEvent::Deactivate->value,
                'EmailAddress' => 'test@example.com',
                'Name' => 'Test User',
                'State' => 'Unsubscribed',
                'Date' => now()->toIso8601String(),
                'CustomFields' => [],
            ],
        ],
    ];

    postJson('/webhooks/campaign-monitor/deactivated-subscriber', $data)
        ->assertSuccessful();

    Event::assertDispatched(CampaignMonitorSubscriberDeactivatedEvent::class, function ($event) use ($data) {
        return $event->listId === $data['ListID'] &&
            $event->email === $data['Events'][0]['EmailAddress'] &&
            $event->name === $data['Events'][0]['Name'] &&
            $event->state === $data['Events'][0]['State'];
    });
});

it('filters non-deactivated events', function () {
    $data = [
        'ListID' => 'test-list-id',
        'Events' => [
            [
                'Type' => 'Subscribe',
                'EmailAddress' => 'test@example.com',
                'Name' => 'Test User',
                'State' => 'Subscribed',
                'Date' => now()->toIso8601String(),
                'CustomFields' => [],
            ],
        ],
    ];

    postJson('/webhooks/campaign-monitor/deactivated-subscriber', $data)
        ->assertSuccessful();

    Event::assertNotDispatched(CampaignMonitorSubscriberDeactivatedEvent::class);
});

it('filters events with empty email addresses', function () {
    $data = [
        'ListID' => 'test-list-id',
        'Events' => [
            [
                'Type' => WebhookEvent::Deactivate->value,
                'EmailAddress' => '',
                'Name' => 'Test User',
                'State' => 'Unsubscribed',
                'Date' => now()->toIso8601String(),
                'CustomFields' => [],
            ],
        ],
    ];

    postJson('/webhooks/campaign-monitor/deactivated-subscriber', $data)
        ->assertSuccessful();

    Event::assertNotDispatched(CampaignMonitorSubscriberDeactivatedEvent::class);
});

it('handles missing list id', function () {
    $data = [
        'ListID' => null,
        'Events' => [
            [
                'Type' => WebhookEvent::Deactivate->value,
                'EmailAddress' => 'test@example.com',
                'Name' => 'Test User',
                'State' => 'Unsubscribed',
                'Date' => now()->toIso8601String(),
                'CustomFields' => [],
            ],
        ],
    ];

    postJson('/webhooks/campaign-monitor/deactivated-subscriber', $data)
        ->assertSuccessful();

    Event::assertNotDispatched(CampaignMonitorSubscriberDeactivatedEvent::class);
});

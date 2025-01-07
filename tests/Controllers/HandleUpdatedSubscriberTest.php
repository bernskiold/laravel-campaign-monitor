<?php

use Bernskiold\LaravelCampaignMonitor\Enum\WebhookEvent;
use Bernskiold\LaravelCampaignMonitor\Events\CampaignMonitorSubscriberUpdatedEvent;
use Illuminate\Support\Facades\Event;

use function Pest\Laravel\postJson;
use function Pest\Laravel\withoutMiddleware;

beforeEach(function () {
    withoutMiddleware();
    Event::fake();
});

it('handles a successful update event', function () {
    $data = [
        'ListID' => 'test-list-id',
        'Events' => [
            [
                'Type' => WebhookEvent::Update->value,
                'EmailAddress' => 'test@example.com',
                'OldEmailAddress' => 'test@example.org',
                'Name' => 'Test User',
                'State' => 'Unsubscribed',
                'Date' => now()->toIso8601String(),
                'CustomFields' => [],
            ],
        ],
    ];

    postJson('/webhooks/campaign-monitor/updated-subscriber', $data)
        ->assertSuccessful();

    Event::assertDispatched(CampaignMonitorSubscriberUpdatedEvent::class, function ($event) use ($data) {
        return $event->listId === $data['ListID'] &&
            $event->email === $data['Events'][0]['EmailAddress'] &&
            $event->name === $data['Events'][0]['Name'] &&
            $event->state === $data['Events'][0]['State'];
    });
});

it('filters non-updated events', function () {
    $data = [
        'ListID' => 'test-list-id',
        'Events' => [
            [
                'Type' => 'NotUpdate',
                'EmailAddress' => 'test@example.com',
                'OldEmailAddress' => 'test@example.org',
                'Name' => 'Test User',
                'State' => 'Unsubscribed',
                'Date' => now()->toIso8601String(),
                'CustomFields' => [],
            ],
        ],
    ];

    postJson('/webhooks/campaign-monitor/updated-subscriber', $data)
        ->assertSuccessful();

    Event::assertNotDispatched(CampaignMonitorSubscriberUpdatedEvent::class);
});

it('filters events with empty email addresses', function () {
    $data = [
        'ListID' => 'test-list-id',
        'Events' => [
            [
                'Type' => WebhookEvent::Update->value,
                'EmailAddress' => '',
                'OldEmailAddress' => '',
                'Name' => 'Test User',
                'State' => 'Unsubscribed',
                'Date' => now()->toIso8601String(),
                'CustomFields' => [],
            ],
        ],
    ];

    postJson('/webhooks/campaign-monitor/updated-subscriber', $data)
        ->assertSuccessful();

    Event::assertNotDispatched(CampaignMonitorSubscriberUpdatedEvent::class);
});

<?php

use BernskioldMedia\LaravelCampaignMonitor\Controllers\HandleDeactivatedSubscriber;
use BernskioldMedia\LaravelCampaignMonitor\Enum\WebhookEvent;
use BernskioldMedia\LaravelCampaignMonitor\Events\CampaignMonitorSubscriberDeactivatedEvent;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;

use function Pest\Laravel\post;

beforeEach(function () {
    Event::fake();
    Config::set('campaign-monitor.webhooks.enabled', true);
    Config::set('campaign-monitor.webhooks.routes.deactivatedSubscriber.enabled', true);
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

    post('/webhooks/campaign-monitor/deactivated-subscriber', $data)
        ->assertSuccessful();

    Event::assertDispatched(CampaignMonitorSubscriberDeactivatedEvent::class, function ($event) use ($data) {
        return $event->listId === $data['ListID'] &&
               $event->email === $data['Events'][0]['EmailAddress'] &&
               $event->name === $data['Events'][0]['Name'] &&
               $event->state === $data['Events'][0]['State'];
    });
})->skip();

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

    $request = makeIncomingJsonRequest('/webhook', $data, 'POST');
    $controller = new HandleDeactivatedSubscriber;
    $controller($request);

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

    $request = makeIncomingJsonRequest('/webhook', $data, 'POST');
    $controller = new HandleDeactivatedSubscriber;
    $controller($request);

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

    $request = makeIncomingJsonRequest('/webhook', $data, 'POST');
    $controller = new HandleDeactivatedSubscriber;
    $response = $controller($request);

    expect($response->getStatusCode())->toBe(200);
    Event::assertNotDispatched(CampaignMonitorSubscriberDeactivatedEvent::class);
});

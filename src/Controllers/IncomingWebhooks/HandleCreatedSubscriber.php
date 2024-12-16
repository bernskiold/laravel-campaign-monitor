<?php

namespace BernskioldMedia\LaravelCampaignMonitor\Controllers\IncomingWebhooks;

use BernskioldMedia\LaravelCampaignMonitor\Enum\WebhookEvent;
use BernskioldMedia\LaravelCampaignMonitor\Events\CampaignMonitorSubscriberSubscribedEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

use function dispatch;

class HandleCreatedSubscriber
{
    public function __invoke(Request $request)
    {
        $body = json_decode($request->getContent(), true);
        $listId = $body['ListID'];

        // If there is no list ID, we return early,
        // but with a positive response to Campaign Monitor.
        if (! $listId) {
            return response();
        }

        collect($body['Events'])
            ->filter(fn ($event) => $event['Type'] === WebhookEvent::Subscribe->value)
            ->filter(fn ($event) => ! empty($event['EmailAddress']))
            ->each(function ($event) use ($listId) {
                $date = Carbon::make($event['Date']);

                dispatch(new CampaignMonitorSubscriberSubscribedEvent(
                    listId: $listId,
                    email: $event['EmailAddress'],
                    name: $event['Name'] ?? null,
                    date: $date,
                    ipAddress: $event['SignupIPAddress'] ?? null,
                    customFields: $event['CustomFields'] ?? [],
                ));
            });
    }
}

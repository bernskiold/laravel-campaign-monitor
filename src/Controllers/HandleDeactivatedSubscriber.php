<?php

namespace BernskioldMedia\LaravelCampaignMonitor\Controllers;

use BernskioldMedia\LaravelCampaignMonitor\Enum\WebhookEvent;
use BernskioldMedia\LaravelCampaignMonitor\Events\CampaignMonitorSubscriberDeactivatedEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class HandleDeactivatedSubscriber
{
    public function __invoke(Request $request)
    {
        $body = json_decode($request->getContent(), true);
        $listId = $body['ListID'];

        // If there is no list ID, we return early,
        // but with a positive response to Campaign Monitor.
        if (! $listId) {
            return response('', 200);
        }

        collect($body['Events'])
            ->filter(fn ($event) => $event['Type'] === WebhookEvent::Deactivate->value)
            ->filter(fn ($event) => ! empty($event['EmailAddress']))
            ->each(function ($event) use ($listId) {
                $date = Carbon::make($event['Date']);

                event(new CampaignMonitorSubscriberDeactivatedEvent(
                    listId: $listId,
                    email: $event['EmailAddress'],
                    name: $event['Name'] ?? null,
                    state: $event['State'],
                    date: $date,
                    customFields: $event['CustomFields'] ?? [],
                ));
            });
    }
}

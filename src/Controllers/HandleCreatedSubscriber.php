<?php

namespace Bernskiold\LaravelCampaignMonitor\Controllers;

use Bernskiold\LaravelCampaignMonitor\Enum\WebhookEvent;
use Bernskiold\LaravelCampaignMonitor\Events\CampaignMonitorSubscriberSubscribedEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class HandleCreatedSubscriber
{
    public function __invoke(Request $request)
    {
        $body = json_decode($request->getContent(), true);
        $listId = $body['ListID'] ?? null;

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

                event(new CampaignMonitorSubscriberSubscribedEvent(
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

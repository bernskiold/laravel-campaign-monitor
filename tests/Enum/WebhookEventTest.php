<?php

use BernskioldMedia\LaravelCampaignMonitor\Enum\WebhookEvent;

it('can return a select array', function() {
    expect(WebhookEvent::asSelectArray())->toEqual([
        'Subscribe',
        'Deactivate',
        'Update',
    ]);
});

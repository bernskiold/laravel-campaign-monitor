<?php

namespace Bernskiold\LaravelCampaignMonitor\Enum;

enum WebhookEvent: string
{
    case Subscribe = 'Subscribe';
    case Deactivate = 'Deactivate';
    case Update = 'Update';

    public static function asSelectArray(): array
    {
        return array_map(static fn (self $event) => $event->value, self::cases());
    }
}

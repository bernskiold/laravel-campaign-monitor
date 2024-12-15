<?php

namespace BernskioldMedia\LaravelCampaignMonitor\Enum;

enum CustomFieldType: string
{
    case Text = 'Text';
    case Number = 'Number';
    case MultiSelectOne = 'MultiSelectOne';
    case MultiSelectMany = 'MultiSelectMany';
    case Date = 'Date';
    case Country = 'Country';
    case USState = 'USState';

    public static function supportsOptions(): array
    {
        return [
            self::MultiSelectOne,
            self::MultiSelectMany,
        ];
    }
}

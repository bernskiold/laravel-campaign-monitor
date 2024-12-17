<?php

use BernskioldMedia\LaravelCampaignMonitor\Enum\CustomFieldType;

test('only multi-selects support options', function() {
    expect(CustomFieldType::supportsOptions())->toEqual([
        CustomFieldType::MultiSelectOne,
        CustomFieldType::MultiSelectMany,
    ]);
});

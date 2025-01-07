<?php

use Bernskiold\LaravelCampaignMonitor\Enum\CustomFieldType;

test('only multi-selects support options', function () {
    expect(CustomFieldType::supportsOptions())->toEqual([
        CustomFieldType::MultiSelectOne,
        CustomFieldType::MultiSelectMany,
    ]);
});

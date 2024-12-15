<?php

use BernskioldMedia\LaravelCampaignMonitor\Data\ListCustomField;
use BernskioldMedia\LaravelCampaignMonitor\Data\ListCustomFields;

it('can get fields', function () {

    $field = ListCustomField::make()
        ->name('Test Field');

    $fields = ListCustomFields::make([
        $field,
    ]);

    expect($fields->all())->toBe([
        $field,
    ]);

});

it('can add field to the list', function () {

    $field = ListCustomField::make()
        ->name('Test Field');

    $fields = ListCustomFields::make()
        ->add($field);

    expect($fields->all())->toBe([
        $field,
    ]);

});

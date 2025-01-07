<?php

use Bernskiold\LaravelCampaignMonitor\Data\ListCustomField;

it('can build text field', function () {
    $details = ListCustomField::make()
        ->name('Test Field')
        ->text();

    expect($details->toApiRequest())
        ->toBe([
            'FieldName' => 'Test Field',
            'DataType' => 'Text',
            'VisibleInPreferenceCenter' => false,
        ]);
});

it('can build number field', function () {
    $details = ListCustomField::make()
        ->name('Test Field')
        ->number();

    expect($details->toApiRequest())
        ->toBe([
            'FieldName' => 'Test Field',
            'DataType' => 'Number',
            'VisibleInPreferenceCenter' => false,
        ]);
});

it('can build date field', function () {
    $details = ListCustomField::make()
        ->name('Test Field')
        ->date();

    expect($details->toApiRequest())
        ->toBe([
            'FieldName' => 'Test Field',
            'DataType' => 'Date',
            'VisibleInPreferenceCenter' => false,
        ]);
});

it('can build multi select (one option) field', function () {
    $details = ListCustomField::make()
        ->name('Test Field')
        ->options(['Option 1', 'Option 2'])
        ->multiSelectOne();

    expect($details->toApiRequest())
        ->toBe([
            'FieldName' => 'Test Field',
            'DataType' => 'MultiSelectOne',
            'VisibleInPreferenceCenter' => false,
            'Options' => ['Option 1', 'Option 2'],
        ]);
});

it('can build multi select (many options) field', function () {
    $details = ListCustomField::make()
        ->name('Test Field')
        ->options(['Option 1', 'Option 2'])
        ->multiSelectMany();

    expect($details->toApiRequest())
        ->toBe([
            'FieldName' => 'Test Field',
            'DataType' => 'MultiSelectMany',
            'VisibleInPreferenceCenter' => false,
            'Options' => ['Option 1', 'Option 2'],
        ]);
});

it('can build country field', function () {
    $details = ListCustomField::make()
        ->name('Test Field')
        ->country();

    expect($details->toApiRequest())
        ->toBe([
            'FieldName' => 'Test Field',
            'DataType' => 'Country',
            'VisibleInPreferenceCenter' => false,
        ]);
});

it('can build US state field', function () {
    $details = ListCustomField::make()
        ->name('Test Field')
        ->usState();

    expect($details->toApiRequest())
        ->toBe([
            'FieldName' => 'Test Field',
            'DataType' => 'USState',
            'VisibleInPreferenceCenter' => false,
        ]);
});

it('can make field visible in preference center', function () {
    $details = ListCustomField::make()
        ->name('Test Field')
        ->visibleInPreferenceCenter(true);

    expect($details->toApiRequest())
        ->toBe([
            'FieldName' => 'Test Field',
            'DataType' => 'Text',
            'VisibleInPreferenceCenter' => true,
        ]);
});

<?php

use BernskioldMedia\LaravelCampaignMonitor\Data\ListDetails;

it('can build a basic request', function () {
    $details = ListDetails::make()->title('My List');

    expect($details->toApiRequest())->toBe([
        'Title' => 'My List',
        'UnsubscribeSetting' => 'OnlyThisList',
        'ConfirmedOptIn' => false,
    ]);
});

it('can customize unsubscribe page', function () {
    $details = ListDetails::make()
        ->title('My List')
        ->unsubscribePage('https://example.org/unsubscribe');

    expect($details->toApiRequest())->toBe([
        'Title' => 'My List',
        'UnsubscribeSetting' => 'OnlyThisList',
        'ConfirmedOptIn' => false,
        'UnsubscribePage' => 'https://example.org/unsubscribe',
    ]);
});

it('can customize subscription page', function () {
    $details = ListDetails::make()
        ->title('My List')
        ->subscriptionConfirmationPage('https://example.org/success');

    expect($details->toApiRequest())->toBe([
        'Title' => 'My List',
        'UnsubscribeSetting' => 'OnlyThisList',
        'ConfirmedOptIn' => false,
        'ConfirmationSuccessPage' => 'https://example.org/success',
    ]);
});

it('can enable double-opt in', function () {
    $details = ListDetails::make()
        ->title('My List')
        ->confirmedOptIn();

    expect($details->toApiRequest())->toBe([
        'Title' => 'My List',
        'UnsubscribeSetting' => 'OnlyThisList',
        'ConfirmedOptIn' => true,
    ]);
});

it('can unsubscribe from all lists', function () {
    $details = ListDetails::make()
        ->title('My List')
        ->unsubscribeFromAllLists();

    expect($details->toApiRequest())->toBe([
        'Title' => 'My List',
        'UnsubscribeSetting' => 'AllClientLists',
        'ConfirmedOptIn' => false,
    ]);
});

it('can unsubscribe from list only', function () {
    $details = ListDetails::make()
        ->title('My List')
        ->unsubscribeFromListOnly();

    expect($details->toApiRequest())->toBe([
        'Title' => 'My List',
        'UnsubscribeSetting' => 'OnlyThisList',
        'ConfirmedOptIn' => false,
    ]);
});

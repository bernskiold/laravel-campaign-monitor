<?php

use Bernskiold\LaravelCampaignMonitor\Data\SubscriberDetails;

use function Pest\Laravel\freezeTime;

it('can get all details', function () {
    freezeTime();

    $details = SubscriberDetails::make()
        ->email('test@example.org')
        ->name('Test User')
        ->mobileNumber('+1234567890')
        ->customField('custom_field', 'value')
        ->customField('date_field', now())
        ->consentsToTracking()
        ->consentsToSendSms();

    expect($details->toApiRequest())
        ->toBe([
            'EmailAddress' => 'test@example.org',
            'Name' => 'Test User',
            'ConsentToTrack' => 'Yes',
            'ConsentToSendSms' => 'Yes',
            'CustomFields' => [
                [
                    'Key' => 'custom_field',
                    'Value' => 'value',
                ],
                [
                    'Key' => 'date_field',
                    'Value' => now()->format('Y-m-d'),
                ],
            ],
            'MobileNumber' => '+1234567890',
        ]);
});

it('can get all details without mobile number', function () {
    $details = SubscriberDetails::make()
        ->email('test@example.org')
        ->name('Test User')
        ->consentsToTracking()
        ->consentsToSendSms();

    expect($details->toApiRequest())
        ->toBe([
            'EmailAddress' => 'test@example.org',
            'Name' => 'Test User',
            'ConsentToTrack' => 'Yes',
            'ConsentToSendSms' => 'Yes',
        ]);
});

it('can get with negative consents', function () {
    $details = SubscriberDetails::make()
        ->email('test@example.org')
        ->name('Test User')
        ->consentsToTracking(false)
        ->consentsToSendSms(false);

    expect($details->toApiRequest())
        ->toBe([
            'EmailAddress' => 'test@example.org',
            'Name' => 'Test User',
            'ConsentToTrack' => 'No',
            'ConsentToSendSms' => 'No',
        ]);
});

it('defaults to no consents', function () {
    $details = SubscriberDetails::make()
        ->email('test@example.org');

    expect($details->consentToSendSms)->toBeFalse()
        ->and($details->consentToTrack)->toBeFalse();
});

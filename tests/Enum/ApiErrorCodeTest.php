<?php

use Bernskiold\LaravelCampaignMonitor\Enum\ApiErrorCode;

it('can be resolved from a response', function () {
    $response = new CS_REST_Wrapper_Result(
        response: (object) [
            'Code' => 203,
            'Message' => 'Subscriber not in list or has already been removed.',
        ],
        code: 400
    );

    expect(ApiErrorCode::fromResponse($response))->toBe(ApiErrorCode::SubscriberNotInList);
});

it('can be resolved from a response with a numeric string code', function () {
    $response = new CS_REST_Wrapper_Result(
        response: (object) ['Code' => '203'],
        code: 400
    );

    expect(ApiErrorCode::fromResponse($response))->toBe(ApiErrorCode::SubscriberNotInList);
});

it('returns null when the response has no known error code', function ($response) {
    $result = new CS_REST_Wrapper_Result(
        response: $response,
        code: 400
    );

    expect(ApiErrorCode::fromResponse($result))->toBeNull();
})->with([
    'string response' => 'Something went wrong.',
    'empty response' => '',
    'object without a code' => fn () => (object) ['Message' => 'Something went wrong.'],
    'unknown code' => fn () => (object) ['Code' => 999999],
]);

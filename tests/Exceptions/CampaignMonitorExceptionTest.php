<?php

use Bernskiold\LaravelCampaignMonitor\Exceptions\CampaignMonitorException;

it('can instantiate missing API Key', function () {
    throw CampaignMonitorException::missingApiKey();
})->throws(CampaignMonitorException::class, 'No API key was provided for Campaign Monitor (CAMPAIGN_MONITOR_API_KEY).');

it('can instantiate from response', function () {
    $response = new CS_REST_Wrapper_Result(
        response: (object) ['Message' => 'This is a test message.'],
        code: 500
    );

    throw CampaignMonitorException::fromResponse($response);
})->throws(CampaignMonitorException::class, 'This is a test message');

it('provides a default message if none is provided', function () {
    $response = new CS_REST_Wrapper_Result(
        response: '',
        code: 500
    );

    throw CampaignMonitorException::fromResponse($response);
})->throws(CampaignMonitorException::class, 'Campaign Monitor responded with an error.');

it('can check if the response is a rate limit exceeded response', function ($code) {
    $response = new CS_REST_Wrapper_Result(
        response: '',
        code: $code
    );

    $exception = CampaignMonitorException::fromResponse($response);

    expect($exception->hasExceededRateLimit())->toBeTrue();
})->with(['429', 429]);

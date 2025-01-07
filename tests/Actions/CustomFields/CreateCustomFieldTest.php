<?php

use Bernskiold\LaravelCampaignMonitor\Actions\CustomFields\CreateCustomField;
use Bernskiold\LaravelCampaignMonitor\Facades\CampaignMonitor;
use Illuminate\Support\Facades\Config;

beforeEach(function () {
    Config::set('campaign-monitor.apiKey', 'test-api');
});

it('returns the response on success', function () {
    CampaignMonitor::shouldReceive('lists->create_custom_field')
        ->andReturn(new CS_REST_Wrapper_Result(
            response: 'test',
            code: 200,
        ));

    $response = app(CreateCustomField::class)->execute(
        listId: 'list-id',
        data: [
            'key' => 'value',
        ],
    );

    expect($response)->toBe('test');
});

it('returns an exception if not successful', function () {
    CampaignMonitor::shouldReceive('lists->create_custom_field')
        ->andReturn(new CS_REST_Wrapper_Result(
            response: 'test',
            code: 400,
        ));

    app(CreateCustomField::class)->execute(
        listId: 'list-id',
        data: [
            'key' => 'value',
        ],
    );
})
    ->throws(Bernskiold\LaravelCampaignMonitor\Exceptions\CampaignMonitorException::class);

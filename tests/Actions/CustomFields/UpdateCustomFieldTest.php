<?php

use Bernskiold\LaravelCampaignMonitor\Actions\CustomFields\UpdateCustomField;
use Bernskiold\LaravelCampaignMonitor\Facades\CampaignMonitor;
use Illuminate\Support\Facades\Config;

beforeEach(function () {
    Config::set('campaign-monitor.apiKey', 'test-api');
});

it('returns the response on success', function () {
    CampaignMonitor::shouldReceive('lists->update_custom_field')
        ->andReturn(new CS_REST_Wrapper_Result(
            response: 'test',
            code: 200,
        ));

    $response = app(UpdateCustomField::class)->execute(
        listId: 'list-id',
        fieldKey: 'field-key',
        data: [
            'key' => 'value',
        ],
    );

    expect($response)->toBe('test');
});

it('returns an exception if not successful', function () {
    CampaignMonitor::shouldReceive('lists->update_custom_field')
        ->andReturn(new CS_REST_Wrapper_Result(
            response: 'test',
            code: 400,
        ));

    app(UpdateCustomField::class)->execute(
        listId: 'list-id',
        fieldKey: 'field-key',
        data: [
            'key' => 'value',
        ],
    );
})
    ->throws(Bernskiold\LaravelCampaignMonitor\Exceptions\CampaignMonitorException::class);

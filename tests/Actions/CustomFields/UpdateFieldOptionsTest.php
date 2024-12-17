<?php

use BernskioldMedia\LaravelCampaignMonitor\Actions\CustomFields\UpdateFieldOptions;
use BernskioldMedia\LaravelCampaignMonitor\Facades\CampaignMonitor;
use Illuminate\Support\Facades\Config;

beforeEach(function () {
    Config::set('campaign-monitor.apiKey', 'test-api');
});

it('returns the response on success', function () {
    CampaignMonitor::shouldReceive('lists->update_field_options')
        ->andReturn(new CS_REST_Wrapper_Result(
            response: 'test',
            code: 200,
        ));

    $response = app(UpdateFieldOptions::class)->execute(
        listId: 'list-id',
        fieldKey: 'field-key',
        options: [
            'option1',
            'option2',
        ],
    );

    expect($response)->toBe('test');
});

it('returns an exception if not successful', function () {
    CampaignMonitor::shouldReceive('lists->update_field_options')
        ->andReturn(new CS_REST_Wrapper_Result(
            response: 'test',
            code: 400,
        ));

    app(UpdateFieldOptions::class)->execute(
        listId: 'list-id',
        fieldKey: 'field-key',
        options: [
            'option1',
            'option2',
        ],
    );
})
    ->throws(BernskioldMedia\LaravelCampaignMonitor\Exceptions\CampaignMonitorException::class);

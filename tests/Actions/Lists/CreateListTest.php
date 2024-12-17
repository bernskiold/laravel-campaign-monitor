<?php

use BernskioldMedia\LaravelCampaignMonitor\Actions\Lists\CreateList;
use BernskioldMedia\LaravelCampaignMonitor\Facades\CampaignMonitor;
use Illuminate\Support\Facades\Config;

beforeEach(function () {
    Config::set('campaign-monitor.apiKey', 'test-api');
});

it('returns the response on success', function () {
    CampaignMonitor::shouldReceive('lists->create')
        ->andReturn(new CS_REST_Wrapper_Result(
            response: 'list-id',
            code: 200,
        ));

    $response = app(CreateList::class)->execute([
        'key' => 'value',
    ]);

    expect($response)->toBe('list-id');
});

it('returns an exception if not successful', function () {
    CampaignMonitor::shouldReceive('lists->create')
        ->andReturn(new CS_REST_Wrapper_Result(
            response: '',
            code: 400,
        ));

    app(CreateList::class)->execute([
        'key' => 'value',
    ]);
})
    ->throws(BernskioldMedia\LaravelCampaignMonitor\Exceptions\CampaignMonitorException::class);

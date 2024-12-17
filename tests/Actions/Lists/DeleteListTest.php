<?php

use BernskioldMedia\LaravelCampaignMonitor\Actions\Lists\DeleteList;
use BernskioldMedia\LaravelCampaignMonitor\Facades\CampaignMonitor;
use Illuminate\Support\Facades\Config;

beforeEach(function () {
    Config::set('campaign-monitor.apiKey', 'test-api');
});

it('returns the response on success', function () {
    CampaignMonitor::shouldReceive('lists->delete')
        ->andReturn(new CS_REST_Wrapper_Result(
            response: 'test',
            code: 200,
        ));

    $response = app(DeleteList::class)->execute('list-id');

    expect($response)->toBe('test');
});

it('returns an exception if not successful', function () {
    CampaignMonitor::shouldReceive('lists->delete')
        ->andReturn(new CS_REST_Wrapper_Result(
            response: 'test',
            code: 400,
        ));

    app(DeleteList::class)->execute('list-id');
})
    ->throws(BernskioldMedia\LaravelCampaignMonitor\Exceptions\CampaignMonitorException::class);

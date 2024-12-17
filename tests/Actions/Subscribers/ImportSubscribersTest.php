<?php

use BernskioldMedia\LaravelCampaignMonitor\Actions\Subscribers\ImportSubscribers;
use BernskioldMedia\LaravelCampaignMonitor\Facades\CampaignMonitor;
use Illuminate\Support\Facades\Config;

beforeEach(function () {
    Config::set('campaign-monitor.apiKey', 'test-api');
});

it('returns the response on success', function () {
    CampaignMonitor::shouldReceive('subscribers->import')
        ->andReturn(new CS_REST_Wrapper_Result(
            response: 'test',
            code: 200,
        ));

    $response = app(ImportSubscribers::class)->execute(
        listId: 'list-id',
        subscriberData: [],
        resubscribe: false,
        queueWorkflows: false,
        restartWorkflows: false,
    );

    expect($response)->toBe('test');
});

it('returns an exception if not successful', function () {
    CampaignMonitor::shouldReceive('subscribers->import')
        ->andReturn(new CS_REST_Wrapper_Result(
            response: 'test',
            code: 400,
        ));

    app(ImportSubscribers::class)->execute(
        listId: 'list-id',
        subscriberData: [],
        resubscribe: false,
        queueWorkflows: false,
        restartWorkflows: false,
    );
})
    ->throws(BernskioldMedia\LaravelCampaignMonitor\Exceptions\CampaignMonitorException::class);

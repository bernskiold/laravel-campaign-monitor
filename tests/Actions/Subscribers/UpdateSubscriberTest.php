<?php

use Bernskiold\LaravelCampaignMonitor\Actions\Subscribers\UpdateSubscriber;
use Bernskiold\LaravelCampaignMonitor\Facades\CampaignMonitor;
use Illuminate\Support\Facades\Config;

beforeEach(function () {
    Config::set('campaign-monitor.apiKey', 'test-api');
});

it('returns the response on success', function () {
    CampaignMonitor::shouldReceive('subscribers->update')
        ->andReturn(new CS_REST_Wrapper_Result(
            response: 'test',
            code: 200,
        ));

    $response = app(UpdateSubscriber::class)->execute(
        listId: 'list-id',
        email: 'test@exmaple.org',
        data: [],
        resubscribe: false,
        restartWorkflows: false,
    );

    expect($response)->toBe('test');
});

it('returns an exception if not successful', function () {
    CampaignMonitor::shouldReceive('subscribers->update')
        ->andReturn(new CS_REST_Wrapper_Result(
            response: 'test',
            code: 400,
        ));

    app(UpdateSubscriber::class)->execute(
        listId: 'list-id',
        email: 'test@exmaple.org',
        data: [],
        resubscribe: false,
        restartWorkflows: false,
    );
})
    ->throws(Bernskiold\LaravelCampaignMonitor\Exceptions\CampaignMonitorException::class);

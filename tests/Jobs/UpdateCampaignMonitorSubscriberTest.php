<?php

use Bernskiold\LaravelCampaignMonitor\Actions\Subscribers\UpdateSubscriber;
use Bernskiold\LaravelCampaignMonitor\Exceptions\CampaignMonitorException;
use Bernskiold\LaravelCampaignMonitor\Jobs\UpdateCampaignMonitorSubscriber;
use Bernskiold\LaravelCampaignMonitor\Tests\Fixtures\SubscriberModel;
use Illuminate\Support\Facades\Config;

it('runs successfully', function () {
    Config::set('campaign-monitor.enabled', true);

    $this->mock(UpdateSubscriber::class)
        ->shouldReceive('execute')
        ->andReturn('success');

    $job = (new UpdateCampaignMonitorSubscriber(
        model: SubscriberModel::make(),
        listId: 'list-id',
        resubscribe: false,
        restartWorkflows: false,
    ))->withFakeQueueInteractions();

    $job->handle(
        app(UpdateSubscriber::class),
    );

    $job->assertNotFailed();
    expect($job->queue)->toBe('campaign-monitor');
});

it('releases the job when rate limit exceeded', function () {
    Config::set('campaign-monitor.enabled', true);

    $this->mock(UpdateSubscriber::class)
        ->shouldReceive('execute')
        ->andThrow(new CampaignMonitorException('Rate limit exceeded', 429));

    $job = (new UpdateCampaignMonitorSubscriber(
        model: SubscriberModel::make(),
        listId: 'list-id',
    ))->withFakeQueueInteractions();
    $job->handle(
        app(UpdateSubscriber::class),
    );

    $job->assertReleased(60);
});

it('fails the job when another Campaign Monitor exception is thrown', function () {
    Config::set('campaign-monitor.enabled', true);

    $this->mock(UpdateSubscriber::class)
        ->shouldReceive('execute')
        ->andThrow(new CampaignMonitorException('Some other error', 500));

    $job = (new UpdateCampaignMonitorSubscriber(
        model: SubscriberModel::make(),
        listId: 'list-id',
    ))->withFakeQueueInteractions();
    $job->handle(
        app(UpdateSubscriber::class),
    );

    $job->assertFailed();
});

it('fails the job when another exception is thrown', function () {
    Config::set('campaign-monitor.enabled', true);

    $this->mock(UpdateSubscriber::class)
        ->shouldReceive('execute')
        ->andThrow(new Exception('Some other error'));

    $job = (new UpdateCampaignMonitorSubscriber(
        model: SubscriberModel::make(),
        listId: 'list-id',
    ))->withFakeQueueInteractions();
    $job->handle(
        app(UpdateSubscriber::class),
    );

    $job->assertFailed();
});

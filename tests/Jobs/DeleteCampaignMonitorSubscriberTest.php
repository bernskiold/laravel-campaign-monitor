<?php

use Bernskiold\LaravelCampaignMonitor\Actions\Subscribers\DeleteSubscriber;
use Bernskiold\LaravelCampaignMonitor\Exceptions\CampaignMonitorException;
use Bernskiold\LaravelCampaignMonitor\Jobs\DeleteCampaignMonitorSubscriber;
use Bernskiold\LaravelCampaignMonitor\Tests\Fixtures\SubscriberModel;
use Illuminate\Support\Facades\Config;

it('runs successfully', function () {
    Config::set('campaign-monitor.enabled', true);

    $this->mock(DeleteSubscriber::class)
        ->shouldReceive('execute')
        ->andReturn('success');

    $job = (new DeleteCampaignMonitorSubscriber(
        model: SubscriberModel::make(),
        listId: 'list-id',
    ))->withFakeQueueInteractions();

    $job->handle(
        app(DeleteSubscriber::class),
    );

    $job->assertNotFailed();
    expect($job->queue)->toBe('campaign-monitor');
});

it('releases the job when rate limit exceeded', function () {
    Config::set('campaign-monitor.enabled', true);

    $this->mock(DeleteSubscriber::class)
        ->shouldReceive('execute')
        ->andThrow(new CampaignMonitorException('Rate limit exceeded', 429));

    $job = (new DeleteCampaignMonitorSubscriber(
        model: SubscriberModel::make(),
        listId: 'list-id',
    ))->withFakeQueueInteractions();
    $job->handle(
        app(DeleteSubscriber::class),
    );

    $job->assertReleased(60);
});

it('fails the job when another Campaign Monitor exception is thrown', function () {
    Config::set('campaign-monitor.enabled', true);

    $this->mock(DeleteSubscriber::class)
        ->shouldReceive('execute')
        ->andThrow(new CampaignMonitorException('Some other error', 500));

    $job = (new DeleteCampaignMonitorSubscriber(
        model: SubscriberModel::make(),
        listId: 'list-id',
    ))->withFakeQueueInteractions();
    $job->handle(
        app(DeleteSubscriber::class),
    );

    $job->assertFailed();
});

it('fails the job when another exception is thrown', function () {
    Config::set('campaign-monitor.enabled', true);

    $this->mock(DeleteSubscriber::class)
        ->shouldReceive('execute')
        ->andThrow(new Exception('Some other error'));

    $job = (new DeleteCampaignMonitorSubscriber(
        model: SubscriberModel::make(),
        listId: 'list-id',
    ))->withFakeQueueInteractions();
    $job->handle(
        app(DeleteSubscriber::class),
    );

    $job->assertFailed();
});

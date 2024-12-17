<?php

use BernskioldMedia\LaravelCampaignMonitor\Actions\Subscribers\Subscribe;
use BernskioldMedia\LaravelCampaignMonitor\Exceptions\CampaignMonitorException;
use BernskioldMedia\LaravelCampaignMonitor\Jobs\CreateCampaignMonitorSubscriber;
use BernskioldMedia\LaravelCampaignMonitor\Tests\Fixtures\SubscriberModel;
use Illuminate\Support\Facades\Config;

it('runs successfully', function () {
    Config::set('campaign-monitor.enabled', true);

    $this->mock(Subscribe::class)
        ->shouldReceive('execute')
        ->andReturnNull();

    $job = (new CreateCampaignMonitorSubscriber(
        model: SubscriberModel::make(),
        listId: 'list-id',
        resubscribe: false,
        restartWorkflows: false,
    ))->withFakeQueueInteractions();

    $job->handle(
        app(Subscribe::class),
    );

    $job->assertNotFailed();
    expect($job->queue)->toBe('campaign-monitor');
});

it('releases the job when rate limit exceeded', function () {
    Config::set('campaign-monitor.enabled', true);

    $this->mock(Subscribe::class)
        ->shouldReceive('execute')
        ->andThrow(new CampaignMonitorException('Rate limit exceeded', 429));

    $job = (new CreateCampaignMonitorSubscriber(SubscriberModel::make(), 'list-id'))->withFakeQueueInteractions();
    $job->handle(
        app(Subscribe::class),
    );

    $job->assertReleased(60);
});

it('fails the job when another Campaign Monitor exception is thrown', function () {
    Config::set('campaign-monitor.enabled', true);

    $this->mock(Subscribe::class)
        ->shouldReceive('execute')
        ->andThrow(new CampaignMonitorException('Some other error', 500));

    $job = (new CreateCampaignMonitorSubscriber(SubscriberModel::make(), 'list-id'))->withFakeQueueInteractions();
    $job->handle(
        app(Subscribe::class),
    );

    $job->assertFailed();
});

it('fails the job when another exception is thrown', function () {
    Config::set('campaign-monitor.enabled', true);

    $this->mock(Subscribe::class)
        ->shouldReceive('execute')
        ->andThrow(new Exception('Some other error'));

    $job = (new CreateCampaignMonitorSubscriber(SubscriberModel::make(), 'list-id'))->withFakeQueueInteractions();
    $job->handle(
        app(Subscribe::class),
    );

    $job->assertFailed();
});

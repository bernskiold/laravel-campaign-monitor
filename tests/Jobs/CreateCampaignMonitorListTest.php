<?php

use BernskioldMedia\LaravelCampaignMonitor\Actions\CustomFields\CreateCustomField;
use BernskioldMedia\LaravelCampaignMonitor\Actions\Lists\CreateList;
use BernskioldMedia\LaravelCampaignMonitor\Events\CampaignMonitorListCreatedEvent;
use BernskioldMedia\LaravelCampaignMonitor\Exceptions\CampaignMonitorException;
use BernskioldMedia\LaravelCampaignMonitor\Jobs\CreateCampaignMonitorList;
use BernskioldMedia\LaravelCampaignMonitor\Tests\Fixtures\ListModel;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;

beforeEach(function () {
    Event::fake();
});

it('runs successfully', function () {
    Config::set('campaign-monitor.enabled', true);

    $this->mock(CreateList::class)
        ->shouldReceive('execute')
        ->andReturn('list-id');

    $this->mock(CreateCustomField::class)
        ->shouldReceive('execute')
        ->andReturnNull();

    $job = (new CreateCampaignMonitorList(ListModel::make()))->withFakeQueueInteractions();
    $job->handle(
        app(CreateList::class),
        app(CreateCustomField::class)
    );

    $job->assertNotFailed();
    expect($job->queue)->toBe('campaign-monitor');

    Event::assertDispatched(CampaignMonitorListCreatedEvent::class);
});

it('releases the job when rate limit exceeded', function () {
    Config::set('campaign-monitor.enabled', true);

    $this->mock(CreateList::class)
        ->shouldReceive('execute')
        ->andThrow(new CampaignMonitorException('Rate limit exceeded', 429));

    $this->mock(CreateCustomField::class)
        ->shouldReceive('execute')
        ->andReturnNull();

    $job = (new CreateCampaignMonitorList(ListModel::make()))->withFakeQueueInteractions();
    $job->handle(
        app(CreateList::class),
        app(CreateCustomField::class)
    );

    $job->assertReleased(60);
});

it('fails the job when another Campaign Monitor exception is thrown', function () {
    Config::set('campaign-monitor.enabled', true);

    $this->mock(CreateList::class)
        ->shouldReceive('execute')
        ->andThrow(new CampaignMonitorException('Some other error', 500));

    $this->mock(CreateCustomField::class)
        ->shouldReceive('execute')
        ->andReturnNull();

    $job = (new CreateCampaignMonitorList(ListModel::make()))->withFakeQueueInteractions();
    $job->handle(
        app(CreateList::class),
        app(CreateCustomField::class)
    );

    $job->assertFailed();
});

it('fails the job when another exception is thrown', function () {
    Config::set('campaign-monitor.enabled', true);

    $this->mock(CreateList::class)
        ->shouldReceive('execute')
        ->andThrow(new Exception('Some other error'));

    $this->mock(CreateCustomField::class)
        ->shouldReceive('execute')
        ->andReturnNull();

    $job = (new CreateCampaignMonitorList(ListModel::make()))->withFakeQueueInteractions();
    $job->handle(
        app(CreateList::class),
        app(CreateCustomField::class)
    );

    $job->assertFailed();
});

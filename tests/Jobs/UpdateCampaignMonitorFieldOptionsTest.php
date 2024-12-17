<?php

use BernskioldMedia\LaravelCampaignMonitor\Actions\CustomFields\UpdateFieldOptions;
use BernskioldMedia\LaravelCampaignMonitor\Exceptions\CampaignMonitorException;
use BernskioldMedia\LaravelCampaignMonitor\Jobs\UpdateCampaignMonitorFieldOptions;
use BernskioldMedia\LaravelCampaignMonitor\Tests\Fixtures\FieldModel;
use Illuminate\Support\Facades\Config;

beforeEach(function () {
    Config::set('campaign-monitor.enabled', true);
});

it('runs successfully', function () {
    $this->mock(UpdateFieldOptions::class)
        ->shouldReceive('execute')
        ->andReturn('success');

    $job = (new UpdateCampaignMonitorFieldOptions(
        model: FieldModel::make(),
        listId: 'list-id',
    ))->withFakeQueueInteractions();

    $job->handle(
        app(UpdateFieldOptions::class),
    );

    $job->assertNotFailed();
    expect($job->queue)->toBe('campaign-monitor');
});

it('releases the job when rate limit exceeded', function () {
    $this->mock(UpdateFieldOptions::class)
        ->shouldReceive('execute')
        ->andThrow(new CampaignMonitorException('Rate limit exceeded', 429));

    $job = (new UpdateCampaignMonitorFieldOptions(
        model: FieldModel::make(),
        listId: 'list-id',
    ))->withFakeQueueInteractions();
    $job->handle(
        app(UpdateFieldOptions::class),
    );

    $job->assertReleased(60);
});

it('fails the job when another Campaign Monitor exception is thrown', function () {
    $this->mock(UpdateFieldOptions::class)
        ->shouldReceive('execute')
        ->andThrow(new CampaignMonitorException('Some other error', 500));

    $job = (new UpdateCampaignMonitorFieldOptions(
        model: FieldModel::make(),
        listId: 'list-id',
    ))->withFakeQueueInteractions();
    $job->handle(
        app(UpdateFieldOptions::class),
    );

    $job->assertFailed();
});

it('fails the job when another exception is thrown', function () {
    $this->mock(UpdateFieldOptions::class)
        ->shouldReceive('execute')
        ->andThrow(new Exception('Some other error'));

    $job = (new UpdateCampaignMonitorFieldOptions(
        model: FieldModel::make(),
        listId: 'list-id',
    ))->withFakeQueueInteractions();
    $job->handle(
        app(UpdateFieldOptions::class),
    );

    $job->assertFailed();
});

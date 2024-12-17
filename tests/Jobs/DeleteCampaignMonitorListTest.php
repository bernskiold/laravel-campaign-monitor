<?php

use BernskioldMedia\LaravelCampaignMonitor\Actions\Lists\DeleteList;
use BernskioldMedia\LaravelCampaignMonitor\Exceptions\CampaignMonitorException;
use BernskioldMedia\LaravelCampaignMonitor\Jobs\DeleteCampaignMonitorList;
use BernskioldMedia\LaravelCampaignMonitor\Tests\Fixtures\ListModel;
use Illuminate\Support\Facades\Config;

beforeEach(function () {
    $this->model = new class extends ListModel
    {
        public function getCampaignMonitorListId(): ?string
        {
            return 'list-id';
        }
    };
});

it('runs successfully', function () {
    Config::set('campaign-monitor.enabled', true);

    $this->mock(DeleteList::class)
        ->shouldReceive('execute')
        ->andReturn('success');

    $job = (new DeleteCampaignMonitorList($this->model))->withFakeQueueInteractions();

    $job->handle(
        app(DeleteList::class),
    );

    $job->assertNotFailed();
    expect($job->queue)->toBe('campaign-monitor');
});

it('releases the job when rate limit exceeded', function () {
    Config::set('campaign-monitor.enabled', true);

    $this->mock(DeleteList::class)
        ->shouldReceive('execute')
        ->andThrow(new CampaignMonitorException('Rate limit exceeded', 429));

    $job = (new DeleteCampaignMonitorList($this->model))->withFakeQueueInteractions();
    $job->handle(
        app(DeleteList::class),
    );

    $job->assertReleased(60);
});

it('fails the job when another Campaign Monitor exception is thrown', function () {
    Config::set('campaign-monitor.enabled', true);

    $this->mock(DeleteList::class)
        ->shouldReceive('execute')
        ->andThrow(new CampaignMonitorException('Some other error', 500));

    $job = (new DeleteCampaignMonitorList($this->model))->withFakeQueueInteractions();
    $job->handle(
        app(DeleteList::class),
    );

    $job->assertFailed();
});

it('fails the job when another exception is thrown', function () {
    Config::set('campaign-monitor.enabled', true);

    $this->mock(DeleteList::class)
        ->shouldReceive('execute')
        ->andThrow(new Exception('Some other error'));

    $job = (new DeleteCampaignMonitorList($this->model))->withFakeQueueInteractions();
    $job->handle(
        app(DeleteList::class),
    );

    $job->assertFailed();
});

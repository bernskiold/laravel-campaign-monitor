<?php

use Bernskiold\LaravelCampaignMonitor\Actions\CustomFields\UpdateCustomField;
use Bernskiold\LaravelCampaignMonitor\Actions\Lists\UpdateList;
use Bernskiold\LaravelCampaignMonitor\Exceptions\CampaignMonitorException;
use Bernskiold\LaravelCampaignMonitor\Jobs\UpdateCampaignMonitorList;
use Bernskiold\LaravelCampaignMonitor\Tests\Fixtures\ListModel;
use Illuminate\Support\Facades\Config;

beforeEach(function () {
    Config::set('campaign-monitor.enabled', true);

    $this->mock(UpdateCustomField::class)
        ->shouldReceive('execute')
        ->andReturn('success');

    $this->model = new class extends ListModel
    {
        public function getCampaignMonitorListId(): ?string
        {
            return 'list-id';
        }
    };
});

it('runs successfully', function () {
    $this->mock(UpdateList::class)
        ->shouldReceive('execute')
        ->andReturn('success');

    $job = (new UpdateCampaignMonitorList($this->model))->withFakeQueueInteractions();

    $job->handle(
        app(UpdateList::class),
        app(UpdateCustomField::class),
    );

    $job->assertNotFailed();
    expect($job->queue)->toBe('campaign-monitor');
});

it('releases the job when rate limit exceeded', function () {
    $this->mock(UpdateList::class)
        ->shouldReceive('execute')
        ->andThrow(new CampaignMonitorException('Rate limit exceeded', 429));

    $job = (new UpdateCampaignMonitorList($this->model))->withFakeQueueInteractions();
    $job->handle(
        app(UpdateList::class),
        app(UpdateCustomField::class),
    );

    $job->assertReleased(60);
});

it('fails the job when another Campaign Monitor exception is thrown', function () {
    $this->mock(UpdateList::class)
        ->shouldReceive('execute')
        ->andThrow(new CampaignMonitorException('Some other error', 500));

    $job = (new UpdateCampaignMonitorList($this->model))->withFakeQueueInteractions();
    $job->handle(
        app(UpdateList::class),
        app(UpdateCustomField::class),
    );

    $job->assertFailed();
});

it('fails the job when another exception is thrown', function () {
    $this->mock(UpdateList::class)
        ->shouldReceive('execute')
        ->andThrow(new Exception('Some other error'));

    $job = (new UpdateCampaignMonitorList($this->model))->withFakeQueueInteractions();
    $job->handle(
        app(UpdateList::class),
        app(UpdateCustomField::class),
    );

    $job->assertFailed();
});

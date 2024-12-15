<?php

use BernskioldMedia\LaravelCampaignMonitor\Contracts\CampaignMonitorList;
use BernskioldMedia\LaravelCampaignMonitor\Events\CampaignMonitorListCreated;
use BernskioldMedia\LaravelCampaignMonitor\Facades\CampaignMonitor;
use BernskioldMedia\LaravelCampaignMonitor\Jobs\CreateListInCampaignMonitor;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;

beforeEach(function () {
    Event::fake();
    $this->model = Mockery::mock(CampaignMonitorList::class);
    $this->model->shouldReceive('getCampaignMonitorListDetails->toApiRequest')
        ->andReturn(['Name' => 'Test List']);
    $this->model->shouldReceive('getCampaignMonitorCustomFields->all')
        ->andReturn([]);

    Config::set('campaign-monitor.apiKey', 'test-client-id');
});

it('creates list and dispatches event', function () {
    $response = Mockery::mock(CS_REST_Wrapper_Result::class);
    $response->shouldReceive('was_successful')
        ->andReturn(true);
    $response->response = 'list_id';

    CampaignMonitor::shouldReceive('lists->create')
        ->andReturn($response);

    CampaignMonitor::shouldReceive('lists->create_custom_field')
        ->andReturn(true);

    $job = new CreateListInCampaignMonitor($this->model);
    $job->handle();

    Event::assertDispatched(CampaignMonitorListCreated::class, function ($event) {
        return $event->model === $this->model && $event->listId === 'list_id';
    });
});

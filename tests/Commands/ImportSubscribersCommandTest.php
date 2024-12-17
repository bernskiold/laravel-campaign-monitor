<?php

use BernskioldMedia\LaravelCampaignMonitor\Actions\Subscribers\ImportSubscribers;
use BernskioldMedia\LaravelCampaignMonitor\Tests\Fixtures\SubscriberModelWithoutContract;

it('fails validation if the model does not exist', function () {
    $this->mock(ImportSubscribers::class)
        ->shouldReceive('execute')
        ->andReturn(true);

    $this->artisan('campaign-monitor:import-subscribers', [
        'listId' => 'list-id',
        'model' => 'App\\Models\\NonExistentModel',
    ])
        ->expectsOutput('The model does not exist.')
        ->assertFailed();
});

it('fails validation if the model does not implement the CampaignMonitorSubscriber interface', function () {
    $this->mock(ImportSubscribers::class)
        ->shouldReceive('execute')
        ->andReturn(true);

    $this->artisan('campaign-monitor:import-subscribers', [
        'listId' => 'list-id',
        'model' => SubscriberModelWithoutContract::class,
    ])
        ->expectsOutput('The model does not implement the CampaignMonitorSubscriber interface.')
        ->assertFailed();
});

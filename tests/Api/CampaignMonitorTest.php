<?php

use BernskioldMedia\LaravelCampaignMonitor\Api\CampaignMonitor;
use Illuminate\Support\Facades\Config;

beforeEach(function () {
    $this->api = new CampaignMonitor('test');
});

it('can get the active status when active in config', function () {
    Config::set('campaign-monitor.active', true);

    expect($this->api->isActive())->toBeTrue();
});

it('can get the active status when inactive in config', function () {
    Config::set('campaign-monitor.active', false);

    expect($this->api->isActive())->toBeFalse();
});

it('the active status is false by default', function () {
    expect($this->api->isActive())->toBeFalse();
});

it('can return the account SDK', function () {
    expect($this->api->account())->toBeInstanceOf(CS_REST_General::class);
});

it('can return the campaigns SDK', function () {
    expect($this->api->campaigns())->toBeInstanceOf(CS_REST_Campaigns::class);
});

it('can return the clients SDK', function () {
    expect($this->api->clients())->toBeInstanceOf(CS_REST_Clients::class);
});

it('can return the journeys SDK', function () {
    expect($this->api->journeys())->toBeInstanceOf(CS_REST_Journeys::class);
});

it('can return the lists SDK', function () {
    expect($this->api->lists())->toBeInstanceOf(CS_REST_Lists::class);
});

it('can return the segments SDK', function () {
    expect($this->api->segments())->toBeInstanceOf(CS_REST_Segments::class);
});

it('can return the subscribers SDK', function () {
    expect($this->api->subscribers('test'))->toBeInstanceOf(CS_REST_Subscribers::class);
});

it('can return the templates SDK', function () {
    expect($this->api->templates())->toBeInstanceOf(CS_REST_Templates::class);
});

it('can return the transactional smart email SDK', function () {
    expect($this->api->transactional())->toBeInstanceOf(CS_REST_Transactional_SmartEmail::class);
});

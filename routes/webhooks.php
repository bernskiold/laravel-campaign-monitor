<?php

use Illuminate\Support\Facades\Route;

Route::middleware(config('campaign-monitor.webhooks.middleware'))
    ->prefix(config('campaign-monitor.webhooks.routePrefix'))
    ->group(function () {
        Route::post(config('campaign-monitor.webhooks.routes.updatedSubscriber.route'), config('campaign-monitor.webhooks.routes.updatedSubscriber.controller'));
        Route::post(config('campaign-monitor.webhooks.routes.createdSubscriber.route'), config('campaign-monitor.webhooks.routes.createdSubscriber.controller'));
        Route::post(config('campaign-monitor.webhooks.routes.deactivatedSubscriber.route'), config('campaign-monitor.webhooks.routes.deactivatedSubscriber.controller'));
    });

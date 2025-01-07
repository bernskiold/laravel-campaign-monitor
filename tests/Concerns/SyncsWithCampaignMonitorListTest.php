<?php

use Bernskiold\LaravelCampaignMonitor\Tests\Fixtures\ListModel;

it('can generate a unique job identifier', function () {
    $model = ListModel::make([
        'id' => 1,
    ]);

    expect($model->getCampaignMonitorUniqueJobIdentifier())
        ->toBe('bernskioldlaravelcampaignmonitortestsfixtureslistmodel-1');
});

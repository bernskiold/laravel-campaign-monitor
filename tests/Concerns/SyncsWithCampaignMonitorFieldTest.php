<?php

use Bernskiold\LaravelCampaignMonitor\Tests\Fixtures\FieldModel;

it('can generate a unique job identifier', function () {
    $model = FieldModel::make([
        'id' => 1,
    ]);

    expect($model->getCampaignMonitorUniqueJobIdentifier())
        ->toBe('bernskioldlaravelcampaignmonitortestsfixturesfieldmodel');
});

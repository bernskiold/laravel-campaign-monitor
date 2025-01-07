<?php

use Bernskiold\LaravelCampaignMonitor\Tests\Fixtures\SubscriberModel;

it('can generate a unique job identifier', function () {
    $model = SubscriberModel::make([
        'id' => 1,
    ]);

    expect($model->getCampaignMonitorUniqueJobIdentifier())
        ->toBe('bernskioldlaravelcampaignmonitortestsfixturessubscribermodel-1');
});

<?php

namespace Bernskiold\LaravelCampaignMonitor\Tests\Fixtures;

use Bernskiold\LaravelCampaignMonitor\Concerns\SyncsWithCampaignMonitorField;
use Bernskiold\LaravelCampaignMonitor\Contracts\CampaignMonitorField;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;

class FieldModel extends Model implements CampaignMonitorField
{
    use SyncsWithCampaignMonitorField;

    protected $guarded = [];

    public function getCampaignMonitorFieldKey(): string
    {
        return 'FieldKey';
    }

    public function getCampaignMonitorOptions(): Arrayable|array
    {
        return [
            'option1',
            'option2',
        ];
    }
}

<?php

namespace BernskioldMedia\LaravelCampaignMonitor\Tests\Fixtures;

use BernskioldMedia\LaravelCampaignMonitor\Concerns\SyncsWithCampaignMonitorField;
use BernskioldMedia\LaravelCampaignMonitor\Contracts\CampaignMonitorField;
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

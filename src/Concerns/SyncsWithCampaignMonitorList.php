<?php

namespace BernskioldMedia\LaravelCampaignMonitor\Concerns;

use BernskioldMedia\LaravelCampaignMonitor\Contracts\CampaignMonitorList;
use BernskioldMedia\LaravelCampaignMonitor\Jobs\CreateCampaignMonitorList;
use BernskioldMedia\LaravelCampaignMonitor\Jobs\DeleteCampaignMonitorList;
use BernskioldMedia\LaravelCampaignMonitor\Jobs\UpdateCampaignMonitorList;

use function dispatch;
use function str;

trait SyncsWithCampaignMonitorList
{
    public function bootSyncsWithCampaignMonitorList()
    {
        static::created(function (CampaignMonitorList $model) {
            if (! $model->shouldSyncWithCampaignMonitor()) {
                return;
            }

            dispatch(new CreateCampaignMonitorList($model));
        });

        static::updated(function (CampaignMonitorList $model) {
            if (! $model->shouldSyncWithCampaignMonitor()) {
                return;
            }

            dispatch(new UpdateCampaignMonitorList($model));
        });

        static::forceDeleted(function (CampaignMonitorList $model) {
            if (! $model->shouldSyncWithCampaignMonitor()) {
                return;
            }

            dispatch(new DeleteCampaignMonitorList($model));
        });
    }

    public function getCampaignMonitorUniqueJobIdentifier(): string
    {
        return str($this->getMorphClass())
            ->append('-')
            ->append($this->getKey())
            ->slug()
            ->toString();
    }

    public function shouldSyncWithCampaignMonitor(): bool
    {
        return true;
    }

    protected function getCampaignMonitorListIds(): array
    {
        return [];
    }
}

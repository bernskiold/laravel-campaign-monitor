<?php

namespace BernskioldMedia\LaravelCampaignMonitor\Concerns;

use BernskioldMedia\LaravelCampaignMonitor\Contracts\CampaignMonitorField;
use BernskioldMedia\LaravelCampaignMonitor\Jobs\UpdateCampaignMonitorFieldOptions;

use function dispatch;

trait SyncsWithCampaignMonitorField
{
    public function bootSyncsWithCampaignMonitorField()
    {
        static::created(function (CampaignMonitorField $model) {
            if (! $model->shouldSyncWithCampaignMonitor()) {
                return;
            }

            foreach ($model->getCampaignMonitorListIds() as $listId) {
                dispatch(new UpdateCampaignMonitorFieldOptions(
                    model: $model,
                    listId: $listId,
                ));
            }
        });

        static::updated(function (CampaignMonitorField $model) {
            if (! $model->shouldSyncWithCampaignMonitor()) {
                return;
            }

            foreach ($model->getCampaignMonitorListIds() as $listId) {
                dispatch(new UpdateCampaignMonitorFieldOptions(
                    model: $model,
                    listId: $listId,
                ));
            }
        });

        static::deleted(function (CampaignMonitorField $model) {
            if (! $model->shouldSyncWithCampaignMonitor()) {
                return;
            }

            foreach ($model->getCampaignMonitorListIds() as $listId) {
                dispatch(new UpdateCampaignMonitorFieldOptions(
                    model: $model,
                    listId: $listId,
                ));
            }
        });

        static::restored(function (CampaignMonitorField $model) {
            if (! $model->shouldSyncWithCampaignMonitor()) {
                return;
            }

            foreach ($model->getCampaignMonitorListIds() as $listId) {
                dispatch(new UpdateCampaignMonitorFieldOptions(
                    model: $model,
                    listId: $listId,
                ));
            }
        });
    }

    public function getCampaignMonitorUniqueJobIdentifier(): string
    {
        return str($this->getMorphClass())
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

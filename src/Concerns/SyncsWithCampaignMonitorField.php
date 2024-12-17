<?php

namespace BernskioldMedia\LaravelCampaignMonitor\Concerns;

use BernskioldMedia\LaravelCampaignMonitor\Contracts\CampaignMonitorField;
use BernskioldMedia\LaravelCampaignMonitor\Jobs\UpdateCampaignMonitorFieldOptions;
use Illuminate\Database\Eloquent\SoftDeletes;

use function class_uses;
use function dispatch;
use function in_array;

trait SyncsWithCampaignMonitorField
{
    public static function bootSyncsWithCampaignMonitorField()
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

        if (in_array(SoftDeletes::class, class_uses(static::class))) {
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

    public function getCampaignMonitorListIds(): array
    {
        return [];
    }
}

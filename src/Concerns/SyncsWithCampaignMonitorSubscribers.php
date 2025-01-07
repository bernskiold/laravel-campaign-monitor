<?php

namespace Bernskiold\LaravelCampaignMonitor\Concerns;

use Bernskiold\LaravelCampaignMonitor\Contracts\CampaignMonitorSubscriber;
use Bernskiold\LaravelCampaignMonitor\Jobs\CreateCampaignMonitorSubscriber;
use Bernskiold\LaravelCampaignMonitor\Jobs\DeleteCampaignMonitorSubscriber;
use Bernskiold\LaravelCampaignMonitor\Jobs\UnsubscribeCampaignMonitorSubscriber;
use Bernskiold\LaravelCampaignMonitor\Jobs\UpdateCampaignMonitorSubscriber;
use Illuminate\Database\Eloquent\SoftDeletes;

use function dispatch;

trait SyncsWithCampaignMonitorSubscribers
{
    public static function bootSyncsWithCampaignMonitorSubscribers()
    {
        static::created(function (CampaignMonitorSubscriber $model) {
            if (! $model->shouldSyncWithCampaignMonitor()) {
                return;
            }

            foreach ($model->getCampaignMonitorListIds() as $listId) {
                dispatch(new CreateCampaignMonitorSubscriber(
                    model: $model,
                    listId: $listId,
                    resubscribe: $model->shouldResubscribeToCampaignMonitorList($listId),
                    restartWorkflows: $model->shouldRerunWorkflowsForCampaignMonitorList($listId)
                ));
            }
        });

        static::updated(function (CampaignMonitorSubscriber $model) {
            if (! $model->shouldSyncWithCampaignMonitor()) {
                return;
            }

            foreach ($model->getCampaignMonitorListIds() as $listId) {
                dispatch(new UpdateCampaignMonitorSubscriber(
                    model: $model,
                    listId: $listId,
                    resubscribe: $model->shouldResubscribeToCampaignMonitorList($listId),
                    restartWorkflows: $model->shouldRerunWorkflowsForCampaignMonitorList($listId)
                ));
            }
        });

        static::deleted(function (CampaignMonitorSubscriber $model) {
            if (! $model->shouldSyncWithCampaignMonitor()) {
                return;
            }

            foreach ($model->getCampaignMonitorListIds() as $listId) {
                dispatch(new UnsubscribeCampaignMonitorSubscriber(
                    model: $model,
                    listId: $listId
                ));
            }
        });

        if (in_array(SoftDeletes::class, class_uses(static::class))) {
            static::forceDeleted(function (CampaignMonitorSubscriber $model) {
                if (! $model->shouldSyncWithCampaignMonitor()) {
                    return;
                }

                foreach ($model->getCampaignMonitorListIds() as $listId) {
                    dispatch(new DeleteCampaignMonitorSubscriber(
                        model: $model,
                        listId: $listId
                    ));
                }
            });

            static::restored(function (CampaignMonitorSubscriber $model) {
                if (! $model->shouldSyncWithCampaignMonitor()) {
                    return;
                }

                foreach ($model->getCampaignMonitorListIds() as $listId) {
                    dispatch(new CreateCampaignMonitorSubscriber(
                        model: $model,
                        listId: $listId,
                        resubscribe: true
                    ));
                }
            });
        }
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

    public function getCampaignMonitorListsToResubscribe(): array
    {
        return [];
    }

    public function getCampaignMonitorListsToRerunWorkflows(): array
    {
        return [];
    }

    protected function shouldResubscribeToCampaignMonitorList(string $listId): bool
    {
        return in_array($listId, $this->getCampaignMonitorListsToResubscribe(), true);
    }

    protected function shouldRerunWorkflowsForCampaignMonitorList(string $listId): bool
    {
        return in_array($listId, $this->getCampaignMonitorListsToRerunWorkflows(), true);
    }
}

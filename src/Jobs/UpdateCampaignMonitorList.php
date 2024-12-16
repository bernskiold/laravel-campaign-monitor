<?php

namespace BernskioldMedia\LaravelCampaignMonitor\Jobs;

use BernskioldMedia\LaravelCampaignMonitor\Actions\CustomFields\UpdateCustomField;
use BernskioldMedia\LaravelCampaignMonitor\Actions\Lists\UpdateList;
use BernskioldMedia\LaravelCampaignMonitor\Contracts\CampaignMonitorList;
use BernskioldMedia\LaravelCampaignMonitor\Exceptions\CampaignMonitorException;
use BernskioldMedia\LaravelCampaignMonitor\Facades\CampaignMonitor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\Skip;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;
use Throwable;

class UpdateCampaignMonitorList implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public CampaignMonitorList $model
    ) {
        $this->onQueue('campaign-monitor');
    }

    public function handle(UpdateList $updateAction, UpdateCustomField $updateFieldAction): void
    {
        try {
            $updateAction->execute(
                $this->model->getCampaignMonitorListId(),
                $this->model->getCampaignMonitorListDetails()->toApiRequest()
            );
        } catch (CampaignMonitorException $e) {
            if ($e->hasExceededRateLimit()) {
                $this->release(60);
            } else {
                $this->fail($e);
            }

            return;
        } catch (Throwable $e) {
            $this->fail($e);

            return;
        }

        $customFields = $this->model->getCampaignMonitorCustomFields();

        foreach ($customFields->all() as $field) {
            $updateFieldAction->execute(
                listId: $this->model->getCampaignMonitorListId(),
                fieldKey: $field->name,
                data: $field->toApiRequest()
            );
        }
    }

    public function middleware(): array
    {
        return [
            (new WithoutOverlapping('cm-updated-list:'.$this->model->getCampaignMonitorUniqueJobIdentifier()))
                ->releaseAfter(5)
                ->expireAfter(60),
            Skip::when($this->model->getCampaignMonitorListId() === null),
            Skip::unless($this->model->shouldSyncWithCampaignMonitor() === true),
            Skip::unless(CampaignMonitor::isActive() === true),
        ];
    }
}

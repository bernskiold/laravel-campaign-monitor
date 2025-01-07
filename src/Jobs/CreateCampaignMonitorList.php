<?php

namespace Bernskiold\LaravelCampaignMonitor\Jobs;

use Bernskiold\LaravelCampaignMonitor\Actions\CustomFields\CreateCustomField;
use Bernskiold\LaravelCampaignMonitor\Actions\Lists\CreateList;
use Bernskiold\LaravelCampaignMonitor\Contracts\CampaignMonitorList;
use Bernskiold\LaravelCampaignMonitor\Events\CampaignMonitorListCreatedEvent;
use Bernskiold\LaravelCampaignMonitor\Exceptions\CampaignMonitorException;
use Bernskiold\LaravelCampaignMonitor\Facades\CampaignMonitor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\Skip;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;
use Throwable;

class CreateCampaignMonitorList implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public CampaignMonitorList $model
    ) {
        $this->onQueue('campaign-monitor');
    }

    public function handle(CreateList $createListAction, CreateCustomField $createCustomFieldAction): void
    {
        try {
            $listId = $createListAction->execute($this->model->getCampaignMonitorListDetails()->toApiRequest());
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
            $createCustomFieldAction->execute($listId, $field->toApiRequest());
        }

        event(new CampaignMonitorListCreatedEvent($this->model, $listId));
    }

    public function middleware(): array
    {
        return [
            (new WithoutOverlapping('cm-new-list:'.$this->model->getCampaignMonitorUniqueJobIdentifier()))
                ->releaseAfter(5)
                ->expireAfter(60),
            Skip::unless($this->model->shouldSyncWithCampaignMonitor() === true),
            Skip::unless(CampaignMonitor::isActive() === true),
        ];
    }
}

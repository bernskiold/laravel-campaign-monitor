<?php

namespace BernskioldMedia\LaravelCampaignMonitor\Jobs;

use BernskioldMedia\LaravelCampaignMonitor\Contracts\CampaignMonitorList;
use BernskioldMedia\LaravelCampaignMonitor\Events\CampaignMonitorListCreated;
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

class CreateListInCampaignMonitor implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public CampaignMonitorList $model
    ) {
        $this->onQueue('campaign-monitor');
    }

    public function handle(): void
    {
        try {
            $response = CampaignMonitor::lists()->create(
                config('campaign-monitor.clientId'),
                $this->model->getCampaignMonitorListDetails()->toApiRequest()
            );

            if (! $response->was_successful()) {
                throw CampaignMonitorException::fromResponse($response);
            }
        } catch (CampaignMonitorException $e) {
            if ($e->hasExceededRateLimit()) {
                $this->release(60);
            } else {
                $this->fail($e);
            }
        } catch (Throwable $e) {
            $this->fail($e);
        }

        $listId = $response->response;
        $customFields = $this->model->getCampaignMonitorCustomFields();

        foreach ($customFields->all() as $field) {
            CampaignMonitor::lists($listId)
                ->create_custom_field($field->toApiRequest());
        }

        event(new CampaignMonitorListCreated($this->model, $listId));
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

<?php

namespace BernskioldMedia\LaravelCampaignMonitor\Jobs;

use BernskioldMedia\LaravelCampaignMonitor\Actions\Subscribers\UpdateSubscriber;
use BernskioldMedia\LaravelCampaignMonitor\Contracts\CampaignMonitorSubscriber;
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

class UpdateCampaignMonitorSubscriber implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public CampaignMonitorSubscriber $model,
        public string $listId,
        public bool $resubscribe = false,
        public bool $restartWorkflows = false,
    ) {
        $this->onQueue('campaign-monitor');
    }

    public function handle(UpdateSubscriber $updateAction): void
    {
        try {
            $updateAction->execute(
                listId: $this->listId,
                email: $this->model->getCampaignMonitorSubscriberDetails()->email,
                data: $this->model->getCampaignMonitorSubscriberDetails()->toApiRequest(),
                resubscribe: $this->resubscribe,
                restartWorkflows: $this->restartWorkflows,
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
    }

    public function middleware(): array
    {
        return [
            (new WithoutOverlapping('cm-subscribe:'.$this->model->getCampaignMonitorUniqueJobIdentifier()))
                ->releaseAfter(5)
                ->expireAfter(60),
            Skip::unless($this->model->shouldSyncWithCampaignMonitor() === true),
            Skip::unless(CampaignMonitor::isActive() === true),
        ];
    }
}

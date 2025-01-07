<?php

namespace Bernskiold\LaravelCampaignMonitor\Jobs;

use Bernskiold\LaravelCampaignMonitor\Actions\Subscribers\Subscribe;
use Bernskiold\LaravelCampaignMonitor\Contracts\CampaignMonitorSubscriber;
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

class CreateCampaignMonitorSubscriber implements ShouldQueue
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

    public function handle(Subscribe $subscribeAction): void
    {
        try {
            $subscribeAction->execute(
                listId: $this->listId,
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

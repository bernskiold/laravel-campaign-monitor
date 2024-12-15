<?php

namespace BernskioldMedia\LaravelCampaignMonitor\Jobs;

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

class UnsubscribeFromCampaignMonitor implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public CampaignMonitorSubscriber $model,
        public string $listId,
    ) {
        $this->onQueue('campaign-monitor');
    }

    public function handle(): void
    {
        try {
            $response = CampaignMonitor::subscribers($this->listId)
                ->unsubscribe($this->model->getCampaignMonitorSubscriberDetails()->email);

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
    }

    public function middleware(): array
    {
        return [
            (new WithoutOverlapping('cm-unsubscribe:'.$this->model->getCampaignMonitorUniqueJobIdentifier()))
                ->releaseAfter(5)
                ->expireAfter(60),
            Skip::unless($this->model->shouldSyncWithCampaignMonitor() === true),
            Skip::unless(CampaignMonitor::isActive() === true),
        ];
    }
}

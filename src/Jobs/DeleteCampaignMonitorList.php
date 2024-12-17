<?php

namespace BernskioldMedia\LaravelCampaignMonitor\Jobs;

use BernskioldMedia\LaravelCampaignMonitor\Actions\Lists\DeleteList;
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

class DeleteCampaignMonitorList implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public CampaignMonitorList $model
    ) {
        $this->onQueue('campaign-monitor');
    }

    public function handle(DeleteList $deleteAction): void
    {
        $listId = $this->model->getCampaignMonitorListId();

        if ($listId === null) {
            return;
        }

        try {
            $deleteAction->execute($listId);
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
            (new WithoutOverlapping('cm-delete-list:'.$this->model->getCampaignMonitorUniqueJobIdentifier()))
                ->releaseAfter(5)
                ->expireAfter(60),
            Skip::when($this->model->getCampaignMonitorListId() === null),
            Skip::unless($this->model->shouldSyncWithCampaignMonitor() === true),
            Skip::unless(CampaignMonitor::isActive() === true),
        ];
    }
}

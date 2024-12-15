<?php

namespace BernskioldMedia\LaravelCampaignMonitor\Jobs;

use BernskioldMedia\LaravelCampaignMonitor\Contracts\CampaignMonitorField;
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

class UpdateCustomFieldOptions implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public CampaignMonitorField $model,
        public string $listId,
    ) {
        $this->onQueue('campaign-monitor');
    }

    public function handle(): void
    {
        try {

            $response = CampaignMonitor::lists($this->listId)
                ->update_field_options(
                    $this->model->getCampaignMonitorFieldKey(),
                    $this->model->getCampaignMonitorOptions(),
                    false,
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
    }

    public function middleware(): array
    {
        return [
            (new WithoutOverlapping('cm-field-options:'.$this->model->getCampaignMonitorUniqueJobIdentifier()))
                ->releaseAfter(5)
                ->expireAfter(60),
            Skip::unless($this->model->shouldSyncWithCampaignMonitor() === true),
            Skip::unless(CampaignMonitor::isActive() === true),
        ];
    }
}

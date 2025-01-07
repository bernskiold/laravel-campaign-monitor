<?php

namespace Bernskiold\LaravelCampaignMonitor\Commands;

use Bernskiold\LaravelCampaignMonitor\Actions\Subscribers\ImportSubscribers;
use Bernskiold\LaravelCampaignMonitor\Contracts\CampaignMonitorSubscriber;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Throwable;

use function class_exists;
use function Laravel\Prompts\progress;
use function Laravel\Prompts\text;

class ImportSubscribersCommand extends Command
{
    protected $signature = 'campaign-monitor:import-subscribers {listId?} {model?} {--resubscribe} {--restartWorkflows} {--queueWorkflows}';

    protected $description = 'Imports subscribers from a model to a Campaign Monitor list.';

    public function handle(ImportSubscribers $importAction)
    {
        $listId = $this->argument('listId');
        $model = $this->argument('model');

        if (empty($listId)) {
            $listId = text(
                label: 'List ID',
                required: true,
                hint: 'The list ID can be found in the Campaign Monitor dashboard under the list settings.'
            );
        }

        if (empty($model)) {
            $model = text(
                label: 'Model',
                placeholder: 'eg. App\\Models\\User',
                required: true,
                hint: 'The model to import subscribers from. It should be a fully qualified class name that implements the CampaignMonitorSubscriber interface.'
            );
        }

        // Validate the model's existence.
        if (! class_exists($model)) {
            $this->error('The model does not exist.');

            return self::FAILURE;
        }

        // Validate that the model implements the CampaignMonitorSubscriber interface.
        if (! in_array(CampaignMonitorSubscriber::class, class_implements($model), true)) {
            $this->error('The model does not implement the CampaignMonitorSubscriber interface.');

            return self::FAILURE;
        }

        $this->info('Started importing subscribers to Campaign Monitor.');

        $progress = progress(
            label: 'Importing subscribers to Campaign Monitor',
            steps: $model::query()->count() / 1000,
        );

        try {

            $progress->start();

            $model::query()
                ->chunkById(1000, function (Collection $subscribers) use ($listId, $progress, $importAction) {
                    $importAction->execute(
                        listId: $listId,
                        subscriberData: $subscribers->map(function (CampaignMonitorSubscriber $subscriber) {
                            return $subscriber->getCampaignMonitorSubscriberDetails()->toApiRequest();
                        })->toArray(),
                        resubscribe: $this->option('resubscribe'),
                        queueWorkflows: $this->option('queueWorkflows'),
                        restartWorkflows: $this->option('restartWorkflows')
                    );

                    $progress->advance();
                });

            $progress->finish();
        } catch (Throwable $e) {
            $this->error('Failed to import subscribers to Campaign Monitor.');
            $this->line($e->getMessage());

            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}

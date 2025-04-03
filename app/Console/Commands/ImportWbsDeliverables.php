<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ExactDeliverables;

class ImportWbsDeliverables extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'budget-app:import-wbs-deliverables';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch WBS Deliverables and store them in the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $nextUrl = config('env.exact_url') . '/project/WBSDeliverables';
        $method = 'GET';

        do {
            $response = commonCurl($nextUrl, $method, []);

            if (isset($response->d) && isset($response->d->results)) {
                foreach ($response->d->results as $expense) {
                    ExactDeliverables::updateOrCreate(
                        ['deliverable_id' => $expense->ID],
                        [
                            'description' => $expense->Description,
                            'part_of' => $expense->PartOf,
                            'part_of_description' => $expense->PartOfDescription,
                            'project_id' => $expense->Project,
                            'project_description' => $expense->ProjectDescription,
                            'completed' => $expense->Completed,
                        ]
                    );
                }
            }

            $nextUrl = $response->d->__next ?? null;
        } while ($nextUrl);

        $this->info('WBS expenses fetched and stored successfully!');
    }
}

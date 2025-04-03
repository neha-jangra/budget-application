<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ExactActivities;
use Carbon\Carbon;

class ImportWbsActivities extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'budget-app:import-wbs-activities';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch WBS Activities from Exact API and store in database';


    public function handle()
    {
        $nextUrl = config('env.exact_url') . '/project/WBSActivities';
        $method = 'GET';

        do {
            $response = commonCurl($nextUrl, $method, []);

            if (isset($response->d) && isset($response->d->results)) {
                foreach ($response->d->results as $activity) {
                    ExactActivities::updateOrCreate(
                        ['activity_id' => $activity->ID],
                        [
                            'budgeted_cost' => $activity->BudgetedCost,
                            'budgeted_hours' => $activity->BudgetedHours,
                            'budgeted_revenue' => $activity->BudgetedRevenue,
                            'completed' => $activity->Completed,
                            'description' => $activity->Description,
                            'part_of' => $activity->PartOf ?? null,
                            'part_of_description' => $activity->PartOfDescription ?? null,
                            'project_id' => $activity->Project,
                            'project_description' => $activity->ProjectDescription,
                        ]
                    );
                }
            }

            $nextUrl = $response->d->__next ?? null;
        } while ($nextUrl);

        $this->info('WBS Activities fetched and stored successfully!');
    }
}

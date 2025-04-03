<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\{ExactSubProject};
use Illuminate\Support\Facades\Log;

class ImportExactSubProjects extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'budget-app:import-exact-sub-projects';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command fetches sub-projects (WBSDeliverables) from the Exact API where PartOf is null';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $apiUrl = config('env.exact_url') . "/project/WBSDeliverables?" . http_build_query(['$filter' => 'PartOf eq null']);
        $method = 'GET';
        $exactProjectIds = []; // Array to store all fetched Exact sub-project IDs
        do {
            $projects = commonCurl($apiUrl, $method, []);
            if (isset($projects->d) && isset($projects->d->results)) {
                foreach ($projects->d->results as $projectData) {
                    $exactProjectIds[] = $projectData->ID;
                    ExactSubProject::updateOrCreate(
                        ['exact_id' => $projectData->ID],
                        [
                            'exact_project_id' => $projectData->Project,
                            'description'      => $projectData->Description,
                        ]
                    );
                }
            }
            // Check if there is a next page
            $apiUrl = $projects->d->{'__next'} ?? null;
        } while (!empty($apiUrl));

        // Find and delete sub-projects that no longer exist in Exact
        ExactSubProject::whereNotIn('exact_id', $exactProjectIds)->delete();
        $this->info("ExactSubProject records synchronized successfully.");
    }
}

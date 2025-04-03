<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\{ExactToken, ExactProjects};
use Illuminate\Support\Facades\Log;

class ImportExactProjects extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'budget-app:import-exact-projects';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command will be used to get the projects from exact system and store inside budget app for further use';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $baseApiUrl = config('env.exact_url') . '/project/Projects';
        $method = 'GET';
        $nextUrl = $baseApiUrl;
        do {
            $projects = commonCurl($nextUrl, $method, []);
            if (isset($projects->d) && isset($projects->d->results)) {
                foreach ($projects->d->results as $resultProject) {
                    ExactProjects::updateOrCreate(
                        ['exact_id' => $resultProject->ID],
                        [
                            'exact_id' => $resultProject->ID,
                            'project_code' => $resultProject->Code,
                            'description' => $resultProject->Description,
                            'start_date' => Carbon::createFromTimestampMs($resultProject->StartDate),
                            'end_date' => Carbon::createFromTimestampMs($resultProject->EndDate),
                            'part_of' => $resultProject->PartOf ?? null,
                            'account' => $resultProject->Account ?? null,
                            'account_id' => $resultProject->AccountID ?? null,
                            'account_name' => $resultProject->AccountName ?? null,
                            'account_code' => $resultProject->AccountCode ?? null,
                            'account_contact' => $resultProject->AccountContact ?? null,
                        ]
                    );
                }
            }
            // Check if there is a next page
            $nextUrl = $projects->d->__next ?? null;
        } while ($nextUrl);
    }
}

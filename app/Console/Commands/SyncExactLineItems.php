<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\{Project, SubProject};
use Illuminate\Support\Facades\Log;

class SyncExactLineItems extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'budget-app:sync-exact-line-items';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $projects = Project::all();
        foreach ($projects as $project) {
            $yearInformation = calculateYears($project->current_budget_timeline_from, $project->current_budget_timeline_to);
            foreach ($yearInformation as $yearData) {
                $year = $yearData['year'] ?? date('Y');
                $this->info("Processing Project ID: {$project->id} for Year: {$year}");
                saveExactLineItemsInBudgetApp($project->id, $year);
            }
        }
        $this->info("All projects processed successfully.");
    }
}

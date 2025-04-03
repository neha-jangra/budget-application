<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ExactExpenses;

class ImportWBSExpenses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'budget-app:import-wbs-expenses';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch WBS Expenses and store them in the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $nextUrl = config('env.exact_url') . '/project/WBSExpenses';
        $method = 'GET';

        do {
            $response = commonCurl($nextUrl, $method, []);

            if (isset($response->d) && isset($response->d->results)) {
                foreach ($response->d->results as $expense) {
                    ExactExpenses::updateOrCreate(
                        ['expense_id' => $expense->ID],
                        [
                            'budgeted_cost' => $expense->BudgetedCost,
                            'budgeted_revenue' => $expense->BudgetedRevenue,
                            'completed' => $expense->Completed,
                            'description' => $expense->Description,
                            'part_of' => $expense->PartOf,
                            'part_of_description' => $expense->PartOfDescription,
                            'project_id' => $expense->Project,
                            'project_description' => $expense->ProjectDescription,
                            'item' => $expense->Item,
                            'quantity' => $expense->Quantity,
                        ]
                    );
                }
            }

            $nextUrl = $response->d->__next ?? null;
        } while ($nextUrl);

        $this->info('WBS expenses fetched and stored successfully!');
    }
}

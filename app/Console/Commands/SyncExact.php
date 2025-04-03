<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ExactSync;
use Carbon\Carbon;
use App\Constants\SyncConstant;
use Illuminate\Support\Facades\Log;

class SyncExact extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'budget-app:sync-exact';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync Exact based on required syncs';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $syncs = ExactSync::where('execute_at', '<=', Carbon::now())
            ->where('status', SyncConstant::STATUS_PENDING)
            ->get();

        $planned = false;

        if ($syncs->isEmpty()) {
            $this->info("No pending syncs found.");
            return;
        }

        // Set syncs to "in progress"
        foreach ($syncs as $sync) {
            $sync->status = SyncConstant::STATUS_IN_PROGRESS;
            $sync->executed_at = Carbon::now();
            $sync->save();

            if ($sync->type === 'planned') {
                $planned = true;
            }
        }

        try {
            $commands = [
                'budget-app:import-exact-employees',
                'budget-app:import-exact-users',
                'budget-app:import-exact-projects',
                'budget-app:import-exact-sub-projects',
                'budget-app:sync-exact-line-items',
            ];

            foreach ($commands as $command) {
                $this->info("Running command: {$command}");
                Log::info("Running command: {$command}");

                $maxAttempts = 5; // Number of retries
                $delay = 5; // Initial delay in seconds

                for ($attempt = 1; $attempt <= $maxAttempts; $attempt++) {
                    $output = shell_exec("php artisan {$command}");
                    Log::info("Output: {$output}");

                    if (!empty($output)) {
                        $this->line($output);
                    }

                    // Check if the output contains a 429 error (simulated check)
                    if (str_contains(strtolower($output), '429')) {
                        $this->error("429 Too Many Requests: Retrying in {$delay} seconds...");
                        sleep($delay);
                        $delay *= 2; // Exponential backoff (5s, 10s, 20s, etc.)
                    } else {
                        break; // Command succeeded, move to the next one
                    }
                }
            }

            foreach ($syncs as $sync) {
                $sync->status = SyncConstant::STATUS_COMPLETED;
                $sync->last_synced_at = Carbon::now();
                $sync->executed_at = Carbon::now();
                $sync->save();
            }

            $this->info("Sync completed successfully.");
        } catch (\Exception $e) {
            $this->error("Error: {$e->getMessage()}");
            Log::error("Sync failed: " . $e->getMessage());

            foreach ($syncs as $sync) {
                $sync->status = SyncConstant::STATUS_FAILED;
                $sync->executed_at = Carbon::now();
                $sync->save();
            }
        }

        if ($planned) {
            ExactSync::create([
                'type' => 'planned',
                'status' => SyncConstant::STATUS_PENDING,
                'execute_at' => Carbon::now()->setTime(2, 0, 0)->addDay(),
                'last_synced_at' => null,
            ]);
        }
    }
}

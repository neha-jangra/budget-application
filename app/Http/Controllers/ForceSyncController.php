<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class ForceSyncController extends Controller
{
    public function sync()
    {
        try {
            // Array of commands to execute in sequence with descriptions
            $commands = [
                'budget-app:get-exact-token' => 'Refreshing authentication token',
                'budget-app:import-exact-employees' => 'Syncing employees data',
                'budget-app:import-exact-projects' => 'Syncing projects data',
                'budget-app:import-exact-users' => 'Syncing users data',
                'budget-app:import-exact-sub-projects' => 'Syncing sub-projects data'
            ];

            $results = [];
            $hasErrors = false;

            // Execute each command and store its output
            foreach ($commands as $command => $description) {
                Log::info("Force Sync: Starting {$description}");

                $exitCode = Artisan::call($command);
                $output = Artisan::output();

                $status = $exitCode === 0 ? 'success' : 'error';
                if ($exitCode !== 0) {
                    $hasErrors = true;
                }

                $results[$command] = [
                    'status' => $status,
                    'description' => $description,
                    'output' => trim($output)
                ];

                Log::info("Force Sync: Completed {$description}", [
                    'status' => $status,
                    'output' => trim($output)
                ]);
            }

            return response()->json([
                'success' => !$hasErrors,
                'message' => $hasErrors ? 'Sync completed with some errors' : 'Sync completed successfully',
                'results' => $results
            ], $hasErrors ? 422 : 200);

        } catch (\Exception $e) {
            Log::error('Force Sync: Failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Force sync failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

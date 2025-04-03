<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ExactSync;
use Carbon\Carbon;
class ExactSyncController extends Controller
{
    public function forceSync()
    {
        ExactSync::create([
            'type' => 'force',
            'status' => 0,
            'execute_at' => Carbon::now(),
            'last_synced_at' => null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Sync force started',
        ], 200);
    }

    public function lastSync()
    {
        $lastSync = ExactSync::where('executed_at', '!=', null)->orderBy('executed_at', 'desc')->first();
        $upcomingSync = ExactSync::where('execute_at', '>', Carbon::now())->where('status', 0)->orderBy('execute_at', 'asc')->first();
        if ($lastSync) {
            return response()->json([
                'success' => true,
                'message' => 'Last sync',
                'data' => [
                    'status' => $lastSync->status,
                    'execute_at' => $lastSync->execute_at,
                    'last_synced_at' => ($lastSync->last_synced_at != null) ? $lastSync->last_synced_at->format('H:i d-m-Y') : '-',
                    'upcoming_sync' => ($upcomingSync != null) ? $upcomingSync->execute_at->format('H:i d-m-Y') : '-',
                ],
            ], 200);
        }
        return response()->json([
            'success' => false,
            'message' => 'No last sync found',
        ], 404);
    }
}

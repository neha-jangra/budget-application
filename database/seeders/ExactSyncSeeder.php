<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ExactSync;
use Carbon\Carbon;

class ExactSyncSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ExactSync::create([
            'type' => 'planned',
            'status' => 0,
            'execute_at' => Carbon::now()->setTime(2, 0, 0)->addDay(),
            'last_synced_at' => null,
        ]);
    }
}

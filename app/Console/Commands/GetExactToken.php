<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use App\Models\ExactToken;

class GetExactToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'budget-app:get-exact-token';

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
        $auth = ExactToken::first();
        if (Carbon::parse($auth->updated_at)->addMinutes(9)->isPast()) {
            getAuthToken();
            $auth = ExactToken::first();
        }
        $this->info($auth->access_token);
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\{ExactToken, ExactUsers};
use Illuminate\Support\Facades\Log;

class ImportExactUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'budget-app:import-exact-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to import the Donors from Exact system';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $queryParams = [
            '$filter' => "Status eq 'C'",
            '$select' => 'ID,AddressLine1,AddressLine2,AddressLine3,City,Code,Country,CountryName,Email,Name,Phone,Postcode,State,StateName,Status,Type',
        ];
        $baseUrl = config('env.exact_url') . "/bulk/CRM/Accounts";
        $nextUrl = $baseUrl . '?' . http_build_query($queryParams);
        $method = 'GET';
        do {
            $donors = commonCurl($nextUrl, $method, []);
            if (isset($donors->d) && isset($donors->d->results)) {
                foreach ($donors->d->results as $donor) {
                    ExactUsers::updateOrCreate(
                        ['exact_id' => $donor->ID],
                        [
                            'account_id' => trim($donor->ID),
                            'code' => trim($donor->Code),
                            'exact_id' => trim($donor->ID),
                            'email' => trim($donor->Email),
                            'account_name' => trim($donor->Name),
                            'phone' => trim($donor->Phone),
                            'city' => trim($donor->City),
                            'state' => trim($donor->State),
                            'country' => trim($donor->Country),
                            'address' => trim($donor->AddressLine1) . ' ' . trim($donor->AddressLine2) . ' ' . trim($donor->AddressLine3),
                        ]
                    );
                }
            }
            // For pagination, we don't add query params since __next contains full URL
            $nextUrl = $donors->d->__next ?? null;
        } while ($nextUrl);
    }
}

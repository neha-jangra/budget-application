<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\{ExactEmployee};
use Illuminate\Support\{Carbon};
use Illuminate\Support\Facades\Log;

class ImportExactEmployees extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'budget-app:import-exact-employees';

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
        $apiUrl = config('env.exact_url') . '/payroll/Employees';
        $method = 'GET';
        $employees = commonCurl($apiUrl, $method, []);

        if (!isset($employees->d->results) || empty($employees->d->results)) {
            return;
        }

        foreach ($employees->d->results as $employeeData) {
            $employeeId = $employeeData->ID;

            // Fetch the employee's internal rates
            $queryParams = [
                '$filter' => "Employee eq guid'$employeeId'",
                '$select' => 'ID,Employee,EndDate,InternalRate,StartDate'
            ];

            $rateApiUrl = config('env.exact_url') . '/project/EmploymentInternalRates?' . http_build_query($queryParams);
            $rateResponse = commonCurl($rateApiUrl, 'GET', []);

            $hourlyRate = null;
            $startRateDate = null;
            $endRateDate = null;
            $latestRate = 0;

            if (!empty($rateResponse->d->results)) {
                $rates = collect($rateResponse->d->results);

                // Get the latest running rate (where EndDate is null or still valid)
                $latestRateData = $rates
                    ->filter(fn($rate) => empty($rate->EndDate) || now()->timestamp * 1000 <= $this->extractTimestamp($rate->EndDate))
                    ->sortByDesc(fn($rate) => $this->extractTimestamp($rate->StartDate))
                    ->first();

                if ($latestRateData) {
                    $hourlyRate = $latestRateData->InternalRate;
                    $latestRate = $hourlyRate * 7.5; // Convert hourly rate to daily rate
                    $startRateDate = $this->convertExactDate($latestRateData->StartDate);
                    $endRateDate = $this->convertExactDate($latestRateData->EndDate ?? null);
                }
            }

            // Store or update employee in the database
            ExactEmployee::updateOrCreate(
                ['exact_id' => $employeeData->ID],
                [
                    'first_name'      => $employeeData->FirstName ?? '',
                    'last_name'       => $employeeData->LastName ?? '',
                    'full_name'       => $employeeData->FullName ?? '',
                    'email'           => $employeeData->Email ?? ($employeeData->BusinessEmail ?? $employeeData->PrivateEmail),
                    'mobile'          => $employeeData->Mobile ?? ($employeeData->BusinessMobile ?? $employeeData->Phone),
                    'country'         => trim($employeeData->Country ?? ''),
                    'rate_type'       => 'EUR',
                    'rate'            => $latestRate,
                    'hourly_rate'     => $hourlyRate,
                    'start_rate_date' => $startRateDate,
                    'end_rate_date'   => $endRateDate,
                ]
            );
        }
    }

    /**
     * Extracts timestamp from Exact Online date format.
     */
    private function extractTimestamp($exactDate)
    {
        if (preg_match('/\/Date\((\d+)\)\//', $exactDate, $matches)) {
            return (int) $matches[1];
        }
        return null;
    }

    /**
     * Converts Exact Online date format to 'Y-m-d' format.
     */
    private function convertExactDate($exactDate)
    {
        if (!$exactDate) {
            return null; // Return null if the date is missing
        }

        $timestamp = $this->extractTimestamp($exactDate);
        return $timestamp ? date('Y-m-d', $timestamp / 1000) : null;
    }

}

<div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-5 gy-4 mb-24">
    <div class="col">
        <div class="bordered-card h-100 p-3 d-flex flex-column justify-content-between">
            <span class="text-gray-500 text-sm font-medium d-block mb-1">{{($sub_project_data == 1) ? 'Budget' : 'Total Awarded Funds'}}</span>
            <p class="text-primary-500 text-xl font-bold mb-0 text-break">
                @if ($sub_project_data)
                €{{(calCulateprojectMatrix($project_id,$tabYear,'approval_budget',isset($sub_project->id) ? $sub_project->id : NULL )  == 1) ? 0 : dutchCurrency(calCulateprojectMatrix($project_id,$tabYear, 'approval_budget',isset($sub_project->id) ? $sub_project->id : NULL )) }}
                @else
                €{{ dutchCurrency($projectdetail->budget) }}
                @endif
            </p>
        </div>
    </div>
    <div class="col">
        <div class="bordered-card h-100 p-3 d-flex flex-column justify-content-between">
            <span class="text-gray-500 text-sm font-medium d-block mb-1">Expenses to Date</span>
            <p class="text-primary-500 text-xl font-bold mb-0 text-break">
                @if($sub_project_data)
                €{{(calCulateprojectMatrix($project_id, $tabYear, 'actual_expenses',isset($sub_project->id) ? $sub_project->id : NULL ) == 1) ? 0 : dutchCurrency(calCulateprojectMatrix($project_id,$tabYear, 'actual_expenses',isset($sub_project->id) ? $sub_project->id : NULL ))}}
                @else
                €{{ dutchCurrency(calculatePreviousYearsExpensesBudgets($projectdetail->id, false)); }}
                @endif
            </p>
        </div>
    </div>
    <div class="col">
        <div class="bordered-card h-100 p-3 d-flex flex-column justify-content-between">
            <span class="text-gray-500 text-sm font-medium d-block mb-1">Remaining Funds</span>
            @php
            if ($sub_project_data) {
            $remaining = (calCulateprojectMatrix($project_id, $tabYear, 'remaining_balance', isset($sub_project->id) ? $sub_project->id : NULL ) == 1) ? 0 : calCulateprojectMatrix($project_id, $tabYear, 'remaining_balance', isset($sub_project->id) ? $sub_project->id : NULL );
            $remainingDutch = dutchCurrency($remaining);
            } else {
            $remaining = $projectdetail->budget - calculatePreviousYearsExpensesBudgets($projectdetail->id, false);
            $remainingDutch = dutchCurrency($remaining);
            }
            @endphp
            <p class="text-primary-500 text-xl font-bold mb-0 text-break {{ ($remaining ?? 0) < 0 ? 'text-error-500' : '' }}">
                €{{ $remainingDutch }}
            </p>
        </div>
    </div>
    <div class="col">
        <div class="bordered-card h-100 p-3 d-flex flex-column justify-content-between overflow-visible">
            <span class="text-gray-500 text-sm font-medium d-block mb-1">Current Year Budget</span>
            @php
            // Calculate available funds at the start of the year
            $priorYearExpenses = calculatePreviousYearsExpensesBudgets($projectdetail->id, true);
            $availableFundsStartOfYear = $projectdetail->budget - $priorYearExpenses;

            // Calculate the current year budget and determine any over-budget variances
            $currentYearBudget = (calCulateprojectMatrix($project_id, date('Y'), 'approval_budget', isset($sub_project->id) ? $sub_project->id : NULL ) == 1) ? 0 : calCulateprojectMatrix($project_id, date('Y'), 'approval_budget', isset($sub_project->id) ? $sub_project->id : NULL );

            // Calculate variance if Current Year Budget exceeds Available Funds Start of Year
            $varianceAvailableFunds = ($currentYearBudget > $availableFundsStartOfYear) ? $currentYearBudget - $availableFundsStartOfYear : 0;
            $highlightClass = $varianceAvailableFunds > 0 ? 'text-error-500' : '';
            @endphp

            <div class="budget-container">
                <p class="text-primary-500 text-xl font-bold mb-0 text-break {{ $highlightClass }}">
                    €{{ dutchCurrency($currentYearBudget) }}
                </p>

                @if ($varianceAvailableFunds > 0)
                <div class="info-icon-container">
                    <i class="fas fa-info-circle info-icon"></i>
                    <div class="info-text">
                        Action Required: The Current Year Budget exceeds the Available Funds at the Start of the Year by €{{ dutchCurrency($varianceAvailableFunds) }}.
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col">
        <div class="bordered-card h-100 p-3 d-flex flex-column justify-content-between">
            <span class="text-gray-500 text-sm font-medium d-block mb-1">Balance for Current Year</span>
            @php
            $currentYearBalance = (calCulateprojectMatrix($project_id, date('Y'), 'remaining_balance',isset($sub_project->id) ? $sub_project->id : NULL ) == 1) ? 0 : dutchCurrency(calCulateprojectMatrix($project_id, date('Y'), 'remaining_balance', isset($sub_project->id) ? $sub_project->id : NULL ));
            @endphp
            <p class="text-primary-500 text-xl font-bold mb-0 text-break {{ ($currentYearBalance ?? 0) < 0 ? 'text-error-500' : '' }}">
                €{{ $currentYearBalance }}
            </p>
        </div>
    </div>
</div>
@php
$projectYears = getYearList();
@endphp

<div class="main-content reports-container" role="main" id="myDiv">
   <div class="page-header">
      <span>
         <div class="breadcrumb d-flex align-items-center mb-3 flex-warp flex-md-nowrap gap-2 gap-md-0">
            <img src="{{ asset('images/icons/breadcrumb-home.svg')}}" alt="home-icon">
            <img src="{{ asset('images/icons/chevron-right.svg')}}" alt="auth-logo" class="custom-mx-2px">
            <span class="d-inline-block text-sm font-semibold px-2 py-1 text-gray-600 active-page">Reports</span>
         </div>
      </span>
      <div class="d-flex flex-wrap align-items-center gap-12 mb-4">
         <h6 class="h6 font-medium text-gray-800 mb-0 order-1 order-sm-1 me-auto">Total Annual Budget</h6>
         <div class="ms-sm-auto order-3 order-sm-2">
            <form action="theme-form">
               <select type="text" class="year-filter-select js-example-basic-single form-control reportYearJs" data-minimum-results-for-search="Infinity" onchange="switchYear('reportYearJs')">
                  @foreach ($projectYears as $projectYear)
                  <option value="{{ $projectYear }}" {{ ($year==$projectYear) ? 'selected' : ''  }}>{{ $projectYear }}</option>
                  @endforeach
               </select>
            </form>
         </div>
         <a type="button" class="btn btn-primary theme-btn order-2 order-sm-3" href="{{ route('reports.pdf', ['year'=> $year]) }}" id="captureButton" onclick="disableButton()">
            <svg class="me-2" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
               <path d="M6.71875 8.59375L10 11.875L13.2812 8.59375" stroke="white" stroke-width="1.5" stroke-linec
                  ap="round" stroke-linejoin="round" />
               <path d="M10 3.125V11.875" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
               <path d="M16.875 11.875V16.25C16.875 16.4158 16.8092 16.5747 16.6919 16.6919C16.5747 16.8092 16.4158 16.875 16.25 16.875H3.75C3.58424 16.875 3.42527 16.8092 3.30806 16.6919C3.19085 16.5747 3.125 16.4158 3.125 16.25V11.875" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            Export report data
         </a>
         <div id="downloadMessage" style="display: none;">We’re exporting pdf...</div>
      </div>
   </div>
   <div class="content withSideSpacing mb-4">
      <div class="row gap-4 gap-xl-0">
         <div class="col-md-12 col-xl-4 col-xxl-4">
            <div class="container-fluid h-100">
               <div class="row h-100">
                  <!-- do not remove this code. this is graph used in pervious version -->
                  <!-- <div class="col-md-12 col-lg-8 col-xxl-8 p-0" wire:ignore>
                     <div class="card-border h-100 p-3">
                        <h5 class="text-gray-800 text-lg font-semibold mb-4">Budget overview</h5>
                        <figure class="highcharts-figure">
                           <div id="budget_overview"></div>
                        </figure>
                     </div>
                  </div> -->
                  @php
                  $projectedBudgetTotal = 0;
                  $totalProjectedBudget = 0;
                  $totalApprovedBudget = 0;
                  $totalRemainingBudget = 0;
                  $otherTotalProjectedBudget = 0;
                  $otherTotalRemainingBudget = 0;
                  $totalOtherApprovedBudget = 0;
                  $otherDirectTotalForAllCat = 0;
                  $totalProjectedBudgetWithoutCat = 0;
                  @endphp
                  @foreach ($categories as $category)
                  @php
                  $otherDirectTotal = calculateSumODE('total_approved_cost', $category->id, $year);
                  $otherDirectTotalForAllCat += $otherDirectTotal;
                  $report = getReportDataIndirect($year, $category->id);
                  $projectedBudget = isset($report->total_annual_budget) ? $report->total_annual_budget : 0;
                  $projectedBudgetTotal += $projectedBudget;
                  @endphp
                  @endforeach
                  @foreach ($employees as $employee)
                  @php
                  $report = getReportData($year, $employee->id);
                  $totalApprovedBudget += isset($report->total_annual_budget) ? $report->total_annual_budget : 0;
                  $totalProjectedBudget+=getProjectedBudget($year, $employee->id);
                  $totalProjectedBudgetWithoutCat+=getProjectedBudget($year, $employee->id);
                  $remainingBudget = isset($report->total_annual_budget) ? getProjectedBudget($year, $employee->id) - $report->total_annual_budget : 0 ;
                  $totalRemainingBudget+=$remainingBudget;
                  @endphp
                  @endforeach
                  @foreach ($otherDirectExpenses as $key =>$otherDirectExpense)
                  @php
                  $report = getReportDataOtherDirect($year, $key);
                  $totalOtherApprovedBudget += isset($report->total_annual_budget) ? $report->total_annual_budget : 0;
                  $otherTotalProjectedBudget+=getProjectedBudgetOtherDirect($year, $key);
                  $otherRemainingBudget = isset($report->total_annual_budget) ? getProjectedBudgetOtherDirect($year, $key) - $report->total_annual_budget : 0 ;
                  $otherTotalRemainingBudget+=$otherRemainingBudget;
                  @endphp
                  @endforeach

                  @php
                  $report = getReportDataOtherDirect($year, 6);
                  $totalOtherApprovedBudget += isset($report->total_annual_budget) ? $report->total_annual_budget : 0;
                  $otherTotalProjectedBudget+=getProjectedBudgetOtherDirect($year, 6);
                  $otherRemainingBudget = isset($report->total_annual_budget) ? getProjectedBudgetOtherDirect($year, 6) - $report->total_annual_budget : 0 ;
                  $otherTotalRemainingBudget+=$otherRemainingBudget;
                  $totalAnnualBudget = (float)$totalOtherApprovedBudget + (float)$totalApprovedBudget;
                  $totalProjectedBudget = (float)$totalProjectedBudget + (float)$otherTotalProjectedBudget + (float)$otherDirectTotalForAllCat;
                  @endphp

                  <div class="col-md-12 p-0">
                     <div class="d-flex flex-column gap-4 h-100">
                        <div class="bordered-card h-100 p-3 d-flex flex-column justify-content-between">
                           <div class="text-gray-500 text-sm font-semibold">Projected Annual Budget <span>{{ $year }}</span></div>
                           <p class="text-primary-500 text-xl font-bold mb-0 text-break">€{{ dutchCurrency($totalOtherApprovedBudget + $totalApprovedBudget)   }}</p>
                        </div>
                        <div class="bordered-card h-100 p-3 d-flex flex-column justify-content-between">
                           <div class="d-flex align-items-start gap-2 justify-content-between">
                              <div class="text-gray-500 text-sm font-semibold">Project budgets</div>
                              <div class="text-white bg-success-500 text-sm font-semibold percentage-text">
                                 <?php
                                 if ($totalApprovedBudget != 0) {
                                    $percentage = (($totalProjectedBudgetWithoutCat + $otherTotalProjectedBudget) / ($totalOtherApprovedBudget + $totalApprovedBudget)) * 100;
                                    echo number_format($percentage) . "%";
                                 } else {
                                    echo '0%';
                                 }
                                 ?>
                              </div>
                           </div>
                           <p class="text-primary-500 text-xl font-bold mb-0 text-break">€{{ dutchCurrency($totalProjectedBudgetWithoutCat+$otherTotalProjectedBudget) }}</p>
                        </div>
                        <div class="bordered-card h-100 p-3 d-flex flex-column justify-content-between">
                           <div class="text-gray-500 text-sm font-semibold">Difference</div>
                           @php
                           $yearDifference = ($totalProjectedBudgetWithoutCat+$otherTotalProjectedBudget) - ($totalOtherApprovedBudget + $totalApprovedBudget);
                           @endphp
                           <p class="text-primary-500 text-xl font-bold mb-0 text-break {{ ($yearDifference ?? 0) < 0 ? 'text-error-500' : '' }}">€{{ dutchCurrency($yearDifference)  }}</p>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="col-md-12 col-xl-8 col-xxl-8" wire:ignore>
            <div class="bordered-card h-100 p-3">
               <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-4">
                  <h5 class="text-gray-800 text-lg font-semibold mb-0">Income by donor</h5>
                  <a class="d-inline-block text-primary-500 text-sm font-semibold text-decoration-none" onclick="scrollToBottom()">See details</a>
               </div>
               <figure class="highcharts-figure">
                  <div id="income-by-donor"></div>
               </figure>
            </div>
         </div>
      </div>
   </div>
   <div>
      <div class="table-responsive" style="max-height: 700px;">
         <table width="100%" class="reports-table total-annual-budget-table mb-12">
            <thead>
               <tr class="primary-header">
                  @if ($lastUpdate)
                  <th rowspan="2" colspan="3" style="vertical-align: middle;position: sticky;top: -1px;z-index: 2;"><b>LAST UPDATE: {{ \Carbon\Carbon::parse($lastUpdate->updated_at)->isoFormat('D MMMM YYYY') }}</b></th>
                  @else
                  <th rowspan="2" colspan="3" style="vertical-align: middle;position: sticky;top: -1px;z-index: 2;"><b>LAST UPDATE: Not updated yet</b></th>
                  @endif
                  <th style="min-width:170px;width:170px;position: sticky;top: -1px;z-index: 2;"><b>Per Unit {{ $year }}</b></th>
                  <th style="min-width:170px;width:170px;position: sticky;top: -1px;z-index: 2;"><b>Units</b></th>
                  <th style="min-width:170px;width:170px;position: sticky;top: -1px;z-index: 2;"><b>Projected Annual Budget</b></th>
                  <th style="min-width:170px;width:170px;position: sticky;top: -1px;z-index: 2;" class="projected-budget-th cursor-pointer" onclick="collapseColumn('hide-projected-budget-column', 'total-annual-budget-table')">
                     <div class="box d-flex align-items-center justify-content-between gap-2">
                        <div class="title whitespace-nowrap transition-duration-point4"><b>Project budgets</b></div>
                        <div>
                           <span class="show">
                              <img src="{{ asset('images/icons/table-cell-eye.svg')}}" alt="img">
                           </span>
                           <span class="hide">
                              <img src="{{ asset('images/icons/table-cell-eye-slash.svg')}}" alt="img">
                           </span>
                        </div>
                     </div>
                  </th>
                  <th style="min-width:170px;width:170px;position: sticky;top: -1px;z-index: 2;" class="balance-th cursor-pointer" onclick="collapseColumn('hide-balance-column', 'total-annual-budget-table')">
                     <div class="box d-flex align-items-center justify-content-between gap-2">
                        <div class="title whitespace-nowrap transition-duration-point4"><b>Balance</b></div>
                        <div>
                           <span class="show">
                              <img src="{{ asset('images/icons/table-cell-eye.svg')}}" alt="img">
                           </span>
                           <span class="hide">
                              <img src="{{ asset('images/icons/table-cell-eye-slash.svg')}}" alt="img">
                           </span>
                        </div>
                     </div>
                  </th>
               </tr>
               <tr class="primary-header">
                  <th style="min-width:170px;width:170px;position: sticky;top: 54px;z-index: 2;" class="border-l-gray-300 editable"><b>Payroll /month</b>
                     <span class="edit">
                        <img src="{{ asset('images/icons/table-cell-edit.svg')}}" alt="img">
                     </span>
                  </th>
                  <th style="min-width:170px;width:170px;position: sticky;top: 54px;z-index: 2;" class="editable"><b>Unit Costs</b>
                     <span class="edit">
                        <img src="{{ asset('images/icons/table-cell-edit.svg')}}" alt="img">
                     </span>
                  </th>
                  <th style="min-width:170px;width:170px;position: sticky;top: 54px;z-index: 2;" class="editable"><b>{{ $year }}</b>
                     <span class="edit">
                        <img src="{{ asset('images/icons/table-cell-edit.svg')}}" alt="img">
                     </span>
                  </th>
                  <th style="min-width:170px;width:170px;position: sticky;top: 54px;z-index: 2;" class="projected-budget-cell projected-budget-cell-bg-color"></th>
                  <th style="min-width:170px;width:170px;position: sticky;top: 54px;z-index: 2;" class="balance-th">
                     <div class="title whitespace-nowrap transition-duration-point4"><b>Should be Zero</b></div>
                  </th>
               </tr>
            </thead>
            <tbody>
               <tr>
                  <td colspan="8" class="padding-0 border-b-none">
                     <table width="100%">
                        <thead>
                           <tr class="secondary-header">
                              <th colspan="8"><b>A. Gross Salary + Employer Taxes And Contributions</b></th>
                           </tr>
                        </thead>
                        <tbody>
                           @if(count($employees)==0)
                           <tr>
                              <td class="count"></td>
                              <td colspan="2" class="border-l-none" style="min-width: 437px;width: 437px;">
                                 <span class="text-gray-50 text-sm font-regular">You don’t have any data yet</span>
                              </td>
                              <td style="min-width:170px;width:170px;" class="border-l-none"></td>
                              <td style="min-width:170px;width:170px;" class="border-l-none"></td>
                              <td style="min-width:170px;width:170px;" class="border-l-none"></td>
                              <td style="min-width:170px;width:170px;" class="projected-budget-cell projected-budget-cell-bg-color dborder-l-none"></td>
                              <td style="min-width:170px;width:170px;" class="balance-cell border-l-none"></td>
                           </tr>
                           @endif
                           @php
                           $empoyeeProjectedBudget = 0;

                           @endphp
                           @foreach ($employees as $employee)
                           @php

                           $report = getReportData($year, $employee->id);
                           $months = $report->months ?? 12;
                           $employeeId = $employee->id;
                           $employeeName = $employee->name;
                           $subProjectsCount = count($employee->subProjectData);
                           $empoyeeProjectedBudget += getProjectedBudget($year, $employeeId);
                           @endphp
                           <tr class="details-row outerTrJs" data-employee-id="{{ $employeeId }}" data-collapsed="false">
                              <td class="cursor-pointer" colspan="2" style="min-width: 300px;width: 300px;" onclick="collapseExpendRows(this)">
                                 <div class="d-flex align-items-center gap-12">

                                    <span class="accordionArrowJs transition-duration-point4" wire:ignore.self><img src="{{ asset('images/icons/table-chevron-circle-right.svg')}}" alt="img"></span>

                                    {{ $employeeName }}
                                 </div>
                              </td>
                              <td style="min-width:170px;width:170px;">{{ getEmployeeTotalPercentage($year, $employeeId) }}%</td>
                              <td style="min-width:170px;width:170px;" class="editable-td p-0 units-input-number">
                                 <span class="currency-sign">€</span>
                                 <input type="text" class="table-input project_expenses project-expenses-input amountJs{{ $employeeId }}" placeholder="0" value="{{ isset($report->monthly_amount) ? dutchCurrency($report->monthly_amount) : 0 }}" min="1" max="10000" data-type="currency" onkeyup="calculateReportEmployeeBudget('{{ $employeeId }}', 'amountJs', 'monthsJs', 'totalAnnualBudgetJs','projectedBudgetJs', 'balanceJs')" />
                                 <span class="edit-pill">Edit</span>
                                 <button type="submit" class="save-table-btn" onclick="saveReportsData('{{ $employeeId }}', 'amountJs', 'monthsJs', 'projectedBudgetJs', '{{ $year }}', false)">save</button>
                                 <div class="text-error-500 project-validation-error"></div>
                              </td>
                              <td style="min-width:170px;width:170px;" class="editable-td p-0 units-input-number">
                                 <select class="js-example-basic-single table-select table-select w-100 monthsJs{{ $employeeId }}" onchange="calculateReportEmployeeBudget('{{ $employeeId }}', 'amountJs', 'monthsJs', 'totalAnnualBudgetJs','projectedBudgetJs', 'balanceJs')" data-minimum-results-for-search="Infinity">
                                    @for ($i = 12; $i >= 1; $i--)
                                    <option value="{{ $i }}" {{ $months == $i ? 'selected' : '' }}>{{ $i }} Month</option>
                                    @endfor
                                 </select>
                                 <span class="edit-pill">Edit</span>
                                 <button type="submit" class="save-table-btn" onclick="saveReportsData('{{ $employeeId }}', 'amountJs', 'monthsJs', 'projectedBudgetJs', '{{ $year }}', false)">save</button>
                              </td>
                              <td style="min-width:170px;width:170px;" class="editable-td p-0 units-input-number">
                                 <span class="currency-sign">€</span>
                                 <input type="text" class="table-input project_expenses project-expenses-input totalAnnualBudgetJs{{ $employeeId }}" placeholder="0" value="{{ isset($report->total_annual_budget) ? dutchCurrency($report->total_annual_budget) : 0 }}" min="1" max="10000" data-type="currency" onkeyup="calculateReportEmployeeBudgetTotalAnnual('{{ $employeeId }}', 'totalAnnualBudgetJs','projectedBudgetJs', 'balanceJs')" />
                                 <span class="edit-pill">Edit</span>
                                 <button type="submit" class="save-table-btn" onclick="saveReportsData('{{ $employeeId }}', 'amountJs', 'monthsJs', 'projectedBudgetJs', '{{ $year }}', false)">save</button>
                                 <div class="text-error-500 project-validation-error"></div>
                              </td>
                              <input type="hidden" class="projectedBudgetJs{{ $employeeId }}" value="{{ getProjectedBudget($year, $employeeId) }}">
                              <td style="min-width:170px;width:170px;" class="projected-budget-cell projected-budget-cell-bg-color">€{{ dutchCurrency(getProjectedBudget($year, $employeeId)) }}</td>
                              @php
                              $emmployeeRemainingAmount = isset($report->total_annual_budget) ? dutchCurrency(getProjectedBudget($year, $employeeId) - $report->total_annual_budget) : 0;
                              @endphp
                              <td style="min-width:170px;width:170px;" class="balance-cell balanceJs{{ $employeeId }}  {{ ($emmployeeRemainingAmount ?? 0) < 0 ? 'text-error-500' : '' }}">€{{ $emmployeeRemainingAmount }}</td>
                           </tr>
                           @php
                           $projectsData = collect();
                           foreach ($employee->subProjectData as $subprojectData) {
                           $projectId = $subprojectData->project->id;
                           $projectName = $subprojectData->project->project_name;
                           if (!$projectsData->has($projectId)) {
                           $projectsData->put($projectId, ['name' => $projectName, 'subprojects' => collect([$subprojectData])]);
                           } else {
                           $projectsData[$projectId]['subprojects']->push($subprojectData);
                           }
                           }

                           @endphp
                           @foreach ($projectsData as $projectId => $projectData)
                           @php
                           $outerClass="";
                           if (count($projectData['subprojects']) == 1 && $projectData['subprojects'][0]->sub_project_id == ''){
                           $withoutSubProjectData = $projectData['subprojects'][0];
                           }
                           @endphp
                           <tr>
                              <td colspan="8" class="padding-0 border-b-none border-t-none">
                                 <table width="100%" class="inner-level-table" wire:ignore.self>
                                    <tbody>
                                       <tr class="innerTrJs">
                                          <td class="{{ ((count($projectData['subprojects']) > 1) || ((count($projectData['subprojects'])==1 && $projectData['subprojects'][0]->sub_project_id != ''))) ? 'cursor-pointer' : '' }}" onclick="collapseExpendRows(this)">
                                             <div class="d-flex align-items-center gap-12">
                                                @if ((count($projectData['subprojects']) > 1) || ((count($projectData['subprojects'])==1 && $projectData['subprojects'][0]->sub_project_id != '')))
                                                <span class="innerAccordionArrowJs transition-duration-point4" wire:ignore.self><img src="{{ asset('images/icons/table-chevron-circle-right.svg')}}" alt="img"></span>
                                                @endif
                                                {{ $projectData['name'] }}
                                             </div>
                                          </td>
                                          @if (count($projectData['subprojects']) == 1 && $projectData['subprojects'][0]->sub_project_id == '')
                                          <td style="min-width:170px;width:170px;">
                                             <form class="d-flex align-items-center gap-1">
                                                <label class="text-gray-400 text-xs font-semibold">UNITS</label>
                                                <div class="value-button cursor-pointer" onclick="decreaseValue('unitsJs','{{ $withoutSubProjectData->project->id }}{{ $withoutSubProjectData->employee_id }}', '{{ $withoutSubProjectData->revised_unit_amount != 0 ? $withoutSubProjectData->revised_unit_amount :$withoutSubProjectData->unit_costs}}', '{{ $withoutSubProjectData->actual_expenses_to_date }}', '{{ $withoutSubProjectData->percentage }}', '{{ $withoutSubProjectData->year }}', '{{ $withoutSubProjectData->sub_project_id }}', '{{ $withoutSubProjectData->id }}', '{{ $withoutSubProjectData->project_id }}', event)" value="Decrease Value">
                                                   <img src="{{ asset('images/icons/minus-circle.svg')}}" alt="img">
                                                </div>
                                                <input type="number" class="units-input-field unitsJs{{ $withoutSubProjectData->project->id}}{{ $withoutSubProjectData->employee_id }}" value="{{ $withoutSubProjectData->revised_units != 0 ? $withoutSubProjectData->revised_units : $withoutSubProjectData->units }}" />
                                                <div class="value-button cursor-pointer" onclick="increaseValue('unitsJs','{{ $withoutSubProjectData->project->id }}{{ $withoutSubProjectData->employee_id }}', '{{ $withoutSubProjectData->revised_unit_amount != 0 ? $withoutSubProjectData->revised_unit_amount :$withoutSubProjectData->unit_costs}}', '{{ $withoutSubProjectData->actual_expenses_to_date }}', '{{ $withoutSubProjectData->percentage }}', '{{ $withoutSubProjectData->year }}', '{{ $withoutSubProjectData->sub_project_id }}', '{{ $withoutSubProjectData->id }}', '{{ $withoutSubProjectData->project_id }}', event)" value="Increase Value">
                                                   <img src="{{ asset('images/icons/plus-circle.svg')}}" alt="img">
                                                </div>
                                             </form>
                                          </td>
                                          @else
                                          <td style="min-width:170px;width:170px;"></td>
                                          @endif
                                          <td style="min-width:170px;width:170px;" wire:key="exclude-me">{{ getEmployeeProjectPercentage($projectId, $subprojectData->employee_id, $year, true, false) }}</td>
                                          <td style="min-width:170px;width:170px;" wire:key="exclude-me">{{ getEmployeeProjectPercentage($projectId, $subprojectData->employee_id, $year, true, true) }}</td>
                                       </tr>
                                       @if ((count($projectData['subprojects']) > 1) || ((count($projectData['subprojects'])==1 && $projectData['subprojects'][0]->sub_project_id != '')))
                                       @foreach ($projectData['subprojects'] as $subprojectData)
                                       @if(isset($subprojectData->subProject->id))
                                       <tr>
                                          <td colspan="4" class="border-b-none" style="padding: 0!important;">
                                             <table width="100%" class="inner-inner-level-table innerTable{{ $subprojectData->subProject->id}}{{ $subprojectData->employee_id }}" wire:ignore.self>
                                                <tbody>
                                                   <tr>
                                                      <td>{{ $subprojectData->subProject->sub_project_name }}</td>
                                                      <td style="min-width:170px;width:170px;">
                                                         <form class="d-flex align-items-center gap-1">
                                                            <label class="text-gray-400 text-xs font-semibold">UNITS</label>
                                                            <div class="value-button cursor-pointer" onclick="decreaseValue('unitsJs','{{ $subprojectData->subProject->id }}{{ $subprojectData->employee_id }}', '{{ $subprojectData->revised_unit_amount != 0 ? $subprojectData->revised_unit_amount :$subprojectData->unit_costs }}', '{{ $subprojectData->actual_expenses_to_date }}', '{{ $subprojectData->percentage }}', '{{ $subprojectData->year }}', '{{ $subprojectData->sub_project_id }}', '{{ $subprojectData->id }}', '{{ $subprojectData->project_id }}', event)" value="Decrease Value">
                                                               <img src="{{ asset('images/icons/minus-circle.svg')}}" alt="img">
                                                            </div>
                                                            <input type="number" class="units-input-field unitsJs{{ $subprojectData->subProject->id}}{{ $subprojectData->employee_id }}" value="{{ $subprojectData->revised_units != 0 ? $subprojectData->revised_units : $subprojectData->units }}" />
                                                            <div class="value-button cursor-pointer" onclick="increaseValue('unitsJs','{{ $subprojectData->subProject->id }}{{ $subprojectData->employee_id }}', '{{ $subprojectData->revised_unit_amount != 0 ? $subprojectData->revised_unit_amount :$subprojectData->unit_costs }}', '{{ $subprojectData->actual_expenses_to_date }}', '{{ $subprojectData->percentage }}', '{{ $subprojectData->year }}', '{{ $subprojectData->sub_project_id }}', '{{ $subprojectData->id }}', '{{ $subprojectData->project_id }}', event)" value="Increase Value">
                                                               <img src="{{ asset('images/icons/plus-circle.svg')}}" alt="img">
                                                            </div>
                                                         </form>
                                                      </td>
                                                      <td style="min-width:170px;width:170px;">{{ getEmployeeProjectPercentage($subprojectData->subProject->id, $subprojectData->employee_id, $year, false, false) }}</td>
                                                      <td style="min-width:170px;width:170px;">{{ getEmployeeProjectPercentage($subprojectData->subProject->id, $subprojectData->employee_id, $year, false, true) }}</td>
                                                   </tr>
                                                </tbody>
                                             </table>
                                          </td>
                                       </tr>
                                       @endif
                                       @endforeach
                                       @endif
                                    </tbody>
                                 </table>
                              </td>
                           </tr>
                           @endforeach
                           <tr>
                              <td colspan="8" class="padding-0 border-b-none border-t-none">
                                 <table width="100%" class="inner-level-table" wire:ignore.self>
                                    <tbody>
                                       <tr class="innerTrJs">
                                          <td colspan="3">
                                             <div class="d-flex align-items-center gap-12">
                                                All indirect costs budget
                                             </div>
                                          </td>
                                          <td style="min-width:170px;width:170px;"></td>
                                          <td style="min-width:170px;width:170px;" class="border-l-gray-300">€{{ dutchCurrency(calculateSumIE('total_approved_cost', $employee->id, $year)) }}</td>
                                          <td style="min-width:170px;width:170px;" class="border-l-gray-300"></td>
                                       </tr>
                                    </tbody>
                                 </table>
                              </td>
                           </tr>
                           @endforeach
                        </tbody>
                        <tfoot>
                           <tr class="estimated-row">
                              <td colspan="5"><b>B. TOTAL PERSONNEL</b></td>
                              <td style="min-width:170px;width:170px;"><b>€{{ dutchCurrency($totalApprovedBudget) }}</b></td>
                              <td style="min-width:170px;width:170px;" class="projected-budget-total-cell"><b>€{{ dutchCurrency($empoyeeProjectedBudget) }}</b></td>
                              <td style="min-width:170px;width:170px;" class="balance-cell {{ ($totalRemainingBudget ?? 0) < 0 ? 'text-error-500' : '' }}"><b>€{{ dutchCurrency($totalRemainingBudget) }}</b></td>
                           </tr>
                        </tfoot>
                     </table>
                  </td>
               </tr>
               <tr>
                  <td colspan="8" class="padding-0 border-b-none border-t-none">
                     <table width="100%">
                        <thead>
                           <tr class="secondary-header">
                              <th colspan="8"><b>C. Other Direct Expenses</b></th>
                           </tr>
                        </thead>
                        <tbody>
                           @php
                           $otherTotalExpense= 0;
                           @endphp
                           @foreach ($otherDirectExpenses as $key =>$otherDirectExpense)
                           @php
                           $otherTotalExpense += getProjectedBudgetOtherDirect($year, $key);
                           $report = getReportDataOtherDirect($year, $key);
                           $months = $report->months ?? 12;
                           $cleaned_key = preg_replace('/[^A-Za-z0-9\s]/', '',str_replace(' ', '', $key)).'otherDirect';

                           @endphp

                           <tr class="details-row outerTrJs">
                              <td class="cursor-pointer border-t-none" colspan="5" style="min-width: 300px;" onclick="collapseExpendRows(this)">
                                 <div class="d-flex align-items-center gap-12">
                                    @if (count($otherDirectExpense)>0)
                                    <span class="accordionArrowJs transition-duration-point4" wire:ignore.self><img src="{{ asset('images/icons/table-chevron-circle-right.svg')}}" alt="img"></span>
                                    @endif
                                    @php
                                    if (is_numeric($key)) {
                                    $user = getLookUpDetail($key);
                                    echo $user->look_up_value ?? '';
                                    }else{
                                    echo $key;
                                    }
                                    @endphp
                                 </div>
                              </td>

                              <td style="min-width:170px;width:170px;" class="editable-td p-0 units-input-number border-t-none">
                                 <!-- €{{ isset($report->total_annual_budget) ? dutchCurrency($report->total_annual_budget) : 0 }} -->
                                 <span class="currency-sign">€</span>
                                 <input
                                    type="text"
                                    class="table-input project_expenses project-expenses-input totalAnnualBudgetJs{{ $cleaned_key }}"
                                    placeholder="0"
                                    value="{{ isset($report->total_annual_budget) ? dutchCurrency($report->total_annual_budget) : 0 }}"
                                    min="1" max="10000"
                                    data-type="currency"
                                    onkeyup="calculateReportEmployeeBudgetTotalAnnual('{{ $cleaned_key }}', 'totalAnnualBudgetJs','projectedBudgetJs', 'balanceJs')" />
                                 <span class="edit-pill">Edit</span>
                                 <button type="submit" class="save-table-btn" onclick="saveReportsData('{{ $key }}', 'amountJs', 'monthsJs', 'projectedBudgetJs', '{{ $year }}', true, '{{ $cleaned_key }}')">save</button>
                                 <div
                                    class="text-error-500 project-validation-error">
                                 </div>
                              </td>
                              <input type="hidden" class="projectedBudgetJs{{ $cleaned_key }}" value="{{ getProjectedBudgetOtherDirect($year, $key) }}">
                              <td style="min-width:170px;width:170px;" class="projected-budget-cell projected-budget-cell-bg-color border-t-none">€{{ dutchCurrency(getProjectedBudgetOtherDirect($year, $key)) }}</td>
                              @php
                              $otherDirectRemainingBalanace = isset($report->total_annual_budget) ? dutchCurrency(getProjectedBudgetOtherDirect($year, $key) - $report->total_annual_budget) : 0;
                              @endphp
                              <td style="min-width:170px;width:170px;" class="balance-cell border-t-none balanceJs{{ $cleaned_key }} {{ ($otherDirectRemainingBalanace ?? 0) < 0 ? 'text-error-500' : '' }}">€{{ $otherDirectRemainingBalanace }}</td>
                           </tr>

                           @foreach ($otherDirectExpense as $project => $projectsData)
                           <tr>
                              <td colspan="8" class="padding-0 border-b-none border-t-none">
                                 <table width="100%" class="inner-level-table" wire:ignore.self>
                                    <tbody>
                                       <tr class="innerTrJs">
                                          <td colspan="3">
                                             <div class="d-flex align-items-center gap-12">
                                                {{ $project }}
                                             </div>
                                          </td>
                                          <td style="min-width:170px;width:170px;" class="position-relative">
                                             <a href="{{ route('project.show', ['project' => $projectsData['project_id']]) }}" target=”_blank” class="position-absolute top-0 bottom-0 left-0 right-0"></a>
                                             <div class="link-to-project d-flex align-items-center gap-2">
                                                Go to project
                                                <span class="d-flex align-items-center"><img src="{{ asset('images/icons/take-to-arrow.svg')}}" alt="img"></span>
                                             </div>
                                          </td>
                                          <td style="min-width:170px;width:170px;"></td>
                                          <td style="min-width:170px;width:170px;" class="border-l-gray-300">€{{ dutchCurrency(getEmployeeUsedAmount($projectsData['project_id'], $key, $year, true)) }}</td>
                                          <td style="min-width:170px;width:170px;" class="border-l-gray-300"></td>
                                       </tr>
                                    </tbody>
                                 </table>
                              </td>
                           </tr>
                           @endforeach
                           @endforeach
                           @php
                           $otherTotalExpense += getProjectedBudgetOtherDirect($year, 6);
                           $report = getReportDataOtherDirect($year, 6);
                           $months = $report->months ?? 12;
                           $cleaned_key = preg_replace('/[^A-Za-z0-9\s]/', '',str_replace(' ', '', 6)).'otherDirect';
                           @endphp
                           <tr class="details-row outerTrJs">
                              <td class="cursor-pointer border-t-none" colspan="5" style="min-width: 300px;" onclick="collapseExpendRows(this)">
                                 <div class="d-flex align-items-center gap-12">
                                    <span class="accordionArrowJs transition-duration-point4" wire:ignore.self><img src="{{ asset('images/icons/table-chevron-circle-right.svg')}}" alt="img"></span>
                                    @php
                                    $user = getLookUpDetail(6);
                                    echo $user->look_up_value ?? '';
                                    @endphp
                                 </div>
                              </td>

                              <td style="min-width:170px;width:170px;" class="editable-td p-0 units-input-number border-t-none">
                                 <span class="currency-sign">€</span>
                                 <input
                                    type="text"
                                    class="table-input project_expenses project-expenses-input totalAnnualBudgetJs{{ $cleaned_key }}"
                                    placeholder="0"
                                    value="{{ isset($report->total_annual_budget) ? dutchCurrency($report->total_annual_budget) : 0 }}"
                                    min="1" max="10000"
                                    data-type="currency"
                                    onkeyup="calculateReportEmployeeBudgetTotalAnnual('{{ $cleaned_key }}', 'totalAnnualBudgetJs','projectedBudgetJs', 'balanceJs')" />
                                 <span class="edit-pill">Edit</span>
                                 <button type="submit" class="save-table-btn" onclick="saveReportsData('{{ 6 }}', 'amountJs', 'monthsJs', 'projectedBudgetJs', '{{ $year }}', true, '{{ $cleaned_key }}')">save</button>
                                 <div
                                    class="text-error-500 project-validation-error">
                                 </div>
                              </td>
                              <input type="hidden" class="projectedBudgetJs{{ $cleaned_key }}" value="{{ getProjectedBudgetOtherDirect($year, 6) }}">
                              <td style="min-width:170px;width:170px;" class="projected-budget-cell projected-budget-cell-bg-color border-t-none">€{{ dutchCurrency(getProjectedBudgetOtherDirect($year, 6)) }}</td>
                              @php
                              $otherDirectRemainingBalanace = isset($report->total_annual_budget) ? dutchCurrency(getProjectedBudgetOtherDirect($year, 6) - $report->total_annual_budget) : 0;
                              @endphp
                              <td style="min-width:170px;width:170px;" class="balance-cell border-t-none balanceJs{{ $cleaned_key }} {{ ($otherDirectRemainingBalanace ?? 0) < 0 ? 'text-error-500' : '' }}">€{{ $otherDirectRemainingBalanace }}</td>
                           </tr>
                           @foreach ($lineItemsODE as $project => $lineItemOD)

                           <tr>
                              <td colspan="8" class="padding-0 border-b-none border-t-none">
                                 <table width="100%" class="inner-level-table" wire:ignore.self>
                                    <tbody>
                                       <tr class="innerTrJs">
                                          <td colspan="3">
                                             <div class="d-flex align-items-center gap-12">
                                                {{ $lineItemOD->name ?? '-' }}
                                             </div>
                                          </td>
                                          <td style="min-width:170px;width:170px;"></td>
                                          <td style="min-width:170px;width:170px;" class="border-l-gray-300">€{{ dutchCurrency($lineItemOD->projects + calculateSumByOtherDirect('total_approved_cost', $lineItemOD->id, $year)) }}</td>
                                          <td style="min-width:170px;width:170px;" class="border-l-gray-300"></td>
                                       </tr>
                                    </tbody>
                                 </table>
                              </td>
                           </tr>
                           @endforeach
                        </tbody>
                        <tfoot>
                           <tr class="estimated-row bg-lite-gray">
                              <td colspan="5" class="border-t-none"><b>D. TOTAL OTHER DIRECT EXPENSES</b></td>
                              <td style="min-width:170px;width:170px;" class="border-t-none"><b>€{{ dutchCurrency($totalOtherApprovedBudget) }}</b></td>
                              <td style="min-width:170px;width:170px;" class="projected-budget-total-cell border-t-none"><b>€{{ dutchCurrency($otherTotalExpense) }}</b></td>
                              <td style="min-width:170px;width:170px;" class="balance-cell border-t-none {{ ($otherTotalRemainingBudget ?? 0) < 0 ? 'text-error-500' : '' }}"><b>€{{ dutchCurrency($otherTotalRemainingBudget) }}</b></td>
                           </tr>
                        </tfoot>
                     </table>
                  </td>
               </tr>
            </tbody>
            <tfoot>
               <tr class="estimated-row bg-lite-gray">
                  <td colspan="5" class="border-b-gray-300"><b>E. Total Operational Expenses/YEAR(B+D)</b></td>
                  <td class="border-b-gray-300" style="min-width:170px;width:170px;"><b>€{{ dutchCurrency($totalOtherApprovedBudget + $totalApprovedBudget) }}</b></td>
                  <td class="border-b-gray-300 projected-budget-total-cell" style="min-width:170px;width:170px;"><b>€{{ dutchCurrency($empoyeeProjectedBudget + $otherTotalExpense) }}</b> </td>
                  <td class="border-b-gray-300 balance-total-cell {{ (($otherTotalRemainingBudget) + ($totalRemainingBudget) ?? 0) < 0 ? 'text-error-500' : '' }}" style="min-width:170px;width:170px;"><b>€{{ dutchCurrency(($otherTotalRemainingBudget) + ($totalRemainingBudget)) }}</b></td>
               </tr>
            </tfoot>
         </table>

         <table width="100%" class="reports-table indirect-expense-cat-table total-annual-budget-table">
            <thead>
               <tr class="secondary-header">
                  <th colspan="8" class="border-t-gray-300"><b>F. Indirect Expense Categories</b></th>
               </tr>
            </thead>
            <tbody>
               @php
               $projectedBudgetTotal = 0;
               $otherDirectTotalForAllCat = 0;
               @endphp
               @foreach ($categories as $category)
               @php
               $otherDirectTotal = calculateSumODE('total_approved_cost', $category->id, $year);
               $otherDirectTotalForAllCat += $otherDirectTotal;
               $report = getReportDataIndirect($year, $category->id);
               $projectedBudget = isset($report->total_annual_budget) ? $report->total_annual_budget : 0;
               $projectedBudgetTotal += $projectedBudget;

               @endphp
               <tr class="details-row">
                  <td class="count">{{ $loop->index + 1 }}</td>
                  <td colspan="2" class="border-l-none" style="min-width: 437px;width: 437px;">{{ $category->name }}</td>
                  <td style="min-width:170px;width:170px;" class="border-l-none"></td>
                  <td style="min-width:170px;width:170px;" class="border-l-none"></td>
                  <td style="min-width:170px;width:170px;" class="editable-td p-0 units-input-number">
                     <span class="currency-sign">€</span>
                     <input
                        type="text"
                        class="table-input project_expenses project-expenses-input totalAnnualBudgetCatJs{{$category->id}}"
                        placeholder="0"
                        value="{{ isset($report->total_annual_budget) ? dutchCurrency($report->total_annual_budget) : 0 }}"
                        min="1" max="10000"
                        data-type="currency" onkeyup="calculateReportEmployeeBudgetTotalAnnual('{{ $category->id }}', 'totalAnnualBudgetCatJs','projectedBudgetCatJs', 'balanceCatJs')" />
                     <span class="edit-pill">Edit</span>
                     <button type="submit" class="save-table-btn" onclick="saveReportsDataIndirect('{{ $category->id }}', 'projectedBudgetCatJs', '{{ $year }}', true)">save</button>
                     <div
                        class="text-error-500 project-validation-error">
                     </div>
                  </td>
                  <td style="min-width:170px;width:170px;" class="projected-budget-cell projected-budget-cell-bg-color">
                     <input type="hidden" class="projectedBudgetCatJs{{ $category->id }}" value="{{ calculateSumIEByCat('total_approved_cost', $category->id, $year) + $otherDirectTotal }}">
                     €{{ dutchCurrency(calculateSumIEByCat('total_approved_cost', $category->id, $year) + $otherDirectTotal) }}
                  </td>
                  <td style="min-width:170px;width:170px;" class="balance-cell balanceCatJs{{ $category->id }} {{ ((calculateSumIEByCat('total_approved_cost', $category->id, $year) + $otherDirectTotal) - $projectedBudget ?? 0) < 0 ? 'text-error-500' : '' }}">€{{ dutchCurrency((calculateSumIEByCat('total_approved_cost', $category->id, $year) + $otherDirectTotal) - $projectedBudget) }}</td>
               </tr>
               @endforeach
            </tbody>

            <tfoot>
               <tr class="estimated-row bg-lite-gray">
                  <td colspan="2" class="border-b-gray-300"><b>G. TOTAL INDIRECT EXPENSES</b></td>
                  <td style="min-width:170px;width:170px;" class="border-b-gray-300"></td>
                  <td style="min-width:170px;width:170px;" class="border-b-gray-300"></td>
                  <td style="min-width:170px;width:170px;" class="border-b-gray-300"></td>
                  <td style="min-width:170px;width:170px;" class="border-b-gray-300"><b>€{{ dutchCurrency($projectedBudgetTotal) }}</b></td>
                  <td style="min-width:170px;width:170px;" class="projected-budget-total-cell border-b-gray-300"><b>€{{ dutchCurrency(calculateSumIEByAllCat('total_approved_cost', $year) + $otherDirectTotalForAllCat) }}</b></td>
                  <td style="min-width:170px;width:170px;" class="balance-total-cell border-b-gray-300 {{ ((calculateSumIEByAllCat('total_approved_cost', $year) + $otherDirectTotalForAllCat) - $projectedBudgetTotal ?? 0) < 0 ? 'text-error-500' : '' }}"><b>€{{ dutchCurrency((calculateSumIEByAllCat('total_approved_cost', $year) + $otherDirectTotalForAllCat) - $projectedBudgetTotal) }}</b></td>
               </tr>
               <tr class="estimated-row">
                  <td colspan="2" class="border-b-gray-300" style="padding: 6px 12px;"></td>
                  <td style="min-width:170px;width:170px; padding: 6px 12px;" class="border-b-gray-300"></td>
                  <td style="min-width:170px;width:170px; padding: 6px 12px;" class="border-b-gray-300"></td>
                  <td style="min-width:170px;width:170px; padding: 6px 12px;" class="border-b-gray-300"></td>
                  <td style="min-width:170px;width:170px; padding: 6px 12px;" class="border-b-gray-300"></td>
                  <td style="min-width:170px;width:170px; padding: 6px 12px;" class="projected-budget-total-cell border-b-gray-300"></td>
                  <td style="min-width:170px;width:170px; padding: 6px 12px;" class="balance-total-cell border-b-gray-300"></td>
               </tr>
               <tr class="estimated-row bg-lite-gray">
                  <td colspan="2" class="border-b-gray-300"><b>H. DIRECT COSTS (E-G)</b></td>
                  <td style="min-width:170px;width:170px;" class="border-b-gray-300"></td>
                  <td style="min-width:170px;width:170px;" class="border-b-gray-300"></td>
                  <td style="min-width:170px;width:170px;" class="border-b-gray-300"> </td>
                  <td style="min-width:170px;width:170px;" class="border-b-gray-300"><b>€{{ dutchCurrency(($totalOtherApprovedBudget + $totalApprovedBudget) - $projectedBudgetTotal) }}</b></td>
                  <td style="min-width:170px;width:170px;" class="projected-budget-total-cell border-b-gray-300"><b>€{{ dutchCurrency((($empoyeeProjectedBudget + $otherTotalExpense) - (calculateSumIEByAllCat('total_approved_cost', $year)+ $otherDirectTotalForAllCat))) }}</b></td>
                  <td style="min-width:170px;width:170px;" class="balance-total-cell border-b-gray-300"><b>€{{ dutchCurrency(($otherTotalRemainingBudget + $totalRemainingBudget) - ((calculateSumIEByAllCat('total_approved_cost', $year) + $otherDirectTotalForAllCat) - $projectedBudgetTotal)) }}</b>
                  </td>
               </tr>
            </tfoot>
         </table>
         <table width="100%" class="reports-table indirect-rate-table-main collapsed mb-100 total-annual-budget-table">
            <tbody>
               @php
               // Calculating the required values
               $otherDirectTotalApproved = (calculateSumIEByAllCat('total_approved_cost', $year) + $otherDirectTotalForAllCat) ;

               // Initializing percentages to 0
               $approvedPercentage = 0;
               $projectedPercentage = 0;
               $balancePercentage = 0;

               // Total Operational Expenses (E)
               $totalOperationalExpenses = ($totalOtherApprovedBudget + $totalApprovedBudget) - $projectedBudgetTotal;

               // Total Indirect Expenses (G)
               $totalIndirectExpenses = $projectedBudgetTotal;


               // Calculate approved percentage (Indirect Rate G/E)
               if ($totalOperationalExpenses != 0) {
               $approvedPercentage = ($totalIndirectExpenses * 100) / $totalOperationalExpenses;
               }

               // Calculate projected percentage
               if ((($empoyeeProjectedBudget + $otherTotalExpense) - $otherDirectTotalApproved) != 0) {
               $projectedPercentage = ($otherDirectTotalApproved * 100) / (($empoyeeProjectedBudget + $otherTotalExpense) - $otherDirectTotalApproved);
               }

               // Calculate balance percentage
               if (($totalRemainingBudget + $otherTotalRemainingBudget) != 0) {
               $balancePercentage = (($totalIndirectExpenses - $otherDirectTotalApproved) * 100) / (($totalRemainingBudget + $otherTotalRemainingBudget) - ($otherDirectTotalApproved - $projectedBudgetTotal));
               }
               @endphp

               <tr class="estimated-row bg-lite-gray outerTrJs">
                  <td colspan="3" class="border-b-gray-300 cursor-pointer" style="min-width: 471px;width: 471px;" onclick="collapseExpendRows(this)">
                     <div class="d-flex align-items-center gap-12">
                        <span class="accordian-arrow accordionArrowJs">
                           <img src="{{ asset('images/icons/table-chevron-circle-right.svg') }}" alt="img">
                        </span>
                        <b>I. INDIRECT RATE (G/H)</b>
                     </div>
                  </td>
                  <td style="min-width:170px;width:170px;" class="border-b-gray-300 cursor-pointer" onclick="collapseExpendRows(this)"></td>
                  <td style="min-width:170px;width:170px;" class="border-b-gray-300 cursor-pointer" onclick="collapseExpendRows(this)"></td>
                  <td style="min-width:170px;width:170px;" class="border-b-gray-300 cursor-pointer" onclick="collapseExpendRows(this)">
                     <b>{{ round($approvedPercentage, 2) ?? 0 }}%</b>
                  </td>
                  <td style="min-width:170px;width:170px;" class="projected-budget-total-cell border-b-gray-300 cursor-pointer" onclick="collapseExpendRows(this)">
                     <b>{{ round($projectedPercentage, 2) ?? 0 }}%</b>
                  </td>
                  <td style="min-width:170px;width:170px;" class="balance-total-cell border-b-gray-300 cursor-pointer" onclick="collapseExpendRows(this)">
                     <b></b>
                  </td>
               </tr>

               <tr>
                  <td colspan="8" class="padding-0 border-b-none border-t-none">
                     <table width="100%" class="inner-level-table indirect-rate-table">
                        <thead>
                           <tr>
                              <th colspan="2">
                                 Project Name
                              </th>
                              <th style="min-width:170px;width:170px;" class="border-l-gray-300">Approved Annual Rate</th>
                              <th style="min-width:170px;width:170px;" class="border-l-none"></th>
                              <th style="min-width:170px;width:170px;" class="border-l-none"></th>
                           </tr>
                        </thead>
                        <tbody>
                           @foreach ($projects as $project)
                           <tr>
                              <td>{{ $project->project_name }}</td>
                              <td style="min-width:170px;width:170px;" class="position-relative">
                                 <a href="{{ route('project.show', ['project' => $project->id]) }}" target=”_blank” class="position-absolute top-0 bottom-0 left-0 right-0"></a>
                                 <div class="link-to-project d-flex align-items-center gap-2">
                                    Go to project
                                    <span class="d-flex align-items-center"><img src="{{ asset('images/icons/take-to-arrow.svg')}}" alt="img"></span>
                                 </div>
                              </td>
                              <td style="min-width:170px;width:170px;">{{ $project->indirect_rate }}%</td>
                              <td style="min-width:170px;width:170px;" class="border-l-none"></td>
                              <td style="min-width:170px;width:170px;" class="border-l-none">
                              </td>
                           </tr>
                           @endforeach
                        </tbody>
                     </table>
                  </td>
               </tr>
            </tbody>
         </table>

         <table width="100%" class="reports-table income-by-donor-table total-annual-budget-table mb-12">
            <thead>
               <tr class="secondary-header">
                  <th colspan="8" class="border-t-gray-300"><b>J. Projected income by donor in FY {{ $year }}</b></th>
               </tr>
            </thead>
            <tbody>
               @php
               $totalDonorBudget = 0;
               @endphp
               @foreach ($donors as $donor)

               @php

               $totalDonorBudget += $donor->total_budget;
               @endphp
               <tr class="details-row">
                  <td class="count">{{ $loop->index + 1 }}</td>
                  <td colspan="2" class="border-l-none" style="min-width: 437px;width: 437px;">{{ $donor->name }}</td>
                  <td style="min-width:170px;width:170px;" class="border-l-none"></td>
                  <td style="min-width:170px;width:170px;" class="border-l-none"></td>
                  <td style="min-width:170px;width:170px;">€{{ dutchCurrency($donor->total_budget) }}</td>
                  <td style="min-width:170px;width:170px;" class="projected-budget-cell"></td>
                  <td style="min-width:170px;width:170px;" class="balance-cell border-l-none"></td>
               </tr>
               @endforeach
               @if(count($donors)==0)
               <tr>
                  <td class="count"></td>
                  <td colspan="2" class="border-l-none" style="min-width: 437px;width: 437px;">
                     <span class="text-gray-50 text-xs font-regular">You don’t have any data yet</span>
                  </td>
                  <td style="min-width:170px;width:170px;" class="border-l-none"></td>
                  <td style="min-width:170px;width:170px;" class="border-l-none"></td>
                  <td style="min-width:170px;width:170px;" class="border-l-none"></td>
                  <td style="min-width:170px;width:170px;" class="projected-budget-cell dborder-l-none"></td>
                  <td style="min-width:170px;width:170px;" class="balance-cell border-l-none"></td>
               </tr>
               @endif
            </tbody>
            <tfoot>
               <tr class="estimated-row bg-lite-gray">
                  <td colspan="3" class="border-b-gray-300"><b>TOTAL SECURED FUNDING FY {{ $year }}</b></td>
                  <td style="min-width:170px;width:170px;" class="border-b-gray-300"></td>
                  <td style="min-width:170px;width:170px;" class="border-b-gray-300"></td>
                  <td class="border-b-gray-300"><b>€{{ dutchCurrency($totalDonorBudget) }}</b></td>
                  <td style="min-width:170px;width:170px;" class="projected-budget-total-cell border-b-gray-300"></td>
                  <td style="min-width:170px;width:170px;" class="balance-total-cell border-b-gray-300"></td>
               </tr>
               <tr class="estimated-row bg-lite-gray">
                  <td colspan="3" class="border-b-gray-300"><b>DIFFERENCE TOTAL SECURED FUNDING - (B+D) </b>
                  </td>
                  <td style="min-width:170px;width:170px;" class="border-b-gray-300"></td>
                  <td style="min-width:170px;width:170px;" class="border-b-gray-300"></td>
                  <td class="border-b-gray-300"><b>€{{ dutchCurrency($totalDonorBudget - ($totalOtherApprovedBudget + $totalApprovedBudget)) }}</b></td>
                  <td style="min-width:170px;width:170px;" class="projected-budget-total-cell border-b-gray-300"></td>
                  <td style="min-width:170px;width:170px;" class="balance-total-cell border-b-gray-300"></td>
               </tr>
            </tfoot>
         </table>
      </div>
   </div>
</div>
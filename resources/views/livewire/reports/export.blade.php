@php
$projectYears = getYearList();
@endphp
<style>
   @media print {
    /* Reset margin and padding for the page */
    @page {
        margin:20px 10px;
        padding: 0px;
    }
    .projected-budget-cell-bg-color {
    background-color: #e8f1f6 !important;
  }
  html {
  -webkit-print-color-adjust: exact;
}
   }
</style>
<div class="main-content reports-container reports-pdf-container" role="main" id="myDiv">
   <div class="d-flex flex-wrap align-items-center gap-12 mb-4">
      <h6 class="text-sm font-medium text-gray-800 mb-0 me-auto">Total Annual Budget</h6>
      <h5 class="text-gray-800 text-sm font-semibold mb-0">Year: {{$year}}</h5>
   </div>
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
      <div class="row">
         <div class="col-md-4">
            <div class="d-flex flex-column gap-4 h-100">
               <div class="bordered-card h-100 p-3 d-flex flex-column justify-content-between">
                  <div class="text-gray-500 text-sm font-semibold">Projected Annual Budget <span>{{ $year }}</span></div>
                  <p class="text-primary-500 text-sm font-bold mb-0 text-break">€{{ dutchCurrency($totalOtherApprovedBudget + $totalApprovedBudget)   }}</p>
               </div>
               <div class="bordered-card h-100 p-3 d-flex flex-column justify-content-between">
                  <div class="d-flex align-items-start gap-2 justify-content-between">
                     <div class="text-gray-500 text-sm font-semibold">Project budgets</div>
                     <div class="text-white bg-success-500 text-sm font-semibold percentage-text">
                    <?php
                                    if ($totalApprovedBudget != 0) {
                                          $percentage = (($totalProjectedBudgetWithoutCat+$otherTotalProjectedBudget) / ($totalOtherApprovedBudget + $totalApprovedBudget)) * 100;
                                          echo number_format($percentage) . "%";
                                    } else {
                                          echo '0%';
                                    }
                                 ?>
                     </div>
                  </div>
                  <p class="text-primary-500 text-sm font-bold mb-0 text-break">€{{ dutchCurrency($totalProjectedBudgetWithoutCat+$otherTotalProjectedBudget) }}
                  </p>
               </div>
               <div class="bordered-card h-100 p-3 d-flex flex-column justify-content-between">
                  <div class="text-gray-500 text-sm font-semibold">Difference</div>

                  @php
                     $yearDifference = ($totalProjectedBudgetWithoutCat+$otherTotalProjectedBudget) - ($totalOtherApprovedBudget + $totalApprovedBudget);
                  @endphp
                  <p class="text-primary-500 text-sm font-bold mb-0 text-break {{ ($yearDifference ?? 0) < 0 ? 'text-error-500' : '' }}">€{{ dutchCurrency($yearDifference)  }}</p>

               </div>
            </div>
         </div>
         <div class="col-md-8">
            <div class="bordered-card h-100 p-3">
               <h5 class="text-gray-800 text-sm font-semibold mb-0">Income by donor</h5>
               <figure class="highcharts-figure mx-auto">
                  <div id="income-by-donor" style="height: 300px;"></div>
               </figure>
            </div>
         </div>
      </div>
      <div class="table-responsive report-pdf mt-4 pt-4">
         <table width="100%" class="reports-table total-annual-budget-table mb-12">
               <!-- thead -->
               <tr class="primary-header">
                  @if ($lastUpdate)
                     <td rowspan="2" colspan="3" style="vertical-align: middle;min-width:150px;width:150px;"><b>LAST UPDATE: {{ \Carbon\Carbon::parse($lastUpdate->updated_at)->isoFormat('D MMMM YYYY') }}</b></td>
                  @else
                  <td rowspan="2" colspan="3" style="vertical-align: middle;min-width:150px;width:150px;"><b>LAST UPDATE: Not updated yet</b></td>
                  @endif
                  
                  <td style="min-width:100px;width:100px;vertical-align: top;"><b>Per Unit {{ $year }}</b></td>
                  <td style="min-width:100px;width:100px;vertical-align: top;"><b>Units</b></td>
                  <td style="min-width:100px;width:100px;vertical-align: top;"><b>Projected Annual Budget</b></td>
                  <td style="min-width:100px;width:100px;vertical-align: top;"><b>Project budgets</b></td>
                  <td style="min-width:100px;width:100px;vertical-align: top;"><b>Balance</b></td>
               </tr>
               <tr class="primary-header">
                  <td style="min-width:100px;width:100px;vertical-align: top;" class="border-l-gray-300"><b>Payroll /month</b></td>
                  <td style="min-width:100px;width:100px;vertical-align: top;"><b>Unit Costs</b></td>
                  <td style="min-width:100px;width:100px;vertical-align: top;"><b>{{ $year }}</b></td>
                  <td style="min-width:100px;width:100px;vertical-align: top;" class="projected-budget-cell projected-budget-cell-bg-color"></td>
                  <td style="min-width:100px;width:100px;vertical-align: top;" class="balance-th"><span class="title"><b>Should be Zero</b></span></td>
               </tr>
               <!-- thead ends-->
               <!-- tbody-->
               <tr>
                  <td colspan="8" class="padding-0 border-b-none">
                     <table width="100%">
                        <!-- thead-->
                        <tr class="secondary-header">
                           <td colspan="8"><b>A. Gross Salary + Employer Taxes And Contributions</b></td>
                        </tr>
                        <!-- thead ends-->
                        <!-- tbody-->
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
                           <td class="{{ ($subProjectsCount >0) ? 'cursor-pointer' : '' }}" colspan="2" style="min-width:150px;width:150px;word-break:break-all;">
                                 {{ $employeeName }}
                           </td>
                           <td style="min-width:100px;width:100px;word-break:break-all;" >{{ getEmployeeTotalPercentage($year, $employeeId) }}%</td>
                           <td style="min-width:100px;width:100px;word-break:break-all;">
                              €{{ isset($report->monthly_amount) ? dutchCurrency($report->monthly_amount) : '' }}
                              
                           </td>
                           <td style="min-width:100px;width:100px;word-break:break-all;">
                              {{ explode('.', $months)[0] ?? $months }} {{ count(explode('.', $months)) > 1 ? 'Months' : 'Month' }}
                           </td>
                           <td style="min-width:100px;width:100px;word-break:break-all;">
                              €{{ isset($report->total_annual_budget) ? dutchCurrency($report->total_annual_budget) : 0 }}
                              
                           </td>
                           <input type="hidden" class="projectedBudgetJs{{ $employeeId }}" value="{{ getProjectedBudget($year, $employeeId) }}">
                           <td style="min-width:100px;width:100px;word-break:break-all;" class="projected-budget-cell projected-budget-cell-bg-color">€{{ dutchCurrency(getProjectedBudget($year, $employeeId)) }}</td>
                           @php
                              $emmployeeRemainingAmount = isset($report->total_annual_budget) ? dutchCurrency(getProjectedBudget($year, $employeeId) - $report->total_annual_budget) : 0;
                           @endphp
                           <td style="min-width:100px;width:100px;word-break:break-all;" class="balance-cell balanceJs{{ $employeeId }} {{ ($emmployeeRemainingAmount ?? 0) < 0 ? 'text-error-500' : '' }}">€{{ $emmployeeRemainingAmount }}</td>
                        </tr>
                        @endforeach
                        <!-- tbody ends-->
                        <!-- tfoot-->
                        <tr class="estimated-row">
                           <td colspan="5"><b>B. TOTAL PERSONNEL</b></td>
                           <td style="min-width:100px;width:100px;word-break:break-all;vertical-align: top;"><b>€{{ dutchCurrency($totalApprovedBudget) }}</b></td>
                           <td style="min-width:100px;width:100px;word-break:break-all;vertical-align: top;" class="projected-budget-total-cell"><b>€{{ dutchCurrency($empoyeeProjectedBudget) }}</b></td>
                           <td style="min-width:100px;width:100px;word-break:break-all;vertical-align: top;" class="balance-cell {{ ($totalRemainingBudget ?? 0) < 0 ? 'text-error-500' : '' }}"><b>€{{ dutchCurrency($totalRemainingBudget) }}</b></td>
                        </tr>
                        <!-- tfoot ends-->

                     </table>
                  </td>
               </tr>
               <tr>
                  <td colspan="8" class="padding-0 border-b-none border-t-none">
                     <table width="100%">
                           <!-- thead-->
                           <tr class="secondary-header">
                              <td colspan="8"><b>C. Other Direct Expenses</b></td>
                           </tr>
                        
                           @php
                           $otherTotalExpense= 0;
                           @endphp
                           
                           @foreach ($otherDirectExpenses as $key =>$otherDirectExpense)
                              @php
                                 $otherTotalExpense += getProjectedBudgetOtherDirect($year, $key); 
                                 $report = getReportDataOtherDirect($year, $key);
                                 $months = $report->months ?? 12;
                              @endphp
                           <tr class="details-row outerTrJs">
                              <td class="cursor-pointer border-t-none" colspan="5" style="min-width: 300px;" onclick="collapseExpendRows(this)">
                                 @php
                                 if (is_numeric($key)) {                                        
                                       $user = getLookUpDetail($key); 
                                       echo $user->look_up_value ?? '';
                                    }else{
                                       echo $key;
                                    }
                                 @endphp
                              </td>
                              <td style="min-width:100px;width:100px;word-break:break-all;" class="border-t-none" >
                              €{{ isset($report->total_annual_budget) ? dutchCurrency($report->total_annual_budget) : 0 }}
                           
                              </td>

                              <input type="hidden" class="projectedBudgetJs{{ str_replace(' ', '', $key) }}" value="{{ getProjectedBudgetOtherDirect($year, $key) }}">
                              <td style="min-width:100px;width:100px;word-break:break-all;" class="projected-budget-cell projected-budget-cell-bg-color border-t-none" >€{{ dutchCurrency(getProjectedBudgetOtherDirect($year, $key)) }}</td>

                              @php
                                 $otherDirectRemainingBalanace = isset($report->total_annual_budget) ? dutchCurrency(getProjectedBudgetOtherDirect($year, $key) - $report->total_annual_budget) : 0;
                              @endphp
                              <td style="min-width:100px;width:100px;word-break:break-all;" class="balance-cell border-t-none balanceJs{{ str_replace(' ', '', $key) }} {{ ($otherDirectRemainingBalanace ?? 0) < 0 ? 'text-error-500' : '' }}" >€{{ $otherDirectRemainingBalanace }}</td>
                           </tr>

                              @foreach ($otherDirectExpense as $project => $projectsData) 
                              <tr>
                                 <td colspan="8" class="padding-0 border-b-none">
                                    <table width="100%" class="inner-level-table" wire:ignore.self>
                                       <!-- tbody-->
                                          <tr class="innerTrJs">
                                             <td style="word-break:break-all;" colspan="3">
                                                {{ $project }}
                                             </td>
                                             <td style="min-width:100px;width:100px;word-break:break-all;" class="position-relative">
                                                <a href="{{ route('project.show', ['project' => $projectsData['project_id']]) }}" class="position-absolute top-0 bottom-0 left-0 right-0"></a>
                                                <div class="link-to-project d-flex align-items-center gap-2">
                                                   Go to project
                                                   <span class="d-flex align-items-center"><img src="{{ asset('images/icons/take-to-arrow.svg')}}" alt="img"></span>
                                                </div>
                                             </td>
                                             <td style="min-width:100px;width:100px;word-break:break-all;"></td>
                                             <td style="min-width:100px;width:100px;word-break:break-all;" class="border-l-gray-300">€{{ dutchCurrency(getEmployeeUsedAmount($projectsData['project_id'], $key, $year, true)) }}</td>
                                             <td style="min-width:100px;width:100px;word-break:break-all;" class="border-l-gray-300"></td>
                                          </tr>
                                    <!-- tbody ends-->
                                    </table>
                                 </td>
                              </tr> 
                              @endforeach
                           @endforeach
                           
                              @php
                                 $otherTotalExpense += getProjectedBudgetOtherDirect($year, 6); 
                                 $report = getReportDataOtherDirect($year, 6);
                                 $months = $report->months ?? 12;
                              @endphp
                           <tr class="details-row outerTrJs">
                              <td class="cursor-pointer border-t-none" colspan="5" style="min-width: 300px;" onclick="collapseExpendRows(this)">
                                 @php                                     
                                       $user = getLookUpDetail(6); 
                                       echo $user->look_up_value ?? '';
                                 @endphp
                              </td>
                              <td style="min-width:100px;width:100px;word-break:break-all;" class="border-t-none" >
                                 €{{ isset($report->total_annual_budget) ? dutchCurrency($report->total_annual_budget) : 0 }}
                              </td>
                              <input type="hidden" class="projectedBudgetJs{{ str_replace(' ', '', 6) }}" value="{{ getProjectedBudgetOtherDirect($year, 6) }}">
                              <td style="min-width:100px;width:100px;word-break:break-all;" class="projected-budget-cell projected-budget-cell-bg-color border-t-none" >€{{ dutchCurrency(getProjectedBudgetOtherDirect($year, 6)) }}</td>
                              @php
                                 $otherDirectRemainingBalanace = isset($report->total_annual_budget) ? dutchCurrency(getProjectedBudgetOtherDirect($year, 6) - $report->total_annual_budget) : 0;
                              @endphp
                              <td style="min-width:100px;width:100px;word-break:break-all;" class="balance-cell border-t-none balanceJs{{ str_replace(' ', '', 6) }} {{ ($otherDirectRemainingBalanace ?? 0) < 0 ? 'text-error-500' : '' }}" >€{{ $otherDirectRemainingBalanace }}</td>
                           </tr>
                              @foreach ($lineItemsODE as $project => $lineItemOD) 
                              <tr>
                                 <td colspan="8" class="padding-0 border-b-none">
                                    <table width="100%" class="inner-level-table" wire:ignore.self>
                                       <!-- tbody-->
                                          <tr class="innerTrJs">
                                             <td style="word-break:break-all;" colspan="3">
                                                {{ $lineItemOD->name ?? '-' }}
                                             </td>
                                       
                                             <td style="min-width:100px;width:100px;word-break:break-all;"></td>
                                             <td style="min-width:100px;width:100px;word-break:break-all;" class="border-l-gray-300">€{{ dutchCurrency($lineItemOD->projects + calculateSumByOtherDirect('total_approved_cost', $lineItemOD->id, $year)) }}</td>
                                             <td style="min-width:100px;width:100px;word-break:break-all;" class="border-l-gray-300"></td>
                                          </tr>
                                    <!-- tbody ends-->
                                    </table>
                                 </td>
                              </tr> 
                              @endforeach
            
                           <!-- tbody ends-->
                           <!-- tfoot-->
                           <tr class="estimated-row bg-lite-gray">
                              <td colspan="5" class="border-t-none"><b>D. TOTAL OTHER DIRECT EXPENSES</b></td>
                              <td style="min-width:100px;width:100px;word-break:break-all;vertical-align: top;" class="border-t-none"><b>€{{ dutchCurrency($totalOtherApprovedBudget) }}</b></td>
                              <td style="min-width:100px;width:100px;word-break:break-all;vertical-align: top;" class="projected-budget-total-cell border-t-none"><b>€{{ dutchCurrency($otherTotalExpense) }}</b></td>
                              <td style="min-width:100px;width:100px;word-break:break-all;vertical-align: top;" class="balance-cell border-t-none{{ ($otherTotalRemainingBudget ?? 0) < 0 ? 'text-error-500' : '' }}"><b>€{{ dutchCurrency($otherTotalRemainingBudget) }}</b></td>
                           </tr>
                           <!-- tfoot ends-->
                     </table>
                  </td>
               </tr>
               <!-- tbody ends-->
               <!-- tfoot-->
               <tr class="estimated-row bg-lite-gray">
                  <td colspan="5" class="border-b-gray-300"><b>E. TOTAL PROJECT OPERATIONAL EXPENSES/YEAR(B+D)</b></td>
                  <td class="border-b-gray-300" style="min-width:100px;width:100px;word-break:break-all;vertical-align: top;"><b>€{{ dutchCurrency($totalOtherApprovedBudget + $totalApprovedBudget) }}</b></td>
                  <td class="border-b-gray-300 projected-budget-total-cell" style="min-width:100px;width:100px;word-break:break-all;vertical-align: top;"><b>€{{ dutchCurrency($empoyeeProjectedBudget + $otherTotalExpense) }} </b></td>
                  <td class="border-b-gray-300 balance-total-cell {{ (($otherTotalRemainingBudget) + ($totalRemainingBudget) ?? 0) < 0 ? 'text-error-500' : '' }}" style="min-width:100px;width:100px;word-break:break-all;vertical-align: top;"><b>€{{ dutchCurrency(($otherTotalRemainingBudget) + ($totalRemainingBudget)) }}</b></td>
               </tr>
               <!-- tfoot ends-->                   
         </table>
   
         <table width="100%" class="reports-table indirect-expense-cat-table">
               <!-- thead-->
               <tr class="secondary-header">
                  <td colspan="8" class="border-t-gray-300"><b>F. Indirect Expense Categories</b></td>
               </tr>
               <!-- thead ends-->
               <!-- tbody-->
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
                  <td colspan="2" class="border-l-none" style="word-break:break-all;">{{ $category->name }}</td>
                  <td style="min-width:100px;width:100px;" class="border-l-none"></td>
                  <td style="min-width:100px;width:100px;" class="border-l-none"></td>
                  <td style="min-width:100px;width:100px;word-break:break-all;" class="">
                  €{{ isset($report->total_annual_budget) ? dutchCurrency($report->total_annual_budget) : '' }}
               
                  </td>
                  <td style="min-width:100px;width:100px;word-break:break-all;" class="projected-budget-cell projected-budget-cell-bg-color">
                     <input type="hidden" class="projectedBudgetCatJs{{ $category->id }}" value="{{ calculateSumIEByCat('total_approved_cost', $category->id) }}">
                  €{{ dutchCurrency(calculateSumIEByCat('total_approved_cost', $category->id, $year) + $otherDirectTotal) }}
                  </td>
                  <td style="min-width:100px;width:100px;word-break:break-all;" class="balance-cell balanceCatJs{{ $category->id }} {{ ((calculateSumIEByCat('total_approved_cost', $category->id, $year) + $otherDirectTotal) - $projectedBudget ?? 0) < 0 ? 'text-error-500' : '' }}">€{{ dutchCurrency((calculateSumIEByCat('total_approved_cost', $category->id, $year) + $otherDirectTotal) - $projectedBudget) }}</td>
               </tr>
               @endforeach
               <!-- tbody ends-->
               <!-- tfoot-->
               <tr class="estimated-row bg-lite-gray">
                  <td colspan="5" class="border-b-gray-300" style="word-break: break-word;"><b>G. TOTAL INDIRECT EXPENSES</b></td>
                  <td style="min-width:100px;width:100px;word-break:break-all;vertical-align: top;" class="border-b-gray-300"><b>€{{ dutchCurrency($projectedBudgetTotal) }}</b></td>
                  <td style="min-width:100px;width:100px;word-break:break-all;vertical-align: top;" class="projected-budget-total-cell border-b-gray-300"><b>€{{ dutchCurrency(calculateSumIEByAllCat('total_approved_cost', $year) + $otherDirectTotalForAllCat) }}</b></td>
                  <td style="min-width:100px;width:100px;word-break:break-all;vertical-align: top;" class="balance-total-cell border-b-gray-300 {{ ((calculateSumIEByAllCat('total_approved_cost', $year) + $otherDirectTotalForAllCat) - $projectedBudgetTotal ?? 0) < 0 ? 'text-error-500' : '' }}"><b>€{{ dutchCurrency((calculateSumIEByAllCat('total_approved_cost', $year) + $otherDirectTotalForAllCat) - $projectedBudgetTotal) }}</b></td>
               </tr>
      
               <tr class="estimated-row bg-lite-gray">
                  <td colspan="5" class="border-b-gray-300"><b>H. DIRECT COSTS (E-G)</b></td>
                  <td style="min-width:100px;width:100px;word-break:break-all;vertical-align: top;" class="border-b-gray-300"><b>€{{ dutchCurrency(($totalOtherApprovedBudget + $totalApprovedBudget) - $projectedBudgetTotal) }}</b></td>
                  <td style="min-width:100px;width:100px;word-break:break-all;vertical-align: top;" class="projected-budget-total-cell border-b-gray-300"><b>€{{ dutchCurrency((($empoyeeProjectedBudget + $otherTotalExpense) - (calculateSumIEByAllCat('total_approved_cost', $year)+ $otherDirectTotalForAllCat))) }}</b></td>
                  <td style="min-width:100px;width:100px;word-break:break-all;vertical-align: top;" class="balance-total-cell border-b-gray-300"><b>€{{ dutchCurrency(($otherTotalRemainingBudget + $totalRemainingBudget) - ((calculateSumIEByAllCat('total_approved_cost', $year) + $otherDirectTotalForAllCat) - $projectedBudgetTotal)) }}</b>
                  </td>

               </tr>
               <!-- tfoot ends-->
         </table>
         <table width="100%" class="reports-table indirect-rate-table-main collapsed mb-100">
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
               <tr class="estimated-row bg-lite-gray outerTrJs" >
                  <td colspan="2" class="border-b-gray-300">
                        <b>I. INDIRECT RATE (G/H)</b>
                  </td>
                  <td style="min-width:100px;width:100px;word-break: break-all;" class="border-b-gray-300"></td>
                  <td style="min-width:100px;width:100px;word-break: break-all;" class="border-b-gray-300"></td>
                  <td style="min-width:100px;width:100px;word-break: break-all;vertical-align: top;" class="border-b-gray-300"><b>{{ round($approvedPercentage, 2) ?? 0 }}%</b></td>
                  <td style="min-width:100px;width:100px;word-break: break-all;vertical-align: top;" class="border-b-gray-300"><b>{{ round($projectedPercentage, 2) ?? 0 }}%</b></td>
                  <td style="min-width:100px;width:100px;word-break: break-all;" class="border-b-gray-300">
                  </td>
               </tr>
               <tr >
                  <td colspan="8" class="padding-0 border-b-none border-t-none">
                     <table width="100%" class="inner-level-table indirect-rate-table">
                        <!-- thead-->
                           <tr>
                              <td colspan="2" style="min-width:440px;width:440px;">
                                 <b>Project Name</b>
                              </td>
                              <td style="min-width:100px;width:100px;vertical-align: top;word-break: break-word;" class="border-l-gray-300"><b>Approved Annual Rate</b></td>
                              <td style="min-width:100px;width:100px;vertical-align: top;word-break: break-word;"><b>Projected Rate</b></td>
                              <td style="min-width:100px;width:100px;vertical-align: top;word-break: break-word;"><b>Balance</b></td>
                           </tr>
                        <!-- thead ends-->
                        <!-- tbody-->
                           @foreach ($projects as $project)
                           <tr>
                              <td style="min-width:100px;width:100px;vertical-align: top;word-break: break-all;">{{ $project->project_name }}</td>
                              <td class="position-relative" style="min-width:100px;width:100px;vertical-align: top;word-break: break-all;">
                                 <a href="{{ route('project.show', ['project' => $project->id]) }}" class="position-absolute top-0 bottom-0 left-0 right-0"></a>
                                 <div class="link-to-project d-flex align-items-center gap-2">
                                    Go to project
                                    <span class="d-flex align-items-center"><img src="{{ asset('images/icons/take-to-arrow.svg')}}" alt="img"></span>
                                 </div>
                              </td>
                              <td style="min-width:100px;width:100px;vertical-align: top;word-break: break-all; border-left:0px">{{ $project->indirect_rate }}%</td>
                              <td style="min-width:100px;width:100px;vertical-align: top;word-break: break-all; border-left:0px">/td>
                              <td style="min-width:100px;width:100px;vertical-align: top;word-break: break-all; border-left:0px">
                              </td>
                           </tr>
                           @endforeach
                        <!-- tbody ends-->
                     </table>
                  </td>
               </tr>
         </table>
            <table width="100%" class="reports-table income-by-donor-table total-annual-budget-table mb-12">
               <!-- thead-->              
               <tr class="secondary-header">
                  <td colspan="8" class="border-t-gray-300"><b>I. Projected income by donor in FY {{ $year }}</b></td>
               </tr>
               <!-- thead ends-->
               <!-- tbody-->
                  @php
                  $totalDonorBudget = 0;
                  @endphp
                  @foreach ($donors as $donor)
                  @php
                  $totalDonorBudget += $donor->total_budget;
                  @endphp
                  <tr class="details-row">
                     <td class="count">{{ $loop->index + 1 }}</td>
                     <td colspan="2" class="border-l-none">{{ $donor->name }}</td>
                     <td style="min-width:100px;width:100px;" class="border-l-none"></td>
                     <td style="min-width:100px;width:100px;" class="border-l-none"></td>
                     <td style="min-width:100px;width:100px;word-break:break-all;">€{{ dutchCurrency($donor->total_budget) }}</td>
                     <td style="min-width:100px;width:100px;" class="projected-budget-cell"></td>
                     <td style="min-width:100px;width:100px;" class="balance-cell border-l-none"></td>
                  </tr>
                  @endforeach
                  @if(count($donors)==0)
                  <tr>
                     <td class="count"></td>
                     <td colspan="2" class="border-l-none">
                        <span class="text-gray-50 text-xs font-regular">You don’t have any data yet</span></td>
                     <td style="min-width:100px;width:100px;" class="border-l-none"></td>
                     <td style="min-width:100px;width:100px;" class="border-l-none"></td>
                     <td style="min-width:100px;width:100px;" class="border-l-none"></td>
                     <td style="min-width:100px;width:100px;" class="projected-budget-cell projected-budget-cell-bg-color dborder-l-none"></td>
                     <td style="min-width:100px;width:100px;" class="balance-cell border-l-none"></td>
                  </tr>
                  @endif
                  <!-- tbody ends-->
                  <!-- tfoot-->
                  <tr class="estimated-row bg-lite-gray">
                     <td colspan="3" class="border-b-gray-300"><b>TOTAL SECURED FUNDING FY {{ $year }}</b></td>
                     <td style="min-width:100px;width:100px;" class="border-b-gray-300"></td>
                     <td style="min-width:100px;width:100px;" class="border-b-gray-300"></td>
                     <td class="border-b-gray-300" style="min-width:100px;width:100px;word-break:break-all;vertical-align: top;"><b>€{{ dutchCurrency($totalDonorBudget) }}</b></td>
                     <td style="min-width:100px;width:100px;" class="projected-budget-total-cell border-b-gray-300"></td>
                     <td style="min-width:100px;width:100px;" class="balance-total-cell border-b-gray-300"></td>
                  </tr>
                  <tr class="estimated-row bg-lite-gray">
                     <td colspan="3" class="border-b-gray-300"><b>DIFFERENCE TOTAL SECURED FUNDING - (B+D) </b>
                     </td>
                     <td style="min-width:100px;width:100px;" class="border-b-gray-300"></td>
                     <td style="min-width:100px;width:100px;" class="border-b-gray-300"></td>
                     <td class="border-b-gray-300" style="min-width:100px;width:100px;word-break:break-all;vertical-align: top;"><b>€{{ dutchCurrency($totalDonorBudget - ($totalOtherApprovedBudget + $totalApprovedBudget)) }}</b></td>
                     <td style="min-width:100px;width:100px;" class="projected-budget-total-cell border-b-gray-300"></td>
                     <td style="min-width:100px;width:100px;" class="balance-total-cell border-b-gray-300"></td>
                  </tr>
                  <!-- tfoot ends-->
         </table>
      </div>
</div>
<div class="tab-pane fade {{ $activeClass }}" id="nav-year-{{ $tabYear }}" role="tabpanel"
   aria-labelledby="FutureForCurrentYear">
   <div class="details-table">
      <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap">
         <h2 class="text-gray-800 text-xl font-semibold mb-0">
            Futures For Current Year
         </h2>
         <div class="d-flex  align-items-center flex-wrap gap-3">
            <a type="button" data-bs-toggle="modal"
               data-bs-target="#sub-project-modal"
               class=" btn btn-primary theme-btn">
               <svg class="me-2" width="20" height="20" viewBox="0 0 20 20"
                  fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M3.125 10H16.875" stroke="white" stroke-width="1.5"
                     stroke-linecap="round" stroke-linejoin="round" />
                  <path d="M10 3.125V16.875" stroke="white" stroke-width="1.5"
                     stroke-linecap="round" stroke-linejoin="round" />
               </svg>
               Add sub project
            </a>
         </div>
      </div>
      @if ($projectdetail->project->isEmpty())
      @foreach ($projectTabData as $key => $segment)
      <div class="table-responsive mb-2">
         <table width="100%" class="subproject-table future-budget collapsed"
            id="collapse_in_future_{{ $key }}_{{ $key }}_empty">
            @if ($segment->other == 1)
            @php
            $unitCost = calCulateprojectBudget($segment->id, $projectdetail->id, $currentYear, 'unit_costs');
            $remainingBalance = calCulateprojectBudget($segment->id, $projectdetail->id, $currentYear, 'remaining_balance');
            $futureUnits = $unitCost != 0 ? sprintf("%.2f", $remainingBalance / $unitCost) : 0;
            $futureApproved = (float) $unitCost * (float) $futureUnits;
            $futureRemaining = $futureApproved - (float) calCulateprojectBudget($segment->id, $projectdetail->id, $currentYear, 'revised_annual');
            @endphp

            <thead>
               <tr class="primary-header table-detail-acordian cursor-pointer" data-id="collapse_in_future_{{ $key }}_{{ $key }}_empty">
                  <th colspan="4">{{ $segment->look_up_value }}</th>
                  <th style="min-width: 116px;width: 116px;">
                     <span class="headings">
                        Costs
                     </span>
                  </th>
                  <th style="min-width: 200px;width: 200px;">
                     <div class="details">
                        <span class="title">Current Year Budget</span>
                        <p class="values {{ ($futureApproved ?? 0) < 0 ? 'text-error-500' : '' }}">
                           €{{ dutchCurrency($futureApproved) }}
                        </p>
                     </div>
                     <div class="total">
                        <p class="total-values {{ ($futureApproved ?? 0) < 0 ? 'text-error-500' : '' }}">
                           €{{ dutchCurrency($futureApproved) }}
                        </p>
                     </div>
                  </th>
                  <th style="min-width: 200px;width: 200px;">
                     <div class="details">
                        <span class="title">Revised Annual</span>
                        <p class="values {{ (calCulateprojectBudget($segment->id, $projectdetail->id, $currentYear, 'revised_annual') ?? 0) < 0 ? 'text-error-500' : '' }}">
                           €{{ dutchCurrency(calCulateprojectBudget($segment->id, $projectdetail->id, $currentYear, 'revised_annual')) }}
                        </p>
                     </div>
                     <div class="total">
                        <p class="total-values {{ (calCulateprojectBudget($segment->id, $projectdetail->id, $currentYear, 'revised_annual') ?? 0) < 0 ? 'text-error-500' : '' }}">
                           €{{ dutchCurrency(calCulateprojectBudget($segment->id, $projectdetail->id, $currentYear, 'revised_annual')) }}
                        </p>
                     </div>
                  </th>
                  <th style="min-width: 200px;width: 200px;">
                     <div class="details">
                        <span class="title">Leftover Budget</span>
                        <p class="values {{ ($futureRemaining ?? 0) < 0 ? 'text-error-500' : '' }}">
                           €{{ dutchCurrency($futureRemaining) }}
                        </p>
                     </div>
                     <div class="total">
                        <p class="total-values {{ ($futureRemaining ?? 0) < 0 ? 'text-error-500' : '' }}">
                           €{{ dutchCurrency($futureRemaining) }}
                        </p>
                     </div>
                  <th class="text-end action-toolbar">
                     <a type="button"
                        class="d-block table-detail-acordian">
                        <svg width="16" height="16"
                           viewBox="0 0 16 16" fill="none"
                           xmlns="http://www.w3.org/2000/svg">
                           <path d="M13 6L8 11L3 6" stroke="#667085"
                              stroke-width="1.5"
                              stroke-linecap="round"
                              stroke-linejoin="round" />
                        </svg>
                     </a>
                  </th>
                  </th>
               </tr>
               <tr class="secondary-header collapsable-header">
                  <th style="min-width: 58px;width:58px;text-align:center;">#</th>
                  <th style="min-width: 195px;width:195px;">A. Total Budget Headline
                  </th>
                  <th style="min-width: 145px;width: 145px;">Notes
                  </th>
                  <th style="min-width: 102px;width: 102px;">Units
                  </th>
                  <th style="min-width: 116px;width: 116px;">Unit Costs</th>
                  <th style="min-width: 200px;width: 200px;">
                     {{ $yearStartDate }} - {{ $yearEndDate }}
                     [€]
                  </th>
                  <th style="min-width: 202px;width: 202px;">
                     @if ($isFuture)
                     The budget hasn't begun yet.
                     @else
                     {{ $fromMonth }} {{ $tabYear }} -
                     {{ date('M') }}
                     {{ $tabYear }} [€]
                     @endif
                  </th>
                  <th colspan="1" style="min-width: 200px;width: 200px;">
                     {{ dateFormat(date('Y-m-d'), 'next_month') }}
                     - {{$toMonth }} {{ $tabYear }}
                     [€]
                  </th>
                  <th class="text-center action-toolbar">
                     <div></div>
                  </th>
               </tr>
            </thead>
            <tbody class="collapsable-body">
               @forelse($segment->projecthierarchdata as $key3 =>$hierarchdata)
               @php
               $futureUnits = ($hierarchdata->unit_costs != 0) ? ($hierarchdata->remaining_balance / $hierarchdata->unit_costs) : 0;
               $futureUnits = sprintf("%.2f", $futureUnits);
               $futureApproved = (float)$hierarchdata->unit_costs * (float)$futureUnits;
               $futureRemaining = (float)$futureApproved - (float)$hierarchdata->revised_annual;
               @endphp

               <tr class="details-row">
                  <td style="text-align:center;">{{ $key3 + 1 }}</td>
                  <td>
                     <span> {{ isset($hierarchdata->user->name) ? $hierarchdata->user->name : $hierarchdata->employee_id}} </span>
                  </td>
                  <td>
                     @php
                     $notes = [
                     'per_day' => 'Per day',
                     'per_event' => 'Per event',
                     'per_month' => 'Per month',
                     'per_year' => 'Per year',
                     'per_partner' => 'Per partner',
                     'per_item' => 'Per item',
                     'per_trip' => 'Per trip',
                     'per_page' => 'Per page',
                     'per_night' => 'Per night',
                     ];
                     $note = $hierarchdata['note'];

                     @endphp
                     <span> {{ isset($notes[$note]) ? $notes[$note] : 'Per day'; }} </span>
                  </td>
                  <td class="{{ ($futureUnits ?? 0) < 0 ? 'text-error-500' : '' }}">
                     <span>{{ intval($futureUnits) }}</span>
                  </td>
                  @if ($segment->id == 4 || $segment->id == 5 || $segment->id == 6 || $segment->id == 3)
                  <td>
                     <span class="currency-sign">€{{ netherlandformatCurrency($hierarchdata->unit_costs, 'blur') }}</span>
                  </td>
                  @else
                  <td>
                     €<span
                        class="rows_unitcost">{{ netherlandformatCurrency($hierarchdata->unit_costs, 'blur') }}</span>
                  </td>
                  @endif
                  <td class="{{ ($futureApproved ?? 0) < 0 ? 'text-error-500' : '' }}">€<span>{{ netherlandformatCurrency($futureApproved, 'blur') }}</span>
                  </td>
                  <td class="{{ ($hierarchdata->revised_annual ?? 0) < 0 ? 'text-error-500' : '' }}">
                     <span class="currency-sign">€{{ netherlandformatCurrency($hierarchdata->revised_annual, 'blur') }}</span>
                  </td>
                  <td class="{{ ($futureRemaining ?? 0) < 0 ? 'text-error-500' : '' }}">€<span>{{ netherlandformatCurrency($futureRemaining, 'blur') }}</span>
                  </td>
                  <td align="center" class="action-toolbar">
                     <div></div>
                  </td>
               </tr>
               @empty
               <td colspan="9" align="left" class="no-data collapse_in_future" data-id="collapse_in_future">
                  <span class="text-xs font-semibold">You
                     don’t have
                     any
                     data
                     yet.</span>
               </td>
               @endforelse
            </tbody>

            @elseif($segment->other == 0)
            @php
            $unitCost = calCulateprojectBudget(null, $projectdetail->id, $currentYear, 'unit_costs');
            $remainingBalance = calCulateprojectBudget(null, $projectdetail->id, $currentYear, 'remaining_balance');
            $futureUnits = $unitCost != 0 ? sprintf("%.2f", $remainingBalance / $unitCost) : 0;
            $futureApproved = (float) $unitCost * (float) $futureUnits;
            $futureRemaining = $futureApproved - (float) calCulateprojectBudget($segment->id, $projectdetail->id, $currentYear, 'revised_annual');
            @endphp
            <thead>
               <tr class="primary-header total-direct-cost-tr">
                  <th colspan="4">{{ $segment->look_up_value }}</th>
                  <th style="min-width: 116px;width: 116px;"></th>
                  <th style="min-width: 200px;width: 200px;" class="{{ ($futureApproved ?? 0) < 0 ? 'text-error-500' : '' }}">
                     <div>
                        €<span
                           class="total_approval_budget_empty">{{ dutchCurrency($futureApproved) }}</span>
                     </div>
                  </th>
                  <th style="min-width: 200px;width: 200px;" class="{{ (calCulateprojectBudget(null, $projectdetail->id, $currentYear, 'revised_annual') ?? 0) < 0 ? 'text-error-500' : '' }}">
                     <div>
                        €<span
                           class="total_actual_expenses_empty">{{ dutchCurrency(calCulateprojectBudget(null, $projectdetail->id, $currentYear, 'revised_annual')) }}</span>
                     </div>
                  </th>
                  <th style="min-width: 200px;width: 200px;" class="{{ ($futureRemaining ?? 0) < 0 ? 'text-error-500' : '' }}">
                     <div>
                        €<span
                           class="total_remaining_balance_empty">{{ dutchCurrency($futureRemaining) }}</span>
                     </div>
                  </th>
                  <th class="action-toolbar">
                     <div></div>
                  </th>
               </tr>
            </thead>
            @endif
         </table>
      </div>
      @endforeach
      @endif
      @if (!$projectdetail->project->isEmpty())
      <ul class="nav nav-tabs tabbing-outlined" id="myTab" role="tablist">
         <li class="nav-item sub-project-result" role="presentation">
            <button class="nav-link change-sub-project-tab" id="future{{ $tabYear }}"
               data-bs-toggle="tab" data-bs-target="#homeFuture{{ $tabYear }}" type="button"
               role="tab" aria-controls="homeFuture{{ $tabYear }}" aria-selected="true">All
               Projects</button>
         </li>
         @foreach ($projectdetail->project as $key => $subproject)
         <li class="nav-item serach-result-nav sub-project-result" role="presentation">
            <button class="nav-link {{ $loop->last ? 'active' : '' }} change-sub-project-tab" id="{{ $subproject->id }}"
               data-bs-toggle="tab" data-bs-target="#future_{{ $key }}_profile"
               type="button" role="tab" aria-controls="profile"
               aria-selected="false">{{ $subproject->sub_project_name }}</button>
         </li>
         @endforeach
      </ul>
      <div class="tab-content" id="myTabContent">
         <div class="tab-pane fade" id="homeFuture{{ $tabYear }}" role="tabpanel"
            aria-labelledby="all-projects">
            <div class="accordion subproject-acordian" id="accordionExample">
               <div class="accordion-item">
                  <h2 class="accordion-header sub-project-header" id="headingOne">
                     <div class="d-flex align-items-center mb-2 gap-3">
                        <button class="accordion-button w-auto" type="button"

                           data-bs-target="#collapse_future_{{ $tabYear }}"
                           aria-expanded="true"
                           aria-controls="collapse_future_{{ $tabYear }}">
                           All Projects
                        </button>
                     </div>
                  </h2>
                  <div id="collapse_future_{{ $tabYear }}"
                     class="accordion-collapse collapse show"
                     aria-labelledby="headingOne">
                     <div class="accordion-body">
                        @forelse($all_projects as $keys=> $segment)
                        <div class="table-responsive mb-2">
                           <table width="100%" class="subproject-table future-budget all-projects-table collapsed"
                              data-sub-project="{{ $subproject->id }}"
                              id="collapse_in_future{{ $key }}_{{ $keys }}">
                              @if ($segment->other == 1)
                              <thead>
                                 @php
                                 $unitCost = calCulateprojectBudget($segment->id, $projectdetail->id, $currentYear, 'unit_costs');
                                 $remainingBalance = calCulateprojectBudget($segment->id, $projectdetail->id, $currentYear, 'remaining_balance');
                                 $futureUnits = $unitCost != 0 ? sprintf("%.2f", $remainingBalance / $unitCost) : 0;
                                 $futureApproved = (float) $unitCost * (float) $futureUnits;
                                 $futureRemaining = $futureApproved - (float) calCulateprojectBudget($segment->id, $projectdetail->id, $currentYear, 'revised_annual');
                                 @endphp
                                 <tr class="primary-header table-detail-acordian cursor-pointer" data-id="collapse_in_future{{ $key }}_{{ $keys }}">
                                    <th colspan="4">{{ $segment->look_up_value }}</th>
                                    <th style="min-width: 116px;width: 116px;">
                                       <span class="headings">
                                          Costs
                                       </span>
                                    </th>
                                    <th style="min-width: 200px;width: 200px;">
                                       <div class="details">
                                          <span class="title">Current Year Budget</span>
                                          <p class="values {{ ($futureApproved ?? 0) < 0 ? 'text-error-500' : '' }}">
                                             €{{ dutchCurrency($futureApproved) }}
                                          </p>
                                       </div>
                                       <div class="total">
                                          <p class="total-values {{ ($futureApproved ?? 0) < 0 ? 'text-error-500' : '' }}">
                                             €{{ dutchCurrency($futureApproved) }}
                                          </p>
                                       </div>
                                    </th>
                                    <th style="min-width: 200px;width: 200px;">
                                       <div class="details">
                                          <span class="title">Revised Annual </span>
                                          <p class="values {{ (calCulateprojectBudget($segment->id, $projectdetail->id, $currentYear, 'revised_annual') ?? 0) < 0 ? 'text-error-500' : '' }}">
                                             €{{ dutchCurrency(calCulateprojectBudget($segment->id, $projectdetail->id, $currentYear, 'revised_annual')) }}
                                          </p>
                                       </div>
                                       <div class="total">
                                          <p class="total-values {{ (calCulateprojectBudget($segment->id, $projectdetail->id, $currentYear, 'revised_annual') ?? 0) < 0 ? 'text-error-500' : '' }}">
                                             €{{ dutchCurrency(calCulateprojectBudget($segment->id, $projectdetail->id, $currentYear, 'revised_annual')) }}
                                          </p>
                                       </div>
                                    </th>
                                    <th style="min-width: 200px;width: 200px;">
                                       <div class="details">
                                          <span
                                             class="title">Remaining
                                             Balance</span>
                                          <p class="values {{ ($futureRemaining ?? 0) < 0 ? 'text-error-500' : '' }}">
                                             €{{ dutchCurrency($futureRemaining) }}
                                          </p>
                                       </div>
                                       <div class="total">
                                          <p class="total-values {{ ($futureRemaining ?? 0) < 0 ? 'text-error-500' : '' }}">
                                             €{{ dutchCurrency($futureRemaining) }}
                                          </p>
                                       </div>
                                    </th>
                                    <th class="text-end action-toolbar">
                                       <a type="button"
                                          class="d-block table-detail-acordian">
                                          <svg width="16" height="16"
                                             viewBox="0 0 16 16" fill="none"
                                             xmlns="http://www.w3.org/2000/svg">
                                             <path d="M13 6L8 11L3 6" stroke="#667085"
                                                stroke-width="1.5"
                                                stroke-linecap="round"
                                                stroke-linejoin="round" />
                                          </svg>
                                       </a>
                                    </th>
                                 </tr>

                                 <tr
                                    class="secondary-header collapsable-header">
                                    <th style="min-width: 58px;width:58px;text-align:center;">#</th>
                                    <th style="min-width: 195px;width:195px;">A. Total Budget Headline
                                    </th>
                                    <th style="min-width: 145px;width: 145px;">Notes
                                    </th>
                                    <th style="min-width: 102px;width: 102px;">Units
                                    </th>
                                    <th style="min-width: 116px;width: 116px;">Unit Costs</th>
                                    <th style="min-width: 200px;width: 200px;">
                                       {{ $yearStartDate }} - {{ $yearEndDate }}
                                       [€]
                                    </th>
                                    <th style="min-width: 202px;width: 202px;">
                                       @if ($isFuture)
                                       The budget hasn't begun yet.
                                       @else
                                       {{ $fromMonth }} {{ $tabYear }} -
                                       {{ date('M') }}
                                       {{ $tabYear }} [€]
                                       @endif
                                    </th>
                                    <th colspan="1" style="min-width: 200px;width: 200px;">
                                       {{ dateFormat(date('Y-m-d'), 'next_month') }}
                                       - {{$toMonth }} {{ $tabYear }}
                                       [€]
                                    </th>
                                    <th class="text-center action-toolbar">
                                       <div></div>
                                    </th>
                                 </tr>
                              </thead>
                              <tbody class="collapsable-body">
                                 @php
                                 $counter = 1;
                                 @endphp
                                 @forelse($segment->projecthierarchdata as $key3 => $hierarchdata)
                                 @php

                                 $futureUnits = $hierarchdata['remaining_balance'] / $hierarchdata['unit_costs_total'];
                                 $futureUnits = sprintf("%.2f", $futureUnits);
                                 $futureApproved = (float)$hierarchdata['unit_costs_total'] * (float)$futureUnits;
                                 $futureRemaining = (float)$futureApproved - (float)$hierarchdata['revised_annual'];
                                 @endphp
                                 <tr class="details-row">
                                    <td style="text-align: center">{{ $counter }}</td>
                                    <td>
                                       <span> {{ $hierarchdata['employee_name'] }} </span>
                                    </td>
                                    <td>
                                       @php
                                       $notes = [
                                       'per_day' => 'Per day',
                                       'per_event' => 'Per event',
                                       'per_month' => 'Per month',
                                       'per_year' => 'Per year',
                                       'per_partner' => 'Per partner',
                                       'per_item' => 'Per item',
                                       'per_night' => 'Per night',
                                       'per_trip' => 'Per trip',
                                       'per_page' => 'Per page',
                                       'per_night' => 'Per night',
                                       ];
                                       $note = $hierarchdata['note'];

                                       @endphp
                                       <span> {{ isset($notes[$note]) ? $notes[$note] : 'Per day'; }}</span>
                                    </td>
                                    <td class="{{ ($futureUnits ?? 0) < 0 ? 'text-error-500' : '' }}">
                                       <span>{{ intval($futureUnits) }}</span>
                                    </td>
                                    <td>
                                       €<span>{{ netherlandformatCurrency($hierarchdata['unit_costs_total'], 'blur') }}</span>
                                    </td>
                                    <td class="{{ ($futureApproved ?? 0) < 0 ? 'text-error-500' : '' }}">€<span>{{ netherlandformatCurrency($futureApproved, 'blur') }}</span>
                                    </td>
                                    <td class="{{ ($hierarchdata['revised_annual'] ?? 0) < 0 ? 'text-error-500' : '' }}">
                                       <span class="currency-sign">€{{ netherlandformatCurrency($hierarchdata['revised_annual'], 'blur') }}</span>
                                    </td>
                                    <td class="{{ ($hierarchdata['revised_annual'] ?? 0) < 0 ? 'text-error-500' : '' }}">€<span>{{ netherlandformatCurrency($futureRemaining, 'blur') }}</span>
                                    </td>
                                    <td align="center" class="action-toolbar">
                                       <div></div>
                                    </td>
                                 </tr>
                                 @php
                                 $counter++;
                                 @endphp
                                 @empty
                                 <td colspan="9" align="left"
                                    class="no-data">
                                    <span
                                       class="text-xs font-semibold">You
                                       don’t have any data
                                       yet.</span>
                                 </td>
                                 @endforelse
                                 <tr
                                    class="tr empty-row">
                                    <td colspan="9"></td>
                                 </tr>
                              </tbody>

                              @elseif($segment->other == 0)
                              <thead>
                                 <tr
                                    class="primary-header total-direct-cost-tr">
                                    <th colspan="4">{{ $segment->look_up_value }}</th>
                                    <th style="min-width: 116px;width: 116px;"></th>
                                    <th style="min-width: 200px;width: 200px;" class="{{ (calCulateprojectBudget(null, $projectdetail->id, $currentYear, 'approval_budget') ?? 0) < 0 ? 'text-error-500' : '' }}">
                                       <div>
                                          €<span>{{ dutchCurrency(calCulateprojectBudget(null, $projectdetail->id, $currentYear, 'approval_budget')) }}</span>
                                       </div>
                                    </th>
                                    <th style="min-width: 200px;width: 200px;" class="{{ (calCulateprojectBudget(null, $projectdetail->id, $currentYear, 'actual_expenses') ?? 0) < 0 ? 'text-error-500' : '' }}">
                                       <div>
                                          €<span>{{ dutchCurrency(calCulateprojectBudget(null, $projectdetail->id, $currentYear, 'actual_expenses')) }}</span>
                                       </div>
                                    </th>
                                    <th style="min-width: 200px;width: 200px;" class="{{ (calCulateprojectBudget(null, $projectdetail->id, $currentYear, 'remaining_balance') ?? 0) < 0 ? 'text-error-500' : '' }}">
                                       <div>
                                          €<span>{{ dutchCurrency(calCulateprojectBudget(null, $projectdetail->id, $currentYear, 'remaining_balance')) }}</span>
                                       </div>
                                    </th>
                                    <th class="action-toolbar">
                                       <div></div>
                                    </th>
                                 </tr>
                              </thead>
                              @endif
                           </table>
                        </div>
                        @empty
                        <div class="table-responsive mb-2">No Data Found
                        </div>
                        @endforelse
                     </div>
                  </div>
               </div>
               <div class="accordion display-none no-subproject-found no-records"
                  id="accordionExample">
                  <span class="text-gray-50 text-md font-regular">You don't
                     have any subprojects listed!</span>
               </div>
            </div>
         </div>
         @foreach ($project_segment as $key => $subproject)
         <div class="tab-pane fade {{ $loop->last ? 'active show' : '' }}" id="future_{{ $key }}_profile" role="tabpanel"
            aria-labelledby="General_OS">
            <div class="sub-project-header d-flex align-item-center gap-3 mb-2">
               <div class="text-primary-500 text-lg font-bold">
                  {{ $subproject->sub_project_name }}
               </div>
            </div>
            @foreach ($subproject->project_hierarchy as $key2 => $segment)
            <div class="table-responsive mb-2">
               <table width="100%" class="subproject-table collapsed"
                  data-sub-project="{{ $subproject->id }}"
                  id="collapse_in_future_{{ $key }}_{{ $key2 }}_{{ $segment->id }}">
                  @if ($segment->other == 1)
                  <thead>
                     <tr class="primary-header table-detail-acordian cursor-pointer" data-id="collapse_in_future_{{ $key }}_{{ $key2 }}_{{ $segment->id }}">
                        <th colspan="4">{{ $segment->look_up_value }}</th>
                        <th style="min-width: 116px;width: 116px;">
                           <span class="headings">
                              Costs
                           </span>
                        </th>
                        <th style="min-width: 200px;width: 200px;">
                           <div class="details">
                              <span class="title">Current Year Budget</span>
                              <p class="values {{ (calCulateprojectBudget($segment->id, $projectdetail->id, $currentYear, 'approval_budget', $subproject->id) ?? 0) < 0 ? 'text-error-500' : '' }}">
                                 €{{ dutchCurrency(calCulateprojectBudget($segment->id, $projectdetail->id, $currentYear, 'approval_budget', $subproject->id)) }}
                              </p>
                           </div>
                           <div class="total">
                              <p class="total-values {{ (calCulateprojectBudget($segment->id, $projectdetail->id, $currentYear, 'approval_budget', $subproject->id) ?? 0) < 0 ? 'text-error-500' : '' }}">
                                 €{{ dutchCurrency(calCulateprojectBudget($segment->id, $projectdetail->id, $currentYear, 'approval_budget', $subproject->id)) }}
                              </p>
                           </div>
                        </th>
                        <th style="min-width: 200px;width: 200px;">
                           <div class="details">
                              <span class="title">Revised Annual</span>
                              <p class="values {{ (calCulateprojectBudget($segment->id, $projectdetail->id, $currentYear, 'actual_expenses', $subproject->id) ?? 0) < 0 ? 'text-error-500' : '' }}">
                                 €{{ dutchCurrency(calCulateprojectBudget($segment->id, $projectdetail->id, $currentYear, 'actual_expenses', $subproject->id)) }}
                              </p>
                           </div>
                           <div class="total">
                              <p class="total-values {{ (calCulateprojectBudget($segment->id, $projectdetail->id, $currentYear, 'actual_expenses', $subproject->id) ?? 0) < 0 ? 'text-error-500' : '' }}">
                                 €{{ dutchCurrency(calCulateprojectBudget($segment->id, $projectdetail->id, $currentYear, 'actual_expenses', $subproject->id)) }}
                              </p>
                           </div>
                        </th>
                        <th style="min-width: 200px;width: 200px;">
                           <div class="details">
                              <span class="title">Remaining
                                 Balance</span>
                              <p class="values {{ (calCulateprojectBudget($segment->id, $projectdetail->id, $currentYear, 'remaining_balance', $subproject->id) ?? 0) < 0 ? 'text-error-500' : '' }}">
                                 €{{ dutchCurrency(calCulateprojectBudget($segment->id, $projectdetail->id, $currentYear, 'remaining_balance', $subproject->id)) }}
                              </p>
                           </div>
                           <div class="total">
                              <p class="total-values {{ (calCulateprojectBudget($segment->id, $projectdetail->id, $currentYear, 'remaining_balance', $subproject->id) ?? 0) < 0 ? 'text-error-500' : '' }}">
                                 €{{ dutchCurrency(calCulateprojectBudget($segment->id, $projectdetail->id, $currentYear, 'remaining_balance', $subproject->id)) }}
                              </p>
                           </div>
                        </th>
                        <th class="text-end action-toolbar">
                           <a type="button"
                              class="d-block table-detail-acordian">
                              <svg width="16" height="16"
                                 viewBox="0 0 16 16" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                 <path d="M13 6L8 11L3 6" stroke="#667085"
                                    stroke-width="1.5"
                                    stroke-linecap="round"
                                    stroke-linejoin="round" />
                              </svg>
                           </a>
                        </th>
                     </tr>

                     <tr class="secondary-header collapsable-header">
                        <th style="min-width: 58px;width:58px;text-align:center;">#</th>
                        <th style="min-width: 195px;width:195px;">A. Total Budget Headline
                        </th>
                        <th style="min-width: 145px;width: 145px;">Notes
                        </th>
                        <th style="min-width: 102px;width: 102px;">Units
                        </th>
                        <th style="min-width: 116px;width: 116px;">Unit Costs</th>
                        <th style="min-width: 200px;width: 200px;">
                           {{ $yearStartDate }} - {{ $yearEndDate }}
                           [€]
                        </th>
                        <th style="min-width: 202px;width: 202px;">
                           @if ($isFuture)
                           The budget hasn't begun yet.
                           @else
                           {{ $fromMonth }} {{ $tabYear }} -
                           {{ date('M') }}
                           {{ $tabYear }} [€]
                           @endif
                        </th>
                        <th colspan="1" style="min-width: 200px;width: 200px;">
                           {{ dateFormat(date('Y-m-d'), 'next_month') }}
                           - {{$toMonth }} {{ $tabYear }}
                           [€]
                        </th>
                        <th class="text-center action-toolbar">
                           <div></div>
                        </th>
                     </tr>
                  </thead>
                  <tbody class="collapsable-body">
                     @forelse($segment->projecthierarchdata as $key3 =>$hierarchdata)
                     @php
                     $futureUnits = $hierarchdata->remaining_balance / $hierarchdata->unit_costs;
                     $futureUnits = sprintf("%.2f", $futureUnits);
                     $futureApproved = (float)$hierarchdata->unit_costs * (float)$futureUnits;
                     $futureRemaining = (float)$futureApproved - (float)$hierarchdata->revised_annual;
                     @endphp
                     <tr class="details-row">
                        <td style="text-align:center;">{{ $key3 + 1 }}</td>
                        <td>
                           <span>{{ isset($hierarchdata->user->name)?$hierarchdata->user->name: $hierarchdata->employee_id}}</span>
                        </td>
                        <td>
                           @php
                           $notes = [
                           'per_day' => 'Per day',
                           'per_event' => 'Per event',
                           'per_month' => 'Per month',
                           'per_year' => 'Per year',
                           'per_partner' => 'Per partner',
                           'per_item' => 'Per item',
                           'per_night' => 'Per night',
                           'per_trip' => 'Per trip',
                           'per_page' => 'Per page',
                           'per_night' => 'Per night',
                           ];
                           $note = $hierarchdata->note;

                           @endphp
                           <span>{{ isset($notes[$note]) ? $notes[$note] : 'Per day'; }}</span>
                        </td>
                        <td class="{{ ($futureUnits ?? 0) < 0 ? 'text-error-500' : '' }}"> <span> {{ intval($futureUnits) }}</span>
                        </td>
                        <td>
                           €<span>{{ netherlandformatCurrency($hierarchdata->unit_costs, 'blur') }}</span>
                        </td>
                        <td class="{{ ($futureApproved ?? 0) < 0 ? 'text-error-500' : '' }}">€<span>{{ netherlandformatCurrency($futureApproved, 'blur') }}</span>
                        </td>
                        <td class="{{ ($hierarchdata->revised_annual ?? 0) < 0 ? 'text-error-500' : '' }}">
                           <span class="currency-sign">€{{ netherlandformatCurrency($hierarchdata->revised_annual, 'blur') }}</span>
                        </td>
                        <td class="{{ ($futureRemaining ?? 0) < 0 ? 'text-error-500' : '' }}">€<span>{{ netherlandformatCurrency($futureRemaining, 'blur') }}</span>
                        </td>
                        <td align="center" class="action-toolbar">
                           <div></div>
                        </td>
                     </tr>
                     @empty
                     <td colspan="9" align="left"
                        class="no-data collapse_in_future"
                        data-id="collapse_in_future">
                        <span class="text-xs font-semibold">You
                           don’t have
                           any
                           data
                           yet.</span>
                     </td>
                     @endforelse
                  </tbody>
                  @endif
               </table>
            </div>
            @endforeach
         </div>
         @endforeach
      </div>
      @endif
   </div>
</div>
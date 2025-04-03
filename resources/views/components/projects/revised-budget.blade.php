<div class="tab-pane fade {{ $activeClass }}" id="nav-revision-for-current-year" role="tabpanel"
   aria-labelledby="RevisionForCurrentYear" wire:ignore.self>
   <div class="details-table">
      <div
         class="d-flex align-items-center justify-content-between mb-4 flex-wrap">
         <h2 class="text-gray-800 text-xl font-semibold mb-0">Revisions For Current Year</h2>
      </div>
      @if ($subprojects->project->isEmpty())
      @foreach ($projectTabData as $key => $segment)
      <div class="table-responsive mb-2">
         <table width="100%" class="subproject-table collapsed"
            id="collapse_in_revsion_{{ $key }}_{{ $key }}_empty" wire:ignore.self>

            @if ($segment->other == 1)
            <thead>
               <tr class="primary-header table-detail-acordian cursor-pointer" data-id="collapse_in_revsion_{{ $key }}_{{ $key }}_empty">
                  <th colspan="2" class="first-column-fixed-to-left">
                     <div>{{ $segment->look_up_value }}</div>
                  </th>
                  <th class="border-l-none" style="min-width: 145px;width: 145px;"></th>
                  <th style="min-width: 102px;width: 102px;"></th>
                  <th style="min-width: 116px;width: 116px;">
                     <span class="headings">
                        Costs
                     </span>
                  </th>
                  <th style="min-width: 200px;width: 200px;">
                     <div class="details">
                        <span class="title">Current Year Budget</span>
                        <p class="values">
                           €{{ dutchCurrency(calCulateprojectBudget($segment->id, $projectdetail->id, $tabYear, 'approval_budget')) }}
                        </p>
                     </div>
                     <div class="total">
                        <p class="total-values">
                           €{{ dutchCurrency(calCulateprojectBudget($segment->id, $projectdetail->id, $tabYear, 'approval_budget')) }}
                        </p>
                     </div>
                  </th>
                  <th style="min-width: 202px;width: 202px;">
                     <div class="details">
                        <span class="title">Current Year Expenses</span>
                        <p class="values">
                           €{{ dutchCurrency(calCulateprojectBudget($segment->id, $projectdetail->id, $tabYear, 'actual_expenses')) }}
                        </p>
                     </div>
                     <div class="total">
                        <p class="total-values">
                           €{{ dutchCurrency(calCulateprojectBudget($segment->id, $projectdetail->id, $tabYear, 'actual_expenses')) }}
                        </p>
                     </div>
                  </th>
                  <th style="min-width: 200px;width: 200px;">
                     <div class="details">
                        <span class="title">Remaining
                           Balance</span>
                        <p class="values {{ (calCulateprojectBudget($segment->id, $projectdetail->id, $tabYear, 'remaining_balance') ?? 0) < 0 ? 'text-error-500' : '' }}">
                           €{{ dutchCurrency(calCulateprojectBudget($segment->id, $projectdetail->id, $tabYear, 'remaining_balance')) }}
                        </p>
                     </div>
                     <div class="total">
                        <p class="total-values {{ (calCulateprojectBudget($segment->id, $projectdetail->id, $tabYear, 'remaining_balance') ?? 0) < 0 ? 'text-error-500' : '' }}">
                           €{{ dutchCurrency(calCulateprojectBudget($segment->id, $projectdetail->id, $tabYear, 'remaining_balance')) }}
                        </p>
                     </div>
                  </th>
                  <th colspan="3" style="text-align: center;min-width: 323px;width: 323px;">
                     <span class="headings">Revise Budget</span>
                  </th>
                  <th style="min-width: 127px;width: 127px;">
                     <span class="headings">Revise Annual</span>
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
               <tr class="primary-header sub-primary-header collapsable-header table-detail-acordian cursor-pointer" data-id="collapse_in_{{ $key }}">
                  <th colspan="2" class="first-column-fixed-to-left">
                     <div>A. Total Personnel</div>
                  </th>
                  <th class="border-l-none" style="min-width: 145px;width: 145px;"></th>
                  <th style="min-width: 102px;width: 102px;"></th>
                  <th style="min-width: 116px;width: 116px;"></th>
                  <th style="min-width: 200px;width: 200px;"></th>
                  <th style="min-width: 202px;width: 202px;"></th>
                  <th style="min-width: 200px;width: 200px;"></th>
                  <th style="min-width: 101px;width: 101px;">
                     <div class="heading">
                        <span class="title">Units</span>
                     </div>
                  </th>
                  <th style="min-width: 119px;width: 119px;">
                     <div class="heading">
                        <span class="title">€<span>{{ dutchCurrency(calCulateprojectBudget($segment->id, $projectdetail->id, $tabYear, 'revised_unit_amount')) }}</span></span>
                     </div>
                  </th>
                  <th style="min-width: 103px;width: 103px;">
                     <div class="heading">
                        <span class="title">€<span>{{ dutchCurrency(calCulateprojectBudget($segment->id, $projectdetail->id, $tabYear, 'revised_new_budget')) }}</span></span>
                     </div>
                  </th>
                  <th style="min-width: 127px;width: 127px;">
                     <div class="heading">
                        @if (calCulateprojectBudget($segment->id, $projectdetail->id, $tabYear, 'revised_annual') == 0 )
                        <span class="title">€<span>{{ dutchCurrency(calCulateprojectBudget($segment->id, $projectdetail->id, $tabYear, 'approval_budget')) }}</span></span>
                        @else
                        <span class="title">€<span>{{ dutchCurrency(calCulateprojectBudget($segment->id, $projectdetail->id, $tabYear, 'revised_annual')) }}</span></span>
                        @endif
                     </div>
                  </th>
                  <th class="text-end action-toolbar">
                     <div></div>
                  </th>
               </tr>
               <tr class="secondary-header collapsable-header">
                  <th class="first-column-fixed-to-left">
                     <div>#</div>
                  </th>
                  <th class="second-column-fixed-to-left" style="min-width: 195px;width:195px;">
                     <div>A. Total Budget Headline</div>
                  </th>
                  <th class="border-l-none" style="min-width: 145px;width: 145px;">Notes

                  </th>
                  <th style="min-width: 102px;width: 102px;">Units</th>
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
                  <th class="editable" style="min-width: 101px;width: 101px;">Days <span class="edit">
                        <img src="{{ asset('images/icons/table-cell-edit.svg') }}"
                           alt="img">
                     </span></th>
                  <th style="min-width: 119px;width: 119px;">+/- EUR Amount</th>
                  <th style="min-width: 103px;width: 103px;">New Budget</th>
                  <th style="min-width: 127px;width: 127px;">Fy {{ $tabYear }}</th>
                  <th class="text-center action-toolbar">
                     <div></div>
                  </th>
               </tr>
            </thead>
            <tbody class="collapsable-body">
               @forelse($segment->projecthierarchdata as $key3 =>
               $hierarchdata)
               <tr class="details-row">
                  <td class="first-column-fixed-to-left">
                     <div>{{ $key3 + 1 }}</div>
                  </td>
                  <td class="second-column-fixed-to-left">
                     <div> {{ isset($hierarchdata->user->name) ? $hierarchdata->user->name : $hierarchdata->employee_id}}</div>
                  </td>
                  <td class="border-l-none">
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
                     <span>{{ isset($notes[$note]) ? $notes[$note] : 'Per day'; }} </span>
                  </td>
                  <td>
                     <span>{{ $hierarchdata->units }}</span>
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
                  <td>€<span>{{ netherlandformatCurrency($hierarchdata->total_approval_budget, 'blur') }}</span>
                  </td>
                  <td>
                     <span class="currency-sign">€{{ netherlandformatCurrency($hierarchdata->actual_expenses_to_date, 'blur') }}</span>
                  </td>
                  <td class="{{ ($hierarchdata->remaining_balance ?? 0) < 0 ? 'text-error-500' : '' }}">€<span>{{ netherlandformatCurrency($hierarchdata->remaining_balance, 'blur') }}</span>
                  </td>
                  <td class="editable-td p-0 units-input-number">
                     @php
                     $revisedUnit = ($hierarchdata->revised_units!=0) ? netherlandformatCurrency($hierarchdata->revised_units) : '';
                     @endphp
                     @if ($segment->id == 4 || $segment->id == 5 || $segment->id == 6 || $segment->id == 3)
                     <input type="text"
                        class="table-input revisedUnits{{ $hierarchdata->id }}"
                        placeholder="0"
                        min="1" max="10000" value="{{ $revisedUnit }}"
                        onkeyup="calculateReviseBudget(0, {{ $hierarchdata->remaining_balance }}, {{ $hierarchdata->actual_expenses_to_date }}, {{ $hierarchdata->id }},event)" />
                     @else
                     <input type="text"
                        class="table-input revisedUnits{{ $hierarchdata->id }}"
                        placeholder="0"
                        min="1" max="10000" value="{{ $revisedUnit }}"
                        onkeyup="calculateReviseBudget({{ $hierarchdata->unit_costs }}, {{ $hierarchdata->remaining_balance }}, {{ $hierarchdata->actual_expenses_to_date }}, {{ $hierarchdata->id }},event)" />
                     @endIf
                     <span class="edit-pill">Edit</span>
                     <button type="submit" class="save-pill-revision" onclick="saveReviseBudget({{ $hierarchdata->id }}, {{ $hierarchdata->sub_project_id }})">save</button>
                  </td>
                  @if ($segment->id == 4 || $segment->id == 5 || $segment->id == 6 || $segment->id == 3)
                  <td class="editable-td p-0 units-input-number">
                     <span class="currency-sign">€</span><input
                        type="text"
                        class="table-input project-expenses-input revisedUnitCost{{ $hierarchdata->id }}"
                        onkeyup="calculateReviseBudget(0, {{ $hierarchdata->remaining_balance }}, {{ $hierarchdata->actual_expenses_to_date }}, {{ $hierarchdata->id }}, event)"
                        placeholder="0" data-type="currency"
                        value="{{ netherlandformatCurrency($hierarchdata->revised_unit_amount, 'blur') }}">
                     <span class="edit-pill">Edit</span>
                     <button type="submit" class="save-pill" onclick="saveReviseBudget({{ $hierarchdata->id }}, {{ $hierarchdata->sub_project_id }})">save</button>
                  </td>
                  @else
                  <td>€<span class="unitsAmount{{ $hierarchdata->id }}">{{ netherlandformatCurrency($hierarchdata->revised_unit_amount) }}</span> </td>
                  @endif
                  <input class="unitsAmountVal{{ $hierarchdata->id }}" type="hidden" value="{{ $hierarchdata->revised_unit_amount }}"> <input class="unitsVal{{ $hierarchdata->id }}" type="hidden" value="{{ $hierarchdata->revised_units }}">
                  <td>€<span class="newBudget{{ $hierarchdata->id }}">{{ netherlandformatCurrency($hierarchdata->revised_new_budget)}}</span> <input class="newBudgetVal{{ $hierarchdata->id }}" type="hidden" value="{{ $hierarchdata->revised_new_budget }}"></td>
                  <td>€<span class="revisedAnnual{{ $hierarchdata->id }}">{{ ($hierarchdata->revised_annual !=0) ? netherlandformatCurrency($hierarchdata->revised_annual) : netherlandformatCurrency($hierarchdata->total_approval_budget)}}</span> <input class="revisedAnnualVal{{ $hierarchdata->id }}" type="hidden" value="{{ ($hierarchdata->revised_annual !=0) ? $hierarchdata->revised_annual : $hierarchdata->total_approval_budget }}"></td>
                  <td align="center" class="action-toolbar">
                     <div></div>
                  </td>
               </tr>
               @empty
               <td colspan="13" align="left" class="no-data collapse_in_revision" data-id="collapse_in_revision">
                  <span class="text-xs font-semibold">You
                     don’t have
                     any
                     data
                     yet.</span>
               </td>
               @endforelse
            </tbody>

            @elseif($segment->other == 0)
            <thead>
               <tr class="primary-header total-direct-cost-tr">
                  <th colspan="2" class="first-column-fixed-to-left">
                     <div>
                        {{ $segment->look_up_value }}
                     </div>
                  </th>
                  <th style="min-width: 145px;width: 145px;"></th>
                  <th style="min-width: 102px;width: 102px;"></th>
                  <th style="min-width: 116px;width: 116px;"></th>
                  <th style="min-width: 200px;width: 200px;">
                     <div>
                        €<span
                           class="total_approval_budget_empty">{{ dutchCurrency(calCulateprojectBudget(null, $projectdetail->id, $tabYear, 'approval_budget')) }}</span>
                     </div>
                  </th>
                  <th style="min-width: 202px;width: 202px;">
                     <div>
                        €<span
                           class="total_actual_expenses_empty">{{ dutchCurrency(calCulateprojectBudget(null, $projectdetail->id, $tabYear, 'actual_expenses')) }}</span>
                     </div>
                  </th>
                  <th style="min-width: 200px;width: 200px;" class="{{ (calCulateprojectBudget(null, $projectdetail->id, $tabYear, 'remaining_balance') ?? 0) < 0 ? 'text-error-500' : '' }}">
                     <div>
                        €<span
                           class="total_remaining_balance_empty">{{ dutchCurrency(calCulateprojectBudget(null, $projectdetail->id, $tabYear, 'remaining_balance')) }}</span>
                     </div>
                  </th>
                  <th colspan="3" style="text-align: center;min-width: 323px;width: 323px;"></th>
                  <th style="min-width: 127px;width: 127px;">
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
      @if (!$subprojects->project->isEmpty())
      <ul class="nav nav-tabs tabbing-outlined" id="myTab" role="tablist">
         <li class="nav-item sub-project-result" role="presentation">
            <button class="nav-link change-sub-project-tab active" id="revison{{ $tabYear }}"
               data-bs-toggle="tab" data-bs-target="#homeRevision{{ $tabYear }}" type="button"
               role="tab" aria-controls="homeRevision{{ $tabYear }}" aria-selected="true" wire:ignore.self>All
               Projects</button>
         </li>
         @foreach ($projectTabData as $key => $subproject)
         <li class="nav-item serach-result-nav sub-project-result" role="presentation">
            <button class="nav-link change-sub-project-tab" id="{{ $subproject->id }}"
               data-bs-toggle="tab" data-bs-target="#revision_{{ $key }}_profile"
               type="button" role="tab" aria-controls="profile"
               aria-selected="false" wire:ignore.self>{{ $subproject->sub_project_name }}</button>
         </li>
         @endforeach
      </ul>
      <div class="tab-content" id="myTabContent">
         <div class="tab-pane fade show active" id="homeRevision{{ $tabYear }}" role="tabpanel" wire:ignore.self
            aria-labelledby="all-projects">
            <div class="accordion subproject-acordian" id="accordionExample">
               <div class="accordion-item">
                  <h2 class="accordion-header sub-project-header" id="headingOne">
                     <div class="d-flex align-items-center mb-2 gap-3">
                        <button class="accordion-button w-auto" type="button"
                           data-bs-target="#collapse_rev_{{ $tabYear }}"
                           aria-expanded="true"
                           aria-controls="collapse_rev_{{ $tabYear }}">
                           All Projects
                        </button>
                     </div>
                  </h2>
                  <div id="collapse_rev_{{ $tabYear }}"
                     class="accordion-collapse collapse show active"
                     aria-labelledby="headingOne">
                     <div class="accordion-body">
                        @forelse($all_projects as $keys=> $segment)
                        <div class="table-responsive mb-2">
                           <table width="100%" class="subproject-table all-projects-table collapsed" wire:ignore.self
                              data-sub-project="{{ $subproject->id }}"
                              id="collapse_in_{{ $key }}_{{ $keys }}">
                              @if ($segment->other == 1)
                              <thead>
                                 <tr class="primary-header table-detail-acordian cursor-pointer" data-id="collapse_in_{{ $key }}_{{ $keys }}">
                                    <th colspan="2" class="first-column-fixed-to-left">
                                       <div>{{ $segment->look_up_value }}</div>
                                    </th>
                                    <th class="border-l-none" style="min-width: 145px;width: 145px;"></th>
                                    <th style="min-width: 102px;width: 102px;"></th>
                                    <th style="min-width: 116px;width: 116px;">
                                       <span class="headings">
                                          Costs
                                       </span>
                                    </th>
                                    <th style="min-width: 200px;width: 200px;">
                                       <div class="details">
                                          <span class="title">Current Year Budget</span>
                                          <p class="values">
                                             €{{ dutchCurrency(calCulateprojectBudget($segment->id, $projectdetail->id, $tabYear, 'approval_budget')) }}
                                          </p>
                                       </div>
                                       <div class="total">
                                          <p class="total-values">
                                             €{{ dutchCurrency(calCulateprojectBudget($segment->id, $projectdetail->id, $tabYear, 'approval_budget')) }}
                                          </p>
                                       </div>
                                    </th>
                                    <th style="min-width: 202px;width: 202px;">
                                       <div class="details">
                                          <span class="title">Current Year Expenses</span>
                                          <p class="values">
                                             €{{ dutchCurrency(calCulateprojectBudget($segment->id, $projectdetail->id, $tabYear, 'actual_expenses')) }}
                                          </p>
                                       </div>
                                       <div class="total">
                                          <p class="total-values">
                                             €{{ dutchCurrency(calCulateprojectBudget($segment->id, $projectdetail->id, $tabYear, 'actual_expenses')) }}
                                          </p>
                                       </div>
                                    </th>
                                    <th style="min-width: 200px;width: 200px;">
                                       <div class="details">
                                          <span
                                             class="title">Remaining
                                             Balance</span>
                                          <p class="values {{ (calCulateprojectBudget($segment->id, $projectdetail->id, $tabYear, 'remaining_balance') ?? 0) < 0 ? 'text-error-500' : '' }}">
                                             €{{ dutchCurrency(calCulateprojectBudget($segment->id, $projectdetail->id, $tabYear, 'remaining_balance')) }}
                                          </p>
                                       </div>
                                       <div class="total">
                                          <p class="total-values {{ (calCulateprojectBudget($segment->id, $projectdetail->id, $tabYear, 'remaining_balance') ?? 0) < 0 ? 'text-error-500' : '' }}">
                                             €{{ dutchCurrency(calCulateprojectBudget($segment->id, $projectdetail->id, $tabYear, 'remaining_balance')) }}
                                          </p>
                                       </div>
                                    </th>
                                    <th colspan="3" style="text-align: center;min-width: 323px;width: 323px;">
                                       <span class="headings">Revise Budget</span>
                                    </th>
                                    <th style="min-width: 127px;width: 127px;">
                                       <span class="headings">Revise Annual</span>
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
                                 <tr class="primary-header sub-primary-header collapsable-header table-detail-acordian cursor-pointer" data-id="collapse_in_{{ $key }}_{{ $keys }}">
                                    <th colspan="2" class="first-column-fixed-to-left">
                                       <div>A. Total Personnel</div>
                                    </th>
                                    <th class="border-l-none"></th>
                                    <th class=""></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                              
                                    <th>
                                       <div class="heading">
                                          <span class="title">Units</span>
                                       </div>
                                    </th>
                                    
                                    <th>
                                       <div class="heading">
                                          <span class="title">€<span>{{ dutchCurrency(calCulateprojectBudget($segment->id, $projectdetail->id, $tabYear, 'revised_unit_amount')) }}</span></span>
                                       </div>
                                    </th>
                                    <th>
                                       <div class="heading">
                                          <span class="title">€<span>{{ dutchCurrency(calCulateprojectBudget($segment->id, $projectdetail->id, $tabYear, 'revised_new_budget')) }}</span></span>
                                       </div>
                                    </th>
                                    <th>

                                       <div class="heading">
                                          @if (calCulateprojectBudget($segment->id, $projectdetail->id, $tabYear, 'revised_annual') == 0 )
                                          <span class="title">€<span>{{ dutchCurrency(calCulateprojectBudget($segment->id, $projectdetail->id, $tabYear, 'approval_budget')) }}</span></span>
                                          @else
                                          <span class="title">€<span>{{ dutchCurrency(calCulateprojectBudget($segment->id, $projectdetail->id, $tabYear, 'revised_annual')) }}</span></span>
                                          @endif
                                       </div>
                                    </th>
                                    <th class="text-end action-toolbar">
                                       <div></div>
                                    </th>
                                 </tr>
                                 <tr
                                    class="secondary-header collapsable-header">
                                    <th class="first-column-fixed-to-left">
                                       <div>#</div>
                                    </th>
                                    <th class="second-column-fixed-to-left" style="min-width: 195px;width:195px;">
                                       <div>A. Total Budget Headline</div>
                                    </th>
                                    <th class="border-l-none" style="min-width: 145px;width: 145px;">Notes
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
                                    <th style="min-width: 101px;width: 101px;">Days
                                    </th>
                                    <th style="min-width: 119px;width: 119px;">+/- EUR Amount</th>
                                    <th style="min-width: 103px;width: 119px;">New Budget</th>
                                    <th style="min-width: 127px;width: 127px;">Fy {{ $tabYear }}</th>
                                    <th class="text-center action-toolbar">
                                       <div></div>
                                    </th>
                                 </tr>
                              </thead>
                              <tbody class="collapsable-body">
                                 @php
                                    $counter = 1;
                                 @endphp
                                 @forelse($segment->projecthierarchdata
                                 as $key3 => $hierarchdata)
                                 <tr class="details-row">
                                    <td class="first-column-fixed-to-left">
                                       <div>{{ $counter }}</div>
                                    </td>
                                    <td class="second-column-fixed-to-left">
                                       <div>{{ $hierarchdata['employee_name'] ?? 'Unlinked Exact Staff' }}</div>
                                    </td>
                                    <td class="border-l-none">
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
                                       <span>{{ isset($notes[$note]) ? $notes[$note] : 'Per day'; }}</span>
                                    </td>
                                    <td>
                                       <span>{{ netherlandformatCurrency($hierarchdata['units_total'], 'blur') }}</span>
                                    </td>
                                    <td>
                                       €<span>{{ netherlandformatCurrency($hierarchdata['unit_costs_total'], 'blur') }}</span>
                                    </td>
                                    <td>€<span>{{ netherlandformatCurrency($hierarchdata['total_approval_budget'], 'blur') }}</span>
                                    </td>
                                    <td>
                                       <span class="currency-sign">€{{ netherlandformatCurrency($hierarchdata['actual_expenses_to_date'], 'blur') }}</span>
                                    </td>
                                    <td class="{{ ($hierarchdata['remaining_balance'] ?? 0) < 0 ? 'text-error-500' : '' }}">€<span>{{ netherlandformatCurrency($hierarchdata['remaining_balance'], 'blur') }}</span>
                                    </td>
                                    <td class="editable-td p-0 units-input-number">
                                       <input type="text"
                                          class="table-input"
                                          placeholder="0"
                                          value="{{ netherlandformatCurrency($hierarchdata['revised_units'], 'blur') }}"
                                          min="1" max="10000"
                                          data-type='currency'
                                          disabled />
                                    </td>
                                    <td>€<span class="unitsAmount">{{ netherlandformatCurrency($hierarchdata['revised_unit_amount'], 'blur') }}</span></td>
                                    <td>€<span class="newBudget">{{ netherlandformatCurrency($hierarchdata['revised_new_budget'], 'blur') }}</span></td>
                                    <td>€<span class="revisedAnnual">{{ netherlandformatCurrency(($hierarchdata['revised_annual'] !=0) ? $hierarchdata['revised_annual'] : $hierarchdata['total_approval_budget'])}}</span></td>

                                    <td align="center" class="action-toolbar">
                                       <div></div>
                                    </td>
                                 </tr>
                                 @php
                                 $counter++;
                                 @endphp
                                 @empty
                                 <td colspan="13" align="left"
                                    class="no-data">
                                    <span
                                       class="text-xs font-semibold">You
                                       don’t have any data
                                       yet.</span>
                                 </td>
                                 @endforelse
                                 <tr
                                    class="tr empty-row">
                                    <td colspan="13"></td>
                                 </tr>
                              </tbody>

                              @elseif($segment->other == 0)
                              <thead>
                                 <tr
                                    class="primary-header total-direct-cost-tr">
                                    <th colspan="2" class="first-column-fixed-to-left">
                                       <div>{{ $segment->look_up_value }}</div>
                                    </th>
                                    <th style="min-width: 145px;width: 145px;"></th>
                                    <th style="min-width: 102px;width: 102px;"></th>
                                    <th style="min-width: 116px;width: 116px;"></th>
                                    <th style="min-width: 200px;width: 200px;">
                                       <div>
                                          €<span>{{ dutchCurrency(calCulateprojectBudget(null, $projectdetail->id, $tabYear, 'approval_budget')) }}</span>
                                       </div>
                                    </th>
                                    <th style="min-width: 202px;width: 202px;">
                                       <div>
                                          €<span>{{ dutchCurrency(calCulateprojectBudget(null, $projectdetail->id, $tabYear, 'actual_expenses')) }}</span>
                                       </div>
                                    </th>
                                    <th style="min-width: 200px;width: 200px;" class="{{ (calCulateprojectBudget(null, $projectdetail->id, $tabYear, 'remaining_balance') ?? 0) < 0 ? 'text-error-500' : '' }}">
                                       <div>
                                          €<span>{{ dutchCurrency(calCulateprojectBudget(null, $projectdetail->id, $tabYear, 'remaining_balance')) }}</span>
                                       </div>
                                    </th>
                                    <th colspan="3" style="text-align: center;min-width: 323px;width: 323px;"></th>
                                    <th style="min-width: 127px;width: 127px;"></th>
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
         @foreach ($projectTabData as $key => $subproject)
         <div class="tab-pane fade" id="revision_{{ $key }}_profile" role="tabpanel"
            aria-labelledby="General_OS" wire:ignore.self>
            <div class="sub-project-header d-flex align-item-center gap-3 mb-2">
               <div class="text-primary-500 text-lg font-bold">
                  {{ $subproject->sub_project_name }}
               </div>
            </div>
            @if(isset($subproject->project_hierarchy))
            @foreach ($subproject->project_hierarchy as $key2 => $segment)
            <div class="table-responsive mb-2">
               <table width="100%" class="subproject-table collapsed" wire:ignore.self
                  data-sub-project="{{ $subproject->id }}"
                  id="collapse_in_revision_{{ $key }}_{{ $key2 }}_{{ $segment->id }}">
                  @if ($segment->other == 1)
                  <thead>
                     <tr class="primary-header table-detail-acordian cursor-pointer" data-id="collapse_in_revision_{{ $key }}_{{ $key2 }}_{{ $segment->id }}">
                        <th colspan="2" class="first-column-fixed-to-left">
                           <div>{{ $segment->look_up_value }}</div>
                        </th>
                        <th class="border-l-none" style="min-width: 145px;width: 145px;"></th>
                        <th style="min-width: 102px;width: 102px;"></th>
                        </th>
                        <th style="min-width: 116px;width: 116px;">
                           <span class="headings">
                              Costs
                           </span>
                        </th>
                        <th style="min-width: 200px;width: 200px;">
                           <div class="details">
                              <span class="title">Current Year Budget</span>
                              <p class="values">
                                 €{{ dutchCurrency(calCulateprojectBudget($segment->id, $projectdetail->id, $tabYear, 'approval_budget', $subproject->id)) }}
                              </p>
                           </div>
                           <div class="total">
                              <p class="total-values">
                                 €{{ dutchCurrency(calCulateprojectBudget($segment->id, $projectdetail->id, $tabYear, 'approval_budget', $subproject->id)) }}
                              </p>
                           </div>
                        </th>
                        <th style="min-width: 202px;width: 202px;">
                           <div class="details">
                              <span class="title">Current Year Expenses</span>
                              <p class="values">
                                 €{{ dutchCurrency(calCulateprojectBudget($segment->id, $projectdetail->id, $tabYear, 'actual_expenses', $subproject->id)) }}
                              </p>
                           </div>
                           <div class="total">
                              <p class="total-values">
                                 €{{ dutchCurrency(calCulateprojectBudget($segment->id, $projectdetail->id, $tabYear, 'actual_expenses', $subproject->id)) }}
                              </p>
                           </div>
                        </th>
                        <th style="min-width: 200px;width: 200px;">
                           <div class="details">
                              <span class="title">Remaining
                                 Balance</span>
                              <p class="values {{ (calCulateprojectBudget($segment->id, $projectdetail->id, $tabYear, 'remaining_balance', $subproject->id) ?? 0) < 0 ? 'text-error-500' : '' }}">
                                 €{{ dutchCurrency(calCulateprojectBudget($segment->id, $projectdetail->id, $tabYear, 'remaining_balance', $subproject->id)) }}
                              </p>
                           </div>
                           <div class="total">
                              <p class="total-values {{ (calCulateprojectBudget($segment->id, $projectdetail->id, $tabYear, 'remaining_balance', $subproject->id) ?? 0) < 0 ? 'text-error-500' : '' }}">
                                 €{{ dutchCurrency(calCulateprojectBudget($segment->id, $projectdetail->id, $tabYear, 'remaining_balance', $subproject->id)) }}
                              </p>
                           </div>
                        </th>
                        <th colspan="3" style="text-align: center;min-width: 323px;width: 323px;">
                           <span class="headings">Revise Budget</span>
                        </th>
                        <th style="min-width: 127px;width: 127px;">
                           <span class="headings">Revise Annual</span>
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
                     <tr class="primary-header sub-primary-header collapsable-header table-detail-acordian cursor-pointer" data-id="collapse_in_revision_{{ $key }}_{{ $keys }}">
                        <th colspan="2" class="first-column-fixed-to-left">
                           <div>A. Total Personnel</div>
                        </th>
                        <th class="border-l-none"></th>
                        <th class=""></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th>
                           <div class="heading">
                              <span class="title">Units</span>
                           </div>
                        </th>
                        <th>
                           <div class="heading">
                              <span class="title">€<span>{{ dutchCurrency(calCulateprojectBudget($segment->id, $projectdetail->id, $tabYear, 'revised_unit_amount', $subproject->id)) }}</span></span>
                           </div>
                        </th>
                        <th>
                           <div class="heading">
                              <span class="title">€<span>{{ dutchCurrency(calCulateprojectBudget($segment->id, $projectdetail->id, $tabYear, 'revised_new_budget', $subproject->id)) }}</span></span>
                           </div>
                        </th>
                        <th>
                           <div class="heading">
                              @if (calCulateprojectBudget($segment->id, $projectdetail->id, $tabYear, 'revised_annual', $subproject->id) == 0 )
                              <span class="title">€<span>{{ dutchCurrency(calCulateprojectBudget($segment->id, $projectdetail->id, $tabYear, 'approval_budget', $subproject->id)) }}</span></span>
                              @else
                              <span class="title">€<span>{{ dutchCurrency(calCulateprojectBudget($segment->id, $projectdetail->id, $tabYear, 'revised_annual', $subproject->id)) }}</span></span>
                              @endif
                           </div>
                        </th>
                        <th class="text-end action-toolbar">
                           <div></div>
                        </th>
                     </tr>
                     <tr class="secondary-header collapsable-header">
                        <th class="first-column-fixed-to-left">
                           <div>#</div>
                        </th>
                        <th class="second-column-fixed-to-left" style="min-width: 195px;width:195px;">
                           <div>A. Total Budget Headline</div>
                        </th>
                        <th class="border-l-none" style="min-width: 145px;width: 145px;">Notes
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
                        <th class="editable" style="min-width: 101px;width: 101px;">Days
                           <span class="edit">
                              <img src="{{ asset('images/icons/table-cell-edit.svg') }}" alt="img">
                           </span>
                        </th>
                        <th style="min-width: 119px;width: 119px;">+/- EUR Amount</th>
                        <th style="min-width: 103px;width: 103px;">New Budget</th>
                        <th style="min-width: 127px;width: 127px;">Fy {{ $tabYear }}</th>
                        <th class="text-center action-toolbar">
                           <div></div>
                        </th>
                     </tr>
                  </thead>
                  <tbody class="collapsable-body">
                     @forelse($segment->projecthierarchdata as $key3 =>
                     $hierarchdata)
                     <tr class="details-row">
                        <td class="first-column-fixed-to-left">
                           <div>{{ $key3 + 1 }}</div>
                        </td>
                        <td class="second-column-fixed-to-left">
                           @if ($hierarchdata->project_hierarchy_id==6)
                           <div>{{ getExpenseName($hierarchdata->employee_id)?? $hierarchdata->exact_wbs_description }}</div>
                           @else
                           <div>{{ isset($hierarchdata->user->name)?$hierarchdata->user->name: $hierarchdata->employee_id ?? $hierarchdata->exact_wbs_description}}</div>
                           @endif
                        </td>
                        <td class="border-l-none">
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
                        <td><span>{{ $hierarchdata->units }}</span>
                        </td>
                        <td>
                           €<span>{{ netherlandformatCurrency($hierarchdata->unit_costs, 'blur') }}</span>
                        </td>
                        <td>€<span>{{ netherlandformatCurrency($hierarchdata->total_approval_budget, 'blur') }}</span>
                        </td>
                        <td>
                           <span class="currency-sign">€{{ netherlandformatCurrency($hierarchdata->actual_expenses_to_date, 'blur') }}</span>
                        </td>
                        <td class="{{ ($hierarchdata->remaining_balance ?? 0) < 0 ? 'text-error-500' : '' }}">€<span>{{ netherlandformatCurrency($hierarchdata->remaining_balance, 'blur') }}</span>
                        </td>
                        <td class="editable-td p-0 units-input-number">
                           @php
                           $revisedUnit = ($hierarchdata->revised_units!=0) ? netherlandformatCurrency($hierarchdata->revised_units) : '';
                           @endphp
                           <input type="text"
                              class="table-input revisedUnits{{ $hierarchdata->id }}"
                              placeholder="0"
                              min="1" max="10000" value="{{ $revisedUnit }}"
                              onkeyup="calculateReviseBudget({{ $hierarchdata->unit_costs }}, {{ $hierarchdata->remaining_balance }}, {{ $hierarchdata->actual_expenses_to_date }}, {{ $hierarchdata->id }}, event)" />
                           <span class="edit-pill">Edit</span>
                           <button type="submit" class="save-pill-revision" onclick="saveReviseBudget({{ $hierarchdata->id }}, {{ $hierarchdata->sub_project_id }})">save</button>
                        </td>
                        @if ($segment->id == 4 || $segment->id == 5 || $segment->id == 6 || $segment->id == 3)
                        <td class="editable-td p-0 units-input-number">
                           <span class="currency-sign">€</span><input
                              type="text"
                              class="table-input project-expenses-input revisedUnitCost{{ $hierarchdata->id }}"
                              onkeyup="calculateReviseBudget('', {{ $hierarchdata->remaining_balance }}, {{ $hierarchdata->actual_expenses_to_date }}, {{ $hierarchdata->id }}, event)"
                              placeholder="0" data-type="currency"
                              value="{{ netherlandformatCurrency($hierarchdata->revised_unit_amount, 'blur') }}">
                           <span class="edit-pill">Edit</span>
                           <button type="submit" class="save-pill" onclick="saveReviseBudget({{ $hierarchdata->id }}, {{ $hierarchdata->sub_project_id }})">save</button>
                        </td>

                        @else
                        <td>€<span class="unitsAmount{{ $hierarchdata->id }}">{{ netherlandformatCurrency($hierarchdata->revised_unit_amount) }}</span> </td>
                        @endif
                        <input class="unitsAmountVal{{ $hierarchdata->id }}" type="hidden" value="{{ $hierarchdata->revised_unit_amount }}"> <input class="unitsVal{{ $hierarchdata->id }}" type="hidden" value="{{ $hierarchdata->revised_units }}">
                        <td>€<span class="newBudget{{ $hierarchdata->id }}">{{ netherlandformatCurrency($hierarchdata->revised_new_budget)}}</span> <input class="newBudgetVal{{ $hierarchdata->id }}" type="hidden" value="{{ $hierarchdata->revised_new_budget }}"></td>
                        <td>€<span class="revisedAnnual{{ $hierarchdata->id }}">{{ $hierarchdata->revised_annual !=0 ? netherlandformatCurrency($hierarchdata->revised_annual) : netherlandformatCurrency($hierarchdata->total_approval_budget)}} </span> <input class="revisedAnnualVal{{ $hierarchdata->id }}" type="hidden" value="{{ ($hierarchdata->revised_annual !=0) ? $hierarchdata->revised_annual : $hierarchdata->total_approval_budget }}"></td>
                        <td align="center" class="action-toolbar">
                           <div></div>
                        </td>
                     </tr>
                     @empty
                     <td colspan="13" align="left"
                        class="no-data collapse_in_revision"
                        data-id="collapse_in_revision">
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
            @endIf
         </div>
         @endforeach
      </div>
      @endif
      <div class="pe-4 py-2 revision-current-year-footer">
         <div class="d-flex align-items-center gap-4">
            <h4 class="mb-0 text-primary-500 text-xs font-medium">INDIRECT RATE</h4>
            <div class="seprator"></div>
            <div class="d-flex align-items-center gap-2">
               <div class="box bg-primary-500">
               </div>
               <h4 class="mb-0 text-gray-500 text-xs font-regular">CONTRACTED</h4>
               <p class="mb-0 text-primary-800 text-xs font-bold">{{ $projectdetail->indirect_rate }}%</p>
            </div>
            <div class="d-flex align-items-center gap-2">
               <div class="box bg-success-500">
               </div>
               <h4 class="mb-0 text-gray-500 text-xs font-regular">ACTUAL</h4>
               @php
               $actual = calculateIndirectCost(calCulateprojectMatrix($projectdetail->id, $tabYear, 'approval_budget'), calCulateprojectBudget(null, $projectdetail->id, $tabYear, 'approval_budget')) == 0 ? '' : calculateIndirectCost(calCulateprojectMatrix($projectdetail->id, $tabYear, 'approval_budget'), calCulateprojectBudget(null, $projectdetail->id, $tabYear, 'approval_budget'));

               @endphp
               <p class="mb-0 text-primary-800 text-xs font-bold">{{ ($actual=='""' ? 0 : $actual) }}%</p>
            </div>
         </div>
      </div>
   </div>
</div>
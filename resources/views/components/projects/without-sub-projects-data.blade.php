@foreach ($data as $key => $segment)
<div class="table-responsive mb-2">
   <table width="100%"
      class="subproject-table {{ ($key!=0 && $key!=7) ? 'collapsed' : '' }}"
      id="collapse_in_{{ $key }}_{{ $key }}_empty_{{ $tabYear }}" wire:ignore.self>

      @if ($segment->other == 1)
      <thead>
         <tr class="primary-header table-detail-acordian cursor-pointer"
            data-id="collapse_in_{{ $key }}_{{ $key }}_empty_{{ $tabYear }}">
            <th colspan="4">
               {{ $segment->look_up_value }}
            </th>
            <th style="min-width: 116px;width: 116px;">
               <span class="headings">
                  Costs
               </span>
            </th>
            <th style="min-width: 200px;width: 200px;">
               <div class="details">
                  <span class="title">{{ $approvedBudget }}</span>
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
            <th style="min-width: 200px;width: 200px;">
               <div class="details">
                  <span class="title">{{ $tillTodayTitle }}</span>
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
                  <span class="title">{{ $leftoverTitle }}</span>
                  <p class="values {{ (calCulateprojectBudget($segment->id, $projectdetail->id, $tabYear, 'remaining_balance')) < 0 ? 'text-error-500' : '' }}">
                     €{{ dutchCurrency(calCulateprojectBudget($segment->id, $projectdetail->id, $tabYear, 'remaining_balance')) }}
                  </p>
               </div>
               <div class="total">
                  <p class="total-values {{ (calCulateprojectBudget($segment->id, $projectdetail->id, $tabYear, 'remaining_balance')) < 0 ? 'text-error-500' : '' }}">
                     €{{ dutchCurrency(calCulateprojectBudget($segment->id, $projectdetail->id, $tabYear, 'remaining_balance')) }}
                  </p>
               </div>
            </th>
            <th class="text-end action-toolbar">
               <a type="button" class="d-block table-detail-acordian">
                  <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                     xmlns="http://www.w3.org/2000/svg">
                     <path d="M13 6L8 11L3 6" stroke="#667085" stroke-width="1.5"
                        stroke-linecap="round" stroke-linejoin="round" />
                  </svg>
               </a>
            </th>
         </tr>
         <tr class="secondary-header collapsable-header">
            <th style="min-width: 58px;width:58px;text-align:center;">#</th>
            <th class="editable" style="min-width: 195px;width:195px;">A. Total Budget
               Headline
               <span class="edit">
                  <img src="{{ asset('images/icons/table-cell-edit.svg') }}"
                     alt="img">
               </span>
            </th>
            <th class="editable" style="min-width: 145px;width: 145px;">Notes
               <span class="edit">
                  <img src="{{ asset('images/icons/table-cell-edit.svg') }}"
                     alt="img">
               </span>
            </th>
            <th class="editable" style="min-width: 102px;width: 102px;">Units
               <span class="edit">
                  <img src="{{ asset('images/icons/table-cell-edit.svg') }}"
                     alt="img">
               </span>
            </th>
            <th style="min-width: 116px;width: 116px;">Unit Costs</th>
            <th style="min-width: 200px;width: 200px;">
               {{ $yearStartDate }} - {{ $yearEndDate }}
               [€]
            </th>
            <th class="editable" style="min-width: 202px;width: 202px;">
               @if ($isFuture)
               The budget hasn't begun yet.
               @else
               {{ $fromMonth }}, {{ $tabYear }} -
               {{ date('M') }},
               {{ $tabYear }} [€]
               <span class="edit">
                  <img src="{{ asset('images/icons/table-cell-edit.svg') }}"
                     alt="img">
               </span>
               @endIf
            </th>
            <th style="min-width: 200px;width: 200px;">
               {{ dateFormat(date('Y-m-d'), 'next_month') }}
               - {{$toMonth }} {{ $tabYear }}
               [€]
            </th>
            @if ($segment->other != 2)
            <th class="text-center action-toolbar">
               <div>Action</div>
            </th>
            @endif
         </tr>
      </thead>
      <tbody class="collapsable-body">
         @forelse($segment->projecthierarchdata as $key3 =>$hierarchdata)
         <tr class="details-row">
            <td style="text-align:center;">{{ $key3 + 1 }} </td>
            <td class="editable-td p-0 units-input-number">
               @if ($segment->id == 4 || $segment->id == 5)
               <input type="text"
                  class="table-input rows_{{ $key }}_{{ $key3 }}_{{ $tabYear }}_employee"
                  data-id="{{ $key }}_{{ $key3 }}_{{ $tabYear }}"
                  id="rows_{{ $key }}_{{ $key3 }}_{{ $tabYear }}_employee"
                  value="{{ $hierarchdata->employee_id }}" />
               @else
               <select
                  class="js-example-basic-single  table-select employee_data create-donot-phone change_employee rows_{{ $key }}_{{ $key3 }}_{{ $tabYear }}_employee w-100"
                  data-id="{{ $key }}_{{ $key3 }}_{{ $tabYear }}"
                  id="rows_{{ $key }}_{{ $key3 }}_{{ $tabYear }}_employee"
                  data-user-type-id="{{ $segment->id }}"
                  data-year="{{ $tabYear }}"
                  data-select22="rows_{{ $key }}_{{ $key3 }}_{{ $tabYear }}_employee" data-record-id="{{$hierarchdata->id}}">

                  @foreach ($segment->donors as $donor)
                  <option value="{{ $donor->id }}"
                     {{ $donor->id == $hierarchdata->employee_id ? 'selected' : '' }}>
                     {{ $donor->name }}
                  </option>
                  @endforeach
               </select>
               <input type="hidden"
                  class="rows_{{ $key }}_{{ $key3 }}_{{ $tabYear }}_employee_input"
                  data-id="{{ $key }}_{{ $key3 }}_{{ $tabYear }}"
                  value="{{ $hierarchdata->employee_id }}" />
               @endif
               <span class="edit-pill">Edit</span>
               <button type="submit" class="save-pill" data-year="{{ $tabYear }}"
                  data-id="{{ $key }}_{{ $key3 }}_{{ $tabYear }}"
                  data-sub-project-data-id="{{ $hierarchdata->id }}">save</button>
               <div id="rows_{{ $key }}_{{ $key3 }}_{{ $tabYear }}_employee_error"
                  class="text-error-500 project-validation-error rows_{{ $key }}_{{ $key3 }}_{{ $tabYear }}_employee_error">
               </div>
            </td>
            <td class="editable-td p-0 units-input-number">
               <select
                  class="js-example-basic-single  table-select rows_{{ $key }}_{{ $key3 }}_{{ $tabYear }}_note w-100"
                  data-minimum-results-for-search="Infinity"
                  data-id="{{ $key }}_{{ $key3 }}_{{ $tabYear }}"
                  id="rows_{{ $key }}_{{ $key3 }}_{{ $tabYear }}_note"
                  data-note="rows_{{ $key }}_{{ $key3 }}_{{ $tabYear }}_note">

                  <option value="per_day"
                     {{ 'per_day' == $hierarchdata->note ? 'selected' : '' }}>
                     Per day</option>
                  <option value="per_night"
                     {{ 'per_night' == $hierarchdata->note ? 'selected' : '' }}>
                     Per night</option>
                  <option value="per_month"
                     {{ 'per_month' == $hierarchdata->note ? 'selected' : '' }}>
                     Per month</option>
                  <option value="per_page"
                     {{ 'per_page' == $hierarchdata->note ? 'selected' : '' }}>
                     Per page
                  </option>
                  <option value="per_year"
                     {{ 'per_year' == $hierarchdata->note ? 'selected' : '' }}>
                     Per year</option>
                  <option value="per_partner"
                     {{ 'per_partner' == $hierarchdata->note ? 'selected' : '' }}>
                     Per partner</option>
                  <option value="per_item"
                     {{ 'per_item' == $hierarchdata->note ? 'selected' : '' }}>
                     Per item</option>
                  <option value="per_trip"
                     {{ 'per_trip' == $hierarchdata->note ? 'selected' : '' }}>
                     Per
                     trip
                  </option>
                  <option value="per_event"
                     {{ 'per_event' == $hierarchdata->note ? 'selected' : '' }}>
                     Per event</option>

               </select>
               <span class="edit-pill">Edit</span>
               <button type="submit" class="save-pill" data-year="{{ $tabYear }}"
                  data-id="{{ $key }}_{{ $key3 }}_{{ $tabYear }}"
                  data-sub-project-data-id="{{ $hierarchdata->id }}">save</button>
            </td>
            <td class="editable-td p-0 units-input-number">
               <input type="text"
                  class="table-input project_unit rows_{{ $key }}_{{ $key3 }}_{{ $tabYear }}_unit"
                  data-id="{{ $key }}_{{ $key3 }}_{{ $tabYear }}"
                  placeholder="0"
                  value="{{ netherlandformatCurrency($hierarchdata->units) }}"
                  min="1" max="10000"
                  data-type='currency'
                  id="rows_{{ $key }}_{{ $key3 }}_{{ $tabYear }}_unit" />
               <span class="edit-pill">Edit</span>
               <button type="submit" class="save-pill" data-year="{{ $tabYear }}"
                  data-id="{{ $key }}_{{ $key3 }}_{{ $tabYear }}"
                  data-sub-project-data-id="{{ $hierarchdata->id }}">save</button>
            </td>
            @if ($segment->id == 4 || $segment->id == 5 || $segment->id == 6 || $segment->id == 3)
            <td class="editable-td p-0 units-input-number">
               <span class="currency-sign">€</span><input type="text"
                  class="table-input project-expenses-input rows_{{ $key }}_{{ $key3 }}_{{ $tabYear }}_unitcost unit_cost"
                  placeholder="0" data-type="currency"
                  data-id="{{ $key }}_{{ $key3 }}_{{ $tabYear }}"
                  value="{{ netherlandformatCurrency($hierarchdata->unit_costs, 'blur') }}">
               <span class="edit-pill">Edit</span>
               <button type="submit" class="save-pill" data-year="{{ $tabYear }}"
                  data-id="{{ $key }}_{{ $key3 }}_{{ $tabYear }}"
                  data-sub-project-data-id="{{ $hierarchdata->id }}">save</button>
            </td>
            @else
            <td>
               €<span
                  class="rows_{{ $key }}_{{ $key3 }}_{{ $tabYear }}_unitcost">{{ netherlandformatCurrency($hierarchdata->unit_costs, 'blur') }}</span>
            </td>
            @endif
            <td>€<span
                  class="rows_{{ $key }}_{{ $key3 }}_{{ $tabYear }}_approval_budget">{{ netherlandformatCurrency($hierarchdata->total_approval_budget, 'blur') }}</span>
            </td>
            <td class="editable-td p-0 units-input-number">
               <span class="currency-sign">€</span><input type="text"
                  class="table-input project_expenses project-expenses-input rows_{{ $key }}_{{ $key3 }}_{{ $tabYear }}_expenses"
                  value="{{ netherlandformatCurrency($hierarchdata->actual_expenses_to_date, 'blur') }}"
                  data-id="{{ $key }}_{{ $key3 }}_{{ $tabYear }}"
                  placeholder="0" min="1" max="10000"
                  id="rows_{{ $key }}_{{ $key3 }}_{{ $tabYear }}_expenses"
                  data-type="currency" />
               <span class="edit-pill">Edit</span>
               <button type="submit" class="save-pill" data-year="{{ $tabYear }}"
                  data-id="{{ $key }}_{{ $key3 }}_{{ $tabYear }}"
                  data-sub-project-data-id="{{ $hierarchdata->id }}">save</button>
               <div id="rows_{{ $key }}_{{ $key3 }}_{{ $tabYear }}_expenses"
                  class="text-error-500 project-validation-error rows_{{ $key }}_{{ $key3 }}_{{ $tabYear }}_project_expenses_error">
               </div>
            </td>
            <td class="{{ ($hierarchdata->remaining_balance ?? 0) < 0 ? 'text-error-500' : '' }}">€<span

                  class="rows_{{ $key }}_{{ $key3 }}_{{ $tabYear }}_remaining_balance">{{ dutchCurrency($hierarchdata->remaining_balance, 'blur') }}</span>

            </td>
            <td align="center" class="action-toolbar">
               <a wire:click="confirmDelete({{ $hierarchdata->id }},{{ $segment->id }},'NULL','NULL')"
                  data-bs-toggle="modal"
                  data-delete-row-id="{{ $hierarchdata->id }}"
                  data-sub-project="0" class="delete-row-btn delete_percentage"
                  href="#delete_item_project"
                  data-id="data-id-{{ $key }}-{{ $key3 }}">
                  <svg width="20" height="20" viewBox="0 0 20 20"
                     fill="none" xmlns="http://www.w3.org/2000/svg">
                     <path d="M16.875 4.375H3.125" stroke="#F04438"
                        stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round" />
                     <path d="M8.125 8.125V13.125" stroke="#F04438"
                        stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round" />
                     <path d="M11.875 8.125V13.125" stroke="#F04438"
                        stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round" />
                     <path
                        d="M15.625 4.375V16.25C15.625 16.4158 15.5592 16.5747 15.4419 16.6919C15.3247 16.8092 15.1658 16.875 15 16.875H5C4.83424 16.875 4.67527 16.8092 4.55806 16.6919C4.44085 16.5747 4.375 16.4158 4.375 16.25V4.375"
                        stroke="#F04438" stroke-width="1.5"
                        stroke-linecap="round" stroke-linejoin="round" />
                     <path
                        d="M13.125 4.375V3.125C13.125 2.79348 12.9933 2.47554 12.7589 2.24112C12.5245 2.0067 12.2065 1.875 11.875 1.875H8.125C7.79348 1.875 7.47554 2.0067 7.24112 2.24112C7.0067 2.47554 6.875 2.79348 6.875 3.125V4.375"
                        stroke="#F04438" stroke-width="1.5"
                        stroke-linecap="round" stroke-linejoin="round" />
                  </svg>
               </a>
            </td>
         </tr>
         @empty
         <td colspan="9" align="left"
            class="no-data collapse_in_{{ $key }}_{{ $key }}_empty_{{ $tabYear }}"
            data-id="collapse_in_{{ $key }}_{{ $key }}_empty_{{ $tabYear }}">
            <span class="text-xs font-medium">You don’t have any data yet, click on ”+ Add” button to create new data</span>
         </td>
         @endforelse
         <tr
            class="tr_collapse_in_{{ $key }}_{{ $key }}_{{ $tabYear }}">
            <td colspan="9" align="left" class="add-employee">
               <button class="btn add_employee_row"
                  data-project-id="{{ $projectdetail->id }}" data-sub-project-id=""
                  data-type-id="{{ $segment->id }}"
                  data-id="collapse_in_{{ $key }}_{{ $key }}_{{ $tabYear }}"
                  data-year="{{ $tabYear }}">+
                  Add
                  {{ convertTosection($segment->id) }}</button>
            </td>
         </tr>
      </tbody>
      @elseif($segment->other == 2)
      <thead>
         <tr class="primary-header indirect-costs">
            <th colspan="4">
               {{ $segment->look_up_value }}
            </th>
            <th style="min-width: 116px;width: 116px;">
               <span class="headings">
                  Costs
               </span>
            </th>
            <th style="min-width: 200px;width: 200px;">
               <div class="details">
                  <span class="title">{{ $approvedBudget }}</span>
                  <p class="values">
                     €<span
                        class="total_approval_budget_indirect_cost_empty_{{ $tabYear }}">{{ dutchCurrency(calculateIndirectCostWithoutRevision(calCulateprojectBudget(null, $projectdetail->id, $tabYear, 'approval_budget'), $projectdetail->id, $tabYear, null)) }}</span>
                  </p>
               </div>
            </th>
            <th style="min-width: 200px;width: 200px;">
               <div class="details">
                  <span class="title">{{ $tillTodayTitle }}</span>
                  <p class="values">
                     €<span
                        class="total_actual_expenses_indirect_cost_empty_{{ $tabYear }}">{{ dutchCurrency(calculateIndirectCostWithoutRevision(calCulateprojectBudget(null, $projectdetail->id, $tabYear, 'actual_expenses'), $projectdetail->id, $tabYear, null)) }}</span>
                  </p>
               </div>
            </th>
            <th style="min-width: 200px;width: 200px;">
               <div class="details">
                  <span class="title">{{ $leftoverTitle }}</span>
                  <p class="values {{ dutchCurrency(calculateIndirectCostWithoutRevision(calCulateprojectBudget(null, $projectdetail->id, $tabYear, 'remaining_balance'), $projectdetail->id, $tabYear, null)) }}">
                     €<span
                        class="total_remaining_balance_indirect_cost_empty_{{ $tabYear }}">{{ dutchCurrency(calculateIndirectCostWithoutRevision(calCulateprojectBudget(null, $projectdetail->id, $tabYear, 'remaining_balance'), $projectdetail->id, $tabYear, null)) }}</span>
                  </p>
               </div>
            </th>
            <th class="action-toolbar">
               <div></div>
            </th>
         </tr>
         <tr class="secondary-header collapsable-header indirect-costs">
            <th style="min-width: 58px;width:58px;text-align:center;">#</th>
            <th style="min-width: 195px;width:195px;">A. Total Budget Headline</th>
            <th style="min-width: 145px;width: 145px;">Notes </th>
            <th style="min-width: 102px;width: 102px;">Units </th>
            <th class="editable" style="min-width: 116px;width: 116px;">
               %
               <span class="edit">
                  <img src="{{ asset('images/icons/table-cell-edit.svg') }}"
                     alt="img">
               </span>
            </th>
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
            <th class="action-toolbar">
               <div></div>
            </th>
         </tr>
      </thead>
      <tbody>
         <tr class="details-row indirect-costs">
            <td>1</td>
            <td>
               Indirect Cost
            </td>
            <td>-</td>
            <td>-</td>
            
            <td class="editable-td p-0 units-input-number">
               @if (indirectcost(null, $projectdetail->id, $tabYear))
               <input type="text" class="table-input indirect_cost"
                  data-indirect-percentage-year="{{ $tabYear }}"
                  data-project-id="{{ $projectdetail->id }}" data-id="empty_{{ $tabYear }}"
                  placeholder="20.15%" min="1" max="99"
                  value="{{ getPercentage($projectdetail->id, $tabYear, null) }}"
                  data-sub-project="0" id="percentageInput"
                  onkeypress="return validateFloatKeyPress(this,event);" />
               <span class="edit-pill">Edit</span>
               <button type="submit" class="save-pill-indirect-cost"
                  data-indirect-percentage-year="{{ $tabYear }}"
                  data-project-id="{{ $projectdetail->id }}" data-id="empty_{{ $tabYear }}"
                  data-year="{{ $tabYear }}"
                  value="{{ getPercentage($projectdetail->id, $tabYear, null) }}">save</button>
               @else
               <div class="pointer-events">
                  <input type="number" class="table-input indirect_cost" placeholder="0%" disabled />
               </div>
               @endif
            </td>
            <td>€<span
                  class="total_approval_budget_indirect_cost_empty_{{ $tabYear }}">{{ dutchCurrency(calculateIndirectCostWithoutRevision(calCulateprojectBudget(null, $projectdetail->id, $tabYear, 'approval_budget'), $projectdetail->id, $tabYear, null)) }}</span>
            </td>
            <td>€<span
                  class="total_actual_expenses_indirect_cost_empty_{{ $tabYear }}">{{ dutchCurrency(calculateIndirectCostWithoutRevision(calCulateprojectBudget(null, $projectdetail->id, $tabYear, 'actual_expenses'), $projectdetail->id, $tabYear, null)) }}
               </span>
            </td>
            <td class="{{ (calculateIndirectCostWithoutRevision(calCulateprojectBudget(null, $projectdetail->id, $tabYear, 'remaining_balance'), $projectdetail->id, $tabYear, null) ?? 0) < 0 ? 'text-error-500' : '' }}">€<span
                  class="total_remaining_balance_indirect_cost_empty_{{ $tabYear }}">{{ dutchCurrency(calculateIndirectCostWithoutRevision(calCulateprojectBudget(null, $projectdetail->id, $tabYear, 'remaining_balance'), $projectdetail->id, $tabYear, null)) }}</span>
            </td>
            <td class="border-l-none action-toolbar">
               <div></div>
            </td>
         </tr>
         <tr class="estimated-row indirect-costs">
            <td colspan="5">
               TOTAL ESTIMATED COSTS
            </td>
            <td>€<span
                  class="total_estimate_approval_budget_empty_{{ $tabYear }}">{{ dutchCurrency(calculateTotalEstimateCost($projectdetail->id, $tabYear, 'approval_budget')) }}</span>
            </td>
            <td>€<span
                  class="total_estimate_actual_expenses_empty_{{ $tabYear }}">{{ dutchCurrency(calculateTotalEstimateCost($projectdetail->id, $tabYear, 'actual_expenses')) }}</span>
            </td>
            <td class="{{ (calculateTotalEstimateCost($projectdetail->id, $tabYear, 'remaining_balance') ?? 0) < 0 ? 'text-error-500' : '' }}">€<span
                  class="total_estimate_remaining_balance_empty_{{ $tabYear }}">{{ dutchCurrency(calculateTotalEstimateCost($projectdetail->id, $tabYear, 'remaining_balance')) }}</span>
            </td>
            <td class="action-toolbar">
               <div></div>
            </td>
         </tr>
      </tbody>
      @elseif($segment->other == 0)
      <thead>
         <tr class="primary-header total-direct-cost-tr">
            <th colspan="4">
               {{ $segment->look_up_value }}
            </th>
            <th style="min-width: 116px;width: 116px;"></th>
            <th style="min-width: 200px;width: 200px;">
               <div>
                  €<span
                     class="total_approval_budget_empty_{{ $tabYear }}">{{ dutchCurrency(calCulateprojectBudget(null, $projectdetail->id, $tabYear, 'approval_budget')) }}</span>
               </div>
            </th>
            <th style="min-width: 200px;width: 200px;">
               <div>
                  €<span
                     class="total_actual_expenses_empty_{{ $tabYear }}">{{ dutchCurrency(calCulateprojectBudget(null, $projectdetail->id, $tabYear, 'actual_expenses')) }}</span>
               </div>
            </th>
            <th style="min-width: 200px;width: 200px;" class="{{ (calCulateprojectBudget(null, $projectdetail->id, $tabYear, 'remaining_balance') ?? 0) < 0 ? 'text-error-500' : '' }}">
               <div>
                  €<span
                     class="total_remaining_balance_empty_{{ $tabYear }} ">{{ dutchCurrency(calCulateprojectBudget(null, $projectdetail->id, $tabYear, 'remaining_balance')) }}</span>
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
<div class="tab-content" id="myTabContent{{ $tabYear }}">
   <div class="tab-pane fade show active"
      id="home{{ $tabYear }}" role="tabpanel" aria-labelledby="all-projects" wire:ignore.self>
      <div class="accordion subproject-acordian" id="accordionExample{{ $tabYear }}">
         <div class="accordion-item">
            <h2 class="accordion-header sub-project-header" id="headingOne">
               <div class="d-flex align-items-center mb-2 gap-3">
                  <button class="accordion-button w-auto" type="button"
                     data-bs-toggle="collapse"
                     data-bs-target="#collapse_{{ $tabYear }}"
                     aria-expanded="true"
                     aria-controls="collapse_{{ $tabYear }}">
                     All Projects
                  </button>
               </div>
            </h2>
            <div id="collapse_{{ $tabYear }}"
               class="accordion-collapse collapse show"
               aria-labelledby="headingOne"
               data-bs-parent="#accordionExample{{ $tabYear }}">
               <div class="accordion-body">

                  @forelse($all_projects as $keys=> $segment)
                  <div class="table-responsive mb-2">
                     <table width="100%"
                        class="subproject-table all-projects-table collapsed"
                        data-sub-project="{{ $subproject->id }}" wire:ignore.self
                        id="collapse_in_{{ $key }}_{{ $keys }}_{{ $tabYear }}">
                        @if ($segment->other == 1)
                        <thead>
                           <tr class="primary-header table-detail-acordian cursor-pointer"
                              data-id="collapse_in_{{ $key }}_{{ $keys }}_{{ $tabYear }}">
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
                                    <span class="title">{{ $tillTodayTitle }} </span>
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
                              <th class="editable" style="min-width: 195px;width:195px;">
                                 A.
                                 Total Budget
                                 Headline
                              </th>
                              <th class="editable" style="min-width: 145px;width: 145px;">
                                 Notes
                              </th>
                              <th class="editable" style="min-width: 102px;width: 102px;">
                                 Units
                              </th>
                              <th class="editable" style="min-width: 116px;width: 116px;">
                                 Unit Costs
                              </th>
                              <th style="min-width: 200px;width: 200px;">
                                 {{ $yearStartDate }} - {{ $yearEndDate }}[€]
                              </th>
                              <th class="editable" style="min-width: 202px;width: 202px;">
                                 @if ($isFuture)
                                 The budget hasn't begun yet.
                                 @else
                                 {{ $fromMonth }},
                                 {{ $tabYear }}
                                 -
                                 {{ date('M') }},
                                 {{ $tabYear }}
                                 [€]
                                 @endif
                              </th>
                              <th style="min-width: 200px;width: 200px;">
                                 {{ dateFormat(date('Y-m-d'), 'next_month') }}
                                 - {{$toMonth }}
                                 {{ $tabYear }}
                                 [€]
                              </th>
                              <!-- @if ($segment->other != 2)
                              <th class="text-center action-toolbar">
                                 <div>Action
                                 </div>
                              </th>
                              @endif -->
                           </tr>
                        </thead>
                        <tbody class="collapsable-body">
                           @php
                           $counter = 1;
                           @endphp
                           @forelse($segment->projecthierarchdata
                           as $key3 => $hierarchdata)

                           <tr class="details-row">
                              <td style="text-align:center;">{{ $counter }}
                              </td>
                              <td class="editable-td p-0 units-input-number">
                                 <input type="text" class="table-input"
                                    value="{{ $hierarchdata['employee_name']??'Unlinked Exact Staff' }}"
                                    readonly />
                              </td>
                              <td class="editable-td p-0 units-input-number">
                                 <select
                                    class="table-select table-select rows_{{ $key3 }}_{{ $keys }}_{{ $key }}_note w-100"
                                    data-minimum-results-for-search="Infinity"
                                    id="rows_{{ $key3 }}_{{ $keys }}_{{ $key }}_note"
                                    data-note="rows_{{ $key3 }}_{{ $keys }}_{{ $key }}_note"
                                    disabled>

                                    <option value="per_day"
                                       {{ 'per_day' == $hierarchdata['note'] ? 'selected' : '' }}>
                                       Per
                                       day
                                    </option>
                                    <option value="per_night"
                                       {{ 'per_night' == $hierarchdata['note'] ? 'selected' : '' }}>
                                       Per
                                       night
                                    </option>
                                    <option value="per_month"
                                       {{ 'per_month' == $hierarchdata['note'] ? 'selected' : '' }}>
                                       Per
                                       month
                                    </option>
                                    <option value="per_year"
                                       {{ 'per_year' == $hierarchdata['note'] ? 'selected' : '' }}>
                                       Per
                                       year
                                    </option>
                                    <option value="per_partner"
                                       {{ 'per_partner' == $hierarchdata['note'] ? 'selected' : '' }}>
                                       Per
                                       partner
                                    </option>
                                    <option value="per_item"
                                       {{ 'per_item' == $hierarchdata['note'] ? 'selected' : '' }}>
                                       Per
                                       item
                                    </option>
                                    <option value="per_trip"
                                       {{ 'per_trip' == $hierarchdata['note'] ? 'selected' : '' }}>
                                       Per
                                       trip
                                    </option>
                                    <option value="per_event"
                                       {{ 'per_event' == $hierarchdata['note'] ? 'selected' : '' }}>
                                       Per
                                       event
                                    </option>

                                 </select>
                              </td>
                              <td class="editable-td p-0 units-input-number">
                                 <input type="text" class="table-input"
                                    placeholder="0"
                                    value="{{ netherlandformatCurrency($hierarchdata['units_total'], 'blur') }}"
                                    min="1" max="10000"
                                    data-type='currency' disabled />
                              </td>
                              <td>
                                 €<span>{{ netherlandformatCurrency($hierarchdata['unit_costs_total'], 'blur') }}</span>
                              </td>
                              <td>€<span>{{ netherlandformatCurrency($hierarchdata['total_approval_budget'], 'blur') }}</span>
                              </td>
                              <td class="editable-td p-0 units-input-number">
                                 <span class="currency-sign">€</span>
                                 <input
                                    type="text"
                                    class="table-input project_expenses project-expenses-input"
                                    value="{{ netherlandformatCurrency($hierarchdata['actual_expenses_to_date'], 'blur') }}"
                                    placeholder="0" min="1"
                                    max="10000" data-type='currency'
                                    disabled />
                              </td>
                              <td class="{{ ($hierarchdata['remaining_balance'] ?? 0) < 0 ? 'text-error-500' : '' }} ">€<span>{{ dutchCurrency($hierarchdata['remaining_balance'], 'blur') }}</span>
                              </td>
                              <!-- <td align="center" class="action-toolbar">
                                 <a wire:click="confirmAllDelete({{ $hierarchdata['id'] }},{{ $segment->id }},{{ calculateIndirectCost(calCulateprojectMatrix($projectdetail->id, $tabYear, 'approval_budget'), calCulateprojectBudget(null, $projectdetail->id, $tabYear, 'approval_budget')) == 0 ? '' : calculateIndirectCost(calCulateprojectMatrix($projectdetail->id, $tabYear, 'approval_budget'), calCulateprojectBudget(null, $projectdetail->id, $tabYear, 'approval_budget')) }})"
                                    data-bs-toggle="modal"
                                    data-delete-row-id="{{ $hierarchdata['id'] }}"
                                    class="delete-row-btn"
                                    href="#delete_item_all_project">
                                    <svg width="20" height="20"
                                       viewBox="0 0 20 20" fill="none"
                                       xmlns="http://www.w3.org/2000/svg">
                                       <path d="M16.875 4.375H3.125"
                                          stroke="#F04438"
                                          stroke-width="1.5"
                                          stroke-linecap="round"
                                          stroke-linejoin="round" />
                                       <path d="M8.125 8.125V13.125"
                                          stroke="#F04438"
                                          stroke-width="1.5"
                                          stroke-linecap="round"
                                          stroke-linejoin="round" />
                                       <path d="M11.875 8.125V13.125"
                                          stroke="#F04438"
                                          stroke-width="1.5"
                                          stroke-linecap="round"
                                          stroke-linejoin="round" />
                                       <path
                                          d="M15.625 4.375V16.25C15.625 16.4158 15.5592 16.5747 15.4419 16.6919C15.3247 16.8092 15.1658 16.875 15 16.875H5C4.83424 16.875 4.67527 16.8092 4.55806 16.6919C4.44085 16.5747 4.375 16.4158 4.375 16.25V4.375"
                                          stroke="#F04438"
                                          stroke-width="1.5"
                                          stroke-linecap="round"
                                          stroke-linejoin="round" />
                                       <path
                                          d="M13.125 4.375V3.125C13.125 2.79348 12.9933 2.47554 12.7589 2.24112C12.5245 2.0067 12.2065 1.875 11.875 1.875H8.125C7.79348 1.875 7.47554 2.0067 7.24112 2.24112C7.0067 2.47554 6.875 2.79348 6.875 3.125V4.375"
                                          stroke="#F04438"
                                          stroke-width="1.5"
                                          stroke-linecap="round"
                                          stroke-linejoin="round" />
                                    </svg>
                                 </a>
                              </td> -->
                           </tr>
                           @php
                           $counter++;
                           @endphp
                           @empty
                           <td colspan="9" align="left"
                              class="no-data collapse_in_{{ $key }}_{{ $keys }}_{{ $tabYear }}"
                              data-id="collapse_in_{{ $key }}_{{ $keys }}_{{ $tabYear }}">
                              <span class="text-xs font-semibold">You
                                 don’t have
                                 any
                                 data
                                 yet.</span>
                           </td>
                           @endforelse
                           <tr
                              class="tr_collapse_in_{{ $key }}_{{ $keys }}_{{ $tabYear }} empty-row">
                              <td colspan="9">
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
                                          class="total_approval_budget_indirect_cost_{{ $tabYear }}{{ $key }}_seprate">{{ ducthCalculationIndirect(
                                       calCulateprojectMatrix($projectdetail->id, $tabYear, 'approval_budget'),
                                       calCulateprojectBudget(null, $projectdetail->id, $tabYear, 'approval_budget'),
                                       ) }}</span>
                                    </p>
                                 </div>
                              </th>
                              <th style="min-width: 200px;width: 200px;">
                                 <div class="details">
                                    <span class="title">{{ $tillTodayTitle }} </span>
                                    <p class="values">
                                       €<span
                                          class="total_actual_expenses_indirect_cost_{{ $tabYear }}{{ $key }}_seprate">{{ ducthCalculationIndirect(
                                       calCulateprojectMatrix($projectdetail->id, $tabYear, 'actual_expenses'),
                                       calCulateprojectBudget(null, $projectdetail->id, $tabYear, 'actual_expenses'),
                                       ) }}</span>
                                    </p>
                                 </div>
                              </th>
                              <th style="min-width: 200px;width: 200px;">
                                 <div class="details">
                                    <span class="title">{{ $leftoverTitle }}</span>
                                    <p class="values {{ (ducthCalculationIndirect(
                                       calCulateprojectMatrix($projectdetail->id, $tabYear, 'remaining_balance'),
                                       calCulateprojectBudget(null, $projectdetail->id, $tabYear, 'remaining_balance'),
                                       ) ?? 0) < 0 ? 'text-error-500' : '' }}">
                                       €<span
                                          class="total_remaining_balance_indirect_cost_{{ $tabYear }}{{ $key }}_seprate currencyMasking">{{ ducthCalculationIndirect(
                                       calCulateprojectMatrix($projectdetail->id, $tabYear, 'remaining_balance'),
                                       calCulateprojectBudget(null, $projectdetail->id, $tabYear, 'remaining_balance'),
                                       ) }}</span>
                                    </p>
                                 </div>
                              </th>
                              <th class="action-toolbar">
                                 <div></div>
                              </th>
                           </tr>
                           <tr class="secondary-header collapsable-header indirect-costs">
                              <th style="min-width: 58px;width:58px;text-align:center;">#</th>
                              <th style="min-width: 195px;width:195px;">A. Total Budget
                                 Headline
                              </th>
                              <th style="min-width: 145px;width: 145px;">Notes </th>
                              <th style="min-width: 102px;width: 102px;">Units </th>
                              <th class="editable" style="min-width: 116px;width: 116px;">
                                 %
                              </th>
                              <th style="min-width: 200px;width: 200px;">
                                 {{ $yearStartDate }} - {{ $yearEndDate }}
                                 [€]
                              </th>
                              <th style="min-width: 202px;width: 202px;">
                                 @if ($isFuture)
                                 The budget hasn't begun yet.
                                 @else
                                 {{ $fromMonth }}
                                 {{ $tabYear }}
                                 -
                                 {{ date('M') }}
                                 {{ $tabYear }}
                                 [€]
                                 @endif
                              </th>
                              <th colspan="1" style="min-width: 200px;width: 200px;">
                                 {{ dateFormat(date('Y-m-d'), 'next_month') }}
                                 - {{$toMonth }}
                                 {{ $tabYear }}
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
                                 @if (indirectcost($subproject->id, $subproject->project_id, $tabYear))
                                 <input type="text"
                                    class="table-input indirect_cost"
                                    placeholder="20.15%" min="1"
                                    max="99"
                                    value="{{ calculateIndirectCost(calCulateprojectMatrix($projectdetail->id, $tabYear, 'approval_budget'), calCulateprojectBudget(null, $projectdetail->id, $tabYear, 'approval_budget')) == 0 ? '' : calculateIndirectCost(calCulateprojectMatrix($projectdetail->id, $tabYear, 'approval_budget'), calCulateprojectBudget(null, $projectdetail->id, $tabYear, 'approval_budget')) }}"
                                    disabled />
                                 @else
                                 <div class="pointer-events">
                                    <input type="number" class="table-input"
                                       value="{{ calculateIndirectCost(calCulateprojectMatrix($projectdetail->id, $tabYear, 'approval_budget'), calCulateprojectBudget(null, $projectdetail->id, $tabYear, 'approval_budget')) == 0 ? '' : calculateIndirectCost(calCulateprojectMatrix($projectdetail->id, $tabYear, 'approval_budget'), calCulateprojectBudget(null, $projectdetail->id, $tabYear, 'approval_budget')) }}"
                                       disabled />
                                 </div>
                                 @endif
                              </td>
                              <td>€<span
                                    class="total_approval_budget_indirect_cost_{{ $tabYear }}{{ $key }}_seprate">{{ ducthCalculationIndirect(
                                 calCulateprojectMatrix($projectdetail->id, $tabYear, 'approval_budget'),
                                 calCulateprojectBudget(null, $projectdetail->id, $tabYear, 'approval_budget'),
                                 ) }}</span>
                              </td>
                              <td>€<span
                                    class="total_actual_expenses_indirect_cost_{{ $tabYear }}{{ $key }}_seprate">{{ ducthCalculationIndirect(
                                 calCulateprojectMatrix($projectdetail->id, $tabYear, 'actual_expenses'),
                                 calCulateprojectBudget(null, $projectdetail->id, $tabYear, 'actual_expenses'),
                                 ) }}</span>
                              </td>
                              <td>€<span
                                    class="total_remaining_balance_indirect_cost_{{ $tabYear }}{{ $key }}_seprate currencyMasking {{ (ducthCalculationIndirect(
                                 calCulateprojectMatrix($projectdetail->id, $tabYear, 'remaining_balance'),
                                 calCulateprojectBudget(null, $projectdetail->id, $tabYear, 'remaining_balance'),
                                 ) ?? 0) < 0 ? 'text-error-500' : '' }}">{{ ducthCalculationIndirect(
                                 calCulateprojectMatrix($projectdetail->id, $tabYear, 'remaining_balance'),
                                 calCulateprojectBudget(null, $projectdetail->id, $tabYear, 'remaining_balance'),
                                 ) }}</span>
                              </td>
                              <td class="border-l-none action-toolbar">
                                 <div></div>
                              </td>
                           </tr>
                           <tr class="estimated-row indirect-costs">
                              <td colspan="5">
                                 TOTAL ESTIMATED
                                 COSTS
                              </td>
                              <td>€<span
                                    class="total_estimate_approval_budget_{{ $tabYear }}{{ $key }}_seprate">{{ dutchCurrency(calCulateprojectMatrix($projectdetail->id, $tabYear, 'approval_budget')) }}</span>
                              </td>
                              <td>€<span
                                    class="total_estimate_actual_expenses_{{ $tabYear }}{{ $key }}_seprate">{{ dutchCurrency(calCulateprojectMatrix($projectdetail->id, $tabYear, 'actual_expenses')) }}</span>
                              </td>
                              <td>€<span
                                    class="total_estimate_remaining_balance_{{ $tabYear }}{{ $key }}_seprate currencyMasking {{ (calCulateprojectMatrix($projectdetail->id, $tabYear, 'remaining_balance') ?? 0) < 0 ? 'text-error-500' : '' }}">{{ dutchCurrency(calCulateprojectMatrix($projectdetail->id, $tabYear, 'remaining_balance')) }}</span>
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
                                       class="total_approval_budget_{{ $tabYear }}{{ $key }}_seprate">{{ dutchCurrency(calCulateprojectBudget(null, $projectdetail->id, $tabYear, 'approval_budget')) }}</span>
                                 </div>
                              </th>
                              <th style="min-width: 200px;width: 200px;">
                                 <div>
                                    €<span
                                       class="total_actual_expenses_{{ $tabYear }}{{ $key }}_seprate">{{ dutchCurrency(calCulateprojectBudget(null, $projectdetail->id, $tabYear, 'actual_expenses')) }}</span>
                                 </div>
                              </th>
                              <th style="min-width: 200px;width: 200px;">
                                 <div>
                                    €<span
                                       class="total_remaining_balance_{{ $tabYear }}{{ $key }}_seprate currencyMasking {{ (calCulateprojectBudget(null, $projectdetail->id, $tabYear, 'remaining_balance') ?? 0) < 0 ? 'text-error-500' : '' }}">{{ dutchCurrency(calCulateprojectBudget(null, $projectdetail->id, $tabYear, 'remaining_balance')) }}</span>
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
                  <div class="table-responsive mb-2">No
                     Data
                     Found
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

   <div class="tab-pane fade"
      id="{{ $tabYear }}_{{ $key }}_profile" role="tabpanel" aria-labelledby="General_OS" wire:ignore.self>

      @include('livewire.project.project_matrix', [
      'project_id' => $projectdetail->id,
      'sub_project' => $subproject,
      'project_detail' => $projectdetail,
      'sub_project_data' => 1,
      'tabYear' => $tabYear
      ])

      <div class="sub-project-header d-flex align-item-center gap-3 mb-2">
         <div class="text-primary-500 text-lg font-bold">
            {{ $subproject->sub_project_name }}
         </div>
         <div class="action-controls d-flex align-items-center gap-3">
            <!-- <a data-bs-toggle="modal" data-bs-target="#edit_sub_project_modal"
               class="d-flex align-items-center text-sm text-decoration-none gap-1 cursor-pointer"
               onclick="setSubProjectData('{{ $subproject->id }}', `{{ $subproject->sub_project_name }}`)">
               <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                  viewBox="0 0 20 20" fill="none">
                  <path
                     d="M7.5 16.8759H3.75C3.58424 16.8759 3.42527 16.8101 3.30806 16.6928C3.19085 16.5756 3.125 16.4167 3.125 16.2509V12.7587C3.12472 12.6776 3.14044 12.5971 3.17128 12.5221C3.20211 12.447 3.24745 12.3787 3.30469 12.3212L12.6797 2.94622C12.7378 2.88717 12.8072 2.84027 12.8836 2.80826C12.9601 2.77625 13.0421 2.75977 13.125 2.75977C13.2079 2.75977 13.2899 2.77625 13.3664 2.80826C13.4428 2.84027 13.5122 2.88717 13.5703 2.94622L17.0547 6.4306C17.1137 6.48875 17.1606 6.55807 17.1927 6.63452C17.2247 6.71097 17.2411 6.79303 17.2411 6.87591C17.2411 6.95879 17.2247 7.04084 17.1927 7.11729C17.1606 7.19374 17.1137 7.26306 17.0547 7.32122L7.5 16.8759Z"
                     stroke="#004677" stroke-width="1.5" stroke-linecap="round"
                     stroke-linejoin="round" />
                  <path d="M16.875 16.875H7.5" stroke="#004677" stroke-width="1.5"
                     stroke-linecap="round" stroke-linejoin="round" />
                  <path d="M10.625 5L15 9.375" stroke="#004677" stroke-width="1.5"
                     stroke-linecap="round" stroke-linejoin="round" />
               </svg>
               <span class="text-primary-500 font-semibold">Edit</span>
            </a> -->
            <a data-bs-toggle="modal" data-bs-target="#delete_sub_project" wire:ignore.self
               class="d-flex align-items-center text-sm text-decoration-none gap-1 cursor-pointer"
               wire:click="confirmsubDelete({{ $subproject->id }})">
               <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                  viewBox="0 0 20 20" fill="none">
                  <path d="M16.875 4.375H3.125" stroke="#F04438" stroke-width="1.5"
                     stroke-linecap="round" stroke-linejoin="round" />
                  <path d="M8.125 8.125V13.125" stroke="#F04438" stroke-width="1.5"
                     stroke-linecap="round" stroke-linejoin="round" />
                  <path d="M11.875 8.125V13.125" stroke="#F04438" stroke-width="1.5"
                     stroke-linecap="round" stroke-linejoin="round" />
                  <path
                     d="M15.625 4.375V16.25C15.625 16.4158 15.5592 16.5747 15.4419 16.6919C15.3247 16.8092 15.1658 16.875 15 16.875H5C4.83424 16.875 4.67527 16.8092 4.55806 16.6919C4.44085 16.5747 4.375 16.4158 4.375 16.25V4.375"
                     stroke="#F04438" stroke-width="1.5" stroke-linecap="round"
                     stroke-linejoin="round" />
                  <path
                     d="M13.125 4.375V3.125C13.125 2.79348 12.9933 2.47554 12.7589 2.24112C12.5245 2.0067 12.2065 1.875 11.875 1.875H8.125C7.79348 1.875 7.47554 2.0067 7.24112 2.24112C7.0067 2.47554 6.875 2.79348 6.875 3.125V4.375"
                     stroke="#F04438" stroke-width="1.5" stroke-linecap="round"
                     stroke-linejoin="round" />
               </svg>
               <span class="text-error-500 font-semibold">Delete</span>
            </a>
         </div>
      </div>
      @foreach ($subproject->project_hierarchy as $key2 => $segment)
      <div class="table-responsive mb-2">
         <table width="100%"
            class="subproject-table collapsed"
            data-sub-project="{{ $subproject->id }}"
            id="collapse_in_{{ $key }}_{{ $key2 }}_{{ $segment->id }}_{{ $tabYear }}">
            @if ($segment->other == 1)
            <thead>
               <tr class="primary-header table-detail-acordian cursor-pointer"
                  data-id="collapse_in_{{ $key }}_{{ $key2 }}_{{ $segment->id }}_{{ $tabYear }}">
                  @if(!getLookupSyncStatus($subproject->id, $segment->id))
                  <div class="alert alert-warning text-xs p-1 m-1">
                     ⚠️ <small>Warning: Can't load in this section due to structure mismatch with Exact environment.</small>
                  </div>

                  @endif
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
                           €{{ dutchCurrency(calCulateprojectBudget($segment->id, $projectdetail->id, $tabYear, 'approval_budget', $subproject->id)) }}
                        </p>
                     </div>
                     <div class="total">
                        <p class="total-values">
                           €{{ dutchCurrency(calCulateprojectBudget($segment->id, $projectdetail->id, $tabYear, 'approval_budget', $subproject->id)) }}
                        </p>
                     </div>
                  </th>
                  <th style="min-width: 200px;width: 200px;">
                     <div class="details">
                        <span class="title">{{ $tillTodayTitle }}</span>
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
                        <span class="title">{{ $leftoverTitle }}</span>
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
                  <th class="text-end action-toolbar">
                     <a type="button" class="d-block table-detail-acordian">
                        <svg width="16" height="16" viewBox="0 0 16 16"
                           fill="none" xmlns="http://www.w3.org/2000/svg">
                           <path d="M13 6L8 11L3 6" stroke="#667085"
                              stroke-width="1.5" stroke-linecap="round"
                              stroke-linejoin="round" />
                        </svg>
                     </a>
                  </th>
               </tr>
               <tr class="secondary-header collapsable-header">
                  <th style="min-width: 58px;width:58px;text-align:center;">#</th>
                  <th class="editable" style="min-width: 195px;width:195px;">A. Total
                     Budget Headline
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
                  <th class="editable" style="min-width: 116px;width: 116px;">Unit Costs
                  </th>
                  <th style="min-width: 200px;width: 200px;">
                     {{ $yearStartDate }} - {{ $yearEndDate }}[€]
                  </th>
                  <th class="editable" style="min-width: 202px;width: 202px;">
                     @if ($isFuture)
                     The budget hasn't begun yet.
                     @else
                     {{ $fromMonth }}, {{ $tabYear }} -
                     {{ date('M') }},
                     {{ $tabYear }}
                     [€]
                     <span class="edit">
                        <img src="{{ asset('images/icons/table-cell-edit.svg') }}"
                           alt="img">
                     </span>
                     @endif
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
               @forelse($segment->projecthierarchdata as $key3 => $hierarchdata)
               <tr class="details-row">
                  <td style="text-align:center;">{{ $key3 + 1 }}
                  </td>
                  <td class="editable-td p-0 units-input-number">
                     @if ($segment->id == 4 || $segment->id == 5)
                     <input type="text"
                        class="table-input rows_{{ $key }}_{{ $key3 }}_{{ $tabYear }}_{{ $key2 }}_seprate_data_employee"
                        data-id="{{ $key }}_{{ $key3 }}_{{ $tabYear }}_{{ $key2 }}"
                        id="rows_{{ $key }}_{{ $key3 }}_{{ $tabYear }}_{{ $key2 }}_employee"
                        value="{{ $hierarchdata->employee_id }}" />
                     @else
                     <select
                        class="js-example-basic-single table-select employee_data create-donot-phone change_employee 
        rows_{{ $key }}_{{ $key3 }}_{{ $tabYear }}_{{ $key2 }}_seprate_data_employee w-100"
                        data-id="{{ $key }}_{{ $key3 }}_{{ $tabYear }}_{{ $key2 }}_seprate_data"
                        data-year="{{ $tabYear }}"
                        id="rows_{{ $key }}_{{ $key3 }}_{{ $tabYear }}_{{ $key2 }}_employee_seprate"
                        data-user-type-id="{{ $segment->id }}"
                        data-select22="rows_{{ $key }}_{{ $key3 }}_{{ $tabYear }}_{{ $key2 }}_employee_seprate"
                        data-placeholder="{{ $hierarchdata->employee_id ? 'Select Employee' : $hierarchdata->exact_wbs_description }}"
                        data-allow-clear="false" data-record-id="{{$hierarchdata->id}}">

                        @if (!$hierarchdata->employee_id)
                        <!-- Force WBS description to be preselected -->
                        <option value="" selected disabled>{{ $hierarchdata->exact_wbs_description }}</option>
                        @else
                        <option value="" disabled>Select Employee</option>
                        @endif

                        @foreach ($segment->donors as $donor)
                        <option value=" {{ $donor->id }}" {{ $donor->id == $hierarchdata->employee_id ? 'selected' : '' }}>
                           {{ $donor->name }}
                        </option>
                        @endforeach
                     </select>

                     <input type="hidden"
                        class="rows_{{ $key }}_{{ $key3 }}_{{ $tabYear }}_{{ $key2 }}_seprate_data_employee_input"
                        data-id="{{ $key }}_{{ $key3 }}_{{ $tabYear }}_{{ $key2 }}"
                        value="{{ $hierarchdata->employee_id }}" />


                     @endif
                     <span class="edit-pill">Edit</span>
                     <button type="submit" class="save-pill" data-year="{{ $tabYear }}"
                        data-id="{{ $key }}_{{ $key3 }}_{{ $tabYear }}_{{ $key2 }}_seprate_data"
                        data-sub-project-data-id="{{ $hierarchdata->id }}">Save</button>

                     <div id="rows_{{ $key }}_{{ $key3 }}_{{ $tabYear }}_{{ $key2 }}_employee_error"
                        class="text-error-500 project-validation-error rows_{{ $key }}_{{ $key3 }}_{{ $tabYear }}_{{ $key2 }}_employee_error">
                     </div>
                  </td>



                  <td class="editable-td p-0 units-input-number">
                     <select
                        class="js-example-basic-single  table-select rows_{{ $key }}_{{ $key3 }}_{{ $tabYear }}_{{ $key2 }}_seprate_data_note w-100"
                        data-minimum-results-for-search="Infinity"
                        data-id="{{ $key }}_{{ $key3 }}_{{ $tabYear }}_{{ $key2 }}_seprate_data"
                        id="rows_{{ $key }}_{{ $key3 }}_{{ $tabYear }}_{{ $key2 }}_note_seprate"
                        data-note="rows_{{ $key }}_{{ $key3 }}_{{ $tabYear }}_{{ $key2 }}_note_seprate">

                        <option value="per_day"
                           {{ 'per_day' == $hierarchdata->note ? 'selected' : '' }}>
                           Per day
                        </option>
                        <option value="per_night"
                           {{ 'per_night' == $hierarchdata->note ? 'selected' : '' }}>
                           Per night
                        </option>
                        <option value="per_month"
                           {{ 'per_month' == $hierarchdata->note ? 'selected' : '' }}>
                           Per month
                        </option>
                        <option value="per_page"
                           {{ 'per_page' == $hierarchdata->note ? 'selected' : '' }}>
                           Per page
                        </option>
                        <option value="per_year"
                           {{ 'per_year' == $hierarchdata->note ? 'selected' : '' }}>
                           Per year
                        </option>
                        <option value="per_partner"
                           {{ 'per_partner' == $hierarchdata->note ? 'selected' : '' }}>
                           Per partner
                        </option>
                        <option value="per_item"
                           {{ 'per_item' == $hierarchdata->note ? 'selected' : '' }}>
                           Per item
                        </option>
                        <option value="per_trip" {{ 'per_trip' == $hierarchdata->note ? 'selected' : '' }}>
                           Per trip
                        </option>
                        <option value="per_event"
                           {{ 'per_event' == $hierarchdata->note ? 'selected' : '' }}>
                           Per event
                        </option>

                     </select>
                     <span class="edit-pill">Edit</span>
                     <button type="submit" class="save-pill" data-year="{{ $tabYear }}"
                        data-id="{{ $key }}_{{ $key3 }}_{{ $tabYear }}_{{ $key2 }}_seprate_data"
                        data-sub-project-data-id="{{ $hierarchdata->id }}">save</button>
                  </td>
                  <td class="editable-td p-0 units-input-number">
                     <input type="text"
                        class="table-input project_unit rows_{{ $key }}_{{ $key3 }}_{{ $tabYear }}_{{ $key2 }}_seprate_data_unit"
                        data-id="{{ $key }}_{{ $key3 }}_{{ $tabYear }}_{{ $key2 }}_seprate_data"
                        placeholder="0"
                        value="{{ netherlandformatCurrency($hierarchdata->units) }}"
                        min="1" max="20000"
                        data-type='currency'
                        id="rows_{{ $key }}_{{ $key3 }}_{{ $tabYear }}_{{ $key2 }}_seprate_data_unit" />
                     <span class="edit-pill">Edit</span>
                     <button type="submit" class="save-pill" data-year="{{ $tabYear }}"
                        data-id="{{ $key }}_{{ $key3 }}_{{ $tabYear }}_{{ $key2 }}_seprate_data"
                        data-sub-project-data-id="{{ $hierarchdata->id }}">save</button>
                  </td>
                  @if ($segment->id == 4 || $segment->id == 5 || $segment->id == 6 || $segment->id == 3)
                  <td class="editable-td p-0 units-input-number">
                     <span class="currency-sign">€</span><input
                        type="text"
                        class="table-input project-expenses-input rows_{{ $key }}_{{ $key3 }}_{{ $tabYear }}_{{ $key2 }}_seprate_data_unitcost unit_cost"
                        placeholder="0" data-type="currency"
                        data-id="{{ $key }}_{{ $key3 }}_{{ $tabYear }}_{{ $key2 }}_seprate_data"
                        value="{{ netherlandformatCurrency($hierarchdata->unit_costs, 'blur') }}">
                     <span class="edit-pill">Edit</span>
                     <button type="submit" class="save-pill" data-year="{{ $tabYear }}"
                        data-id="{{ $key }}_{{ $key3 }}_{{ $tabYear }}_{{ $key2 }}_seprate_data"
                        data-sub-project-data-id="{{ $hierarchdata->id }}">save</button>
                  </td>
                  @else
                  <td>
                     €<span
                        class="rows_{{ $key }}_{{ $key3 }}_{{ $tabYear }}_{{ $key2 }}_seprate_data_unitcost">{{ netherlandformatCurrency($hierarchdata->unit_costs, 'blur') }}</span>
                  </td>
                  @endif
                  <td>€<span
                        class="rows_{{ $key }}_{{ $key3 }}_{{ $tabYear }}_{{ $key2 }}_seprate_data_approval_budget">{{ netherlandformatCurrency($hierarchdata->total_approval_budget, 'blur') }}</span>
                  </td>
                  <td class="editable-td p-0 units-input-number">
                     <span class="currency-sign">€</span><input type="text"
                        class="table-input project_expenses project-expenses-input rows_{{ $key }}_{{ $key3 }}_{{ $tabYear }}_{{ $key2 }}_seprate_data_expenses"
                        value="{{ netherlandformatCurrency($hierarchdata->actual_expenses_to_date, 'blur') }}"
                        data-id="{{ $key }}_{{ $key3 }}_{{ $tabYear }}_{{ $key2 }}_seprate_data"
                        placeholder="0" min="1" max="20000"
                        id="rows_{{ $key }}_{{ $key3 }}_{{ $tabYear }}_{{ $key2 }}_seprate_data_expenses"
                        data-type='currency' />
                     <span class="edit-pill">Edit</span>
                     <button type="submit" class="save-pill" data-year="{{ $tabYear }}"
                        data-id="{{ $key }}_{{ $key3 }}_{{ $tabYear }}_{{ $key2 }}_seprate_data"
                        data-sub-project-data-id="{{ $hierarchdata->id }}">save</button>
                     <div id="rows_{{ $key }}_{{ $key3 }}_{{ $tabYear }}_expenses"
                        class="text-error-500 project-validation-error rows_{{ $key }}_{{ $key3 }}_{{ $tabYear }}_{{ $key2 }}_seprate_data_project_expenses_error">
                     </div>
                  </td>
                  <td class="{{ ($hierarchdata->remaining_balance ?? 0) < 0 ? 'text-error-500' : '' }}">€<span
                        class="rows_{{ $key }}_{{ $key3 }}_{{ $tabYear }}_{{ $key2 }}_seprate_data_remaining_balance currencyMasking">{{ netherlandformatCurrency($hierarchdata->remaining_balance, 'blur') }}</span>
                  </td>
                  <td align="center" class="action-toolbar">
                     <a wire:click="confirmDelete({{ $hierarchdata->id }},{{ $segment->id }},{{ $subproject->id }},{{ calculateIndirectCost(calCulateprojectMatrix($projectdetail->id, $tabYear, 'approval_budget'), calCulateprojectBudget(null, $projectdetail->id, $tabYear, 'approval_budget')) == 0 ? '' : calculateIndirectCost(calCulateprojectMatrix($projectdetail->id, $tabYear, 'approval_budget'), calCulateprojectBudget(null, $projectdetail->id, $tabYear, 'approval_budget')) }})"
                        data-bs-toggle="modal"
                        data-sub-project="{{ $subproject->id }}"
                        data-delete-row-id="{{ $hierarchdata->id }}"
                        class="delete-row-btn delete_percentage"
                        href="#delete_item_project"
                        data-id="data-id-{{ $key2 }}-{{ $key }}-{{ $key3 }}">
                        <svg width="20" height="20"
                           viewBox="0 0 20 20" fill="none"
                           xmlns="http://www.w3.org/2000/svg">
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
                              stroke-linecap="round"
                              stroke-linejoin="round" />
                           <path
                              d="M13.125 4.375V3.125C13.125 2.79348 12.9933 2.47554 12.7589 2.24112C12.5245 2.0067 12.2065 1.875 11.875 1.875H8.125C7.79348 1.875 7.47554 2.0067 7.24112 2.24112C7.0067 2.47554 6.875 2.79348 6.875 3.125V4.375"
                              stroke="#F04438" stroke-width="1.5"
                              stroke-linecap="round"
                              stroke-linejoin="round" />
                        </svg>
                     </a>
                  </td>
               </tr>
               @empty
               <td colspan="9" align="left"
                  class="no-data collapse_in_{{ $key }}_{{ $key2 }}_{{ $segment->id }}_{{ $tabYear }}"
                  data-id="collapse_in_{{ $key }}_{{ $key2 }}_{{ $segment->id }}_{{ $tabYear }}">
                  <span class="text-xs font-semibold">You
                     don’t have
                     any data yet, click on
                     ”+
                     Add” button
                     to create new
                     data</span>
               </td>
               @endforelse
               <tr
                  class="tr_collapse_in_{{ $key }}_{{ $key2 }}_{{ $segment->id }}_{{ $tabYear }}">
                  <td colspan="9" align="left" class="add-employee">
                     <button class="btn add-employee add_employee_row"
                        data-project-id="{{ $projectdetail->id }}"
                        data-sub-project-id="{{ $subproject->id }}"
                        data-type-id="{{ $segment->id }}"
                        data-id="collapse_in_{{ $key }}_{{ $key2 }}_{{ $segment->id }}_{{ $tabYear }}"
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
                              class="total_approval_budget_indirect_cost_{{ $key }}_join">{{ dutchCurrency(calculateIndirectCostWithoutRevision(calCulateprojectBudget(null, $projectdetail->id, $tabYear, 'approval_budget'), $projectdetail->id, $tabYear, $subproject->id)) }}</span>
                        </p>
                     </div>
                  </th>
                  <th style="min-width: 200px;width: 200px;">
                     <div class="details">
                        <span class="title">{{ $tillTodayTitle }}</span>
                        <p class="values">
                           €<span
                              class="total_actual_expenses_indirect_cost_{{ $key }}_join">
                              {{ dutchCurrency(calculateIndirectCostWithoutRevision(calCulateprojectBudget(null, $projectdetail->id, $tabYear, 'actual_expenses'), $projectdetail->id, $tabYear, $subproject->id)) }}
                           </span>
                        </p>
                     </div>
                  </th>
                  <th style="min-width: 200px;width: 200px;">
                     <div class="details">
                        <span class="title">{{ $leftoverTitle }}</span>
                        <p class="values {{ (calculateIndirectCostWithoutRevision(calCulateprojectBudget(null, $projectdetail->id, $tabYear, 'remaining_balance'), $projectdetail->id, $tabYear, $subproject->id) ?? 0) < 0 ? 'text-error-500' : '' }}">
                           €<span
                              class="total_remaining_balance_indirect_cost_{{ $key }}_join currencyMasking">
                              {{ dutchCurrency(calculateIndirectCostWithoutRevision(calCulateprojectBudget(null, $projectdetail->id, $tabYear, 'remaining_balance'), $projectdetail->id, $tabYear, $subproject->id)) }}
                           </span>
                        </p>
                     </div>
                  </th>
                  <th class="action-toolbar">
                     <div></div>
                  </th>
               </tr>
               <tr class="secondary-header collapsable-header indirect-costs">
                  <th style="min-width: 58px;width:58px;text-align:center;">#</th>
                  <th style="min-width: 195px;width:195px;">A. Total Budget Headline
                  </th>
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
                     {{ $tabYear }}
                     [€]
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
                     <span></span>
                     @if (indirectcost($subproject->id, $subproject->project_id, $tabYear))
                     <input type="text" class="table-input indirect_cost"
                        data-sub-project="{{ $subproject->id }}"
                        data-indirect-percentage-year="{{ $tabYear }}{{ $subproject->id }}"
                        data-project-id="{{ $subproject->project_id }}"
                        data-id="{{ $tabYear }}{{ $key }}_join"
                        placeholder="20.15%" min="1" max="99"
                        value="{{ getPercentage($projectdetail->id, $tabYear, $subproject->id)}}"
                        onkeypress="return validateFloatKeyPress(this,event);" />
                     <span class="edit-pill">Edit</span>
                     <button type="submit" class="save-pill-indirect-cost"
                        data-sub-project="{{ $subproject->id }}"
                        data-indirect-percentage-year="{{ $tabYear }}"
                        data-project-id="{{ $subproject->project_id }}"
                        data-id="{{ $tabYear }}{{ $key }}_join"
                        data-year="{{ $tabYear }}"
                        value="{{ getPercentage($projectdetail->id, $tabYear, $subproject->id) }}">save</button>
                     @else
                     <div class="pointer-events">
                        <input type="number" class="table-input" disabled />
                     </div>
                     @endif
                  </td>
                  <td>€<span
                        class="total_approval_budget_indirect_cost_{{ $tabYear }}{{ $key }}_join">
                        {{ dutchCurrency(calculateIndirectCostWithoutRevision(calCulateprojectBudget(null, $projectdetail->id, $tabYear, 'approval_budget', $subproject->id), $projectdetail->id, $tabYear, $subproject->id)) }}
                     </span>
                  </td>
                  <td>€<span
                        class="total_actual_expenses_indirect_cost_{{ $tabYear }}{{ $key }}_join">
                        {{ dutchCurrency(calculateIndirectCostWithoutRevision(calCulateprojectBudget(null, $projectdetail->id, $tabYear, 'actual_expenses', $subproject->id), $projectdetail->id, $tabYear, $subproject->id)) }}
                     </span>
                  </td>
                  <td>
                     €<span
                        class="total_remaining_balance_indirect_cost_{{ $tabYear }}{{ $key }}_join">
                        {{ dutchCurrency(calculateIndirectCostWithoutRevision(calCulateprojectBudget(null, $projectdetail->id, $tabYear, 'remaining_balance', $subproject->id), $projectdetail->id, $tabYear, $subproject->id)) }}
                     </span>
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
                        class="total_estimate_approval_budget_{{ $tabYear }}{{ $key }}_join">
                        {{ dutchCurrency(calculateTotalEstimateCost($projectdetail->id, $tabYear, 'approval_budget', $subproject->id)) }}
                     </span>
                  </td>
                  <td>€<span
                        class="total_estimate_actual_expenses_{{ $tabYear }}{{ $key }}_join">
                        {{ dutchCurrency(calculateTotalEstimateCost($projectdetail->id, $tabYear, 'actual_expenses', $subproject->id)) }}
                     </span>
                  </td>
                  <td>€<span
                        class="total_estimate_remaining_balance_{{ $tabYear }}{{ $key }}_join {{ (calculateTotalEstimateCost($projectdetail->id, $tabYear, 'remaining_balance', $subproject->id) ?? 0) < 0 ? 'text-error-500' : '' }}">
                        {{ dutchCurrency(calculateTotalEstimateCost($projectdetail->id, $tabYear, 'remaining_balance', $subproject->id)) }}</span>
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
                           class="total_approval_budget_{{ $tabYear }}{{ $key }}_join">{{ dutchCurrency(calCulateprojectBudget(null, $projectdetail->id, $tabYear, 'approval_budget', $subproject->id)) }}</span>
                     </div>
                  </th>
                  <th style="min-width: 200px;width: 200px;">
                     <div>
                        €<span
                           class="total_actual_expenses_{{ $tabYear }}{{ $key }}_join">{{ dutchCurrency(calCulateprojectBudget(null, $projectdetail->id, $tabYear, 'actual_expenses', $subproject->id)) }}</span>
                     </div>
                  </th>
                  <th style="min-width: 200px;width: 200px;">
                     <div>
                        €<span
                           class="total_remaining_balance_{{ $tabYear }}{{ $key }}_join {{ (calCulateprojectBudget(null, $projectdetail->id, $tabYear, 'remaining_balance', $subproject->id) ?? 0) < 0 ? 'text-error-500' : '' }}">{{ dutchCurrency(calCulateprojectBudget(null, $projectdetail->id, $tabYear, 'remaining_balance', $subproject->id)) }}</span>
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
   </div>
   @endforeach
</div>
@php
$projectYears = getYearList();
@endphp
<div>
    <div class="page-header">
        <span>@include('elements.breadcrumb', ['breadcrumbs' => Breadcrumb()])</span>
        <div class="d-flex flex-wrap align-items-center gap-12 mb-4">
            <h6 class="h6 font-medium text-gray-800 mb-0 order-1 order-sm-1 me-auto">Indirect Costs Budget</h6>
            <div class="ms-sm-auto order-3 order-sm-2">
                <form action="theme-form" class="position-relative">
                    <!-- Wrapper for the dropdown and icon -->
                    <div class="ms-sm-auto order-3 order-sm-2">
                        <form action="theme-form" class="position-relative">
                            <div class="">
                                <select type="text" class="year-filter-select js-example-basic-single form-control indirectJs"
                                    data-minimum-results-for-search="Infinity"
                                    onchange="switchYear('indirectJs')">
                                    @foreach ($projectYears as $projectYear)
                                    <option value="{{ $projectYear }}" {{ ($currentYear == $projectYear) ? 'selected' : '' }}>
                                        {{ $projectYear }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </form>
                    </div>
                </form>
            </div>
        </div>
        <nav>
            <ul class="nav nav-tabs tabbing-outlined" id="indirectCostBudgetTabs" role="tablist">
                <li class="nav-item" role="presentation" wire:ignore:self>
                    <button class="nav-link {{ $activeTab === 'all' ? ' active' : '' }}" id="all" wire:ignore:self
                        data-bs-toggle="tab" data-bs-target="#nav-all" type="button" role="tab"
                        aria-controls="all" aria-selected="true" onclick="updateTab('all'), allTabData({{$currentYear}}, switchTab('all'))">All</button>
                </li>
                @foreach ($categories as $category)
                <li class="nav-item" role="presentation" wire:ignore:self>
                    <button class="nav-link {{ $activeTab === 'tab' . $category->id ? 'active show' : '' }}" wire:ignore:self
                        id="category-{{ $category->id }}" data-bs-toggle="tab"
                        data-bs-target="#nav-category-{{ $category->id }}" type="button" role="tab"
                        aria-controls="category-{{ $category->id }}" aria-selected="false"
                        onclick="updateTab('tab{{ $category->id }}'), switchTab('tab{{ $category->id }}')">{{ $category->name }}</button>
                </li>
                @endforeach
            </ul>
        </nav>
    </div>
    <div class="content">
        <div class="tab-content" id="nav-indirectCostBudget-tabContent" wire:ignore:self>
            <div class="tab-pane fade {{ $activeTab === 'all' ? 'show active' : '' }} " id="nav-all" role="tabpanel" aria-labelledby="all-tab">
                <div id="all-tab-detail">
                    @include('components.indirect-other-cost.all-tab',['allTabEmployees'=>$allTabEmployees])
                </div>
            </div>
            @foreach ($categories as $category)
            <div class="tab-pane fade {{ $activeTab === 'tab' . $category->id ? 'show active' : '' }}"
                id="nav-category-{{ $category->id }}" role="tabpanel"
                aria-labelledby="category-{{ $category->id }}-tab">
                <div class="table-responsive mb-3">
                    <table width="100%" class="detailing-table">
                        <thead>
                            <tr class="primary-header">
                                <th colspan="4">SALARIES AND FRINGE BENEFITS</th>
                                <th style="min-width:120px;width:120px;">
                                    <span class="headings">
                                        Costs
                                    </span>
                                </th>
                                <th style="min-width:120px;width:120px;">
                                    <div class="details">
                                        <span class="title">Current Year Budget</span>
                                        <p class="values IeTotalApprovalJs{{ $category->id }}" style="word-break:break-all;">
                                            €{{ dutchCurrency(calculateSumIEByCat('total_approved_cost', $category->id, $currentYear)) }}
                                        </p>
                                    </div>
                                </th>
                                <th style="min-width:120px;width:120px;">
                                    <div class="details">
                                        <span class="title">Current Year Expenses</span>
                                        <p class="values  IeTotalExpense{{ $category->id }}" style="word-break:break-all;">
                                            €{{ dutchCurrency(calculateSumIEByCat('actual_cost_till_date', $category->id, $currentYear)) }}
                                        </p>
                                    </div>
                                </th>
                                <th style="min-width:120px;width:120px;"></th>
                            </tr>
                            <tr class="secondary-header">
                                <th class="text-center" style="width: 40px; min-width: 40px;">#</th>
                                <th style="width: 332px;min-width: 332px;">A. Total Personnel</th>
                                <th style="min-width:140px;width:140px;" class="editable">Notes
                                    <span class="edit">
                                        <img src="{{ asset('images/icons/table-cell-edit.svg') }}" alt="img">
                                    </span>
                                </th>
                                <th style="min-width:120px;width:120px;" class="editable">Units
                                    <span class="edit">
                                        <img src="{{ asset('images/icons/table-cell-edit.svg') }}"
                                            alt="img">
                                    </span>
                                </th>
                                <th style="min-width:120px;width:120px;">Unit Costs</th>
                                <th style="min-width:120px;width:120px;">
                                    Jan, {{ $currentYear }} - Dec, {{ $currentYear }} [€]
                                </th>
                                <th style="min-width:120px;width:120px;" class="editable">
                                    Jan, {{ $currentYear }} - {{ date('M') }}, {{ $currentYear }} [€]
                                    <span class="edit">
                                        <img src="{{ asset('images/icons/table-cell-edit.svg') }}"
                                            alt="img">
                                    </span>
                                </th>
                                <th style="min-width:120px;width:120px;">EUR</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($employees as $employee)
                            @php
                            $ieCalculations = $employee->indirectExpensesCalculations->where('indirect_expense_category_id', $category->id)->where('year', $currentYear)->first();
                            $actualCost = $ieCalculations->actual_cost_till_date ?? 0;
                            $units = $ieCalculations->units ?? 0;
                            @endphp
                            <tr class="details-row" wire:ignore:self>
                                <td class="text-center">{{ $loop->index + 1 }}</td>
                                <td>{{ $employee->name }}</td>
                                <td class="editable-td p-0 units-input-number">
                                    <input class="employeeId" type="hidden" value="{{ $employee->id }}">
                                    <input class="categoryId" type="hidden" value="{{ $category->id }}">
                                    <input class="unitCost" type="hidden" value="{{ calculateAverageDailyRate($employee->id, $employee->userprofile->rate) }}">
                                    <input class="IeUnitCost{{ $employee->id }}{{ $category->id }}"
                                        type="hidden" value="{{ calculateAverageDailyRate($employee->id, $employee->userprofile->rate) }}">

                                    <select class="js-example-basic-single table-select w-100 IeNotes{{ $employee->id }}{{ $category->id }}"
                                        data-minimum-results-for-search="Infinity"
                                        data-input="employee-notes">
                                        <option value="per_day"
                                            {{ $ieCalculations && $ieCalculations->notes == 'per_day' ? 'selected' : '' }}>
                                            Per day</option>
                                        <option value="per_night"
                                            {{ $ieCalculations && $ieCalculations->notes == 'per_night' ? 'selected' : '' }}>
                                            Per night</option>
                                        <option value="per_month"
                                            {{ $ieCalculations && $ieCalculations->notes == 'per_month' ? 'selected' : '' }}>
                                            Per month</option>
                                        <option value="per_year"
                                            {{ $ieCalculations && $ieCalculations->notes == 'per_year' ? 'selected' : '' }}>
                                            Per year</option>
                                    </select>

                                    <span class="edit-pill">Edit</span>
                                    <button type="button" class="save-table-btn save" onclick="saveDetail('indirect', 'notes', '{{ $employee->id }}', '{{ $category->id }}', 'IeNotes{{ $employee->id }}{{ $category->id }}', '{{ calculateAverageDailyRate($employee->id, $employee->userprofile->rate) }}')">save</button>
                                </td>
                                <td class="editable-td p-0 units-input-number">
                                    <input type="text"
                                        class="table-input IeUnit{{ $employee->id }}{{ $category->id }}"
                                        placeholder="0" value="{{ ($units>0)?$units:'' }}"
                                        min="1" max="20000"
                                        onkeyup="calculatePrice('IeUnit{{ $employee->id }}{{ $category->id }}','IeUnitCost{{ $employee->id }}{{ $category->id }}','IeApprovedBudget{{ $employee->id }}{{ $category->id }}','IeActualExpense{{ $employee->id }}{{ $category->id }}', 'IeRemainingExpense{{ $employee->id }}{{ $category->id }}', {{ $category->id }})"
                                        data-id="{{ $employee->id }}"
                                        onkeydown="updateIndirectExpensesUnitsViaEnter(event)"
                                        data-category="{{ $category->id }}" , data-rate="{{ calculateAverageDailyRate($employee->id, $employee->userprofile->rate) }}" />
                                    <span class="edit-pill">Edit</span>
                                    <button type="button" class="save-table-btn updateUnitJs" data-category="{{ $category->id }}" , data-rate="{{ calculateAverageDailyRate($employee->id, $employee->userprofile->rate) }}">save</button>

                                </td>
                                <td style="word-break:break-all;"> €{{ dutchCurrency(calculateAverageDailyRate($employee->id, $employee->userprofile->rate)) }} </td>
                                <td style="word-break:break-all;" class="IeApprovedBudget{{ $employee->id }}{{ $category->id }} IeApprovedBudget{{ $category->id }}">
                                    €{{ netherlandformatCurrency($ieCalculations->total_approved_cost ?? 0, 'blur') }}
                                </td>
                                <td class="editable-td p-0 units-input-number">
                                    <span class="currency-sign">€</span>
                                    <input type="text"
                                        class="table-input IeActualExpense{{ $employee->id }}{{ $category->id }} project-expenses-input IeActualExpenseJs{{ $category->id }}"
                                        placeholder="0" min="1" max="20000"
                                        value="{{ ($actualCost>0) ? netherlandformatCurrency($ieCalculations->actual_cost_till_date) : '' }}"
                                        onkeyup="calculatePrice('IeUnit{{ $employee->id }}{{ $category->id }}','IeUnitCost{{ $employee->id }}{{ $category->id }}','IeApprovedBudget{{ $employee->id }}{{ $category->id }}','IeActualExpense{{ $employee->id }}{{ $category->id }}', 'IeRemainingExpense{{ $employee->id }}{{ $category->id }}', {{ $category->id }})"
                                        data-id="{{ $employee->id }}"
                                        onkeydown="updateIndirectExpenseCurrentYearCost(event)"
                                        data-type="currency"
                                        data-category="{{ $category->id }}" , data-rate="{{ calculateAverageDailyRate($employee->id, $employee->userprofile->rate) }}" />
                                    <span class="edit-pill">Edit</span>

                                    <button type="button" class="save-table-btn updateYearExpensesJs" data-category="{{ $category->id }}" , data-rate="{{ calculateAverageDailyRate($employee->id, $employee->userprofile->rate) }}">save</button>
                                </td>
                                <td style="word-break:break-all;" class="IeRemainingExpense{{ $employee->id }}{{ $category->id }} {{ ($ieCalculations->remaining_cost ?? 0) < 0 ? 'text-error-500' : '' }}">
                                    €{{ netherlandformatCurrency($ieCalculations->remaining_cost ?? 0, 'blur') }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="no-records-td">
                                    <div class="no-records">
                                        <span class="text-gray-50 text-md font-regular">No records
                                            found</span>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="table-responsive">
                    <table width="100%" class="detailing-table">
                        <thead>
                            <tr class="primary-header">
                                <th colspan="4">OTHER DIRECT EXPENSES</th>
                                <th style="min-width:120px;width:120px;">
                                    <span class="headings">
                                        Costs
                                    </span>
                                </th>
                                <th style="min-width:120px;width:120px;">
                                    <div class="details">
                                        <span class="title">Current Year Budget</span>
                                        <p class="values odTotalApprovalCost{{ $category->id }}" style="word-break:break-all;">
                                            €{{ dutchCurrency(calculateSumODE('total_approved_cost', $category->id, $currentYear)) }}
                                        </p>
                                    </div>
                                </th>
                                <th style="min-width:120px;width:120px;">
                                    <div class="details">
                                        <span class="title">Current Year Expenses</span>
                                        <p class="values odCostTillNow{{ $category->id }}" style="word-break:break-all;">
                                            €{{ dutchCurrency(calculateSumODE('actual_cost_till_date', $category->id, $currentYear)) }}
                                        </p>
                                    </div>
                                </th>
                                <th style="min-width:120px;width:120px;"></th>
                            </tr>
                            <tr class="secondary-header">
                                <th class="text-center" style="width: 40px; min-width: 40px;">#</th>
                                <th style="width: 332px;min-width: 332px;">A. Total Personnel</th>
                                <th style="min-width:140px;width:140px;" class="editable">Notes
                                    <span class="edit">
                                        <img src="{{ asset('images/icons/table-cell-edit.svg') }}" alt="img">
                                    </span>
                                </th>
                                <th style="min-width:120px;width:120px;" class="editable">Units
                                    <span class="edit">
                                        <img src="{{ asset('images/icons/table-cell-edit.svg') }}"
                                            alt="img">
                                    </span>
                                </th>
                                <th style="min-width:120px;width:120px;" class="editable">Unit Costs
                                    <span class="edit">
                                        <img src="{{ asset('images/icons/table-cell-edit.svg') }}"
                                            alt="img">
                                    </span>
                                </th>
                                <th style="min-width:120px;width:120px;word-break: break-all;">
                                    Jan, {{ $currentYear }} - Dec, {{ $currentYear }} [€]
                                </th>
                                <th style="min-width:120px;width:120px;" class="editable">
                                    Jan, {{ $currentYear }} - {{ date('M') }}, {{ $currentYear }} [€]
                                    <span class="edit">
                                        <img src="{{ asset('images/icons/table-cell-edit.svg') }}"
                                            alt="img">
                                    </span>
                                </th>
                                <th style="min-width:120px;width:120px;">EUR</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($otherDirectExpenses as $otherDirectExpense)
                            <tr class="details-row" wire:ignore:self>
                                @php
                                $calculation = getOtherDirectByCategory($otherDirectExpense->id, $category->id, $currentYear);
                                $units = $calculation->units ?? 0;
                                $unitCost = $calculation->cost_per_unit ?? 0;
                                $actualExpense = $calculation->actual_cost_till_date ?? 0;
                                @endphp
                                <td class="text-center">{{ $loop->index + 1 }}</td>
                                <td>{{ $otherDirectExpense->name }}</td>
                                <td class="editable-td p-0 units-input-number">
                                    <select class="js-example-basic-single table-select w-100 OdNotes{{ $employee->id }}{{ $category->id }}"
                                        data-minimum-results-for-search="Infinity" data-input="notes">
                                        <option value="per_day"
                                            {{ $calculation && $calculation->notes == 'per_day' ? 'selected' : '' }}>
                                            Per day</option>
                                        <option value="per_night"
                                            {{ $calculation && $calculation->notes == 'per_night' ? 'selected' : '' }}>
                                            Per night</option>
                                        <option value="per_month"
                                            {{ $calculation && $calculation->notes == 'per_month' ? 'selected' : '' }}>
                                            Per month</option>
                                        <option value="per_year"
                                            {{ $calculation && $calculation->notes == 'per_year' ? 'selected' : '' }}>
                                            Per year</option>
                                    </select>
                                    <input class="expenseId" type="hidden" value="{{ $otherDirectExpense->id }}">
                                    <input class="categoryId" type="hidden" value="{{ $category->id }}">
                                    <span class="edit-pill">Edit</span>
                                    <button type="button" class="save-table-btn" onclick="saveDetail('otherDirect', 'notes', '{{ $otherDirectExpense->id }}', '{{ $category->id }}', 'OdNotes{{ $employee->id }}{{ $category->id }}', 0)">save</button>
                                </td>
                                <td class="editable-td p-0 units-input-number">
                                    <input type="text" class="table-input OdUnit{{ $otherDirectExpense->id.$category->id }}"
                                        placeholder="0" value="{{ ($units==0 ? '': $units) }}" min="1"
                                        max="20000"
                                        onkeyup="calculatePrice('OdUnit{{ $otherDirectExpense->id.$category->id }}','OdUnitCost{{ $otherDirectExpense->id.$category->id }}','OdApprovedBudget{{ $otherDirectExpense->id.$category->id }}','OdActualExpense{{ $otherDirectExpense->id.$category->id }}', 'OdRemainingExpense{{ $otherDirectExpense->id.$category->id }}', '{{ $category->id }}')"
                                        data-id="{{ $otherDirectExpense->id }}"
                                        onkeydown="updateIndirectExpensesUnitsViaEnter(event)"
                                        data-category="{{ $category->id }}" data-expense="{{ $otherDirectExpense->id }}" />
                                    <span class="edit-pill">Edit</span>
                                    <button type="button" class="save-table-btn" onclick="saveDetail('otherDirect', 'units', '{{ $otherDirectExpense->id }}', '{{ $category->id }}', 'OdUnit{{ $otherDirectExpense->id.$category->id }}')">save</button>
                                </td>
                                <td class="editable-td p-0 units-input-number">
                                    <span class="currency-sign">€</span>
                                    <input type="text"
                                        class="table-input OdUnitCost{{ $otherDirectExpense->id.$category->id }} project-expenses-input"
                                        placeholder="0"
                                        value="{{ ($unitCost>0) ? netherlandformatCurrency($unitCost, 'blur') : ''}}"
                                        min="1" max="20000"
                                        onkeydown="updateIndirectExpenseUnitCost(event)"
                                        data-category="{{ $category->id }}"
                                        data-expense="{{ $otherDirectExpense->id }}"
                                        onkeyup="calculatePrice('OdUnit{{ $otherDirectExpense->id.$category->id }}','OdUnitCost{{ $otherDirectExpense->id.$category->id }}','OdApprovedBudget{{ $otherDirectExpense->id.$category->id }}','OdActualExpense{{ $otherDirectExpense->id.$category->id }}', 'OdRemainingExpense{{ $otherDirectExpense->id.$category->id }}', '{{ $category->id }}')" data-type="currency" />
                                    <span class="edit-pill">Edit</span>
                                    <button type="button" class="save-table-btn" onclick="saveDetail('otherDirect', 'cost_per_unit', '{{ $otherDirectExpense->id }}', '{{ $category->id }}', 'OdUnitCost{{ $otherDirectExpense->id.$category->id }}')">save</button>
                                </td>
                                <td style="word-break: break-all;" class="OdApprovedBudget{{ $otherDirectExpense->id.$category->id  }} OdApprovalBudgetJs{{ $category->id }}">
                                    €{{ netherlandformatCurrency($calculation->total_approved_cost ?? 0, 'blur') }}
                                </td>
                                <td class="editable-td p-0 units-input-number">
                                    <span class="currency-sign">€</span>
                                    <input type="text"
                                        class="table-input OdActualExpense{{ $otherDirectExpense->id.$category->id  }} project-expenses-input OdExpenseJs{{ $category->id }}"
                                        placeholder="0" min="1" max="20000"
                                        value="{{ ($actualExpense>0) ? netherlandformatCurrency($actualExpense, 'blur') : '' }}"
                                        onkeyup="calculatePrice('OdUnit{{ $otherDirectExpense->id.$category->id  }}','OdUnitCost{{ $otherDirectExpense->id.$category->id  }}','OdApprovedBudget{{ $otherDirectExpense->id.$category->id  }}','OdActualExpense{{ $otherDirectExpense->id.$category->id  }}', 'OdRemainingExpense{{ $otherDirectExpense->id.$category->id  }}', '{{ $category->id }}')"
                                        data-id="{{ $otherDirectExpense->id }}" onkeydown="updateIndirectExpenseCurrentYearCost(event)"
                                        data-expense="{{ $otherDirectExpense->id }}"
                                        data-category="{{ $category->id }}" data-type="currency" />
                                    <span class="edit-pill">Edit</span>
                                    <button type="button" class="save-table-btn" onclick="saveDetail('otherDirect', 'actual_cost_till_date', '{{ $otherDirectExpense->id }}', '{{ $category->id }}', 'OdActualExpense{{ $otherDirectExpense->id.$category->id }}')">save</button>
                                </td>
                                <td style="word-break: break-all;" class="OdRemainingExpense{{ $otherDirectExpense->id.$category->id  }} {{ ($otherDirectExpense->remaining_cost ?? 0) < 0 ? 'text-error-500' : '' }}">
                                    €{{ netherlandformatCurrency($calculation->remaining_cost ?? 0, 'blur') }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="no-records-td">
                                    <div class="no-records">
                                        <span class="text-gray-50 text-md font-regular">No records
                                            found</span>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                            <tr class="estimated-row">
                                <td colspan="5">TOTAL INDIRECT COSTS BUDGET</td>
                                <td style="min-width:120px;width:120px;word-break: break-all;" class="indirectApprovedTotalJs{{ $category->id }}">€{{ dutchCurrency(calculateSumODE('total_approved_cost', $category->id, $currentYear) + calculateSumIEByCat('total_approved_cost', $category->id, $currentYear)) }}</td>
                                <td style="min-width:120px;width:120px;" class="projected-budget-cell"></td>
                                <td style="min-width:120px;width:120px;" class="balance-cell"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
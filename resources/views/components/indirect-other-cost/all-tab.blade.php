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
                        <p class="values" style="word-break:break-all;">
                            €{{ dutchCurrency(calculateSumIEByAllCat('total_approved_cost',  $currentYear)) }}
                        </p>
                    </div>
                </th>
                <th style="min-width:120px;width:120px;">
                    <div class="details">
                        <span class="title">Current Year Expenses</span>
                        <p class="values" style="word-break:break-all;">
                            €{{ dutchCurrency(calculateSumIEByAllCat('actual_cost_till_date', $currentYear)) }}
                        </p>
                    </div>
                </th>
                <th style="min-width:120px;width:120px;"></th>
            </tr>
            <tr class="secondary-header">
                <th class="text-center" style="width: 40px;min-width:40px;">#</th>
                <th style="width: 332px; min-width: 332px;">A. Total Personnel</th>
                <th style="min-width:140px;width:140px;">Notes</th>
                <th style="min-width:120px;width:120px;" class="editable">Units</th>
                <th style="min-width:120px;width:120px;">Unit Costs</th>
                <th style="min-width:120px;width:120px;">
                    Jan, {{ $currentYear }} - Dec, {{ $currentYear }} [€]
                </th>
                <th style="min-width:120px;width:120px;">
                    Jan, {{ $currentYear }} - {{ date('M') }}, {{ $currentYear }} [€]
                </th>
                <th style="min-width:120px;width:120px;">EUR</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($allTabEmployees as $employee)
            <tr class="details-row">
                <td class="text-center">{{ $loop->index + 1 }}</td>
                <td>{{ $employee->name }}</td>
                <td>Per day</td>
                <td style="word-break:break-all;">{{ $employee->units }}</td>
                <td style="word-break:break-all;">€{{ $employee->unit_cost }}</td>
                <td style="word-break:break-all;">€{{ $employee->total_approved_cost }}</td>
                <td style="word-break:break-all;">€{{ $employee->actual_cost_till_date }}</td>
                <td style="word-break:break-all;" class="{{ ($employee->remaining_cost ?? 0) < 0 ? 'text-error-500' : '' }}">€{{ $employee->remaining_cost }}</td>
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
                        <p class="values" style="word-break:break-all;">
                            €{{ dutchCurrency(calculateSumODByAllCat('total_approved_cost', $currentYear)) }}
                        </p>
                    </div>
                </th>
                <th style="min-width:120px;width:120px;">
                    <div class="details">
                        <span class="title">Current Year Expenses</span>
                        <p class="values" style="word-break:break-all;">
                            €{{ dutchCurrency(calculateSumODByAllCat('actual_cost_till_date', $currentYear)) }}
                        </p>
                    </div>
                </th>
                <th style="min-width:120px;width:120px;"></th>
            </tr>
            <tr class="secondary-header">
                <th class="text-center" style="width: 40px;min-width:40px;">#</th>
                <th style="width: 332px; min-width: 332px;">A. Total Personnel</th>
                <th style="min-width:140px;width:140px;">Notes</th>
                <th style="min-width:120px;width:120px;" class="editable">Units</th>
                <th style="min-width:120px;width:120px;">Unit Costs</th>
                <th style="min-width:120px;width:120px;">
                    Jan, {{ $currentYear }} - Dec, {{ $currentYear }} [€]
                </th>
                <th style="min-width:120px;width:120px;">
                    Jan, {{ $currentYear }} - {{ date('M') }}, {{ $currentYear }} [€]
                </th>
                <th style="min-width:120px;width:120px;">EUR</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($allOtherDirectExpenses as $otherDirect)
            <tr class="details-row">
                <td class="text-center">{{ $loop->index + 1 }}</td>
                <td>{{ $otherDirect->name }}</td>
                <td>Per day</td>
                <td style="word-break:break-all;">{{ $otherDirect->units }}</td>
                <td style="word-break:break-all;">€{{ $otherDirect->cost_per_unit }}</td>
                <td style="word-break:break-all;">€{{ $otherDirect->total_approved_cost }}</td>
                <td style="word-break:break-all;">€{{ $otherDirect->actual_cost_till_date }}</td>
                <td style="word-break:break-all;" class="{{ ($otherDirect->remaining_cost ?? 0) < 0 ? 'text-error-500' : '' }}">€{{ $otherDirect->remaining_cost }}</td>
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
                <td style="min-width:120px;width:120px;word-break: break-all;">€{{ dutchCurrency(calculateSumODByAllCat('total_approved_cost', $currentYear) + calculateSumIEByAllCat('total_approved_cost', $currentYear)) }}</td>
                <td style="min-width:120px;width:120px;" class="projected-budget-cell"></td>
                <td style="min-width:120px;width:120px;" class="balance-cell"></td>
            </tr>
        </tbody>
    </table>
</div>
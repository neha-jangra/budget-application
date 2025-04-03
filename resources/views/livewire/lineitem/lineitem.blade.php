<div>
    <div class="page-header">
        @include('elements.breadcrumb', ['breadcrumbs' => Breadcrumb()])
        <h6 class="h6 font-medium text-gray-800 mb-20">Line Items</h6>
    </div>
    <div class="content">
        <nav>
            <div class="main-tabbings nav nav-tabs" id="nav-tab" role="tablist">
                <button type="button" wire:click="switchTab('tab1')"
                    class="nav-link {{ $activeTab === 'tab1' ? 'active' : '' }}" id="consultants" data-bs-toggle="tab"
                    data-bs-target="#nav-consultants" role="tab" aria-controls="nav-consultants"
                    aria-selected="true" onclick="updateTab('tab1')">
                    Consultants</button>
                <button class="nav-link {{ $activeTab === 'tab2' ? 'active' : '' }}" wire:click="switchTab('tab2')"
                    id="sub-grantees" data-bs-toggle="tab" data-bs-target="#nav-sub-grantees" type="button"
                    role="tab" aria-controls="nav-sub-grantees" aria-selected="true"
                    onclick="updateTab('tab2')">Sub-Grantees</button>
                <button class="nav-link {{ $activeTab === 'tab3' ? 'active' : '' }}" wire:click="switchTab('tab3')"
                    id="employees" data-bs-toggle="tab" data-bs-target="#nav-employees" type="button" role="tab"
                    aria-controls="nav-employees" aria-selected="true" onclick="updateTab('tab3')">Employees</button>
                <button class="nav-link {{ $activeTab === 'tab4' ? 'active' : '' }}" wire:click="switchTab('tab4')"
                    id="other-direct-expenses" data-bs-toggle="tab" data-bs-target="#nav-other-indirect-expenses"
                    type="button" role="tab" aria-controls="nav-employees" aria-selected="true"
                    onclick="updateTab('tab4')">Other Direct
                    Expenses</button>
            </div>
        </nav>
        <div class="tab-content" id="nav-tabContent" wire:init="getlineitem">
            @if ($isLoading)
                <livewire:loader />
            @else
                <div class="tab-pane fade {{ $activeTab === 'tab1' ? 'show active' : '' }}" id="nav-consultants"
                    role="tabpanel" aria-labelledby="consultants">
                    <div class="content withSideSpacing">
                        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                            <div>
                                @include('elements.search-bar', [
                                    'placeholder' => 'Search consultants..',
                                    'model' => 0,
                                ])
                            </div>
                            <a href="{{ route('consultant.create') }}" class="btn btn-primary theme-btn">
                                <svg class="me-2" width="20" height="20" viewBox="0 0 20 20" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M3.125 10H16.875" stroke="white" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M10 3.125V16.875" stroke="white" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                                Add new consultant
                            </a>
                        </div>
                    </div>
                    @if ($consulatntDataExists)
                        <div class="table-responsive" wire:key="consultant-{{ time() }}">
                            <table class="table theme-table table-hover mb-2">
                                <thead>
                                    <tr>
                                        <th scope="col">Name</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Contact Number</th>
                                        <th scope="col">Company Name</th>
                                        <th scope="col">Daily Rate</th>
                                        <th scope="col" class="action-toolbar">
                                            <div>Action</div>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($consultants as $consultant)
                                        <tr>
                                            <td class="clickable-table-cell">
                                                <a href="{{ route('consultant.edit', ['consultant' => $consultant->id]) }}"
                                                    class="link"></a>
                                                {{ $consultant->name }}
                                            </td>
                                            <td class="clickable-table-cell">
                                                <a href="{{ route('consultant.edit', ['consultant' => $consultant->id]) }}"
                                                    class="link"></a>
                                                {{ $consultant->email }}
                                            </td>
                                            <td class="clickable-table-cell">
                                                <a href="{{ route('consultant.edit', ['consultant' => $consultant->id]) }}"
                                                    class="link"></a>
                                                    {{ $consultant->phone_number ? '+(' . config('env.country_code.' . strtoupper($consultant->userprofile->country)).')'. $consultant->phone_number : '-' }}
                                            </td>
                                            <td class="clickable-table-cell">
                                                <a href="{{ route('consultant.edit', ['consultant' => $consultant->id]) }}"
                                                    class="link"></a>
                                                {{ isset($consultant->userprofile->company) ? $consultant->userprofile->company : '-' }}
                                            </td>
                                            <td class="clickable-table-cell">
                                                <a href="{{ route('consultant.edit', ['consultant' => $consultant->id]) }}"
                                                    class="link"></a>
                                                €{{ isset($consultant->userprofile->rate) ? netherlandformatCurrency($consultant->userprofile->rate, 'blur') : 0 }}
                                            </td>
                                            <td class="action-toolbar">
                                                <ul class="list-unstyled mb-0 d-flex align-items-center gap-1">
                                                    <li><a
                                                            href="{{ route('consultant.edit', ['consultant' => $consultant->id]) }}"><img
                                                                src="{{ asset('images/icons/edit-pencil.svg') }}"
                                                                alt="edit" data-bs-tolltip="toggle"
                                                                title="Edit">
                                                    </li>
                                                    <li><a wire:click.prevent="confirmDelete({{ $consultant->id }},5)"
                                                            data-bs-toggle="modal"
                                                            href="#delete_lineItem_consultant"><img
                                                                src="{{ asset('images/icons/delete-bin.svg') }}"
                                                                alt="delete" data-bs-tolltip="toggle"
                                                                title="Delete"></a></li>
                                                </ul>
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
                        <div>{{ $consultants->links('components.pagination') }}</div>
                    @else
                        <div class="content withSideSpacing">
                            <div class="no-records-without-table">
                                <span class="text-gray-50 text-md font-regular">You don't have any consultants listed
                                    yet!</span>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="tab-pane fade {{ $activeTab === 'tab2' ? 'show active' : '' }}" id="nav-sub-grantees"
                    role="tabpanel" aria-labelledby="sub-grantees">
                    <div class="content withSideSpacing">
                        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                            <div>
                                @include('elements.search-bar', [
                                    'placeholder' => 'Search sub-grantees..',
                                    'model' => 0,
                                ])
                            </div>
                            <a href="{{ route('subgrantee.create') }}" class="btn btn-primary theme-btn">
                                <svg class="me-2" width="20" height="20" viewBox="0 0 20 20"
                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M3.125 10H16.875" stroke="white" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M10 3.125V16.875" stroke="white" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                Add new sub-grantee
                            </a>
                        </div>
                    </div>
                    @if ($subgranteeDataExists)
                        <div class="table-responsive">
                            <table class="table theme-table table-hover mb-2">
                                <thead>
                                    <tr>
                                        <th scope="col">Name</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Contact Number</th>
                                        <th scope="col">Company Name</th>
                                        <th scope="col" class="action-toolbar">
                                            <div>Action</div>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($subgrantees as $subgrantee)
                                        <tr>
                                            <td class="clickable-table-cell">
                                                <a href="{{ route('subgrantee.edit', ['subgrantee' => $subgrantee->id]) }}"
                                                    class="link"></a>
                                                {{ $subgrantee->name }}
                                            </td>
                                            <td class="clickable-table-cell">
                                                <a href="{{ route('subgrantee.edit', ['subgrantee' => $subgrantee->id]) }}"
                                                    class="link"></a>
                                                {{ $subgrantee->email }}
                                            </td>
                                            <td class="clickable-table-cell">
                                                <a href="{{ route('subgrantee.edit', ['subgrantee' => $subgrantee->id]) }}"
                                                    class="link"></a>
                                                    
                                                     {{ $subgrantee->phone_number ? '+(' . config('env.country_code.' . strtoupper($subgrantee->userprofile->country)) .')'. $subgrantee->phone_number : '-' }}
                                              
                                            </td>
                                            <td class="clickable-table-cell">
                                                <a href="{{ route('subgrantee.edit', ['subgrantee' => $subgrantee->id]) }}"
                                                    class="link"></a>
                                                {{ isset($subgrantee->userprofile->company) ? $subgrantee->userprofile->company : '-' }}
                                            </td>

                                            <td class="action-toolbar">
                                                <ul class="list-unstyled mb-0 d-flex align-items-center gap-1">
                                                    <li><a
                                                            href="{{ route('subgrantee.edit', ['subgrantee' => $subgrantee->id]) }}"><img
                                                                src="{{ asset('images/icons/edit-pencil.svg') }}"
                                                                alt="edit" data-bs-tolltip="toggle"
                                                                title="Edit"></li>
                                                    <li><a wire:click.prevent="confirmDelete({{ $subgrantee->id }},4)"
                                                            data-bs-toggle="modal"
                                                            href="#delete_lineItem_sub_grantee"><img
                                                                src="{{ asset('images/icons/delete-bin.svg') }}"
                                                                alt="delete" data-bs-tolltip="toggle"
                                                                title="Delete"></a></li>
                                                </ul>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="no-records-td">
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
                        <div>{{ $subgrantees->links('components.pagination') }}</div>
                    @else
                        <div class="content withSideSpacing">
                            <div class="no-records-without-table">
                                <span class="text-gray-50 text-md font-regular">You don't have any sub-grantees listed
                                    yet!</span>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="tab-pane fade {{ $activeTab === 'tab3' ? 'show active' : '' }}" id="nav-employees"
                    role="tabpanel" aria-labelledby="employees">
                    <div class="content withSideSpacing">
                        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                            <div>
                                @include('elements.search-bar', [
                                    'placeholder' => 'Search employees..',
                                    'model' => 0,
                                ])
                            </div>
                            <a href="{{ route('employee.create') }}" class="btn btn-primary theme-btn">
                                <svg class="me-2" width="20" height="20" viewBox="0 0 20 20"
                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M3.125 10H16.875" stroke="white" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M10 3.125V16.875" stroke="white" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                Add new employee
                            </a>
                        </div>
                    </div>
                    
                    @if ($employeeDataExists)
                        <div class="table-responsive">
                            <table class="table theme-table table-hover mb-2">
                                <thead>
                                    <tr>
                                        <th scope="col">Name</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Contact Number</th>
                                        <th scope="col">Position</th>
                                        <th scope="col">Daily Rate</th>
                                        <th scope="col" class="action-toolbar">
                                            <div>Action</div>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($employees as $employee)
                                        <tr>
                                            <td class="clickable-table-cell">
                                                <a href="{{ route('employee.edit', ['employee' => $employee->id]) }}"
                                                    class="link"></a>
                                                {{ $employee->name }}
                                            </td>
                                            <td class="clickable-table-cell">
                                                <a href="{{ route('employee.edit', ['employee' => $employee->id]) }}"
                                                    class="link"></a>
                                                {{ $employee->email }}
                                            </td>
                                            <td class="clickable-table-cell">
                                                <a href="{{ route('employee.edit', ['employee' => $employee->id]) }}"
                                                    class="link"></a>
                                               {{ $employee->phone_number ? '+(' . config('env.country_code.' . strtoupper($employee->userprofile->country)) .')'. $employee->phone_number : '-' }}

                                            </td>
                                            <td class="clickable-table-cell">
                                                <a href="{{ route('employee.edit', ['employee' => $employee->id]) }}"
                                                    class="link"></a>
                                                {{ isset($employee->userprofile->position) ? $employee->userprofile->position : '-' }}
                                            </td>
                                            <td class="clickable-table-cell">
                                                <a href="{{ route('employee.edit', ['employee' => $employee->id]) }}"
                                                    class="link"></a>
                                                €{{ isset($employee->userprofile->rate) ? netherlandformatCurrency($employee->userprofile->rate, 'blur') : 0 }}
                                            </td>
                                            <td class="action-toolbar">
                                                <ul class="list-unstyled mb-0 d-flex align-items-center gap-1">
                                                    <li><a
                                                            href="{{ route('employee.edit', ['employee' => $employee->id]) }}"><img
                                                                src="{{ asset('images/icons/edit-pencil.svg') }}"
                                                                alt="edit" data-bs-tolltip="toggle"
                                                                title="Edit"></li>
                                                    <li><a wire:click.prevent="confirmDelete({{ $employee->id }},3)"
                                                            data-bs-toggle="modal"
                                                            href="#delete_lineItem_employee"><img
                                                                src="{{ asset('images/icons/delete-bin.svg') }}"
                                                                alt="delete" data-bs-tolltip="toggle"
                                                                title="Delete"></a></li>
                                                </ul>
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
                        <div>{{ $employees->links('components.pagination') }}</div>
                    @else
                        <div class="content withSideSpacing">
                            <div class="no-records-without-table">
                                <span class="text-gray-50 text-md font-regular">You don't have any employees listed
                                    yet!</span>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="tab-pane fade {{ $activeTab === 'tab4' ? 'show active' : '' }}"
                    id="nav-other-direct-expenses" role="tabpanel" aria-labelledby="other-direct-expenses">
                    <div class="content withSideSpacing">
                        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                            <div>
                                @include('elements.search-bar', [
                                    'placeholder' => 'Search other direct expenses',
                                    'model' => 0,
                                ])
                            </div>
                            <a data-bs-toggle="modal" href="#add_other_direct_expenses"
                                wire:click.prevent="clearModal()" class="btn btn-primary theme-btn">
                                <svg class="me-2" width="20" height="20" viewBox="0 0 20 20"
                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M3.125 10H16.875" stroke="white" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M10 3.125V16.875" stroke="white" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                Add new other direct expense
                            </a>
                        </div>
                    </div>
                    @if ($otherDirectExpensesExists)
                        <div class="table-responsive">
                            <table class="table theme-table table-hover mb-2">
                                <thead>
                                    <tr>
                                        <th scope="col" width="90%">Other Direct Expense Name</th>
                                        <th scope="col">Overhead</th>
                                        <th scope="col">Project</th>
                                        <th scope="col" class="action-toolbar">
                                            <div>Action</div>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($otherDirectExpenses as $otherDirectExpense)
                                        <tr>
                                            <td class="clickable-table-cell">
                                                <a data-bs-toggle="modal" href="#edit_other_direct_expenses"
                                                    wire:click.prevent="editOtherDirect({{ $otherDirectExpense->id }})"
                                                    class="link"></a>
                                                {{ $otherDirectExpense->name }}
                                            </td>
                                            <td class="clickable-table-cell">
                                                <a data-bs-toggle="modal" href="#edit_other_direct_expenses"
                                                    wire:click.prevent="editOtherDirect({{ $otherDirectExpense->id }})"
                                                    class="link"></a>
                                                {{ $otherDirectExpense->is_overhead ? 'Yes' : '-' }}
                                            </td>
                                            <td class="clickable-table-cell">
                                                <a data-bs-toggle="modal" href="#edit_other_direct_expenses"
                                                    wire:click.prevent="editOtherDirect({{ $otherDirectExpense->id }})"
                                                    class="link"></a>
                                                {{ $otherDirectExpense->is_project ? 'Yes' : '-' }}
                                            </td>
                                            <td class="action-toolbar">
                                                <ul class="list-unstyled mb-0 d-flex align-items-center gap-1">
                                                    <li><a data-bs-toggle="modal" href="#edit_other_direct_expenses"
                                                            wire:click.prevent="editOtherDirect({{ $otherDirectExpense->id }})"><img
                                                                src="{{ asset('images/icons/edit-pencil.svg') }}"
                                                                alt="edit" data-bs-tolltip="toggle" title="Edit">
                                                    </li>
                                                    <li><a data-bs-toggle="modal"
                                                            wire:click.prevent="confirmDeleteOtherDirect({{ $otherDirectExpense->id }})"
                                                            href="#delete_other_direct_expenses"><img
                                                                src="{{ asset('images/icons/delete-bin.svg') }}"
                                                                alt="delete" data-bs-tolltip="toggle"
                                                                title="Delete"></a></li>
                                                </ul>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="no-records-td">
                                                <div class="no-records">
                                                    <span class="text-gray-50 text-md font-regular">No records found</span>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                
                                </tbody>
                            </table>
                            <div>{{ $otherDirectExpenses->links('components.pagination') }}</div> 
                        </div>
                    @else
                        <div class="content withSideSpacing">
                            <div class="no-records-without-table">
                                <span class="text-gray-50 text-md font-regular">You don't have any other direct expense
                                    yet!</span>
                            </div>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
    @include('components.line-items.consulatnt_delete')
    @include('components.line-items.sub_grantee_delete')
    @include('components.line-items.employee_delete')
    @include('components.line-items.other_direct_expenses_delete')
    @include('components.line-items.add-other-direct-expenses')
    @include('components.line-items.edit-other-direct-expenses')
</div>

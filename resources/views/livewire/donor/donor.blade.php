<div>
    <div class="page-header">
        <span >@include('elements.breadcrumb',['breadcrumbs' => Breadcrumb()])</span>
        <h6 class="h6 font-medium text-gray-800 mb-20">All Donors</h6>
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
            <div>
                @include('elements.search-bar',['placeholder' => 'Search donors..','model' => 0])
            </div>
            <a href="{{route('donor.create')}}" class="btn btn-primary theme-btn">
                <svg class="me-2" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M3.125 10H16.875" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M10 3.125V16.875" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                Add new donor
            </a>
        </div>
    </div>
    <div class="content" wire:init="init">
        @if($loader)
        <livewire:loader />
        @else
        @if($donorDataExists)
        <div class="table-responsive" wire:key="donor-{{ time() }}">
            <table class="table theme-table table-hover mb-2">
                <thead>
                    <tr>
                        <th scope="col">Donor Name</th>
                        <th scope="col">Contact Number</th>
                        <th scope="col">Email address</th>
                        <th scope="col" class="action-toolbar">
                            <div>Action</div>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    
                    @forelse($users as $key => $user)
                    <tr>
                        <td class="clickable-table-cell ellipsis" style="max-width: 170px;">
                            <a href="{{ route('donor.edit',['donor' => $user->id]) }}" class="link"></a>

                            {{ $user->name}}
                        </td>
                        <td class="clickable-table-cell">
                            <a href="{{ route('donor.edit',['donor' => $user->id]) }}" class="link"></a>
                            <?php
                                $country_code = config('env.country_code')[$user->userprofile?->country ?? 'nl'] ?? '';
                                $phone_number = $user->phone_number ? '+' . $country_code . $user->phone_number : '-';
                                echo $phone_number;
                            ?>
                        </td>
                        <td class="clickable-table-cell">
                            <a href="{{ route('donor.edit',['donor' => $user->id]) }}" class="link"></a>
                            {{ $user->email}}
                        </td>
                        <td class="action-toolbar">
                            <ul class="list-unstyled mb-0 d-flex align-items-center gap-1">
                                <li><a href="{{ route('donor.edit',['donor' => $user->id]) }}"><img src="{{ asset('images/icons/edit-pencil.svg') }}" alt="edit" data-bs-tolltip="toggle" title="Edit"></li>
                                <li><a data-bs-toggle="modal"  wire:click.prevent="confirmDelete({{ $user->id }})" href="#delete_donor"><img src="{{ asset('images/icons/delete-bin.svg') }}" alt="delete" data-bs-tolltip="toggle" title="Delete"></a></li>
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
        </div>
        <div>{{ $users->links('components.pagination') }}</div>
        @else
            <div class="content withSideSpacing">
                <div class="no-records-without-table">
                    <span class="text-gray-50 text-md font-regular">You don't have any donors listed yet!</span>
                </div>
            </div>
        @endif
        @endif
        @include('components.donors.delete')
    </div>
</div>
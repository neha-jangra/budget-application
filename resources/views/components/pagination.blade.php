<div class="pagination d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div class="data-show d-flex align-items-center text-gray-800 text-sm font-regular order-2 order-md-1">
        <span class="me-3">Show</span>
        <select wire:model="recordsPerPage" class="form-control" data-minimum-results-for-search="Infinity">
            <option value="10">10</option>
            <option value="20">20</option>
            <option value="50">50</option>
            <option value="100">100</option>
        </select>
        <span class="ms-3 flex-shrink-0">{{ $paginator->firstItem() == null ? '' : $paginator->firstItem() }}
            {{ $paginator->lastItem() == null ? '' : '- ' . $paginator->lastItem() }}
            {{ $paginator->total() == null ? '' : 'of ' . $paginator->total() }}</span>
    </div>
    <ul class="pagination-button-group list-unstyled d-flex align-items-center mb-0 order-1 order-md-2">
        <li class="previous">
            @if ($paginator->onFirstPage())
                <span class="list-item d-inline-flex align-items-center gap-2 text-gray-400 text-sm font-semibold">
                     <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                        <path d="M16.875 10H3.125" stroke="#667085" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <path d="M8.75 4.375L3.125 10L8.75 15.625" stroke="#98a2b3" stroke-width="1.5"
                            stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    Previous
                </span>
            @else
                <button wire:click="previousPage" wire:loading.attr="disabled" rel="prev" class="list-item d-inline-flex align-items-center gap-2 text-gray-700 text-sm font-semibold">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                        <path d="M16.875 10H3.125" stroke="#667085" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <path d="M8.75 4.375L3.125 10L8.75 15.625" stroke="#667085" stroke-width="1.5"
                            stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    Previous
                </button>
            @endif
            </li>
        {{-- <li class="previous {{ $paginator->onFirstPage() ? 'disabled' : '' }}">
            <a class="text-decoration-none d-flex align-items-center {{ $paginator->onFirstPage() ? 'pagination-not-allowed' : '' }}"
                @unless($paginator->onFirstPage())
                     wire:click="previousPage" wire:loading.attr="disabled" rel="prev" 
                @endunless>
                <svg class="me-2" width="20" height="20" viewBox="0 0 20 20" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M16.875 10H3.125" stroke="#667085" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round" />
                    <path d="M8.75 4.375L3.125 10L8.75 15.625" stroke="#667085" stroke-width="1.5"
                        stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <span class="text-gray-700 text-sm font-semibold">Previous</span>
            </a>
        </li> --}}

        {{-- Page Numbers --}}
        @foreach (range(1, $paginator->lastPage()) as $i)
            @if ($i >= $paginator->currentPage() - 2 && $i <= $paginator->currentPage() + 2)
                <li class="page-number cursor-pointer {{ $i == $paginator->currentPage() ? 'active' : '' }}"
                    wire:click="gotoPage({{ $i }})">
                    <div class="text-gray-800 text-sm font-semibold {{ $i == $paginator->currentPage() ? 'active-color' : '' }}">{{ $i }}</div>
                </li>
            @endif
        @endforeach

        {{-- <li class="next {{ $paginator->hasMorePages() ? '' : 'disabled' }}">
            <a class="text-decoration-none d-flex align-items-center {{ $paginator->hasMorePages() ? '' : 'pagination-not-allowed' }}"
                @if($paginator->hasMorePages())
                    wire:click="nextPage" wire:loading.attr="disabled" rel="next"
                @endif>
                <span class="text-gray-700 text-sm font-semibold">Next</span>
                <svg class="ms-2" width="20" height="20" viewBox="0 0 20 20" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M3.125 10H16.875" stroke="#667085" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round" />
                    <path d="M11.25 4.375L16.875 10L11.25 15.625" stroke="#667085" stroke-width="1.5"
                        stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </a>
        </li> --}}
        <li class="next">
            @if ($paginator->hasMorePages())
                <button wire:click="nextPage" wire:loading.attr="disabled" rel="next" class="list-item d-inline-flex align-items-center gap-2 text-gray-700 text-sm font-semibold">
                    Next
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                        <path d="M3.125 10H16.875" stroke="#667085" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <path d="M11.25 4.375L16.875 10L11.25 15.625" stroke="#667085" stroke-width="1.5"
                            stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>
            @else
                <span class="list-item d-inline-flex align-items-center gap-2 text-gray-400 text-sm font-semibold">
                    Next
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                        <path d="M3.125 10H16.875" stroke="#667085" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <path d="M11.25 4.375L16.875 10L11.25 15.625" stroke="#98a2b3" stroke-width="1.5"
                            stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </span>
            @endif
        </li>
    </ul>
</div>

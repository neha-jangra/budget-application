<div class="theme-form">
    <div class="searchbar position-relative">
        <div class="search-icon position-absolute">
            <img src="/images/icons/search-icon.svg" alt="search-icon">
        </div>
        @if ($model == 1)
            <input type="text" id="searchInput" class="form-control w-360" placeholder="{{ $placeholder }}">
            <div class="clear-result position-absolute" id="clearButton">
                <img src="/images/icons/close-btn.svg" alt="close-btn">
            </div>
        @else
            <input type="text" wire:model="search_by_name" id="searchInput" class="form-control w-360"
                placeholder="{{ $placeholder }}">
            <div class="clear-result position-absolute" id="clearButton" wire:click="clearInput">
                <img src="/images/icons/close-btn.svg" alt="close-btn">
            </div>
        @endif 
    </div>
</div>

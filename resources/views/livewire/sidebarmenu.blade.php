<ul class="list-unstyled sidebar-menu-listing">
    <li class="mb-12">
        @if (sideBarPermission('budgeting'))
            <div class="parent-menu">Budgeting</div>
        @endif
        <ul class="list-unstyled">
            @if (auth()->user()->roles->flatMap->permissions->pluck('name')->contains('project'))
                <li class="menu-item {{ request()->is('project*') == 'project' ? 'active' : '' }}">
                    <a href="{{ route('project.index') }}"
                        class="d-flex align-items-center justify-content-center justify-content-md-start">
                        <svg class="icon flex-shrink-0 d-flex align-self-start" width="22" height="22"
                            viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M8.25 13.0625H13.75" stroke="#D4D9E0" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M8.25 10.3125H13.75" stroke="#D4D9E0" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path
                                d="M13.75 3.4375H17.1875C17.3698 3.4375 17.5447 3.50993 17.6736 3.63886C17.8026 3.7678 17.875 3.94266 17.875 4.125V18.5625C17.875 18.7448 17.8026 18.9197 17.6736 19.0486C17.5447 19.1776 17.3698 19.25 17.1875 19.25H4.8125C4.63016 19.25 4.4553 19.1776 4.32636 19.0486C4.19743 18.9197 4.125 18.7448 4.125 18.5625V4.125C4.125 3.94266 4.19743 3.7678 4.32636 3.63886C4.4553 3.50993 4.63016 3.4375 4.8125 3.4375H8.25"
                                stroke="#D4D9E0" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path
                                d="M7.5625 6.1875V5.5C7.5625 4.58832 7.92466 3.71398 8.56932 3.06932C9.21398 2.42466 10.0883 2.0625 11 2.0625C11.9117 2.0625 12.786 2.42466 13.4307 3.06932C14.0753 3.71398 14.4375 4.58832 14.4375 5.5V6.1875H7.5625Z"
                                stroke="#D4D9E0" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <span class="align-self-center item-name">Projects</span>
                    </a>
                    <div class="vertical-bar">
                        <div class="element"></div>
                    </div>
                </li>
            @endif
            @if (auth()->user()->roles->flatMap->permissions->pluck('name')->contains('donor'))
                <li class="menu-item {{ request()->is('donor*') == 'donor' ? 'active' : '' }}">
                    <a href="{{ route('donor.index') }}"
                        class="d-flex align-items-center justify-content-center justify-content-md-start">
                        <svg class="icon flex-shrink-0 d-flex align-self-start" width="22" height="22"
                            viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M7.5625 13.75C10.0305 13.75 12.0312 11.7493 12.0312 9.28125C12.0312 6.81323 10.0305 4.8125 7.5625 4.8125C5.09448 4.8125 3.09375 6.81323 3.09375 9.28125C3.09375 11.7493 5.09448 13.75 7.5625 13.75Z"
                                stroke="#D4D9E0" stroke-width="1.5" stroke-miterlimit="10" />
                            <path
                                d="M13.3547 4.97578C13.7498 4.86864 14.1571 4.81375 14.5665 4.8125C15.7516 4.8125 16.8883 5.28331 17.7263 6.12137C18.5644 6.95942 19.0352 8.09606 19.0352 9.28125C19.0352 10.4664 18.5644 11.6031 17.7263 12.4411C16.8883 13.2792 15.7516 13.75 14.5665 13.75"
                                stroke="#D4D9E0" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path
                                d="M1.375 16.9641C2.07277 15.9712 2.99925 15.1608 4.07619 14.6013C5.15314 14.0418 6.34891 13.7498 7.5625 13.7498C8.77609 13.7498 9.97186 14.0418 11.0488 14.6013C12.1257 15.1608 13.0522 15.9712 13.75 16.9641"
                                stroke="#D4D9E0" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path
                                d="M14.5664 13.75C15.7801 13.7492 16.9761 14.041 18.0532 14.6004C19.1302 15.1599 20.0566 15.9707 20.7539 16.9641"
                                stroke="#D4D9E0" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <span class="align-self-center item-name">Donors</span>
                    </a>
                    <div class="vertical-bar">
                        <div class="element"></div>
                    </div>
                </li>
            @endif
            @if (auth()->user()->roles->flatMap->permissions->pluck('name')->contains('line_item'))
                <li class="menu-item {{ request()->is('line-item*') == 'line-item' ? 'active' : '' }}">
                    <a href="{{ route('line-item.index') }}"
                        class="d-flex align-items-center justify-content-center justify-content-md-start">
                        <svg class="icon flex-shrink-0 d-flex align-self-start" width="22" height="22"
                            viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M7.5625 5.5H18.5625" stroke="#D4D9E0" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M7.5625 11H18.5625" stroke="#D4D9E0" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M7.5625 16.5H18.5625" stroke="#D4D9E0" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path
                                d="M3.78125 6.53125C4.35079 6.53125 4.8125 6.06954 4.8125 5.5C4.8125 4.93046 4.35079 4.46875 3.78125 4.46875C3.21171 4.46875 2.75 4.93046 2.75 5.5C2.75 6.06954 3.21171 6.53125 3.78125 6.53125Z"
                                fill="#D4D9E0" />
                            <path
                                d="M3.78125 12.0312C4.35079 12.0312 4.8125 11.5695 4.8125 11C4.8125 10.4305 4.35079 9.96875 3.78125 9.96875C3.21171 9.96875 2.75 10.4305 2.75 11C2.75 11.5695 3.21171 12.0312 3.78125 12.0312Z"
                                fill="#D4D9E0" />
                            <path
                                d="M3.78125 17.5312C4.35079 17.5312 4.8125 17.0695 4.8125 16.5C4.8125 15.9305 4.35079 15.4688 3.78125 15.4688C3.21171 15.4688 2.75 15.9305 2.75 16.5C2.75 17.0695 3.21171 17.5312 3.78125 17.5312Z"
                                fill="#D4D9E0" />
                        </svg>
                        <span class="align-self-center item-name">Line Items</span>
                    </a>
                    <div class="vertical-bar">
                        <div class="element"></div>
                    </div>
                </li>
            @endif
            @if (auth()->user()->roles->flatMap->permissions->pluck('name')->contains('indirect_costs_budget'))
            <li
                class="menu-item {{ request()->is('indirect-costs-budget*') == 'indirect-costs-budget' ? 'active' : '' }}">
                <a href="{{ route('indirect.index') }}"
                    class="d-flex align-items-center justify-content-center justify-content-md-start">
                    <svg class="icon flex-shrink-0 d-flex align-self-start" width="22" height="22"
                        viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M7.5625 5.5H18.5625" stroke="#D4D9E0" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <path d="M7.5625 11H18.5625" stroke="#D4D9E0" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <path d="M7.5625 16.5H18.5625" stroke="#D4D9E0" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <path
                            d="M3.78125 6.53125C4.35079 6.53125 4.8125 6.06954 4.8125 5.5C4.8125 4.93046 4.35079 4.46875 3.78125 4.46875C3.21171 4.46875 2.75 4.93046 2.75 5.5C2.75 6.06954 3.21171 6.53125 3.78125 6.53125Z"
                            fill="#D4D9E0" />
                        <path
                            d="M3.78125 12.0312C4.35079 12.0312 4.8125 11.5695 4.8125 11C4.8125 10.4305 4.35079 9.96875 3.78125 9.96875C3.21171 9.96875 2.75 10.4305 2.75 11C2.75 11.5695 3.21171 12.0312 3.78125 12.0312Z"
                            fill="#D4D9E0" />
                        <path
                            d="M3.78125 17.5312C4.35079 17.5312 4.8125 17.0695 4.8125 16.5C4.8125 15.9305 4.35079 15.4688 3.78125 15.4688C3.21171 15.4688 2.75 15.9305 2.75 16.5C2.75 17.0695 3.21171 17.5312 3.78125 17.5312Z"
                            fill="#D4D9E0" />
                    </svg>
                    <span class="align-self-center item-name">Indirect Costs Budget</span>
                </a>
                <div class="vertical-bar">
                    <div class="element"></div>
                </div>
            </li>
            @endif
        </ul>
    </li>
    <li class="mb-12">
        @if (sideBarPermission('managemnet'))
            <div class="parent-menu">Management</div>
        @endif
        <ul class="list-unstyled">
            @if (auth()->user()->roles->flatMap->permissions->pluck('name')->contains('user'))
                <li class="menu-item {{ request()->is('user*') == 'user' ? 'active' : '' }}">
                    <a href="{{ route('user.index') }}"
                        class="d-flex align-items-center justify-content-center justify-content-md-start">
                        <svg class="icon flex-shrink-0 d-flex align-self-start" width="22" height="22"
                            viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M11 15.4688C12.8985 15.4688 14.4375 13.9297 14.4375 12.0312C14.4375 10.1328 12.8985 8.59375 11 8.59375C9.10152 8.59375 7.5625 10.1328 7.5625 12.0312C7.5625 13.9297 9.10152 15.4688 11 15.4688Z"
                                stroke="#D4D9E0" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path
                                d="M16.8438 9.96876C17.6445 9.96742 18.4344 10.1532 19.1506 10.5113C19.8668 10.8694 20.4894 11.3899 20.9688 12.0313"
                                stroke="#D4D9E0" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path
                                d="M1.03125 12.0313C1.51061 11.3899 2.13321 10.8694 2.84939 10.5113C3.56558 10.1532 4.35553 9.96742 5.15625 9.96876"
                                stroke="#D4D9E0" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path
                                d="M6.05005 18.5623C6.50279 17.6351 7.20684 16.8537 8.08199 16.3071C8.95715 15.7605 9.96823 15.4707 11 15.4707C12.0319 15.4707 13.0429 15.7605 13.9181 16.3071C14.7933 16.8537 15.4973 17.6351 15.95 18.5623"
                                stroke="#D4D9E0" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path
                                d="M5.15623 9.96875C4.63429 9.96928 4.12296 9.82126 3.68202 9.54198C3.24107 9.26271 2.88871 8.86371 2.6661 8.39161C2.44349 7.91951 2.35983 7.39381 2.42489 6.87594C2.48996 6.35806 2.70106 5.86939 3.03354 5.46704C3.36601 5.06468 3.80613 4.76525 4.30245 4.60373C4.79878 4.44222 5.33083 4.42528 5.83642 4.55491C6.34202 4.68454 6.80028 4.95538 7.15767 5.33578C7.51506 5.71618 7.75681 6.19043 7.85467 6.70312"
                                stroke="#D4D9E0" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path
                                d="M14.1453 6.70312C14.2431 6.19043 14.4849 5.71618 14.8423 5.33578C15.1997 4.95538 15.6579 4.68454 16.1635 4.55491C16.6691 4.42528 17.2012 4.44222 17.6975 4.60373C18.1938 4.76525 18.6339 5.06468 18.9664 5.46704C19.2989 5.86939 19.51 6.35806 19.575 6.87594C19.6401 7.39381 19.5564 7.91951 19.3338 8.39161C19.1112 8.86371 18.7589 9.26271 18.3179 9.54198C17.877 9.82126 17.3656 9.96928 16.8437 9.96875"
                                stroke="#D4D9E0" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                        <span class="align-self-center item-name">Users</span>
                    </a>
                    <div class="vertical-bar">
                        <div class="element"></div>
                    </div>
                </li>
            @endif
            @if (auth()->user()->roles->flatMap->permissions->pluck('name')->contains('role_management'))
                <li
                    class="menu-item @if (request()->is('role-management*') == 'role-management' || currentRoute() == 'sidebarmenu') {{ 'active' }} @else {{ '' }} @endif">
                    <a href="{{ route('role-management.index') }}"
                        class="d-flex align-items-center justify-content-center justify-content-md-start">
                        <svg class="icon flex-shrink-0 d-flex align-self-start" width="22" height="22"
                            viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M9.28125 13.75C12.129 13.75 14.4375 11.4415 14.4375 8.59375C14.4375 5.74603 12.129 3.4375 9.28125 3.4375C6.43353 3.4375 4.125 5.74603 4.125 8.59375C4.125 11.4415 6.43353 13.75 9.28125 13.75Z"
                                stroke="#D4D9E0" stroke-width="1.5" stroke-miterlimit="10" />
                            <path
                                d="M1.90784 17.1875C2.81126 16.1108 3.93955 15.2449 5.21339 14.6509C6.48724 14.0569 7.87573 13.749 9.28128 13.749C10.6868 13.749 12.0753 14.0569 13.3492 14.6509C14.623 15.2449 15.7513 16.1108 16.6547 17.1875"
                                stroke="#D4D9E0" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path
                                d="M18.9062 13.0625C19.6656 13.0625 20.2812 12.4469 20.2812 11.6875C20.2812 10.9281 19.6656 10.3125 18.9062 10.3125C18.1469 10.3125 17.5312 10.9281 17.5312 11.6875C17.5312 12.4469 18.1469 13.0625 18.9062 13.0625Z"
                                stroke="#D4D9E0" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M18.9062 10.3125V9.28125" stroke="#D4D9E0" stroke-width="1.5"
                                stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M17.7117 11L16.8265 10.4844" stroke="#D4D9E0" stroke-width="1.5"
                                stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M17.7117 12.375L16.8265 12.8906" stroke="#D4D9E0" stroke-width="1.5"
                                stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M18.9062 13.0625V14.0938" stroke="#D4D9E0" stroke-width="1.5"
                                stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M20.1008 12.375L20.986 12.8906" stroke="#D4D9E0" stroke-width="1.5"
                                stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M20.1008 11L20.986 10.4844" stroke="#D4D9E0" stroke-width="1.5"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <span class="align-self-center item-name">Role Management</span>
                    </a>
                    <div class="vertical-bar">
                        <div class="element"></div>
                    </div>
                </li>
            @endif
        </ul>
    </li>
   
    <li class="mb-12">
        <div class="parent-menu">General</div>
        <ul class="list-unstyled">
            @if (auth()->user()->roles->flatMap->permissions->pluck('name')->contains('reports'))
            <li class="menu-item {{ request()->is('reports*') == 'reports' ? 'active' : '' }}">
                <a href="{{ route('reports.index') }}"
                    class="d-flex align-items-center justify-content-center justify-content-md-start ">
                    <svg class="icon flex-shrink-0 d-flex align-self-start" width="22" height="22"
                        viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M3.78125 17.875V11.6875H8.59375" stroke="#D4D9E0" stroke-width="1.5"
                            stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M19.5938 17.875H2.40625" stroke="#D4D9E0" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <path d="M8.59375 17.875V7.5625H13.4062" stroke="#D4D9E0" stroke-width="1.5"
                            stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M18.2188 3.4375H13.4062V17.875H18.2188V3.4375Z" stroke="#D4D9E0" stroke-width="1.5"
                            stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <span class="align-self-center item-name">Reports</span>
                </a>
                <div class="vertical-bar">
                    <div class="element"></div>
                </div>
            </li>
            @endif
            <li class="menu-item">
                <a href="{{ route('settings.index') }}"
                    class="d-flex align-items-center justify-content-center justify-content-md-start">
                    <svg class="icon flex-shrink-0 d-flex align-self-start" width="22" height="22"
                        viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M11 15.125C13.2782 15.125 15.125 13.2782 15.125 11C15.125 8.72183 13.2782 6.875 11 6.875C8.72183 6.875 6.875 8.72183 6.875 11C6.875 13.2782 8.72183 15.125 11 15.125Z"
                            stroke="#D4D9E0" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        <path
                            d="M15.7867 5.59473C16.0044 5.79525 16.2106 6.0015 16.4054 6.21348L18.7515 6.54863C19.1338 7.2125 19.4284 7.92311 19.6281 8.6627L18.2015 10.5619C18.2015 10.5619 18.2273 11.1463 18.2015 11.4385L19.6281 13.3377C19.4293 14.0776 19.1347 14.7883 18.7515 15.4518L16.4054 15.7869C16.4054 15.7869 16.0015 16.208 15.7867 16.4057L15.4515 18.7518C14.7876 19.134 14.077 19.4287 13.3375 19.6283L11.4382 18.2018C11.1466 18.2275 10.8533 18.2275 10.5617 18.2018L8.66245 19.6283C7.92255 19.4296 7.21183 19.1349 6.54839 18.7518L6.21323 16.4057C6.00125 16.2051 5.795 15.9989 5.59448 15.7869L3.24839 15.4518C2.86612 14.7879 2.57148 14.0773 2.37183 13.3377L3.79839 11.4385C3.79839 11.4385 3.77261 10.8541 3.79839 10.5619L2.37183 8.6627C2.57056 7.92279 2.86525 7.21207 3.24839 6.54863L5.59448 6.21348C5.795 6.0015 6.00125 5.79525 6.21323 5.59473L6.54839 3.24863C7.21226 2.86637 7.92286 2.57173 8.66245 2.37207L10.5617 3.79863C10.8533 3.77285 11.1466 3.77285 11.4382 3.79863L13.3375 2.37207C14.0774 2.5708 14.7881 2.86549 15.4515 3.24863L15.7867 5.59473Z"
                            stroke="#D4D9E0" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <span class="align-self-center item-name">Settings</span>
                </a>
                <div class="vertical-bar">
                    <div class="element"></div>
                </div>
            </li>
        </ul>
    </li>

</ul>

<nav class="sidebar bg-gray-900 active d-flex flex-column" id='menu'>
    <ul class="list-unstyled mb-auto">
        <li class="logo-li">
            <a id="menu-action" class="logo" href="{{route('project.index')}}">
                <img src="/images/logo-small.svg" class="icon img-1 img-fluid">
                <img src="/images/sidebar-logo.svg" class="icon img-2 img-fluid">
            </a>
        </li>
        <li>
            <livewire:sidebarmenu />
        </li>
    </ul>
    <div class=" mt-auto">
        <div class="btn-group dropup w-100 last-syn-div">
            <a type="button" class="text-decoration-none w-100 inner-content" data-bs-toggle="dropdown" data-bs-auto-close="outside"
                aria-expanded="false">
                <div class="d-flex w-100 justify-content-between text-decoration-none align-items-center last-syn-tag">
                    <div class="d-flex flex-column">
                        <div class="text-1">Last Sync</div>
                        <div class="last-sync-date date-text">-</div>
                    </div>
                    <div class="error-tag last-sync-error-tag d-none">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                            <path
                                d="M8.65625 9.34375V6.65625H7.34375V9.34375H8.65625ZM8.65625 12V10.6562H7.34375V12H8.65625ZM0.65625 14L8 1.34375L15.3438 14H0.65625Z"
                                fill="#912018" />
                        </svg>
                        Error
                    </div>
                    <div class="success-tag last-sync-success-tag d-none">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                            <path d="M2.66667 8.00011L6.22 11.5534L13.3267 4.44678" stroke="#05603A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg> Active
                    </div>
                    <div class="pending-tag last-sync-pending-tag">
                        <svg id="Layer_1" style="enable-background:new 0 0 30 30;" version="1.1" viewBox="0 0 30 30" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                            <style type="text/css">
                                .st8 {
                                    fill: #d9512c;
                                }
                            </style>
                            <path class="st8" d="M15,4C8.9,4,4,8.9,4,15s4.9,11,11,11s11-4.9,11-11S21.1,4,15,4z M21.7,16.8c-0.1,0.4-0.5,0.6-0.9,0.5l-5.6-1.1  c-0.2,0-0.4-0.2-0.6-0.3C14.2,15.7,14,15.4,14,15c0,0,0,0,0,0l0.2-8c0-0.5,0.4-0.8,0.8-0.8c0.4,0,0.8,0.4,0.8,0.8l0.1,6.9l5.2,1.8  C21.6,15.8,21.8,16.3,21.7,16.8z" />
                        </svg>
                        Pending
                    </div>
                </div>
            </a>
            <ul class="dropdown-menu">
                <li class="user-info">
                    <div class="d-flex justify-content-between align-items-center theme-py-8">
                 
                        <div class="error-tag last-sync-error-tag">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                <path
                                    d="M8.65625 9.34375V6.65625H7.34375V9.34375H8.65625ZM8.65625 12V10.6562H7.34375V12H8.65625ZM0.65625 14L8 1.34375L15.3438 14H0.65625Z"
                                    fill="#912018" />
                            </svg>
                            Error
                        </div>
                        <div class="success-tag last-sync-success-tag">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                <path d="M2.66667 8.00011L6.22 11.5534L13.3267 4.44678" stroke="#05603A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg> Active
                        </div>
                        <div class="pending-tag last-sync-pending-tag">
                            <svg style="enable-background:new 0 0 30 30;" version="1.1" viewBox="0 0 30 30" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                <style type="text/css">
                                    .st8 {
                                        fill: #d9512c;
                                    }
                                </style>
                                <path class="st8" d="M15,4C8.9,4,4,8.9,4,15s4.9,11,11,11s11-4.9,11-11S21.1,4,15,4z M21.7,16.8c-0.1,0.4-0.5,0.6-0.9,0.5l-5.6-1.1  c-0.2,0-0.4-0.2-0.6-0.3C14.2,15.7,14,15.4,14,15c0,0,0,0,0,0l0.2-8c0-0.5,0.4-0.8,0.8-0.8c0.4,0,0.8,0.4,0.8,0.8l0.1,6.9l5.2,1.8  C21.6,15.8,21.8,16.3,21.7,16.8z" />
                            </svg>
                            Pending
                        </div>
                    </div>
                    <div class="theme-py-4">
                        <p class="text-1 m-0">Last Sync</p>
                        <p class="date-text last-sync-date m-0">-</p>
                    </div>
                    <div class="theme-py-4">
                        <p class="text-1 m-0">Upcoming Sync</p>
                        <p class="date-text m-0 upcomming-sync-date">-</p>
                    </div>
                </li>
                <li class="theme-py-2 theme-px-6">
                    <a id="force-sync-btn" class="btn btn-primary theme-btn d-block" type="button" data-bs-toggle="modal" data-bs-target="#forceModal">Force Sync</a>
                </li>
            </ul>
        </div>

        <div class="btn-group dropup w-100">
            <button type="button"
                class="btn dropdown-toggle text-white d-flex align-items-center justify-content-md-between justify-content-center"
                data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">

                <div class="d-flex align-items-center">
                    @if (auth()->user()->userprofile->photo)
                    <img class="rounded-circle img-fluid imagePreview" src="{{ asset('storage/' . auth()->user()->userprofile->photo) }}"
                        alt="Profile Photo" height="30" width="30"
                        style="height: 30px;width: 30px;object-fit: cover; margin-right: 5px;">
                    @else
                    <div class="profile-pic">
                        {{ nameInitial() }}
                    </div>
                    @endif
                    <span class="user-name text-sm font-medium">{{ auth()->user()->name }}</span>
                </div>

            </button>
            <ul class="dropdown-menu">
                <!-- Dropdown menu links -->
                <li class="user-info">
                    <div class="d-flex align-items-center">
                        <div class="profile-pic">
                            {{nameInitial()}}
                        </div>
                        <div>
                            <div class="text-white text-sm font-medium">{{ auth()->user()->name }}</div>
                            <div class="text-gray-300 text-xs font-regular text-break">{{ auth()->user()->email }}</div>
                        </div>
                    </div>
                </li>
                <li class="list-item">
                    <form class="theme-form" id="logout-form" action="{{ route('logout') }}" method="POST">
                        {{ csrf_field() }}
                        <button type="submit" name="logout" name="logout"
                            class="logout-btn w-100 d-flex align-items-center text-decoration-none bg-primary-900">
                            <svg class="flex-shrink-0 me-2" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M16.3125 8.0625L20.25 12L16.3125 15.9375" stroke="#F04438" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M9.75 12H20.25" stroke="#F04438" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path
                                    d="M9.75 20.25H4.5C4.30109 20.25 4.11032 20.171 3.96967 20.0303C3.82902 19.8897 3.75 19.6989 3.75 19.5V4.5C3.75 4.30109 3.82902 4.11032 3.96967 3.96967C4.11032 3.82902 4.30109 3.75 4.5 3.75H9.75"
                                    stroke="#F04438" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>

                            <span class="text-error-500 text-sm font-medium">Log out</span>
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>
<!-- Force Sync Modal -->
<div class="modal fade force-modal" id="forceModal" tabindex="-1" aria-labelledby="forceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <svg xmlns="http://www.w3.org/2000/svg" width="128" height="126" viewBox="0 0 128 126" fill="none">
                    <path d="M64 125.794C98.8773 125.794 127.151 97.6337 127.151 62.8968C127.151 28.1598 98.8773 0 64 0C29.1227 0 0.84906 28.1598 0.84906 62.8968C0.84906 97.6337 29.1227 125.794 64 125.794Z" fill="#E8F1F6" />
                    <path d="M48.25 36.4162H80.75V52.6662L69.9167 63.4995L80.75 74.3328V90.5828H48.25V74.3328L59.0833 63.4995L48.25 52.6662V36.4162ZM75.3333 75.687L64.5 64.8537L53.6667 75.687V85.1662H75.3333V75.687ZM64.5 62.1453L75.3333 51.312V41.8328H53.6667V51.312L64.5 62.1453ZM59.0833 47.2495H69.9167V49.2807L64.5 54.6974L59.0833 49.2807V47.2495Z" fill="#0D5B92" />
                </svg>
                <div>
                    <h6>We are syncing the data</h6>
                    <p>This may take up to 30 seconds. You can close this window if needed; the sync will continue in the background.</p>
                </div>
            </div>
        </div>
    </div>
</div>
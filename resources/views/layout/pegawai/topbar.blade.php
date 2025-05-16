    <div class="nk-header nk-header-fixed is-light mb-5">
        <div class="container-fluid">
            <div class="nk-header-wrap">
                <div class="nk-menu-trigger d-xl-none ms-n1">
                    <a href="#" class="nk-nav-toggle nk-quick-nav-icon" data-target="sidebarMenu"><em
                            class="icon ni ni-menu"></em></a>
                </div>
                <div class="nk-header-brand d-xl-none">
                    <a href="{{ route('pegawai.pages.pegawai.dashboard') }}" class="logo-link">
                        <img class="logo-light logo-img" src="{{ asset('demo5/src/images/logo.png') }}"
                            srcset="{{ asset('demo5/src/images/logo2x.png') }} 2x" alt="logo">
                        <p style="margin-top:-2rem" class="text-left text-allign-center"><b></b></p>
                    </a>
                </div><!-- .nk-header-brand -->
                <div class="nk-header-news d-none d-xl-block">
                    <div class="nk-news-list">
                        <div class="nk-header-search ms-3 ms-xl-0" style="display: flex; align-items: center;">
                            <em class="icon ni ni-search" style="margin-right: 10px;"></em>
                            <input type="text" id="search" value=""
                                class="form-control border-transparent form-focus-none" placeholder="Cari pekerjaan"
                                style="width: 300px; max-wlightprimaidth: 100%;">
                        </div>
                    </div>
                </div><!-- .nk-header-news -->

                <div class="nk-header-tools">
                    <ul class="nk-quick-nav">
                        <div class="nk-news-icon">
                            <em class="fa-solid fa-display"></em>
                        </div>
                        <li class="dropdown user-dropdown">
                            <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown">
                                <div class="user-toggle">
                                    <div class="user-avatar sm bg-secondary">
                                        <em class="icon ni ni-user-alt text-white"></em>
                                    </div>
                                    <div class="user-info d-none d-md-block">
                                        <div class="user-status text-secondary">Pegawai</div>
                                        <div class="user-name dropdown-indicator">{{ auth()->user()->name }}</div>
                                    </div>
                                </div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-md dropdown-menu-end ">
                                <div class="dropdown-inner user-card-wrap bg-lighter d-none d-md-block">
                                    <div class="user-card">
                                        <div class="user-avatar bg-secondary ">
                                            @php
                                                $nameParts = explode(' ', auth()->user()->name);
                                                $initials = strtoupper(
                                                    $nameParts[0][0] . (isset($nameParts[1]) ? $nameParts[1][0] : ''),
                                                );
                                            @endphp
                                            <span class="text-white">{{ $initials }}</span>
                                        </div>
                                        <div class="user-info">
                                            <span class="lead-text">{{ auth()->user()->name }}</span>
                                            <span class="sub-text">{{ auth()->user()->email }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="dropdown-inner">
                                    <ul class="link-list">
                                        <li><a href="{{ route('pegawai.profilakun') }}"><em
                                                    class="icon ni ni-account-setting-alt"></em><span>Informasi
                                                    Akun</span></a></li>
                                        {{-- <li><a href="html/user-profile-activity.html"><em
                                                    class="icon ni ni-activity-alt"></em><span>Login
                                                    Activity</span></a></li>
                                        <li><a class="dark-switch" href="#"><em
                                                    class="icon ni ni-moon"></em><span>Dark Mode</span></a></li> --}}

                                    </ul>
                                </div>
                                <div class="dropdown-inner">
                                    <ul class="link-list">
                                        <li><a href="{{ route('logout') }}"><em
                                                    class="icon ni ni-signout"></em><span>Logout</span></a></li>
                                    </ul>
                                </div>
                            </div>
                        </li><!-- .dropdown -->
                        {{-- <li class="dropdown notification-dropdown me-n1">
                            <a href="#" class="dropdown-toggle nk-quick-nav-icon" data-bs-toggle="dropdown">
                                <div class="icon-status icon-status-info"><em class="icon ni ni-bell"></em></div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-xl dropdown-menu-end">
                                <div class="dropdown-head">
                                    <span class="sub-title nk-dropdown-title">Notifications</span>
                                </div>
                                <div class="dropdown-body">
                                    <div class="nk-notification">
                                        <div class="nk-notification-item dropdown-inner">
                                            <div class="nk-notification-icon">
                                                <em class="icon icon-circle bg-warning-dim ni ni-curve-down-right"></em>
                                            </div>
                                            <div class="nk-notification-content">
                                                <div class="nk-notification-text">You have requested to
                                                    <span>Widthdrawl</span>
                                                </div>
                                                <div class="nk-notification-time">2 hrs ago</div>
                                            </div>
                                        </div>
                                        <div class="nk-notification-item dropdown-inner">
                                            <div class="nk-notification-icon">
                                                <em class="icon icon-circle bg-success-dim ni ni-curve-down-left"></em>
                                            </div>
                                            <div class="nk-notification-content">
                                                <div class="nk-notification-text">Your <span>Deposit
                                                        Order</span> is placed</div>
                                                <div class="nk-notification-time">2 hrs ago</div>
                                            </div>
                                        </div>
                                    </div><!-- .nk-dropdown-body -->
                                    <div class="dropdown-foot center">
                                        <a href="#" class="text-secondary">View All</a>
                                    </div>
                                </div>
                        </li><!-- .dropdown --> --}}
                    </ul><!-- .nk-quick-nav -->
                </div><!-- .nk-header-tools -->
            </div><!-- .nk-header-wrap -->
        </div><!-- .container-fliud -->
    </div>

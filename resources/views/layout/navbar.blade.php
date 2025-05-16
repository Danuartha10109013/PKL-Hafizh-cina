<div id="sidebar" class="nk-sidebar nk-sidebar-fixed" data-content="sidebarMenu">
    <div class="nk-sidebar-element nk-sidebar-head">
        <div class="nk-sidebar-brand">
            <a href="{{ route('admin.pages.admin.dashboard') }}" class="logo-link nk-sidebar-logo">
                <img class="logo-dark logo-img" src="{{ asset('demo5/src/images/logonew1.png') }}"
                    srcset="{{ asset('demo5/src/images/logonew1.png') }}" alt="logo-dark">
            </a>
        </div>
        <div class="nk-menu-trigger me-n2">
            {{-- <a href="#" class="nk-nav-toggle nk-quick-nav-icon" data-target="sidebarMenu"><em
                    class="icon ni ni-arrow-left"></em></a> --}}
            <a href="#" class="nk-nav-compact nk-quick-nav-icon d-none d-xl-inline-flex"
                data-target="sidebarMenu"><em class="icon ni ni-menu"></em></a>
        </div>

    </div><!-- .nk-sidebar-element -->
    <div class="nk-sidebar-element">
        <div class="nk-sidebar-body" data-simplebar>
            <div class="nk-sidebar-content">
                <div class="nk-sidebar-menu">
                    <ul class="nk-menu">
                        <li class="nk-menu-heading">
                            <h6 class="overline-title text-secondary-alt">Hallo {{ auth()->user()->name }}</h6>
                        </li><!-- .nk-menu-item -->
                        <li class="nk-menu-item">
                            <a href="{{ route('admin.pages.admin.dashboard') }}" class="nk-menu-link"
                                data-bs-original-title title="">
                                <span class="nk-menu-icon"><em class="icon ni ni-home"></em></span>
                                <span class="nk-menu-text">Dashboard</span>
                            </a>
                        </li><!-- .nk-menu-item -->

                        <li class="nk-menu-heading">
                            <h6 class="overline-title text-secondary-alt">Applications</h6>
                        </li><!-- .nk-menu-heading -->
                        <li class="nk-menu-item">
                            <a href="{{ route('admin.kelolapegawai') }}" class="nk-menu-link">
                                <span class="nk-menu-icon"><em class="icon ni ni-users"></em></span>
                                <span class="nk-menu-text">Kelola Pegawai</span>
                            </a>
                        </li><!-- .nk-menu-item -->
                        <li class="nk-menu-item has-sub">
                            <a href="#" class="nk-menu-link nk-menu-toggle">
                                <span class="nk-menu-icon"><em class="icon ni ni-calendar-check"></em></span>
                                <span class="nk-menu-text">Kelola Kehadiran</span>
                            </a>
                            <ul class="nk-menu-sub">
                                <li class="nk-menu-item">
                                    <a href="{{ route('admin.kelolakehadiranpegawai') }}" class="nk-menu-link"><span
                                            class="nk-menu-text">Pegawai</span></a>
                                </li>
                                <li class="nk-menu-item">
                                    <a href="{{ route('admin.rekapitulasi') }}" class="nk-menu-link"><span
                                            class="nk-menu-text">Rekapitulasi</span></a>
                                </li>
                                <li class="nk-menu-item">
                                    <a href="{{ route('admin.daftarsanksi') }}" class="nk-menu-link"><span
                                            class="nk-menu-text">Daftar
                                            Sanksi</span></a>
                                </li>
                            </ul>
                        </li>
                        <li class="nk-menu-item">
                            <a href="{{ route('admin.kelolajadwal') }}" class="nk-menu-link">
                                <span class="nk-menu-icon"><em class="icon ni ni-calendar"></em></span>
                                <span class="nk-menu-text">Kelola Jadwal</span>
                            </a>
                        <li class="nk-menu-item">
                            <a href="{{ route('admin.kelolacuti') }}" class="nk-menu-link">
                                <span class="nk-menu-icon"><em class="icon ni ni-calendar-alt"></em></span>
                                <span class="nk-menu-text">Kelola Cuti</span>
                            </a>
                        </li><!-- .nk-menu-item -->
                        <li class="nk-menu-heading">
                            <h6 class="overline-title text-secondary-alt">Trash</h6>
                        </li><!-- .nk-menu-heading -->
                        <li class="nk-menu-item">
                            <a href="{{ route('admin.trashed') }}" class="nk-menu-link">
                                <span class="nk-menu-icon"><em class="icon ni ni-trash"></em></span>
                                <span class="nk-menu-text">Recycle Bin</span>
                            </a>
                        </li><!-- .nk-menu-item -->
                        <li class="nk-menu-item">
                            <a href="#" class="nk-menu-link">
                                <span class="nk-menu-icon"><em class="icon ni ni-location"></em></span>
                                <span class="nk-menu-text">Coordinate</span>
                            </a>
                        </li><!-- .nk-menu-item -->
                </div><!-- .nk-sidebar-menu -->
                {{-- <div class="nk-sidebar-footer">
                    <ul class="nk-menu nk-menu-footer">
                    </ul><!-- .nk-footer-menu -->
                </div><!-- .nk-sidebar-footer --> --}}
            </div><!-- .nk-sidebar-content -->
        </div><!-- .nk-sidebar-body -->
    </div><!-- .nk-sidebar-element -->
</div>

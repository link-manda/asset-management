<!-- Start Sidebar -->
<aside class="app-menu" id="app-menu">
    <!-- Sidenav Menu Brand Logo -->
    <a class="logo-box sticky top-0 flex min-h-topbar-height items-center justify-start px-6 backdrop-blur-xs"
        href="{{ route('dashboard') }}">
        <!-- Light Brand Logo -->
        <div class="logo-light text-primary font-bold text-2xl">
           <i data-lucide="shield-check" class="inline-block size-6 me-2"></i> ASSET MGMT
        </div>
        <!-- Dark Brand Logo -->
        <div class="logo-dark text-white font-bold text-2xl">
           <i data-lucide="shield-check" class="inline-block size-6 me-2 text-primary"></i> ASSET MGMT
        </div>
    </a>
    <!-- Sidenav Menu Toggle Button -->
    <div class="absolute top-0 end-5 flex h-topbar items-center justify">
        <button class="" id="button-hover-toggle">
            <i class="iconify tabler--circle size-5"></i>
        </button>
    </div>
    <!-- Sidenav Menu Item Link -->
    <div class="relative min-h-0 flex-grow">
        <div class="size-full" data-simplebar="">
            <ul class="side-nav p-3 hs-accordion-group">
                <li class="menu-title">
                    <span>Utama</span>
                </li>
                <li class="menu-item">
                    <a class="menu-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <span class="menu-icon"><i data-lucide="monitor-dot"></i></span>
                        <span class="menu-text"> Dashboard </span>
                    </a>
                </li>

                <li class="menu-title">
                    <span>Manajemen Aset</span>
                </li>

                @php
                    $isAssetActive = request()->is('assets*', 'maintenances*', 'assignments*');
                @endphp
                <li class="menu-item hs-accordion {{ $isAssetActive ? 'active' : '' }}">
                    <a class="hs-accordion-toggle menu-link {{ $isAssetActive ? 'active' : '' }}" href="javascript:void(0)">
                        <span class="menu-icon"><i data-lucide="package"></i></span>
                        <span class="menu-text"> Asset </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul class="sub-menu hs-accordion-content {{ $isAssetActive ? '' : 'hidden' }}">
                        <li class="menu-item">
                            <a class="menu-link {{ request()->routeIs('assets.index') ? 'active' : '' }}" href="{{ route('assets.index') }}">
                                <span class="menu-text"> Daftar Asset </span>
                            </a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link {{ request()->routeIs('assets.create') ? 'active' : '' }}" href="{{ route('assets.create') }}">
                                <span class="menu-text"> Tambah Asset </span>
                            </a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link {{ request()->routeIs('assignments.index') ? 'active' : '' }}" href="{{ route('assignments.index') }}">
                                <span class="menu-text"> Riwayat Penugasan </span>
                            </a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link {{ request()->routeIs('maintenances.index') ? 'active' : '' }}" href="{{ route('maintenances.index') }}">
                                <span class="menu-text"> Maintenance Aset </span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="menu-title">
                    <span>Master Data</span>
                </li>

                <li class="menu-item">
                    <a class="menu-link {{ request()->routeIs('categories.*') ? 'active' : '' }}" href="{{ route('categories.index') }}">
                        <span class="menu-icon"><i data-lucide="tags"></i></span>
                        <div class="menu-text">Kategori Aset</div>
                    </a>
                </li>

                <li class="menu-item">
                    <a class="menu-link {{ request()->routeIs('locations.*') ? 'active' : '' }}" href="{{ route('locations.index') }}">
                        <span class="menu-icon"><i data-lucide="map-pin"></i></span>
                        <div class="menu-text">Lokasi Aset</div>
                    </a>
                </li>

                <li class="menu-item">
                    <a class="menu-link {{ request()->routeIs('uoms.*') ? 'active' : '' }}" href="{{ route('uoms.index') }}">
                        <span class="menu-icon"><i data-lucide="box"></i></span>
                        <div class="menu-text">Satuan (UoM)</div>
                    </a>
                </li>

                <li class="menu-item">
                    <a class="menu-link {{ request()->routeIs('divisions.*') ? 'active' : '' }}" href="{{ route('divisions.index') }}">
                        <span class="menu-icon"><i data-lucide="building-2"></i></span>
                        <div class="menu-text">Divisi</div>
                    </a>
                </li>

                <li class="menu-item">
                    <a class="menu-link {{ request()->routeIs('departments.*') ? 'active' : '' }}" href="{{ route('departments.index') }}">
                        <span class="menu-icon"><i data-lucide="network"></i></span>
                        <div class="menu-text">Departemen</div>
                    </a>
                </li>

                <li class="menu-title">
                    <span>Sistem</span>
                </li>

                <li class="menu-item">
                    <a class="menu-link {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                        <span class="menu-icon"><i data-lucide="users"></i></span>
                        <div class="menu-text">User Management</div>
                    </a>
                </li>

                <li class="menu-item">
                    <a class="menu-link {{ request()->routeIs('settings.*') ? 'active' : '' }}" href="{{ route('settings.index') }}">
                        <span class="menu-icon"><i data-lucide="settings"></i></span>
                        <div class="menu-text">Pengaturan</div>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</aside>
<!-- End Sidebar -->

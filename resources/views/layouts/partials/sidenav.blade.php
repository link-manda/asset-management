<!-- Start Sidebar -->
<aside class="app-menu" id="app-menu">
    <!-- Sidenav Menu Brand Logo -->
    <a class="logo-box sticky top-0 flex min-h-topbar-height items-center backdrop-blur-xs"
        href="{{ route('dashboard') }}">
        <!-- Logo Large (Full) -->
        <div class="logo-lg text-default-900 dark:text-white font-bold text-2xl flex items-center px-6 w-full">
           <i data-lucide="shield-check" class="size-6 me-2 text-primary"></i> ASSET MGMT
        </div>
        <!-- Logo Small (Icon Only) -->
        <div class="logo-sm text-default-900 dark:text-white font-bold text-2xl flex items-center justify-center w-full">
           <i data-lucide="shield-check" class="size-6 text-primary"></i>
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
                    <span>Main Menu</span>
                </li>
                <li class="menu-item">
                    <a class="menu-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <span class="menu-icon"><i data-lucide="monitor-dot"></i></span>
                        <span class="menu-text"> Dashboard </span>
                    </a>
                </li>

                <li class="menu-title">
                    <span>Asset Operations</span>
                </li>

                @php
                    $isAssetActive = request()->is('assets*', 'inventory*', 'maintenances*', 'assignments*', 'disposals*');
                @endphp
                <li class="menu-item hs-accordion {{ $isAssetActive ? 'active' : '' }}">
                    <a class="hs-accordion-toggle menu-link {{ $isAssetActive ? 'active' : '' }}" href="javascript:void(0)">
                        <span class="menu-icon"><i data-lucide="package"></i></span>
                        <span class="menu-text"> Asset Management </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul class="sub-menu hs-accordion-content {{ $isAssetActive ? '' : 'hidden' }}">
                        <li class="menu-item">
                            <a class="menu-link {{ request()->routeIs('assets.index', 'assets.show', 'assets.edit') ? 'active' : '' }}" href="{{ route('assets.index') }}">
                                <span class="menu-text"> Master Catalog </span>
                            </a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link {{ request()->routeIs('inventory.*') ? 'active' : '' }}" href="{{ route('inventory.index') }}">
                                <span class="menu-text"> Physical Units </span>
                            </a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link {{ request()->routeIs('assignments.*') ? 'active' : '' }}" href="{{ route('assignments.index') }}">
                                <span class="menu-text"> Assignment History </span>
                            </a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link {{ request()->routeIs('maintenances.*') ? 'active' : '' }}" href="{{ route('maintenances.index') }}">
                                <span class="menu-text"> Maintenance Log </span>
                            </a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-link {{ request()->routeIs('disposals.*') ? 'active' : '' }}" href="{{ route('disposals.index') }}">
                                <span class="menu-text"> Asset Disposal </span>
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
                        <div class="menu-text">Asset Categories</div>
                    </a>
                </li>

                <li class="menu-item">
                    <a class="menu-link {{ request()->routeIs('locations.*') ? 'active' : '' }}" href="{{ route('locations.index') }}">
                        <span class="menu-icon"><i data-lucide="map-pin"></i></span>
                        <div class="menu-text">Locations</div>
                    </a>
                </li>

                <li class="menu-item">
                    <a class="menu-link {{ request()->routeIs('uoms.*') ? 'active' : '' }}" href="{{ route('uoms.index') }}">
                        <span class="menu-icon"><i data-lucide="box"></i></span>
                        <div class="menu-text">Units of Measurement</div>
                    </a>
                </li>

                <li class="menu-item">
                    <a class="menu-link {{ request()->routeIs('divisions.*') ? 'active' : '' }}" href="{{ route('divisions.index') }}">
                        <span class="menu-icon"><i data-lucide="building-2"></i></span>
                        <div class="menu-text">Divisions</div>
                    </a>
                </li>

                <li class="menu-item">
                    <a class="menu-link {{ request()->routeIs('departments.*') ? 'active' : '' }}" href="{{ route('departments.index') }}">
                        <span class="menu-icon"><i data-lucide="network"></i></span>
                        <div class="menu-text">Departments</div>
                    </a>
                </li>

                <li class="menu-title">
                    <span>Reports</span>
                </li>

                <li class="menu-item">
                    <a class="menu-link {{ request()->routeIs('reports.general') ? 'active' : '' }}" href="{{ route('reports.general') }}">
                        <span class="menu-icon"><i data-lucide="pie-chart"></i></span>
                        <div class="menu-text">General Asset Report</div>
                    </a>
                </li>

                <li class="menu-item">
                    <a class="menu-link {{ request()->routeIs('reports.depreciation') ? 'active' : '' }}" href="{{ route('reports.depreciation') }}">
                        <span class="menu-icon"><i data-lucide="line-chart"></i></span>
                        <div class="menu-text">Depreciation Report</div>
                    </a>
                </li>

                <li class="menu-item">
                    <a class="menu-link {{ request()->routeIs('reports.summary') ? 'active' : '' }}" href="{{ route('reports.summary') }}">
                        <span class="menu-icon"><i data-lucide="bar-chart-3"></i></span>
                        <div class="menu-text">Asset Summary</div>
                    </a>
                </li>

                <li class="menu-title">
                    <span>System</span>
                </li>

                <li class="menu-item">
                    <a class="menu-link {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                        <span class="menu-icon"><i data-lucide="users"></i></span>
                        <div class="menu-text">User Management</div>
                    </a>
                </li>

                <li class="menu-item">
                    <a class="menu-link {{ request()->routeIs('activity-logs.*') ? 'active' : '' }}" href="{{ route('activity-logs.index') }}">
                        <span class="menu-icon"><i data-lucide="history"></i></span>
                        <div class="menu-text">Audit Trail (Log)</div>
                    </a>
                </li>

                <li class="menu-item">
                    <a class="menu-link {{ request()->routeIs('settings.*') ? 'active' : '' }}" href="{{ route('settings.index') }}">
                        <span class="menu-icon"><i data-lucide="settings"></i></span>
                        <div class="menu-text">Settings</div>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</aside>
<!-- End Sidebar -->

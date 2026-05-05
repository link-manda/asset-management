<!-- Topbar Start -->
<div
    class="app-header min-h-topbar-height flex items-center sticky top-0 z-30 bg-(--topbar-background) border-b border-default-200">
    <div class="w-full flex items-center justify-between px-6">
        <div class="flex items-center gap-5">
            <!-- Sidenav Menu Toggle Button -->
            <button class="btn btn-icon size-8 hover:bg-default-150 rounded" id="button-toggle-menu">
                <i class="iconify lucide--align-left text-xl"></i>
            </button>
            <!-- Topbar Search -->
            <div class="lg:flex hidden items-center relative">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                    <i class="iconify tabler--search text-base"></i>
                </div>
                <input class="form-input px-12 text-sm rounded border-transparent focus:border-transparent w-72"
                       id="topbar-search" placeholder="Search assets, barcode, etc..." type="search"/>
                <div class="absolute inset-y-0 end-0 flex items-center pe-3 gap-1">
                    <button class="btn btn-icon btn-sm text-default-500 hover:text-primary rounded-md" type="button" title="Scan Barcode">
                        <i class="iconify tabler--barcode text-lg"></i>
                    </button>
                    <span class="font-medium text-default-400 text-xs px-1.5 py-0.5 rounded bg-default-100">⌘ K</span>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <!-- Quick Add Button -->
            <div class="topbar-item hidden sm:flex">
                <a href="{{ route('assets.index') }}" class="btn btn-sm bg-primary text-white rounded-full flex items-center gap-1.5 px-3 shadow-sm hover:shadow-md transition-all">
                    <i class="size-4" data-lucide="plus"></i>
                    <span class="hidden md:inline-block font-medium">New Asset</span>
                </a>
            </div>
            
            <!-- Light/Dark Mode Button -->
            <div class="topbar-item">
                <button class="btn btn-icon size-8 hover:bg-default-150 transition-[scale,background] rounded-full"
                        id="light-dark-mode" type="button">
                    <i class="iconify tabler--moon text-xl absolute dark:scale-0 dark:-rotate-90 scale-100 rotate-0 transition-all duration-200"></i>
                    <i class="iconify tabler--sun text-xl absolute dark:scale-100 dark:rotate-0 scale-0 rotate-90 transition-all duration-200"></i>
                </button>
            </div>
            <!-- Notification Button -->
            <div class="topbar-item hs-dropdown [--auto-close:inside] relative inline-flex">
                <button aria-expanded="false" aria-haspopup="menu" aria-label="Dropdown"
                        class="hs-dropdown-toggle btn btn-icon size-8 hover:bg-default-150 rounded-full relative"
                        type="button">
                    <i class="size-4.5" data-lucide="bell-ring"></i>
                    <span class="absolute end-0 top-0 size-1.5 bg-primary/90 rounded-full"></span>
                </button>
                <div class="hs-dropdown-menu max-w-100 p-0" role="menu">
                    <!-- Header -->
                    <div class="p-4 border-b border-default-200">
                        <div class="flex items-center gap-2">
                            <h3 class="text-base text-default-800">Notifications</h3>
                            <span class="size-5 font-semibold bg-orange-500 rounded text-white flex items-center justify-center text-xs">3</span>
                        </div>
                    </div>
                    <!-- Notification Content -->
                    <div class="h-80" data-simplebar="">
                        <a class="flex gap-3 p-4 items-center hover:bg-default-150 border-b border-default-100" href="#">
                            <div>
                                <div class="size-10 rounded-md bg-warning/10 flex justify-center items-center">
                                    <i class="size-5 text-warning" data-lucide="alert-triangle"></i>
                                </div>
                            </div>
                            <div class="flex justify-between w-full text-sm">
                                <div>
                                    <h6 class="mb-1 font-medium text-default-800"><b>Low Stock Alert</b></h6>
                                    <p class="text-xs text-default-500 mb-1">Toner Cartridge HP is running low (2 left).</p>
                                    <p class="flex items-center gap-1 text-default-400 text-xs">
                                        <i class="align-middle size-3.5" data-lucide="clock"></i>
                                        <span>Just now</span>
                                    </p>
                                </div>
                            </div>
                        </a>
                        <a class="flex gap-3 p-4 items-center hover:bg-default-150 border-b border-default-100" href="#">
                            <div>
                                <div class="size-10 rounded-md bg-info/10 flex justify-center items-center">
                                    <i class="size-5 text-info" data-lucide="wrench"></i>
                                </div>
                            </div>
                            <div class="flex justify-between w-full text-sm">
                                <div>
                                    <h6 class="mb-1 font-medium text-default-800"><b>Maintenance Due</b></h6>
                                    <p class="text-xs text-default-500 mb-1">Asset <b>AC-001</b> scheduled for maintenance tomorrow.</p>
                                    <p class="flex items-center gap-1 text-default-400 text-xs">
                                        <i class="align-middle size-3.5" data-lucide="clock"></i>
                                        <span>2 hours ago</span>
                                    </p>
                                </div>
                            </div>
                        </a>
                        <a class="flex gap-3 p-4 items-center hover:bg-default-150" href="#">
                            <div>
                                <div class="size-10 rounded-md bg-primary/10 flex justify-center items-center">
                                    <i class="size-5 text-primary" data-lucide="arrow-right-left"></i>
                                </div>
                            </div>
                            <div class="flex justify-between w-full text-sm">
                                <div>
                                    <h6 class="mb-1 font-medium text-default-800"><b>New Assignment Request</b></h6>
                                    <p class="text-xs text-default-500 mb-1">John Doe requested <b>MacBook Pro M2</b>.</p>
                                    <p class="flex items-center gap-1 text-default-400 text-xs">
                                        <i class="align-middle size-3.5" data-lucide="clock"></i>
                                        <span>Yesterday</span>
                                    </p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <!-- Footer -->
                    <div class="flex items-center justify-between p-4 border-t border-default-200">
                        <a class="text-sm font-medium text-default-900" href="#!">Manage Notification</a>
                        <button class="btn btn-sm text-white bg-primary" type="button">
                            View All
                            <i class="size-4" data-lucide="move-right"></i>
                        </button>
                    </div>
                </div>
            </div>
            <!-- Profile Dropdown Button -->
            <!-- Profile Dropdown Button -->
            <div class="topbar-item hs-dropdown relative inline-flex">
                @auth
                    <button aria-expanded="false" aria-haspopup="menu" aria-label="Dropdown"
                            class="cursor-pointer">
                        @php
                            $colors = ['bg-primary/10 text-primary', 'bg-success/10 text-success', 'bg-info/10 text-info', 'bg-warning/10 text-warning', 'bg-danger/10 text-danger'];
                            $userColor = $colors[auth()->id() % count($colors)];
                        @endphp
                        <div class="hs-dropdown-toggle size-9.5 rounded-full {{ $userColor }} flex items-center justify-center font-bold text-xs border border-default-200">
                            {{ substr(auth()->user()->name, 0, 2) }}
                        </div>
                    </button>
                    <div aria-labelledby="hs-dropdown-with-icons" aria-orientation="vertical"
                         class="hs-dropdown-menu min-w-48" role="menu">
                        <div class="p-2">
                            <h6 class="mb-2 text-default-500 uppercase text-[10px] font-bold tracking-wider">User Session</h6>
                            <div class="flex gap-3">
                                <div class="relative inline-block">
                                    <div class="size-12 rounded {{ $userColor }} flex items-center justify-center font-bold text-lg">
                                        {{ substr(auth()->user()->name, 0, 2) }}
                                    </div>
                                    <span class="-top-1 -end-1 absolute w-2.5 h-2.5 bg-green-400 border-2 border-white rounded-full"></span>
                                </div>
                                <div>
                                    <h6 class="mb-1 text-sm font-semibold text-default-800">{{ auth()->user()->name }}</h6>
                                    <p class="text-xs text-default-500 uppercase font-bold">{{ auth()->user()->role }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="border-t border-t-default-200 -mx-2 my-2"></div>
                        <div class="flex flex-col gap-y-1">
                            <a class="flex items-center gap-x-3.5 py-1.5 font-medium px-3 text-default-600 hover:bg-default-150 rounded"
                               href="{{ route('users.edit', auth()->user()) }}">
                                <i class="size-4" data-lucide="user"></i>
                                My Profile
                            </a>
                            <a class="flex items-center gap-x-3.5 py-1.5 font-medium px-3 text-default-600 hover:bg-default-150 rounded"
                               href="{{ route('settings.index') }}">
                                <i class="size-4" data-lucide="settings"></i>
                                Account Settings
                            </a>
                            <div class="border-t border-default-200 -mx-2 my-1"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-x-3.5 py-1.5 font-medium px-3 text-danger hover:bg-danger/10 rounded">
                                    <i class="size-4" data-lucide="log-out"></i>
                                    Sign Out
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('dashboard') }}" class="btn btn-sm bg-primary/10 text-primary rounded-full">
                        <i class="size-4 me-1" data-lucide="log-in"></i> Guest
                    </a>
                @endauth
            </div>
        </div>
    </div>
</div>
<!-- Topbar End -->

@extends('layouts.app')

@section('title', 'User Management')

@section('content')
    @include('layouts.partials/page-title', ['subtitle' => 'System', 'title' => 'User Management'])

    <div class="grid grid-cols-1 gap-5 mb-5">
        <div class="card">
            <div class="card-header flex justify-between items-center">
                <div class="flex gap-3 items-center">
                    <div class="relative">
                        <input class="ps-11 form-input form-input-sm w-64" placeholder="Search users..." type="text" />
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3">
                            <i class="size-3.5 flex items-center text-default-500" data-lucide="search"></i>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    @can('create users')
                    <a href="{{ route('users.create') }}" class="btn btn-sm bg-primary text-white">
                        <i class="size-4 me-1" data-lucide="user-plus"></i> Add User
                    </a>
                    @endcan
                </div>
            </div>
            <div class="flex flex-col">
                <div class="overflow-x-auto">
                    <div class="min-w-full inline-block align-middle">
                        <div class="overflow-hidden">
                            <table class="min-w-full divide-y divide-default-200">
                                <thead class="bg-default-100">
                                    <tr class="text-xs font-semibold text-default-600 uppercase tracking-wider">
                                        <th class="px-4 py-3 text-start">User / Account</th>
                                        <th class="px-4 py-3 text-start">Email Address</th>
                                        <th class="px-4 py-3 text-start">Org. Structure</th>
                                        <th class="px-4 py-3 text-start">Role</th>
                                        <th class="px-4 py-3 text-start" scope="col">Joined Date</th>
                                        @canany(['edit users', 'delete users'])
                                        <th class="px-4 py-3 text-center" scope="col">Actions</th>
                                        @endcanany
                                        </tr>

                                </thead>
                                <tbody class="divide-y divide-default-200">
                                    @foreach ($users as $user)
                                        <tr class="text-default-800 hover:bg-default-50 transition-all">
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <div class="flex items-center gap-3">
                                                    @php
                                                        $colors = ['bg-primary/10 text-primary', 'bg-success/10 text-success', 'bg-info/10 text-info', 'bg-warning/10 text-warning', 'bg-danger/10 text-danger'];
                                                        $colorClass = $colors[$user->id % count($colors)];
                                                    @endphp
                                                    <div class="size-10 {{ $colorClass }} rounded-full flex items-center justify-center font-bold text-sm uppercase">
                                                        {{ substr($user->name, 0, 2) }}
                                                    </div>
                                                    <div>
                                                        <h6 class="text-sm font-bold text-default-800">{{ $user->name }}</h6>
                                                        <p class="text-[10px] text-default-500 font-medium">ID: #USR-{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-default-600">
                                                {{ $user->email }}
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <div>
                                                    <p class="text-xs font-bold text-default-800">{{ $user->department?->division?->name ?? '-' }}</p>
                                                    <p class="text-[10px] text-default-500">{{ $user->department?->name ?? '-' }}</p>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                @foreach($user->roles as $role)
                                                    @php
                                                        $class = match($role->name) {
                                                            'Super Admin' => 'bg-danger/10 text-danger border-danger/20',
                                                            'Manager' => 'bg-primary/10 text-primary border-primary/20',
                                                            'Staff' => 'bg-success/10 text-success border-success/20',
                                                            default => 'bg-default-100 text-default-500',
                                                        };
                                                    @endphp
                                                    <span class="inline-flex items-center gap-x-1.5 py-1 px-2.5 rounded border text-[10px] font-bold uppercase tracking-wider {{ $class }}">
                                                        {{ $role->name }}
                                                    </span>
                                                @endforeach
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-default-600">
                                                {{ $user->created_at->format('d M Y') }}
                                            </td>
                                            @canany(['edit users', 'delete users'])
                                            <td class="px-4 py-3 text-center">
                                                <div class="hs-dropdown relative inline-flex">
                                                    <button aria-expanded="false" aria-haspopup="menu" aria-label="Dropdown"
                                                        class="hs-dropdown-toggle btn size-8 bg-default-100 hover:bg-default-600 text-default-500 hover:text-white rounded-md transition-all"
                                                        hs-dropdown-placement="bottom-end" type="button">
                                                        <i class="size-4" data-lucide="more-horizontal"></i>
                                                    </button>
                                                    <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-32 z-50 bg-white shadow-lg rounded-lg p-2 mt-2 border border-default-200 dark:bg-default-50" role="menu">
                                                        @can('edit users')
                                                        <a class="flex items-center gap-2 py-2 px-3 text-sm text-default-600 hover:bg-default-100 rounded-md font-medium"
                                                            href="{{ route('users.edit', $user) }}">
                                                            <i class="size-4" data-lucide="user-cog"></i> Edit Account
                                                        </a>
                                                        @endcan
                                                        
                                                        @can('delete users')
                                                        <div class="h-px bg-default-200 my-1"></div>
                                                        <form action="{{ route('users.destroy', $user) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="w-full flex items-center gap-2 py-2 px-3 text-sm text-danger hover:bg-danger/10 rounded-md font-medium delete-confirm"
                                                                data-name="User {{ $user->name }}"
                                                                @if($user->id === auth()->id()) disabled title="You cannot delete your own account" @endif>
                                                                <i class="size-4" data-lucide="user-minus"></i> Delete User
                                                            </button>
                                                        </form>
                                                        @endcan
                                                    </div>
                                                </div>
                                            </td>
                                            @endcanany
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card-footer border-t border-default-200 p-4">
                    {{ $users->links('vendor.pagination.tailwind-custom') }}
                </div>
            </div>
        </div>
    </div>
@endsection

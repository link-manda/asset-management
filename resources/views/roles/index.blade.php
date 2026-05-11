@extends('layouts.app')

@section('title', 'Role Management')

@section('content')
    @include('layouts.partials/page-title', ['subtitle' => 'System', 'title' => 'Role Management'])

    <div class="grid grid-cols-1 gap-5 mb-5">
        <div class="card">
            <div class="card-header flex justify-between items-center">
                <div class="flex gap-3 items-center">
                    <h4 class="card-title">User Roles</h4>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('roles.create') }}" class="btn btn-sm bg-primary text-white">
                        <i class="size-4 me-1" data-lucide="plus-circle"></i> Add Role
                    </a>
                </div>
            </div>
            <div class="flex flex-col">
                <div class="overflow-x-auto">
                    <div class="min-w-full inline-block align-middle">
                        <div class="overflow-hidden">
                            <table class="min-w-full divide-y divide-default-200">
                                <thead class="bg-default-100">
                                    <tr class="text-xs font-semibold text-default-600 uppercase tracking-wider">
                                        <th class="px-4 py-3 text-start">Role Name</th>
                                        <th class="px-4 py-3 text-start">Permissions Count</th>
                                        <th class="px-4 py-3 text-start">Users Count</th>
                                        <th class="px-4 py-3 text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-default-200">
                                    @foreach ($roles as $role)
                                        <tr class="text-default-800 hover:bg-default-50 transition-all">
                                            <td class="px-4 py-3 whitespace-nowrap font-medium">
                                                {{ $role->name }}
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <span class="inline-flex items-center py-0.5 px-2 rounded-full text-xs font-medium bg-info/10 text-info">
                                                    {{ $role->permissions->count() }} Permissions
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-default-600">
                                                {{ $role->users->count() }} Users
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <div class="hs-dropdown relative inline-flex">
                                                    <button aria-expanded="false" aria-haspopup="menu" aria-label="Dropdown"
                                                        class="hs-dropdown-toggle btn size-8 bg-default-100 hover:bg-default-600 text-default-500 hover:text-white rounded-md transition-all"
                                                        hs-dropdown-placement="bottom-end" type="button">
                                                        <i class="size-4" data-lucide="more-horizontal"></i>
                                                    </button>
                                                    <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-32 z-50 bg-white shadow-lg rounded-lg p-2 mt-2 border border-default-200 dark:bg-default-50" role="menu">
                                                        @if($role->name !== 'Super Admin')
                                                            <a class="flex items-center gap-2 py-2 px-3 text-sm text-default-600 hover:bg-default-100 rounded-md font-medium"
                                                                href="{{ route('roles.edit', $role) }}">
                                                                <i class="size-4" data-lucide="edit"></i> Edit Role
                                                            </a>
                                                        @endif
                                                        
                                                        @if(!in_array($role->name, ['Super Admin', 'Staff', 'Manager']))
                                                            <div class="h-px bg-default-200 my-1"></div>
                                                            <form action="{{ route('roles.destroy', $role) }}" method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="w-full flex items-center gap-2 py-2 px-3 text-sm text-danger hover:bg-danger/10 rounded-md font-medium delete-confirm"
                                                                    data-name="Role {{ $role->name }}">
                                                                    <i class="size-4" data-lucide="trash-2"></i> Delete Role
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

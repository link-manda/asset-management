@extends('layouts.app')

@section('title', 'Edit Role')

@section('content')
    @include('layouts.partials/page-title', ['subtitle' => 'System', 'title' => 'Edit Role'])

    <div class="grid grid-cols-1 gap-5">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Role Information: {{ $role->name }}</h4>
            </div>
            <div class="p-6">
                <form action="{{ route('roles.update', $role) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 gap-6 mb-6">
                        <div>
                            <label for="name" class="text-sm font-medium text-default-900 mb-2 block">Role Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $role->name) }}" 
                                class="form-input w-full @error('name') border-danger @enderror" placeholder="Enter role name">
                            @error('name')
                                <p class="text-danger text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="text-sm font-medium text-default-900 mb-4 block">Permissions</label>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($groupedPermissions as $module => $permissions)
                                <div class="border border-default-200 rounded-lg p-4">
                                    <h5 class="text-sm font-bold text-default-800 uppercase mb-3 border-b border-default-200 pb-2">
                                        {{ ucfirst($module) }}
                                    </h5>
                                    <div class="space-y-2">
                                        @foreach($permissions as $permission)
                                            <div class="flex items-center">
                                                <input type="checkbox" name="permissions[]" value="{{ $permission->name }}" 
                                                    id="perm-{{ $permission->id }}"
                                                    class="form-checkbox rounded text-primary"
                                                    {{ in_array($permission->name, old('permissions', $rolePermissions)) ? 'checked' : '' }}>
                                                <label for="perm-{{ $permission->id }}" class="ms-2 text-sm text-default-600">
                                                    {{ ucfirst(explode(' ', $permission->name)[0]) }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @error('permissions')
                            <p class="text-danger text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end gap-2">
                        <a href="{{ route('roles.index') }}" class="btn border-default-200 text-default-600">Cancel</a>
                        <button type="submit" class="btn bg-primary text-white">Update Role</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

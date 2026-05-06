@extends('layouts.app')

@section('title', 'Edit User: ' . $user->name)

@section('content')
    @include('layouts.partials/page-title', ['subtitle' => 'System Management', 'title' => 'Edit User Account'])

    <div class="grid lg:grid-cols-12 grid-cols-1 gap-6">
        <div class="lg:col-span-8 col-span-1">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('users.update', $user) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="grid lg:grid-cols-2 grid-cols-1 gap-5 mb-5">
                            <div class="col-span-1">
                                <label class="inline-block mb-2 text-sm text-default-800 font-medium" for="name">Full Name</label>
                                <input class="form-input" id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required />
                            </div>
                            <div class="col-span-1">
                                <label class="inline-block mb-2 text-sm text-default-800 font-medium" for="email">Email Address</label>
                                <input class="form-input" id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required />
                            </div>
                        </div>

                        <div class="grid lg:grid-cols-2 grid-cols-1 gap-5 mb-5">
                            <div class="col-span-1">
                                <label class="inline-block mb-2 text-sm text-default-800 font-medium" for="role">User Role</label>
                                <select class="form-input" id="role" name="role" required>
                                    <option value="staff" {{ old('role', $user->role) == 'staff' ? 'selected' : '' }}>Staff</option>
                                    <option value="manager" {{ old('role', $user->role) == 'manager' ? 'selected' : '' }}>Manager</option>
                                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Administrator</option>
                                </select>
                            </div>
                            <div class="col-span-1">
                                <label class="inline-block mb-2 text-sm text-default-800 font-medium" for="department_id">Department</label>
                                <select class="form-input" id="department_id" name="department_id" required>
                                    @foreach($divisions as $division)
                                        <optgroup label="{{ $division->name }}">
                                            @foreach($division->departments as $dept)
                                                <option value="{{ $dept->id }}" {{ old('department_id', $user->department_id) == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="p-4 bg-primary/5 rounded-lg border border-primary/10 mb-8 mt-8">
                            <h6 class="text-xs font-bold text-primary uppercase tracking-widest mb-3">Update Security (Optional)</h6>
                            <div class="grid lg:grid-cols-2 grid-cols-1 gap-5">
                                <div class="col-span-1">
                                    <label class="inline-block mb-2 text-[11px] text-default-700 font-bold uppercase" for="password">New Password</label>
                                    <input class="form-input" id="password" name="password" placeholder="Leave blank to keep current" type="password" />
                                </div>
                                <div class="col-span-1">
                                    <label class="inline-block mb-2 text-[11px] text-default-700 font-bold uppercase" for="password_confirmation">Confirm New Password</label>
                                    <input class="form-input" id="password_confirmation" name="password_confirmation" placeholder="Repeat new password" type="password" />
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end gap-3">
                            <a href="{{ route('users.index') }}" class="btn border-default-200 text-default-600 px-6">Cancel</a>
                            <button type="submit" class="btn bg-primary text-white px-10 font-bold uppercase tracking-widest">Update Account</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="lg:col-span-4 col-span-1">
            <div class="card h-full">
                <div class="card-body">
                    <div class="flex flex-col items-center text-center p-5">
                        @php
                            $colors = ['bg-primary/10 text-primary', 'bg-success/10 text-success', 'bg-info/10 text-info', 'bg-warning/10 text-warning', 'bg-danger/10 text-danger'];
                            $userColor = $colors[$user->id % count($colors)];
                        @endphp
                        <div class="size-24 {{ $userColor }} rounded-full flex items-center justify-center font-bold text-3xl mb-4 border-4 border-white dark:border-default-50 shadow-lg">
                            {{ substr($user->name, 0, 2) }}
                        </div>
                        <h4 class="text-lg font-black text-default-800 uppercase tracking-tight">{{ $user->name }}</h4>
                        <p class="text-sm text-default-500 mb-6 font-medium">{{ $user->email }}</p>
                        
                        <div class="w-full space-y-3">
                            <div class="flex justify-between py-2 border-b border-dashed border-default-200">
                                <span class="text-xs text-default-400 font-bold uppercase">Account ID:</span>
                                <span class="text-xs font-black text-default-800">#USR-{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-dashed border-default-200">
                                <span class="text-xs text-default-400 font-bold uppercase">Joined Since:</span>
                                <span class="text-xs font-black text-default-800">{{ $user->created_at->format('d M Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

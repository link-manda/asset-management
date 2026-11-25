@extends('layouts.app')

@section('title', 'Add New User')

@section('content')
    @include('layouts.partials/page-title', ['subtitle' => 'System Management', 'title' => 'Create User'])

    <div class="grid lg:grid-cols-12 grid-cols-1 gap-6">
        <div class="lg:col-span-8 col-span-1">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('users.store') }}" method="POST">
                        @csrf
                        <div class="grid lg:grid-cols-2 grid-cols-1 gap-5 mb-5">
                            <div class="col-span-1">
                                <label class="inline-block mb-2 text-sm text-default-800 font-medium" for="name">Full Name</label>
                                <input class="form-input" id="name" name="name" placeholder="Enter name" type="text" value="{{ old('name') }}" required />
                            </div>
                            <div class="col-span-1">
                                <label class="inline-block mb-2 text-sm text-default-800 font-medium" for="email">Email Address</label>
                                <input class="form-input" id="email" name="email" placeholder="email@example.com" type="email" value="{{ old('email') }}" required />
                            </div>
                        </div>

                        <div class="grid lg:grid-cols-2 grid-cols-1 gap-5 mb-5">
                            <div class="col-span-1">
                                <label class="inline-block mb-2 text-sm text-default-800 font-medium" for="role">User Role</label>
                                <select class="form-input" id="role" name="role" required>
                                    <option value="staff" {{ old('role') == 'staff' ? 'selected' : '' }}>Staff</option>
                                    <option value="manager" {{ old('role') == 'manager' ? 'selected' : '' }}>Manager</option>
                                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrator</option>
                                </select>
                            </div>
                            <div class="col-span-1">
                                <label class="inline-block mb-2 text-sm text-default-800 font-medium" for="department_id">Department</label>
                                <select class="form-input" id="department_id" name="department_id" required>
                                    <option value="">-- Select Department --</option>
                                    @foreach($divisions as $division)
                                        <optgroup label="{{ $division->name }}">
                                            @foreach($division->departments as $dept)
                                                <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="grid lg:grid-cols-2 grid-cols-1 gap-5 mb-8">
                            <div class="col-span-1">
                                <label class="inline-block mb-2 text-sm text-default-800 font-medium" for="password">Password</label>
                                <input class="form-input" id="password" name="password" placeholder="Min 8 characters" type="password" required />
                            </div>
                            <div class="col-span-1">
                                <label class="inline-block mb-2 text-sm text-default-800 font-medium" for="password_confirmation">Confirm Password</label>
                                <input class="form-input" id="password_confirmation" name="password_confirmation" placeholder="Repeat password" type="password" required />
                            </div>
                        </div>

                        <div class="flex justify-end gap-3 pt-5 border-t border-default-200">
                            <a href="{{ route('users.index') }}" class="btn border-default-200 text-default-600 px-6">Cancel</a>
                            <button type="submit" class="btn bg-primary text-white px-10 font-bold uppercase tracking-widest">Create Account</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="lg:col-span-4 col-span-1">
            <div class="card">
                <div class="card-body">
                    <h6 class="text-sm font-bold text-default-800 mb-4 flex items-center gap-2">
                        <i class="size-4 text-primary" data-lucide="shield-check"></i>
                        Access Roles
                    </h6>
                    <ul class="space-y-4">
                        <li class="flex gap-3">
                            <div class="size-8 bg-danger/10 text-danger rounded flex items-center justify-center shrink-0 font-bold text-xs">A</div>
                            <div>
                                <p class="text-xs font-bold text-default-800 uppercase">Administrator</p>
                                <p class="text-[10px] text-default-500">Full system access including user and master data management.</p>
                            </div>
                        </li>
                        <li class="flex gap-3">
                            <div class="size-8 bg-primary/10 text-primary rounded flex items-center justify-center shrink-0 font-bold text-xs">M</div>
                            <div>
                                <p class="text-xs font-bold text-default-800 uppercase">Manager</p>
                                <p class="text-[10px] text-default-500">Can view reports, manage assets, and approve maintenance.</p>
                            </div>
                        </li>
                        <li class="flex gap-3">
                            <div class="size-8 bg-success/10 text-success rounded flex items-center justify-center shrink-0 font-bold text-xs">S</div>
                            <div>
                                <p class="text-xs font-bold text-default-800 uppercase">Staff</p>
                                <p class="text-[10px] text-default-500">Regular user, can borrow assets and request maintenance.</p>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

@extends('layouts.app')

@section('title', 'Edit Profil Pengguna')

@section('content')
    @include('layouts.partials/page-title', ['subtitle' => 'Manajemen Pengguna', 'title' => 'Edit User'])

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <div class="card">
                <div class="card-header flex items-center justify-between">
                    <h6 class="card-title text-base">Edit Data: {{ $user->name }}</h6>
                    <span class="px-2 py-0.5 rounded bg-default-100 text-[10px] font-bold text-default-500 uppercase">ID: #{{ $user->id }}</span>
                </div>
                <div class="p-6">
                    <form action="{{ route('users.update', $user) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
                            <div>
                                <label class="inline-block mb-2 text-sm text-default-800 font-medium">Nama Lengkap</label>
                                <input name="name" type="text" class="form-input @error('name') border-danger @enderror" value="{{ old('name', $user->name) }}" required>
                                @error('name') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="inline-block mb-2 text-sm text-default-800 font-medium">Email Address</label>
                                <input name="email" type="email" class="form-input @error('email') border-danger @enderror" value="{{ old('email', $user->email) }}" required>
                                @error('email') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="inline-block mb-2 text-sm text-default-800 font-medium">Role Sistem</label>
                                <select name="role" class="form-select @error('role') border-danger @enderror" required>
                                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Administrator</option>
                                    <option value="manager" {{ old('role', $user->role) == 'manager' ? 'selected' : '' }}>Manager</option>
                                    <option value="staff" {{ old('role', $user->role) == 'staff' ? 'selected' : '' }}>Staff / User</option>
                                </select>
                                @error('role') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="card-header -mx-6 px-6 py-4 border-y border-default-200 bg-default-50/50 mb-6">
                            <h6 class="card-title text-base">Struktur Organisasi</h6>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
                            <div>
                                <label class="inline-block mb-2 text-sm text-default-800 font-medium">Divisi</label>
                                <select id="division_select" class="form-select" required>
                                    <option value="" disabled>Pilih Divisi</option>
                                    @foreach($divisions as $division)
                                        <option value="{{ $division->id }}" {{ ($user->department?->division_id == $division->id) ? 'selected' : '' }}>{{ $division->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="inline-block mb-2 text-sm text-default-800 font-medium">Departemen</label>
                                <select id="department_select" name="department_id" class="form-select @error('department_id') border-danger @enderror" required>
                                    <option value="" disabled>Pilih Departemen</option>
                                    @if($user->department)
                                        @foreach($divisions->find($user->department->division_id)->departments as $dept)
                                            <option value="{{ $dept->id }}" {{ $user->department_id == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                                        @endforeach
                                    @else
                                        <option value="" disabled selected>Pilih Divisi Terlebih Dahulu</option>
                                    @endif
                                </select>
                                @error('department_id') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="card-header -mx-6 px-6 py-4 border-y border-default-200 bg-warning/5 mb-6">
                            <div class="flex items-center gap-2">
                                <i class="size-4 text-warning" data-lucide="alert-circle"></i>
                                <h6 class="card-title text-base">Ubah Kata Sandi (Opsional)</h6>
                            </div>
                            <p class="text-[11px] text-default-500 mt-1">Biarkan kosong jika Anda tidak ingin mengubah password pengguna.</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-8">
                            <div>
                                <label class="inline-block mb-2 text-sm text-default-800 font-medium">Password Baru</label>
                                <div class="relative">
                                    <input name="password" type="password" class="form-input @error('password') border-danger @enderror">
                                    <div class="absolute inset-y-0 end-0 flex items-center pe-3 pointer-events-none">
                                        <i class="size-4 text-default-400" data-lucide="lock"></i>
                                    </div>
                                </div>
                                @error('password') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="inline-block mb-2 text-sm text-default-800 font-medium">Konfirmasi Password Baru</label>
                                <div class="relative">
                                    <input name="password_confirmation" type="password" class="form-input">
                                    <div class="absolute inset-y-0 end-0 flex items-center pe-3 pointer-events-none">
                                        <i class="size-4 text-default-400" data-lucide="shield-check"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end gap-3 pt-6 border-t border-default-200">
                            <a href="{{ route('users.index') }}" class="btn border-0 text-danger bg-transparent hover:bg-danger/10">Batal</a>
                            <button type="submit" class="btn bg-primary text-white px-10">Update Data User</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="lg:col-span-1">
            <div class="card">
                <div class="p-6">
                    <div class="flex flex-col items-center text-center">
                        @php
                            $colors = ['bg-primary/10 text-primary', 'bg-success/10 text-success', 'bg-info/10 text-info', 'bg-warning/10 text-warning', 'bg-danger/10 text-danger'];
                            $colorClass = $colors[$user->id % count($colors)];
                        @endphp
                        <div class="size-24 {{ $colorClass }} rounded-full flex items-center justify-center font-bold text-2xl uppercase mb-4 shadow-sm border-4 border-white">
                            {{ substr($user->name, 0, 2) }}
                        </div>
                        <h6 class="text-lg font-bold text-default-800 mb-1">{{ $user->name }}</h6>
                        <p class="text-sm text-default-500 mb-4">{{ $user->email }}</p>
                        
                        <div class="w-full pt-4 border-t border-default-200 space-y-3 text-start">
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-default-500">Status Akun:</span>
                                <span class="text-success font-bold flex items-center gap-1">
                                    <span class="size-1.5 bg-success rounded-full"></span> Aktif
                                </span>
                            </div>
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-default-500">Terdaftar Sejak:</span>
                                <span class="text-default-800 font-medium">{{ $user->created_at->format('M Y') }}</span>
                            </div>
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-default-500">Aset Dipinjam:</span>
                                <span class="inline-flex items-center gap-x-1.5 py-0.5 px-2.5 rounded text-xs font-medium bg-primary/15 text-primary">{{ $user->assetAssignments()->whereNull('return_date')->count() }} Item</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const divisions = @json($divisions);
        const divisionSelect = document.getElementById('division_select');
        const departmentSelect = document.getElementById('department_select');

        divisionSelect.addEventListener('change', function() {
            const divisionId = this.value;
            const division = divisions.find(d => d.id == divisionId);

            // Clear departments
            departmentSelect.innerHTML = '<option value="" disabled selected>Pilih Departemen</option>';
            departmentSelect.disabled = false;

            if (division && division.departments) {
                division.departments.forEach(dept => {
                    const option = document.createElement('option');
                    option.value = dept.id;
                    option.textContent = dept.name;
                    departmentSelect.appendChild(option);
                });
            }
        });
    });
</script>
@endpush

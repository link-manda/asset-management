@extends('layouts.app')

@section('title', 'Tambah Pengguna Baru')

@section('content')
    @include('layouts.partials/page-title', ['subtitle' => 'Manajemen Pengguna', 'title' => 'Tambah User'])

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title text-base">Informasi Pengguna</h6>
                </div>
                <div class="p-6">
                    <form action="{{ route('users.store') }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
                            <div>
                                <label class="inline-block mb-2 text-sm text-default-800 font-medium">Nama Lengkap</label>
                                <input name="name" type="text" class="form-input @error('name') border-danger @enderror" value="{{ old('name') }}" placeholder="Contoh: John Doe" required>
                                @error('name') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="inline-block mb-2 text-sm text-default-800 font-medium">Email Address</label>
                                <input name="email" type="email" class="form-input @error('email') border-danger @enderror" value="{{ old('email') }}" placeholder="john@example.com" required>
                                @error('email') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="inline-block mb-2 text-sm text-default-800 font-medium">Role Sistem</label>
                                <select name="role" class="form-select @error('role') border-danger @enderror" required>
                                    <option value="" disabled selected>Pilih Role</option>
                                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrator</option>
                                    <option value="manager" {{ old('role') == 'manager' ? 'selected' : '' }}>Manager</option>
                                    <option value="staff" {{ old('role') == 'staff' ? 'selected' : '' }}>Staff / User</option>
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
                                    <option value="" disabled selected>Pilih Divisi</option>
                                    @foreach($divisions as $division)
                                        <option value="{{ $division->id }}">{{ $division->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="inline-block mb-2 text-sm text-default-800 font-medium">Departemen</label>
                                <select id="department_select" name="department_id" class="form-select @error('department_id') border-danger @enderror" required disabled>
                                    <option value="" disabled selected>Pilih Divisi Terlebih Dahulu</option>
                                </select>
                                @error('department_id') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="card-header -mx-6 px-6 py-4 border-y border-default-200 bg-default-50/50 mb-6">
                            <h6 class="card-title text-base">Kata Sandi</h6>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-8">
                            <div>
                                <label class="inline-block mb-2 text-sm text-default-800 font-medium">Password Baru</label>
                                <div class="relative">
                                    <input name="password" type="password" class="form-input @error('password') border-danger @enderror" required>
                                    <div class="absolute inset-y-0 end-0 flex items-center pe-3 pointer-events-none">
                                        <i class="size-4 text-default-400" data-lucide="lock"></i>
                                    </div>
                                </div>
                                @error('password') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="inline-block mb-2 text-sm text-default-800 font-medium">Konfirmasi Password</label>
                                <div class="relative">
                                    <input name="password_confirmation" type="password" class="form-input" required>
                                    <div class="absolute inset-y-0 end-0 flex items-center pe-3 pointer-events-none">
                                        <i class="size-4 text-default-400" data-lucide="shield-check"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end gap-3 pt-6 border-t border-default-200">
                            <a href="{{ route('users.index') }}" class="btn border-0 text-danger bg-transparent hover:bg-danger/10">Batal</a>
                            <button type="submit" class="btn bg-primary text-white px-10">Simpan Pengguna</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="lg:col-span-1">
            <div class="card">
                <div class="p-6">
                    <div class="flex flex-col items-center text-center">
                        <div class="size-20 bg-primary/10 text-primary rounded-full flex items-center justify-center mb-4">
                            <i class="size-10" data-lucide="user-cog"></i>
                        </div>
                        <h6 class="text-lg font-bold text-default-800 mb-1">Manajemen Akses</h6>
                        <p class="text-sm text-default-500 mb-6">Tentukan tingkat otorisasi pengguna dalam sistem inventaris.</p>

                        <div class="w-full space-y-4">
                            <div class="p-3 bg-danger/5 border border-danger/10 rounded-lg text-start">
                                <h6 class="text-xs font-bold text-danger uppercase mb-1">Administrator</h6>
                                <p class="text-[11px] text-default-600">Akses penuh ke seluruh sistem, termasuk pengaturan dan manajemen pengguna.</p>
                            </div>
                            <div class="p-3 bg-primary/5 border border-primary/10 rounded-lg text-start">
                                <h6 class="text-xs font-bold text-primary uppercase mb-1">Manager</h6>
                                <p class="text-[11px] text-default-600">Akses ke modul aset, penugasan, dan maintenance tanpa pengaturan sistem.</p>
                            </div>
                            <div class="p-3 bg-success/5 border border-success/10 rounded-lg text-start">
                                <h6 class="text-xs font-bold text-success uppercase mb-1">Staff / User</h6>
                                <p class="text-[11px] text-default-600">Akses terbatas untuk melihat aset dan membuat riwayat penugasan.</p>
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

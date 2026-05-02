@extends('layouts.app')

@section('title', 'Tambah Departemen')

@section('content')
    @include('layouts.partials/page-title', ['subtitle' => 'Master Data', 'title' => 'Tambah Departemen'])

    <div class="grid lg:grid-cols-12 grid-cols-1 gap-6">
        <div class="lg:col-span-9 col-span-1">
            <div class="card">
                <div class="card-body">
                    <h6 class="mb-4 card-title text-base">Form Departemen Baru</h6>
                    
                    <form action="{{ route('departments.store') }}" method="POST">
                        @csrf
                        <div class="grid lg:grid-cols-2 grid-cols-1 gap-5 mb-5">
                            <div class="col-span-1">
                                <label for="division_id" class="inline-block mb-2 text-sm text-default-800 font-medium">Divisi</label>
                                <select name="division_id" id="division_id" class="form-select @error('division_id') border-danger @enderror" required>
                                    <option value="" disabled selected>Pilih Divisi</option>
                                    @foreach ($divisions as $division)
                                        <option value="{{ $division->id }}" {{ old('division_id') == $division->id ? 'selected' : '' }}>{{ $division->name }}</option>
                                    @endforeach
                                </select>
                                @error('division_id')
                                    <p class="text-danger text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col-span-1">
                                <label for="name" class="inline-block mb-2 text-sm text-default-800 font-medium">Nama Departemen</label>
                                <input type="text" name="name" id="name" class="form-input @error('name') border-danger @enderror" value="{{ old('name') }}" placeholder="Contoh: FRONT OFFICE" required>
                                @error('name')
                                    <p class="text-danger text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="flex justify-end gap-2 border-t border-default-200 pt-5">
                            <a href="{{ route('departments.index') }}" class="btn border-0 text-danger bg-transparent hover:bg-danger/10">Batal</a>
                            <button type="submit" class="btn bg-primary text-white px-10">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="lg:col-span-3 col-span-1">
            <div class="card">
                <div class="card-body">
                    <h6 class="mb-4 card-title">Petunjuk</h6>
                    <ul class="text-default-500 text-sm list-disc pl-4 space-y-2">
                        <li>Pilih divisi induk terlebih dahulu.</li>
                        <li>Gunakan huruf kapital untuk konsistensi data.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

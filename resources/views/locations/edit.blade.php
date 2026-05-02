@extends('layouts.app')

@section('title', 'Edit Lokasi: ' . $location->name)

@section('content')
    @include('layouts.partials/page-title', ['subtitle' => 'Locations', 'title' => 'Edit Location'])

    <div class="grid grid-cols-1 gap-6">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title">Edit Informasi Lokasi</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('locations.update', $location) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 gap-5">
                        <div>
                            <label class="form-label text-sm font-medium text-default-700 mb-2 block" for="name">Nama Lokasi <span class="text-danger">*</span></label>
                            <input class="form-input w-full @error('name') border-danger @enderror" id="name" name="name" type="text" value="{{ old('name', $location->name) }}" required />
                            @error('name')
                                <p class="text-danger text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="form-label text-sm font-medium text-default-700 mb-2 block" for="address">Alamat Lengkap <span class="text-danger">*</span></label>
                            <textarea class="form-input w-full @error('address') border-danger @enderror" id="address" name="address" rows="5" required>{{ old('address', $location->address) }}</textarea>
                            @error('address')
                                <p class="text-danger text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 mt-6">
                        <a href="{{ route('locations.index') }}" class="btn border-default-200 text-default-600 hover:bg-default-100">Batal</a>
                        <button type="submit" class="btn bg-primary text-white">Perbarui Lokasi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

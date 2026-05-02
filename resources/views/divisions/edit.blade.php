@extends('layouts.app')

@section('title', 'Edit Divisi')

@section('content')
    @include('layouts.partials/page-title', ['subtitle' => 'Master Data', 'title' => 'Edit Divisi'])

    <div class="grid lg:grid-cols-12 grid-cols-1 gap-6">
        <div class="lg:col-span-9 col-span-1">
            <div class="card">
                <div class="card-body">
                    <h6 class="mb-4 card-title text-base">Edit Divisi: {{ $division->name }}</h6>
                    
                    <form action="{{ route('divisions.update', $division) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-5">
                            <label for="name" class="inline-block mb-2 text-sm text-default-800 font-medium">Nama Divisi</label>
                            <input type="text" name="name" id="name" class="form-input @error('name') border-danger @enderror" value="{{ old('name', $division->name) }}" required>
                            @error('name')
                                <p class="text-danger text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="flex justify-end gap-2 border-t border-default-200 pt-5">
                            <a href="{{ route('divisions.index') }}" class="btn border-0 text-danger bg-transparent hover:bg-danger/10">Batal</a>
                            <button type="submit" class="btn bg-primary text-white px-10">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="lg:col-span-3 col-span-1">
            <div class="card">
                <div class="card-body">
                    <h6 class="mb-4 card-title">Statistik</h6>
                    <p class="text-default-500 text-sm">Divisi ini memiliki <b>{{ $division->departments_count }}</b> departemen terkait.</p>
                </div>
            </div>
        </div>
    </div>
@endsection

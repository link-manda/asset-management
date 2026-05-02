@extends('layouts.app')

@section('title', 'Edit Kategori: ' . $category->name)

@section('content')
    @include('layouts.partials/page-title', ['subtitle' => 'Categories', 'title' => 'Edit Category'])

    <div class="grid grid-cols-1 gap-6">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title">Edit Informasi Kategori</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('categories.update', $category) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 gap-5">
                        <div>
                            <label class="form-label text-sm font-medium text-default-700 mb-2 block" for="name">Nama Kategori <span class="text-danger">*</span></label>
                            <input class="form-input w-full @error('name') border-danger @enderror" id="name" name="name" type="text" value="{{ old('name', $category->name) }}" required />
                            @error('name')
                                <p class="text-danger text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="form-label text-sm font-medium text-default-700 mb-2 block" for="description">Deskripsi</label>
                            <textarea class="form-input w-full @error('description') border-danger @enderror" id="description" name="description" rows="5">{{ old('description', $category->description) }}</textarea>
                            @error('description')
                                <p class="text-danger text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 mt-6">
                        <a href="{{ route('categories.index') }}" class="btn border-default-200 text-default-600 hover:bg-default-100">Batal</a>
                        <button type="submit" class="btn bg-primary text-white">Perbarui Kategori</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

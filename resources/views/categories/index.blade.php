@extends('layouts.app')

@section('title', 'Manajemen Kategori')

@section('content')
    @include('layouts.partials/page-title', ['subtitle' => 'Master Data', 'title' => 'Categories'])

    <div class="grid grid-cols-1 gap-5 mb-5">
        <div class="card">
            <div class="card-header flex justify-between items-center">
                <h6 class="card-title">Daftar Kategori</h6>
                <button data-hs-overlay="#modal-add-category" class="btn btn-sm bg-primary text-white">
                    <i class="size-4 me-1" data-lucide="plus"></i>Tambah Kategori
                </button>
            </div>
            <div class="card-body p-0">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-default-200">
                        <thead class="bg-default-100 font-normal whitespace-nowrap">
                            <tr class="text-sm text-default-800">
                                <th class="px-3.5 py-3 font-medium text-start" scope="col">Nama Kategori</th>
                                <th class="px-3.5 py-3 font-medium text-start" scope="col">Deskripsi</th>
                                <th class="px-3.5 py-3 font-medium text-start" scope="col">Total Asset</th>
                                <th class="px-3.5 py-3 font-medium text-center" scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-default-200">
                            @foreach ($categories as $category)
                                <tr class="text-default-800 font-normal whitespace-nowrap">
                                    <td class="px-3.5 py-4 text-sm font-medium text-default-800">{{ $category->name }}</td>
                                    <td class="px-3.5 py-4 text-default-500 text-sm whitespace-normal max-w-xs">{{ $category->description ?? '-' }}</td>
                                    <td class="px-3.5 py-4">
                                        <span class="inline-flex items-center gap-x-1.5 py-0.5 px-2.5 rounded text-xs font-medium bg-info/15 text-info">
                                            {{ $category->assets_count }} Items
                                        </span>
                                    </td>
                                    <td class="px-3.5 py-4 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <button data-hs-overlay="#modal-edit-{{ $category->id }}" class="flex size-8 bg-default-200 rounded-md items-center justify-center hover:bg-primary/10 hover:text-primary transition-all text-default-600">
                                                <i class="size-4" data-lucide="pencil"></i>
                                            </button>
                                            <form action="{{ route('categories.destroy', $category) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="flex size-8 bg-default-200 rounded-md items-center justify-center hover:bg-danger/10 hover:text-danger transition-all text-default-600 delete-confirm" data-name="Kategori {{ $category->name }}">
                                                    <i class="size-4" data-lucide="trash-2"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>

                                {{-- MODAL EDIT --}}
                                <div id="modal-edit-{{ $category->id }}" class="hs-overlay hidden size-full fixed top-0 start-0 z-80 overflow-x-hidden overflow-y-auto pointer-events-none">
                                    <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto flex items-center min-h-[calc(100%-3.5rem)]">
                                        <div class="flex flex-col bg-card border border-default-200 shadow-sm rounded-md pointer-events-auto w-full">
                                            <div class="flex justify-between items-center py-3 px-4 border-b border-default-200">
                                                <h3 class="font-bold text-default-800 text-lg">Edit Kategori</h3>
                                                <button type="button" class="size-8 inline-flex justify-center items-center gap-x-2 rounded-full border border-transparent bg-default-100 text-default-800 hover:bg-default-200 focus:outline-none focus:bg-default-200 disabled:opacity-50 disabled:pointer-events-none" data-hs-overlay="#modal-edit-{{ $category->id }}">
                                                    <i class="size-4" data-lucide="x"></i>
                                                </button>
                                            </div>
                                            <form action="{{ route('categories.update', $category) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="p-5">
                                                    <div class="mb-5">
                                                        <label class="inline-block mb-2 text-sm text-default-800 font-medium">Nama Kategori</label>
                                                        <input type="text" name="name" class="form-input" value="{{ $category->name }}" required>
                                                    </div>
                                                    <div class="mb-0">
                                                        <label class="inline-block mb-2 text-sm text-default-800 font-medium">Deskripsi</label>
                                                        <textarea name="description" class="form-input" rows="3">{{ $category->description }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="flex justify-end items-center gap-2 py-3 px-4 border-t border-default-200">
                                                    <button type="button" class="btn border-default-200 text-default-600" data-hs-overlay="#modal-edit-{{ $category->id }}">Batal</button>
                                                    <button type="submit" class="btn bg-primary text-white">Simpan Perubahan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="p-4 border-t border-default-200">
                {{ $categories->links() }}
            </div>
        </div>
    </div>

    {{-- MODAL ADD --}}
    <div id="modal-add-category" class="hs-overlay hidden size-full fixed top-0 start-0 z-80 overflow-x-hidden overflow-y-auto pointer-events-none">
        <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto flex items-center min-h-[calc(100%-3.5rem)]">
            <div class="flex flex-col bg-card border border-default-200 shadow-sm rounded-md pointer-events-auto w-full">
                <div class="flex justify-between items-center py-3 px-4 border-b border-default-200">
                    <h3 class="font-bold text-default-800 text-lg">Tambah Kategori Baru</h3>
                    <button type="button" class="size-8 inline-flex justify-center items-center gap-x-2 rounded-full border border-transparent bg-default-100 text-default-800 hover:bg-default-200 focus:outline-none focus:bg-default-200 disabled:opacity-50 disabled:pointer-events-none" data-hs-overlay="#modal-add-category">
                        <i class="size-4" data-lucide="x"></i>
                    </button>
                </div>
                <form action="{{ route('categories.store') }}" method="POST">
                    @csrf
                    <div class="p-5">
                        <div class="mb-5">
                            <label class="inline-block mb-2 text-sm text-default-800 font-medium">Nama Kategori</label>
                            <input type="text" name="name" class="form-input" placeholder="Misal: IT Equipment" required>
                        </div>
                        <div class="mb-0">
                            <label class="inline-block mb-2 text-sm text-default-800 font-medium">Deskripsi</label>
                            <textarea name="description" class="form-input" rows="3" placeholder="Deskripsi singkat..."></textarea>
                        </div>
                    </div>
                    <div class="flex justify-end items-center gap-2 py-3 px-4 border-t border-default-200">
                        <button type="button" class="btn border-default-200 text-default-600" data-hs-overlay="#modal-add-category">Batal</button>
                        <button type="submit" class="btn bg-primary text-white">Tambah Kategori</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

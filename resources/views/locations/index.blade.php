@extends('layouts.app')

@section('title', 'Manajemen Lokasi')

@section('content')
    @include('layouts.partials/page-title', ['subtitle' => 'Master Data', 'title' => 'Locations'])

    <div class="grid grid-cols-1 gap-5 mb-5">
        <div class="card">
            <div class="card-header flex justify-between items-center">
                <h6 class="card-title">Daftar Lokasi</h6>
                <button data-hs-overlay="#modal-add-location" class="btn btn-sm bg-primary text-white">
                    <i class="size-4 me-1" data-lucide="plus"></i>Tambah Lokasi
                </button>
            </div>
            <div class="card-body p-0">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-default-200">
                        <thead class="bg-default-100 font-normal whitespace-nowrap">
                            <tr class="text-sm text-default-800">
                                <th class="px-3.5 py-3 font-medium text-start" scope="col">Nama Lokasi</th>
                                <th class="px-3.5 py-3 font-medium text-start" scope="col">Lokasi Induk</th>
                                <th class="px-3.5 py-3 font-medium text-start" scope="col">Alamat</th>
                                <th class="px-3.5 py-3 font-medium text-start" scope="col">Total Asset</th>
                                <th class="px-3.5 py-3 font-medium text-center" scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-default-200">
                            @foreach ($locations as $location)
                                <tr class="text-default-800 font-normal whitespace-nowrap">
                                    <td class="px-3.5 py-4 text-sm font-medium text-default-800">
                                        <div class="flex items-center gap-2">
                                            @if($location->parent_id)
                                                <i class="size-3 text-default-400" data-lucide="corner-down-right"></i>
                                            @endif
                                            {{ $location->name }}
                                        </div>
                                    </td>
                                    <td class="px-3.5 py-4 text-sm">
                                        @if($location->parent)
                                            <span class="text-default-500">{{ $location->parent->name }}</span>
                                        @else
                                            <span class="text-default-300 italic">- Top Level -</span>
                                        @endif
                                    </td>
                                    <td class="px-3.5 py-4 text-default-500 text-sm whitespace-normal max-w-xs">{{ $location->address }}</td>
                                    <td class="px-3.5 py-4">
                                        <span class="inline-flex items-center gap-x-1.5 py-0.5 px-2.5 rounded text-xs font-medium bg-secondary/15 text-secondary">
                                            {{ $location->items_count }} Units
                                        </span>
                                    </td>
                                    <td class="px-3.5 py-4 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <button data-hs-overlay="#modal-edit-{{ $location->id }}" class="flex size-8 bg-default-200 rounded-md items-center justify-center hover:bg-primary/10 hover:text-primary transition-all text-default-600">
                                                <i class="size-4" data-lucide="pencil"></i>
                                            </button>
                                            <form action="{{ route('locations.destroy', $location) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="flex size-8 bg-default-200 rounded-md items-center justify-center hover:bg-danger/10 hover:text-danger transition-all text-default-600 delete-confirm" data-name="Lokasi {{ $location->name }}">
                                                    <i class="size-4" data-lucide="trash-2"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>

                                {{-- MODAL EDIT --}}
                                <div id="modal-edit-{{ $location->id }}" class="hs-overlay hidden size-full fixed top-0 start-0 z-80 overflow-x-hidden overflow-y-auto pointer-events-none">
                                    <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto flex items-center min-h-[calc(100%-3.5rem)]">
                                        <div class="flex flex-col bg-card border border-default-200 shadow-sm rounded-md pointer-events-auto w-full">
                                             <div class="flex justify-between items-center py-3 px-4 border-b border-default-200">
                                                <h3 class="font-bold text-default-800 text-lg">Edit Lokasi</h3>
                                                <button type="button" class="size-8 inline-flex justify-center items-center gap-x-2 rounded-full border border-transparent bg-default-100 text-default-800 hover:bg-default-200 focus:outline-none focus:bg-default-200 disabled:opacity-50 disabled:pointer-events-none" data-hs-overlay="#modal-edit-{{ $location->id }}">
                                                    <i class="size-4" data-lucide="x"></i>
                                                </button>
                                            </div>
                                            <form action="{{ route('locations.update', $location) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="p-5">
                                                    <div class="mb-5">
                                                        <label class="inline-block mb-2 text-sm text-default-800 font-medium">Nama Lokasi</label>
                                                        <input type="text" name="name" class="form-input" value="{{ $location->name }}" required>
                                                    </div>
                                                    <div class="mb-5">
                                                        <label class="inline-block mb-2 text-sm text-default-800 font-medium">Lokasi Induk (Parent)</label>
                                                        <select name="parent_id" class="form-input">
                                                            <option value="">- Jadikan Lokasi Utama -</option>
                                                            @foreach($allLocations as $parent)
                                                                @if($parent->id != $location->id)
                                                                    <option value="{{ $parent->id }}" {{ $location->parent_id == $parent->id ? 'selected' : '' }}>{{ $parent->name }}</option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                        <p class="mt-1 text-xs text-default-400 italic">*Pilih induk jika lokasi ini adalah bagian dari area lain</p>
                                                    </div>
                                                    <div class="mb-0">
                                                        <label class="inline-block mb-2 text-sm text-default-800 font-medium">Alamat</label>
                                                        <textarea name="address" class="form-input" rows="3" required>{{ $location->address }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="flex justify-end items-center gap-2 py-3 px-4 border-t border-default-200">
                                                    <button type="button" class="btn border-default-200 text-default-600" data-hs-overlay="#modal-edit-{{ $location->id }}">Batal</button>
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
                {{ $locations->links() }}
            </div>
        </div>
    </div>

    {{-- MODAL ADD --}}
    <div id="modal-add-location" class="hs-overlay hidden size-full fixed top-0 start-0 z-80 overflow-x-hidden overflow-y-auto pointer-events-none">
        <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto flex items-center min-h-[calc(100%-3.5rem)]">
            <div class="flex flex-col bg-card border border-default-200 shadow-sm rounded-md pointer-events-auto w-full">
                <div class="flex justify-between items-center py-3 px-4 border-b border-default-200">
                    <h3 class="font-bold text-default-800 text-lg">Tambah Lokasi Baru</h3>
                    <button type="button" class="size-8 inline-flex justify-center items-center gap-x-2 rounded-full border border-transparent bg-default-100 text-default-800 hover:bg-default-200 focus:outline-none focus:bg-default-200 disabled:opacity-50 disabled:pointer-events-none" data-hs-overlay="#modal-add-location">
                        <i class="size-4" data-lucide="x"></i>
                    </button>
                </div>
                <form action="{{ route('locations.store') }}" method="POST">
                    @csrf
                    <div class="p-5">
                        <div class="mb-5">
                            <label class="inline-block mb-2 text-sm text-default-800 font-medium">Nama Lokasi</label>
                            <input type="text" name="name" class="form-input" placeholder="Misal: Gudang Jakarta" required>
                        </div>
                        <div class="mb-5">
                            <label class="inline-block mb-2 text-sm text-default-800 font-medium">Lokasi Induk (Parent)</label>
                            <select name="parent_id" class="form-input">
                                <option value="">- Jadikan Lokasi Utama -</option>
                                @foreach($allLocations as $parent)
                                    <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-0">
                            <label class="inline-block mb-2 text-sm text-default-800 font-medium">Alamat Lengkap</label>
                            <textarea name="address" class="form-input" rows="3" placeholder="Alamat detail..." required></textarea>
                        </div>
                    </div>
                    <div class="flex justify-end items-center gap-2 py-3 px-4 border-t border-default-200">
                        <button type="button" class="btn border-default-200 text-default-600" data-hs-overlay="#modal-add-location">Batal</button>
                        <button type="submit" class="btn bg-primary text-white">Tambah Lokasi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

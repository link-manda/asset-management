@extends('layouts.app')

@section('title', 'Katalog Aset (Master)')

@section('content')
    @include('layouts.partials/page-title', ['subtitle' => 'Katalog', 'title' => 'Master Asset Catalog'])

    <div class="grid grid-cols-1 gap-5 mb-5">
        <div class="card">
            <div class="card-header flex justify-between items-center">
                <div class="flex gap-3 items-center">
                    <div class="relative">
                        <input class="ps-11 form-input form-input-sm w-64" placeholder="Cari nama atau kode aset..." type="text" />
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3">
                            <i class="size-3.5 flex items-center text-default-500" data-lucide="search"></i>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('assets.create') }}" class="btn btn-sm bg-primary text-white">
                        <i class="size-4 me-1" data-lucide="plus"></i>Tambah Asset Baru
                    </a>
                </div>
            </div>
            <div class="flex flex-col">
                <div class="overflow-x-auto">
                    <div class="min-w-full inline-block align-middle">
                        <div class="overflow-hidden">
                            <table class="min-w-full divide-y divide-default-200">
                                <thead class="bg-default-150 font-normal">
                                    <tr class="text-sm text-default-700">
                                        <th class="px-3.5 py-3 text-start" scope="col">Master Code</th>
                                        <th class="px-3.5 py-3 text-start" scope="col">Nama Barang</th>
                                        <th class="px-3.5 py-3 text-start" scope="col">Kategori</th>
                                        <th class="px-3.5 py-3 text-start" scope="col">Stok Fisik</th>
                                        <th class="px-3.5 py-3 text-start" scope="col">Estimasi Nilai</th>
                                        <th class="px-3.5 py-3 text-start" scope="col">Status Distribusi</th>
                                        <th class="px-3.5 py-3 text-center" scope="col">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-default-200">
                                    @forelse ($assets as $asset)
                                        <tr class="text-default-800 font-normal hover:bg-default-50 transition-all">
                                            <td class="px-3.5 py-4 whitespace-nowrap text-sm text-primary font-bold">
                                                {{ $asset->asset_code }}
                                            </td>
                                            <td class="px-3.5 py-4 whitespace-nowrap text-sm">
                                                <a href="{{ route('assets.show', $asset) }}" class="flex items-center gap-2">
                                                    <h6 class="text-default-800 hover:text-primary transition-all font-semibold">{{ $asset->name }}</h6>
                                                </a>
                                            </td>
                                            <td class="px-3.5 py-4 whitespace-nowrap text-sm">
                                                <span class="inline-flex py-0.5 px-2 rounded text-[10px] font-bold bg-default-100 text-default-600 border border-default-200">
                                                    {{ $asset->category?->name ?? 'Uncategorized' }}
                                                </span>
                                            </td>
                                            <td class="px-3.5 py-4 whitespace-nowrap text-sm">
                                                <div class="font-bold text-default-800">
                                                    {{ $asset->total_quantity }} {{ $asset->uom?->symbol }}
                                                </div>
                                            </td>
                                            <td class="px-3.5 py-4 whitespace-nowrap text-sm">
                                                <div class="font-bold text-default-800">
                                                    Rp {{ number_format($asset->total_value, 0, ',', '.') }}
                                                </div>
                                                <p class="text-[10px] text-default-400">@ Rp {{ number_format($asset->price, 0, ',', '.') }}</p>
                                            </td>
                                            <td class="px-3.5 py-4 whitespace-nowrap">
                                                @php
                                                    $itemStatuses = $asset->items->groupBy('status')->map->count();
                                                @endphp
                                                <div class="flex flex-wrap gap-1">
                                                    @foreach($itemStatuses as $status => $count)
                                                        @php
                                                            $statusClasses = [
                                                                'Available' => 'bg-success/15 text-success',
                                                                'Deployed' => 'bg-primary/15 text-primary',
                                                                'Maintenance' => 'bg-warning/15 text-warning',
                                                                'Broken' => 'bg-danger/15 text-danger',
                                                                'Disposed' => 'bg-danger text-white',
                                                            ];
                                                            $class = $statusClasses[$status] ?? 'bg-default-100 text-default-500';
                                                        @endphp
                                                        <span class="inline-flex items-center py-0.5 px-1.5 rounded text-[9px] font-bold {{ $class }}">
                                                            {{ $count }} {{ $status }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            </td>
                                            <td class="px-3.5 py-4 text-center">
                                                <div class="flex items-center justify-center gap-2">
                                                    <a href="{{ route('assets.show', $asset) }}" class="size-8 flex items-center justify-center bg-default-100 text-default-600 rounded hover:bg-primary/10 hover:text-primary transition-all">
                                                        <i class="size-4" data-lucide="eye"></i>
                                                    </a>
                                                    <a href="{{ route('assets.edit', $asset) }}" class="size-8 flex items-center justify-center bg-default-100 text-default-600 rounded hover:bg-warning/10 hover:text-warning transition-all">
                                                        <i class="size-4" data-lucide="edit-3"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="px-3.5 py-12 text-center text-default-400 italic">
                                                Belum ada katalog aset terdaftar.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card-footer p-4 border-t border-default-200">
                    {{ $assets->links('vendor.pagination.tailwind-custom') }}
                </div>
            </div>
        </div>
    </div>
@endsection

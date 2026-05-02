@extends('layouts.app')

@section('title', 'Maintenance Aset')

@section('content')
    @include('layouts.partials/page-title', ['subtitle' => 'Manajemen Aset', 'title' => 'Daftar Maintenance'])

    <div class="grid grid-cols-1 gap-5 mb-5">
        <div class="card">
            <div class="card-header flex justify-between items-center">
                <div class="flex gap-3 items-center">
                    <div class="relative">
                        <input class="ps-11 form-input form-input-sm w-64" placeholder="Cari maintenance..." type="text" />
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3">
                            <i class="size-3.5 flex items-center text-default-500" data-lucide="search"></i>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('maintenances.create') }}" class="btn btn-sm bg-primary text-white">
                        <i class="size-4 me-1" data-lucide="wrench"></i> Ajukan Perbaikan
                    </a>
                </div>
            </div>
            <div class="flex flex-col">
                <div class="overflow-x-auto">
                    <div class="min-w-full inline-block align-middle">
                        <div class="overflow-hidden">
                            <table class="min-w-full divide-y divide-default-200">
                                <thead class="bg-default-100">
                                    <tr class="text-xs font-semibold text-default-600 uppercase">
                                        <th class="px-4 py-3 text-start">Asset Info</th>
                                        <th class="px-4 py-3 text-start">Tanggal</th>
                                        <th class="px-4 py-3 text-start">Deskripsi Perbaikan</th>
                                        <th class="px-4 py-3 text-start">Biaya (Actual)</th>
                                        <th class="px-4 py-3 text-start">Status Servis</th>
                                        <th class="px-4 py-3 text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-default-200">
                                    @forelse ($maintenances as $item)
                                        <tr class="text-default-800 hover:bg-default-50 transition-all">
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <div class="flex items-center gap-3">
                                                    <div class="size-10 bg-default-100 rounded flex items-center justify-center">
                                                        <i class="size-5 text-default-500" data-lucide="package"></i>
                                                    </div>
                                                    <div>
                                                        <a href="{{ route('assets.show', $item->asset) }}" class="text-sm font-bold text-default-800 hover:text-primary transition-all">
                                                            {{ $item->asset->name }}
                                                        </a>
                                                        <p class="text-xs text-primary font-medium">#{{ $item->asset->asset_code }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-default-600 font-medium">
                                                {{ \Carbon\Carbon::parse($item->maintenance_date)->format('d/m/Y') }}
                                            </td>
                                            <td class="px-4 py-3 text-sm text-default-600">
                                                <div class="max-w-[250px] truncate" title="{{ $item->description }}">
                                                    {{ $item->description }}
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm">
                                                <span class="font-bold text-default-800">Rp {{ number_format($item->cost, 0, ',', '.') }}</span>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                @php
                                                    $statusClasses = [
                                                        'Scheduled' => 'bg-info/10 text-info border-info/20',
                                                        'In Progress' => 'bg-warning/10 text-warning border-warning/20',
                                                        'Completed' => 'bg-success/10 text-success border-success/20',
                                                    ];
                                                    $class = $statusClasses[$item->status] ?? 'bg-default-100 text-default-500';
                                                @endphp
                                                <span class="inline-flex items-center gap-x-1.5 py-1 px-2.5 rounded border text-xs font-semibold {{ $class }}">
                                                    {{ $item->status }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <div class="hs-dropdown relative inline-flex">
                                                    <button aria-expanded="false" aria-haspopup="menu" aria-label="Dropdown"
                                                        class="hs-dropdown-toggle btn size-8 bg-default-100 hover:bg-default-600 text-default-500 hover:text-white rounded-md transition-all"
                                                        hs-dropdown-placement="bottom-end" type="button">
                                                        <i class="size-4" data-lucide="more-horizontal"></i>
                                                    </button>
                                                    <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-32 z-50 bg-white shadow-lg rounded-lg p-2 mt-2 border border-default-200 dark:bg-default-50" role="menu">
                                                        <a class="flex items-center gap-2 py-2 px-3 text-sm text-default-600 hover:bg-default-100 rounded-md font-medium"
                                                            href="{{ route('maintenances.edit', $item) }}">
                                                            <i class="size-4" data-lucide="edit-3"></i> Edit Data
                                                        </a>
                                                        <div class="h-px bg-default-200 my-1"></div>
                                                        <form action="{{ route('maintenances.destroy', $item) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="w-full flex items-center gap-2 py-2 px-3 text-sm text-danger hover:bg-danger/10 rounded-md font-medium delete-confirm" data-name="Log Maintenance {{ $item->asset->name }}">
                                                                <i class="size-4" data-lucide="trash-2"></i> Hapus Log
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-4 py-10 text-center">
                                                <div class="flex flex-col items-center">
                                                    <i class="size-12 text-default-300 mb-3" data-lucide="clipboard-list"></i>
                                                    <p class="text-default-500 font-medium">Belum ada riwayat maintenance.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card-footer flex items-center justify-between border-t border-default-200 p-4">
                    <p class="text-default-500 text-sm italic">Menampilkan riwayat maintenance terbaru.</p>
                    <div>
                         {{ $maintenances->links('vendor.pagination.tailwind-custom') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

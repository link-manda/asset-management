@extends('layouts.app')

@section('title', 'Laporan Penyusutan Aset')

@section('content')
    @include('layouts.partials/page-title', ['subtitle' => 'Reports', 'title' => 'Depreciation Report'])

    <div class="grid grid-cols-1 gap-6">
        <div class="card">
            <div class="card-header flex justify-between items-center">
                <h6 class="card-title text-base">Filter Laporan</h6>
                <div class="flex gap-2">
                    <a href="{{ route('reports.depreciation.export', request()->all()) }}" class="btn btn-sm bg-success text-white flex items-center gap-2">
                        <i class="size-4" data-lucide="download"></i> Export CSV
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('reports.depreciation') }}" method="GET" class="grid lg:grid-cols-4 md:grid-cols-2 grid-cols-1 gap-4">
                    <div>
                        <label class="text-xs font-medium text-default-600 mb-1 block">Cari Aset/Barcode</label>
                        <input type="text" name="search" class="form-input form-input-sm" placeholder="Nama aset atau barcode..." value="{{ request('search') }}">
                    </div>
                    <div>
                        <label class="text-xs font-medium text-default-600 mb-1 block">Kategori</label>
                        <select name="category_id" class="form-input form-input-sm">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-xs font-medium text-default-600 mb-1 block">Status</label>
                        <select name="status" class="form-input form-input-sm">
                            <option value="">Semua Status Aktif</option>
                            @foreach($statuses as $status)
                                <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>{{ $status }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-end gap-2">
                        <button type="submit" class="btn btn-sm bg-primary text-white w-full">Filter</button>
                        <a href="{{ route('reports.depreciation') }}" class="btn btn-sm border-default-200 text-default-600">Reset</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-body p-0">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-default-200 text-sm">
                        <thead class="bg-default-50">
                            <tr>
                                <th class="px-4 py-3 text-start font-bold text-default-800">Item Code</th>
                                <th class="px-4 py-3 text-start font-bold text-default-800">Nama Aset</th>
                                <th class="px-4 py-3 text-start font-bold text-default-800">Kategori</th>
                                <th class="px-4 py-3 text-start font-bold text-default-800">Tgl Beli</th>
                                <th class="px-4 py-3 text-end font-bold text-default-800">Harga Perolehan</th>
                                <th class="px-4 py-3 text-center font-bold text-default-800">Umur (Bln)</th>
                                <th class="px-4 py-3 text-end font-bold text-default-800">Akumulasi Penyusutan</th>
                                <th class="px-4 py-3 text-end font-bold text-default-800">Nilai Buku</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-default-200">
                            @forelse($items as $item)
                                @php
                                    $accumulated = $item->purchase_price - $item->current_value;
                                @endphp
                                <tr class="hover:bg-default-50 transition-all">
                                    <td class="px-4 py-4 font-mono text-primary font-bold">
                                        <a href="{{ route('inventory.show', $item) }}" class="hover:underline">{{ $item->item_code }}</a>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="flex flex-col">
                                            <span class="font-bold text-default-800">{{ $item->asset->name }}</span>
                                            <span class="text-xs text-default-500">SN: {{ $item->serial_number ?? '-' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <span class="bg-default-100 text-default-600 px-2 py-0.5 rounded text-[10px] font-bold uppercase">
                                            {{ $item->asset->category->name }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-default-600">
                                        {{ $item->purchase_date->format('d/m/Y') }}
                                    </td>
                                    <td class="px-4 py-4 text-end font-medium text-default-800">
                                        Rp {{ number_format($item->purchase_price, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-4 text-center text-default-600">
                                        {{ $item->useful_life_months }}
                                    </td>
                                    <td class="px-4 py-4 text-end text-danger font-medium">
                                        -Rp {{ number_format($accumulated, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-4 text-end font-bold text-primary">
                                        Rp {{ number_format($item->current_value, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-4 py-8 text-center text-default-500 italic">
                                        Tidak ada data aset untuk laporan ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                {{ $items->links() }}
            </div>
        </div>
    </div>
@endsection

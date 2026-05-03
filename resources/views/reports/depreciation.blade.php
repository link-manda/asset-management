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
            <div class="card-header border-b border-default-200">
                <div class="flex flex-wrap justify-between items-center gap-4">
                    <h6 class="card-title text-base">Hasil Kalkulasi Penyusutan</h6>
                    
                    <div class="flex bg-default-100 rounded-lg p-1">
                        <a href="{{ route('reports.depreciation', array_merge(request()->all(), ['mode' => 'commercial'])) }}" 
                           class="px-4 py-1.5 rounded-md text-xs font-bold transition-all {{ $mode == 'commercial' ? 'bg-white text-primary shadow-sm' : 'text-default-500 hover:text-default-700' }}">
                            Komersial
                        </a>
                        <a href="{{ route('reports.depreciation', array_merge(request()->all(), ['mode' => 'fiscal'])) }}" 
                           class="px-4 py-1.5 rounded-md text-xs font-bold transition-all {{ $mode == 'fiscal' ? 'bg-white text-primary shadow-sm' : 'text-default-500 hover:text-default-700' }}">
                            Fiskal
                        </a>
                        <a href="{{ route('reports.depreciation', array_merge(request()->all(), ['mode' => 'comparison'])) }}" 
                           class="px-4 py-1.5 rounded-md text-xs font-bold transition-all {{ $mode == 'comparison' ? 'bg-white text-primary shadow-sm' : 'text-default-500 hover:text-default-700' }}">
                            Perbandingan
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-default-200 text-sm">
                        <thead class="bg-default-50">
                            @if($mode == 'comparison')
                                <tr>
                                    <th rowspan="2" class="px-4 py-3 text-start font-bold text-default-800 border-e border-default-200">Aset / Barcode</th>
                                    <th colspan="2" class="px-4 py-2 text-center font-bold text-primary bg-primary/5 border-b border-default-200">Komersial (Internal)</th>
                                    <th colspan="2" class="px-4 py-2 text-center font-bold text-success bg-success/5 border-b border-default-200">Fiskal (Pajak)</th>
                                    <th rowspan="2" class="px-4 py-3 text-end font-bold text-default-800 border-s border-default-200">Selisih (K-F)</th>
                                </tr>
                                <tr class="bg-default-50">
                                    <th class="px-4 py-2 text-end font-bold text-default-600 text-[10px] uppercase">Nilai Buku</th>
                                    <th class="px-4 py-2 text-center font-bold text-default-600 text-[10px] uppercase border-e border-default-200">Umur</th>
                                    <th class="px-4 py-2 text-end font-bold text-default-600 text-[10px] uppercase">Nilai Buku</th>
                                    <th class="px-4 py-2 text-center font-bold text-default-600 text-[10px] uppercase">Umur</th>
                                </tr>
                            @else
                                <tr>
                                    <th class="px-4 py-3 text-start font-bold text-default-800">Item Code</th>
                                    <th class="px-4 py-3 text-start font-bold text-default-800">Nama Aset</th>
                                    <th class="px-4 py-3 text-start font-bold text-default-800">Kategori</th>
                                    <th class="px-4 py-3 text-start font-bold text-default-800">Tgl Beli</th>
                                    <th class="px-4 py-3 text-end font-bold text-default-800">Harga Perolehan</th>
                                    <th class="px-4 py-3 text-center font-bold text-default-800">Umur (Bln)</th>
                                    <th class="px-4 py-3 text-end font-bold text-default-800">Akumulasi Penyusutan</th>
                                    <th class="px-4 py-3 text-end font-bold text-default-800">Nilai Buku ({{ ucfirst($mode) }})</th>
                                </tr>
                            @endif
                        </thead>
                        <tbody class="divide-y divide-default-200">
                            @forelse($items as $item)
                                @php
                                    $commValue = (float) $item->commercial_value;
                                    $fiscalValue = (float) $item->fiscal_value;
                                    $currentValue = $mode == 'fiscal' ? $fiscalValue : $commValue;
                                    $accumulated = (float) $item->purchase_price - $currentValue;
                                    $diff = $commValue - $fiscalValue;
                                @endphp
                                <tr class="hover:bg-default-50 transition-all">
                                    <td class="px-4 py-4 {{ $mode == 'comparison' ? 'border-e border-default-200' : '' }}">
                                        <div class="flex flex-col">
                                            <a href="{{ route('inventory.show', $item) }}" class="font-mono text-primary font-bold hover:underline">{{ $item->item_code }}</a>
                                            <span class="text-xs font-bold text-default-800">{{ $item->asset->name }}</span>
                                        </div>
                                    </td>
                                    
                                    @if($mode == 'comparison')
                                        <td class="px-4 py-4 text-end font-medium text-default-800 bg-primary/5">
                                            Rp {{ number_format($commValue, 0, ',', '.') }}
                                        </td>
                                        <td class="px-4 py-4 text-center text-default-500 text-xs border-e border-default-200">
                                            {{ $item->useful_life_months }} bln
                                        </td>
                                        <td class="px-4 py-4 text-end font-medium text-default-800 bg-success/5">
                                            Rp {{ number_format($fiscalValue, 0, ',', '.') }}
                                        </td>
                                        <td class="px-4 py-4 text-center text-default-500 text-xs">
                                            {{ $item->fiscal_useful_life }} bln
                                        </td>
                                        <td class="px-4 py-4 text-end font-black border-s border-default-200 {{ $diff != 0 ? 'text-warning' : 'text-default-400' }}">
                                            Rp {{ number_format($diff, 0, ',', '.') }}
                                        </td>
                                    @else
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
                                            {{ $mode == 'fiscal' ? $item->fiscal_useful_life : $item->useful_life_months }}
                                        </td>
                                        <td class="px-4 py-4 text-end text-danger font-medium">
                                            -Rp {{ number_format($accumulated, 0, ',', '.') }}
                                        </td>
                                        <td class="px-4 py-4 text-end font-bold text-primary">
                                            Rp {{ number_format($currentValue, 0, ',', '.') }}
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ $mode == 'comparison' ? 6 : 8 }}" class="px-4 py-8 text-center text-default-500 italic">
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

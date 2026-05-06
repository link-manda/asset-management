@extends('layouts.app')

@section('title', 'Asset Depreciation Report')

@section('content')
    @include('layouts.partials/page-title', ['subtitle' => 'Reports', 'title' => 'Depreciation Report'])


    <div class="grid grid-cols-1 gap-6">
        <div class="card">
            <div class="card-header flex justify-between items-center">
                <h6 class="card-title text-base text-default-800">Report Filter</h6>
                <div class="flex gap-2">
                    <a href="{{ route('reports.depreciation.export', array_merge(request()->all(), ['format' => 'xlsx'])) }}" class="btn btn-sm bg-success/10 text-success border border-success/20 hover:bg-success hover:text-white transition-all">
                        <i class="size-4 me-1" data-lucide="download"></i> Excel
                    </a>
                    <a href="{{ route('reports.depreciation.export', array_merge(request()->all(), ['format' => 'csv'])) }}" class="btn btn-sm bg-info/10 text-info border border-info/20 hover:bg-info hover:text-white transition-all">
                        <i class="size-4 me-1" data-lucide="file-text"></i> CSV
                    </a>
                    <a href="{{ route('reports.depreciation.export', array_merge(request()->all(), ['format' => 'pdf'])) }}" class="btn btn-sm bg-danger/10 text-danger border border-danger/20 hover:bg-danger hover:text-white transition-all">
                        <i class="size-4 me-1" data-lucide="file-type-2"></i> PDF
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('reports.depreciation') }}" method="GET" class="grid lg:grid-cols-4 md:grid-cols-2 grid-cols-1 gap-4">
                    <div>
                        <label class="text-xs font-medium text-default-600 mb-1 block">Search Asset/Barcode</label>
                        <input type="text" name="search" class="form-input form-input-sm" placeholder="Asset name or barcode..." value="{{ request('search') }}">
                    </div>
                    <div>
                        <label class="text-xs font-medium text-default-600 mb-1 block">Category</label>
                        <select name="category_id" class="form-input form-input-sm">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-xs font-medium text-default-600 mb-1 block">Status</label>
                        <select name="status" class="form-input form-input-sm">
                            <option value="">All Active Statuses</option>
                            @foreach($statuses as $status)
                                <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>{{ $status }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-end gap-2">
                        <button type="submit" class="btn btn-sm bg-primary text-white w-full">
                            <i class="size-4 me-1" data-lucide="search"></i> Filter
                        </button>
                        <a href="{{ route('reports.depreciation') }}" class="btn btn-sm border-default-200 text-default-600">
                            <i class="size-4 me-1" data-lucide="rotate-ccw"></i> Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header border-b border-default-200">
                <div class="flex flex-wrap justify-between items-center gap-4">
                    <h6 class="card-title text-base uppercase tracking-wider text-[11px] font-bold">Depreciation Calculation Results</h6>
                    
                    <div class="flex bg-default-100 rounded-lg p-1">
                        <a href="{{ route('reports.depreciation', array_merge(request()->all(), ['mode' => 'commercial'])) }}" 
                           class="px-4 py-1.5 rounded-md text-[10px] font-black uppercase tracking-widest transition-all {{ $mode == 'commercial' ? 'bg-white text-primary shadow-sm' : 'text-default-500 hover:text-default-700' }}">
                            Commercial
                        </a>
                        <a href="{{ route('reports.depreciation', array_merge(request()->all(), ['mode' => 'fiscal'])) }}" 
                           class="px-4 py-1.5 rounded-md text-[10px] font-black uppercase tracking-widest transition-all {{ $mode == 'fiscal' ? 'bg-white text-primary shadow-sm' : 'text-default-500 hover:text-default-700' }}">
                            Fiscal
                        </a>
                        <a href="{{ route('reports.depreciation', array_merge(request()->all(), ['mode' => 'comparison'])) }}" 
                           class="px-4 py-1.5 rounded-md text-[10px] font-black uppercase tracking-widest transition-all {{ $mode == 'comparison' ? 'bg-white text-primary shadow-sm' : 'text-default-500 hover:text-default-700' }}">
                            Comparison
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
                                    <th rowspan="2" class="px-4 py-3 text-start font-black uppercase tracking-widest text-[10px] text-default-600 border-e border-default-200">Asset / Barcode</th>
                                    <th colspan="2" class="px-4 py-2 text-center font-black uppercase tracking-widest text-[10px] text-primary bg-primary/5 border-b border-default-200">Commercial (Internal)</th>
                                    <th colspan="2" class="px-4 py-2 text-center font-black uppercase tracking-widest text-[10px] text-success bg-success/5 border-b border-default-200">Fiscal (Tax)</th>
                                    <th rowspan="2" class="px-4 py-3 text-end font-black uppercase tracking-widest text-[10px] text-default-600 border-s border-default-200">Diff (C-F)</th>
                                </tr>
                                <tr class="bg-default-50">
                                    <th class="px-4 py-2 text-end font-bold text-default-500 text-[9px] uppercase">Book Value</th>
                                    <th class="px-4 py-2 text-center font-bold text-default-500 text-[9px] uppercase border-e border-default-200">Life</th>
                                    <th class="px-4 py-2 text-end font-bold text-default-500 text-[9px] uppercase">Book Value</th>
                                    <th class="px-4 py-2 text-center font-bold text-default-500 text-[9px] uppercase">Life</th>
                                </tr>
                            @else
                                <tr class="text-[10px] font-black uppercase tracking-widest text-default-600">
                                    <th class="px-4 py-3 text-start">Item Code</th>
                                    <th class="px-4 py-3 text-start">Asset Name</th>
                                    <th class="px-4 py-3 text-start">Category</th>
                                    <th class="px-4 py-3 text-start">Purchase Date</th>
                                    <th class="px-4 py-3 text-end">Acquisition Cost</th>
                                    <th class="px-4 py-3 text-center">Life (Mos)</th>
                                    <th class="px-4 py-3 text-end">Accum. Depreciation</th>
                                    <th class="px-4 py-3 text-end">Book Value ({{ ucfirst($mode) }})</th>
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
                                            <a href="{{ route('inventory.show', $item) }}" class="font-mono text-primary font-bold hover:underline text-sm">#{{ $item->item_code }}</a>
                                            <a href="{{ route('assets.show', $item->asset_id) }}" class="text-[11px] font-bold text-default-800 hover:text-primary transition-all">{{ $item->asset->name }}</a>
                                        </div>
                                    </td>
                                    
                                    @if($mode == 'comparison')
                                        <td class="px-4 py-4 text-end font-medium text-default-800 bg-primary/5">
                                            Rp {{ number_format($commValue, 0, ',', '.') }}
                                        </td>
                                        <td class="px-4 py-4 text-center text-default-500 text-[10px] border-e border-default-200">
                                            {{ $item->useful_life_months }} mos
                                        </td>
                                        <td class="px-4 py-4 text-end font-medium text-default-800 bg-success/5">
                                            Rp {{ number_format($fiscalValue, 0, ',', '.') }}
                                        </td>
                                        <td class="px-4 py-4 text-center text-default-500 text-[10px]">
                                            {{ $item->fiscal_useful_life }} mos
                                        </td>
                                        <td class="px-4 py-4 text-end font-black border-s border-default-200 {{ $diff != 0 ? 'text-warning' : 'text-default-400' }}">
                                            Rp {{ number_format($diff, 0, ',', '.') }}
                                        </td>
                                    @else
                                        <td class="px-4 py-4">
                                            <div class="flex flex-col">
                                                <a href="{{ route('assets.show', $item->asset_id) }}" class="font-bold text-default-800 hover:text-primary transition-all">{{ $item->asset->name }}</a>
                                                <span class="text-[10px] text-default-400 font-medium">SN: {{ $item->serial_number ?? '-' }}</span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-4">
                                            <span class="bg-default-100 text-default-600 px-2 py-0.5 rounded text-[9px] font-black uppercase border border-default-200">
                                                {{ $item->asset->category->name }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-default-600 text-sm">
                                            {{ $item->purchase_date->format('d M Y') }}
                                        </td>
                                        <td class="px-4 py-4 text-end font-bold text-default-800 text-sm">
                                            Rp {{ number_format($item->purchase_price, 0, ',', '.') }}
                                        </td>
                                        <td class="px-4 py-4 text-center text-default-600 text-sm">
                                            {{ $mode == 'fiscal' ? $item->fiscal_useful_life : $item->useful_life_months }}
                                        </td>
                                        <td class="px-4 py-4 text-end text-danger font-bold text-sm">
                                            -Rp {{ number_format($accumulated, 0, ',', '.') }}
                                        </td>
                                        <td class="px-4 py-4 text-end font-black text-primary text-sm">
                                            Rp {{ number_format($currentValue, 0, ',', '.') }}
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ $mode == 'comparison' ? 6 : 8 }}" class="px-4 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <i class="size-12 text-default-200 mb-3" data-lucide="bar-chart-3"></i>
                                            <p class="text-default-500 font-medium italic">No asset data found for this report.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer border-t border-default-200 p-4">
                {{ $items->links('vendor.pagination.tailwind-custom') }}
            </div>
        </div>
    </div>
@endsection

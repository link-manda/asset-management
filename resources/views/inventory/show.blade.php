@extends('layouts.app')

@section('title', 'Detail Item: ' . $item->item_code)

@section('content')
    @include('layouts.partials/page-title', [
        'subtitle' => 'Inventory', 
        'title' => 'Item Specification',
        'breadcrumbs' => [
            ['label' => 'Global List', 'url' => route('inventory.index')],
            ['label' => 'Item Details', 'url' => null],
        ]
    ])

    <div class="grid lg:grid-cols-12 grid-cols-1 gap-4">
        {{-- Left Column: Identity (3/12) --}}
        <div class="lg:col-span-3 space-y-4">
            <div class="card">
                <div class="card-body p-4 text-center">
                    <div class="size-16 bg-primary/10 text-primary rounded-xl flex items-center justify-center mx-auto mb-3">
                        <i class="size-8" data-lucide="package"></i>
                    </div>
                    <h4 class="text-base font-black text-default-800 mb-0.5 tracking-tight">{{ $item->item_code }}</h4>
                    <p class="text-[10px] text-default-400 font-medium mb-3">SN: {{ $item->serial_number ?? 'N/A' }}</p>
                    
                    @php
                        $sClasses = [
                            'Available' => 'bg-success/15 text-success',
                            'Deployed' => 'bg-primary/15 text-primary',
                            'Maintenance' => 'bg-warning/15 text-warning',
                            'Broken' => 'bg-danger/15 text-danger',
                            'Disposed' => 'bg-danger text-white',
                        ];
                        $class = $sClasses[$item->status] ?? 'bg-default-100 text-default-500';
                    @endphp
                    <span class="inline-flex py-0.5 px-2.5 rounded-full text-[9px] font-black uppercase {{ $class }} mb-4">
                        {{ $item->status }}
                    </span>

                    <div class="space-y-1.5">
                        <form action="{{ route('inventory.bulk-print') }}" method="POST" target="_blank">
                            @csrf
                            <input type="hidden" name="ids[]" value="{{ $item->id }}">
                            <button type="submit" class="btn btn-sm bg-primary text-white w-full py-1.5 flex items-center justify-center gap-1.5 text-[11px] font-bold">
                                <i class="size-3.5" data-lucide="printer"></i> Cetak Label
                            </button>
                        </form>
                        <a href="{{ route('inventory.edit', $item) }}" class="btn btn-sm bg-default-100 text-default-700 w-full py-1.5 flex items-center justify-center gap-1.5 hover:bg-default-200 transition-all text-[11px] font-bold">
                            <i class="size-3.5" data-lucide="edit-3"></i> Edit Spesifikasi
                        </a>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header border-b border-default-200 py-2 px-4 bg-default-50/50">
                    <h6 class="card-title text-[11px] font-bold uppercase tracking-widest text-default-600">Status & Lokasi</h6>
                </div>
                <div class="card-body p-3 space-y-3">
                    <div class="flex items-center gap-2.5">
                        <div class="size-7 bg-secondary/10 text-secondary rounded-lg flex items-center justify-center shrink-0">
                            <i class="size-3.5" data-lucide="map-pin"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="text-[9px] text-default-400 font-bold uppercase tracking-wider mb-0.5 leading-none">Lokasi</p>
                            <p class="text-[11px] text-default-800 font-bold truncate leading-none">{{ $item->location?->name ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <div class="size-7 bg-info/10 text-info rounded-lg flex items-center justify-center shrink-0">
                            <i class="size-3.5" data-lucide="activity"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="text-[9px] text-default-400 font-bold uppercase tracking-wider mb-0.5 leading-none">Kondisi</p>
                            <p class="text-[11px] text-default-800 font-bold leading-none">{{ $item->condition }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <div class="size-7 bg-success/10 text-success rounded-lg flex items-center justify-center shrink-0">
                            <i class="size-3.5" data-lucide="calendar"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="text-[9px] text-default-400 font-bold uppercase tracking-wider mb-0.5 leading-none">Perolehan</p>
                            <p class="text-[11px] text-default-800 font-bold leading-none">{{ \Carbon\Carbon::parse($item->purchase_date)->format('d M Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card overflow-hidden relative border-primary/20 border">
                <div class="card-body p-3 z-10 relative">
                    <div class="flex justify-between items-center mb-2.5">
                        <p class="text-[9px] font-black uppercase tracking-widest text-primary leading-none">QR Identification</p>
                        <i class="size-3 text-primary" data-lucide="qr-code"></i>
                    </div>
                    <div class="bg-primary/5 p-1.5 rounded-lg inline-block mb-2 border border-primary/10">
                        <div class="size-16 bg-white dark:bg-default-100 flex items-center justify-center rounded">
                            <i class="size-6 text-primary/40" data-lucide="scan-barcode"></i>
                        </div>
                    </div>
                    <p class="text-[9px] text-default-500 italic leading-snug">Scan to verify asset authenticity and history.</p>
                </div>
                <div class="absolute -bottom-4 -right-4 size-20 bg-primary/5 rounded-full blur-2xl"></div>
            </div>
        </div>

        {{-- Middle & Right Content: Comprehensive Data (9/12) --}}
        <div class="lg:col-span-9 space-y-4">
            <div class="grid lg:grid-cols-12 grid-cols-1 gap-4">
                {{-- Master Profile (7/12) --}}
                <div class="lg:col-span-7 card overflow-hidden">
                    <div class="card-header border-b border-default-200 py-2 px-4 flex justify-between items-center bg-default-50/50">
                        <h6 class="card-title text-[11px] font-bold uppercase tracking-widest text-default-600">Profil Katalog</h6>
                        <a href="{{ route('assets.show', $item->asset_id) }}" class="text-primary text-[9px] font-black hover:underline flex items-center gap-1 uppercase">DETAIL KATALOG <i class="size-3" data-lucide="arrow-right"></i></a>
                    </div>
                    <div class="card-body p-0">
                        <div class="flex flex-col sm:flex-row">
                            <div class="sm:w-28 w-full aspect-square bg-default-100 flex items-center justify-center shrink-0 overflow-hidden border-e border-default-200 relative group">
                                @if($item->asset?->images?->count() > 0)
                                    <img src="{{ $item->asset->images->first()->url }}" class="size-full object-cover transition-transform duration-500 group-hover:scale-110 cursor-zoom-in" onclick="zoomImage('{{ $item->asset->images->first()->url }}')">
                                @else
                                    <i class="size-8 text-default-300" data-lucide="image"></i>
                                @endif
                            </div>
                            <div class="grow p-3 min-w-0">
                                <h5 class="text-sm font-black text-default-800 mb-0.5 truncate uppercase tracking-tight">{{ $item->asset?->name }}</h5>
                                <p class="text-primary font-mono text-[10px] font-bold mb-2.5">{{ $item->asset?->asset_code }}</p>
                                <div class="grid grid-cols-2 gap-x-3 gap-y-2">
                                    <div>
                                        <p class="text-[8px] text-default-400 uppercase font-black tracking-widest mb-0.5">Kategori</p>
                                        <p class="text-[11px] font-bold text-default-700 truncate leading-none">{{ $item->asset?->category?->name }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[8px] text-default-400 uppercase font-black tracking-widest mb-0.5">Satuan</p>
                                        <p class="text-[11px] font-bold text-default-700 leading-none">{{ $item->asset?->uom?->name ?? 'Unit' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[8px] text-default-400 uppercase font-black tracking-widest mb-0.5">Harga Master</p>
                                        <p class="text-[11px] font-bold text-default-700 leading-none">Rp {{ number_format($item->asset?->price, 0, ',', '.') }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[8px] text-default-400 uppercase font-black tracking-widest mb-0.5">Brand/Merk</p>
                                        <p class="text-[11px] font-bold text-default-700 leading-none truncate">{{ $item->asset?->brand ?? '-' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Financial Summary (5/12) --}}
                <div class="lg:col-span-5 card h-full">
                    <div class="card-header border-b border-default-200 py-2 px-4 bg-default-50/50">
                        <h6 class="card-title text-[11px] font-bold uppercase tracking-widest text-default-600">Ringkasan Finansial</h6>
                    </div>
                    <div class="card-body p-3 h-full flex flex-col justify-between">
                        <div class="grid grid-cols-2 gap-2.5">
                            <div class="bg-primary/5 p-2 rounded-lg border border-primary/10">
                                <p class="text-[8px] text-primary font-black uppercase mb-0.5 tracking-widest leading-none">Nilai Buku</p>
                                <p class="text-sm font-black text-default-900 leading-tight">Rp {{ number_format($item->current_value, 0, ',', '.') }}</p>
                            </div>
                            <div class="bg-danger/5 p-2 rounded-lg border border-danger/10">
                                <p class="text-[8px] text-danger font-black uppercase mb-0.5 tracking-widest leading-none">Depresiasi</p>
                                <p class="text-sm font-black text-danger leading-tight">{{ number_format($item->depreciation_percentage, 1) }}%</p>
                            </div>
                        </div>
                        <div class="mt-3 flex items-center justify-between text-[9px] font-bold tracking-tight">
                            <div class="flex items-center gap-1">
                                <span class="text-default-400">UMUR TERPAKAI:</span>
                                <span class="text-default-800">{{ round($item->purchase_date->diffInMonths(now()), 1) }} BLN</span>
                            </div>
                            <div class="flex items-center gap-1 text-success">
                                <span class="text-default-400">SISA UMUR:</span>
                                <span>{{ max(0, $item->useful_life_months - round($item->purchase_date->diffInMonths(now()), 1)) }} BLN</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid lg:grid-cols-12 grid-cols-1 gap-4">
                {{-- Assignment History (4/12) --}}
                <div class="lg:col-span-4 card flex flex-col">
                    <div class="card-header border-b border-default-200 py-2 px-4 flex justify-between items-center bg-default-50/50">
                        <h6 class="card-title text-[11px] font-bold uppercase tracking-widest text-default-600">Riwayat Penugasan</h6>
                        <i class="size-3.5 text-default-400" data-lucide="history"></i>
                    </div>
                    <div class="card-body p-0 overflow-hidden grow">
                        <div class="overflow-x-auto h-full">
                            <table class="min-w-full divide-y divide-default-200 text-[10px]">
                                <thead class="bg-default-50">
                                    <tr>
                                        <th class="px-3 py-2 text-start font-black uppercase tracking-wider text-default-500">User</th>
                                        <th class="px-3 py-2 text-start font-black uppercase tracking-wider text-default-500">Periode</th>
                                        <th class="px-3 py-2 text-center font-black uppercase tracking-wider text-default-500">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-default-200">
                                    @forelse($item->assignments as $history)
                                        <tr class="hover:bg-default-50 transition-all">
                                            <td class="px-3 py-2 font-bold text-default-800">{{ $history->user->name }}</td>
                                            <td class="px-3 py-2 text-default-600 font-medium">
                                                {{ \Carbon\Carbon::parse($history->assigned_date)->format('d/m/y') }} - 
                                                {{ $history->return_date ? \Carbon\Carbon::parse($history->return_date)->format('d/m/y') : 'Now' }}
                                            </td>
                                            <td class="px-3 py-2 text-center">
                                                @if(!$history->return_date)
                                                    <span class="bg-primary text-white px-1.5 py-0.5 rounded text-[8px] font-black uppercase">ACTIVE</span>
                                                @else
                                                    <i class="size-3 text-success mx-auto" data-lucide="check-circle"></i>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="px-4 py-8 text-center text-default-400 italic">No history.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Simulation Table (8/12) --}}
                <div class="lg:col-span-8 card flex flex-col" x-data="{ tab: 'commercial' }">
                    <div class="card-header border-b border-default-200 py-2 px-4 flex justify-between items-center bg-default-50/50">
                        <div class="flex items-center gap-3">
                            <h6 class="card-title text-[11px] font-bold uppercase tracking-widest text-default-600">Depreciation</h6>
                            <div class="flex bg-default-200 p-0.5 rounded-md">
                                <button @click="tab = 'commercial'" :class="tab === 'commercial' ? 'bg-white text-primary shadow-sm' : 'text-default-500 hover:text-default-700'" class="px-2 py-0.5 rounded-sm text-[9px] font-black transition-all uppercase tracking-widest">Commercial</button>
                                <button @click="tab = 'fiscal'" :class="tab === 'fiscal' ? 'bg-white text-primary shadow-sm' : 'text-default-500 hover:text-default-700'" class="px-2 py-0.5 rounded-sm text-[9px] font-black transition-all uppercase tracking-widest">Fiscal</button>
                            </div>
                        </div>
                        <div class="flex items-center gap-1 text-[9px] font-black text-primary uppercase tracking-widest">
                            <i class="size-3" data-lucide="trending-down"></i> PROJECTION
                        </div>
                    </div>
                    
                    <div x-show="tab === 'commercial'" class="grow flex flex-col">
                        <div class="p-2 bg-primary/5 border-b border-primary/10 flex justify-between items-center px-4">
                            <span class="text-[9px] text-default-500 font-bold uppercase tracking-widest">Parameters:</span>
                            <span class="text-primary text-[9px] font-black uppercase tracking-tight">Life: {{ $item->useful_life_months }} Mos | Residual: Rp {{ number_format($item->residual_value, 0, ',', '.') }}</span>
                        </div>
                        <div class="overflow-x-auto max-h-[280px]" data-simplebar>
                            <table class="min-w-full divide-y divide-default-200 text-[10px]">
                                <thead class="bg-default-50 sticky top-0 z-10 shadow-sm">
                                    <tr>
                                        <th class="px-4 py-2 text-start font-black uppercase tracking-wider text-default-500">Month</th>
                                        <th class="px-4 py-2 text-end font-black uppercase tracking-wider text-default-500">Beginning Value</th>
                                        <th class="px-4 py-2 text-end font-black uppercase tracking-wider text-default-500">Depreciation</th>
                                        <th class="px-4 py-2 text-end font-black uppercase tracking-wider text-default-500">Book Value</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-default-200">
                                    @foreach($commercialSchedule as $row)
                                        @php $isCurrent = $row['month_year'] == now()->translatedFormat('F Y'); @endphp
                                        <tr class="{{ $isCurrent ? 'bg-primary/5 font-black' : '' }} hover:bg-default-50 transition-all">
                                            <td class="px-4 py-1.5 whitespace-nowrap uppercase">{{ $row['month_year'] }}</td>
                                            <td class="px-4 py-1.5 text-end text-default-500">Rp{{ number_format($row['beginning_value'], 0, ',', '.') }}</td>
                                            <td class="px-4 py-1.5 text-end text-danger font-bold">-Rp{{ number_format($row['depreciation_expense'], 0, ',', '.') }}</td>
                                            <td class="px-4 py-1.5 text-end font-bold {{ $isCurrent ? 'text-primary' : 'text-default-800' }}">Rp{{ number_format($row['ending_book_value'], 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div x-show="tab === 'fiscal'" class="grow flex flex-col" x-cloak>
                        <div class="p-2 bg-warning/5 border-b border-warning/10 flex justify-between items-center px-4">
                            <span class="text-[9px] text-default-500 font-bold uppercase tracking-widest">Fiscal Parameters (Tax):</span>
                            <span class="text-warning text-[9px] font-black uppercase tracking-tight">Group: {{ $item->effective_fiscal_group ?? 'N/A' }} | Life: {{ $item->fiscal_useful_life }} Mos</span>
                        </div>
                        <div class="overflow-x-auto max-h-[280px]" data-simplebar>
                            <table class="min-w-full divide-y divide-default-200 text-[10px]">
                                <thead class="bg-default-50 sticky top-0 z-10 shadow-sm">
                                    <tr>
                                        <th class="px-4 py-2 text-start font-black uppercase tracking-wider text-default-500">Month</th>
                                        <th class="px-4 py-2 text-end font-black uppercase tracking-wider text-default-500">Beginning Value</th>
                                        <th class="px-4 py-2 text-end font-black uppercase tracking-wider text-default-500">Depreciation</th>
                                        <th class="px-4 py-2 text-end font-black uppercase tracking-wider text-default-500">Book Value</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-default-200">
                                    @foreach($fiscalSchedule as $row)
                                        @php $isCurrent = $row['month_year'] == now()->translatedFormat('F Y'); @endphp
                                        <tr class="{{ $isCurrent ? 'bg-warning/5 font-black' : '' }} hover:bg-default-50 transition-all">
                                            <td class="px-4 py-1.5 whitespace-nowrap uppercase">{{ $row['month_year'] }}</td>
                                            <td class="px-4 py-1.5 text-end text-default-500">Rp{{ number_format($row['beginning_value'], 0, ',', '.') }}</td>
                                            <td class="px-4 py-1.5 text-end text-danger font-bold">-Rp{{ number_format($row['depreciation_expense'], 0, ',', '.') }}</td>
                                            <td class="px-4 py-1.5 text-end font-bold {{ $isCurrent ? 'text-warning' : 'text-default-800' }}">Rp{{ number_format($row['ending_book_value'], 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card-footer bg-default-50 py-1.5">
                        <p class="text-[8px] text-default-400 italic text-center font-bold tracking-widest uppercase">Straight Line Method | Residual Rp0 (Fiscal)</p>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header border-b border-default-200 py-2 px-4 flex justify-between items-center bg-default-50/50">
                    <h6 class="card-title text-[11px] font-bold uppercase tracking-widest text-default-600">Audit Trail & History</h6>
                    <span class="text-[9px] bg-default-100 text-default-500 px-2 py-0.5 rounded font-black tracking-widest uppercase">ACTIVITY LOG</span>
                </div>
                <div class="card-body p-4">
                    @include('layouts.partials.activity-log', ['activities' => $activities])
                </div>
            </div>
        </div>
    </div>

    @push('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function zoomImage(url) {
            Swal.fire({
                imageUrl: url,
                imageAlt: 'Asset Image',
                showCloseButton: true,
                showConfirmButton: false,
                width: 'auto',
                padding: '0',
                background: 'transparent',
                customClass: {
                    image: 'rounded-lg shadow-2xl border-4 border-white'
                }
            });
        }
    </script>
    @endpush
@endsection

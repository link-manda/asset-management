@extends('layouts.app')

@section('title', 'Detail Item: ' . $item->item_code)

@section('content')
    @include('layouts.partials/page-title', ['subtitle' => 'Inventory', 'title' => 'Item Specification'])

    <div class="grid lg:grid-cols-4 grid-cols-1 gap-6">
        {{-- Left: Item Identity & Quick Info --}}
        <div class="lg:col-span-1 space-y-6">
            <div class="card">
                <div class="card-body">
                    <div class="flex flex-col items-center text-center">
                        <div class="size-20 bg-primary/10 text-primary rounded-2xl flex items-center justify-center mb-4">
                            <i class="size-10" data-lucide="package"></i>
                        </div>
                        <h4 class="text-lg font-bold text-default-800 mb-1">{{ $item->item_code }}</h4>
                        <p class="text-default-500 text-xs mb-4">SN: {{ $item->serial_number ?? 'N/A' }}</p>
                        
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
                        <span class="inline-flex py-1 px-3 rounded-full text-[10px] font-bold uppercase {{ $class }} mb-6">
                            {{ $item->status }}
                        </span>

                        <div class="w-full space-y-2">
                            <form action="{{ route('inventory.bulk-print') }}" method="POST" target="_blank">
                                @csrf
                                <input type="hidden" name="ids[]" value="{{ $item->id }}">
                                <button type="submit" class="btn btn-sm bg-primary text-white w-full flex items-center justify-center gap-2">
                                    <i class="size-4" data-lucide="printer"></i> Cetak Label
                                </button>
                            </form>
                            @if($item->status == 'Available')
                                <a href="{{ route('items.checkout.create', $item) }}" class="btn btn-sm bg-info text-white w-full flex items-center justify-center gap-2">
                                    <i class="size-4" data-lucide="send"></i> Checkout Unit
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header border-b border-default-200">
                    <h6 class="card-title text-sm">Status & Lokasi</h6>
                </div>
                <div class="card-body p-4 space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="size-8 bg-secondary/10 text-secondary rounded-lg flex items-center justify-center shrink-0">
                            <i class="size-4" data-lucide="map-pin"></i>
                        </div>
                        <div class="overflow-hidden">
                            <p class="text-[10px] text-default-400 font-medium uppercase tracking-wider">Lokasi</p>
                            <p class="text-sm text-default-800 font-bold truncate">{{ $item->location?->name ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="size-8 bg-info/10 text-info rounded-lg flex items-center justify-center shrink-0">
                            <i class="size-4" data-lucide="activity"></i>
                        </div>
                        <div>
                            <p class="text-[10px] text-default-400 font-medium uppercase tracking-wider">Kondisi</p>
                            <p class="text-sm text-default-800 font-bold">{{ $item->condition }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="size-8 bg-success/10 text-success rounded-lg flex items-center justify-center shrink-0">
                            <i class="size-4" data-lucide="calendar"></i>
                        </div>
                        <div>
                            <p class="text-[10px] text-default-400 font-medium uppercase tracking-wider">Perolehan</p>
                            <p class="text-sm text-default-800 font-bold">{{ \Carbon\Carbon::parse($item->purchase_date)->format('d M Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card bg-default-900 text-white overflow-hidden relative">
                <div class="card-body p-4 z-10 relative">
                    <div class="flex justify-between items-center mb-4">
                        <p class="text-[10px] font-bold uppercase tracking-widest text-default-400">QR Identification</p>
                        <i class="size-4 text-primary" data-lucide="qr-code"></i>
                    </div>
                    <div class="bg-white p-2 rounded-lg inline-block mb-3">
                        {{-- Placeholder for QR --}}
                        <div class="size-20 bg-default-100 flex items-center justify-center">
                            <i class="size-8 text-default-400" data-lucide="scan-barcode"></i>
                        </div>
                    </div>
                    <p class="text-[10px] text-default-400 italic">Scan to verify asset authenticity and history.</p>
                </div>
                <div class="absolute -bottom-4 -right-4 size-24 bg-primary/20 rounded-full blur-2xl"></div>
            </div>
        </div>

        {{-- Right: Comprehensive Data --}}
        <div class="lg:col-span-3 space-y-6">
            {{-- Financial & Master Summary --}}
            <div class="grid md:grid-cols-2 grid-cols-1 gap-6">
                {{-- Master Profile Card --}}
                <div class="card overflow-hidden">
                    <div class="card-header border-b border-default-200 flex justify-between items-center bg-default-50/50">
                        <h6 class="card-title text-sm">Profil Katalog</h6>
                        <a href="{{ route('assets.show', $item->asset_id) }}" class="text-primary text-[10px] font-bold hover:underline flex items-center gap-1">DETAIL KATALOG <i class="size-3" data-lucide="arrow-right"></i></a>
                    </div>
                    <div class="card-body p-0">
                        <div class="flex flex-col md:flex-row">
                            <div class="md:w-32 w-full aspect-square md:aspect-auto bg-default-100 flex items-center justify-center shrink-0 overflow-hidden border-e border-default-200 relative group">
                                @if($item->asset?->images?->count() > 0)
                                    <img src="{{ $item->asset->images->first()->url }}" class="size-full object-cover transition-transform duration-500 group-hover:scale-110 cursor-zoom-in" onclick="zoomImage('{{ $item->asset->images->first()->url }}')">
                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center pointer-events-none">
                                        <i class="size-6 text-white" data-lucide="maximize-2"></i>
                                    </div>
                                @else
                                    <i class="size-10 text-default-300" data-lucide="image"></i>
                                @endif
                            </div>
                            <div class="grow p-4 min-w-0">
                                <h5 class="text-base font-bold text-default-800 mb-0.5 truncate">{{ $item->asset?->name }}</h5>
                                <p class="text-primary font-mono text-[11px] mb-3">{{ $item->asset?->asset_code }}</p>
                                <div class="grid grid-cols-2 gap-x-4 gap-y-2">
                                    <div>
                                        <p class="text-[10px] text-default-400 uppercase font-bold tracking-tighter">Kategori</p>
                                        <p class="text-xs font-bold text-default-700 truncate">{{ $item->asset?->category?->name }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] text-default-400 uppercase font-bold tracking-tighter">Satuan</p>
                                        <p class="text-xs font-bold text-default-700">{{ $item->asset?->uom?->name ?? 'Unit' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] text-default-400 uppercase font-bold tracking-tighter">Harga Master</p>
                                        <p class="text-xs font-bold text-default-700">Rp {{ number_format($item->asset?->price, 0, ',', '.') }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] text-default-400 uppercase font-bold tracking-tighter">Brand/Merk</p>
                                        <p class="text-xs font-bold text-default-700">{{ $item->asset?->brand ?? '-' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Financial Quick Stats --}}
                <div class="card">
                    <div class="card-header border-b border-default-200">
                        <h6 class="card-title text-sm">Ringkasan Finansial</h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-primary/5 p-3 rounded-xl border border-primary/10">
                                <p class="text-[10px] text-primary font-bold uppercase mb-1">Nilai Buku</p>
                                <p class="text-lg font-bold text-default-900">Rp {{ number_format($item->current_value, 0, ',', '.') }}</p>
                            </div>
                            <div class="bg-default-50 p-3 rounded-xl border border-default-200">
                                <p class="text-[10px] text-default-500 font-bold uppercase mb-1">Depresiasi</p>
                                <p class="text-lg font-bold text-danger">{{ number_format($item->depreciation_percentage, 1) }}%</p>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center justify-between text-[11px]">
                            <div class="flex items-center gap-1.5">
                                <span class="text-default-500">Umur Terpakai:</span>
                                <span class="font-bold text-default-800">{{ round($item->purchase_date->diffInMonths(now()), 1) }} Bulan</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <span class="text-default-500">Sisa Umur:</span>
                                <span class="font-bold text-success">{{ max(0, $item->useful_life_months - round($item->purchase_date->diffInMonths(now()), 1)) }} Bulan</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Activity & Simulation Tabs/Grid --}}
            <div class="grid lg:grid-cols-5 grid-cols-1 gap-6">
                {{-- Riwayat Assignment (2/5) --}}
                <div class="lg:col-span-2 card">
                    <div class="card-header border-b border-default-200 flex justify-between items-center">
                        <h6 class="card-title text-sm">Riwayat Penugasan</h6>
                        <i class="size-4 text-default-400" data-lucide="history"></i>
                    </div>
                    <div class="card-body p-0">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-default-200 text-[11px]">
                                <thead class="bg-default-50">
                                    <tr>
                                        <th class="px-4 py-2 text-start font-bold">User</th>
                                        <th class="px-4 py-2 text-start font-bold">Periode</th>
                                        <th class="px-4 py-2 text-center font-bold">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-default-200">
                                    @forelse($item->assignments as $history)
                                        <tr class="hover:bg-default-50 transition-all">
                                            <td class="px-4 py-3">
                                                <div class="font-bold text-default-800">{{ $history->user->name }}</div>
                                            </td>
                                            <td class="px-4 py-3 text-default-600">
                                                {{ \Carbon\Carbon::parse($history->assigned_date)->format('d/m/y') }} - 
                                                {{ $history->return_date ? \Carbon\Carbon::parse($history->return_date)->format('d/m/y') : 'Now' }}
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                @if(!$history->return_date)
                                                    <span class="bg-primary/10 text-primary px-2 py-0.5 rounded-full text-[10px] font-bold">ACTIVE</span>
                                                @else
                                                    <span class="text-default-400"><i class="size-3" data-lucide="check-circle"></i></span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="px-4 py-8 text-center text-default-400 italic">No assignment history.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Simulation Table (3/5) --}}
                <div class="lg:col-span-3 card" x-data="{ tab: 'commercial' }">
                    <div class="card-header border-b border-default-200 flex justify-between items-center bg-default-50/50">
                        <div class="flex items-center gap-4">
                            <h6 class="card-title text-sm">Simulasi Penyusutan</h6>
                            <div class="flex bg-default-200 p-0.5 rounded-lg">
                                <button @click="tab = 'commercial'" :class="tab === 'commercial' ? 'bg-white text-primary shadow-sm' : 'text-default-500 hover:text-default-700'" class="px-3 py-1 rounded-md text-[10px] font-bold transition-all uppercase tracking-wider">Komersial</button>
                                <button @click="tab = 'fiscal'" :class="tab === 'fiscal' ? 'bg-white text-primary shadow-sm' : 'text-default-500 hover:text-default-700'" class="px-3 py-1 rounded-md text-[10px] font-bold transition-all uppercase tracking-wider">Fiskal</button>
                            </div>
                        </div>
                        <div class="flex items-center gap-1 text-[10px] font-bold text-primary px-2 py-1">
                            <i class="size-3" data-lucide="trending-down"></i> PROYEKSI
                        </div>
                    </div>
                    
                    {{-- Commercial Table --}}
                    <div x-show="tab === 'commercial'" class="card-body p-0">
                        <div class="p-3 bg-primary/5 border-b border-primary/10">
                            <div class="flex justify-between text-[10px]">
                                <span class="text-default-500 uppercase font-bold">Parameter Komersial:</span>
                                <span class="text-primary font-black uppercase">Umur: {{ $item->useful_life_months }} Bln | Sisa: Rp {{ number_format($item->residual_value, 0, ',', '.') }}</span>
                            </div>
                        </div>
                        <div class="overflow-x-auto max-h-[350px]" data-simplebar>
                            <table class="min-w-full divide-y divide-default-200 text-[11px]">
                                <thead class="bg-default-50 sticky top-0 z-10">
                                    <tr>
                                        <th class="px-4 py-2 text-start font-bold">Bulan</th>
                                        <th class="px-4 py-2 text-end font-bold">Nilai Awal</th>
                                        <th class="px-4 py-2 text-end font-bold">Penyusutan</th>
                                        <th class="px-4 py-2 text-end font-bold">Nilai Buku</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-default-200">
                                    @forelse($commercialSchedule as $row)
                                        @php $isCurrent = $row['month_year'] == now()->translatedFormat('F Y'); @endphp
                                        <tr class="{{ $isCurrent ? 'bg-primary/5 font-bold' : '' }} hover:bg-default-50 transition-all">
                                            <td class="px-4 py-2 whitespace-nowrap">
                                                @if($isCurrent)
                                                    <span class="size-2 bg-primary rounded-full inline-block me-1 animate-pulse"></span>
                                                @endif
                                                {{ $row['month_year'] }}
                                            </td>
                                            <td class="px-4 py-2 text-end text-default-500">Rp {{ number_format($row['beginning_value'], 0, ',', '.') }}</td>
                                            <td class="px-4 py-2 text-end text-danger">-Rp {{ number_format($row['depreciation_expense'], 0, ',', '.') }}</td>
                                            <td class="px-4 py-2 text-end font-bold {{ $isCurrent ? 'text-primary' : 'text-default-800' }}">Rp {{ number_format($row['ending_book_value'], 0, ',', '.') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-4 py-8 text-center text-default-400 italic">Financial data incomplete.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Fiscal Table --}}
                    <div x-show="tab === 'fiscal'" class="card-body p-0" x-cloak>
                        <div class="p-3 bg-warning/5 border-b border-warning/10">
                            <div class="flex justify-between text-[10px]">
                                <span class="text-default-500 uppercase font-bold">Parameter Fiskal (Pajak):</span>
                                <span class="text-warning font-black uppercase">Group: {{ $item->effective_fiscal_group ?? 'N/A' }} ({{ $item->fiscal_useful_life }} Bln) | Sisa: Rp 0</span>
                            </div>
                        </div>
                        <div class="overflow-x-auto max-h-[350px]" data-simplebar>
                            <table class="min-w-full divide-y divide-default-200 text-[11px]">
                                <thead class="bg-default-50 sticky top-0 z-10">
                                    <tr>
                                        <th class="px-4 py-2 text-start font-bold">Bulan</th>
                                        <th class="px-4 py-2 text-end font-bold">Nilai Awal</th>
                                        <th class="px-4 py-2 text-end font-bold">Penyusutan</th>
                                        <th class="px-4 py-2 text-end font-bold">Nilai Buku</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-default-200">
                                    @forelse($fiscalSchedule as $row)
                                        @php $isCurrent = $row['month_year'] == now()->translatedFormat('F Y'); @endphp
                                        <tr class="{{ $isCurrent ? 'bg-warning/5 font-bold' : '' }} hover:bg-default-50 transition-all">
                                            <td class="px-4 py-2 whitespace-nowrap">
                                                @if($isCurrent)
                                                    <span class="size-2 bg-warning rounded-full inline-block me-1 animate-pulse"></span>
                                                @endif
                                                {{ $row['month_year'] }}
                                            </td>
                                            <td class="px-4 py-2 text-end text-default-500">Rp {{ number_format($row['beginning_value'], 0, ',', '.') }}</td>
                                            <td class="px-4 py-2 text-end text-danger">-Rp {{ number_format($row['depreciation_expense'], 0, ',', '.') }}</td>
                                            <td class="px-4 py-2 text-end font-bold {{ $isCurrent ? 'text-warning' : 'text-default-800' }}">Rp {{ number_format($row['ending_book_value'], 0, ',', '.') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-4 py-8 text-center text-default-400 italic">
                                                @if(!$item->effective_fiscal_group)
                                                    <div class="flex flex-col items-center">
                                                        <i class="size-8 text-warning/30 mb-2" data-lucide="alert-circle"></i>
                                                        <span>Kelompok Fiskal Belum Diset.</span>
                                                    </div>
                                                @else
                                                    Financial data incomplete.
                                                @endif
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card-footer bg-default-50 py-2">
                        <p class="text-[10px] text-default-500 italic text-center">Penyusutan fiskal menggunakan metode garis lurus dengan nilai sisa Rp 0 sesuai aturan perpajakan.</p>
                    </div>
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

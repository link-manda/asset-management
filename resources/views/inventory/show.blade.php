@extends('layouts.app')

@section('title', 'Detail Item: ' . $item->item_code)

@section('content')
    @include('layouts.partials/page-title', ['subtitle' => 'Inventory', 'title' => 'Item Specification'])

    <div class="grid lg:grid-cols-3 grid-cols-1 gap-6">
        {{-- Left: Item Info --}}
        <div class="lg:col-span-1">
            <div class="card">
                <div class="card-body text-center">
                    <div class="size-24 bg-primary/10 text-primary rounded-xl flex items-center justify-center mx-auto mb-4">
                        <i class="size-12" data-lucide="package"></i>
                    </div>
                    <h4 class="text-xl font-bold text-default-800 mb-1">{{ $item->item_code }}</h4>
                    <p class="text-default-500 text-sm mb-4">SN: {{ $item->serial_number ?? 'N/A' }}</p>
                    
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
                    <span class="inline-flex py-1 px-3 rounded-full text-xs font-bold uppercase {{ $class }} mb-6">
                        {{ $item->status }}
                    </span>

                    <div class="grid grid-cols-1 gap-2">
                        <form action="{{ route('inventory.bulk-print') }}" method="POST" target="_blank">
                            @csrf
                            <input type="hidden" name="ids[]" value="{{ $item->id }}">
                            <button type="submit" class="btn bg-primary text-white w-full">
                                <i class="size-4 me-2" data-lucide="printer"></i> Cetak Label
                            </button>
                        </form>
                        <a href="{{ route('inventory.index') }}" class="btn border-default-200 text-default-600 w-full">Kembali</a>
                    </div>
                </div>
            </div>

            <div class="card mt-6">
                <div class="card-header">
                    <h6 class="card-title">Lokasi & Kondisi</h6>
                </div>
                <div class="card-body">
                    <div class="space-y-4">
                        <div class="flex items-start gap-3">
                            <div class="size-8 bg-secondary/10 text-secondary rounded flex items-center justify-center shrink-0">
                                <i class="size-4" data-lucide="map-pin"></i>
                            </div>
                            <div>
                                <p class="text-xs text-default-400 font-medium">Lokasi Saat Ini</p>
                                <p class="text-sm text-default-800 font-bold">{{ $item->location?->name ?? 'Belum Ditentukan' }}</p>
                                <p class="text-[10px] text-default-500">{{ $item->location?->address }}</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="size-8 bg-info/10 text-info rounded flex items-center justify-center shrink-0">
                                <i class="size-4" data-lucide="activity"></i>
                            </div>
                            <div>
                                <p class="text-xs text-default-400 font-medium">Kondisi Terakhir</p>
                                <p class="text-sm text-default-800 font-bold">{{ $item->condition }}</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="size-8 bg-success/10 text-success rounded flex items-center justify-center shrink-0">
                                <i class="size-4" data-lucide="calendar"></i>
                            </div>
                            <div>
                                <p class="text-xs text-default-400 font-medium">Tanggal Perolehan</p>
                                <p class="text-sm text-default-800 font-bold">{{ \Carbon\Carbon::parse($item->purchase_date)->format('d F Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mt-6">
                <div class="card-header flex justify-between items-center">
                    <h6 class="card-title">Financial / Depreciation</h6>
                    <i class="size-4 text-primary" data-lucide="trending-down"></i>
                </div>
                <div class="card-body">
                    <div class="mb-5">
                        <div class="flex justify-between text-xs mb-1">
                            <span class="text-default-500">Current Book Value</span>
                            <span class="font-bold text-primary">Rp {{ number_format($item->current_value, 0, ',', '.') }}</span>
                        </div>
                        <div class="w-full bg-default-100 rounded-full h-1.5">
                            <div class="bg-primary h-1.5 rounded-full" style="width: {{ 100 - $item->depreciation_percentage }}%"></div>
                        </div>
                        <p class="text-[10px] text-default-400 mt-1 italic">* Berdasarkan metode penyusutan garis lurus.</p>
                    </div>

                    <div class="space-y-3">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-default-500">Harga Perolehan</span>
                            <span class="font-medium text-default-800">Rp {{ number_format($item->purchase_price, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-default-500">Nilai Sisa (Residual)</span>
                            <span class="font-medium text-default-800">Rp {{ number_format($item->residual_value, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-default-500">Umur Ekonomis</span>
                            <span class="font-medium text-default-800">{{ $item->useful_life_months }} Bulan</span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-default-500">Umur Terpakai</span>
                            <span class="font-medium {{ $item->depreciation_percentage >= 100 ? 'text-danger' : 'text-default-800' }}">
                                {{ $item->purchase_date->diffInMonths(now()) }} Bulan
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right: Master Info & History --}}
        <div class="lg:col-span-2">
            <div class="card mb-6">
                <div class="card-header flex justify-between items-center">
                    <h6 class="card-title">Profil Katalog (Master Asset)</h6>
                    <a href="{{ route('assets.show', $item->asset_id) }}" class="text-primary text-sm font-bold hover:underline">Lihat Katalog <i class="size-3 inline" data-lucide="external-link"></i></a>
                </div>
                <div class="card-body">
                    <div class="flex flex-col md:flex-row gap-6">
                        <div class="size-24 bg-default-100 rounded-lg flex items-center justify-center shrink-0">
                            @if($item->asset?->images?->count() > 0)
                                <img src="{{ asset('storage/' . $item->asset->images->first()->image_path) }}" class="size-full object-cover rounded-lg">
                            @else
                                <i class="size-10 text-default-400" data-lucide="image"></i>
                            @endif
                        </div>
                        <div class="grow">
                            <h5 class="text-lg font-bold text-default-800 mb-1">{{ $item->asset?->name }}</h5>
                            <p class="text-primary font-mono text-sm mb-3">Master Code: {{ $item->asset?->asset_code }}</p>
                            <div class="flex flex-wrap gap-4 text-sm">
                                <div class="flex items-center gap-1.5 text-default-600">
                                    <span class="font-medium">Kategori:</span>
                                    <span class="bg-default-100 px-2 py-0.5 rounded">{{ $item->asset?->category?->name ?? 'N/A' }}</span>
                                </div>
                                <div class="flex items-center gap-1.5 text-default-600">
                                    <span class="font-medium">Harga Master:</span>
                                    <span>Rp {{ number_format($item->asset?->price, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header border-b border-default-200">
                    <div class="flex items-center justify-between">
                        <h6 class="card-title">Riwayat Penugasan Unit</h6>
                        @if($item->status == 'Available')
                            <a href="{{ route('items.checkout.create', $item) }}" class="btn btn-sm bg-primary text-white">Checkout Unit Ini</a>
                        @endif
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-default-200 text-sm">
                            <thead class="bg-default-50">
                                <tr>
                                    <th class="px-4 py-3 text-start">Peminjam</th>
                                    <th class="px-4 py-3 text-start">Tgl Keluar</th>
                                    <th class="px-4 py-3 text-start">Tgl Kembali</th>
                                    <th class="px-4 py-3 text-start">Kondisi Keluar</th>
                                    <th class="px-4 py-3 text-start">Kondisi Masuk</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-default-200">
                                @forelse($item->assignments as $history)
                                    <tr>
                                        <td class="px-4 py-3 font-medium text-default-800">{{ $history->user->name }}</td>
                                        <td class="px-4 py-3">{{ \Carbon\Carbon::parse($history->assigned_date)->format('d/m/Y') }}</td>
                                        <td class="px-4 py-3">
                                            @if($history->return_date)
                                                {{ \Carbon\Carbon::parse($history->return_date)->format('d/m/Y') }}
                                            @else
                                                <span class="text-primary font-bold">ACTIVE</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-default-500">{{ $history->condition_on_checkout }}</td>
                                        <td class="px-4 py-3 text-default-500">{{ $history->condition_on_return ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-8 text-center text-default-400 italic">Belum ada riwayat penugasan untuk unit ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

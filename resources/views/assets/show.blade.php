@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<style>
    .swiper { width: 100%; height: 100%; }
    .swiper-slide { text-align: center; display: flex; justify-content: center; align-items: center; }
    .swiper-slide img { display: block; width: 100%; height: 100%; object-cover: cover; }
    .swiper-button-next, .swiper-button-prev { color: #fff; text-shadow: 0 0 2px rgba(0,0,0,0.5); }
    .swiper-pagination-bullet-active { background: #4f46e5 !important; }
</style>
@endsection

@section('title', 'Detail Asset: ' . $asset->name)

@section('content')
    @include('layouts.partials/page-title', ['subtitle' => 'Assets', 'title' => 'Asset Overview'])

    <div class="grid lg:grid-cols-3 grid-cols-1 lg:gap-5">
        <div class="col-span-1">
            <div class="sticky top-24">
                <div class="card mb-5">
                    <div class="card-body">
                        @if($asset->images->count() > 0)
                            <div class="swiper mySwiper rounded-md overflow-hidden mb-5 aspect-square lg:aspect-video bg-default-100 border border-default-200 shadow-sm group">
                                <div class="swiper-wrapper">
                                    @foreach($asset->images as $image)
                                        <div class="swiper-slide">
                                            <img src="{{ $image->url }}" class="w-full h-full object-cover cursor-zoom-in" onclick="openImageModal('{{ $image->url }}')">
                                        </div>
                                    @endforeach
                                </div>
                                <div class="swiper-button-next opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                <div class="swiper-button-prev opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                <div class="swiper-pagination"></div>
                            </div>
                        @else
                            <div class="rounded-md bg-info/10 flex items-center justify-center py-12 mb-5">
                                <i class="size-24 text-info/30" data-lucide="package"></i>
                            </div>
                        @endif
                        
                        <div class="grid grid-cols-2 gap-2 mt-4">
                            @if($asset->status == 'Available')
                                <a href="{{ route('assets.checkout.create', $asset) }}" class="bg-primary w-full rounded btn text-white hover:bg-primary-600">
                                    <i class="size-4 me-1" data-lucide="log-out"></i> Checkout
                                </a>
                            @elseif($asset->status == 'Deployed')
                                <button data-hs-overlay="#modal-checkin" class="bg-success w-full rounded btn text-white hover:bg-success-600">
                                    <i class="size-4 me-1" data-lucide="log-in"></i> Checkin
                                </button>
                            @endif
                            <a href="{{ route('assets.edit', $asset) }}" class="border border-default-200 w-full rounded btn text-default-700 hover:bg-default-100 border-dashed">
                                <i class="size-4 me-1" data-lucide="edit"></i> Edit
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card mb-5">
                    <div class="card-body border-b border-b-default-200">
                        <div class="flex justify-between items-center">
                            <h6 class="text-default-800 font-semibold text-[15px] flex items-center gap-1.25">
                                <i class="size-4" data-lucide="qr-code"></i>
                                Identifikasi Aset
                            </h6>
                            <a href="{{ route('assets.print', $asset) }}" target="_blank" class="text-primary hover:text-primary-600 transition-all">
                                <i class="size-4" data-lucide="printer"></i>
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="flex flex-col items-center gap-4">
                            <div class="p-2 bg-white border border-default-200 rounded-lg">
                                {!! QrCode::size(120)->margin(1)->generate(route('assets.show', $asset)) !!}
                            </div>
                            <div class="w-full text-center">
                                <div class="inline-block bg-white p-2 border border-default-100 rounded">
                                    {!! DNS1D::getBarcodeSVG($asset->asset_code, 'C128', 1.5, 33) !!}
                                </div>
                                <p class="mt-2 text-[10px] font-mono text-default-500 uppercase tracking-widest">{{ $asset->asset_code }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-5">
                    <div class="card-body border-b border-b-default-200">
                        <div class="flex justify-between flex-wrap gap-5">
                            <h6 class="text-default-800 font-semibold text-[15px] flex items-center gap-1.25">
                                <i class="size-4" data-lucide="info"></i>
                                Informasi Dasar & Keuangan
                            </h6>
                            <span class="inline-flex items-center gap-x-1.5 py-1 px-3 rounded text-xs font-semibold bg-primary/10 text-primary">
                                {{ $asset->uom?->name ?? 'N/A' }} ({{ $asset->uom?->symbol ?? 'unit' }})
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="flex flex-col gap-4">
                            <div class="flex justify-between">
                                <span class="text-default-500 text-sm">Kategori:</span>
                                <span class="text-default-800 font-medium text-sm">{{ $asset->category?->name ?? 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-default-500 text-sm">Harga Satuan:</span>
                                <span class="text-default-800 font-medium text-sm">Rp {{ number_format($asset->price, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-default-500 text-sm">Total Kuantitas:</span>
                                <span class="text-default-800 font-medium text-sm">{{ $asset->total_quantity }} {{ $asset->uom?->symbol }}</span>
                            </div>
                            <div class="flex justify-between border-t border-dashed border-default-200 pt-3">
                                <span class="text-default-800 font-bold text-sm uppercase tracking-wider">Total Nilai Aset:</span>
                                <span class="text-primary font-black text-lg">Rp {{ number_format($asset->total_value, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-5">
                    <div class="card-body border-b border-b-default-200 bg-default-50/50">
                        <h6 class="text-default-800 font-semibold text-[15px] flex items-center gap-1.25">
                            <i class="size-4 text-primary" data-lucide="layers"></i>
                            Distribusi Stok & Lokasi
                        </h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-default-100">
                                <thead class="bg-default-50">
                                    <tr class="text-[10px] font-bold text-default-500 uppercase tracking-wider">
                                        <th class="px-4 py-2 text-start">Lokasi</th>
                                        <th class="px-4 py-2 text-center">Qty</th>
                                        <th class="px-4 py-2 text-end">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-default-100">
                                    @foreach($asset->stocks as $stock)
                                        <tr class="text-xs hover:bg-default-50 transition-all">
                                            <td class="px-4 py-3">
                                                <div class="flex items-center gap-2">
                                                    <i class="size-3 text-default-400" data-lucide="map-pin"></i>
                                                    <span class="font-medium text-default-800">{{ $stock->location->name }}</span>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 text-center font-bold text-default-800">{{ $stock->quantity }}</td>
                                            <td class="px-4 py-3 text-end">
                                                @php
                                                    $stockStatusClasses = [
                                                        'Available' => 'bg-success/10 text-success',
                                                        'Deployed' => 'bg-primary/10 text-primary',
                                                        'Maintenance' => 'bg-warning/10 text-warning',
                                                        'Broken' => 'bg-danger/10 text-danger',
                                                    ];
                                                    $sClass = $stockStatusClasses[$stock->status] ?? 'bg-default-100 text-default-500';
                                                @endphp
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $sClass }}">
                                                    {{ $stock->status }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2 col-span-1">
            <div class="card">
                <div class="card-body">
                    <div class="flex justify-between items-center">
                        <span class="px-2.5 py-0.5 text-xs inline-block font-semibold rounded bg-primary/10 text-primary">Asset Details</span>
                        <div class="hs-dropdown relative inline-flex">
                            <button aria-expanded="false" aria-haspopup="menu" aria-label="Dropdown"
                                    class="hs-dropdown-toggle btn size-7.5 bg-default-200 hover:bg-default-600 text-default-500 hover:text-white"
                                    hs-dropdown-placement="bottom-end" type="button">
                                <i class="size-4" data-lucide="more-vertical"></i>
                            </button>
                            <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-32 z-50 bg-white shadow-md rounded-lg p-2 mt-2 dark:bg-default-50 dark:border dark:border-default-200" role="menu">
                                <a class="flex items-center gap-1.5 py-1.5 font-medium px-3 text-sm text-default-500 hover:bg-default-150 rounded"
                                   href="{{ route('assets.edit', $asset) }}">
                                    <i class="size-3" data-lucide="edit"></i>
                                    Edit
                                </a>
                                <form action="{{ route('assets.destroy', $asset) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full flex items-center gap-1.5 py-1.5 font-medium px-3 text-sm text-danger hover:bg-danger/10 rounded delete-confirm" data-name="Asset {{ $asset->name }}">
                                        <i class="size-3" data-lucide="trash-2"></i>
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <h5 class="mt-3 mb-1 text-2xl text-default-800 font-bold">{{ $asset->name }}</h5>
                    <p class="text-primary font-bold text-lg mb-5">#{{ $asset->asset_code }}</p>

                    <div class="grid md:grid-cols-2 gap-6 my-6">
                        <div class="p-4 border rounded-md border-default-200 bg-default-50">
                            <h6 class="mb-2 text-default-800 font-semibold flex items-center gap-2">
                                <i class="size-4 text-primary" data-lucide="calendar"></i>
                                Tanggal Perolehan
                            </h6>
                            <p class="text-default-600 text-sm">{{ $asset->purchase_date ? \Carbon\Carbon::parse($asset->purchase_date)->format('d F Y') : '-' }}</p>
                        </div>
                        <div class="p-4 border rounded-md border-default-200 bg-default-50">
                            <h6 class="mb-2 text-default-800 font-semibold flex items-center gap-2">
                                <i class="size-4 text-primary" data-lucide="map-pin"></i>
                                Lokasi Penempatan
                            </h6>
                            @php
                                $primaryStock = $asset->primary_stock;
                                $locationCount = $asset->stocks->count();
                            @endphp
                            @if($primaryStock)
                                <p class="text-default-800 font-bold text-sm mb-0.5">{{ $primaryStock->location->name }}</p>
                                <p class="text-default-500 text-xs truncate">
                                    {{ $primaryStock->location->address }}
                                </p>
                                @if($locationCount > 1)
                                    <div class="mt-2 flex items-center gap-1.5 text-primary">
                                        <i class="size-3" data-lucide="layers-2"></i>
                                        <span class="text-[10px] font-bold uppercase tracking-wider">& {{ $locationCount - 1 }} Lokasi Lainnya</span>
                                    </div>
                                @endif
                            @else
                                <p class="text-default-600 text-sm italic">Lokasi belum ditentukan</p>
                            @endif
                        </div>
                    </div>

                    @if($asset->status == 'Deployed' && $asset->currentAssignment)
                        <div class="mt-5 p-4 bg-primary/5 border border-primary/20 rounded-md flex items-center gap-4">
                            <div class="size-12 bg-primary text-white rounded-full flex items-center justify-center">
                                <i class="size-6" data-lucide="user"></i>
                            </div>
                            <div>
                                <p class="text-primary text-xs font-bold uppercase mb-1">Sedang Dipinjam Oleh:</p>
                                <h6 class="text-default-800 font-bold text-lg leading-none">{{ $asset->currentAssignment->user->name }}</h6>
                                <p class="text-default-500 text-xs mt-1">Mulai: {{ \Carbon\Carbon::parse($asset->currentAssignment->assigned_date)->format('d M Y') }}</p>
                            </div>
                        </div>
                    @endif

                    <div class="mt-8">
                        <h6 class="text-[15px] font-semibold text-default-800 mb-3">Deskripsi / Catatan:</h6>
                        <div class="p-4 bg-default-100 rounded-md text-default-600 text-sm leading-relaxed">
                            {{ $asset->notes ?? 'Tidak ada catatan tambahan untuk asset ini.' }}
                        </div>
                    </div>

                    <div class="mt-8">
                        <div class="flex justify-between items-center mb-4">
                            <h6 class="text-[15px] font-semibold text-default-800">Riwayat Peminjaman</h6>
                        </div>
                        <div class="overflow-x-auto border rounded-md border-default-200">
                            <table class="min-w-full divide-y divide-default-200">
                                <thead class="bg-default-100">
                                    <tr class="text-xs font-semibold text-default-600 uppercase">
                                        <th class="px-4 py-3 text-start">Peminjam</th>
                                        <th class="px-4 py-3 text-start">Tgl Pinjam</th>
                                        <th class="px-4 py-3 text-start">Tgl Kembali</th>
                                        <th class="px-4 py-3 text-start">Kondisi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-default-200">
                                    @forelse($asset->assignments as $history)
                                        <tr class="text-sm">
                                            <td class="px-4 py-3 font-medium text-default-800">{{ $history->user->name }}</td>
                                            <td class="px-4 py-3">{{ \Carbon\Carbon::parse($history->assigned_date)->format('d/m/Y') }}</td>
                                            <td class="px-4 py-3">
                                                @if($history->return_date)
                                                    {{ \Carbon\Carbon::parse($history->return_date)->format('d/m/Y') }}
                                                @else
                                                    <span class="text-primary font-medium italic">Active</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3">
                                                <p class="text-xs text-default-500"><span class="font-semibold text-default-700">Out:</span> {{ $history->condition_on_checkout }}</p>
                                                @if($history->condition_on_return)
                                                    <p class="text-xs text-default-500"><span class="font-semibold text-default-700">In:</span> {{ $history->condition_on_return }}</p>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-4 py-6 text-center text-default-400 italic">Belum ada riwayat.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL CHECKIN --}}
    <div id="modal-checkin" class="hs-overlay hidden size-full fixed top-0 start-0 z-80 overflow-x-hidden overflow-y-auto pointer-events-none">
        <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto min-h-[calc(100%-3.5rem)] flex items-center">
            <div class="flex flex-col bg-card border border-default-200 shadow-sm rounded-md pointer-events-auto">
                <div class="flex justify-between items-center py-3 px-4 border-b border-default-200">
                    <h3 class="font-bold text-default-800 text-lg">Checkin Asset (Pengembalian)</h3>
                    <button type="button" class="size-8 inline-flex justify-center items-center gap-x-2 rounded-full border border-transparent bg-default-100 text-default-800 hover:bg-default-200 focus:outline-none focus:bg-default-200 disabled:opacity-50 disabled:pointer-events-none" data-hs-overlay="#modal-checkin">
                        <i class="size-4" data-lucide="x"></i>
                    </button>
                </div>
                <form action="{{ route('assets.checkin', $asset) }}" method="POST">
                    @csrf
                    <div class="p-4 overflow-y-auto">
                        <div class="p-3 bg-primary/5 border border-primary/20 rounded-md mb-4 text-sm text-default-600">
                            Aset sedang dipinjam oleh: <b>{{ $asset->currentAssignment?->user->name ?? '-' }}</b>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-2" for="return_date">Tanggal Kembali</label>
                            <input type="date" name="return_date" id="return_date" class="form-input w-full" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-2" for="condition_on_return">Kondisi Saat Kembali</label>
                            <textarea name="condition_on_return" id="condition_on_return" rows="3" class="form-input w-full" placeholder="Misal: Normal, Ada goresan sedikit" required></textarea>
                        </div>
                    </div>
                    <div class="flex justify-end items-center gap-x-2 py-3 px-4 border-t border-default-200">
                        <button type="button" class="btn border-default-200 text-default-600" data-hs-overlay="#modal-checkin">Batal</button>
                        <button type="submit" class="btn bg-success text-white px-6">Konfirmasi Pengembalian</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@push('js')
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var swiper = new Swiper(".mySwiper", {
            pagination: {
                el: ".swiper-pagination",
                dynamicBullets: true,
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            loop: true,
        });
    });

    function openImageModal(url) {
        Swal.fire({
            imageUrl: url,
            imageAlt: 'Asset Image',
            showCloseButton: true,
            showConfirmButton: false,
            width: 'auto',
            padding: '0',
            background: 'transparent',
            customClass: {
                image: 'rounded-lg shadow-2xl border-4 border-white dark:border-default-100'
            }
        });
    }
</script>
@endpush
@endsection

@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<style>
    .swiper { width: 100%; height: 100%; }
    .swiper-slide { text-align: center; display: flex; justify-content: center; align-items: center; }
    .swiper-slide img { display: block; width: 100%; height: 100%; object-fit: cover; }
    .swiper-button-next, .swiper-button-prev { color: #fff; text-shadow: 0 0 2px rgba(0,0,0,0.5); }
    .swiper-pagination-bullet-active { background: #4f46e5 !important; }
</style>
@endsection

@section('title', 'Detail Asset: ' . $asset->name)

@section('content')
    @include('layouts.partials/page-title', ['subtitle' => 'Assets', 'title' => 'Master Asset Overview'])

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

                        <div class="grid grid-cols-1 gap-2 mt-4">
                            <a href="{{ route('assets.edit', $asset) }}" class="border border-default-200 w-full rounded btn text-default-700 hover:bg-default-100 border-dashed">
                                <i class="size-4 me-1" data-lucide="edit"></i> Edit Profil Master
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card mb-5">
                    <div class="card-body">
                        <div class="flex flex-col items-center justify-center p-6 bg-primary/5 rounded-lg border border-dashed border-primary/20 text-center">
                            <i class="size-10 text-primary/30 mb-3" data-lucide="package"></i>
                            <h6 class="text-xs font-black text-primary/60 uppercase tracking-[0.2em] mb-1">Catalog Master</h6>
                            <p class="text-[10px] text-default-500 italic">Gunakan Menu Inventory untuk operasional unit.</p>
                        </div>
                    </div>
                </div>

                <div class="card mb-5">
                    <div class="card-body border-b border-b-default-200">
                        <div class="flex justify-between flex-wrap gap-5">
                            <h6 class="text-default-800 font-semibold text-[15px] flex items-center gap-1.25">
                                <i class="size-4" data-lucide="info"></i>
                                Informasi & Keuangan
                            </h6>
                            <span class="inline-flex items-center gap-x-1.5 py-1 px-3 rounded text-xs font-semibold bg-primary/10 text-primary">
                                {{ $asset->uom?->name ?? 'N/A' }}
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
                                <span class="text-default-500 text-sm">Total Unit Aktif:</span>
                                <span class="text-default-800 font-medium text-sm">{{ $asset->items->where('status', '!=', 'Disposed')->count() }} Unit</span>
                            </div>
                            <div class="flex justify-between border-t border-dashed border-default-200 pt-3">
                                <span class="text-default-800 font-bold text-sm uppercase tracking-wider">Total Nilai Aset:</span>
                                <span class="text-primary font-black text-lg">Rp {{ number_format($asset->total_value, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2 col-span-1">
            <div class="card mb-5">
                <div class="card-body">
                    <div class="flex justify-between items-center mb-4">
                        <h5 class="text-2xl text-default-800 font-bold">{{ $asset->name }}</h5>
                        <div class="hs-dropdown relative inline-flex">
                            <button aria-expanded="false" aria-haspopup="menu" class="hs-dropdown-toggle btn size-7.5 bg-default-200 hover:bg-default-600 text-default-500 hover:text-white" hs-dropdown-placement="bottom-end" type="button">
                                <i class="size-4" data-lucide="more-vertical"></i>
                            </button>
                            <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-32 z-50 bg-white shadow-md rounded-lg p-2 mt-2 dark:bg-default-50 dark:border dark:border-default-200" role="menu">
                                <a class="flex items-center gap-1.5 py-1.5 font-medium px-3 text-sm text-default-500 hover:bg-default-150 rounded" href="{{ route('assets.edit', $asset) }}">
                                    <i class="size-3" data-lucide="edit"></i> Edit Master
                                </a>
                                <form action="{{ route('assets.destroy', $asset) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="w-full flex items-center gap-1.5 py-1.5 font-medium px-3 text-sm text-danger hover:bg-danger/10 rounded delete-confirm" data-name="{{ $asset->name }}">
                                        <i class="size-3" data-lucide="trash-2"></i> Delete All
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="p-4 bg-default-100 rounded-md text-default-600 text-sm italic mb-5">
                        {{ $asset->notes ?? 'Tidak ada deskripsi katalog.' }}
                    </div>

                    <div class="flex justify-between items-center mb-4">
                        <h6 class="text-[15px] font-bold text-default-800 flex items-center gap-2">
                            <i class="size-5 text-primary" data-lucide="box"></i>
                            Daftar Unit Fisik (Physical Items)
                        </h6>
                        <button type="button" data-hs-overlay="#modal-add-item" class="btn btn-sm bg-primary text-white">
                            <i class="size-4 me-1" data-lucide="plus"></i> Tambah Unit Baru
                        </button>
                    </div>

                    <div class="overflow-x-auto border rounded-lg border-default-200">
                        <table class="min-w-full divide-y divide-default-200">
                            <thead class="bg-default-50">
                                <tr class="text-[10px] font-bold text-default-500 uppercase tracking-wider text-start">
                                    <th class="px-4 py-3">Barcode/Unit</th>
                                    <th class="px-4 py-3">Lokasi</th>
                                    <th class="px-4 py-3">Kondisi</th>
                                    <th class="px-4 py-3">Status</th>
                                    <th class="px-4 py-3 text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-default-200">
                                @forelse($asset->items as $item)
                                    <tr class="text-sm hover:bg-default-50 transition-all">
                                        <td class="px-4 py-3">
                                            <div class="flex flex-col">
                                                <span class="font-bold text-primary">{{ $item->item_code }}</span>
                                                <span class="text-[10px] text-default-400">SN: {{ $item->serial_number ?? '-' }}</span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="flex items-center gap-1.5 text-default-600 text-xs">
                                                <i class="size-3" data-lucide="map-pin"></i>
                                                {{ $item->location?->name ?? 'N/A' }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="text-xs">{{ $item->condition }}</span>
                                        </td>
                                        <td class="px-4 py-3">
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
                                            <span class="py-0.5 px-2 rounded text-[10px] font-bold {{ $class }}">
                                                {{ $item->status }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-end">
                                            <div class="flex justify-end gap-1">
                                                @if($item->status == 'Available')
                                                    <a href="{{ route('items.checkout.create', $item) }}" class="size-7 flex items-center justify-center bg-primary/10 text-primary rounded hover:bg-primary hover:text-white transition-all" title="Checkout">
                                                        <i class="size-3.5" data-lucide="log-out"></i>
                                                    </a>
                                                @elseif($item->status == 'Deployed')
                                                    @php
                                                        $borrowerName = $item->currentAssignment?->user?->name ?? 'Unknown';
                                                    @endphp
                                                    <button type="button"
                                                        data-hs-overlay="#modal-checkin"
                                                        data-item-id="{{ $item->id }}"
                                                        data-item-code="{{ $item->item_code }}"
                                                        data-borrower="{{ $borrowerName }}"
                                                        class="btn-checkin size-7 flex items-center justify-center bg-success/10 text-success rounded hover:bg-success hover:text-white transition-all" title="Checkin">
                                                        <i class="size-3.5" data-lucide="log-in"></i>
                                                    </button>
                                                @endif

                                                @if($item->status != 'Disposed')
                                                    <button type="button"
                                                        data-hs-overlay="#modal-disposal"
                                                        data-barcode="{{ $item->item_code }}"
                                                        class="btn-disposal size-7 flex items-center justify-center bg-danger/10 text-danger rounded hover:bg-danger hover:text-white transition-all" title="Disposal">
                                                        <i class="size-3.5" data-lucide="trash-2"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-8 text-center text-default-400 italic">Belum ada unit fisik terdaftar.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card mt-5">
                <div class="card-header border-b border-default-200">
                    <h6 class="card-title">Riwayat Transaksi Aset (Semua Unit)</h6>
                </div>
                <div class="card-body p-0">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-default-200 text-sm">
                            <thead class="bg-default-50">
                                <tr>
                                    <th class="px-4 py-3 text-start">Tanggal</th>
                                    <th class="px-4 py-3 text-start">Unit/Barcode</th>
                                    <th class="px-4 py-3 text-start">Aktivitas</th>
                                    <th class="px-4 py-3 text-start">User/Keterangan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-default-200">
                                @forelse($asset->assignments as $history)
                                    <tr>
                                        <td class="px-4 py-3">{{ $history->assigned_date->format('d/m/Y') }}</td>
                                        <td class="px-4 py-3 font-medium text-primary">{{ $history->item->item_code }}</td>
                                        <td class="px-4 py-3">
                                            @if($history->return_date)
                                                <span class="text-success font-medium">Returned</span>
                                            @else
                                                <span class="text-primary font-medium">Checkout</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3">
                                            <p class="font-bold">{{ $history->user->name }}</p>
                                            <p class="text-[10px] text-default-500">Kondisi: {{ $history->condition_on_checkout }}</p>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-4 py-6 text-center text-default-400 italic">Belum ada riwayat transaksi.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL CHECKIN (Dinamis via JS) --}}
    <div id="modal-checkin" class="hs-overlay hidden size-full fixed top-0 start-0 z-80 overflow-x-hidden overflow-y-auto pointer-events-none">
        <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto min-h-[calc(100%-3.5rem)] flex items-center">
            <div class="flex flex-col bg-card border border-default-200 shadow-sm rounded-md pointer-events-auto w-full">
                <div class="flex justify-between items-center py-3 px-4 border-b border-default-200">
                    <h3 class="font-bold text-default-800 text-lg">Checkin Unit: <span id="checkin-unit-name"></span></h3>
                    <button type="button" class="size-8 inline-flex justify-center items-center rounded-full bg-default-100 text-default-800 hover:bg-default-200" data-hs-overlay="#modal-checkin">
                        <i class="size-4" data-lucide="x"></i>
                    </button>
                </div>
                <form id="form-checkin" method="POST">
                    @csrf
                    <div class="p-4">
                        <div class="p-3 bg-primary/5 border border-primary/20 rounded-md mb-4 text-xs text-default-600">
                            Unit sedang dipinjam oleh: <b id="checkin-user-name"></b>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-2">Tanggal Kembali</label>
                            <input type="date" name="return_date" class="form-input w-full" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-2">Kondisi Saat Kembali</label>
                            <textarea name="condition_on_return" rows="3" class="form-input w-full" placeholder="Normal, Ada goresan, dll" required></textarea>
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

    {{-- MODAL DISPOSAL (Dinamis via JS) --}}
    <div id="modal-disposal" class="hs-overlay hidden size-full fixed top-0 start-0 z-80 overflow-x-hidden overflow-y-auto pointer-events-none">
        <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto flex items-center min-h-[calc(100%-3.5rem)]">
            <div class="flex flex-col bg-card border border-default-200 shadow-sm rounded-md pointer-events-auto w-full">
                <div class="flex justify-between items-center py-3 px-4 border-b border-default-200">
                    <h3 class="font-bold text-danger text-lg flex items-center gap-2">
                        <i class="size-5" data-lucide="trash-2"></i> Disposal Unit: <span id="disposal-unit-name"></span>
                    </h3>
                    <button type="button" class="size-8 inline-flex justify-center items-center rounded-full bg-default-100 text-default-800" data-hs-overlay="#modal-disposal">
                        <i class="size-4" data-lucide="x"></i>
                    </button>
                </div>
                <form action="{{ route('disposals.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="barcode" id="disposal-barcode">
                    <div class="p-5">
                        <div class="grid grid-cols-1 gap-4 text-sm">
                            <div>
                                <label class="inline-block mb-2 font-medium">Tanggal Disposal</label>
                                <input type="date" name="disposal_date" class="form-input w-full" value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div>
                                <label class="inline-block mb-2 font-medium">Alasan</label>
                                <select name="reason" class="form-input w-full" required onchange="togglePriceField(this.value)">
                                    <option value="Broken">Rusak Berat</option>
                                    <option value="Sold">Dijual</option>
                                    <option value="Lost">Hilang</option>
                                    <option value="Scrapped">Scrapped</option>
                                </select>
                            </div>
                            <div id="price-field" class="hidden">
                                <label class="inline-block mb-2 font-medium">Harga Jual</label>
                                <input type="number" name="selling_price" class="form-input w-full" placeholder="0">
                            </div>
                            <div>
                                <label class="inline-block mb-2 font-medium">Catatan</label>
                                <textarea name="notes" class="form-input w-full" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-end items-center gap-2 py-3 px-4 border-t border-default-200">
                        <button type="button" class="btn border-default-200" data-hs-overlay="#modal-disposal">Batal</button>
                        <button type="submit" class="btn bg-danger text-white px-6">Proses Disposal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Add New Unit --}}
    <div id="modal-add-item" class="hs-overlay hidden size-full fixed top-0 start-0 z-80 overflow-x-hidden overflow-y-auto pointer-events-none">
        <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto flex items-center min-h-[calc(100%-3.5rem)]">
            <div class="flex flex-col bg-white border border-default-200 shadow-sm rounded-md pointer-events-auto w-full">
                <div class="flex justify-between items-center py-3 px-4 border-b border-default-200">
                    <h3 class="font-bold text-default-800">Tambah Unit Fisik Baru</h3>
                    <button type="button" class="size-8 inline-flex justify-center items-center gap-x-2 rounded-full border border-transparent bg-default-100 text-default-800 hover:bg-default-200" data-hs-overlay="#modal-add-item">
                        <i class="size-4" data-lucide="x"></i>
                    </button>
                </div>
                <form action="{{ route('inventory.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="asset_id" value="{{ $asset->id }}">
                    <div class="p-5 space-y-4">
                        <div class="bg-primary/5 p-3 rounded text-xs text-primary font-medium flex items-center gap-2">
                            <i class="size-4" data-lucide="info"></i>
                            Unit baru akan menggunakan Master Code: <strong>{{ $asset->asset_code }}</strong>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-default-700 mb-1">Jumlah Unit yang Ditambah</label>
                            <input type="number" name="quantity" class="form-input" value="1" min="1" max="50" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-default-700 mb-1">Lokasi Penyimpanan</label>
                            <select name="location_id" class="form-select" required>
                                @foreach($locations as $location)
                                    <option value="{{ $location->id }}">{{ $location->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-default-700 mb-1">Kondisi Barang</label>
                            <select name="condition" class="form-select" required>
                                <option value="New">Baru (New)</option>
                                <option value="Good" selected>Baik (Good)</option>
                                <option value="Fair">Cukup (Fair)</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex justify-end items-center gap-2 py-3 px-4 border-t border-default-200">
                        <button type="button" class="btn border-default-200 text-default-600" data-hs-overlay="#modal-add-item">Batal</button>
                        <button type="submit" class="btn bg-primary text-white">Simpan Unit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@push('js')
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Swiper init
        new Swiper(".mySwiper", {
            pagination: { el: ".swiper-pagination", dynamicBullets: true },
            navigation: { nextEl: ".swiper-button-next", prevEl: ".swiper-button-prev" },
            loop: true,
        });

        // Modal Checkin Listener
        document.querySelectorAll('.btn-checkin').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-item-id');
                const code = this.getAttribute('data-item-code');
                const borrower = this.getAttribute('data-borrower');

                document.getElementById('checkin-unit-name').innerText = code;
                document.getElementById('checkin-user-name').innerText = borrower;
                document.getElementById('form-checkin').action = "/items/" + id + "/checkin";
            });
        });

        // Modal Disposal Listener
        document.querySelectorAll('.btn-disposal').forEach(btn => {
            btn.addEventListener('click', function() {
                const barcode = this.getAttribute('data-barcode');
                document.getElementById('disposal-unit-name').innerText = barcode;
                document.getElementById('disposal-barcode').value = barcode;
            });
        });
    });

    function togglePriceField(reason) {
        const field = document.getElementById('price-field');
        if (reason === 'Sold') field.classList.remove('hidden');
        else field.classList.add('hidden');
    }

    function openImageModal(url) {
        Swal.fire({
            imageUrl: url, imageAlt: 'Asset Image', showCloseButton: true, showConfirmButton: false, width: 'auto', padding: '0', background: 'transparent',
            customClass: { image: 'rounded-lg shadow-2xl border-4 border-white' }
        });
    }
</script>
@endpush
@endsection

@extends('layouts.app')

@section('title', 'Ajukan Perbaikan Aset')

@section('content')
    @include('layouts.partials/page-title', ['subtitle' => 'Maintenance', 'title' => 'Request Service'])

    <div class="grid lg:grid-cols-12 grid-cols-1 gap-6">
        <div class="lg:col-span-9 col-span-1">
            <div class="card">
                <div class="card-body">
                    <h6 class="mb-4 card-title text-base">Detail Pengajuan Perbaikan</h6>

                    <form action="{{ route('maintenances.store') }}" method="POST" x-data="assetPicker()">
                        @csrf

                        <div class="mb-6">
                            <label class="inline-block mb-2 text-sm text-default-800 font-medium">Pilih Unit Fisik yang Akan Diperbaiki</label>
                            
                            {{-- Selected Item Preview --}}
                            <div class="flex items-center gap-3 p-3 border border-default-200 rounded-md bg-default-50 mb-2" x-show="selectedItem">
                                <div class="size-10 bg-primary/10 rounded flex items-center justify-center">
                                    <i class="size-5 text-primary" data-lucide="package"></i>
                                </div>
                                <div class="flex-grow">
                                    <h6 class="text-sm font-bold text-default-800" x-text="selectedItem ? selectedItem.item_code + ' - ' + selectedItem.asset_name : ''"></h6>
                                    <p class="text-xs text-default-500" x-text="selectedItem ? 'SN: ' + (selectedItem.serial_number || '-') + ' | Lokasi: ' + (selectedItem.location_name || '-') : ''"></p>
                                </div>
                                <button type="button" @click="clearSelection" class="text-danger hover:text-danger-600">
                                    <i class="size-4" data-lucide="x-circle"></i>
                                </button>
                            </div>

                            <input type="hidden" name="asset_item_id" :value="selectedItem ? selectedItem.id : ''" required>

                            <button type="button" 
                                    class="btn border-primary text-primary hover:bg-primary hover:text-white w-full py-2 flex items-center justify-center gap-2"
                                    data-hs-overlay="#modal-asset-picker"
                                    x-show="!selectedItem">
                                <i class="size-4" data-lucide="search"></i> Cari & Pilih Aset...
                            </button>

                            @error('asset_item_id')
                                <p class="mt-1 text-danger text-xs">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- MODAL PICKER --}}
                        <div id="modal-asset-picker" class="hs-overlay hidden size-full fixed top-0 start-0 z-80 overflow-x-hidden overflow-y-auto pointer-events-none">
                            <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-3xl sm:w-full m-3 sm:mx-auto flex items-center min-h-[calc(100%-3.5rem)]">
                                <div class="flex flex-col bg-card border border-default-200 shadow-sm rounded-md pointer-events-auto w-full max-h-[80vh]">
                                    <div class="flex justify-between items-center py-3 px-4 border-b border-default-200">
                                        <h3 class="font-bold text-default-800 text-lg">Pilih Unit Aset</h3>
                                        <button type="button" class="size-8 inline-flex justify-center items-center gap-x-2 rounded-full border border-transparent bg-default-100 text-default-800 hover:bg-default-200 focus:outline-none focus:bg-default-200 disabled:opacity-50 disabled:pointer-events-none" data-hs-overlay="#modal-asset-picker">
                                            <i class="size-4" data-lucide="x"></i>
                                        </button>
                                    </div>
                                    <div class="p-4 border-b border-default-200 bg-default-50">
                                        <div class="relative">
                                            <input type="text" x-model="search" class="form-input ps-10" placeholder="Cari Kode, Nama, atau SN...">
                                            <i class="size-4 absolute start-3 top-1/2 -translate-y-1/2 text-default-400" data-lucide="search"></i>
                                        </div>
                                    </div>
                                    <div class="overflow-y-auto p-0">
                                        <table class="min-w-full divide-y divide-default-200">
                                            <thead class="bg-default-100 sticky top-0">
                                                <tr class="text-xs text-default-600 uppercase font-bold">
                                                    <th class="px-4 py-3 text-start">Kode / Nama</th>
                                                    <th class="px-4 py-3 text-start">Serial Number</th>
                                                    <th class="px-4 py-3 text-start">Lokasi</th>
                                                    <th class="px-4 py-3 text-center">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-default-200">
                                                <template x-for="item in filteredItems" :key="item.id">
                                                    <tr class="hover:bg-primary/5 transition-all cursor-pointer" @click="selectItem(item)">
                                                        <td class="px-4 py-3">
                                                            <div class="flex flex-col">
                                                                <span class="font-mono text-primary font-bold text-xs" x-text="item.item_code"></span>
                                                                <span class="text-sm font-medium text-default-800" x-text="item.asset_name"></span>
                                                            </div>
                                                        </td>
                                                        <td class="px-4 py-3 text-sm text-default-600" x-text="item.serial_number || '-'"></td>
                                                        <td class="px-4 py-3 text-sm text-default-600" x-text="item.location_name || '-'"></td>
                                                        <td class="px-4 py-3 text-center">
                                                            <button type="button" class="btn btn-sm bg-primary/10 text-primary hover:bg-primary hover:text-white transition-all">Pilih</button>
                                                        </td>
                                                    </tr>
                                                </template>
                                                <tr x-show="filteredItems.length === 0">
                                                    <td colspan="4" class="px-4 py-8 text-center text-default-500 italic">Data tidak ditemukan...</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="grid lg:grid-cols-2 grid-cols-1 gap-5 mb-5">
                            <div class="col-span-1">
                                <label class="inline-block mb-2 text-sm text-default-800 font-medium" for="maintenance_date">Tanggal Mulai Servis</label>
                                <input class="form-input" id="maintenance_date" name="maintenance_date" type="date" value="{{ old('maintenance_date', date('Y-m-d')) }}" required />
                            </div>
                            <div class="col-span-1">
                                <label class="inline-block mb-2 text-sm text-default-800 font-medium" for="status">Status Awal</label>
                                <select class="form-input" id="status" name="status">
                                    <option value="Scheduled" {{ old('status') == 'Scheduled' ? 'selected' : '' }}>Scheduled (Dijadwalkan)</option>
                                    <option value="In Progress" {{ old('status') == 'In Progress' ? 'selected' : '' }}>In Progress (Sedang Dikerjakan)</option>
                                    <option value="Completed" {{ old('status') == 'Completed' ? 'selected' : '' }}>Completed (Selesai)</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-5">
                            <label class="inline-block mb-2 text-sm text-default-800 font-medium" for="cost">Estimasi Biaya (IDR)</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 start-0 flex items-center ps-3 text-default-500 font-semibold">Rp</span>
                                <input class="form-input ps-10 @error('cost') border-danger @enderror" id="cost" name="cost" placeholder="0" type="number" value="{{ old('cost', 0) }}" required />
                            </div>
                            @error('cost')
                                <p class="mt-1 text-danger text-xs">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-5">
                            <label class="font-medium text-default-800 text-sm mb-2 inline-block" for="description">Keluhan / Deskripsi Kerusakan</label>
                            <textarea class="form-input @error('description') border-danger @enderror" id="description" name="description" placeholder="Jelaskan detail kerusakan atau alasan perbaikan..." rows="5" required>{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-danger text-xs">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mt-6 flex gap-3 md:justify-end border-t border-default-200 pt-5">
                            <a href="{{ route('maintenances.index') }}" class="btn border-default-200 text-default-600 hover:bg-default-100">Batal</a>
                            <button type="submit" class="text-white btn bg-primary px-10">Kirim Pengajuan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="lg:col-span-3 col-span-1">
            <div class="sticky top-24 space-y-6">
                <div class="card">
                    <div class="card-body">
                        <h6 class="mb-4 card-title text-base flex items-center gap-2">
                            <i class="size-4 text-primary" data-lucide="info"></i> Informasi Servis
                        </h6>
                        <div class="bg-primary/5 border border-primary/20 rounded-md p-4 mb-4">
                            <p class="text-xs text-default-600 leading-relaxed">
                                <span class="font-bold text-primary italic">Catatan Sistem:</span>
                                <br>Aset yang diajukan untuk perbaikan akan otomatis dikunci statusnya menjadi <span class="font-bold">"Maintenance"</span>.
                            </p>
                        </div>
                        <div class="space-y-3">
                            <div class="flex items-start gap-2">
                                <i class="size-4 text-success mt-0.5" data-lucide="check-circle-2"></i>
                                <p class="text-xs text-default-500">Aset tidak bisa dipinjam selama masa perbaikan.</p>
                            </div>
                            <div class="flex items-start gap-2">
                                <i class="size-4 text-success mt-0.5" data-lucide="check-circle-2"></i>
                                <p class="text-xs text-default-500">Biaya akan diakumulasikan ke total pengeluaran aset.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card bg-default-800 text-white">
                    <div class="card-body">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="size-10 bg-white/10 rounded flex items-center justify-center">
                                <i class="size-6 text-white" data-lucide="help-circle"></i>
                            </div>
                            <h6 class="font-bold">Butuh Bantuan?</h6>
                        </div>
                        <p class="text-xs text-default-300 leading-relaxed mb-4">
                            Jika aset rusak total, silakan ganti status aset menjadi "Broken" di halaman edit aset daripada mengajukan maintenance.
                        </p>
                        <a href="{{ route('assets.index') }}" class="text-xs font-bold text-primary hover:underline flex items-center gap-1">
                            Kembali ke Daftar Aset <i class="size-3" data-lucide="arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
<script>
    function assetPicker() {
        return {
            search: '',
            selectedItem: null,
            items: [
                @foreach($items as $item)
                {
                    id: {{ $item->id }},
                    item_code: '{{ $item->item_code }}',
                    asset_name: '{{ addslashes($item->asset->name) }}',
                    serial_number: '{{ $item->serial_number }}',
                    location_name: '{{ $item->location?->name }}',
                },
                @endforeach
            ],
            get filteredItems() {
                if (this.search === '') return this.items;
                const s = this.search.toLowerCase();
                return this.items.filter(i => 
                    i.item_code.toLowerCase().includes(s) || 
                    i.asset_name.toLowerCase().includes(s) || 
                    (i.serial_number && i.serial_number.toLowerCase().includes(s))
                );
            },
            selectItem(item) {
                this.selectedItem = item;
                // Close Modal using Preline API if available, or just via overlay click
                const modal = document.querySelector('#modal-asset-picker');
                if (window.HSOverlay) {
                    HSOverlay.close(modal);
                }
            },
            clearSelection() {
                this.selectedItem = null;
                this.search = '';
            }
        }
    }
</script>
@endpush

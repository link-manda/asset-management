@extends('layouts.app')

@section('title', 'Edit Unit Aset: ' . $item->item_code)

@section('content')
    @include('layouts.partials/page-title', ['subtitle' => 'Inventory', 'title' => 'Update Asset Specification'])

    <div class="max-w-6xl mx-auto">
        <form action="{{ route('inventory.update', $item) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Header: Fixed Identity (Read Only) - More Compact --}}
            <div class="card mb-4 border-primary/20 border bg-primary/5">
                <div class="card-body py-3 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="size-10 bg-primary text-white rounded-lg flex items-center justify-center shadow-md shadow-primary/20">
                            <i class="size-5" data-lucide="hash"></i>
                        </div>
                        <div>
                            <p class="text-[9px] text-primary font-black uppercase tracking-widest leading-none mb-1">Identitas Unit Tetap (Read-Only)</p>
                            <h3 class="text-lg font-black text-default-900 tracking-tight leading-none">{{ $item->item_code }}</h3>
                        </div>
                    </div>
                    <div class="text-end">
                        <p class="text-[9px] text-default-400 font-bold uppercase mb-0.5">Master Katalog</p>
                        <p class="text-sm font-bold text-default-700">{{ $item->asset->name }}</p>
                    </div>
                </div>
            </div>

            <div class="grid lg:grid-cols-12 grid-cols-1 gap-4">
                {{-- Column Left: Operational & Condition (5/12) --}}
                <div class="lg:col-span-5 space-y-4">
                    <div class="card">
                        <div class="card-header border-b border-default-200 bg-default-50/50 py-2 px-4 flex items-center gap-2">
                            <i class="size-3.5 text-primary" data-lucide="settings"></i>
                            <h6 class="card-title text-[11px] font-bold uppercase tracking-tight">Informasi Operasional</h6>
                        </div>
                        <div class="card-body p-4 space-y-3">
                            <div>
                                <label class="block font-bold text-default-700 text-[9px] uppercase tracking-widest mb-1.5" for="serial_number">Serial Number / IMEI</label>
                                <input class="form-input rounded-lg border-default-200 focus:border-primary py-2 text-sm" 
                                       id="serial_number" name="serial_number" value="{{ old('serial_number', $item->serial_number) }}" 
                                       placeholder="Misal: SN-9988776655" type="text" />
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block font-bold text-default-700 text-[9px] uppercase tracking-widest mb-1.5" for="location_id">Lokasi</label>
                                    <select class="form-select rounded-lg border-default-200 focus:border-primary py-2 text-sm" id="location_id" name="location_id" required>
                                        @foreach($locations as $loc)
                                            <option value="{{ $loc->id }}" {{ $item->location_id == $loc->id ? 'selected' : '' }}>{{ $loc->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block font-bold text-default-700 text-[9px] uppercase tracking-widest mb-1.5" for="condition">Kondisi Fisik</label>
                                    <select class="form-select rounded-lg border-default-200 focus:border-primary py-2 text-sm" id="condition" name="condition" required>
                                        <option value="Good" {{ $item->condition == 'Good' ? 'selected' : '' }}>Good (Baik)</option>
                                        <option value="Fair" {{ $item->condition == 'Fair' ? 'selected' : '' }}>Fair (Cukup)</option>
                                        <option value="Poor" {{ $item->condition == 'Poor' ? 'selected' : '' }}>Poor (Buruk)</option>
                                        <option value="Broken" {{ $item->condition == 'Broken' ? 'selected' : '' }}>Broken (Rusak)</option>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label class="block font-bold text-default-700 text-[9px] uppercase tracking-widest mb-1.5" for="status">Status Aset</label>
                                <select class="form-select rounded-lg border-default-200 focus:border-primary py-2 text-sm" id="status" name="status" required>
                                    @foreach($statuses as $status)
                                        <option value="{{ $status }}" {{ $item->status == $status ? 'selected' : '' }}>{{ $status }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="p-2.5 bg-warning/5 border border-warning/10 rounded flex gap-2">
                                <i class="size-3.5 text-warning shrink-0" data-lucide="alert-circle"></i>
                                <p class="text-[9px] text-warning/80 leading-tight">Berhati-hatilah mengubah status manual. Status 'Deployed' hanya boleh digunakan jika aset sedang dipinjam.</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Column Right: Financial Parameters (7/12) --}}
                <div class="lg:col-span-7 space-y-4">
                    <div class="card h-full flex flex-col">
                        <div class="card-header border-b border-default-200 bg-default-50/50 py-2 px-4 flex items-center gap-2">
                            <i class="size-3.5 text-success" data-lucide="coins"></i>
                            <h6 class="card-title text-[11px] font-bold uppercase tracking-tight">Parameter Finansial</h6>
                        </div>
                        <div class="card-body p-4 space-y-4 grow">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block font-bold text-default-700 text-[9px] uppercase tracking-widest mb-1.5" for="purchase_date">Tanggal Perolehan</label>
                                    <input class="form-input rounded-lg border-default-200 focus:border-primary py-2 text-sm" 
                                           id="purchase_date" name="purchase_date" value="{{ old('purchase_date', $item->purchase_date->format('Y-m-d')) }}" 
                                           type="date" required />
                                </div>
                                <div>
                                    <label class="block font-bold text-default-700 text-[9px] uppercase tracking-widest mb-1.5" for="fiscal_group">Grup Fiskal</label>
                                    <select class="form-select rounded-lg border-default-200 focus:border-primary py-2 text-sm" id="fiscal_group" name="fiscal_group">
                                        <option value="">(Inherit dari Kategori)</option>
                                        @foreach($fiscalGroups as $group)
                                            <option value="{{ $group }}" {{ $item->fiscal_group == $group ? 'selected' : '' }}>{{ $group }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-3 gap-3">
                                <div class="col-span-1">
                                    <label class="block font-bold text-default-700 text-[9px] uppercase tracking-widest mb-1.5" for="purchase_price">Harga Beli (Rp)</label>
                                    <input class="form-input rounded-lg border-default-200 focus:border-primary py-2 text-sm" 
                                           id="purchase_price" name="purchase_price" value="{{ old('purchase_price', (float)$item->purchase_price) }}" 
                                           type="number" step="0.01" required />
                                </div>
                                <div class="col-span-1">
                                    <label class="block font-bold text-default-700 text-[9px] uppercase tracking-widest mb-1.5" for="residual_value">Nilai Sisa (Rp)</label>
                                    <input class="form-input rounded-lg border-default-200 focus:border-primary py-2 text-sm" 
                                           id="residual_value" name="residual_value" value="{{ old('residual_value', (float)$item->residual_value) }}" 
                                           type="number" step="0.01" required />
                                </div>
                                <div class="col-span-1">
                                    <label class="block font-bold text-default-700 text-[9px] uppercase tracking-widest mb-1.5" for="useful_life_months">Umur (Bln)</label>
                                    <input class="form-input rounded-lg border-default-200 focus:border-primary py-2 text-sm" 
                                           id="useful_life_months" name="useful_life_months" value="{{ old('useful_life_months', $item->useful_life_months) }}" 
                                           type="number" required />
                                </div>
                            </div>

                            <div class="p-3 bg-default-50 border border-default-100 rounded-lg">
                                <p class="text-[9px] text-default-500 italic leading-relaxed">
                                    Perubahan parameter finansial akan langsung mengupdate simulasi penyusutan dan nilai buku secara real-time pada tab History.
                                </p>
                            </div>
                        </div>
                        <div class="card-footer bg-default-50/50 border-t border-default-200 py-3 px-4">
                            <div class="flex items-center gap-3">
                                <button type="submit" class="btn btn-sm bg-primary hover:bg-primary-600 text-white grow py-2.5 rounded-lg font-bold shadow-md shadow-primary/10 transition-all flex items-center justify-center gap-2">
                                    <i class="size-3.5" data-lucide="save"></i> Simpan Perubahan
                                </button>
                                <a href="{{ route('inventory.show', $item) }}" class="btn btn-sm bg-default-200 hover:bg-default-300 text-default-700 px-5 py-2.5 rounded-lg font-bold transition-all">
                                    Batal
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@extends('layouts.app')

@section('title', 'Ajukan Perbaikan Aset')

@section('content')
    @include('layouts.partials/page-title', ['subtitle' => 'Maintenance', 'title' => 'Request Service'])

    <div class="grid lg:grid-cols-12 grid-cols-1 gap-6">
        <div class="lg:col-span-9 col-span-1">
            <div class="card">
                <div class="card-body">
                    <h6 class="mb-4 card-title text-base">Detail Pengajuan Perbaikan</h6>
                    
                    <form action="{{ route('maintenances.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-5">
                            <label class="inline-block mb-2 text-sm text-default-800 font-medium" for="asset_id">Pilih Aset yang Akan Diperbaiki</label>
                            <select class="form-input @error('asset_id') border-danger @enderror" id="asset_id" name="asset_id" required>
                                <option value="">-- Pilih Aset --</option>
                                @foreach($assets as $asset)
                                    <option value="{{ $asset->id }}" {{ old('asset_id') == $asset->id ? 'selected' : '' }}>
                                        {{ $asset->name }} ({{ $asset->asset_code }}) - Status: {{ $asset->status }}
                                    </option>
                                @endforeach
                            </select>
                            @error('asset_id')
                                <p class="mt-1 text-danger text-xs">{{ $message }}</p>
                            @enderror
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

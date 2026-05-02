@extends('layouts.app')

@section('title', 'Manajemen Disposal Aset')

@section('content')
    @include('layouts.partials/page-title', ['subtitle' => 'Assets', 'title' => 'Asset Disposal'])

    <div class="grid lg:grid-cols-4 grid-cols-1 gap-6">
        <!-- Sidebar: Scan Barcode -->
        <div class="lg:col-span-1">
            <div class="card sticky top-24">
                <div class="card-body">
                    <h6 class="mb-4 card-title flex items-center gap-2">
                        <i class="size-5 text-primary" data-lucide="scan-barcode"></i>
                        Scan Barcode
                    </h6>
                    <form action="{{ route('disposals.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="inline-block mb-2 text-sm text-default-800 font-medium">Barcode / SN</label>
                            <input type="text" name="barcode" class="form-input" placeholder="Scan or type barcode..." required autofocus>
                        </div>
                        
                        <div class="mb-4">
                            <label class="inline-block mb-2 text-sm text-default-800 font-medium">Tanggal Disposal</label>
                            <input type="date" name="disposal_date" class="form-input" value="{{ date('Y-m-d') }}" required>
                        </div>

                        <div class="mb-4">
                            <label class="inline-block mb-2 text-sm text-default-800 font-medium">Alasan</label>
                            <select name="reason" class="form-input" required onchange="togglePriceField(this.value)">
                                <option value="Broken">Rusak Berat (Broken)</option>
                                <option value="Sold">Dijual (Sold)</option>
                                <option value="Lost">Hilang (Lost)</option>
                                <option value="Scrapped">Scrapped</option>
                                <option value="Donated">Donasi</option>
                            </select>
                        </div>

                        <div id="price-field" class="mb-4 hidden">
                            <label class="inline-block mb-2 text-sm text-default-800 font-medium">Harga Jual</label>
                            <input type="number" name="selling_price" class="form-input" placeholder="0">
                        </div>

                        <div class="mb-4">
                            <label class="inline-block mb-2 text-sm text-default-800 font-medium">Catatan</label>
                            <textarea name="notes" class="form-input" rows="3"></textarea>
                        </div>

                        <button type="submit" class="btn bg-danger text-white w-full">
                            <i class="size-4 me-1" data-lucide="trash-2"></i> Proses Disposal
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Main Content: History List -->
        <div class="lg:col-span-3">
            <div class="card">
                <div class="card-header flex justify-between items-center">
                    <h6 class="card-title">Riwayat Penghapusan Aset</h6>
                </div>
                <div class="card-body p-0">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-default-200">
                            <thead class="bg-default-100 font-normal whitespace-nowrap">
                                <tr class="text-sm text-default-800 text-start">
                                    <th class="px-4 py-3 font-medium">Tanggal</th>
                                    <th class="px-4 py-3 font-medium">Aset / Barcode</th>
                                    <th class="px-4 py-3 font-medium">Alasan</th>
                                    <th class="px-4 py-3 font-medium">Harga Jual</th>
                                    <th class="px-4 py-3 font-medium">Oleh</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-default-200">
                                @forelse($disposals as $disposal)
                                    <tr class="text-default-800">
                                        <td class="px-4 py-4 text-sm whitespace-nowrap">
                                            {{ $disposal->disposal_date->format('d M Y') }}
                                        </td>
                                        <td class="px-4 py-4">
                                            <div class="flex flex-col">
                                                <span class="font-bold text-sm">{{ $disposal->item?->asset?->name }}</span>
                                                <span class="text-xs text-primary">#{{ $disposal->item?->item_code }}</span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <span class="inline-flex py-0.5 px-2 rounded text-xs font-medium bg-danger/10 text-danger">
                                                {{ $disposal->reason }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-4 text-sm">
                                            @if($disposal->selling_price)
                                                Rp {{ number_format($disposal->selling_price, 0, ',', '.') }}
                                            @else
                                                <span class="text-default-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-4 text-sm whitespace-nowrap">
                                            {{ $disposal->creator?->name }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-8 text-center text-default-500 italic">
                                            Belum ada riwayat disposal.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    {{ $disposals->links() }}
                </div>
            </div>
        </div>
    </div>

@push('js')
<script>
    function togglePriceField(reason) {
        const field = document.getElementById('price-field');
        if (reason === 'Sold') {
            field.classList.remove('hidden');
        } else {
            field.classList.add('hidden');
        }
    }
</script>
@endpush
@endsection

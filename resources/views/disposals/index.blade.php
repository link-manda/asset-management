@extends('layouts.app')

@section('title', 'Asset Disposal Management')

@section('content')
    @include('layouts.partials/page-title', ['subtitle' => 'Assets', 'title' => 'Asset Disposal'])

    <div class="grid lg:grid-cols-4 grid-cols-1 gap-6">
        <!-- Sidebar: Scan Barcode -->
        <div class="lg:col-span-1">
            <div class="card sticky top-24">
                <div class="card-body">
                    <h6 class="mb-4 card-title flex items-center gap-2 text-primary font-bold uppercase tracking-wider text-xs">
                        <i class="size-4" data-lucide="scan-barcode"></i>
                        Scan / Type Barcode
                    </h6>
                    <form action="{{ route('disposals.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="inline-block mb-2 text-sm text-default-800 font-medium">Barcode / SN</label>
                            <input type="text" name="barcode" class="form-input" placeholder="Scan or type barcode..." required autofocus>
                        </div>
                        
                        <div class="mb-4">
                            <label class="inline-block mb-2 text-sm text-default-800 font-medium">Disposal Date</label>
                            <input type="date" name="disposal_date" class="form-input" value="{{ date('Y-m-d') }}" required>
                        </div>

                        <div class="mb-4">
                            <label class="inline-block mb-2 text-sm text-default-800 font-medium">Reason</label>
                            <select name="reason" class="form-input" required onchange="togglePriceField(this.value)">
                                <option value="Broken">Heavy Damage (Broken)</option>
                                <option value="Sold">Sold</option>
                                <option value="Lost">Lost</option>
                                <option value="Scrapped">Scrapped</option>
                                <option value="Donated">Donated</option>
                            </select>
                        </div>

                        <div id="price-field" class="mb-4 hidden">
                            <label class="inline-block mb-2 text-sm text-default-800 font-medium">Selling Price</label>
                            <input type="number" name="selling_price" class="form-input" placeholder="0">
                        </div>

                        <div class="mb-4">
                            <label class="inline-block mb-2 text-sm text-default-800 font-medium">Notes</label>
                            <textarea name="notes" class="form-input" rows="3" placeholder="Additional details..."></textarea>
                        </div>

                        <button type="submit" class="btn bg-danger text-white w-full uppercase font-bold text-xs tracking-widest">
                            <i class="size-4 me-1" data-lucide="trash-2"></i> Process Disposal
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Main Content: History List -->
        <div class="lg:col-span-3">
            <div class="card">
                <div class="card-header flex justify-between items-center">
                    <h6 class="card-title">Disposal History</h6>
                </div>
                <div class="card-body p-0">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-default-200">
                            <thead class="bg-default-100 font-normal whitespace-nowrap">
                                <tr class="text-sm text-default-800 text-start uppercase tracking-wider text-[11px] font-bold">
                                    <th class="px-4 py-3 text-start">Disposal Date</th>
                                    <th class="px-4 py-3 text-start">Asset / Barcode</th>
                                    <th class="px-4 py-3 text-start">Reason</th>
                                    <th class="px-4 py-3 text-end">Selling Price</th>
                                    <th class="px-4 py-3 text-end">Gain / Loss</th>
                                    <th class="px-4 py-3 text-start">Processed By</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-default-200">
                                @forelse($disposals as $disposal)
                                    <tr class="text-default-800 hover:bg-default-50 transition-all">
                                        <td class="px-4 py-4 text-sm whitespace-nowrap">
                                            {{ $disposal->disposal_date->format('d M Y') }}
                                        </td>
                                        <td class="px-4 py-4">
                                            <div class="flex flex-col">
                                                <a href="{{ route('assets.show', $disposal->item?->asset_id) }}" class="group">
                                                    <span class="font-bold text-sm text-default-800 group-hover:text-primary transition-all">{{ $disposal->item?->asset?->name }}</span>
                                                </a>
                                                <span class="text-xs text-primary font-mono">#{{ $disposal->item?->item_code }}</span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <span class="inline-flex py-0.5 px-2 rounded text-[10px] font-bold uppercase bg-danger/10 text-danger">
                                                {{ $disposal->reason }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-4 text-sm text-end font-medium">
                                            @if($disposal->selling_price)
                                                Rp {{ number_format($disposal->selling_price, 0, ',', '.') }}
                                            @else
                                                <span class="text-default-400 italic">N/A</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-4 text-sm text-end">
                                            @if($disposal->reason === 'Sold')
                                                @if($disposal->gain_loss > 0)
                                                    <span class="text-success font-bold">+Rp {{ number_format($disposal->gain_loss, 0, ',', '.') }}</span>
                                                @elseif($disposal->gain_loss < 0)
                                                    <span class="text-danger font-bold">-Rp {{ number_format(abs($disposal->gain_loss), 0, ',', '.') }}</span>
                                                @else
                                                    <span class="text-default-400">Break-even</span>
                                                @endif
                                            @else
                                                <span class="text-default-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-4 text-sm whitespace-nowrap">
                                            <div class="flex items-center gap-2">
                                                <div class="size-6 bg-default-100 text-default-500 rounded-full flex items-center justify-center text-[10px] font-bold">
                                                    {{ substr($disposal->creator?->name ?? 'S', 0, 1) }}
                                                </div>
                                                <span class="font-medium">{{ $disposal->creator?->name }}</span>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-4 py-8 text-center text-default-500 italic">
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

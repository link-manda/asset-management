@extends('layouts.app')

@section('title', 'Daftar Item Fisik (Inventory List)')

@section('content')
    @include('layouts.partials/page-title', ['subtitle' => 'Inventory', 'title' => 'Global Item List'])

    <div class="grid grid-cols-1 gap-5 mb-5">
        <div class="card">
            <div class="card-header flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div class="flex gap-3 items-center w-full md:w-auto">
                    <form action="{{ route('inventory.index') }}" method="GET" class="relative w-full md:w-80">
                        <input class="ps-11 form-input form-input-sm w-full" name="search" value="{{ request('search') }}" placeholder="Scan barcode atau cari item..." type="text" autofocus />
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3">
                            <i class="size-4 text-default-500" data-lucide="search"></i>
                        </div>
                    </form>

                    {{-- Filter Modal Trigger --}}
                    <button data-hs-overlay="#modal-filters" class="btn btn-sm bg-default-100 text-default-600">
                        <i class="size-4 me-1" data-lucide="filter"></i> Filter
                    </button>
                </div>

                <div class="flex items-center gap-2">
                    <button id="btn-bulk-print" class="btn btn-sm bg-primary text-white hidden">
                        <i class="size-4 me-1" data-lucide="printer"></i> Cetak Label Terpilih (<span id="selected-count">0</span>)
                    </button>
                </div>
            </div>

            <div class="flex flex-col">
                <div class="overflow-x-auto">
                    <div class="min-w-full inline-block align-middle">
                        <div class="overflow-hidden">
                            <form id="form-bulk-print" action="{{ route('inventory.bulk-print') }}" method="POST" target="_blank">
                                @csrf
                                <table class="min-w-full divide-y divide-default-200">
                                    <thead class="bg-default-100 font-normal whitespace-nowrap">
                                        <tr class="text-sm text-default-800">
                                            <th class="px-4 py-3 text-start w-10">
                                                <input type="checkbox" id="check-all" class="form-checkbox size-4 rounded border-default-300 text-primary">
                                            </th>
                                            <th class="px-4 py-3 font-medium text-start">Item Code / Barcode</th>
                                            <th class="px-4 py-3 font-medium text-start">Asset Name</th>
                                            <th class="px-4 py-3 font-medium text-start">Location</th>
                                            <th class="px-4 py-3 font-medium text-start">Status</th>
                                            <th class="px-4 py-3 font-medium text-start">Borrower</th>
                                            <th class="px-4 py-3 font-medium text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-default-200">
                                        @forelse ($items as $item)
                                            <tr class="text-default-800 font-normal whitespace-nowrap hover:bg-default-50 transition-all">
                                                <td class="px-4 py-4">
                                                    <input type="checkbox" name="ids[]" value="{{ $item->id }}" class="item-checkbox form-checkbox size-4 rounded border-default-300 text-primary">
                                                </td>
                                                <td class="px-4 py-4">
                                                    <div class="flex flex-col">
                                                        <span class="font-bold text-primary font-mono">#{{ $item->item_code }}</span>
                                                        <span class="text-[10px] text-default-400">SN: {{ $item->serial_number ?? '-' }}</span>
                                                    </div>
                                                </td>
                                                <td class="px-4 py-4">
                                                    <div class="flex flex-col">
                                                        <span class="font-semibold">{{ $item->asset?->name }}</span>
                                                        <span class="text-[10px] text-default-500">{{ $item->asset?->category?->name ?? 'Uncategorized' }}</span>
                                                    </div>
                                                </td>
                                                <td class="px-4 py-4 text-sm text-default-600">
                                                    <div class="flex items-center gap-1.5">
                                                        <i class="size-3.5 text-secondary" data-lucide="map-pin"></i>
                                                        {{ $item->location?->name ?? 'N/A' }}
                                                    </div>
                                                </td>
                                                <td class="px-4 py-4">
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
                                                <td class="px-4 py-4 text-sm">
                                                    @if($item->status == 'Deployed' && $item->currentAssignment)
                                                        <div class="flex items-center gap-2">
                                                            <div class="size-6 bg-primary/10 text-primary rounded-full flex items-center justify-center text-[10px] font-bold">
                                                                {{ substr($item->currentAssignment->user->name, 0, 1) }}
                                                            </div>
                                                            <span class="text-default-700 font-medium">{{ $item->currentAssignment->user->name }}</span>
                                                        </div>
                                                    @else
                                                        <span class="text-default-300 italic">-</span>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-4 text-center">
                                                    <div class="flex items-center justify-center gap-2">
                                                        <a href="{{ route('inventory.show', $item) }}" class="size-8 flex items-center justify-center bg-default-100 text-default-600 rounded hover:bg-primary/10 hover:text-primary transition-all" title="Detail Item">
                                                            <i class="size-4" data-lucide="eye"></i>
                                                        </a>
                                                        <form action="{{ route('inventory.bulk-print') }}" method="POST" target="_blank">
                                                            @csrf
                                                            <input type="hidden" name="ids[]" value="{{ $item->id }}">
                                                            <button type="submit" class="size-8 flex items-center justify-center bg-default-100 text-default-600 rounded hover:bg-secondary/10 hover:text-secondary transition-all" title="Cetak Label">
                                                                <i class="size-4" data-lucide="printer"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="px-4 py-12 text-center">
                                                    <div class="flex flex-col items-center">
                                                        <i class="size-12 text-default-200 mb-3" data-lucide="package-search"></i>
                                                        <p class="text-default-500 font-medium">Tidak ada item fisik ditemukan.</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-footer border-t border-default-200 p-4">
                    {{ $items->links('vendor.pagination.tailwind-custom') }}
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Filters --}}
    <div id="modal-filters" class="hs-overlay hidden size-full fixed top-0 start-0 z-80 overflow-x-hidden overflow-y-auto pointer-events-none">
        <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto flex items-center min-h-[calc(100%-3.5rem)]">
            <div class="flex flex-col bg-white border border-default-200 shadow-sm rounded-md pointer-events-auto w-full">
                <div class="flex justify-between items-center py-3 px-4 border-b border-default-200">
                    <h3 class="font-bold text-default-800">Filter Inventory</h3>
                    <button type="button" class="size-8 inline-flex justify-center items-center gap-x-2 rounded-full border border-transparent bg-default-100 text-default-800 hover:bg-default-200" data-hs-overlay="#modal-filters">
                        <i class="size-4" data-lucide="x"></i>
                    </button>
                </div>
                <form action="{{ route('inventory.index') }}" method="GET">
                    <div class="p-5 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-default-700 mb-1">Status Item</label>
                            <select name="status" class="form-select">
                                <option value="">Semua Status</option>
                                @foreach($statuses as $status)
                                    <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>{{ $status }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-default-700 mb-1">Kategori</label>
                            <select name="category_id" class="form-select">
                                <option value="">Semua Kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-default-700 mb-1">Lokasi</label>
                            <select name="location_id" class="form-select">
                                <option value="">Semua Lokasi</option>
                                @foreach($locations as $location)
                                    <option value="{{ $location->id }}" {{ request('location_id') == $location->id ? 'selected' : '' }}>{{ $location->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="flex justify-end items-center gap-2 py-3 px-4 border-t border-default-200">
                        <a href="{{ route('inventory.index') }}" class="btn border-default-200 text-default-600">Reset</a>
                        <button type="submit" class="btn bg-primary text-white">Terapkan Filter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const checkAll = document.getElementById('check-all');
        const checkboxes = document.querySelectorAll('.item-checkbox');
        const btnBulkPrint = document.getElementById('btn-bulk-print');
        const selectedCount = document.getElementById('selected-count');
        const formBulkPrint = document.getElementById('form-bulk-print');

        function updateUI() {
            const checkedCount = document.querySelectorAll('.item-checkbox:checked').length;
            selectedCount.textContent = checkedCount;

            if (checkedCount > 0) {
                btnBulkPrint.classList.remove('hidden');
            } else {
                btnBulkPrint.classList.add('hidden');
            }
        }

        checkAll.addEventListener('change', function () {
            checkboxes.forEach(cb => cb.checked = checkAll.checked);
            updateUI();
        });

        checkboxes.forEach(cb => {
            cb.addEventListener('change', updateUI);
        });

        btnBulkPrint.addEventListener('click', function() {
            formBulkPrint.submit();
        });
    });
</script>
@endpush

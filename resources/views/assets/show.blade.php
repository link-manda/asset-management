@extends('layouts.app')

@section('title', 'Asset Overview: ' . $asset->name)

@section('content')
    @include('layouts.partials/page-title', [
        'subtitle' => 'Assets', 
        'title' => 'Master Asset Overview',
        'breadcrumbs' => [
            ['label' => 'Asset List', 'url' => route('assets.index')],
            ['label' => 'Asset Overview', 'url' => null],
        ]
    ])

    <div class="grid lg:grid-cols-3 grid-cols-1 lg:gap-5">
        <div class="col-span-1">
            <div class="sticky top-24">
                <div class="card mb-5">
                    <div class="card-body">
                        @if($asset->images->count() > 0)
                            <div class="swiper mySwiper rounded-md overflow-hidden mb-5 aspect-square lg:aspect-video bg-default-100 border border-default-200 shadow-sm group">
                                <div class="swiper-wrapper">
                                    @foreach($asset->images as $image)
                                        <div class="swiper-slide cursor-zoom-in" onclick="zoomImage('{{ $image->url }}')">
                                            <img src="{{ $image->url }}" class="size-full object-cover">
                                        </div>
                                    @endforeach
                                </div>
                                <div class="swiper-pagination"></div>
                                <div class="swiper-button-next opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                <div class="swiper-button-prev opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            </div>
                        @else
                            <div class="w-full aspect-square lg:aspect-video bg-default-100 rounded-md border border-default-200 flex flex-col items-center justify-center text-default-400 mb-5">
                                <i class="size-12 mb-2" data-lucide="image"></i>
                                <p class="text-xs font-medium">No Image Available</p>
                            </div>
                        @endif

                        <div class="flex flex-col items-center justify-center p-6 bg-primary/5 rounded-lg border border-dashed border-primary/20 text-center">
                            <i class="size-10 text-primary/30 mb-3" data-lucide="package"></i>
                            <h6 class="text-xs font-black text-primary/60 uppercase tracking-[0.2em] mb-1">Catalog Master</h6>
                            <p class="text-[10px] text-default-500 italic">Use the Inventory menu for unit-level operations.</p>
                        </div>
                    </div>
                </div>

                <div class="card mb-5">
                    <div class="card-body border-b border-b-default-200">
                        <div class="flex justify-between flex-wrap gap-5">
                            <h6 class="text-default-800 font-semibold text-[15px] flex items-center gap-1.25">
                                <i class="size-4" data-lucide="info"></i>
                                Information & Finance
                            </h6>
                            <span class="inline-flex items-center gap-x-1.5 py-1 px-3 rounded text-xs font-semibold bg-primary/10 text-primary">
                                {{ $asset->uom?->name ?? 'N/A' }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="flex flex-col gap-4">
                            <div class="flex justify-between">
                                <span class="text-default-500 text-sm">Category:</span>
                                <span class="text-default-800 font-medium text-sm">{{ $asset->category?->name ?? 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-default-500 text-sm">Unit Price:</span>
                                <span class="text-default-800 font-medium text-sm">Rp {{ number_format($asset->price, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-default-500 text-sm">Total Active Units:</span>
                                <span class="text-default-800 font-medium text-sm">{{ $asset->items->where('status', '!=', 'Disposed')->count() }} Units</span>
                            </div>
                            <div class="flex justify-between border-t border-dashed border-default-200 pt-3">
                                <span class="text-default-800 font-bold text-sm uppercase tracking-wider">Total Asset Value:</span>
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
                            <button aria-expanded="false" aria-haspopup="menu" class="hs-dropdown-toggle btn size-7.5 bg-default-200 hover:bg-default-600 text-default-500 hover:text-white rounded-md transition-all" hs-dropdown-placement="bottom-end" type="button">
                                <i class="size-4" data-lucide="more-vertical"></i>
                            </button>
                            <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-32 z-50 bg-white shadow-md rounded-lg p-2 mt-2 dark:bg-default-50 dark:border dark:border-default-200" role="menu">
                                @can('edit assets')
                                <a class="flex items-center gap-1.5 py-1.5 font-medium px-3 text-sm text-default-500 hover:bg-default-150 rounded" href="{{ route('assets.edit', $asset) }}">
                                    <i class="size-3" data-lucide="edit-3"></i> Edit Master
                                </a>
                                @endcan
                            </div>
                        </div>
                    </div>
                    <div class="p-3 bg-default-100 rounded-md text-default-600 text-sm italic mb-4">
                        {{ $asset->notes ?? 'No catalog description provided.' }}
                    </div>

                    <div class="flex justify-between items-center mb-3">
                        <h6 class="text-[14px] font-bold text-default-800 flex items-center gap-2">
                            <i class="size-4 text-primary" data-lucide="box"></i>
                            Physical Units List
                        </h6>
                        @can('create assets')
                        <button type="button" data-hs-overlay="#modal-add-item" class="btn btn-sm bg-primary text-white py-1">
                            <i class="size-3.5 me-1" data-lucide="plus"></i> Add Unit
                        </button>
                        @endcan
                    </div>

                    <div class="overflow-x-auto border rounded-lg border-default-200">
                        <table class="min-w-full divide-y divide-default-200">
                            <thead class="bg-default-50">
                                <tr class="text-[10px] font-bold text-default-500 uppercase tracking-wider text-start">
                                    <th class="px-4 py-3">Barcode / Unit</th>
                                    <th class="px-4 py-3">Location</th>
                                    <th class="px-4 py-3">Condition</th>
                                    <th class="px-4 py-3 text-center">Status</th>
                                    <th class="px-4 py-3 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-default-200">
                                @forelse($asset->items as $item)
                                    <tr class="text-sm hover:bg-default-50 transition-all">
                                        <td class="px-4 py-3">
                                            <div class="flex flex-col">
                                                <span class="font-bold text-primary font-mono">#{{ $item->item_code }}</span>
                                                <span class="text-[10px] text-default-400 font-medium">SN: {{ $item->serial_number ?? '-' }}</span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="flex items-center gap-1.5 text-default-600 text-xs">
                                                <i class="size-3 text-secondary" data-lucide="map-pin"></i>
                                                {{ $item->location?->name ?? 'N/A' }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="text-xs font-medium">{{ $item->condition }}</span>
                                        </td>
                                        <td class="px-4 py-3 text-center">
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
                                            <span class="py-0.5 px-2 rounded text-[10px] font-bold uppercase {{ $class }}">
                                                {{ $item->status }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-2 text-center">
                                            <div class="hs-dropdown relative inline-flex">
                                                <button class="hs-dropdown-toggle btn size-7 bg-default-100 hover:bg-default-600 text-default-500 hover:text-white rounded-full transition-all" type="button">
                                                    <i class="size-3.5" data-lucide="more-vertical"></i>
                                                </button>
                                                <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-32 z-50 bg-white shadow-md rounded-lg p-2 mt-2 border border-default-200 text-start" role="menu">
                                                    <a href="{{ route('inventory.show', $item) }}" class="flex items-center gap-1.5 py-1.5 font-medium px-3 text-sm text-default-500 hover:bg-default-150 rounded">
                                                        <i class="size-3.5" data-lucide="eye"></i> View Specs
                                                    </a>
                                                    @can('edit assets')
                                                    <a href="{{ route('inventory.edit', $item) }}" class="flex items-center gap-1.5 py-1.5 font-medium px-3 text-sm text-info hover:bg-info/10 rounded">
                                                        <i class="size-3.5" data-lucide="edit-3"></i> Edit Unit
                                                    </a>

                                                    @if($item->status == 'Available')
                                                        <a href="{{ route('items.checkout.create', $item) }}" class="flex items-center gap-1.5 py-1.5 font-medium px-3 text-sm text-primary hover:bg-primary/10 rounded">
                                                            <i class="size-3.5" data-lucide="log-out"></i> Checkout
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
                                                            class="btn-checkin w-full text-start flex items-center gap-1.5 py-1.5 font-medium px-3 text-sm text-success hover:bg-success/10 rounded">
                                                            <i class="size-3.5" data-lucide="log-in"></i> Check-in
                                                        </button>
                                                    @endif
                                                    @endcan

                                                    @if($item->status != 'Disposed')
                                                        @can('delete assets')
                                                        <hr class="my-1 border-default-200">
                                                        <button type="button"
                                                            data-hs-overlay="#modal-disposal"
                                                            data-barcode="{{ $item->item_code }}"
                                                            class="btn-disposal w-full text-start flex items-center gap-1.5 py-1.5 font-medium px-3 text-sm text-danger hover:bg-danger/10 rounded">
                                                            <i class="size-3.5" data-lucide="trash-2"></i> Disposal
                                                        </button>
                                                        @endcan
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-8 text-center text-default-400 italic">No physical units registered yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card mt-5">
                <div class="card-header border-b border-default-200">
                    <h6 class="card-title text-base uppercase tracking-wider text-[11px] font-bold">Asset Transaction History (All Units)</h6>
                </div>
                <div class="card-body p-0">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-default-200 text-sm">
                            <thead class="bg-default-50">
                                <tr class="text-[10px] font-bold text-default-500 uppercase tracking-wider text-start">
                                    <th class="px-4 py-3">Date</th>
                                    <th class="px-4 py-3">Unit / Barcode</th>
                                    <th class="px-4 py-3">Activity</th>
                                    <th class="px-4 py-3">User / Notes</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-default-200">
                                @forelse($asset->assignments as $history)
                                    <tr class="hover:bg-default-50 transition-all">
                                        <td class="px-4 py-3 whitespace-nowrap">{{ $history->assigned_date->format('d M Y') }}</td>
                                        <td class="px-4 py-3 font-bold text-primary">#{{ $history->item->item_code }}</td>
                                        <td class="px-4 py-3">
                                            @if($history->return_date)
                                                <span class="text-success font-bold uppercase text-[10px]">Returned</span>
                                            @else
                                                <span class="text-primary font-bold uppercase text-[10px]">Checkout</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3">
                                            <p class="font-bold text-default-800">{{ $history->user->name }}</p>
                                            <p class="text-[10px] text-default-500 italic">Condition: {{ $history->condition_on_checkout }}</p>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-4 py-8 text-center text-default-400 italic">No transaction history found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL: CHECK-IN --}}
    <div id="modal-checkin" class="hs-overlay hidden size-full fixed top-0 start-0 z-80 overflow-x-hidden overflow-y-auto pointer-events-none">
        <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto min-h-[calc(100%-3.5rem)] flex items-center">
            <div class="flex flex-col bg-white border border-default-200 shadow-sm rounded-md pointer-events-auto w-full">
                <div class="flex justify-between items-center py-3 px-4 border-b border-default-200">
                    <h3 class="font-bold text-default-800">Return Unit (Check-in)</h3>
                    <button type="button" class="size-8 inline-flex justify-center items-center gap-x-2 rounded-full border border-transparent bg-default-100 text-default-800 hover:bg-default-200" data-hs-overlay="#modal-checkin">
                        <i class="size-4" data-lucide="x"></i>
                    </button>
                </div>
                <form id="form-checkin" method="POST">
                    @csrf
                    <div class="p-5">
                        <div class="mb-4 bg-primary/5 p-3 rounded-md border border-primary/10">
                            <p class="text-xs text-default-500 uppercase font-black tracking-widest mb-1">Returning Unit:</p>
                            <h6 id="checkin-item-code" class="text-sm font-black text-primary"></h6>
                            <p class="text-xs text-default-600 mt-1">Currently held by: <span id="checkin-borrower" class="font-bold"></span></p>
                        </div>
                        
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-2">Return Date</label>
                            <input type="date" name="return_date" class="form-input" value="{{ date('Y-m-d') }}" required>
                        </div>

                        <div class="mb-0">
                            <label class="block text-sm font-medium mb-2">Condition on Return</label>
                            <textarea name="condition_on_return" class="form-input" rows="3" placeholder="Explain unit condition..." required></textarea>
                        </div>
                    </div>
                    <div class="flex justify-end items-center gap-2 py-3 px-4 border-t border-default-200">
                        <button type="button" class="btn border-default-200 text-default-600" data-hs-overlay="#modal-checkin">Cancel</button>
                        <button type="submit" class="btn bg-success text-white px-6 font-bold uppercase tracking-wider text-xs">Confirm Return</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL: DISPOSAL --}}
    <div id="modal-disposal" class="hs-overlay hidden size-full fixed top-0 start-0 z-80 overflow-x-hidden overflow-y-auto pointer-events-none">
        <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto min-h-[calc(100%-3.5rem)] flex items-center">
            <div class="flex flex-col bg-white border border-default-200 shadow-sm rounded-md pointer-events-auto w-full">
                <div class="flex justify-between items-center py-3 px-4 border-b border-default-200">
                    <h3 class="font-bold text-default-800">Asset Disposal</h3>
                    <button type="button" class="size-8 inline-flex justify-center items-center gap-x-2 rounded-full border border-transparent bg-default-100 text-default-800 hover:bg-default-200" data-hs-overlay="#modal-disposal">
                        <i class="size-4" data-lucide="x"></i>
                    </button>
                </div>
                <form action="{{ route('disposals.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="barcode" id="disposal-barcode-hidden">
                    <div class="p-5 space-y-4">
                        <div class="bg-danger/5 p-3 rounded-md border border-danger/10">
                            <p class="text-xs text-danger font-black uppercase tracking-widest mb-1">Disposing Unit:</p>
                            <h6 id="disposal-item-code" class="text-sm font-black text-danger"></h6>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">Disposal Reason</label>
                            <select name="reason" class="form-select" required>
                                <option value="Broken">Heavy Damage (Broken)</option>
                                <option value="Sold">Sold</option>
                                <option value="Lost">Lost</option>
                                <option value="Scrapped">Scrapped</option>
                                <option value="Donated">Donated</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">Disposal Date</label>
                            <input type="date" name="disposal_date" class="form-input" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>
                    <div class="flex justify-end items-center gap-2 py-3 px-4 border-t border-default-200">
                        <button type="button" class="btn border-default-200 text-default-600" data-hs-overlay="#modal-disposal">Cancel</button>
                        <button type="submit" class="btn bg-danger text-white px-6 font-bold uppercase tracking-wider text-xs">Confirm Disposal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL: ADD UNIT --}}
    <div id="modal-add-item" class="hs-overlay hidden size-full fixed top-0 start-0 z-80 overflow-x-hidden overflow-y-auto pointer-events-none">
        <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto min-h-[calc(100%-3.5rem)] flex items-center">
            <div class="flex flex-col bg-card border border-default-200 shadow-sm rounded-md pointer-events-auto w-full">
                <div class="flex justify-between items-center py-3 px-4 border-b border-default-200">
                    <h3 class="font-bold text-default-800">Add New Physical Units</h3>
                    <button type="button" class="size-8 inline-flex justify-center items-center gap-x-2 rounded-full border border-transparent bg-default-100 text-default-800 hover:bg-default-200" data-hs-overlay="#modal-add-item">
                        <i class="size-4" data-lucide="x"></i>
                    </button>
                </div>
                <form action="{{ route('inventory.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="asset_id" value="{{ $asset->id }}">
                    <div class="p-5 space-y-4">
                        <div class="p-3 bg-primary/5 rounded border border-primary/10">
                            <p class="text-xs text-default-500 font-medium italic">New units will inherit acquisition price and useful life from the master catalog.</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-default-700 mb-1">Number of Units to Add</label>
                            <input type="number" name="quantity" class="form-input" min="1" max="50" value="1" required>
                            <p class="text-[10px] text-default-400 mt-1">Batch generation limit: 50 units per request.</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-default-700 mb-1">Initial Location</label>
                            <select name="location_id" class="form-input" required>
                                @foreach($locations as $loc)
                                    <option value="{{ $loc->id }}">{{ $loc->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-default-700 mb-1">Initial Condition</label>
                                <select name="condition" class="form-input">
                                    <option value="New">New</option>
                                    <option value="Good">Good</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-default-700 mb-1">Fiscal Group</label>
                                <select name="fiscal_group" class="form-input">
                                    <option value="">Default (From Category)</option>
                                    @foreach(\App\Models\AssetItem::FISCAL_GROUPS as $group => $months)
                                        <option value="{{ $group }}">{{ $group }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-end items-center gap-2 py-3 px-4 border-t border-default-200">
                        <button type="button" class="btn border-default-200 text-default-600" data-hs-overlay="#modal-add-item">Cancel</button>
                        <button type="submit" class="btn bg-primary text-white">Save Units</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Swiper Init
        new Swiper(".mySwiper", {
            pagination: { el: ".swiper-pagination", clickable: true },
            navigation: { nextEl: ".swiper-button-next", prevEl: ".swiper-button-prev" },
        });

        // Checkin Modal Data
        document.querySelectorAll('.btn-checkin').forEach(btn => {
            btn.addEventListener('click', () => {
                const itemId = btn.dataset.itemId;
                const itemCode = btn.dataset.itemCode;
                const borrower = btn.dataset.borrower;
                
                document.getElementById('checkin-item-code').innerText = '#' + itemCode;
                document.getElementById('checkin-borrower').innerText = borrower;
                document.getElementById('form-checkin').action = `/assets/items/${itemId}/checkin`;
            });
        });

        // Disposal Modal Data
        document.querySelectorAll('.btn-disposal').forEach(btn => {
            btn.addEventListener('click', () => {
                const barcode = btn.dataset.barcode;
                document.getElementById('disposal-item-code').innerText = '#' + barcode;
                document.getElementById('disposal-barcode-hidden').value = barcode;
            });
        });
    });

    function zoomImage(url) {
        Swal.fire({
            imageUrl: url, imageAlt: 'Asset Image', showCloseButton: true, showConfirmButton: false, width: 'auto', padding: '0', background: 'transparent',
            customClass: { image: 'rounded-lg shadow-2xl border-4 border-white' }
        });
    }
</script>
@endpush

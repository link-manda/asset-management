@extends('layouts.app')

@section('title', 'Edit Specs: ' . $item->item_code)

@section('content')
    @include('layouts.partials/page-title', [
        'subtitle' => 'Inventory', 
        'title' => 'Edit Item Specifications',
        'breadcrumbs' => [
            ['label' => 'Global List', 'url' => route('inventory.index')],
            ['label' => 'Item Details', 'url' => route('inventory.show', $item)],
            ['label' => 'Edit Specs', 'url' => null],
        ]
    ])

    <form action="{{ route('inventory.update', $item) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="grid lg:grid-cols-12 grid-cols-1 gap-6">
            {{-- Left Column: Core Specs (8/12) --}}
            <div class="lg:col-span-8 col-span-1">
                <div class="card mb-6">
                    <div class="card-body">
                        <h6 class="mb-4 card-title text-base flex items-center gap-2 text-primary font-bold uppercase tracking-wider text-xs">
                            <i class="size-4" data-lucide="settings-2"></i> Physical Specifications
                        </h6>

                        <div class="grid lg:grid-cols-2 grid-cols-1 gap-5 mb-5">
                            <div class="col-span-1">
                                <label class="inline-block mb-2 text-sm text-default-800 font-medium">Item Code (Barcode)</label>
                                <input class="form-input bg-default-50 font-mono font-bold text-primary" type="text" value="{{ $item->item_code }}" readonly />
                                <p class="mt-1 text-[10px] text-default-400 italic">* Item code is unique and cannot be changed.</p>
                            </div>
                            <div class="col-span-1">
                                <label class="inline-block mb-2 text-sm text-default-800 font-medium" for="serial_number">Serial Number (SN)</label>
                                <input class="form-input @error('serial_number') border-danger @enderror" id="serial_number" name="serial_number" placeholder="Factory SN..." type="text" value="{{ old('serial_number', $item->serial_number) }}" />
                            </div>
                        </div>

                        <div class="grid lg:grid-cols-2 grid-cols-1 gap-5 mb-5">
                            <div class="col-span-1">
                                <label class="inline-block mb-2 text-sm text-default-800 font-medium" for="location_id">Current Location</label>
                                <select class="form-input" id="location_id" name="location_id" required>
                                    @foreach($locations as $location)
                                        <option value="{{ $location->id }}" {{ old('location_id', $item->location_id) == $location->id ? 'selected' : '' }}>{{ $location->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-span-1">
                                <label class="inline-block mb-2 text-sm text-default-800 font-medium" for="condition">Physical Condition</label>
                                <select class="form-input" id="condition" name="condition" required>
                                    <option value="New" {{ old('condition', $item->condition) == 'New' ? 'selected' : '' }}>New (Mint)</option>
                                    <option value="Good" {{ old('condition', $item->condition) == 'Good' ? 'selected' : '' }}>Good (Normal)</option>
                                    <option value="Fair" {{ old('condition', $item->condition) == 'Fair' ? 'selected' : '' }}>Fair (Usable)</option>
                                    <option value="Poor" {{ old('condition', $item->condition) == 'Poor' ? 'selected' : '' }}>Poor (Damaged)</option>
                                    <option value="Broken" {{ old('condition', $item->condition) == 'Broken' ? 'selected' : '' }}>Broken (Non-functional)</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-5">
                            <label class="font-medium text-default-800 text-sm mb-2 inline-block">Technical Notes</label>
                            <textarea class="form-input" name="notes" rows="3" placeholder="Specific details for this unit...">{{ old('notes', $item->notes) }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h6 class="mb-4 card-title text-base flex items-center gap-2 text-primary font-bold uppercase tracking-wider text-xs">
                            <i class="size-4" data-lucide="trending-down"></i> Financial & Depreciation
                        </h6>

                        <div class="grid lg:grid-cols-2 grid-cols-1 gap-5 mb-5">
                            <div class="col-span-1">
                                <label class="inline-block mb-2 text-sm text-default-800 font-medium" for="purchase_date">Acquisition Date</label>
                                <input type="date" name="purchase_date" id="purchase_date" class="form-input" value="{{ old('purchase_date', $item->purchase_date ? $item->purchase_date->format('Y-m-d') : '') }}">
                            </div>
                            <div class="col-span-1">
                                <label class="inline-block mb-2 text-sm text-default-800 font-medium" for="purchase_price">Acquisition Cost</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 start-0 flex items-center ps-3 text-default-500">Rp</span>
                                    <input type="number" name="purchase_price" id="purchase_price" class="form-input ps-10" value="{{ old('purchase_price', (int)$item->purchase_price) }}">
                                </div>
                            </div>
                        </div>

                        <div class="grid lg:grid-cols-2 grid-cols-1 gap-5 mb-5">
                            <div class="col-span-1">
                                <label class="inline-block mb-2 text-sm text-default-800 font-medium" for="residual_value">Residual Value</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 start-0 flex items-center ps-3 text-default-500">Rp</span>
                                    <input type="number" name="residual_value" id="residual_value" class="form-input ps-10" value="{{ old('residual_value', (int)$item->residual_value) }}">
                                </div>
                            </div>
                            <div class="col-span-1">
                                <label class="inline-block mb-2 text-sm text-default-800 font-medium" for="useful_life_months">Useful Life (Months)</label>
                                <input type="number" name="useful_life_months" id="useful_life_months" class="form-input" value="{{ old('useful_life_months', $item->useful_life_months) }}">
                            </div>
                        </div>
                        
                        <div class="mt-5 p-3 bg-primary/5 rounded-lg border border-primary/10">
                            <label class="inline-block mb-2 text-sm text-primary font-black uppercase tracking-widest text-[10px]">Fiscal Group (Tax Authority)</label>
                            <select name="fiscal_group" class="form-input">
                                <option value="">- Use Category Default -</option>
                                @foreach(\App\Models\AssetItem::FISCAL_GROUPS as $group => $months)
                                    <option value="{{ $group }}" {{ old('fiscal_group', $item->fiscal_group) == $group ? 'selected' : '' }}>{{ $group }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Column: Summary & Actions (4/12) --}}
            <div class="lg:col-span-4 col-span-1 space-y-4">
                <div class="card">
                    <div class="card-body">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="size-10 bg-primary/10 text-primary rounded flex items-center justify-center">
                                <i class="size-6" data-lucide="info"></i>
                            </div>
                            <h6 class="text-sm font-bold text-default-800 uppercase tracking-wider">Unit Status</h6>
                        </div>
                        
                        <div class="mb-4">
                            <label class="inline-block mb-2 text-sm text-default-800 font-medium" for="status">Operational Status</label>
                            <select class="form-input font-bold" id="status" name="status" required>
                                @foreach($statuses as $status)
                                    <option value="{{ $status }}" {{ old('status', $item->status) == $status ? 'selected' : '' }}>{{ $status }}</option>
                                @endforeach
                            </select>
                        </div>

                        <p class="text-xs text-default-500 mb-4 italic">This unit is tied to the master catalog below:</p>
                        <div class="p-3 border border-default-200 rounded-lg bg-default-50">
                            <h6 class="text-sm font-black text-default-800 uppercase mb-1">{{ $item->asset->name }}</h6>
                            <p class="text-[10px] text-primary font-mono font-bold">{{ $item->asset->asset_code }}</p>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body flex flex-col gap-2">
                        <button type="submit" class="btn bg-primary text-white w-full py-3 font-bold uppercase tracking-widest">
                            <i class="size-3.5 me-1" data-lucide="save"></i> Save Changes
                        </button>
                        <a href="{{ route('inventory.show', $item) }}" class="btn border-default-200 text-default-600 w-full text-center">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

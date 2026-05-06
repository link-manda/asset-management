@extends('layouts.app')

@section('title', 'Checkout Unit: ' . $item->item_code)

@section('content')
    @include('layouts.partials/page-title', [
        'subtitle' => 'Assignments', 
        'title' => 'Checkout Physical Unit',
        'breadcrumbs' => [
            ['label' => 'Asset List', 'url' => route('assets.index')],
            ['label' => 'Checkout', 'url' => null],
        ]
    ])

    <div class="grid lg:grid-cols-12 grid-cols-1 gap-6">
        <div class="lg:col-span-8 col-span-1">
            <div class="card">
                <div class="card-body">
                    <h6 class="mb-4 card-title text-base">New Assignment Details</h6>
                    
                    <form action="{{ route('items.checkout', $item) }}" method="POST">
                        @csrf
                        
                        <div class="mb-5">
                            <label class="inline-block mb-2 text-sm text-default-800 font-medium" for="assigned_to">Assign To (User)</label>
                            <select class="form-select @error('assigned_to') border-danger @enderror" id="assigned_to" name="assigned_to" required>
                                <option value="" disabled selected>-- Select Recipient --</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('assigned_to') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ strtoupper($user->role) }})
                                    </option>
                                @endforeach
                            </select>
                            @error('assigned_to')
                                <p class="mt-1 text-danger text-xs">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-5">
                            <label class="inline-block mb-2 text-sm text-default-800 font-medium" for="assigned_date">Assignment Date</label>
                            <input class="form-input" id="assigned_date" name="assigned_date" type="date" value="{{ old('assigned_date', date('Y-m-d')) }}" required />
                        </div>

                        <div class="mb-5">
                            <label class="font-medium text-default-800 text-sm mb-2 inline-block" for="condition_on_checkout">Condition on Checkout</label>
                            <textarea class="form-input @error('condition_on_checkout') border-danger @enderror" id="condition_on_checkout" name="condition_on_checkout" placeholder="Describe the physical condition on handover..." rows="5" required>{{ old('condition_on_checkout', 'Good / Normal') }}</textarea>
                            @error('condition_on_checkout')
                                <p class="mt-1 text-danger text-xs">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mt-6 flex gap-3 md:justify-end border-t border-default-200 pt-5">
                            <a href="{{ route('assets.show', $item->asset_id) }}" class="btn border-default-200 text-default-600 hover:bg-default-100">Cancel</a>
                            <button type="submit" class="text-white btn bg-primary px-10 font-bold uppercase tracking-wide">Confirm Checkout</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="lg:col-span-4 col-span-1">
            <div class="card overflow-hidden">
                <div class="card-body bg-default-100/50">
                    <h6 class="mb-4 card-title text-base flex items-center gap-2">
                        <i class="size-4 text-primary" data-lucide="package"></i> Unit Summary
                    </h6>
                    <div class="flex items-center gap-4 mb-5">
                        <div class="size-16 bg-white border border-default-200 rounded-lg flex items-center justify-center shadow-sm">
                            <i class="size-8 text-primary/50" data-lucide="monitor"></i>
                        </div>
                        <div>
                            <h5 class="text-default-800 font-bold leading-tight">{{ $item->asset?->name }}</h5>
                            <p class="text-xs text-primary font-bold font-mono">#{{ $item->item_code }}</p>
                        </div>
                    </div>
                    <div class="space-y-3 py-4 border-t border-default-200">
                        <div class="flex justify-between items-center text-xs">
                            <span class="text-default-500 font-medium">Master Catalog:</span>
                            <span class="text-default-800 font-bold">{{ $item->asset?->asset_code }}</span>
                        </div>
                        <div class="flex justify-between items-center text-xs">
                            <span class="text-default-500 font-medium">Category:</span>
                            <span class="text-default-800 font-bold">{{ $item->asset?->category?->name ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between items-center text-xs">
                            <span class="text-default-500 font-medium">Current Location:</span>
                            <span class="text-default-800 font-bold">{{ $item->location?->name ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between items-center text-xs">
                            <span class="text-default-500 font-medium">Physical Condition:</span>
                            <span class="px-2 py-0.5 bg-success/10 text-success rounded-full font-bold uppercase">{{ $item->condition }}</span>
                        </div>
                    </div>
                </div>
                <div class="card-body border-t border-default-200">
                    <div class="flex gap-3">
                        <div class="size-8 bg-warning/10 text-warning rounded-full flex items-center justify-center shrink-0">
                            <i class="size-4" data-lucide="alert-triangle"></i>
                        </div>
                        <div>
                            <p class="text-xs text-default-600 leading-relaxed font-medium">
                                Ensure the user signs the handover document after this assignment is confirmed.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

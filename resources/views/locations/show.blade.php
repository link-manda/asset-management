@extends('layouts.app')

@section('title', 'Location Detail: ' . $location->name)

@section('content')
    @include('layouts.partials/page-title', ['subtitle' => 'Locations', 'title' => 'Location Details'])

    <div class="grid lg:grid-cols-4 grid-cols-1 gap-6">
        <div class="lg:col-span-1">
            <div class="card h-full">
                <div class="card-body">
                    <div class="text-center">
                        <div class="size-20 rounded-full bg-secondary/10 text-secondary flex items-center justify-center mx-auto mb-4">
                            <i class="size-10" data-lucide="map-pin"></i>
                        </div>
                        <h5 class="text-lg font-bold text-default-800 mb-1">{{ $location->name }}</h5>
                        <p class="text-default-500 text-sm mb-4">Total Assets: {{ $location->items->count() }} Units</p>

                        <div class="flex flex-col gap-2">
                            @can('edit locations')
                            <a href="{{ route('locations.edit', $location) }}" class="btn btn-sm bg-primary text-white w-full uppercase font-bold text-xs tracking-wider">Edit Location</a>
                            @endcan
                            <a href="{{ route('locations.index') }}" class="btn btn-sm border-default-200 text-default-600 w-full">Back to List</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-3">
            <div class="card mb-6">
                <div class="card-header">
                    <h6 class="card-title">Full Address</h6>
                </div>
                <div class="card-body">
                    <p class="text-default-600 font-medium">
                        {{ $location->address }}
                    </p>
                </div>
            </div>

            <div class="card">
                <div class="card-header flex justify-between items-center">
                    <h6 class="card-title">Physical Units at this Location</h6>
                    <a href="{{ route('inventory.index', ['location_id' => $location->id]) }}" class="btn btn-sm bg-primary text-white">View All Items</a>
                </div>
                <div class="card-body p-0">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-default-200">
                            <thead class="bg-default-100">
                                <tr class="text-[10px] font-black text-default-600 uppercase tracking-widest">
                                    <th class="px-6 py-3 text-start">Item Code</th>
                                    <th class="px-6 py-3 text-start">Asset Name</th>
                                    <th class="px-6 py-3 text-start">Serial Number</th>
                                    <th class="px-6 py-3 text-start">Status</th>
                                    <th class="px-6 py-3 text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-default-200">
                                @forelse($location->items as $item)
                                    <tr class="text-sm hover:bg-default-50 transition-all">
                                        <td class="px-6 py-4 font-bold text-primary font-mono">#{{ $item->item_code }}</td>
                                        <td class="px-6 py-4">
                                            <div class="flex flex-col">
                                                <a href="{{ route('assets.show', $item->asset_id) }}" class="font-bold text-default-800 hover:text-primary transition-all">{{ $item->asset?->name }}</a>
                                                <span class="text-[10px] text-default-500 uppercase font-medium">{{ $item->asset?->category?->name ?? '-' }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-default-600 text-xs">{{ $item->serial_number ?? 'N/A' }}</td>
                                        <td class="px-6 py-4">
                                            @php
                                                $statusClasses = [
                                                    'Available' => 'bg-success/15 text-success',
                                                    'Deployed' => 'bg-primary/15 text-primary',
                                                    'Maintenance' => 'bg-warning/15 text-warning',
                                                    'Broken' => 'bg-danger/15 text-danger',
                                                    'Disposed' => 'bg-danger text-white',
                                                ];
                                            @endphp
                                            <span class="inline-flex items-center py-0.5 px-2 rounded text-[9px] font-black uppercase tracking-wider {{ $statusClasses[$item->status] ?? 'bg-default-100 text-default-500' }}">
                                                {{ $item->status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-end">
                                            <a href="{{ route('inventory.show', $item) }}" class="text-primary hover:text-primary-600 font-bold text-xs uppercase">Details</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-10 text-center text-default-400 italic">No physical units found in this location.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

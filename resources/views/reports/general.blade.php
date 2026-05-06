@extends('layouts.vertical', ['title' => 'General Asset Report'])

@section('css')
@endsection

@section('content')
    @include('layouts.partials/page-title', ['subtitle' => 'Reports', 'title' => 'General Asset Report'])



    <!-- Data Table Section -->
    <div class="card">
        <div class="card-header border-b border-default-200 flex flex-wrap items-center justify-between gap-4">
            <h6 class="card-title text-base">Asset Item Details</h6>
            <div class="flex gap-2">
                <a href="{{ route('reports.general.excel', request()->all()) }}" class="btn btn-sm bg-success/10 text-success border border-success/20 hover:bg-success hover:text-white transition-all">
                    <i class="size-4 me-1" data-lucide="download"></i> Excel
                </a>
                <a href="{{ route('reports.general.csv', request()->all()) }}" class="btn btn-sm bg-info/10 text-info border border-info/20 hover:bg-info hover:text-white transition-all">
                    <i class="size-4 me-1" data-lucide="file-text"></i> CSV
                </a>
                <a href="{{ route('reports.general.pdf', request()->all()) }}" class="btn btn-sm bg-danger/10 text-danger border border-danger/20 hover:bg-danger hover:text-white transition-all">
                    <i class="size-4 me-1" data-lucide="file-type-2"></i> PDF
                </a>
            </div>
        </div>

        <!-- Filters -->
        <div class="p-4 border-b border-default-200 bg-default-50/50">
            <form action="{{ route('reports.general') }}" method="GET" class="grid lg:grid-cols-4 md:grid-cols-2 grid-cols-1 gap-4">
                <div>
                    <label class="text-xs font-black text-default-600 uppercase mb-1 block">Category</label>
                    <select name="category_id" class="form-input form-input-sm w-full">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-xs font-black text-default-600 uppercase mb-1 block">Location</label>
                    <select name="location_id" class="form-input form-input-sm w-full">
                        <option value="">All Locations</option>
                        @foreach($locations as $location)
                            <option value="{{ $location->id }}" {{ request('location_id') == $location->id ? 'selected' : '' }}>
                                {{ $location->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-xs font-black text-default-600 uppercase mb-1 block">Status</label>
                    <select name="status" class="form-input form-input-sm w-full">
                        <option value="">All Statuses</option>
                        <option value="Available" {{ request('status') == 'Available' ? 'selected' : '' }}>Available</option>
                        <option value="Deployed" {{ request('status') == 'Deployed' ? 'selected' : '' }}>Deployed</option>
                        <option value="Maintenance" {{ request('status') == 'Maintenance' ? 'selected' : '' }}>Maintenance</option>
                        <option value="Disposed" {{ request('status') == 'Disposed' ? 'selected' : '' }}>Disposed</option>
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <div class="relative flex-grow">
                        <input type="text" name="search" value="{{ request('search') }}" class="form-input form-input-sm ps-8 w-full" placeholder="Search assets...">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-2.5">
                            <i class="size-3.5 text-default-500" data-lucide="search"></i>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-sm bg-primary text-white px-4">Filter</button>
                    <a href="{{ route('reports.general') }}" class="btn btn-sm bg-default-200 text-default-700">Reset</a>
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-default-200">
                <thead class="bg-default-100">
                    <tr class="text-[11px] font-bold text-default-600 uppercase tracking-wider">
                        <th class="px-4 py-3 text-start">Item Code</th>
                        <th class="px-4 py-3 text-start">Asset Name / SN</th>
                        <th class="px-4 py-3 text-start">Category</th>
                        <th class="px-4 py-3 text-start">Location</th>
                        <th class="px-4 py-3 text-center">Status</th>
                        <th class="px-4 py-3 text-end">Acquisition Cost</th>
                        <th class="px-4 py-3 text-end">Current Book Value</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-default-200">
                    @forelse($items as $item)
                        <tr class="hover:bg-default-50 transition-all">
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-mono text-primary font-bold">#{{ $item->item_code }}</td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <a href="{{ route('assets.show', $item->asset_id) }}" class="group block">
                                    <div class="text-sm font-bold text-default-800 group-hover:text-primary transition-all">{{ $item->asset->name }}</div>
                                    <div class="text-[10px] text-default-400 font-medium">SN: {{ $item->serial_number ?? 'N/A' }}</div>
                                </a>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-default-600">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-default-100 border border-default-200 uppercase">{{ $item->asset->category->name ?? '-' }}</span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-default-600">
                                <div class="flex items-center gap-1">
                                    <i class="size-3 text-default-400" data-lucide="map-pin"></i>
                                    {{ $item->location->name ?? '-' }}
                                </div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-center">
                                @php
                                    $statusColor = match($item->status) {
                                        'Available' => 'bg-success/15 text-success',
                                        'Deployed' => 'bg-info/15 text-info',
                                        'Maintenance' => 'bg-warning/15 text-warning',
                                        'Disposed' => 'bg-danger/15 text-danger',
                                        default => 'bg-default-100 text-default-600'
                                    };
                                @endphp
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $statusColor }}">
                                    {{ $item->status }}
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-end font-medium">Rp {{ number_format($item->purchase_price, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-end font-bold text-primary">Rp {{ number_format($item->current_value, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-default-500 italic">Data aset tidak ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer border-t border-default-200 p-4">
            {{ $items->links('vendor.pagination.tailwind-custom') }}
        </div>
    </div>
@endsection



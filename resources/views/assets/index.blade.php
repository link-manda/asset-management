@extends('layouts.app')

@section('title', 'Asset Catalog (Master)')

@section('content')
    @include('layouts.partials/page-title', ['subtitle' => 'Catalog', 'title' => 'Master Asset List'])

    <div class="grid grid-cols-1 gap-5 mb-5">
        <div class="card">
            <div class="card-header border-b border-default-200">
                <form action="{{ route('assets.index') }}" method="GET" class="flex flex-wrap gap-3 items-center w-full">
                    <div class="relative flex-1 min-w-[200px]">
                        <input name="search" value="{{ request('search') }}" class="ps-11 form-input form-input-sm w-full" placeholder="Search name or asset code..." type="text" />
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3">
                            <i class="size-3.5 flex items-center text-default-500" data-lucide="search"></i>
                        </div>
                    </div>
                    
                    <select name="category_id" class="form-select form-select-sm min-w-[150px]">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>

                    <select name="location_id" class="form-select form-select-sm min-w-[150px]">
                        <option value="">All Locations</option>
                        @foreach($locations as $location)
                            <option value="{{ $location->id }}" {{ request('location_id') == $location->id ? 'selected' : '' }}>
                                {{ $location->name }}
                            </option>
                        @endforeach
                    </select>

                    <select name="status" class="form-select form-select-sm min-w-[130px]">
                        <option value="">All Status</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                {{ $status }}
                            </option>
                        @endforeach
                    </select>

                    <div class="flex gap-2">
                        <button type="submit" class="btn btn-sm bg-primary text-white px-4">
                            Filter
                        </button>
                        @if(request()->anyFilled(['search', 'category_id', 'location_id', 'status']))
                            <a href="{{ route('assets.index') }}" class="btn btn-sm bg-default-150 text-default-700">
                                Reset
                            </a>
                        @endif
                    </div>
                    
                    <div class="ms-auto">
                        @can('create assets')
                        <a href="{{ route('assets.create') }}" class="btn btn-sm bg-primary text-white">
                            <i class="size-4 me-1" data-lucide="plus"></i> Add New Asset
                        </a>
                        @endcan
                    </div>
                </form>
            </div>
            <div class="flex flex-col">
                <div class="overflow-x-auto">
                    <div class="inline-block min-w-full align-middle">
                        <div class="overflow-hidden">
                            <table class="min-w-full divide-y divide-default-200">
                                <thead class="bg-default-150 font-normal">
                                    <tr class="text-sm text-default-700 uppercase tracking-wider text-[11px] font-bold">
                                        <th class="px-3.5 py-3 text-start" scope="col">Asset Code</th>
                                        <th class="px-3.5 py-3 text-start" scope="col">Asset Name</th>
                                        <th class="px-3.5 py-3 text-start" scope="col">Category</th>
                                        <th class="px-3.5 py-3 text-start" scope="col">Total Stock</th>
                                        <th class="px-3.5 py-3 text-start" scope="col">Estimated Value</th>
                                        <th class="px-3.5 py-3 text-start" scope="col">Distribution</th>
                                        @can('edit assets')
                                        <th class="px-3.5 py-3 text-center" scope="col">Actions</th>
                                        @endcan
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-default-200">
                                    @forelse ($assets as $asset)
                                        <tr class="text-default-800 font-normal hover:bg-default-50 transition-all">
                                            <td class="px-3.5 py-4 whitespace-nowrap text-sm text-primary font-bold">
                                                {{ $asset->asset_code }}
                                            </td>
                                            <td class="px-3.5 py-4 whitespace-nowrap text-sm">
                                                <a href="{{ route('assets.show', $asset) }}" class="group">
                                                    <h6 class="text-default-800 group-hover:text-primary transition-all font-semibold">{{ $asset->name }}</h6>
                                                    <p class="text-[10px] text-default-400 group-hover:text-primary/70">Click to view overview</p>
                                                </a>
                                            </td>
                                            <td class="px-3.5 py-4 whitespace-nowrap text-sm">
                                                <span class="inline-flex py-0.5 px-2 rounded text-[10px] font-bold bg-default-100 text-default-600 border border-default-200">
                                                    {{ $asset->category?->name ?? 'Uncategorized' }}
                                                </span>
                                            </td>
                                            <td class="px-3.5 py-4 whitespace-nowrap text-sm">
                                                <div class="font-bold text-default-800">
                                                    {{ $asset->total_quantity }} {{ $asset->uom?->symbol }}
                                                </div>
                                            </td>
                                            <td class="px-3.5 py-4 whitespace-nowrap text-sm">
                                                <div class="font-bold text-default-800">
                                                    Rp {{ number_format($asset->total_value, 0, ',', '.') }}
                                                </div>
                                                <p class="text-[10px] text-default-400">@ Rp {{ number_format($asset->price, 0, ',', '.') }}</p>
                                            </td>
                                            <td class="px-3.5 py-4 whitespace-nowrap">
                                                @php
                                                    $itemStatuses = $asset->items->groupBy('status')->map->count();
                                                @endphp
                                                <div class="flex flex-wrap gap-1">
                                                    @foreach($itemStatuses as $status => $count)
                                                        @php
                                                            $statusClasses = [
                                                                'Available' => 'bg-success/15 text-success',
                                                                'Deployed' => 'bg-primary/15 text-primary',
                                                                'Maintenance' => 'bg-warning/15 text-warning',
                                                                'Broken' => 'bg-danger/15 text-danger',
                                                                'Disposed' => 'bg-danger text-white',
                                                            ];
                                                            $class = $statusClasses[$status] ?? 'bg-default-100 text-default-500';
                                                        @endphp
                                                        <span class="inline-flex items-center py-0.5 px-1.5 rounded text-[9px] font-bold {{ $class }}">
                                                            {{ $count }} {{ $status }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            </td>
                                            @can('edit assets')
                                            <td class="px-3.5 py-4 text-center">
                                                <div class="hs-dropdown relative inline-flex">
                                                    <button class="hs-dropdown-toggle btn size-8 bg-default-100 hover:bg-default-600 text-default-500 hover:text-white rounded-full transition-all" type="button">
                                                        <i class="size-4" data-lucide="more-vertical"></i>
                                                    </button>
                                                    <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-32 z-50 bg-white shadow-md rounded-lg p-2 mt-2 border border-default-200" role="menu">
                                                        <a class="flex items-center gap-1.5 py-1.5 font-medium px-3 text-sm text-default-500 hover:bg-default-150 rounded" href="{{ route('assets.show', $asset) }}">
                                                            <i class="size-3.5" data-lucide="eye"></i> View Details
                                                        </a>
                                                        <a class="flex items-center gap-1.5 py-1.5 font-medium px-3 text-sm text-warning hover:bg-warning/10 rounded" href="{{ route('assets.edit', $asset) }}">
                                                            <i class="size-3.5" data-lucide="edit-3"></i> Edit Asset
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                            @endcan
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="{{ auth()->user()->can('edit assets') ? 7 : 6 }}" class="px-3.5 py-12 text-center text-default-400 italic">
                                                No asset catalog registered yet.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card-footer p-4 border-t border-default-200">
                    {{ $assets->links('vendor.pagination.tailwind-custom') }}
                </div>
            </div>
        </div>
    </div>
@endsection

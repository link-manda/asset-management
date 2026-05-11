@extends('layouts.app')

@section('title', 'Location Management')

@section('content')
    @include('layouts.partials/page-title', ['subtitle' => 'Master Data', 'title' => 'Locations'])

    <div class="grid grid-cols-1 gap-5 mb-5">
        <div class="card">
            <div class="card-header flex justify-between items-center">
                <h6 class="card-title">Location List</h6>
                @can('create locations')
                <button data-hs-overlay="#modal-add-location" class="btn btn-sm bg-primary text-white">
                    <i class="size-4 me-1" data-lucide="plus"></i>Add Location
                </button>
                @endcan
            </div>
            <div class="card-body p-0">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-default-200">
                        <thead class="bg-default-100 font-normal whitespace-nowrap">
                            <tr class="text-sm text-default-800 uppercase tracking-wider text-[11px] font-bold">
                                <th class="px-3.5 py-3 text-start" scope="col">Location Name</th>
                                <th class="px-3.5 py-3 text-start" scope="col">Parent Location</th>
                                <th class="px-3.5 py-3 text-start" scope="col">Address</th>
                                <th class="px-3.5 py-3 text-start" scope="col">Total Units</th>
                                @canany(['edit locations', 'delete locations'])
                                <th class="px-3.5 py-3 text-center" scope="col">Actions</th>
                                @endcanany
                                </tr>

                        </thead>
                        <tbody class="divide-y divide-default-200">
                            @foreach ($locations as $location)
                                <tr class="text-default-800 font-normal whitespace-nowrap hover:bg-default-50 transition-all">
                                    <td class="px-3.5 py-4 text-sm font-medium text-default-800">
                                        <div class="flex items-center gap-2">
                                            @if($location->parent_id)
                                                <i class="size-3 text-default-400" data-lucide="corner-down-right"></i>
                                            @endif
                                            {{ $location->name }}
                                        </div>
                                    </td>
                                    <td class="px-3.5 py-4 text-sm">
                                        @if($location->parent)
                                            <span class="text-default-500">{{ $location->parent->name }}</span>
                                        @else
                                            <span class="text-default-300 italic">- Top Level -</span>
                                        @endif
                                    </td>
                                    <td class="px-3.5 py-4 text-default-500 text-sm whitespace-normal max-w-xs">{{ $location->address }}</td>
                                    <td class="px-3.5 py-4">
                                        <span class="inline-flex items-center gap-x-1.5 py-0.5 px-2.5 rounded text-xs font-medium bg-secondary/15 text-secondary">
                                            {{ $location->items_count }} Units
                                        </span>
                                    </td>
                                    @canany(['edit locations', 'delete locations'])
                                    <td class="px-3.5 py-4 text-center">
                                        <div class="hs-dropdown relative inline-flex">
                                            <button class="hs-dropdown-toggle btn size-8 bg-default-100 hover:bg-default-600 text-default-500 hover:text-white rounded-full transition-all" type="button">
                                                <i class="size-4" data-lucide="more-vertical"></i>
                                            </button>
                                            <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-32 z-50 bg-white shadow-md rounded-lg p-2 mt-2 border border-default-200" role="menu">
                                                @can('edit locations')
                                                <button data-hs-overlay="#modal-edit-{{ $location->id }}" class="w-full flex items-center gap-1.5 py-1.5 font-medium px-3 text-sm text-default-500 hover:bg-default-150 rounded">
                                                    <i class="size-3.5" data-lucide="edit-3"></i> Edit
                                                </button>
                                                @endcan
                                                
                                                @can('delete locations')
                                                <hr class="my-1 border-default-200">
                                                <form action="{{ route('locations.destroy', $location) }}" method="POST" class="block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="w-full flex items-center gap-1.5 py-1.5 font-medium px-3 text-sm text-danger hover:bg-danger/10 rounded delete-confirm" data-name="Location {{ $location->name }}">
                                                        <i class="size-3.5" data-lucide="trash-2"></i> Delete
                                                    </button>
                                                </form>
                                                @endcan
                                            </div>
                                        </div>
                                    </td>
                                    @endcanany
                                </tr>

                                {{-- MODAL EDIT --}}
                                <div id="modal-edit-{{ $location->id }}" class="hs-overlay hidden size-full fixed top-0 start-0 z-80 overflow-x-hidden overflow-y-auto pointer-events-none">
                                    <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto flex items-center min-h-[calc(100%-3.5rem)]">
                                        <div class="flex flex-col bg-card border border-default-200 shadow-sm rounded-md pointer-events-auto w-full">
                                             <div class="flex justify-between items-center py-3 px-4 border-b border-default-200">
                                                <h3 class="font-bold text-default-800 text-lg">Edit Location</h3>
                                                <button type="button" class="size-8 inline-flex justify-center items-center gap-x-2 rounded-full border border-transparent bg-default-100 text-default-800 hover:bg-default-200 focus:outline-none focus:bg-default-200 disabled:opacity-50 disabled:pointer-events-none" data-hs-overlay="#modal-edit-{{ $location->id }}">
                                                    <i class="size-4" data-lucide="x"></i>
                                                </button>
                                            </div>
                                            <form action="{{ route('locations.update', $location) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="p-5">
                                                    <div class="mb-5">
                                                        <label class="inline-block mb-2 text-sm text-default-800 font-medium">Location Name</label>
                                                        <input type="text" name="name" class="form-input" value="{{ $location->name }}" required>
                                                    </div>
                                                    <div class="mb-5">
                                                        <label class="inline-block mb-2 text-sm text-default-800 font-medium">Parent Location</label>
                                                        <select name="parent_id" class="form-input">
                                                            <option value="">- Set as Top Level Location -</option>
                                                            @foreach($allLocations as $parent)
                                                                @if($parent->id != $location->id)
                                                                    <option value="{{ $parent->id }}" {{ $location->parent_id == $parent->id ? 'selected' : '' }}>{{ $parent->name }}</option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                        <p class="mt-1 text-xs text-default-400 italic">Select a parent if this location is a sub-area.</p>
                                                    </div>
                                                    <div class="mb-0">
                                                        <label class="inline-block mb-2 text-sm text-default-800 font-medium">Full Address</label>
                                                        <textarea name="address" class="form-input" rows="3" required>{{ $location->address }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="flex justify-end items-center gap-2 py-3 px-4 border-t border-default-200">
                                                    <button type="button" class="btn border-default-200 text-default-600" data-hs-overlay="#modal-edit-{{ $location->id }}">Cancel</button>
                                                    <button type="submit" class="btn bg-primary text-white">Save Changes</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="p-4 border-t border-default-200">
                {{ $locations->links('vendor.pagination.tailwind-custom') }}
            </div>
        </div>
    </div>

    {{-- MODAL ADD --}}
    <div id="modal-add-location" class="hs-overlay hidden size-full fixed top-0 start-0 z-80 overflow-x-hidden overflow-y-auto pointer-events-none">
        <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto flex items-center min-h-[calc(100%-3.5rem)]">
            <div class="flex flex-col bg-card border border-default-200 shadow-sm rounded-md pointer-events-auto w-full">
                <div class="flex justify-between items-center py-3 px-4 border-b border-default-200">
                    <h3 class="font-bold text-default-800 text-lg">Add New Location</h3>
                    <button type="button" class="size-8 inline-flex justify-center items-center gap-x-2 rounded-full border border-transparent bg-default-100 text-default-800 hover:bg-default-200 focus:outline-none focus:bg-default-200 disabled:opacity-50 disabled:pointer-events-none" data-hs-overlay="#modal-add-location">
                        <i class="size-4" data-lucide="x"></i>
                    </button>
                </div>
                <form action="{{ route('locations.store') }}" method="POST">
                    @csrf
                    <div class="p-5">
                        <div class="mb-5">
                            <label class="inline-block mb-2 text-sm text-default-800 font-medium">Location Name</label>
                            <input type="text" name="name" class="form-input" placeholder="e.g.: Warehouse A" required>
                        </div>
                        <div class="mb-5">
                            <label class="inline-block mb-2 text-sm text-default-800 font-medium">Parent Location</label>
                            <select name="parent_id" class="form-input">
                                <option value="">- Set as Top Level Location -</option>
                                @foreach($allLocations as $parent)
                                    <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-0">
                            <label class="inline-block mb-2 text-sm text-default-800 font-medium">Full Address</label>
                            <textarea name="address" class="form-input" rows="3" placeholder="Detailed location address..." required></textarea>
                        </div>
                    </div>
                    <div class="flex justify-end items-center gap-2 py-3 px-4 border-t border-default-200">
                        <button type="button" class="btn border-default-200 text-default-600" data-hs-overlay="#modal-add-location">Cancel</button>
                        <button type="submit" class="btn bg-primary text-white">Create Location</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

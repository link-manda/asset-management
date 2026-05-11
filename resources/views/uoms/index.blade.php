@extends('layouts.app')

@section('title', 'Unit Management (UoM)')

@section('content')
    @include('layouts.partials/page-title', ['subtitle' => 'Master Data', 'title' => 'Units of Measurement'])

    <div class="grid grid-cols-1 gap-5 mb-5">
        <div class="card">
            <div class="card-header flex justify-between items-center">
                <h6 class="card-title">Unit List</h6>
                @can('create uoms')
                <button data-hs-overlay="#modal-add-uom" class="btn btn-sm bg-primary text-white">
                    <i class="size-4 me-1" data-lucide="plus"></i>Add Unit
                </button>
                @endcan
            </div>
            <div class="card-body p-0">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-default-200">
                        <thead class="bg-default-100 font-normal whitespace-nowrap">
                            <tr class="text-sm text-default-800 uppercase tracking-wider text-[11px] font-bold">
                                <th class="px-3.5 py-3 text-start" scope="col">Unit Name</th>
                                <th class="px-3.5 py-3 text-start" scope="col">Symbol / Initial</th>
                                <th class="px-3.5 py-3 text-start" scope="col">Used In</th>
                                <th class="px-3.5 py-3 text-center" scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-default-200">
                            @forelse ($uoms as $uom)
                                <tr class="text-default-800 font-normal whitespace-nowrap hover:bg-default-50 transition-all">
                                    <td class="px-3.5 py-4 text-sm font-medium text-default-800">{{ $uom->name }}</td>
                                    <td class="px-3.5 py-4 text-default-500 text-sm">
                                        <span class="px-2 py-1 bg-default-100 rounded border border-default-200 font-bold">{{ $uom->symbol ?? '-' }}</span>
                                    </td>
                                    <td class="px-3.5 py-4">
                                        <span class="inline-flex items-center gap-x-1.5 py-0.5 px-2.5 rounded text-xs font-medium bg-info/15 text-info">
                                            {{ $uom->assets_count }} Assets
                                        </span>
                                    </td>
                                    <td class="px-3.5 py-4 text-center">
                                        <div class="hs-dropdown relative inline-flex">
                                            <button class="hs-dropdown-toggle btn size-8 bg-default-100 hover:bg-default-600 text-default-500 hover:text-white rounded-full transition-all" type="button">
                                                <i class="size-4" data-lucide="more-vertical"></i>
                                            </button>
                                            <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-32 z-50 bg-white shadow-md rounded-lg p-2 mt-2 border border-default-200" role="menu">
                                                @can('edit uoms')
                                                <button data-hs-overlay="#modal-edit-{{ $uom->id }}" class="w-full flex items-center gap-1.5 py-1.5 font-medium px-3 text-sm text-default-500 hover:bg-default-150 rounded">
                                                    <i class="size-3.5" data-lucide="edit-3"></i> Edit Unit
                                                </button>
                                                @endcan
                                                
                                                @can('delete uoms')
                                                <hr class="my-1 border-default-200">
                                                <form action="{{ route('uoms.destroy', $uom) }}" method="POST" class="block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="w-full flex items-center gap-1.5 py-1.5 font-medium px-3 text-sm text-danger hover:bg-danger/10 rounded delete-confirm" data-name="Unit {{ $uom->name }}">
                                                        <i class="size-3.5" data-lucide="trash-2"></i> Delete
                                                    </button>
                                                </form>
                                                @endcan
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                {{-- MODAL EDIT --}}
                                <div id="modal-edit-{{ $uom->id }}" class="hs-overlay hidden size-full fixed top-0 start-0 z-80 overflow-x-hidden overflow-y-auto pointer-events-none">
                                    <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto flex items-center min-h-[calc(100%-3.5rem)]">
                                        <div class="flex flex-col bg-card border border-default-200 shadow-sm rounded-md pointer-events-auto w-full">
                                            <div class="flex justify-between items-center py-3 px-4 border-b border-default-200">
                                                <h3 class="font-bold text-default-800 text-lg">Edit Unit</h3>
                                                <button type="button" class="size-8 inline-flex justify-center items-center gap-x-2 rounded-full border border-transparent bg-default-100 text-default-800 hover:bg-default-200 focus:outline-none focus:bg-default-200 disabled:opacity-50 disabled:pointer-events-none" data-hs-overlay="#modal-edit-{{ $uom->id }}">
                                                    <i class="size-4" data-lucide="x"></i>
                                                </button>
                                            </div>
                                            <form action="{{ route('uoms.update', $uom) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="p-5">
                                                    <div class="mb-5">
                                                        <label class="inline-block mb-2 text-sm text-default-800 font-medium">Unit Name</label>
                                                        <input type="text" name="name" class="form-input" value="{{ $uom->name }}" required>
                                                    </div>
                                                    <div class="mb-0">
                                                        <label class="inline-block mb-2 text-sm text-default-800 font-medium">Symbol / Initial</label>
                                                        <input type="text" name="symbol" class="form-input" value="{{ $uom->symbol }}" placeholder="e.g.: Pcs, Set, Kg">
                                                    </div>
                                                </div>
                                                <div class="flex justify-end items-center gap-2 py-3 px-4 border-t border-default-200">
                                                    <button type="button" class="btn border-default-200 text-default-600" data-hs-overlay="#modal-edit-{{ $uom->id }}">Cancel</button>
                                                    <button type="submit" class="btn bg-primary text-white">Save Changes</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-3.5 py-12 text-center text-default-400 italic">No unit records found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="p-4 border-t border-default-200">
                {{ $uoms->links('vendor.pagination.tailwind-custom') }}
            </div>
        </div>
    </div>

    {{-- MODAL ADD --}}
    <div id="modal-add-uom" class="hs-overlay hidden size-full fixed top-0 start-0 z-80 overflow-x-hidden overflow-y-auto pointer-events-none">
        <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto flex items-center min-h-[calc(100%-3.5rem)]">
            <div class="flex flex-col bg-card border border-default-200 shadow-sm rounded-md pointer-events-auto w-full">
                <div class="flex justify-between items-center py-3 px-4 border-b border-default-200">
                    <h3 class="font-bold text-default-800 text-lg">Add New Unit</h3>
                    <button type="button" class="size-8 inline-flex justify-center items-center gap-x-2 rounded-full border border-transparent bg-default-100 text-default-800 hover:bg-default-200 focus:outline-none focus:bg-default-200 disabled:opacity-50 disabled:pointer-events-none" data-hs-overlay="#modal-add-uom">
                        <i class="size-4" data-lucide="x"></i>
                    </button>
                </div>
                <form action="{{ route('uoms.store') }}" method="POST">
                    @csrf
                    <div class="p-5">
                        <div class="mb-5">
                            <label class="inline-block mb-2 text-sm text-default-800 font-medium">Unit Name</label>
                            <input type="text" name="name" class="form-input" placeholder="e.g.: Pieces, Kilogram" required>
                        </div>
                        <div class="mb-0">
                            <label class="inline-block mb-2 text-sm text-default-800 font-medium">Symbol / Initial</label>
                            <input type="text" name="symbol" class="form-input" placeholder="e.g.: Pcs, Kg">
                        </div>
                    </div>
                    <div class="flex justify-end items-center gap-2 py-3 px-4 border-t border-default-200">
                        <button type="button" class="btn border-default-200 text-default-600" data-hs-overlay="#modal-add-uom">Cancel</button>
                        <button type="submit" class="btn bg-primary text-white">Create Unit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

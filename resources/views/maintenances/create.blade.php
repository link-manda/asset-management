@extends('layouts.app')

@section('title', 'Request New Repair')

@section('content')
    @include('layouts.partials/page-title', ['subtitle' => 'Maintenance', 'title' => 'Request Service'])

    <div class="grid lg:grid-cols-12 grid-cols-1 gap-6" x-data="assetPicker()">
        <div class="lg:col-span-9 col-span-1">
            <div class="card">
                <div class="card-body">
                    <h6 class="mb-4 card-title text-base">Service Request Details</h6>

                    <form action="{{ route('maintenances.store') }}" method="POST">
                        @csrf

                        <div class="mb-6">
                            <label class="inline-block mb-2 text-sm text-default-800 font-medium">Target Physical Unit</label>
                            <div class="flex shadow-sm rounded-md overflow-hidden border border-default-200">
                                <input type="hidden" name="asset_item_id" :value="selectedItem ? selectedItem.id : ''" required>
                                <div class="grow p-3 bg-default-50 flex items-center gap-3">
                                    <template x-if="selectedItem">
                                        <div class="flex items-center gap-3">
                                            <div class="size-10 bg-primary/10 text-primary rounded-lg flex items-center justify-center">
                                                <i class="size-6" data-lucide="package"></i>
                                            </div>
                                            <div>
                                                <h6 class="text-sm font-bold text-default-800" x-text="selectedItem.asset.name"></h6>
                                                <p class="text-xs text-primary font-mono font-bold" x-text="'#' + selectedItem.item_code"></p>
                                            </div>
                                        </div>
                                    </template>
                                    <template x-if="!selectedItem">
                                        <span class="text-default-400 italic text-sm">Please select a unit to repair...</span>
                                    </template>
                                </div>
                                <button type="button" data-hs-overlay="#modal-asset-picker" class="btn bg-default-200 text-default-800 px-4 hover:bg-default-300 transition-all font-bold text-xs uppercase tracking-wider">
                                    <i class="size-4 me-1" data-lucide="search"></i> Search Unit
                                </button>
                            </div>
                            @error('asset_item_id')
                                <p class="mt-1 text-danger text-xs">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid lg:grid-cols-2 grid-cols-1 gap-5 mb-5">
                            <div class="col-span-1">
                                <label class="inline-block mb-2 text-sm text-default-800 font-medium" for="maintenance_date">Maintenance Date</label>
                                <input class="form-input @error('maintenance_date') border-danger @enderror" id="maintenance_date" name="maintenance_date" type="date" value="{{ old('maintenance_date', date('Y-m-d')) }}" required />
                                @error('maintenance_date')
                                    <p class="mt-1 text-danger text-xs">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col-span-1">
                                <label class="inline-block mb-2 text-sm text-default-800 font-medium" for="type">Service Type</label>
                                <select class="form-select @error('type') border-danger @enderror" id="type" name="type" required>
                                    <option value="Repair" {{ old('type') == 'Repair' ? 'selected' : '' }}>Repair</option>
                                    <option value="Routine" {{ old('type') == 'Routine' ? 'selected' : '' }}>Routine</option>
                                    <option value="Upgrade" {{ old('type') == 'Upgrade' ? 'selected' : '' }}>Upgrade</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid lg:grid-cols-2 grid-cols-1 gap-5 mb-5">
                            <div class="col-span-1">
                                <label class="inline-block mb-2 text-sm text-default-800 font-medium" for="cost">Estimated Cost</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 start-0 flex items-center ps-3 text-default-500">Rp</span>
                                    <input type="number" name="cost" id="cost" class="form-input ps-11 @error('cost') border-danger @enderror" placeholder="0" value="{{ old('cost', 0) }}" required>
                                </div>
                                @error('cost')
                                    <p class="mt-1 text-danger text-xs">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col-span-1">
                                <label class="inline-block mb-2 text-sm text-default-800 font-medium" for="status">Initial Status</label>
                                <select class="form-select @error('status') border-danger @enderror" id="status" name="status" required>
                                    <option value="Scheduled" {{ old('status') == 'Scheduled' ? 'selected' : '' }}>Scheduled</option>
                                    <option value="In Progress" {{ old('status') == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="Completed" {{ old('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-5">
                            <label class="font-medium text-default-800 text-sm mb-2 inline-block" for="description">Issue / Damage Description</label>
                            <textarea class="form-input @error('description') border-danger @enderror" id="description" name="description" placeholder="Explain the damage details or reason for service..." rows="5" required>{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-danger text-xs">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mt-6 flex gap-3 md:justify-end border-t border-default-200 pt-5">
                            <a href="{{ route('maintenances.index') }}" class="btn border-default-200 text-default-600 hover:bg-default-100">Cancel</a>
                            <button type="submit" class="text-white btn bg-primary px-10">Submit Request</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- ASSET PICKER MODAL --}}
        <div id="modal-asset-picker" class="hs-overlay hidden size-full fixed top-0 start-0 z-80 overflow-x-hidden overflow-y-auto pointer-events-none">
            <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-2xl sm:w-full m-3 sm:mx-auto min-h-[calc(100%-3.5rem)] flex items-center">
                <div class="flex flex-col bg-white border border-default-200 shadow-sm rounded-md pointer-events-auto w-full max-h-[80vh]">
                    <div class="flex justify-between items-center py-3 px-4 border-b border-default-200 bg-default-50">
                        <h3 class="font-bold text-default-800">Select Unit for Maintenance</h3>
                        <button type="button" class="size-8 inline-flex justify-center items-center gap-x-2 rounded-full border border-transparent bg-default-100 text-default-800 hover:bg-default-200" data-hs-overlay="#modal-asset-picker">
                            <i class="size-4" data-lucide="x"></i>
                        </button>
                    </div>
                    <div class="p-4 border-b border-default-200">
                        <div class="relative">
                            <input type="text" x-model="searchQuery" class="form-input ps-10" placeholder="Search by name, code or serial number...">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3.5">
                                <i class="size-4 text-default-400" data-lucide="search"></i>
                            </div>
                        </div>
                    </div>
                    <div class="overflow-y-auto grow p-0">
                        <table class="min-w-full divide-y divide-default-200">
                            <thead class="bg-default-100 sticky top-0">
                                <tr class="text-xs text-default-600 uppercase font-bold">
                                    <th class="px-4 py-3 text-start">Code / Name</th>
                                    <th class="px-4 py-3 text-start">Serial Number</th>
                                    <th class="px-4 py-3 text-start">Location</th>
                                    <th class="px-4 py-3 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-default-200">
                                <template x-for="item in filteredItems" :key="item.id">
                                    <tr class="hover:bg-primary/5 transition-all cursor-pointer" @click="selectItem(item)">
                                        <td class="px-4 py-3">
                                            <div class="flex flex-col">
                                                <span class="font-bold text-primary font-mono" x-text="'#' + item.item_code"></span>
                                                <span class="text-xs text-default-800 font-medium" x-text="item.asset.name"></span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-xs text-default-600" x-text="item.serial_number || '-'"></td>
                                        <td class="px-4 py-3 text-xs text-default-600" x-text="item.location ? item.location.name : 'N/A'"></td>
                                        <td class="px-4 py-3 text-center">
                                            <button type="button" class="btn btn-sm bg-primary/10 text-primary hover:bg-primary hover:text-white transition-all text-[10px] font-bold uppercase">Select</button>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-3 col-span-1">
            {{-- Help Card --}}
            <div class="space-y-5">
                <div class="card border-primary/20 border relative overflow-hidden">
                    <div class="card-body relative z-10">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="size-10 bg-primary/10 text-primary rounded flex items-center justify-center">
                                <i class="size-6" data-lucide="help-circle"></i>
                            </div>
                            <h6 class="font-bold text-default-800">Need Help?</h6>
                        </div>
                        <p class="text-xs text-default-500 leading-relaxed mb-4">
                            If the asset is completely broken, please change the asset status to "Broken" in the edit asset page instead of filing a maintenance request.
                        </p>
                        <a href="{{ route('assets.index') }}" class="text-xs font-bold text-primary hover:underline flex items-center gap-1 uppercase tracking-wider">
                            Asset List <i class="size-3" data-lucide="arrow-right"></i>
                        </a>
                    </div>
                    <div class="absolute -bottom-6 -right-6 size-24 bg-primary/5 rounded-full blur-xl"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
<script>
    function assetPicker() {
        return {
            searchQuery: '',
            selectedItem: null,
            items: @json($items),
            get filteredItems() {
                if (this.searchQuery === '') return this.items;
                return this.items.filter(item => {
                    return item.item_code.toLowerCase().includes(this.searchQuery.toLowerCase()) || 
                           item.asset.name.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                           (item.serial_number && item.serial_number.toLowerCase().includes(this.searchQuery.toLowerCase()));
                });
            },
            selectItem(item) {
                this.selectedItem = item;
                // Close Modal using Preline API if available, or just via overlay click
                const modal = document.querySelector('#modal-asset-picker');
                if (window.HSOverlay) {
                    HSOverlay.close(modal);
                } else {
                    modal.click();
                }
            }
        }
    }
</script>
@endpush

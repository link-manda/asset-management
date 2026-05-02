@extends('layouts.app')

@section('title', 'Daftar Asset')

@section('content')
    @include('layouts.partials/page-title', ['subtitle' => 'Assets', 'title' => 'List View'])

    <div class="grid grid-cols-1 gap-5 mb-5">
        <div class="card">
            <div class="card-header flex justify-between items-center">
                <div class="flex gap-3 items-center">
                    <div class="relative">
                        <input class="ps-11 form-input form-input-sm w-full" placeholder="Search for assets..." type="text" />
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3">
                            <i class="size-3.5 flex items-center text-default-500" data-lucide="search"></i>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" id="btn-bulk-print" class="btn btn-sm bg-info text-white">
                        <i class="size-4 me-1" data-lucide="printer"></i> Bulk Print
                    </button>
                    <a href="{{ route('assets.create') }}" class="btn btn-sm bg-primary text-white">
                        <i class="size-4 me-1" data-lucide="plus"></i>Tambah Asset
                    </a>
                </div>
            </div>
            <div class="flex flex-col">
                <div class="overflow-x-auto">
                    <div class="min-w-full inline-block align-middle">
                        <div class="overflow-hidden">
                            <table class="min-w-full divide-y divide-default-200">
                                <thead class="bg-default-150">
                                    <tr class="text-sm font-normal text-default-700">
                                        <th class="px-3.5 py-3 text-start w-10">
                                            <input type="checkbox" id="select-all" class="form-checkbox size-4 rounded border-default-300 text-primary">
                                        </th>
                                        <th class="px-3.5 py-3 text-start" scope="col">Asset Code</th>
                                        <th class="px-3.5 py-3 text-start" scope="col">Asset Name</th>
                                        <th class="px-3.5 py-3 text-start" scope="col">Category</th>
                                        <th class="px-3.5 py-3 text-start" scope="col">Total Qty</th>
                                        <th class="px-3.5 py-3 text-start" scope="col">Total Nilai</th>
                                        <th class="px-3.5 py-3 text-start" scope="col">Status</th>
                                        <th class="px-3.5 py-3 text-start" scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-default-200">
                                    @foreach ($assets as $asset)
                                        <tr class="text-default-800 font-normal">
                                            <td class="px-3.5 py-2.5">
                                                <input type="checkbox" value="{{ $asset->id }}" class="asset-checkbox form-checkbox size-4 rounded border-default-300 text-primary">
                                            </td>
                                            <td class="px-3.5 py-2.5 whitespace-nowrap text-sm text-primary font-medium">
                                                #{{ $asset->asset_code }}
                                            </td>
                                            <td class="px-3.5 py-2.5 whitespace-nowrap text-sm">
                                                <a href="{{ route('assets.show', $asset) }}" class="flex items-center gap-2">
                                                    <h6 class="text-default-800 hover:text-primary transition-all">{{ $asset->name }}</h6>
                                                </a>
                                            </td>
                                            <td class="px-3.5 py-2.5 whitespace-nowrap text-sm">
                                                <div class="inline-flex py-0.5 px-2.5 rounded text-xs font-normal bg-default-100 border border-default-200 text-default-500">
                                                    {{ $asset->category?->name ?? 'Uncategorized' }}
                                                </div>
                                            </td>
                                            <td class="px-3.5 py-2.5 whitespace-nowrap text-sm">
                                                <div class="flex items-center gap-1.5 font-bold text-default-800">
                                                    {{ $asset->total_quantity }} {{ $asset->uom?->symbol }}
                                                </div>
                                            </td>
                                            <td class="px-3.5 py-2.5 whitespace-nowrap text-sm">
                                                <div class="font-bold text-default-800">
                                                    Rp {{ number_format($asset->total_value, 0, ',', '.') }}
                                                </div>
                                                <p class="text-[10px] text-default-500">@ Rp {{ number_format($asset->price, 0, ',', '.') }}</p>
                                            </td>
                                            <td class="px-3.5 py-2.5 whitespace-nowrap">
                                                @php
                                                    $statusClasses = [
                                                        'Available' => 'bg-success/15 text-success',
                                                        'Deployed' => 'bg-primary/15 text-primary',
                                                        'Maintenance' => 'bg-warning/15 text-warning',
                                                        'Broken' => 'bg-danger/15 text-danger',
                                                        'Lost' => 'bg-default-100 text-default-500',
                                                    ];
                                                    $class = $statusClasses[$asset->status] ?? 'bg-default-100 text-default-500';
                                                @endphp
                                                <span class="inline-flex items-center gap-x-1.5 py-0.5 px-2.5 rounded text-xs font-medium {{ $class }}">
                                                    {{ $asset->status }}
                                                </span>
                                            </td>
                                            <td class="px-3.5 py-2.5">
                                                <div class="hs-dropdown relative inline-flex">
                                                    <button aria-expanded="false" aria-haspopup="menu" aria-label="Dropdown"
                                                        class="hs-dropdown-toggle btn size-7.5 bg-default-200 hover:bg-default-600 text-default-500 hover:text-white"
                                                        hs-dropdown-placement="bottom-end" type="button">
                                                        <i class="size-4" data-lucide="more-horizontal"></i>
                                                    </button>
                                                    <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-32 z-50 bg-white shadow-md rounded-lg p-2 mt-2 dark:bg-default-50 dark:border dark:border-default-200" role="menu">
                                                        <a class="flex items-center gap-1.5 py-1.5 font-medium px-3 text-sm text-default-500 hover:bg-default-150 rounded"
                                                            href="{{ route('assets.show', $asset) }}">
                                                            <i class="size-3" data-lucide="eye"></i>
                                                            Show
                                                        </a>
                                                        <a class="flex items-center gap-1.5 py-1.5 font-medium px-3 text-sm text-default-500 hover:bg-default-150 rounded"
                                                            href="{{ route('assets.print', $asset) }}" target="_blank">
                                                            <i class="size-3" data-lucide="printer"></i>
                                                            Print Label
                                                        </a>
                                                        <a class="flex items-center gap-1.5 py-1.5 font-medium px-3 text-sm text-default-500 hover:bg-default-150 rounded"
                                                            href="{{ route('assets.edit', $asset) }}">
                                                            <i class="size-3" data-lucide="edit"></i>
                                                            Edit
                                                        </a>
                                                        <div class="h-px bg-default-200 my-1"></div>
                                                        <form action="{{ route('assets.destroy', $asset) }}" method="POST" class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="w-full flex items-center gap-1.5 py-1.5 font-medium px-3 text-sm text-danger hover:bg-danger/10 rounded delete-confirm" data-name="Asset {{ $asset->name }}">
                                                                <i class="size-3" data-lucide="trash-2"></i>
                                                                Delete
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card-footer flex items-center justify-between">
                    <p class="text-default-500 text-sm">Showing <b>{{ $assets->firstItem() }}-{{ $assets->lastItem() }}</b> of <b>{{ $assets->total() }}</b> Results</p>
                    <div class="flex items-center gap-2">
                         {{ $assets->links('vendor.pagination.tailwind-custom') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Hidden Form for Bulk Print --}}
    <form id="bulk-print-form" action="{{ route('assets.bulk-print') }}" method="POST" target="_blank" class="hidden">
        @csrf
        <div id="bulk-ids-container"></div>
    </form>
@endsection

@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const selectAll = document.getElementById('select-all');
        const assetCheckboxes = document.querySelectorAll('.asset-checkbox');
        const btnBulkPrint = document.getElementById('btn-bulk-print');
        const bulkPrintForm = document.getElementById('bulk-print-form');
        const bulkIdsContainer = document.getElementById('bulk-ids-container');

        if (selectAll) {
            selectAll.addEventListener('change', function () {
                assetCheckboxes.forEach(checkbox => {
                    checkbox.checked = selectAll.checked;
                });
            });
        }

        if (btnBulkPrint) {
            btnBulkPrint.addEventListener('click', function () {
                const checked = document.querySelectorAll('.asset-checkbox:checked');
                
                if (checked.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Perhatian',
                        text: 'Pilih minimal satu aset untuk dicetak.',
                        confirmButtonColor: '#4f46e5',
                    });
                    return;
                }

                // Clear previous IDs
                bulkIdsContainer.innerHTML = '';

                // Append new IDs
                checked.forEach(checkbox => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'ids[]';
                    input.value = checkbox.value;
                    bulkIdsContainer.appendChild(input);
                });

                bulkPrintForm.submit();
            });
        }
    });
</script>
@endpush

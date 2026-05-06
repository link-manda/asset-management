@extends('layouts.app')

@section('title', 'Edit Maintenance: ' . $maintenance->item->item_code)

@section('content')
    @include('layouts.partials/page-title', [
        'subtitle' => 'Asset Management', 
        'title' => 'Edit Maintenance Record',
        'breadcrumbs' => [
            ['label' => 'Maintenance List', 'url' => route('maintenances.index')],
            ['label' => 'Edit Record', 'url' => null],
        ]
    ])

    <form action="{{ route('maintenances.update', $maintenance) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="grid lg:grid-cols-12 grid-cols-1 gap-6">
            <div class="lg:col-span-8 col-span-1">
                <div class="card">
                    <div class="card-body">
                        <div class="grid lg:grid-cols-2 grid-cols-1 gap-5 mb-5">
                            <div class="col-span-1">
                                <label class="inline-block mb-2 text-sm text-default-800 font-medium">Asset Unit</label>
                                <input class="form-input bg-default-100" type="text" value="{{ $maintenance->item->asset->name }} (#{{ $maintenance->item->item_code }})" readonly />
                            </div>
                            <div class="col-span-1">
                                <label class="inline-block mb-2 text-sm text-default-800 font-medium">Service Date</label>
                                <input class="form-input" name="maintenance_date" type="date" value="{{ old('maintenance_date', $maintenance->maintenance_date->format('Y-m-d')) }}" required />
                            </div>
                        </div>

                        <div class="grid lg:grid-cols-2 grid-cols-1 gap-5 mb-5">
                            <div class="col-span-1">
                                <label class="inline-block mb-2 text-sm text-default-800 font-medium">Maintenance Type</label>
                                <select class="form-input" name="type" required>
                                    <option value="Repair" {{ old('type', $maintenance->type) == 'Repair' ? 'selected' : '' }}>Repair</option>
                                    <option value="Routine" {{ old('type', $maintenance->type) == 'Routine' ? 'selected' : '' }}>Routine</option>
                                    <option value="Upgrade" {{ old('type', $maintenance->type) == 'Upgrade' ? 'selected' : '' }}>Upgrade</option>
                                </select>
                            </div>
                            <div class="col-span-1">
                                <label class="inline-block mb-2 text-sm text-default-800 font-medium">Actual Cost</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 start-0 flex items-center ps-3 text-default-500">Rp</span>
                                    <input type="number" name="cost" class="form-input ps-10" value="{{ old('cost', (int)$maintenance->cost) }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-5">
                            <label class="inline-block mb-2 text-sm text-default-800 font-medium">Service Description</label>
                            <textarea class="form-input" name="description" rows="4" required>{{ old('description', $maintenance->description) }}</textarea>
                        </div>

                        <div class="mb-5">
                            <label class="inline-block mb-2 text-sm text-default-800 font-medium">Current Status</label>
                            <select class="form-input" name="status" required>
                                <option value="Scheduled" {{ old('status', $maintenance->status) == 'Scheduled' ? 'selected' : '' }}>Scheduled</option>
                                <option value="In Progress" {{ old('status', $maintenance->status) == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="Completed" {{ old('status', $maintenance->status) == 'Completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                        </div>

                        <div class="flex justify-end gap-3">
                            <a href="{{ route('maintenances.index') }}" class="btn border-default-200 text-default-600 px-6">Cancel</a>
                            <button type="submit" class="btn bg-primary text-white px-10">Update Record</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

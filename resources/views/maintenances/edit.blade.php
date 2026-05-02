@extends('layouts.app')

@section('title', 'Update Maintenance')

@section('content')
    @include('layouts.partials/page-title', ['subtitle' => 'Maintenance', 'title' => 'Update Record'])

    <div class="grid lg:grid-cols-12 grid-cols-1 gap-6">
        <div class="lg:col-span-8 col-span-1">
            <div class="card">
                <div class="card-body">
                    <h6 class="mb-4 card-title text-base">Update Informasi Maintenance</h6>
                    
                    <form action="{{ route('maintenances.update', $maintenance) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-5">
                            <label class="inline-block mb-2 text-sm text-default-800 font-medium" for="asset_id">Asset</label>
                            <input type="text" class="form-input bg-default-100" value="{{ $maintenance->asset->name }} ({{ $maintenance->asset->asset_code }})" disabled>
                        </div>

                        <div class="grid lg:grid-cols-2 grid-cols-1 gap-5 mb-5">
                            <div class="col-span-1">
                                <label class="inline-block mb-2 text-sm text-default-800 font-medium" for="maintenance_date">Maintenance Date</label>
                                <input class="form-input" id="maintenance_date" name="maintenance_date" type="date" value="{{ old('maintenance_date', $maintenance->maintenance_date) }}" required />
                            </div>
                            <div class="col-span-1">
                                <label class="inline-block mb-2 text-sm text-default-800 font-medium" for="status">Maintenance Status</label>
                                <select class="form-input" id="status" name="status">
                                    <option value="Scheduled" {{ old('status', $maintenance->status) == 'Scheduled' ? 'selected' : '' }}>Scheduled</option>
                                    <option value="In Progress" {{ old('status', $maintenance->status) == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="Completed" {{ old('status', $maintenance->status) == 'Completed' ? 'selected' : '' }}>Completed</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-5">
                            <label class="inline-block mb-2 text-sm text-default-800 font-medium" for="cost">Actual Cost</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 start-0 flex items-center ps-3 text-default-500 font-semibold">Rp</span>
                                <input class="form-input ps-10" id="cost" name="cost" placeholder="0" type="number" value="{{ old('cost', $maintenance->cost) }}" required />
                            </div>
                        </div>

                        <div class="mb-5">
                            <label class="font-medium text-default-800 text-sm" for="description">Work Details / Results</label>
                            <textarea class="form-input" id="description" name="description" placeholder="Describe the maintenance results..." rows="5" required>{{ old('description', $maintenance->description) }}</textarea>
                        </div>

                        <div class="mt-6 flex gap-3 md:justify-end border-t border-default-200 pt-5">
                            <a href="{{ route('maintenances.index') }}" class="btn border-default-200 text-default-600 hover:bg-default-100">Cancel</a>
                            <button type="submit" class="text-white btn bg-primary px-10">Update Maintenance</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="lg:col-span-4 col-span-1">
            <div class="card">
                <div class="card-body">
                    <h6 class="mb-4 card-title text-base">Current Status</h6>
                    <div class="p-4 rounded-md border border-default-200 bg-default-50 mb-5">
                        <div class="flex justify-between items-center mb-3">
                            <span class="text-sm text-default-500">Asset Status:</span>
                            @php
                                $statusClasses = [
                                    'Available' => 'bg-success/15 text-success',
                                    'Deployed' => 'bg-primary/15 text-primary',
                                    'Maintenance' => 'bg-warning/15 text-warning',
                                    'Broken' => 'bg-danger/15 text-danger',
                                ];
                                $class = $statusClasses[$maintenance->asset->status] ?? 'bg-default-100 text-default-500';
                            @endphp
                            <span class="{{ $class }} px-2 py-0.5 rounded text-xs font-medium">{{ $maintenance->asset->status }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-default-500">Code:</span>
                            <span class="text-sm font-bold text-default-800">{{ $maintenance->asset->asset_code }}</span>
                        </div>
                    </div>

                    <div class="bg-info/5 border border-info/20 rounded-md p-4">
                        <p class="text-xs text-info leading-relaxed italic">
                            Tip: Jika status diubah menjadi <strong>Completed</strong>, sistem akan otomatis mengembalikan status aset ini menjadi <strong>Available</strong>.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

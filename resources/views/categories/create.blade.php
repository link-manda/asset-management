@extends('layouts.app')

@section('title', 'Add New Category')

@section('content')
    @include('layouts.partials/page-title', ['subtitle' => 'Master Data', 'title' => 'Create Category'])

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('categories.store') }}" method="POST">
                    @csrf
                    <div class="mb-5">
                        <label class="inline-block mb-2 text-sm text-default-800 font-medium" for="name">Category Name</label>
                        <input class="form-input" id="name" name="name" placeholder="e.g.: IT Equipment" type="text" value="{{ old('name') }}" required />
                    </div>

                    <div class="mb-5">
                        <label class="inline-block mb-2 text-sm text-default-800 font-medium" for="description">Description</label>
                        <textarea class="form-input" id="description" name="description" placeholder="Short description..." rows="3">{{ old('description') }}</textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-5">
                        <div class="col-span-1">
                            <label class="inline-block mb-2 text-sm text-default-800 font-medium">Default Life (Months)</label>
                            <input type="number" name="default_useful_life_months" class="form-input" placeholder="e.g.: 48" value="{{ old('default_useful_life_months') }}">
                        </div>
                        <div class="col-span-1">
                            <label class="inline-block mb-2 text-sm text-default-800 font-medium">Residual Value (%)</label>
                            <input type="number" name="default_residual_percentage" step="0.01" class="form-input" placeholder="e.g.: 10" value="{{ old('default_residual_percentage', 0) }}">
                        </div>
                    </div>

                    <div class="mb-8">
                        <label class="inline-block mb-2 text-sm text-default-800 font-medium">Fiscal Group (Tax)</label>
                        <select name="fiscal_group" class="form-input">
                            <option value="">-- Select Fiscal Group --</option>
                            @foreach(\App\Models\AssetItem::FISCAL_GROUPS as $group => $months)
                                <option value="{{ $group }}" {{ old('fiscal_group') == $group ? 'selected' : '' }}>{{ $group }} ({{ $months / 12 }} Years)</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex justify-end gap-3 pt-5 border-t border-default-200">
                        <a href="{{ route('categories.index') }}" class="btn border-default-200 text-default-600 px-6">Cancel</a>
                        <button type="submit" class="btn bg-primary text-white px-10 font-bold uppercase tracking-widest">Save Category</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

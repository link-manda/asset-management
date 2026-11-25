@extends('layouts.app')

@section('title', 'Edit Category: ' . $category->name)

@section('content')
    @include('layouts.partials/page-title', ['subtitle' => 'Categories', 'title' => 'Edit Category'])

    <div class="grid grid-cols-1 gap-6">
        <div class="card">
            <div class="card-header border-b border-default-200 bg-default-50/50">
                <h6 class="card-title">Edit Category Information</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('categories.update', $category) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 gap-5">
                        <div>
                            <label class="form-label text-sm font-medium text-default-700 mb-2 block" for="name">Category Name <span class="text-danger">*</span></label>
                            <input class="form-input w-full @error('name') border-danger @enderror" id="name" name="name" type="text" value="{{ old('name', $category->name) }}" required />
                            @error('name')
                                <p class="text-danger text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="form-label text-sm font-medium text-default-700 mb-2 block" for="description">Description</label>
                            <textarea class="form-input w-full @error('description') border-danger @enderror" id="description" name="description" rows="3">{{ old('description', $category->description) }}</textarea>
                            @error('description')
                                <p class="text-danger text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="form-label text-sm font-medium text-default-700 mb-2 block" for="default_useful_life_months">Default Useful Life (Months)</label>
                                <input class="form-input w-full @error('default_useful_life_months') border-danger @enderror" id="default_useful_life_months" name="default_useful_life_months" type="number" placeholder="e.g.: 48" value="{{ old('default_useful_life_months', $category->default_useful_life_months) }}" />
                                @error('default_useful_life_months')
                                    <p class="text-danger text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="form-label text-sm font-medium text-default-700 mb-2 block" for="default_residual_percentage">Default Residual Value (%)</label>
                                <div class="relative">
                                    <input class="form-input w-full @error('default_residual_percentage') border-danger @enderror" id="default_residual_percentage" name="default_residual_percentage" type="number" step="0.01" placeholder="e.g.: 10" value="{{ old('default_residual_percentage', $category->default_residual_percentage) }}" />
                                    <span class="absolute inset-y-0 end-0 flex items-center pe-3 text-default-500">%</span>
                                </div>
                                @error('default_residual_percentage')
                                    <p class="text-danger text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label class="form-label text-sm font-medium text-default-700 mb-2 block" for="fiscal_group">Fiscal Group (Tax)</label>
                            <select class="form-input w-full @error('fiscal_group') border-danger @enderror" id="fiscal_group" name="fiscal_group">
                                <option value="">-- Select Fiscal Group --</option>
                                @foreach(\App\Models\AssetItem::FISCAL_GROUPS as $group => $months)
                                    <option value="{{ $group }}" {{ old('fiscal_group', $category->fiscal_group) == $group ? 'selected' : '' }}>{{ $group }} ({{ $months / 12 }} Years)</option>
                                @endforeach
                            </select>
                            <p class="text-xs text-default-500 mt-1 italic">Used for automated tax depreciation calculations.</p>
                            @error('fiscal_group')
                                <p class="text-danger text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 mt-8 pt-5 border-t border-default-200">
                        <a href="{{ route('categories.index') }}" class="btn border-default-200 text-default-600 px-6">Cancel</a>
                        <button type="submit" class="btn bg-primary text-white px-10 font-bold uppercase tracking-wider text-xs">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

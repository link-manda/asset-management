@extends('layouts.app')

@section('title', 'Category Detail: ' . $category->name)

@section('content')
    @include('layouts.partials/page-title', ['subtitle' => 'Categories', 'title' => 'Category Details'])

    <div class="grid lg:grid-cols-4 grid-cols-1 gap-6">
        <div class="lg:col-span-1">
            <div class="card h-full">
                <div class="card-body">
                    <div class="text-center">
                        <div class="size-20 rounded-full bg-primary/10 text-primary flex items-center justify-center mx-auto mb-4">
                            <i class="size-10" data-lucide="tag"></i>
                        </div>
                        <h5 class="text-lg font-bold text-default-800 mb-1">{{ $category->name }}</h5>
                        <p class="text-default-500 text-sm mb-4">Total Assets: {{ $category->assets->count() }}</p>

                        <div class="flex flex-col gap-2">
                            @can('edit categories')
                            <a href="{{ route('categories.edit', $category) }}" class="btn btn-sm bg-primary text-white w-full uppercase font-bold text-xs tracking-wider">Edit Category</a>
                            @endcan
                            <a href="{{ route('categories.index') }}" class="btn btn-sm border-default-200 text-default-600 w-full">Back to List</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-3">
            <div class="card mb-6">
                <div class="card-header">
                    <h6 class="card-title">Category Description</h6>
                </div>
                <div class="card-body">
                    <p class="text-default-600 italic">
                        {{ $category->description ?: 'No description provided for this category.' }}
                    </p>
                </div>
            </div>

            <div class="card">
                <div class="card-header flex justify-between items-center">
                    <h6 class="card-title">Assets in this Category</h6>
                    @can('create assets')
                    <a href="{{ route('assets.create', ['category_id' => $category->id]) }}" class="btn btn-sm bg-primary text-white">Add Asset</a>
                    @endcan
                </div>
                <div class="card-body p-0">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-default-200">
                            <thead class="bg-default-100">
                                <tr class="text-xs font-semibold text-default-600 uppercase tracking-wider">
                                    <th class="px-6 py-3 text-start">Asset Code</th>
                                    <th class="px-6 py-3 text-start">Asset Name</th>
                                    <th class="px-6 py-3 text-start">Current Status</th>
                                    <th class="px-6 py-3 text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-default-200">
                                @forelse($category->assets as $asset)
                                    <tr class="text-sm hover:bg-default-50 transition-all">
                                        <td class="px-6 py-4 font-bold text-primary font-mono">#{{ $asset->asset_code }}</td>
                                        <td class="px-6 py-4 font-medium text-default-800">
                                            <a href="{{ route('assets.show', $asset) }}" class="hover:text-primary transition-all">{{ $asset->name }}</a>
                                        </td>
                                        <td class="px-6 py-4">
                                            @php
                                                $statusClasses = [
                                                    'Available' => 'bg-success/15 text-success',
                                                    'Deployed' => 'bg-primary/15 text-primary',
                                                    'Maintenance' => 'bg-warning/15 text-warning',
                                                    'Broken' => 'bg-danger/15 text-danger',
                                                ];
                                            @endphp
                                            <span class="inline-flex items-center py-0.5 px-2 rounded text-[10px] font-bold uppercase {{ $statusClasses[$asset->status] ?? 'bg-default-100 text-default-500' }}">
                                                {{ $asset->status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-end">
                                            <a href="{{ route('assets.show', $asset) }}" class="text-primary hover:text-primary-600 font-bold text-xs">VIEW</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-10 text-center text-default-400 italic">No assets registered in this category.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

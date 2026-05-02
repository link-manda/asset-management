@extends('layouts.app')

@section('title', 'Detail Kategori: ' . $category->name)

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
                        <p class="text-default-500 text-sm mb-4">Total Asset: {{ $category->assets->count() }}</p>
                        
                        <div class="flex flex-col gap-2">
                            <a href="{{ route('categories.edit', $category) }}" class="btn btn-sm bg-primary text-white w-full">Edit Kategori</a>
                            <a href="{{ route('categories.index') }}" class="btn btn-sm border-default-200 text-default-600 w-full">Kembali</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-3">
            <div class="card mb-6">
                <div class="card-header">
                    <h6 class="card-title">Deskripsi Kategori</h6>
                </div>
                <div class="card-body">
                    <p class="text-default-600 italic">
                        {{ $category->description ?: 'Tidak ada deskripsi untuk kategori ini.' }}
                    </p>
                </div>
            </div>

            <div class="card">
                <div class="card-header flex justify-between items-center">
                    <h6 class="card-title">Daftar Asset dalam Kategori Ini</h6>
                    <a href="{{ route('assets.create', ['category_id' => $category->id]) }}" class="btn btn-sm bg-primary text-white">Tambah Asset</a>
                </div>
                <div class="card-body p-0">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-default-200">
                            <thead class="bg-default-100">
                                <tr class="text-xs font-semibold text-default-600 uppercase">
                                    <th class="px-6 py-3 text-start">Kode</th>
                                    <th class="px-6 py-3 text-start">Nama Asset</th>
                                    <th class="px-6 py-3 text-start">Status</th>
                                    <th class="px-6 py-3 text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-default-200">
                                @forelse($category->assets as $asset)
                                    <tr class="text-sm">
                                        <td class="px-6 py-4 font-bold text-primary">#{{ $asset->asset_code }}</td>
                                        <td class="px-6 py-4 font-medium text-default-800">{{ $asset->name }}</td>
                                        <td class="px-6 py-4">
                                            @php
                                                $statusClasses = [
                                                    'Available' => 'bg-success/15 text-success',
                                                    'Deployed' => 'bg-primary/15 text-primary',
                                                    'Maintenance' => 'bg-warning/15 text-warning',
                                                    'Broken' => 'bg-danger/15 text-danger',
                                                ];
                                            @endphp
                                            <span class="inline-flex items-center py-0.5 px-2 rounded text-xs font-medium {{ $statusClasses[$asset->status] ?? 'bg-default-100 text-default-500' }}">
                                                {{ $asset->status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-end">
                                            <a href="{{ route('assets.show', $asset) }}" class="text-primary hover:text-primary-600">Lihat</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-10 text-center text-default-400 italic">Belum ada asset di kategori ini.</td>
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

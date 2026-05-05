@extends('layouts.vertical', ['title' => 'Laporan Aset Umum'])

@section('css')
@endsection

@section('content')
    @include('layouts.partials/page-title', ['subtitle' => 'Laporan', 'title' => 'Laporan Aset Umum'])



    <!-- Data Table Section -->
    <div class="card">
        <div class="card-header border-b border-default-200 flex flex-wrap items-center justify-between gap-4">
            <h6 class="card-title text-base">Detail Item Aset</h6>
            <div class="flex gap-2">
                <a href="{{ route('reports.general.excel', request()->all()) }}" class="btn btn-sm bg-success/10 text-success border border-success/20 hover:bg-success hover:text-white transition-all">
                    <i class="size-4 me-1" data-lucide="download"></i> Excel
                </a>
                <a href="{{ route('reports.general.csv', request()->all()) }}" class="btn btn-sm bg-info/10 text-info border border-info/20 hover:bg-info hover:text-white transition-all">
                    <i class="size-4 me-1" data-lucide="file-text"></i> CSV
                </a>
                <button onclick="window.print()" class="btn btn-sm bg-primary/10 text-primary border border-primary/20 hover:bg-primary hover:text-white transition-all">
                    <i class="size-4 me-1" data-lucide="printer"></i> Cetak PDF
                </button>
            </div>
        </div>
        
        <!-- Filters -->
        <div class="p-4 border-b border-default-200 bg-default-50/50">
            <form action="{{ route('reports.general') }}" method="GET" class="grid lg:grid-cols-4 md:grid-cols-2 grid-cols-1 gap-4">
                <div>
                    <label class="text-xs font-black text-default-600 uppercase mb-1 block">Kategori</label>
                    <select name="category_id" class="form-input form-input-sm w-full">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-xs font-black text-default-600 uppercase mb-1 block">Lokasi</label>
                    <select name="location_id" class="form-input form-input-sm w-full">
                        <option value="">Semua Lokasi</option>
                        @foreach($locations as $location)
                            <option value="{{ $location->id }}" {{ request('location_id') == $location->id ? 'selected' : '' }}>
                                {{ $location->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-xs font-black text-default-600 uppercase mb-1 block">Status</label>
                    <select name="status" class="form-input form-input-sm w-full">
                        <option value="">Semua Status</option>
                        <option value="Available" {{ request('status') == 'Available' ? 'selected' : '' }}>Available</option>
                        <option value="Deployed" {{ request('status') == 'Deployed' ? 'selected' : '' }}>Deployed</option>
                        <option value="Maintenance" {{ request('status') == 'Maintenance' ? 'selected' : '' }}>Maintenance</option>
                        <option value="Disposed" {{ request('status') == 'Disposed' ? 'selected' : '' }}>Disposed</option>
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <div class="relative flex-grow">
                        <input type="text" name="search" value="{{ request('search') }}" class="form-input form-input-sm ps-8 w-full" placeholder="Cari aset...">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-2.5">
                            <i class="size-3.5 text-default-500" data-lucide="search"></i>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-sm bg-primary text-white px-4">Filter</button>
                    <a href="{{ route('reports.general') }}" class="btn btn-sm bg-default-200 text-default-700">Reset</a>
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-default-200">
                <thead class="bg-default-100">
                    <tr>
                        <th class="px-4 py-3 text-start text-xs font-bold text-default-600 uppercase">Item Code</th>
                        <th class="px-4 py-3 text-start text-xs font-bold text-default-600 uppercase">Aset</th>
                        <th class="px-4 py-3 text-start text-xs font-bold text-default-600 uppercase">Kategori</th>
                        <th class="px-4 py-3 text-start text-xs font-bold text-default-600 uppercase">Lokasi</th>
                        <th class="px-4 py-3 text-start text-xs font-bold text-default-600 uppercase text-center">Status</th>
                        <th class="px-4 py-3 text-end text-xs font-bold text-default-600 uppercase">Nilai Perolehan</th>
                        <th class="px-4 py-3 text-end text-xs font-bold text-default-600 uppercase">Nilai Buku</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-default-200">
                    @forelse($items as $item)
                        <tr class="hover:bg-default-50 transition-all">
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-mono text-primary">{{ $item->item_code }}</td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="text-sm font-bold text-default-800">{{ $item->asset->name }}</div>
                                <div class="text-xs text-default-500">{{ $item->serial_number }}</div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-default-600">{{ $item->asset->category->name ?? '-' }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-default-600">{{ $item->location->name ?? '-' }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-center">
                                @php
                                    $statusColor = match($item->status) {
                                        'Available' => 'bg-success/15 text-success',
                                        'Deployed' => 'bg-info/15 text-info',
                                        'Maintenance' => 'bg-warning/15 text-warning',
                                        'Disposed' => 'bg-danger/15 text-danger',
                                        default => 'bg-default-100 text-default-600'
                                    };
                                @endphp
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $statusColor }}">
                                    {{ $item->status }}
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-end font-medium">Rp {{ number_format($item->purchase_price, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-end font-bold text-primary">Rp {{ number_format($item->current_value, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-default-500 italic">Data aset tidak ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer border-t border-default-200">
            {{ $items->links() }}
        </div>
    </div>
@endsection



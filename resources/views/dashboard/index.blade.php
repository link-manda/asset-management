@extends('layouts.app')

@section('title', 'Dashboard Statistik')

@section('content')
    @include('layouts.partials/page-title', ['subtitle' => 'Menu', 'title' => 'Dashboard Overview'])

    <div class="grid lg:grid-cols-4 md:grid-cols-2 grid-cols-1 gap-5 mb-5">
        {{-- Total Assets --}}
        <div class="card">
            <div class="card-body">
                <div class="flex items-center gap-4">
                    <div class="btn bg-primary/10 text-primary size-12">
                        <i class="size-6" data-lucide="package"></i>
                    </div>
                    <div class="grow">
                        <h6 class="mb-1 text-default-800 font-semibold text-xs uppercase tracking-wider">Total Unit Fisik</h6>
                        <p class="text-default-800 text-xl font-bold">{{ number_format($totalAssets) }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Total Initial Value --}}
        <div class="card">
            <div class="card-body">
                <div class="flex items-center gap-4">
                    <div class="btn bg-success/10 text-success size-12">
                        <i class="size-6" data-lucide="circle-dollar-sign"></i>
                    </div>
                    <div class="grow">
                        <h6 class="mb-1 text-default-800 font-semibold text-xs uppercase tracking-wider">Nilai Perolehan</h6>
                        <p class="text-default-800 text-xl font-bold">Rp {{ number_format($totalValue, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Total Book Value --}}
        <div class="card border-primary/20 border-2 shadow-primary/5">
            <div class="card-body">
                <div class="flex items-center gap-4">
                    <div class="btn bg-primary/20 text-primary size-12">
                        <i class="size-6" data-lucide="trending-down"></i>
                    </div>
                    <div class="grow">
                        <h6 class="mb-1 text-primary font-bold text-xs uppercase tracking-wider">Nilai Buku Saat Ini</h6>
                        <p class="text-primary text-xl font-bold">Rp {{ number_format($totalBookValue, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Active Deployments --}}
        <div class="card">
            <div class="card-body">
                <div class="flex items-center gap-4">
                    <div class="btn bg-info/10 text-info size-12">
                        <i class="size-6" data-lucide="user-check"></i>
                    </div>
                    <div class="grow">
                        <h6 class="mb-1 text-default-800 font-semibold text-xs uppercase tracking-wider">Unit Deployed</h6>
                        <p class="text-default-800 text-xl font-bold">{{ number_format($stats['Deployed']) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid lg:grid-cols-2 grid-cols-1 gap-5 mb-5">
        {{-- Chart 1: Tren Nilai Aset --}}
        <div class="card">
            <div class="card-header">
                <h6 class="card-title">Tren Akuisisi Aset (Nilai)</h6>
            </div>
            <div class="card-body">
                <canvas id="trendChart" height="300"></canvas>
            </div>
        </div>

        {{-- Chart 2: Distribusi Per Kategori --}}
        <div class="card">
            <div class="card-header">
                <h6 class="card-title">Proporsi Nilai Aset Per Kategori</h6>
            </div>
            <div class="card-body flex justify-center">
                <div class="size-80">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="grid lg:grid-cols-2 grid-cols-1 gap-5">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title">Status Aset Aktif</h6>
            </div>
            <div class="card-body">
                <div class="space-y-4">
                    @foreach($stats as $status => $count)
                        @php
                            $percentage = $totalAssets > 0 ? ($count / $totalAssets) * 100 : 0;
                            $barColors = [
                                'Available'   => 'bg-success',
                                'Deployed'    => 'bg-primary',
                                'Maintenance' => 'bg-warning',
                                'Broken'      => 'bg-danger',
                                'Lost'        => 'bg-default-500',
                                'Disposed'    => 'bg-danger/50',
                            ];
                            $currentColor = $barColors[$status] ?? 'bg-default-400';
                        @endphp
                        <div>
                            <div class="flex justify-between mb-1">
                                <span class="text-sm font-medium text-default-700">{{ $status }}</span>
                                <span class="text-sm font-medium text-default-700">{{ $count }} Unit</span>
                            </div>
                            <div class="w-full bg-default-100 rounded-full h-1.5">
                                <div class="{{ $currentColor }} h-1.5 rounded-full" style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h6 class="card-title">Akses Cepat</h6>
            </div>
            <div class="card-body">
                <div class="grid grid-cols-2 gap-4">
                    <a href="{{ route('assets.create') }}" class="flex flex-col items-center justify-center p-6 border border-dashed border-default-200 rounded-md hover:bg-default-50 transition-all group">
                        <i class="size-10 text-primary mb-2 group-hover:scale-110 transition-transform" data-lucide="plus-square"></i>
                        <span class="text-sm font-semibold text-default-800">Tambah Asset</span>
                    </a>
                    <a href="{{ route('assets.index') }}" class="flex flex-col items-center justify-center p-6 border border-dashed border-default-200 rounded-md hover:bg-default-50 transition-all group">
                        <i class="size-10 text-secondary mb-2 group-hover:scale-110 transition-transform" data-lucide="layout-list"></i>
                        <span class="text-sm font-semibold text-default-800">Daftar Asset</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Line Chart: Trend
        const trendCtx = document.getElementById('trendChart').getContext('2d');
        new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: @json($trendLabels),
                datasets: [{
                    label: 'Nilai Akuisisi (Rp)',
                    data: @json($trendValues),
                    borderColor: '#3e60d5',
                    backgroundColor: 'rgba(62, 96, 213, 0.1)',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointBackgroundColor: '#3e60d5'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString();
                            }
                        }
                    }
                },
                plugins: {
                    legend: { display: false }
                }
            }
        });

        // Doughnut Chart: Category
        const catCtx = document.getElementById('categoryChart').getContext('2d');
        new Chart(catCtx, {
            type: 'doughnut',
            data: {
                labels: @json($categoryLabels),
                datasets: [{
                    data: @json($categoryValues),
                    backgroundColor: [
                        '#3e60d5', '#47ad5d', '#ffc107', '#fa5c7c', '#39afd1', '#6c757d'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { padding: 20 }
                    }
                },
                cutout: '70%'
            }
        });
    });
</script>
@endpush

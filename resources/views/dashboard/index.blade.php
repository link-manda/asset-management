@extends('layouts.app')

@section('title', 'Dashboard Overview')

@section('content')
    @include('layouts.partials/page-title', ['subtitle' => 'Menu', 'title' => 'Executive Dashboard'])

    <div class="grid lg:grid-cols-4 md:grid-cols-2 grid-cols-1 gap-6 mb-6">
        {{-- Total Assets --}}
        <div class="card overflow-hidden">
            <div class="card-body">
                <div class="flex items-center gap-4">
                    <div class="size-12 bg-primary/10 text-primary rounded-xl flex items-center justify-center shrink-0">
                        <i class="size-6" data-lucide="package"></i>
                    </div>
                    <div class="grow">
                        <h6 class="text-[10px] text-default-500 font-bold uppercase tracking-widest mb-1">Total Unit Fisik</h6>
                        <div class="flex items-baseline gap-1">
                            <h3 class="text-2xl font-black text-default-900">{{ number_format($totalAssets) }}</h3>
                            <span class="text-[10px] text-default-400 font-medium">Unit</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="h-1 bg-primary/20 w-full"></div>
        </div>

        {{-- Total Initial Value --}}
        <div class="card overflow-hidden">
            <div class="card-body">
                <div class="flex items-center gap-4">
                    <div class="size-12 bg-success/10 text-success rounded-xl flex items-center justify-center shrink-0">
                        <i class="size-6" data-lucide="circle-dollar-sign"></i>
                    </div>
                    <div class="grow">
                        <h6 class="text-[10px] text-default-500 font-bold uppercase tracking-widest mb-1">Nilai Perolehan</h6>
                        <h3 class="text-xl font-black text-default-900">Rp {{ number_format($totalValue, 0, ',', '.') }}</h3>
                    </div>
                </div>
            </div>
            <div class="h-1 bg-success/20 w-full"></div>
        </div>

        {{-- Total Book Value --}}
        <div class="card overflow-hidden border-primary/30 border shadow-lg shadow-primary/5 bg-primary/5">
            <div class="card-body">
                <div class="flex items-center gap-4">
                    <div class="size-12 bg-primary text-white rounded-xl flex items-center justify-center shrink-0 shadow-lg shadow-primary/30">
                        <i class="size-6" data-lucide="trending-down"></i>
                    </div>
                    <div class="grow">
                        <h6 class="text-[10px] text-primary font-black uppercase tracking-widest mb-1">Nilai Buku (Est)</h6>
                        <h3 class="text-xl font-black text-primary">Rp {{ number_format($totalBookValue, 0, ',', '.') }}</h3>
                    </div>
                </div>
            </div>
            <div class="h-1 bg-primary w-full"></div>
        </div>

        {{-- Active Deployments --}}
        <div class="card overflow-hidden">
            <div class="card-body">
                <div class="flex items-center gap-4">
                    <div class="size-12 bg-info/10 text-info rounded-xl flex items-center justify-center shrink-0">
                        <i class="size-6" data-lucide="user-check"></i>
                    </div>
                    <div class="grow">
                        <h6 class="text-[10px] text-default-500 font-bold uppercase tracking-widest mb-1">Distribusi Aktif</h6>
                        <div class="flex items-baseline gap-1">
                            <h3 class="text-2xl font-black text-default-900">{{ number_format($stats['Deployed']) }}</h3>
                            <span class="text-[10px] text-default-400 font-medium">In Use</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="h-1 bg-info/20 w-full"></div>
        </div>
    </div>

    <div class="grid lg:grid-cols-3 grid-cols-1 gap-6 mb-6">
        {{-- Chart 1: Tren Nilai Aset --}}
        <div class="lg:col-span-2 card">
            <div class="card-header border-b border-default-200 flex justify-between items-center bg-default-50/50">
                <h6 class="card-title text-sm">Tren Investasi Aset (6 Bulan Terakhir)</h6>
                <span class="text-[10px] bg-primary/10 text-primary px-2 py-0.5 rounded-full font-bold uppercase">Financial Trend</span>
            </div>
            <div class="card-body">
                <div class="h-[320px]">
                    <canvas id="trendChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Chart 2: Distribusi Per Kategori --}}
        <div class="card">
            <div class="card-header border-b border-default-200 bg-default-50/50">
                <h6 class="card-title text-sm">Proporsi Nilai Per Kategori</h6>
            </div>
            <div class="card-body">
                <div class="h-[280px] w-full relative flex items-center justify-center">
                    <canvas id="categoryChart"></canvas>
                    <div class="absolute flex flex-col items-center justify-center pointer-events-none">
                        <span class="text-[10px] text-default-400 uppercase font-bold tracking-tighter">Total Value</span>
                        <span class="text-sm font-black text-default-800">Rp {{ number_format($totalValue/1000000, 1) }}M</span>
                    </div>
                </div>
                <div id="category-legend" class="mt-4 space-y-1"></div>
            </div>
        </div>
    </div>

    <div class="grid lg:grid-cols-5 grid-cols-1 gap-6">
        {{-- Status Breakdown (3/5) --}}
        <div class="lg:col-span-3 card">
            <div class="card-header border-b border-default-200 flex justify-between items-center">
                <h6 class="card-title text-sm">Status Kondisi Aset</h6>
                <i class="size-4 text-default-400" data-lucide="activity"></i>
            </div>
            <div class="card-body">
                <div class="grid md:grid-cols-2 gap-x-8 gap-y-6">
                    @foreach($stats as $status => $count)
                        @php
                            $percentage = $totalAssets > 0 ? ($count / $totalAssets) * 100 : 0;
                            $barColors = [
                                'Available'   => 'bg-success',
                                'Deployed'    => 'bg-primary',
                                'Maintenance' => 'bg-warning',
                                'Broken'      => 'bg-danger',
                                'Lost'        => 'bg-default-900',
                                'Disposed'    => 'bg-default-400',
                            ];
                            $currentColor = $barColors[$status] ?? 'bg-default-400';
                        @endphp
                        <div>
                            <div class="flex justify-between mb-1.5">
                                <div class="flex items-center gap-2">
                                    <span class="size-2 rounded-full {{ $currentColor }}"></span>
                                    <span class="text-xs font-bold text-default-700">{{ $status }}</span>
                                </div>
                                <span class="text-xs font-black text-default-900">{{ number_format($percentage, 1) }}% <span class="text-[10px] text-default-400 font-normal">({{ $count }})</span></span>
                            </div>
                            <div class="w-full bg-default-100 rounded-full h-1.5 overflow-hidden">
                                <div class="{{ $currentColor }} h-1.5 rounded-full transition-all duration-1000" style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Quick Actions & Activity (2/5) --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="card bg-default-900 text-white overflow-hidden relative">
                <div class="card-body z-10 relative">
                    <h6 class="text-xs font-bold uppercase tracking-widest text-primary mb-4">Quick Management</h6>
                    <div class="grid grid-cols-2 gap-3">
                        <a href="{{ route('assets.create') }}" class="flex flex-col items-center justify-center p-4 bg-white/10 hover:bg-white/20 border border-white/10 rounded-xl transition-all group">
                            <i class="size-6 text-primary mb-2 group-hover:scale-110 transition-transform" data-lucide="plus-circle"></i>
                            <span class="text-[11px] font-bold">New Asset</span>
                        </a>
                        <a href="{{ route('inventory.index') }}" class="flex flex-col items-center justify-center p-4 bg-white/10 hover:bg-white/20 border border-white/10 rounded-xl transition-all group">
                            <i class="size-6 text-success mb-2 group-hover:scale-110 transition-transform" data-lucide="scan-barcode"></i>
                            <span class="text-[11px] font-bold">Inventory List</span>
                        </a>
                        <a href="{{ route('assignments.index') }}" class="flex flex-col items-center justify-center p-4 bg-white/10 hover:bg-white/20 border border-white/10 rounded-xl transition-all group">
                            <i class="size-6 text-info mb-2 group-hover:scale-110 transition-transform" data-lucide="users"></i>
                            <span class="text-[11px] font-bold">Assignments</span>
                        </a>
                        <a href="{{ route('reports.depreciation') }}" class="flex flex-col items-center justify-center p-4 bg-white/10 hover:bg-white/20 border border-white/10 rounded-xl transition-all group">
                            <i class="size-6 text-warning mb-2 group-hover:scale-110 transition-transform" data-lucide="bar-chart-3"></i>
                            <span class="text-[11px] font-bold">Reports</span>
                        </a>
                    </div>
                </div>
                <div class="absolute -bottom-10 -right-10 size-40 bg-primary/20 rounded-full blur-3xl"></div>
            </div>

            <div class="card">
                <div class="card-body p-4">
                    <div class="flex items-center gap-3">
                        <div class="size-10 bg-warning/10 text-warning rounded-lg flex items-center justify-center">
                            <i class="size-5" data-lucide="alert-triangle"></i>
                        </div>
                        <div>
                            <h6 class="text-xs font-bold text-default-800">System Note</h6>
                            <p class="text-[10px] text-default-500 leading-tight">Data penyusutan di-update secara real-time setiap kali halaman detail aset dibuka.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        Chart.defaults.color = '#8492a6';
        Chart.defaults.font.family = "'Inter', sans-serif";

        // Line Chart: Trend
        const trendCtx = document.getElementById('trendChart').getContext('2d');
        const gradient = trendCtx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(62, 96, 213, 0.2)');
        gradient.addColorStop(1, 'rgba(62, 96, 213, 0)');

        new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: @json($trendLabels),
                datasets: [{
                    label: 'Investasi',
                    data: @json($trendValues),
                    borderColor: '#3e60d5',
                    backgroundColor: gradient,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#3e60d5',
                    pointBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        callbacks: {
                            label: function(context) {
                                return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        grid: { display: true, drawBorder: false, color: 'rgba(0,0,0,0.03)' },
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + (value/1000000).toFixed(0) + 'jt';
                            }
                        }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });

        // Doughnut Chart: Category
        const catLabels = @json($categoryLabels);
        const catValues = @json($categoryValues);
        const totalCatValue = catValues.reduce((a, b) => parseFloat(a) + parseFloat(b), 0);
        
        const catCtx = document.getElementById('categoryChart').getContext('2d');
        const catChart = new Chart(catCtx, {
            type: 'doughnut',
            data: {
                labels: catLabels,
                datasets: [{
                    data: catValues,
                    backgroundColor: ['#3e60d5', '#47ad5d', '#ffc107', '#fa5c7c', '#39afd1'],
                    hoverOffset: 10,
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const val = parseFloat(context.parsed);
                                const pct = ((val / totalCatValue) * 100).toFixed(1);
                                return ` ${context.label}: Rp ${val.toLocaleString('id-ID')} (${pct}%)`;
                            }
                        }
                    }
                },
                cutout: '80%'
            }
        });

        // Custom Legend for Category Chart
        const legendContainer = document.getElementById('category-legend');
        const colors = catChart.data.datasets[0].backgroundColor;
        
        catLabels.forEach((label, i) => {
            const val = parseFloat(catValues[i]);
            const pct = totalCatValue > 0 ? ((val / totalCatValue) * 100).toFixed(1) : 0;
            
            const div = document.createElement('div');
            div.className = 'flex items-center justify-between text-[11px]';
            div.innerHTML = `
                <div class="flex items-center gap-2">
                    <span class="size-2 rounded-full" style="background-color: ${colors[i]}"></span>
                    <span class="text-default-600 font-medium">${label}</span>
                </div>
                <span class="font-bold text-default-800">${pct}%</span>
            `;
            legendContainer.appendChild(div);
        });
    });
</script>
@endpush

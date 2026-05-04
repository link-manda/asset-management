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

    <div class="grid lg:grid-cols-2 grid-cols-1 gap-6 mb-6">
        {{-- Chart 1: Tren Investasi Aset (Line) --}}
        <div class="card">
            <div class="card-header border-b border-default-200 flex justify-between items-center bg-default-50/50">
                <h6 class="card-title text-sm font-bold">Tren Investasi Aset (6 Bulan Terakhir)</h6>
                <span class="text-[10px] bg-primary/10 text-primary px-2 py-0.5 rounded-full font-bold uppercase">Asset Growth</span>
            </div>
            <div class="card-body">
                <div class="h-[280px]">
                    <canvas id="trendChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Chart 2: Biaya Maintenance (Bar) --}}
        <div class="card">
            <div class="card-header border-b border-default-200 flex justify-between items-center bg-default-50/50">
                <h6 class="card-title text-sm font-bold">Analisis Biaya Pemeliharaan</h6>
                <span class="text-[10px] bg-warning/10 text-warning px-2 py-0.5 rounded-full font-bold uppercase">Operational Cost</span>
            </div>
            <div class="card-body">
                <div class="h-[280px]">
                    <canvas id="maintenanceChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="grid lg:grid-cols-3 md:grid-cols-2 grid-cols-1 gap-6 mb-6">
        {{-- Chart 3: Proporsi Kategori (Donut) --}}
        <div class="card">
            <div class="card-header border-b border-default-200 bg-default-50/50">
                <h6 class="card-title text-sm font-bold">Nilai Per Kategori</h6>
            </div>
            <div class="card-body">
                <div class="h-[220px] w-full relative flex items-center justify-center">
                    <canvas id="categoryChart"></canvas>
                    <div class="absolute flex flex-col items-center justify-center pointer-events-none">
                        <span class="text-[9px] text-default-400 uppercase font-black tracking-tighter">Total</span>
                        <span class="text-xs font-black text-default-800">Rp {{ number_format($totalValue/1000000, 1) }}M</span>
                    </div>
                </div>
                <div id="category-legend" class="mt-4 grid grid-cols-2 gap-2"></div>
            </div>
        </div>

        {{-- Chart 4: Proporsi Status (Donut) --}}
        <div class="card">
            <div class="card-header border-b border-default-200 bg-default-50/50">
                <h6 class="card-title text-sm font-bold">Distribusi Status Unit</h6>
            </div>
            <div class="card-body">
                <div class="h-[220px] w-full relative flex items-center justify-center">
                    <canvas id="statusChart"></canvas>
                    <div class="absolute flex flex-col items-center justify-center pointer-events-none">
                        <span class="text-[9px] text-default-400 uppercase font-black tracking-tighter">Units</span>
                        <span class="text-sm font-black text-default-800">{{ number_format($totalAssets) }}</span>
                    </div>
                </div>
                <div id="status-legend" class="mt-4 grid grid-cols-2 gap-2"></div>
            </div>
        </div>

        {{-- Quick Actions & Alerts --}}
        <div class="flex flex-col gap-6">
            <div class="card bg-default-900 text-white overflow-hidden relative grow">
                <div class="card-body z-10 relative">
                    <h6 class="text-xs font-bold uppercase tracking-widest text-primary mb-4 italic">Command Center</h6>
                    <div class="grid grid-cols-2 gap-2">
                        <a href="{{ route('assets.create') }}" class="flex flex-col items-center justify-center p-3 bg-white/5 hover:bg-white/10 border border-white/10 rounded-lg transition-all group">
                            <i class="size-5 text-primary mb-1 group-hover:scale-110 transition-transform" data-lucide="plus-circle"></i>
                            <span class="text-[10px] font-bold">New Asset</span>
                        </a>
                        <a href="{{ route('inventory.index') }}" class="flex flex-col items-center justify-center p-3 bg-white/5 hover:bg-white/10 border border-white/10 rounded-lg transition-all group">
                            <i class="size-5 text-success mb-1 group-hover:scale-110 transition-transform" data-lucide="scan-barcode"></i>
                            <span class="text-[10px] font-bold">Inventory</span>
                        </a>
                        <a href="{{ route('assignments.index') }}" class="flex flex-col items-center justify-center p-3 bg-white/5 hover:bg-white/10 border border-white/10 rounded-lg transition-all group">
                            <i class="size-5 text-info mb-1 group-hover:scale-110 transition-transform" data-lucide="users"></i>
                            <span class="text-[10px] font-bold">Assignments</span>
                        </a>
                        <a href="{{ route('reports.depreciation') }}" class="flex flex-col items-center justify-center p-3 bg-white/5 hover:bg-white/10 border border-white/10 rounded-lg transition-all group">
                            <i class="size-5 text-warning mb-1 group-hover:scale-110 transition-transform" data-lucide="bar-chart-3"></i>
                            <span class="text-[10px] font-bold">Reports</span>
                        </a>
                    </div>
                </div>
                <div class="absolute -bottom-10 -right-10 size-32 bg-primary/20 rounded-full blur-2xl"></div>
            </div>

            <div class="card border-warning/30 border bg-warning/5">
                <div class="card-body p-4">
                    <div class="flex items-center gap-3">
                        <div class="size-10 bg-warning/20 text-warning rounded-lg flex items-center justify-center shrink-0">
                            <i class="size-5" data-lucide="shield-alert"></i>
                        </div>
                        <div>
                            <h6 class="text-[11px] font-black text-default-800 uppercase">Audit Ready</h6>
                            <p class="text-[10px] text-default-600 leading-tight">Seluruh log aktivitas aset kini tercatat dalam Audit Trail sistem.</p>
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

        // Helper for Currency
        const formatCurrency = (val) => 'Rp ' + val.toLocaleString('id-ID');

        // Line Chart: Trend Investasi
        const trendCtx = document.getElementById('trendChart').getContext('2d');
        const trendGradient = trendCtx.createLinearGradient(0, 0, 0, 300);
        trendGradient.addColorStop(0, 'rgba(62, 96, 213, 0.2)');
        trendGradient.addColorStop(1, 'rgba(62, 96, 213, 0)');

        new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: @json($trendLabels),
                datasets: [{
                    label: 'Investasi',
                    data: @json($trendValues),
                    borderColor: '#3e60d5',
                    backgroundColor: trendGradient,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointHoverRadius: 6,
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
                        callbacks: { label: (c) => formatCurrency(c.parsed.y) }
                    }
                },
                scales: {
                    y: { grid: { color: 'rgba(0,0,0,0.03)' }, ticks: { callback: (v) => (v/1000000).toFixed(0) + 'jt' } },
                    x: { grid: { display: false } }
                }
            }
        });

        // Bar Chart: Maintenance Cost
        const maintCtx = document.getElementById('maintenanceChart').getContext('2d');
        new Chart(maintCtx, {
            type: 'bar',
            data: {
                labels: @json($maintLabels),
                datasets: [{
                    label: 'Biaya Servis',
                    data: @json($maintValues),
                    backgroundColor: '#ffbc00',
                    borderRadius: 6,
                    barThickness: 20
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: { callbacks: { label: (c) => formatCurrency(c.parsed.y) } }
                },
                scales: {
                    y: { grid: { color: 'rgba(0,0,0,0.03)' }, ticks: { callback: (v) => (v/1000).toFixed(0) + 'rb' } },
                    x: { grid: { display: false } }
                }
            }
        });

        // Shared Donut Config
        const donutOptions = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            cutout: '80%'
        };

        // Donut 1: Category
        const catLabels = @json($categoryLabels);
        const catValues = @json($categoryValues);
        const catCtx = document.getElementById('categoryChart').getContext('2d');
        const catChart = new Chart(catCtx, {
            type: 'doughnut',
            data: {
                labels: catLabels,
                datasets: [{
                    data: catValues,
                    backgroundColor: ['#3e60d5', '#47ad5d', '#ffbc00', '#fa5c7c', '#39afd1', '#6c757d'],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: donutOptions
        });

        // Donut 2: Status
        const statLabels = @json($statusLabels);
        const statValues = @json($statusValues);
        const statCtx = document.getElementById('statusChart').getContext('2d');
        const statChart = new Chart(statCtx, {
            type: 'doughnut',
            data: {
                labels: statLabels,
                datasets: [{
                    data: statValues,
                    backgroundColor: ['#47ad5d', '#3e60d5', '#ffbc00', '#fa5c7c', '#000000', '#98a6ad'],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: donutOptions
        });

        // Build Custom Legends
        const buildLegend = (chart, containerId, values, isCurrency = false) => {
            const container = document.getElementById(containerId);
            const labels = chart.data.labels;
            const colors = chart.data.datasets[0].backgroundColor;
            const total = values.reduce((a, b) => parseFloat(a) + parseFloat(b), 0);

            labels.forEach((label, i) => {
                const val = parseFloat(values[i]);
                if (val === 0 && !isCurrency) return; // Skip zero units
                
                const pct = total > 0 ? ((val / total) * 100).toFixed(0) : 0;
                const div = document.createElement('div');
                div.className = 'flex items-center justify-between text-[10px] bg-default-50 p-1.5 rounded border border-default-100';
                div.innerHTML = `
                    <div class="flex items-center gap-1.5 truncate">
                        <span class="size-1.5 rounded-full shrink-0" style="background-color: ${colors[i]}"></span>
                        <span class="text-default-600 font-bold truncate">${label}</span>
                    </div>
                    <span class="font-black text-default-800">${pct}%</span>
                `;
                container.appendChild(div);
            });
        };

        buildLegend(catChart, 'category-legend', catValues, true);
        buildLegend(statChart, 'status-legend', statValues, false);
    });
</script>
@endpush

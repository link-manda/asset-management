@extends('layouts.vertical', ['title' => 'Asset Summary Report'])

@section('content')
    @include('layouts.partials/page-title', ['subtitle' => 'Reports', 'title' => 'Asset Summary'])

    <div class="card">
        <div class="card-header border-b border-default-200 flex flex-wrap items-center justify-between gap-4">
            <h6 class="card-title text-base">Summary Table</h6>
            <div class="flex gap-2">
                <a href="{{ route('reports.summary.excel', request()->all()) }}" class="btn btn-sm bg-success/10 text-success border border-success/20 hover:bg-success hover:text-white transition-all">
                    <i class="size-4 me-1" data-lucide="download"></i> Excel
                </a>
                <a href="{{ route('reports.summary.csv', request()->all()) }}" class="btn btn-sm bg-info/10 text-info border border-info/20 hover:bg-info hover:text-white transition-all">
                    <i class="size-4 me-1" data-lucide="file-text"></i> CSV
                </a>
                <a href="{{ route('reports.summary.pdf', request()->all()) }}" class="btn btn-sm bg-danger/10 text-danger border border-danger/20 hover:bg-danger hover:text-white transition-all">
                    <i class="size-4 me-1" data-lucide="file-type-2"></i> PDF
                </a>
            </div>
        </div>

        <div class="p-4 border-b border-default-200 bg-default-50/50">
            <form action="{{ route('reports.summary') }}" method="GET" id="summaryForm">
                <div class="max-w-xs">
                    <label class="text-xs font-black text-default-600 uppercase mb-1 block">Summarize By</label>
                    <select name="by" class="form-input form-input-sm w-full" onchange="this.form.submit()">
                        <option value="category" {{ $groupBy == 'category' ? 'selected' : '' }}>Category</option>
                        <option value="division" {{ $groupBy == 'division' ? 'selected' : '' }}>Division</option>
                        <option value="department" {{ $groupBy == 'department' ? 'selected' : '' }}>Department</option>
                        <option value="location" {{ $groupBy == 'location' ? 'selected' : '' }}>Location</option>
                    </select>
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-default-200">
                <thead class="bg-default-100">
                    <tr class="text-[11px] font-bold text-default-600 uppercase tracking-wider">
                        <th class="px-4 py-3 text-start">{{ ucfirst($groupBy) }}</th>
                        <th class="px-4 py-3 text-center">Total Units</th>
                        <th class="px-4 py-3 text-center">Available</th>
                        <th class="px-4 py-3 text-center">Deployed</th>
                        <th class="px-4 py-3 text-center">Maintenance</th>
                        <th class="px-4 py-3 text-end">Investment Value</th>
                        <th class="px-4 py-3 text-end">Book Value</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-default-200">
                    @forelse($data as $row)
                        <tr class="hover:bg-default-50 transition-all text-sm">
                            <td class="px-4 py-3 font-bold text-default-800">{{ $row->label }}</td>
                            <td class="px-4 py-3 text-center">{{ number_format($row->total_units) }}</td>
                            <td class="px-4 py-3 text-center text-success font-medium">{{ number_format($row->available_units) }}</td>
                            <td class="px-4 py-3 text-center text-info font-medium">{{ number_format($row->deployed_units) }}</td>
                            <td class="px-4 py-3 text-center text-warning font-medium">{{ number_format($row->maintenance_units) }}</td>
                            <td class="px-4 py-3 text-end font-medium">Rp {{ number_format($row->total_investment, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-end font-bold text-primary">Rp {{ number_format($row->current_book_value, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-default-500 italic">No summary data available.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

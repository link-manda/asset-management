@extends('layouts.app')

@section('title', 'Division Management')

@section('content')
    @include('layouts.partials/page-title', ['subtitle' => 'Master Data', 'title' => 'Divisions'])

    <div class="grid grid-cols-1 gap-6">
        <div class="card">
            <div class="card-header flex justify-between items-center">
                <h6 class="card-title">Division List</h6>
                @can('create divisions')
                <a href="{{ route('divisions.create') }}" class="btn btn-sm bg-primary text-white">
                    <i class="size-4 me-1" data-lucide="plus"></i> Add Division
                </a>
                @endcan
            </div>
            <div class="p-0">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-default-200">
                        <thead class="bg-default-100 font-normal whitespace-nowrap">
                            <tr class="text-sm text-default-800 uppercase tracking-wider text-[11px] font-bold">
                                <th class="px-3.5 py-3 text-start" scope="col">Division Name</th>
                                <th class="px-3.5 py-3 text-start" scope="col">Total Departments</th>
                                @canany(['edit divisions', 'delete divisions'])
                                <th class="px-3.5 py-3 text-center" scope="col">Actions</th>
                                @endcanany
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-default-200">
                            @foreach ($divisions as $division)
                                <tr class="text-default-800 font-normal whitespace-nowrap hover:bg-default-50 transition-all">
                                    <td class="px-3.5 py-4 text-sm font-medium text-default-800">{{ $division->name }}</td>
                                    <td class="px-3.5 py-4">
                                        <span class="inline-flex items-center gap-x-1.5 py-0.5 px-2.5 rounded text-xs font-medium bg-info/15 text-info">
                                            {{ $division->departments_count }} Departments
                                        </span>
                                    </td>
                                    @canany(['edit divisions', 'delete divisions'])
                                    <td class="px-3.5 py-4 text-center">
                                        <div class="hs-dropdown relative inline-flex">
                                            <button class="hs-dropdown-toggle btn size-8 bg-default-100 hover:bg-default-600 text-default-500 hover:text-white rounded-full transition-all" type="button">
                                                <i class="size-4" data-lucide="more-vertical"></i>
                                            </button>
                                            <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-32 z-50 bg-white shadow-md rounded-lg p-2 mt-2 border border-default-200" role="menu">
                                                @can('edit divisions')
                                                <a class="flex items-center gap-1.5 py-1.5 font-medium px-3 text-sm text-default-500 hover:bg-default-150 rounded" href="{{ route('divisions.edit', $division) }}">
                                                    <i class="size-3.5" data-lucide="edit-3"></i> Edit Division
                                                </a>
                                                @endcan
                                                
                                                @can('delete divisions')
                                                <hr class="my-1 border-default-200">
                                                <form action="{{ route('divisions.destroy', $division) }}" method="POST" class="block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="w-full flex items-center gap-1.5 py-1.5 font-medium px-3 text-sm text-danger hover:bg-danger/10 rounded delete-confirm" data-name="Division {{ $division->name }}">
                                                        <i class="size-3.5" data-lucide="trash-2"></i> Delete
                                                    </button>
                                                </form>
                                                @endcan
                                            </div>
                                        </div>
                                    </td>
                                    @endcanany
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-4 border-t border-default-200">
                    {{ $divisions->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection

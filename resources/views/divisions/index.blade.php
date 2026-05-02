@extends('layouts.app')

@section('title', 'Master Divisi')

@section('content')
    @include('layouts.partials/page-title', ['subtitle' => 'Master Data', 'title' => 'Divisi'])

    <div class="grid grid-cols-1 gap-6">
        <div class="card">
            <div class="card-header flex justify-between items-center">
                <h6 class="card-title">Daftar Divisi</h6>
                <a href="{{ route('divisions.create') }}" class="btn btn-sm bg-primary text-white">
                    <i class="size-4 me-1" data-lucide="plus"></i> Tambah Divisi
                </a>
            </div>
            <div class="p-0">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-default-200">
                        <thead class="bg-default-100 font-normal whitespace-nowrap">
                            <tr class="text-sm text-default-800">
                                <th class="px-3.5 py-3 font-medium text-start" scope="col">Nama Divisi</th>
                                <th class="px-3.5 py-3 font-medium text-start" scope="col">Jumlah Departemen</th>
                                <th class="px-3.5 py-3 font-medium text-center" scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-default-200">
                            @foreach ($divisions as $division)
                                <tr class="text-default-800 font-normal whitespace-nowrap">
                                    <td class="px-3.5 py-4 text-sm font-medium text-default-800">{{ $division->name }}</td>
                                    <td class="px-3.5 py-4">
                                        <span class="inline-flex items-center gap-x-1.5 py-0.5 px-2.5 rounded text-xs font-medium bg-info/15 text-info">
                                            {{ $division->departments_count }} Dept
                                        </span>
                                    </td>
                                    <td class="px-3.5 py-4 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('divisions.edit', $division) }}" class="flex size-8 bg-default-200 rounded-md items-center justify-center hover:bg-primary/10 hover:text-primary transition-all text-default-600">
                                                <i class="size-4" data-lucide="pencil"></i>
                                            </a>
                                            <form action="{{ route('divisions.destroy', $division) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="flex size-8 bg-default-200 rounded-md items-center justify-center hover:bg-danger/10 hover:text-danger transition-all text-default-600 delete-confirm" data-name="Divisi {{ $division->name }}">
                                                     <i class="size-4" data-lucide="trash-2"></i>
                                                 </button>
                                            </form>
                                        </div>
                                    </td>
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

@extends('layouts.app')

@section('title', 'Master Departemen')

@section('content')
    @include('layouts.partials/page-title', ['subtitle' => 'Master Data', 'title' => 'Departemen'])

    <div class="grid grid-cols-1 gap-6">
        <div class="card">
            <div class="card-header flex justify-between items-center">
                <h6 class="card-title">Daftar Departemen</h6>
                <a href="{{ route('departments.create') }}" class="btn btn-sm bg-primary text-white">
                    <i class="size-4 me-1" data-lucide="plus"></i> Tambah Departemen
                </a>
            </div>
            <div class="p-0">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-default-200">
                        <thead class="bg-default-100 font-normal whitespace-nowrap">
                            <tr class="text-sm text-default-800">
                                <th class="px-3.5 py-3 font-medium text-start" scope="col">Nama Departemen</th>
                                <th class="px-3.5 py-3 font-medium text-start" scope="col">Divisi</th>
                                <th class="px-3.5 py-3 font-medium text-start" scope="col">Jumlah User</th>
                                <th class="px-3.5 py-3 font-medium text-center" scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-default-200">
                            @foreach ($departments as $department)
                                <tr class="text-default-800 font-normal whitespace-nowrap">
                                    <td class="px-3.5 py-4 text-sm font-medium text-default-800">{{ $department->name }}</td>
                                    <td class="px-3.5 py-4">
                                        <span class="inline-flex items-center gap-x-1.5 py-0.5 px-2.5 rounded text-xs font-medium bg-primary/15 text-primary uppercase font-bold">
                                            {{ $department->division->name }}
                                        </span>
                                    </td>
                                    <td class="px-3.5 py-4 text-sm text-default-600">
                                        <span class="text-xs font-semibold text-default-500">{{ $department->users_count }} Anggota</span>
                                    </td>
                                    <td class="px-3.5 py-4 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('departments.edit', $department) }}" class="flex size-8 bg-default-200 rounded-md items-center justify-center hover:bg-primary/10 hover:text-primary transition-all text-default-600">
                                                <i class="size-4" data-lucide="pencil"></i>
                                            </a>
                                            <form action="{{ route('departments.destroy', $department) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="flex size-8 bg-default-200 rounded-md items-center justify-center hover:bg-danger/10 hover:text-danger transition-all text-default-600 delete-confirm" data-name="Departemen {{ $department->name }}">
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
                    {{ $departments->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection

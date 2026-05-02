@extends('layouts.app')

@section('title', 'Riwayat Penugasan Aset')

@section('content')
    @include('layouts.partials/page-title', ['subtitle' => 'Manajemen Aset', 'title' => 'Riwayat Penugasan'])

    <div class="grid grid-cols-1 gap-5 mb-5">
        <div class="card">
            <div class="card-header flex justify-between items-center">
                <div class="flex gap-3 items-center">
                    <div class="relative">
                        <input class="ps-11 form-input form-input-sm w-64" placeholder="Cari log penugasan..." type="text" />
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3">
                            <i class="size-3.5 flex items-center text-default-500" data-lucide="search"></i>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <button class="btn btn-sm bg-default-100 text-default-600 hover:bg-default-200">
                        <i class="size-4 me-1" data-lucide="download"></i> Export
                    </button>
                </div>
            </div>
            <div class="flex flex-col">
                <div class="overflow-x-auto">
                    <div class="min-w-full inline-block align-middle">
                        <div class="overflow-hidden">
                            <table class="min-w-full divide-y divide-default-200">
                                <thead class="bg-default-100 font-normal whitespace-nowrap">
                                    <tr class="text-sm text-default-800">
                                        <th class="px-3.5 py-3 font-medium text-start" scope="col">Asset</th>
                                        <th class="px-3.5 py-3 font-medium text-start" scope="col">Peminjam</th>
                                        <th class="px-3.5 py-3 font-medium text-start" scope="col">Periode Pinjam</th>
                                        <th class="px-3.5 py-3 font-medium text-start" scope="col">Kondisi (Out/In)</th>
                                        <th class="px-3.5 py-3 font-medium text-start" scope="col">Status Log</th>
                                        <th class="px-3.5 py-3 font-medium text-center" scope="col">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-default-200">
                                    @forelse ($assignments as $assignment)
                                        <tr class="text-default-800 font-normal whitespace-nowrap">
                                            <td class="px-3.5 py-4">
                                                <div class="flex items-center gap-3">
                                                    <div class="size-10 bg-primary/15 rounded flex items-center justify-center">
                                                        <i class="size-5 text-primary" data-lucide="package"></i>
                                                    </div>
                                                    <div>
                                                        <a href="{{ route('assets.show', $assignment->asset) }}" class="text-sm font-bold text-default-800 hover:text-primary transition-all">
                                                            {{ $assignment->asset->name }}
                                                        </a>
                                                        <div class="flex items-center gap-1.5 mt-0.5">
                                                            <span class="text-[10px] bg-primary/10 text-primary font-mono font-bold px-1 rounded">#{{ $assignment->item?->item_code ?? 'N/A' }}</span>
                                                            <span class="text-[10px] text-default-400">Master: {{ $assignment->asset->asset_code }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-3.5 py-4">
                                                <div class="flex items-center gap-2">
                                                    @php
                                                        $colors = ['bg-primary/10 text-primary', 'bg-success/10 text-success', 'bg-info/10 text-info', 'bg-warning/10 text-warning', 'bg-danger/10 text-danger'];
                                                        $userColor = $colors[$assignment->user->id % count($colors)];
                                                    @endphp
                                                    <div class="size-8 {{ $userColor }} rounded-full flex items-center justify-center text-xs font-bold uppercase">
                                                        {{ substr($assignment->user->name, 0, 2) }}
                                                    </div>
                                                    <div>
                                                        <h6 class="text-sm font-semibold text-default-800">{{ $assignment->user->name }}</h6>
                                                        <p class="text-[10px] text-default-400 uppercase tracking-wider font-bold">{{ $assignment->user->role }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-3.5 py-4 text-sm whitespace-normal">
                                                <div class="flex flex-col">
                                                    <span class="text-default-700 font-medium flex items-center gap-1">
                                                        <i class="size-3 text-success" data-lucide="arrow-up-right"></i>
                                                        {{ \Carbon\Carbon::parse($assignment->assigned_date)->format('d M Y') }}
                                                    </span>
                                                    @if($assignment->return_date)
                                                        <span class="text-default-400 flex items-center gap-1">
                                                            <i class="size-3 text-danger" data-lucide="arrow-down-left"></i>
                                                            {{ \Carbon\Carbon::parse($assignment->return_date)->format('d M Y') }}
                                                        </span>
                                                    @else
                                                        <span class="text-primary font-bold text-[10px] flex items-center gap-1 mt-1">
                                                            <span class="size-1.5 bg-primary rounded-full animate-pulse"></span>
                                                            MASIH DIPINJAM
                                                        </span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-3.5 py-4 text-sm whitespace-normal">
                                                <div class="flex flex-col gap-1">
                                                    <div class="flex items-center gap-1.5">
                                                        <span class="px-1.5 py-0.5 rounded bg-default-100 text-[10px] font-bold text-default-500">OUT</span>
                                                        <span class="text-xs text-default-600 truncate max-w-[120px]" title="{{ $assignment->condition_on_checkout }}">{{ $assignment->condition_on_checkout }}</span>
                                                    </div>
                                                    @if($assignment->condition_on_return)
                                                        <div class="flex items-center gap-1.5">
                                                            <span class="px-1.5 py-0.5 rounded bg-success/15 text-[10px] font-bold text-success">IN</span>
                                                            <span class="text-xs text-default-600 truncate max-w-[120px]" title="{{ $assignment->condition_on_return }}">{{ $assignment->condition_on_return }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-3.5 py-4">
                                                @if($assignment->return_date)
                                                    <span class="inline-flex items-center gap-1.5 py-0.5 px-2.5 rounded text-xs font-medium bg-success/15 text-success">
                                                        <i class="size-3" data-lucide="check-circle"></i> Selesai
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center gap-1.5 py-0.5 px-2.5 rounded text-xs font-medium bg-primary/15 text-primary">
                                                        <i class="size-3" data-lucide="clock"></i> Active
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-3.5 py-4 text-center">
                                                <a href="{{ route('assets.show', $assignment->asset) }}" class="flex size-8 bg-default-200 rounded-md items-center justify-center hover:bg-primary/10 hover:text-primary transition-all text-default-600 mx-auto">
                                                    <i class="size-4" data-lucide="eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-3.5 py-12 text-center">
                                                <div class="flex flex-col items-center">
                                                    <i class="size-12 text-default-200 mb-3" data-lucide="history"></i>
                                                    <p class="text-default-500 font-medium">Belum ada riwayat penugasan.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card-footer border-t border-default-200 p-4">
                    {{ $assignments->links('vendor.pagination.tailwind-custom') }}
                </div>
            </div>
        </div>
    </div>
@endsection

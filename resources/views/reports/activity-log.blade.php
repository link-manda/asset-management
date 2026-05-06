@extends('layouts.app')

@section('title', 'Audit Trail - Activity Log')

@section('content')
    @include('layouts.partials/page-title', ['subtitle' => 'System', 'title' => 'Audit Trail (Activity Logs)'])

    <div class="grid grid-cols-1 gap-6">
        {{-- Filters --}}
        <div class="card">
            <div class="card-body">
                <form action="{{ route('activity-logs.index') }}" method="GET" class="grid lg:grid-cols-4 md:grid-cols-2 grid-cols-1 gap-4">
                    <div>
                        <label class="text-xs font-medium text-default-600 mb-1 block">User (Causer)</label>
                        <select name="user_id" class="form-input form-input-sm">
                            <option value="">All Users</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-xs font-medium text-default-600 mb-1 block">Event Type</label>
                        <select name="event" class="form-input form-input-sm">
                            <option value="">All Events</option>
                            <option value="created" {{ request('event') == 'created' ? 'selected' : '' }}>Created</option>
                            <option value="updated" {{ request('event') == 'updated' ? 'selected' : '' }}>Updated</option>
                            <option value="deleted" {{ request('event') == 'deleted' ? 'selected' : '' }}>Deleted</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-xs font-medium text-default-600 mb-1 block">Module / Type</label>
                        <input type="text" name="subject_type" class="form-input form-input-sm" placeholder="e.g.: Asset" value="{{ request('subject_type') }}">
                    </div>
                    <div class="flex items-end gap-2">
                        <button type="submit" class="btn btn-sm bg-primary text-white w-full font-bold uppercase tracking-wider">Filter</button>
                        <a href="{{ route('activity-logs.index') }}" class="btn btn-sm border-default-200 text-default-600">Reset</a>
                    </div>
                </form>
            </div>
        </div>

        {{-- Activity Table --}}
        <div class="card">
            <div class="card-body p-0">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-default-200">
                        <thead class="bg-default-50">
                            <tr>
                                <th class="px-4 py-3 text-start text-xs font-bold text-default-600 uppercase tracking-wider">Timestamp</th>
                                <th class="px-4 py-3 text-start text-xs font-bold text-default-600 uppercase tracking-wider">User</th>
                                <th class="px-4 py-3 text-start text-xs font-bold text-default-600 uppercase tracking-wider">Action</th>
                                <th class="px-4 py-3 text-start text-xs font-bold text-default-600 uppercase tracking-wider">Module</th>
                                <th class="px-4 py-3 text-start text-xs font-bold text-default-600 uppercase tracking-wider">Change Details</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-default-200">
                            @forelse($activities as $log)
                                <tr class="hover:bg-default-50 transition-all">
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-default-600">
                                        {{ $log->created_at->format('d/m/Y H:i:s') }}
                                        <p class="text-[10px] text-default-400">{{ $log->created_at->diffForHumans() }}</p>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-2">
                                            <div class="size-8 bg-primary/10 text-primary rounded-full flex items-center justify-center font-bold text-xs">
                                                {{ substr($log->causer?->name ?? 'SYS', 0, 1) }}
                                            </div>
                                            <span class="text-sm font-medium text-default-800">{{ $log->causer?->name ?? 'System' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        @php
                                            $eventClass = match($log->event) {
                                                'created' => 'bg-success/15 text-success',
                                                'updated' => 'bg-warning/15 text-warning',
                                                'deleted' => 'bg-danger/15 text-danger',
                                                default => 'bg-default-100 text-default-600'
                                            };
                                        @endphp
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $eventClass }}">
                                            {{ $log->event ?? 'log' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-default-600">
                                        {{ str_replace('App\\Models\\', '', $log->subject_type) }}
                                        <p class="text-[10px] font-mono text-default-400">ID: {{ $log->subject_id }}</p>
                                    </td>
                                    <td class="px-4 py-4 text-sm">
                                        @if($log->event == 'updated')
                                            @php
                                                $old = $log->properties['old'] ?? [];
                                                $new = $log->properties['attributes'] ?? [];
                                                $changes = array_diff_assoc($new, $old);
                                            @endphp
                                            <div class="space-y-1">
                                                @foreach($changes as $key => $val)
                                                    @if($key != 'updated_at')
                                                        <div class="text-[11px]">
                                                            <span class="font-bold text-default-700">{{ $key }}:</span> 
                                                            <span class="text-default-400 line-through">{{ is_array($old[$key] ?? '') ? json_encode($old[$key]) : ($old[$key] ?? 'N/A') }}</span>
                                                            <i class="iconify tabler--arrow-right text-[10px] mx-1"></i>
                                                            <span class="text-primary font-medium">{{ is_array($val) ? json_encode($val) : $val }}</span>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        @elseif($log->event == 'created')
                                            <span class="text-success text-xs italic">New record added to the system.</span>
                                        @else
                                            <span class="text-default-500 text-xs">{{ $log->description }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-12 text-center text-default-400 italic">No activity recorded yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer border-t border-default-200 p-4">
                {{ $activities->links('vendor.pagination.tailwind-custom') }}
            </div>
        </div>
    </div>
@endsection

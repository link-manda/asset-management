<div class="space-y-4">
    @forelse($activities as $activity)
        <div class="flex gap-4 relative pb-6 last:pb-0">
            {{-- Line --}}
            @if(!$loop->last)
                <div class="absolute start-4 top-8 bottom-0 w-px bg-default-200"></div>
            @endif

            {{-- Icon --}}
            <div class="size-8 rounded-full flex items-center justify-center shrink-0 z-10 
                {{ $activity->event === 'created' ? 'bg-success/10 text-success' : 
                   ($activity->event === 'updated' ? 'bg-primary/10 text-primary' : 'bg-danger/10 text-danger') }}">
                <i class="size-4" data-lucide="{{ $activity->event === 'created' ? 'plus' : ($activity->event === 'updated' ? 'edit-3' : 'trash-2') }}"></i>
            </div>

            <div class="grow pt-1">
                <div class="flex justify-between items-start mb-1">
                    <h6 class="text-sm font-bold text-default-800">
                        {{ $activity->causer ? $activity->causer->name : 'System' }} 
                        <span class="font-normal text-default-500 lowercase">
                            {{ $activity->event === 'created' ? 'added new record' : ($activity->event === 'updated' ? 'updated data' : 'removed record') }}
                        </span>
                    </h6>
                    <span class="text-[10px] text-default-400 font-medium">{{ $activity->created_at->diffForHumans() }}</span>
                </div>

                @if($activity->event === 'updated' && !empty($activity->changes['attributes']))
                    <div class="bg-default-50 rounded-lg p-3 border border-default-100 mt-2">
                        <p class="text-[10px] uppercase font-bold text-default-400 mb-2 tracking-widest">Change Details:</p>
                        <div class="grid grid-cols-1 gap-2">
                            @foreach($activity->changes['attributes'] as $key => $value)
                                @php
                                    $oldValue = $activity->changes['old'][$key] ?? 'N/A';
                                    // Skip sensitive or redundant fields
                                    if(in_array($key, ['updated_at', 'created_at'])) continue;
                                @endphp
                                <div class="flex items-center gap-2 text-xs">
                                    <span class="font-bold text-default-700 w-24 shrink-0">{{ ucwords(str_replace('_', ' ', $key)) }}:</span>
                                    <span class="text-danger line-through opacity-50">{{ is_array($oldValue) ? json_encode($oldValue) : $oldValue }}</span>
                                    <i class="size-3 text-default-400" data-lucide="arrow-right"></i>
                                    <span class="text-success font-bold">{{ is_array($value) ? json_encode($value) : $value }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
                
                <p class="text-[10px] text-default-400 mt-1 italic">{{ $activity->created_at->format('d M Y, H:i') }}</p>
            </div>
        </div>
    @empty
        <div class="text-center py-10">
            <div class="size-16 bg-default-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-default-100">
                <i class="size-8 text-default-300" data-lucide="history"></i>
            </div>
            <p class="text-default-500 font-medium">No activity history for this asset yet.</p>
        </div>
    @endforelse
</div>

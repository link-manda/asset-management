@php
    $subtitleMap = [
        'Home' => route('dashboard'),
        'Catalog' => route('assets.index'),
        'Assets' => route('assets.index'),
        'Inventory' => route('inventory.index'),
        'Maintenance' => route('maintenances.index'),
        'Reports' => route('reports.general'),
        'System Management' => route('users.index'),
        'System' => route('users.index'),
        'Assignments' => route('assignments.index'),
        'Master Data' => route('categories.index'),
    ];
    $subtitleUrl = $subtitleUrl ?? ($subtitleMap[$subtitle] ?? null);
    $currentUrl = url()->current();
@endphp

<!-- Page Title Start -->
<div class="flex items-center md:justify-between flex-wrap gap-2 mb-4 print:hidden">
    <h4 class="text-default-900 text-lg font-semibold">{{ $title }}</h4>
    <div class="md:flex hidden items-center gap-2 text-sm font-semibold">
        <a class="text-sm font-medium text-default-700 hover:text-primary transition-all" href="{{ route('dashboard') }}">Home</a>

        @if(isset($breadcrumbs) && is_array($breadcrumbs))
            @foreach($breadcrumbs as $breadcrumb)
                <i class="iconify tabler--chevron-right text-sm flex-shrink-0 text-default-500 rtl:rotate-180"></i>
                @if(!$loop->last && isset($breadcrumb['url']) && $breadcrumb['url'] != $currentUrl)
                    <a class="text-sm font-medium text-default-700 hover:text-primary transition-all" href="{{ $breadcrumb['url'] }}">{{ $breadcrumb['label'] }}</a>
                @else
                    <span class="text-sm font-medium text-default-700">{{ $breadcrumb['label'] }}</span>
                @endif
            @endforeach
        @else
            @if(isset($subtitle))
                <i class="iconify tabler--chevron-right text-sm flex-shrink-0 text-default-500 rtl:rotate-180"></i>
                @if($subtitleUrl && $subtitleUrl != $currentUrl)
                    <a class="text-sm font-medium text-default-700 hover:text-primary transition-all" href="{{ $subtitleUrl }}">{{ $subtitle }}</a>
                @else
                    <span class="text-sm font-medium text-default-700">{{ $subtitle }}</span>
                @endif
            @endif

            <i class="iconify tabler--chevron-right text-sm flex-shrink-0 text-default-500 rtl:rotate-180"></i>
            <span aria-current="page" class="text-sm font-medium text-primary">{{ $title }}</span>
        @endif
    </div>
</div>
<!-- Page Title End -->


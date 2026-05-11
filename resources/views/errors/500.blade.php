@extends('layouts.base', ['title' => 'Server Error'])

@section('content')
<div class="flex items-center justify-center min-h-screen p-6 bg-default-50/50 dark:bg-default-50/10">
    <div class="max-w-xl w-full text-center">
        <div class="mb-6">
            <span class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-[10px] font-black bg-warning/10 text-warning uppercase tracking-widest border border-warning/20">
                Error 500
            </span>
        </div>

        <h1 class="text-4xl md:text-5xl font-black text-default-900 mb-4 tracking-tight">System Breakdown</h1>
        
        <p class="text-base text-default-500 mb-10 max-w-md mx-auto leading-relaxed">
            Whoops! Something went wrong on our end. Our engineering team has been notified. Please try again in a few moments.
        </p>

        <div class="mb-12 flex justify-center">
            <div class="size-32 md:size-40 bg-warning/10 text-warning rounded-full flex items-center justify-center animate-pulse">
                <i data-lucide="server-crash" class="size-16 md:size-20"></i>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
            <a href="{{ route('dashboard') }}" class="btn bg-primary hover:bg-primary-600 text-white px-8 py-3 rounded-lg font-bold shadow-lg shadow-primary/20 transition-all flex items-center gap-2">
                <i data-lucide="home" class="size-4"></i> Dashboard
            </a>
            <button onclick="window.location.reload()" class="btn border border-default-200 text-default-600 px-8 py-3 rounded-lg font-bold hover:bg-default-100 transition-all flex items-center gap-2">
                <i data-lucide="refresh-cw" class="size-4"></i> Reload Page
            </button>
        </div>

        <div class="mt-16 pt-8 border-t border-default-200/50 text-center text-xs text-default-400 font-bold uppercase tracking-widest">
            © {{ date('Y') }} Asset Management System • Maintenance Ongoing
        </div>
    </div>
</div>
@endsection

@extends('layouts.base', ['title' => 'Session Expired'])

@section('content')
<div class="flex items-center justify-center min-h-screen p-6 bg-default-50/50 dark:bg-default-50/10">
    <div class="max-w-xl w-full text-center">
        <div class="mb-6">
            <span class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-[10px] font-black bg-info/10 text-info uppercase tracking-widest border border-info/20">
                Error 419
            </span>
        </div>

        <h1 class="text-4xl md:text-5xl font-black text-default-900 mb-4 tracking-tight">Session Timeout</h1>
        
        <p class="text-base text-default-500 mb-10 max-w-md mx-auto leading-relaxed">
            Your session has expired due to inactivity. For your security, please refresh the page and sign in again to continue.
        </p>

        <div class="mb-12 flex justify-center">
            <div class="size-32 md:size-40 bg-info/10 text-info rounded-full flex items-center justify-center border-4 border-dashed border-info/20 animate-[spin_10s_linear_infinite]">
                <i data-lucide="clock-rewind" class="size-16 md:size-20"></i>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
            <a href="{{ route('login') }}" class="btn bg-primary hover:bg-primary-600 text-white px-8 py-3 rounded-lg font-bold shadow-lg shadow-primary/20 transition-all flex items-center gap-2">
                <i data-lucide="log-in" class="size-4"></i> Sign In Again
            </a>
            <button onclick="window.location.reload()" class="btn border border-default-200 text-default-600 px-8 py-3 rounded-lg font-bold hover:bg-default-100 transition-all flex items-center gap-2">
                <i data-lucide="refresh-cw" class="size-4"></i> Refresh Page
            </button>
        </div>

        <div class="mt-16 pt-8 border-t border-default-200/50 text-center text-xs text-default-400 font-bold uppercase tracking-widest">
            © {{ date('Y') }} Asset Management System • Security First
        </div>
    </div>
</div>
@endsection

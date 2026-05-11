@extends('layouts.base', ['title' => 'Access Denied'])

@section('content')
<div class="flex items-center justify-center min-h-screen p-6 bg-default-50/50 dark:bg-default-50/10">
    <div class="max-w-xl w-full text-center">
        <div class="mb-6">
            <span class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-[10px] font-black bg-danger/10 text-danger uppercase tracking-widest border border-danger/20">
                Error 403
            </span>
        </div>

        <h1 class="text-4xl md:text-5xl font-black text-default-900 mb-4 tracking-tight">Access Denied</h1>
        
        <p class="text-base text-default-500 mb-10 max-w-md mx-auto leading-relaxed">
            Sorry, you don't have permission to access this area. Your activity has been logged. Please contact your administrator for assistance.
        </p>

        <div class="mb-12 flex justify-center">
            <div class="relative">
                <div class="size-32 md:size-40 bg-danger/10 text-danger rounded-2xl flex items-center justify-center rotate-12 hover:rotate-0 transition-transform duration-500">
                    <i data-lucide="shield-off" class="size-16 md:size-20"></i>
                </div>
                <div class="absolute -top-3 -right-3 size-10 bg-white dark:bg-default-50 border-4 border-danger rounded-full flex items-center justify-center text-danger font-black text-lg shadow-lg">
                    !
                </div>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
            <a href="{{ route('dashboard') }}" class="btn bg-primary hover:bg-primary-600 text-white px-8 py-3 rounded-lg font-bold shadow-lg shadow-primary/20 transition-all flex items-center gap-2">
                <i data-lucide="home" class="size-4"></i> Dashboard
            </a>
            <button onclick="window.history.back()" class="btn border border-default-200 text-default-600 px-8 py-3 rounded-lg font-bold hover:bg-default-100 transition-all flex items-center gap-2">
                <i data-lucide="arrow-left" class="size-4"></i> Back to Safety
            </button>
        </div>

        <div class="mt-16 pt-8 border-t border-default-200/50 text-center text-xs text-default-400 font-bold uppercase tracking-widest">
            © {{ date('Y') }} Asset Management System • Security Protocol
        </div>
    </div>
</div>
@endsection

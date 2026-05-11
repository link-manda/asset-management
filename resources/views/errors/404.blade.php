@extends('layouts.base', ['title' => 'Page Not Found'])

@section('content')
<div class="flex items-center justify-center min-h-screen p-6 bg-default-50/50 dark:bg-default-50/10">
    <div class="max-w-xl w-full text-center">
        <div class="mb-6">
            <span class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-[10px] font-black bg-primary/10 text-primary uppercase tracking-widest border border-primary/20">
                Error 404
            </span>
        </div>

        <h1 class="text-4xl md:text-5xl font-black text-default-900 mb-4 tracking-tight">Oops! Page Not Found</h1>
        
        <p class="text-base text-default-500 mb-8 max-w-md mx-auto leading-relaxed">
            The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.
        </p>

        <div class="mb-12">
            <img src="{{ asset('images/error-404.png') }}" alt="404 Error" class="mx-auto h-48 md:h-64 object-contain drop-shadow-2xl opacity-90 hover:opacity-100 transition-opacity">
        </div>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
            <a href="{{ route('dashboard') }}" class="btn bg-primary hover:bg-primary-600 text-white px-8 py-3 rounded-lg font-bold shadow-lg shadow-primary/20 transition-all flex items-center gap-2">
                <i data-lucide="home" class="size-4"></i> Back to Dashboard
            </a>
            <button onclick="window.history.back()" class="btn border border-default-200 text-default-600 px-8 py-3 rounded-lg font-bold hover:bg-default-100 transition-all flex items-center gap-2">
                <i data-lucide="arrow-left" class="size-4"></i> Go Back
            </button>
        </div>

        <div class="mt-16 pt-8 border-t border-default-200/50 text-center">
            <p class="text-xs text-default-400 font-bold uppercase tracking-widest">© {{ date('Y') }} Asset Management System</p>
        </div>
    </div>
</div>
@endsection

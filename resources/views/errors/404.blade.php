@extends('layouts.base', ['title' => 'Page Not Found'])

@section('content')
<div class="flex items-center justify-center min-h-screen p-6 bg-default-50 dark:bg-default-50">
    <div class="max-w-xl w-full text-center">
        <div class="mb-10">
            <img src="{{ asset('images/error-404.png') }}" alt="404 Error" class="mx-auto h-64 md:h-80 object-contain drop-shadow-xl animate-pulse">
        </div>

        <div class="px-4">
            <h1 class="text-5xl font-black text-default-900 mb-4 tracking-tight">Opps! Page Not Found</h1>
            <p class="text-lg text-default-500 mb-10 max-w-md mx-auto leading-relaxed">
                The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('dashboard') }}" class="btn bg-primary hover:bg-primary-600 text-white px-8 py-3 rounded-xl font-bold shadow-lg shadow-primary/20 transition-all transform hover:-translate-y-1 flex items-center gap-2">
                    <i data-lucide="home" class="size-5"></i> Back to Dashboard
                </a>
                <button onclick="window.history.back()" class="btn border-default-200 text-default-600 px-8 py-3 rounded-xl font-bold hover:bg-default-100 transition-all flex items-center gap-2">
                    <i data-lucide="arrow-left" class="size-5"></i> Go Back
                </button>
            </div>
        </div>

        <div class="mt-16 pt-8 border-t border-default-200/60 text-center text-sm text-default-400 font-medium">
            © {{ date('Y') }} Asset Management System. All rights reserved.
        </div>
    </div>
</div>
@endsection

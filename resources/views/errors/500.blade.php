@extends('layouts.base', ['title' => 'Server Error'])

@section('content')
<div class="flex items-center justify-center min-h-screen p-6 bg-default-50 dark:bg-default-50">
    <div class="max-w-xl w-full text-center">
        <div class="mb-10">
            <div class="size-48 bg-warning/10 text-warning rounded-full flex items-center justify-center mx-auto">
                <i data-lucide="server-crash" class="size-32"></i>
            </div>
        </div>

        <div class="px-4">
            <h1 class="text-5xl font-black text-default-900 mb-4 tracking-tight">500 - Internal Server Error</h1>
            <p class="text-lg text-default-500 mb-10 max-w-md mx-auto leading-relaxed">
                Whoops! Something went wrong on our end. We're working on fixing it. Please try again later.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('dashboard') }}" class="btn bg-primary hover:bg-primary-600 text-white px-8 py-3 rounded-xl font-bold shadow-lg shadow-primary/20 transition-all flex items-center gap-2">
                    <i data-lucide="home" class="size-5"></i> Back to Dashboard
                </a>
                <button onclick="window.location.reload()" class="btn border-default-200 text-default-600 px-8 py-3 rounded-xl font-bold hover:bg-default-100 transition-all flex items-center gap-2">
                    <i data-lucide="refresh-cw" class="size-5"></i> Reload Page
                </button>
            </div>
        </div>

        <div class="mt-16 pt-8 border-t border-default-200/60 text-center text-sm text-default-400 font-medium">
            © {{ date('Y') }} Asset Management System. Maintenance mode initiated.
        </div>
    </div>
</div>
@endsection

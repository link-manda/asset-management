@extends('layouts.base', ['title' => 'Page Expired'])

@section('content')
<div class="flex items-center justify-center min-h-screen p-6 bg-default-50 dark:bg-default-50">
    <div class="max-w-xl w-full text-center">
        <div class="mb-10">
            <div class="size-48 bg-info/10 text-info rounded-full flex items-center justify-center mx-auto">
                <i data-lucide="clock" class="size-32"></i>
            </div>
        </div>

        <div class="px-4">
            <h1 class="text-5xl font-black text-default-900 mb-4 tracking-tight">419 - Session Expired</h1>
            <p class="text-lg text-default-500 mb-10 max-w-md mx-auto leading-relaxed">
                Your session has expired due to inactivity. Please refresh the page and try logging in again.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('login') }}" class="btn bg-primary hover:bg-primary-600 text-white px-8 py-3 rounded-xl font-bold shadow-lg shadow-primary/20 transition-all flex items-center gap-2">
                    <i data-lucide="log-in" class="size-5"></i> Back to Login
                </a>
                <button onclick="window.location.reload()" class="btn border-default-200 text-default-600 px-8 py-3 rounded-xl font-bold hover:bg-default-100 transition-all flex items-center gap-2">
                    <i data-lucide="refresh-cw" class="size-5"></i> Refresh Page
                </button>
            </div>
        </div>

        <div class="mt-16 pt-8 border-t border-default-200/60 text-center text-sm text-default-400 font-medium">
            © {{ date('Y') }} Asset Management System. Security protocol updated.
        </div>
    </div>
</div>
@endsection

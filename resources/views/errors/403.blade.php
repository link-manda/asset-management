@extends('layouts.base', ['title' => 'Access Denied'])

@section('content')
<div class="flex items-center justify-center min-h-screen p-6 bg-default-50 dark:bg-default-50">
    <div class="max-w-xl w-full text-center">
        <div class="mb-10 relative flex justify-center">
            <div class="size-48 bg-danger/10 text-danger rounded-full flex items-center justify-center animate-bounce">
                <i data-lucide="shield-alert" class="size-32"></i>
            </div>
            <div class="absolute -bottom-2 right-1/3 size-12 bg-white dark:bg-default-50 border-4 border-danger rounded-full flex items-center justify-center text-danger font-black text-xl shadow-lg">
                !
            </div>
        </div>

        <div class="px-4">
            <h1 class="text-5xl font-black text-default-900 mb-4 tracking-tight">403 - Forbidden</h1>
            <p class="text-lg text-default-500 mb-10 max-w-md mx-auto leading-relaxed">
                Sorry, you don't have permission to access this area. Please contact your system administrator if you think this is a mistake.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('dashboard') }}" class="btn bg-primary hover:bg-primary-600 text-white px-8 py-3 rounded-xl font-bold shadow-lg shadow-primary/20 transition-all transform hover:-translate-y-1 flex items-center gap-2">
                    <i data-lucide="home" class="size-5"></i> Dashboard
                </a>
                <button onclick="window.history.back()" class="btn border-default-200 text-default-600 px-8 py-3 rounded-xl font-bold hover:bg-default-100 transition-all flex items-center gap-2">
                    <i data-lucide="arrow-left" class="size-5"></i> Back to Safety
                </button>
            </div>
        </div>

        <div class="mt-16 pt-8 border-t border-default-200/60 text-center text-sm text-default-400 font-medium">
            © {{ date('Y') }} Asset Management System. Security Protocol Active.
        </div>
    </div>
</div>
@endsection

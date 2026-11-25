@extends('layouts.base', ['title' => 'Access Denied'])

@section('content')
<div class="relative flex items-center justify-center min-h-screen p-6 overflow-hidden bg-white dark:bg-default-50">
    {{-- Decorative Background Elements --}}
    <div class="absolute top-0 start-0 -translate-x-1/2 -translate-y-1/2 size-96 bg-danger/5 rounded-full blur-3xl"></div>
    <div class="absolute bottom-0 end-0 translate-x-1/2 translate-y-1/2 size-96 bg-danger/5 rounded-full blur-3xl"></div>

    <div class="relative max-w-lg w-full text-center z-10">
        <div class="mb-8 flex justify-center">
            <div class="inline-flex items-center justify-center size-20 bg-danger/10 rounded-2xl -rotate-6">
                <i data-lucide="shield-off" class="size-10 text-danger rotate-6"></i>
            </div>
        </div>

        <h1 class="text-8xl font-black text-danger/20 mb-[-2rem] select-none tracking-tighter">403</h1>
        <h2 class="text-4xl font-bold text-default-900 mb-4 relative">Access Denied</h2>
        
        <p class="text-base text-default-500 mb-12 max-w-sm mx-auto leading-relaxed">
            Sorry, you don't have permission to access this area. Your activity has been logged for security purposes.
        </p>

        <div class="mb-12 flex justify-center">
            <div class="size-48 bg-danger/5 rounded-full flex items-center justify-center border-4 border-danger/10 animate-pulse">
                <i data-lucide="lock-keyhole" class="size-24 text-danger/20"></i>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
            <a href="{{ route('dashboard') }}" class="btn bg-primary hover:bg-primary-600 text-white px-8 py-3 rounded-xl font-bold shadow-lg shadow-primary/20 transition-all transform hover:-translate-y-0.5 active:scale-95 flex items-center gap-2">
                <i data-lucide="home" class="size-4"></i> Dashboard
            </a>
            <button onclick="window.history.back()" class="btn border-2 border-default-100 text-default-600 hover:bg-default-100 px-8 py-3 rounded-xl font-bold transition-all flex items-center gap-2">
                <i data-lucide="arrow-left" class="size-4"></i> Back to Safety
            </button>
        </div>

        <div class="mt-16 text-[10px] font-bold uppercase tracking-widest text-default-400 italic">
            <span class="text-danger">●</span> Security Protocol Active
        </div>
    </div>
</div>
@endsection

@extends('layouts.base', ['title' => 'System Error'])

@section('content')
<div class="relative flex items-center justify-center min-h-screen p-6 overflow-hidden bg-white dark:bg-default-50">
    {{-- Decorative Background Elements --}}
    <div class="absolute top-0 start-0 -translate-x-1/2 -translate-y-1/2 size-96 bg-warning/5 rounded-full blur-3xl"></div>
    <div class="absolute bottom-0 end-0 translate-x-1/2 translate-y-1/2 size-96 bg-warning/5 rounded-full blur-3xl"></div>

    <div class="relative max-w-lg w-full text-center z-10">
        <div class="mb-8 flex justify-center">
            <div class="inline-flex items-center justify-center size-20 bg-warning/10 rounded-2xl rotate-3">
                <i data-lucide="server-off" class="size-10 text-warning -rotate-3"></i>
            </div>
        </div>

        <h1 class="text-8xl font-black text-warning/20 mb-[-2rem] select-none tracking-tighter">500</h1>
        <h2 class="text-4xl font-bold text-default-900 mb-4 relative">System Breakdown</h2>
        
        <p class="text-base text-default-500 mb-12 max-w-sm mx-auto leading-relaxed">
            Whoops! Something went wrong on our end. Our engineering team has been notified. Please try again in a few moments.
        </p>

        <div class="mb-12 flex justify-center">
            <div class="flex items-center gap-2">
                <span class="size-2 bg-warning rounded-full animate-bounce"></span>
                <span class="size-2 bg-warning rounded-full animate-bounce [animation-delay:-0.3s]"></span>
                <span class="size-2 bg-warning rounded-full animate-bounce [animation-delay:-0.5s]"></span>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
            <a href="{{ route('dashboard') }}" class="btn bg-primary hover:bg-primary-600 text-white px-8 py-3 rounded-xl font-bold shadow-lg shadow-primary/20 transition-all transform hover:-translate-y-0.5 active:scale-95 flex items-center gap-2">
                <i data-lucide="home" class="size-4"></i> Dashboard
            </a>
            <button onclick="window.location.reload()" class="btn border-2 border-default-100 text-default-600 hover:bg-default-100 px-8 py-3 rounded-xl font-bold transition-all flex items-center gap-2">
                <i data-lucide="refresh-cw" class="size-4"></i> Reload Page
            </button>
        </div>

        <div class="mt-16 text-[10px] font-bold uppercase tracking-widest text-default-400">
            © {{ date('Y') }} Asset Management System • Maintenance Ongoing
        </div>
    </div>
</div>
@endsection

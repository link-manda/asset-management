@extends('layouts.base', ['title' => 'Login'])

@section('content')
    <div class="relative min-h-screen w-full flex justify-center items-center py-16 md:py-10">
        <div class="card md:w-lg w-screen z-10 mx-4">
            <div class="text-center px-10 py-12">
                <!-- Logo -->
                <a class="flex justify-center" href="{{ route('dashboard') }}">
                    <img alt="logo dark" class="h-8 flex dark:hidden" src="/images/logo-dark.png"/>
                    <img alt="logo light" class="h-8 hidden dark:flex" src="/images/logo-light.png"/>
                </a>
                <div class="mt-8 text-center">
                    <h4 class="mb-2.5 text-xl font-semibold text-primary uppercase tracking-wider">Asset Management</h4>
                    <p class="text-base text-default-500">Silakan login untuk mengelola aset perusahaan.</p>
                </div>
                
                @if($errors->any())
                    <div class="mt-6 p-4 bg-danger/10 border border-danger/20 rounded-lg text-left">
                        <ul class="list-disc list-inside text-sm text-danger font-medium">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('login') }}" method="POST" class="text-left w-full mt-10">
                    @csrf
                    <div class="mb-4">
                        <label class="block font-medium text-default-900 text-sm mb-2" for="email">Alamat Email</label>
                        <input class="form-input" id="email" name="email" value="{{ old('email') }}" placeholder="admin@example.com" type="email" required autofocus />
                    </div>
                    <div class="mb-6">
                        <label class="block font-medium text-default-900 text-sm mb-2" for="password">Password</label>
                        <input class="form-input" id="password" name="password" placeholder="••••••••" type="password" required />
                    </div>
                    <div class="mt-10 text-center">
                        <button class="btn bg-primary text-white w-full py-2.5 font-semibold" type="submit">
                            Sign In
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Decorative Background Pattern -->
        <div class="absolute inset-0 overflow-hidden -z-0">
            <svg aria-hidden="true" class="absolute inset-0 size-full fill-black/2 stroke-black/5 dark:fill-white/2.5 dark:stroke-white/2.5">
                <defs>
                    <pattern height="56" id="authPattern" patternunits="userSpaceOnUse" width="56" x="50%" y="16">
                        <path d="M.5 56V.5H72" fill="none"></path>
                    </pattern>
                </defs>
                <rect fill="url(#authPattern)" height="100%" stroke-width="0" width="100%"></rect>
            </svg>
        </div>
    </div>
@endsection

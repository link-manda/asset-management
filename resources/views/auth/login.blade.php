@extends('layouts.base', ['title' => 'Login'])

@section('content')
    <div class="flex min-h-screen bg-white dark:bg-default-50">
        <!-- Left Pane: Image & Branding (Desktop Only) -->
        <div class="hidden lg:block lg:w-1/2 relative overflow-hidden">
            {{-- Background Image --}}
            <img src="https://images.unsplash.com/photo-1581092160562-40aa08e78837?auto=format&fit=crop&q=80&w=2070" 
                 class="absolute inset-0 h-full w-full object-cover" 
                 alt="Industrial Tech Background">
            
            {{-- Gradient Overlay --}}
            <div class="absolute inset-0 bg-gradient-to-t from-primary/90 via-primary/40 to-transparent flex flex-col justify-end p-16">
                <div class="mb-6">
                    <span class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-medium bg-white/20 text-white backdrop-blur-md mb-4">
                        <span class="size-1.5 inline-block rounded-full bg-white"></span>
                        Enterprise Solution
                    </span>
                    <h2 class="text-white text-5xl font-black leading-tight mb-4">
                        Asset Management <br>
                        <span class="text-white/60">Intelligence.</span>
                    </h2>
                    <p class="text-white/80 text-xl max-w-lg leading-relaxed">
                        Optimize maintenance, track fiscal depreciation, and manage your physical inventory in one integrated smart platform.
                    </p>
                </div>
                
                <div class="flex items-center gap-4 border-t border-white/10 pt-8 mt-8">
                    <div class="flex -space-x-2">
                        <img class="inline-block size-10 rounded-full ring-2 ring-primary bg-default-100" src="https://ui-avatars.com/api/?name=Admin&background=random" alt="User">
                        <img class="inline-block size-10 rounded-full ring-2 ring-primary bg-default-100" src="https://ui-avatars.com/api/?name=Finance&background=random" alt="User">
                        <img class="inline-block size-10 rounded-full ring-2 ring-primary bg-default-100" src="https://ui-avatars.com/api/?name=IT&background=random" alt="User">
                    </div>
                    <p class="text-white/60 text-sm font-medium">Trusted by multiple divisions for asset data accuracy.</p>
                </div>
            </div>
        </div>

        <!-- Right Pane: Login Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 md:p-16 relative overflow-hidden">
            {{-- Decorative pattern for mobile/right side --}}
            <div class="absolute top-0 right-0 -translate-y-1/2 translate-x-1/2 size-96 bg-primary/5 rounded-full blur-3xl lg:hidden"></div>
            
            <div class="max-w-md w-full z-10">
                <div class="mb-10">
                    {{-- Logo --}}
                    <a class="flex items-center gap-2 mb-8" href="{{ route('dashboard') }}">
                        <div class="size-10 bg-primary rounded-xl flex items-center justify-center text-white shadow-lg shadow-primary/20">
                            <i data-lucide="shield-check" class="size-6"></i>
                        </div>
                        <span class="text-2xl font-black tracking-tight text-default-900">ASSET<span class="text-primary">MGMT</span></span>
                    </a>
                    
                    <h1 class="text-3xl font-bold text-default-900 mb-2">Welcome Back</h1>
                    <p class="text-default-500">Please enter your credentials to start managing assets.</p>
                </div>

                @if($errors->any())
                    <div class="mb-6 p-4 bg-danger/10 border border-danger/20 rounded-xl">
                        <div class="flex gap-3">
                            <i data-lucide="alert-circle" class="size-5 text-danger"></i>
                            <ul class="list-none text-sm text-danger font-medium space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <form action="{{ route('login') }}" method="POST" class="space-y-5">
                    @csrf
                    <div>
                        <label class="block font-bold text-default-700 text-xs uppercase tracking-widest mb-2" for="email">Corporate Email</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-4 pointer-events-none z-10">
                                <i class="size-4 text-default-400" data-lucide="mail"></i>
                            </div>
                            <input class="form-input ps-11 py-3 border-default-200 focus:border-primary rounded-xl transition-all w-full" 
                                   id="email" name="email" value="{{ old('email') }}" 
                                   placeholder="name@company.com" type="email" required autofocus />
                        </div>
                    </div>
                    
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <label class="block font-bold text-default-700 text-xs uppercase tracking-widest" for="password">Password</label>
                            <a href="#" class="text-xs font-semibold text-primary hover:underline">Forgot Password?</a>
                        </div>
                        <div class="relative" x-data="{ show: false }">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-4 pointer-events-none z-10">
                                <i class="size-4 text-default-400" data-lucide="lock"></i>
                            </div>
                            
                            <input class="form-input ps-11 pe-12 py-3 border-default-200 focus:border-primary rounded-xl transition-all w-full" 
                                   id="password" :type="show ? 'text' : 'password'" name="password" 
                                   placeholder="••••••••" required />
                            
                            <button type="button" @click="show = !show" class="absolute inset-y-0 end-0 flex items-center pe-4 text-default-400 hover:text-primary transition-all z-10 focus:outline-none">
                                <span x-show="!show" x-cloak><i class="size-4" data-lucide="eye"></i></span>
                                <span x-show="show" x-cloak><i class="size-4" data-lucide="eye-off"></i></span>
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center mb-6">
                        <input type="checkbox" class="form-checkbox rounded text-primary border-default-300 size-4" id="remember" name="remember">
                        <label class="ms-2 text-sm text-default-600 font-medium" for="remember">Remember me on this device</label>
                    </div>

                    <button class="btn bg-primary hover:bg-primary-600 text-white w-full py-4 rounded-xl font-bold shadow-lg shadow-primary/20 transition-all transform hover:-translate-y-0.5 active:scale-95 flex items-center justify-center gap-2" type="submit">
                        Sign In to Dashboard <i data-lucide="arrow-right" class="size-4"></i>
                    </button>
                </form>

                <div class="mt-12 pt-8 border-t border-default-100 text-center">
                    <p class="text-sm text-default-500 font-medium">© {{ date('Y') }} Asset Management System. v2.1.0</p>
                </div>
            </div>
        </div>
    </div>
@endsection

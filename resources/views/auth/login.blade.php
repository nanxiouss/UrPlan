<x-guest-layout>
    <div class="flex min-h-screen bg-white overflow-hidden">

        <div class="w-full lg:w-1/2 flex flex-col justify-center px-8 sm:px-16 md:px-24 xl:px-32 py-12 lg:py-0 overflow-y-auto relative z-10">

            <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none -z-10">
                <div class="absolute -top-[10%] -left-[10%] w-[50%] h-[50%] rounded-full bg-blue-100/40 blur-3xl"></div>
                <div class="absolute top-[60%] -right-[10%] w-[40%] h-[40%] rounded-full bg-indigo-100/40 blur-3xl"></div>
            </div>

            <div class="w-full max-w-md mx-auto">

                <a href="/" class="flex items-center -gap-2 mt-4 mb-8 group w-fit -ml-6">
                    <img src="{{ asset('images/logo-1.png') }}" alt="UrPlan Logo" class="h-14 w-auto object-contain transition-all duration-300 transform group-hover:scale-105">
                    <span class="font-black text-2xl tracking-tight text-slate-900 leading-none">UrPlan</span>
                </a>

                <h1 class="text-3xl md:text-4xl font-black text-slate-900 mb-2 leading-tight">
                    Selamat datang kembali! <span class="inline-block hover:rotate-12 transition-transform cursor-default"></span>
                </h1>
                <p class="text-slate-500 font-medium mt-2 mb-10 leading-relaxed">
                    Lanjutkan rencanamu dan wujudkan ide konten <span class="text-blue-600 font-bold">terbaikmu</span> hari ini.
                </p>

                <button type="button" class="w-full flex items-center justify-center gap-3 bg-white border border-slate-200 hover:border-blue-300 hover:bg-blue-50/50 text-slate-700 font-bold py-3.5 px-4 rounded-2xl transition-all shadow-sm mb-6 group">
                    <img src="https://www.svgrepo.com/show/475656/google-color.svg" class="h-5 w-5 group-hover:scale-110 transition-transform duration-300" alt="Google Logo">
                    Masuk dengan Google
                </button>

                <div class="flex items-center gap-4 mb-6">
                    <div class="h-px bg-slate-200 flex-1"></div>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Atau masuk dengan</span>
                    <div class="h-px bg-slate-200 flex-1"></div>
                </div>

                {{-- Shortcut Pengisian Cepat Akun Demo --}}
                <div class="mb-6 p-4 bg-slate-50 border border-slate-200/80 rounded-2xl">
                    <span class="block text-[11px] font-black uppercase tracking-wider text-slate-400 mb-2.5">⚡ Masuk Cepat Akun Demo (Klik untuk auto-fill):</span>
                    <div class="grid grid-cols-2 gap-2">
                        <button type="button" onclick="document.getElementById('email').value='admin@urplan.test'; document.getElementById('password').value='password123';" class="px-3 py-2.5 bg-white hover:bg-slate-900 hover:text-white border border-slate-200 text-slate-700 rounded-xl text-xs font-bold transition-all shadow-sm flex items-center justify-center gap-1.5 group cursor-pointer">
                            <span>👑</span>
                            <span>Admin UrPlan</span>
                        </button>
                        <button type="button" onclick="document.getElementById('email').value='budi@urplan.test'; document.getElementById('password').value='password123';" class="px-3 py-2.5 bg-white hover:bg-blue-600 hover:text-white border border-slate-200 text-slate-700 rounded-xl text-xs font-bold transition-all shadow-sm flex items-center justify-center gap-1.5 group cursor-pointer">
                            <span>🎨</span>
                            <span>Budi Kreator</span>
                        </button>
                    </div>
                </div>

                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="email" class="block text-sm font-bold text-slate-700 mb-2">Alamat Email</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect width="20" height="16" x="2" y="4" rx="2" />
                                    <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7" /></svg>
                            </div>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="kreator@contoh.com" class="w-full bg-slate-50 border border-slate-200 text-slate-900 pl-11 pr-5 py-3.5 rounded-2xl focus:outline-none focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 focus:bg-white transition-all font-medium placeholder-slate-400">
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-500 text-sm" />
                    </div>

                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <label for="password" class="block text-sm font-bold text-slate-700">Password</label>
                            @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-sm font-bold text-blue-600 hover:text-indigo-600 transition-colors">Lupa password?</a>
                            @endif
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect width="18" height="11" x="3" y="11" rx="2" ry="2" />
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4" /></svg>
                            </div>
                            <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" class="w-full bg-slate-50 border border-slate-200 text-slate-900 pl-11 pr-5 py-3.5 rounded-2xl focus:outline-none focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 focus:bg-white transition-all font-medium placeholder-slate-400">
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-500 text-sm" />
                    </div>

                    <div class="flex items-center mt-4">
                        <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                            <input id="remember_me" type="checkbox" name="remember" class="rounded border-slate-300 text-blue-600 shadow-sm focus:ring-blue-500 w-4 h-4 cursor-pointer transition-all">
                            <span class="ms-2 text-sm text-slate-500 font-medium group-hover:text-slate-800 transition-colors">Ingat saya di perangkat ini</span>
                        </label>
                    </div>

                    <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold py-4 px-4 rounded-2xl transition-all shadow-lg shadow-blue-500/30 hover:shadow-blue-500/50 hover:-translate-y-0.5 mt-2">
                        Masuk ke Workspace
                    </button>
                </form>

                @if (Route::has('register'))
                <p class="text-center mt-8 mb-12  text-sm text-slate-500 font-medium pb-8 lg:pb-0">
                    Belum punya akun? <a href="{{ route('register') }}" class="text-blue-600 font-bold hover:text-indigo-600 hover:underline transition-colors">Daftar sekarang</a>
                </p>
                @endif
            </div>
        </div>

        <div class="hidden lg:flex lg:w-1/2 relative bg-slate-900 items-center justify-center overflow-hidden">
            <img src="https://images.unsplash.com/photo-1555421689-491a97ff2040?q=80&w=2070&auto=format&fit=crop" alt="Ruang kerja estetik" class="absolute inset-0 w-full h-full object-cover opacity-60 mix-blend-overlay">
            <div class="absolute inset-0 bg-gradient-to-tr from-blue-900/90 via-slate-900/60 to-transparent"></div>

            <div class="relative z-10 flex flex-col items-center opacity-20 pointer-events-none">
                <div class="w-32 h-32 bg-white rounded-3xl flex items-center justify-center text-slate-900 font-black text-5xl mb-4 rotate-12">UP</div>
                <span class="font-black text-4xl tracking-widest text-white uppercase">UrPlan</span>
            </div>
        </div>

    </div>
</x-guest-layout>

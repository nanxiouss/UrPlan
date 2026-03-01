<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-2xl text-slate-900 leading-tight">
            {{ __('Profil Saya') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100 flex flex-col md:flex-row items-center gap-8 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-64 h-64 bg-blue-50 rounded-full blur-3xl -z-10 translate-x-1/2 -translate-y-1/2"></div>

                <div class="relative group cursor-pointer">
                    <div class="w-24 h-24 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-3xl flex items-center justify-center text-white font-black text-4xl shadow-lg shadow-blue-500/30 group-hover:scale-105 transition-transform duration-300">
                        {{ substr(Auth::user()->name, 0, 1) }} </div>
                    <div class="absolute inset-0 bg-slate-900/40 rounded-3xl flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity backdrop-blur-sm">
                        <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 16.5v2.5h2.5l11-11-2.5-2.5-11 11zM16.5 4.5l2.5 2.5m-2.5-2.5l-2.5 2.5" /></svg>
                    </div>
                </div>

                <div class="text-center md:text-left flex-1">
                    <h3 class="text-2xl font-black text-slate-900">{{ Auth::user()->name }}</h3>
                    <p class="text-slate-500 font-medium mt-1 flex items-center justify-center md:justify-start gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                        {{ Auth::user()->email }}
                    </p>
                    <div class="mt-4 inline-flex items-center px-3 py-1 rounded-full bg-indigo-50 text-indigo-600 text-xs font-bold uppercase tracking-wide">
                        {{ Auth::user()->role ?? 'Content Creator' }}
                    </div>
                </div>

                <div class="flex gap-6 text-center md:text-right">
                    <div class="bg-slate-50 px-6 py-4 rounded-2xl border border-slate-100">
                        <div class="text-3xl font-black text-slate-900">5<span class="text-lg text-orange-500">🔥</span></div>
                        <div class="text-xs font-bold text-slate-400 uppercase tracking-wider mt-1">Hari Streak</div>
                    </div>
                    <div class="bg-slate-50 px-6 py-4 rounded-2xl border border-slate-100">
                        <div class="text-3xl font-black text-blue-600">12</div>
                        <div class="text-xs font-bold text-slate-400 uppercase tracking-wider mt-1">Total Konten</div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <div class="lg:col-span-2 space-y-8">
                    
                    <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100">
                        <h4 class="text-lg font-bold text-slate-900 mb-1">Informasi Personal</h4>
                        <p class="text-sm text-slate-500 mb-6">Perbarui nama dan alamat email akun workspace kamu.</p>

                        <form method="post" action="#" class="space-y-6">
                            @csrf
                            @method('patch')
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="name" class="block text-sm font-bold text-slate-700 mb-2">Nama Lengkap</label>
                                    <input id="name" name="name" type="text" value="{{ Auth::user()->name }}" class="w-full bg-slate-50 border border-slate-200 text-slate-900 px-5 py-3.5 rounded-2xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all font-medium" required>
                                </div>

                                <div>
                                    <label for="email" class="block text-sm font-bold text-slate-700 mb-2">Alamat Email</label>
                                    <input id="email" name="email" type="email" value="{{ Auth::user()->email }}" class="w-full bg-slate-50 border border-slate-200 text-slate-900 px-5 py-3.5 rounded-2xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all font-medium" required>
                                </div>
                            </div>

                            <div class="flex justify-end pt-4">
                                <button type="submit" class="bg-slate-900 hover:bg-blue-600 text-white font-bold py-3.5 px-8 rounded-2xl transition-all shadow-md hover:shadow-blue-600/25">
                                    Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100">
                        <h4 class="text-lg font-bold text-slate-900 mb-1">Keamanan Akun</h4>
                        <p class="text-sm text-slate-500 mb-6">Pastikan akunmu menggunakan password panjang yang acak agar tetap aman.</p>

                        <form method="post" action="#" class="space-y-6">
                            @csrf
                            @method('put')

                            <div class="space-y-5">
                                <div>
                                    <label for="current_password" class="block text-sm font-bold text-slate-700 mb-2">Password Saat Ini</label>
                                    <input id="current_password" name="current_password" type="password" class="w-full md:w-2/3 bg-slate-50 border border-slate-200 text-slate-900 px-5 py-3.5 rounded-2xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all font-medium">
                                </div>
                                <div>
                                    <label for="password" class="block text-sm font-bold text-slate-700 mb-2">Password Baru</label>
                                    <input id="password" name="password" type="password" class="w-full md:w-2/3 bg-slate-50 border border-slate-200 text-slate-900 px-5 py-3.5 rounded-2xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all font-medium">
                                </div>
                                <div>
                                    <label for="password_confirmation" class="block text-sm font-bold text-slate-700 mb-2">Konfirmasi Password Baru</label>
                                    <input id="password_confirmation" name="password_confirmation" type="password" class="w-full md:w-2/3 bg-slate-50 border border-slate-200 text-slate-900 px-5 py-3.5 rounded-2xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all font-medium">
                                </div>
                            </div>

                            <div class="flex justify-start pt-4">
                                <button type="submit" class="bg-slate-100 hover:bg-slate-200 text-slate-900 font-bold py-3.5 px-8 rounded-2xl transition-all">
                                    Perbarui Password
                                </button>
                            </div>
                        </form>
                    </div>

                </div>

                <div class="space-y-8">
                    
                    <div class="bg-gradient-to-br from-blue-600 to-indigo-600 p-8 rounded-[2rem] shadow-lg shadow-blue-500/20 text-white relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full blur-2xl -z-0 translate-x-1/3 -translate-y-1/3"></div>
                        
                        <h4 class="text-xl font-black mb-2 relative z-10">Riset Ide Konten</h4>
                        <p class="text-blue-100 text-sm font-medium mb-6 relative z-10">Target habit harianmu berjalan sangat lancar!</p>
                        
                        <div class="flex items-end gap-3 relative z-10">
                            <span class="text-5xl font-black">5</span>
                            <span class="text-blue-200 font-bold mb-1">Hari Beruntun</span>
                        </div>

                        <button class="mt-6 w-full bg-white/20 hover:bg-white/30 text-white font-bold py-3 px-4 rounded-xl transition-all backdrop-blur-sm border border-white/10 relative z-10">
                            Tandai Selesai Hari Ini
                        </button>
                    </div>

                    <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100">
                        <h4 class="text-lg font-bold text-slate-900 mb-4">Platform Terhubung</h4>
                        
                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-4 rounded-2xl border border-slate-100 bg-slate-50">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center text-red-600">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-900 text-sm">YouTube</div>
                                        <div class="text-xs text-green-500 font-bold">Terhubung</div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center justify-between p-4 rounded-2xl border border-slate-100 bg-slate-50 opacity-60">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-slate-200 rounded-xl flex items-center justify-center text-slate-600">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64 2.93 2.93 0 0 1 .88.13V9.4a6.84 6.84 0 0 0-1-.05A6.33 6.33 0 0 0 5 20.1a6.34 6.34 0 0 0 10.86-4.43v-7a8.16 8.16 0 0 0 4.77 1.52v-3.4a4.85 4.85 0 0 1-1-.1z"/></svg>
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-900 text-sm">TikTok</div>
                                        <div class="text-xs text-slate-400 font-bold">Belum ditambahkan</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
</x-app-layout>
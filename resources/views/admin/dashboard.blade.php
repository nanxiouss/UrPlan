<x-app-layout>
    <div class="py-8 bg-[#F8FAFC] min-h-screen font-sans">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-6 py-4 rounded-2xl relative flex items-center gap-3 shadow-sm">
                <span class="text-xl">✅</span>
                <span class="font-bold text-sm">{{ session('success') }}</span>
            </div>
            @endif

            <div class="bg-slate-900 rounded-[2.5rem] p-10 shadow-xl shadow-slate-900/10 border border-slate-800 flex flex-col justify-center relative overflow-hidden text-white">
                <div class="absolute -right-20 -top-20 w-80 h-80 bg-blue-600 rounded-full blur-[100px] opacity-30"></div>
                <div class="absolute -left-10 -bottom-10 w-64 h-64 bg-emerald-500 rounded-full blur-[80px] opacity-20"></div>
                
                <div class="relative z-10">
                    <div class="inline-flex items-center gap-2 bg-slate-800/50 backdrop-blur-sm text-slate-300 px-4 py-1.5 rounded-full mb-6 border border-slate-700">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse shadow-[0_0_8px_#34d399]"></span>
                        <span class="text-[10px] font-black tracking-widest uppercase">System Online • {{ now()->translatedFormat('d F Y') }}</span>
                    </div>

                    <h2 class="text-4xl md:text-5xl font-black leading-tight tracking-tight">
                        Admin Control Center ⚡
                    </h2>
                    
                    <p class="text-slate-400 mt-4 text-lg font-medium max-w-2xl leading-relaxed">
                        Selamat bekerja, <span class="text-white">{{ Auth::user()->name }}</span>. Pantau aktivitas sistem, kelola data pengguna, dan pastikan platform UrPlan berjalan optimal hari ini.
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white rounded-[2rem] p-6 shadow-sm border border-slate-100 flex items-center gap-5 hover:shadow-md hover:border-blue-200 transition-all group">
                    <div class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-[10px] uppercase tracking-widest text-slate-400 font-black mb-1">Total Pengguna</p>
                        <h3 class="text-4xl font-black text-slate-800 tracking-tight">{{ $totalUsers ?? 0 }}</h3>
                    </div>
                </div>

                <div class="bg-white rounded-[2rem] p-6 shadow-sm border border-slate-100 flex items-center gap-5 hover:shadow-md hover:border-emerald-200 transition-all group">
                    <div class="w-16 h-16 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-600 group-hover:bg-emerald-500 group-hover:text-white transition-colors">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                    </div>
                    <div>
                        <p class="text-[10px] uppercase tracking-widest text-slate-400 font-black mb-1">Task Dibuat (Global)</p>
                        <h3 class="text-4xl font-black text-slate-800 tracking-tight">{{ $totalTasks ?? 0 }}</h3>
                    </div>
                </div>

                <div class="bg-white rounded-[2rem] p-6 shadow-sm border border-slate-100 flex items-center gap-5">
                    <div class="w-16 h-16 bg-purple-50 rounded-2xl flex items-center justify-center text-purple-600">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"></path></svg>
                    </div>
                    <div>
                        <p class="text-[10px] uppercase tracking-widest text-slate-400 font-black mb-1">Status Server</p>
                        <h3 class="text-2xl font-black text-emerald-500">Aman 🟢</h3>
                        <p class="text-xs text-slate-400 font-medium mt-0.5">Database berjalan normal</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-8">
                
                <div class="lg:col-span-2 bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden flex flex-col">
                    <div class="p-8 border-b border-slate-100 flex justify-between items-center bg-white">
                        <div>
                            <h3 class="text-xl font-black text-slate-800">Pengguna Baru mendaftar</h3>
                            <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mt-1">5 Pendaftar Terakhir</p>
                        </div>
                        <button class="bg-slate-50 hover:bg-slate-100 text-slate-600 px-4 py-2 rounded-xl text-xs font-bold transition-colors">
                            Lihat Semua
                        </button>
                    </div>
                    
                    <div class="overflow-x-auto w-full">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50/50 text-slate-400 text-[10px] uppercase tracking-widest">
                                    <th class="p-5 font-black">Informasi Pengguna</th>
                                    <th class="p-5 font-black">Status Email</th>
                                    <th class="p-5 font-black">Tgl Mendaftar</th>
                                    <th class="p-5 font-black text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm">
                                @forelse($recentUsers as $user)
                                <tr class="border-b border-slate-50 hover:bg-slate-50/80 transition-colors group">
                                    <td class="p-5">
                                        <div class="font-bold text-slate-800 text-sm group-hover:text-blue-600 transition-colors">{{ $user->name }}</div>
                                        <div class="text-xs text-slate-500 font-medium mt-0.5">{{ $user->email }}</div>
                                    </td>
                                    <td class="p-5">
                                        @if($user->email_verified_at)
                                            <span class="bg-emerald-50 text-emerald-600 px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider border border-emerald-100">Terverifikasi</span>
                                        @else
                                            <span class="bg-amber-50 text-amber-600 px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider border border-amber-100">Pending</span>
                                        @endif
                                    </td>
                                    <td class="p-5 text-slate-600 font-medium text-xs">
                                        {{ $user->created_at->translatedFormat('d M Y') }}
                                        <div class="text-[10px] text-slate-400 mt-0.5">{{ $user->created_at->diffForHumans() }}</div>
                                    </td>
                                    <td class="p-5 text-center">
                                        <button class="text-slate-400 hover:text-blue-600 transition-colors p-2 rounded-lg hover:bg-blue-50" title="Lihat Detail">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="p-8 text-center text-slate-400 font-medium">
                                        <span class="text-3xl block mb-2">👻</span>
                                        Belum ada pengguna yang mendaftar.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100 flex flex-col">
                    <div>
                        <h3 class="text-xl font-black text-slate-800 mb-1">Panel Kontrol</h3>
                        <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mb-6">Aksi Cepat Admin</p>
                    </div>
                    
                    <div class="space-y-3 flex-1">
                        <button class="w-full bg-blue-50 hover:bg-blue-600 hover:text-white group border border-blue-100 text-blue-700 px-5 py-4 rounded-2xl text-sm font-bold transition-all flex items-center gap-4 text-left shadow-sm">
                            <span class="w-10 h-10 rounded-xl bg-white text-blue-500 group-hover:text-blue-600 flex items-center justify-center shadow-sm transition-colors">📢</span>
                            <div>
                                <span class="block text-sm">Broadcast Pesan</span>
                                <span class="block text-[10px] font-medium opacity-70 mt-0.5">Kirim info ke semua user</span>
                            </div>
                        </button>
                        
                        <button class="w-full bg-slate-50 hover:bg-slate-800 hover:text-white group border border-slate-200 text-slate-700 px-5 py-4 rounded-2xl text-sm font-bold transition-all flex items-center gap-4 text-left">
                            <span class="w-10 h-10 rounded-xl bg-white text-slate-500 flex items-center justify-center shadow-sm">⚙️</span>
                            <div>
                                <span class="block text-sm">Pengaturan Sistem</span>
                                <span class="block text-[10px] font-medium opacity-70 mt-0.5">Limitasi & Pricing</span>
                            </div>
                        </button>

                        <div class="pt-6 mt-6 border-t border-slate-100">
                            <div class="bg-rose-50 rounded-[1.5rem] p-5 border border-rose-100 relative overflow-hidden">
                                <div class="absolute right-0 top-0 w-24 h-24 bg-rose-500/10 rounded-full blur-xl"></div>
                                <h4 class="text-rose-800 font-black text-sm mb-1 flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-rose-500 animate-ping"></span>
                                    Maintenance Mode
                               </h4>
                                <p class="text-rose-600 text-[11px] font-medium mb-4 leading-relaxed relative z-10">Tutup akses login user sementara waktu untuk perbaikan server/database.</p>
                                <button class="w-full bg-rose-600 hover:bg-rose-700 text-white px-4 py-2.5 rounded-xl text-xs font-black uppercase tracking-wider transition-all shadow-lg shadow-rose-500/30 relative z-10">
                                    Aktifkan Mode
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
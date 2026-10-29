<x-app-layout>
    <div x-data="{
        showBroadcastModal: false,
        showAllUsersModal: false,
        showDetailUserModal: false,
        showSettingsModal: false,
        searchUserQuery: '',
        selectedUser: {
            id: null,
            name: '',
            email: '',
            email_verified_at: null,
            created_at: '',
            projects_count: 0,
            habits_count: 0,
            projects: []
        },
        openDetail(user) {
            this.selectedUser = user;
            this.showDetailUserModal = true;
        },
        matchesUser(name, email) {
            if (!this.searchUserQuery) return true;
            const q = this.searchUserQuery.toLowerCase();
            return name.toLowerCase().includes(q) || email.toLowerCase().includes(q);
        }
    }" class="py-8 bg-[#F8FAFC] min-h-screen font-sans">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Flash Message --}}
            @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-6 py-4 rounded-2xl relative flex items-center justify-between shadow-sm animate-fade-in">
                <div class="flex items-center gap-3">
                    <span class="text-xl">✅</span>
                    <span class="font-bold text-sm">{{ session('success') }}</span>
                </div>
                <button type="button" @click="$el.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            @endif

            @if(session('error'))
            <div class="bg-rose-50 border border-rose-200 text-rose-700 px-6 py-4 rounded-2xl relative flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-3">
                    <span class="text-xl">⚠️</span>
                    <span class="font-bold text-sm">{{ session('error') }}</span>
                </div>
                <button type="button" @click="$el.parentElement.remove()" class="text-rose-500 hover:text-rose-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            @endif

            {{-- Hero Banner --}}
            <div class="bg-slate-900 rounded-[2.5rem] p-10 shadow-xl shadow-slate-900/10 border border-slate-800 flex flex-col justify-center relative overflow-hidden text-white">
                <div class="absolute -right-20 -top-20 w-80 h-80 bg-blue-600 rounded-full blur-[100px] opacity-30"></div>
                <div class="absolute -left-10 -bottom-10 w-64 h-64 bg-emerald-500 rounded-full blur-[80px] opacity-20"></div>
                
                <div class="relative z-10">
                    <div class="inline-flex items-center gap-2 bg-slate-800/50 backdrop-blur-sm text-slate-300 px-4 py-1.5 rounded-full mb-6 border border-slate-700">
                        @if($isMaintenance ?? false)
                        <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse shadow-[0_0_8px_#fbbf24]"></span>
                        <span class="text-[10px] font-black tracking-widest uppercase text-amber-300">Maintenance Mode Aktif • {{ now()->translatedFormat('d F Y') }}</span>
                        @else
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse shadow-[0_0_8px_#34d399]"></span>
                        <span class="text-[10px] font-black tracking-widest uppercase">System Online • {{ now()->translatedFormat('d F Y') }}</span>
                        @endif
                    </div>

                    <h2 class="text-4xl md:text-5xl font-black leading-tight tracking-tight">
                        Admin Control Center ⚡
                    </h2>
                    
                    <p class="text-slate-400 mt-4 text-lg font-medium max-w-2xl leading-relaxed">
                        Selamat bekerja, <span class="text-white">{{ Auth::user()->name }}</span>. Pantau aktivitas sistem, kelola data pengguna, dan broadcast pengumuman ke seluruh kreator Our Plan.
                    </p>
                </div>
            </div>

            {{-- Stats Cards --}}
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
                        @if($isMaintenance ?? false)
                        <h3 class="text-2xl font-black text-amber-500">Maintenance 🟡</h3>
                        <p class="text-xs text-slate-400 font-medium mt-0.5">Akses terbatas</p>
                        @else
                        <h3 class="text-2xl font-black text-emerald-500">Aman 🟢</h3>
                        <p class="text-xs text-slate-400 font-medium mt-0.5">Database berjalan normal</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Users Table & Quick Controls --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-8">
                
                {{-- Recent Users Table --}}
                <div class="lg:col-span-2 bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden flex flex-col">
                    <div class="p-8 border-b border-slate-100 flex justify-between items-center bg-white">
                        <div>
                            <h3 class="text-xl font-black text-slate-800">Pengguna Baru Mendaftar</h3>
                            <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mt-1">5 Pendaftar Terakhir</p>
                        </div>
                        <button @click="showAllUsersModal = true" class="bg-slate-50 hover:bg-slate-100 text-slate-600 px-4 py-2 rounded-xl text-xs font-bold transition-colors">
                            Lihat Semua ({{ isset($allUsers) ? count($allUsers) : 0 }})
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
                                        <button @click="openDetail({{ json_encode($user) }})" class="text-slate-400 hover:text-blue-600 transition-colors p-2 rounded-lg hover:bg-blue-50" title="Lihat Detail">
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

                {{-- Quick Actions --}}
                <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100 flex flex-col">
                    <div>
                        <h3 class="text-xl font-black text-slate-800 mb-1">Panel Kontrol</h3>
                        <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mb-6">Aksi Cepat Admin</p>
                    </div>
                    
                    <div class="space-y-3 flex-1">
                        <button @click="showBroadcastModal = true" class="w-full bg-blue-50 hover:bg-blue-600 hover:text-white group border border-blue-100 text-blue-700 px-5 py-4 rounded-2xl text-sm font-bold transition-all flex items-center gap-4 text-left shadow-sm">
                            <span class="w-10 h-10 rounded-xl bg-white text-blue-500 group-hover:text-blue-600 flex items-center justify-center shadow-sm transition-colors">📢</span>
                            <div>
                                <span class="block text-sm">Broadcast Pesan</span>
                                <span class="block text-[10px] font-medium opacity-70 mt-0.5">Kirim info pengumuman ke user</span>
                            </div>
                        </button>
                        
                        <button @click="showAllUsersModal = true" class="w-full bg-slate-50 hover:bg-slate-800 hover:text-white group border border-slate-200 text-slate-700 px-5 py-4 rounded-2xl text-sm font-bold transition-all flex items-center gap-4 text-left">
                            <span class="w-10 h-10 rounded-xl bg-white text-slate-500 flex items-center justify-center shadow-sm">👥</span>
                            <div>
                                <span class="block text-sm">Manajemen Pengguna</span>
                                <span class="block text-[10px] font-medium opacity-70 mt-0.5">Kelola & pantau akun user</span>
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
                                
                                <form action="{{ route('admin.maintenance') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full {{ ($isMaintenance ?? false) ? 'bg-slate-800 hover:bg-slate-900' : 'bg-rose-600 hover:bg-rose-700' }} text-white px-4 py-2.5 rounded-xl text-xs font-black uppercase tracking-wider transition-all shadow-lg shadow-rose-500/30 relative z-10">
                                        {{ ($isMaintenance ?? false) ? 'Matikan Mode Maintenance' : 'Aktifkan Mode Maintenance' }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- MODAL 1: Broadcast Pesan --}}
        <div x-show="showBroadcastModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto">
            <div x-show="showBroadcastModal" x-transition.opacity class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm" @click="showBroadcastModal = false"></div>

            <div class="flex min-h-full items-center justify-center p-4 text-center">
                <div x-show="showBroadcastModal" x-transition class="relative w-full max-w-lg transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all">
                    <div class="bg-white px-6 py-6 sm:px-8 sm:py-7">
                        <div class="flex justify-between items-center mb-5">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-blue-600"></span>
                                <h3 class="text-xl font-black text-slate-900">Broadcast Pengumuman</h3>
                            </div>
                            <button @click="showBroadcastModal = false" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-xl hover:bg-slate-100 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>

                        <form action="{{ route('admin.broadcast') }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Target Penerima</label>
                                <select name="target" class="w-full bg-slate-50 border border-slate-200 text-slate-900 px-4 py-2.5 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                                    <option value="all">📢 Semua Pengguna Terdaftar</option>
                                    @if(isset($allUsers))
                                    @foreach($allUsers as $u)
                                    <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Judul Pengumuman <span class="text-red-500">*</span></label>
                                <input type="text" name="title" required class="w-full bg-slate-50 border border-slate-200 text-slate-900 px-4 py-2.5 rounded-xl text-sm focus:ring-2 focus:ring-blue-500" placeholder="Contoh: Pembaruan Fitur Baru UrPlan!">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Isi Pesan <span class="text-red-500">*</span></label>
                                <textarea name="message" rows="4" required class="w-full bg-slate-50 border border-slate-200 text-slate-900 px-4 py-2.5 rounded-xl text-sm focus:ring-2 focus:ring-blue-500" placeholder="Tulis pesan lengkap yang akan tampil di dashboard pengguna..."></textarea>
                            </div>

                            <div class="mt-6 flex justify-end gap-2 pt-4 border-t border-slate-100">
                                <button type="button" @click="showBroadcastModal = false" class="px-5 py-2.5 rounded-xl font-bold text-slate-500 text-sm hover:bg-slate-100 transition-colors">Batal</button>
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-xl text-sm font-bold shadow-md shadow-blue-500/20 transition-all">
                                    Kirim Broadcast 🚀
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- MODAL 2: Lihat Semua Pengguna --}}
        <div x-show="showAllUsersModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto">
            <div x-show="showAllUsersModal" x-transition.opacity class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm" @click="showAllUsersModal = false"></div>

            <div class="flex min-h-full items-center justify-center p-4 text-center">
                <div x-show="showAllUsersModal" x-transition class="relative w-full max-w-3xl transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all">
                    <div class="bg-white px-6 py-6 sm:px-8 sm:py-7">
                        <div class="flex justify-between items-center mb-5">
                            <div>
                                <h3 class="text-xl font-black text-slate-900">Daftar Pengguna Terdaftar</h3>
                                <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mt-0.5">Kelola seluruh pengguna aktif UrPlan</p>
                            </div>
                            <button @click="showAllUsersModal = false" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-xl hover:bg-slate-100 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>

                        {{-- Search Filter --}}
                        <div class="mb-4">
                            <input type="text" x-model="searchUserQuery" placeholder="Cari nama atau email pengguna..." class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-xs rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div class="overflow-x-auto max-h-96 custom-scrollbar border border-slate-100 rounded-2xl">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-slate-50 text-slate-400 text-[10px] uppercase tracking-widest sticky top-0">
                                        <th class="p-4 font-black">User</th>
                                        <th class="p-4 font-black">Status</th>
                                        <th class="p-4 font-black">Projects / Habits</th>
                                        <th class="p-4 font-black text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="text-sm">
                                    @if(isset($allUsers))
                                    @foreach($allUsers as $u)
                                    <tr x-show="matchesUser('{{ addslashes($u->name) }}', '{{ addslashes($u->email) }}')" class="border-b border-slate-50 hover:bg-slate-50/80 transition-colors">
                                        <td class="p-4">
                                            <div class="font-bold text-slate-800 text-xs">{{ $u->name }}</div>
                                            <div class="text-[11px] text-slate-400">{{ $u->email }}</div>
                                        </td>
                                        <td class="p-4">
                                            @if($u->email_verified_at)
                                                <span class="bg-emerald-50 text-emerald-600 px-2 py-0.5 rounded text-[9px] font-black uppercase tracking-wider">Terverifikasi</span>
                                            @else
                                                <span class="bg-amber-50 text-amber-600 px-2 py-0.5 rounded text-[9px] font-black uppercase tracking-wider">Pending</span>
                                            @endif
                                        </td>
                                        <td class="p-4 text-xs font-semibold text-slate-600">
                                            <span>{{ $u->projects_count }} Proj</span> • <span>{{ $u->habits_count }} Habit</span>
                                        </td>
                                        <td class="p-4 text-center">
                                            <div class="flex items-center justify-center gap-1">
                                                <button @click="openDetail({{ json_encode($u) }}); showAllUsersModal = false;" class="text-blue-600 hover:text-blue-800 p-1.5 rounded-lg hover:bg-blue-50 transition-colors" title="Lihat Detail">
                                                    👁️
                                                </button>
                                                <form action="{{ route('admin.users.destroy', $u->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pengguna {{ $u->name }}? Seluruh data project dan task miliknya akan dihapus.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-rose-600 hover:text-rose-800 p-1.5 rounded-lg hover:bg-rose-50 transition-colors" title="Hapus User">
                                                        🗑️
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-5 flex justify-end">
                            <button type="button" @click="showAllUsersModal = false" class="px-5 py-2.5 rounded-xl font-bold text-slate-500 text-sm hover:bg-slate-100 transition-colors">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- MODAL 3: Detail Pengguna --}}
        <div x-show="showDetailUserModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto">
            <div x-show="showDetailUserModal" x-transition.opacity class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm" @click="showDetailUserModal = false"></div>

            <div class="flex min-h-full items-center justify-center p-4 text-center">
                <div x-show="showDetailUserModal" x-transition class="relative w-full max-w-md transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all">
                    <div class="bg-white px-6 py-6 sm:px-8 sm:py-7">
                        <div class="flex justify-between items-center mb-5">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                                <h3 class="text-xl font-black text-slate-900">Detail Pengguna</h3>
                            </div>
                            <button @click="showDetailUserModal = false" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-xl hover:bg-slate-100 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>

                        <div class="space-y-4 text-sm">
                            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 space-y-2">
                                <div>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Nama Lengkap</span>
                                    <h4 class="font-black text-slate-800 text-base" x-text="selectedUser.name"></h4>
                                </div>
                                <div>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Alamat Email</span>
                                    <p class="font-medium text-slate-600" x-text="selectedUser.email"></p>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div class="bg-blue-50/50 p-3.5 rounded-2xl border border-blue-100">
                                    <span class="text-[10px] font-bold text-blue-400 uppercase">Total Project</span>
                                    <p class="text-2xl font-black text-blue-700" x-text="selectedUser.projects_count ?? 0"></p>
                                </div>
                                <div class="bg-emerald-50/50 p-3.5 rounded-2xl border border-emerald-100">
                                    <span class="text-[10px] font-bold text-emerald-400 uppercase">Daily Habits</span>
                                    <p class="text-2xl font-black text-emerald-700" x-text="selectedUser.habits_count ?? 0"></p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-between items-center pt-4 border-t border-slate-100">
                            <form :action="'/admin/users/' + selectedUser.id" method="POST" onsubmit="return confirm('Hapus user ini secara permanen?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-rose-600 hover:text-rose-700 hover:bg-rose-50 px-3.5 py-2 rounded-xl text-xs font-bold transition-colors">
                                    🗑️ Hapus User
                                </button>
                            </form>

                            <button type="button" @click="showDetailUserModal = false" class="bg-slate-900 hover:bg-slate-800 text-white px-5 py-2 rounded-xl text-xs font-bold transition-all">
                                Tutup
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            height: 6px;
            width: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #CBD5E1;
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94A3B8;
        }
    </style>
</x-app-layout>
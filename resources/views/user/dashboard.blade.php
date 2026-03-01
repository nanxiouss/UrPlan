<x-app-layout>
    <div x-data="{ showModal: false, taskStatus: 'Idea' }" class="py-8 bg-slate-50 min-h-screen">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-2xl relative flex items-center gap-3 shadow-sm">
                <span class="text-2xl">✅</span>
                <span class="font-bold">{{ session('success') }}</span>
            </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <div class="lg:col-span-2 bg-white rounded-3xl p-8 shadow-sm border border-slate-100 flex flex-col justify-center relative overflow-hidden">
                    <div class="absolute -right-10 -top-10 w-64 h-64 bg-blue-50 rounded-full blur-3xl opacity-70"></div>
                    <div class="relative z-10">
                        <div class="inline-flex items-center gap-2 bg-blue-50 text-blue-600 px-3 py-1 rounded-full mb-4">
                            <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span>
                            <span class="text-xs font-bold tracking-wider uppercase">{{ now()->translatedFormat('l, d F Y') }}</span>
                        </div>

                        @php
                        $hour = now()->timezone('Asia/Jakarta')->format('H');
                        if ($hour < 12) { $greeting='Selamat Pagi' ; } elseif ($hour < 15) { $greeting='Selamat Siang' ; } elseif ($hour < 18) { $greeting='Selamat Sore' ; } else { $greeting='Selamat Malam' ; } @endphp <h2 class="text-3xl md:text-4xl font-black text-slate-800 leading-tight">
                            {{ $greeting }}, <span class="text-blue-600">{{ Auth::user()->name }}</span>
                            </h2>

                            @if(Auth::user()->name === 'Admin UrPlan' || Auth::user()->email === 'admin@urplan.com')
                            <p class="text-slate-500 mt-2 font-medium max-w-lg leading-relaxed">
                                Selamat datang di pusat kendali UrPlan. Pantau aktivitas sistem, kelola data pengguna, dan pastikan platform berjalan optimal hari ini. 🚀
                            </p>
                            @else
                            <p class="text-slate-500 mt-2 font-medium max-w-lg leading-relaxed">
                                Ayo wujudkan ide kontenmu hari ini. Konsistensi adalah kunci dari kesuksesan seorang kreator! 💡
                            </p>
                            @endif
                    </div>
                </div>

                <div class="w-full bg-white rounded-3xl p-6 shadow-sm border border-slate-100 flex flex-col">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Daily Habit Tracker</h3>
                    @forelse($habits as $habit)
                    <div class="bg-blue-600 p-6 rounded-2xl text-white shadow-md shadow-blue-500/20 relative overflow-hidden flex-1 flex flex-col justify-between">
                        <div class="flex justify-between items-start mb-4">
                            <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center text-xl">🔥</div>
                            <button class="bg-white/20 hover:bg-white/30 text-white text-xs font-bold px-3 py-1.5 rounded-lg transition-colors">
                                Check-in
                            </button>
                        </div>
                        <div>
                            <h4 class="text-xl font-black mb-1">{{ $habit->name }}</h4>
                            <div class="flex items-end gap-2 mt-2">
                                <span class="text-4xl font-black">{{ $habit->streak_count }}</span>
                                <span class="text-blue-200 font-bold mb-1 text-sm">Hari Beruntun</span>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="bg-slate-50 p-6 rounded-2xl text-center border-2 border-dashed border-slate-200 flex-1 flex flex-col items-center justify-center">
                        <span class="text-3xl mb-2">🌱</span>
                        <h4 class="font-bold text-slate-700 text-sm">Belum ada Habit</h4>
                        <p class="text-xs text-slate-400 mt-1">Buat habit pertamamu nanti.</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="md:col-span-2 bg-white rounded-3xl p-6 shadow-sm border border-slate-100 flex items-center gap-5">
                    <div class="w-14 h-14 bg-slate-800 rounded-2xl flex items-center justify-center text-white">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-[10px] uppercase tracking-widest text-slate-400 font-black mb-0.5">Active Project</p>
                        <h3 class="text-lg font-bold text-slate-800">{{ $project ? $project->name : 'Belum Ada Project' }}</h3>
                        <p class="text-xs text-slate-500">{{ $project ? $project->description : 'Buat project baru untuk memulai.' }}</p>
                    </div>
                </div>

                <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 border-b-4 border-b-red-500 flex justify-between items-center">
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">YouTube</p>
                        <h3 class="text-3xl font-black text-slate-800">{{ isset($platformStats['YouTube']) ? $platformStats['YouTube'] : 0 }}</h3>
                    </div>
                    <div class="w-10 h-10 bg-red-50 text-red-500 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z" /></svg>
                    </div>
                </div>

                <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 border-b-4 border-b-blue-500 flex justify-between items-center">
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Total Task</p>
                        <h3 class="text-3xl font-black text-slate-800">{{ $project ? $project->tasks->count() : 0 }}</h3>
                    </div>
                    <div class="w-10 h-10 bg-blue-50 text-blue-500 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="mt-8 bg-white p-6 rounded-3xl shadow-sm border border-slate-100">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h3 class="text-xl font-black text-slate-800">Content Pipeline</h3>
                        <p class="text-slate-500 text-xs font-medium mt-1">Geser untuk melihat status.</p>
                    </div>
                    <button @click="showModal = true; taskStatus = 'Idea'" class="bg-slate-900 hover:bg-blue-600 text-white px-5 py-2.5 rounded-xl font-bold text-sm transition-colors flex items-center gap-2">
                        <span>+ Task Baru</span>
                    </button>
                </div>

                <div class="flex overflow-x-auto space-x-4 pb-6 custom-scrollbar">
                    @php $statuses = ['Idea', 'Draft', 'In Progress', 'Review', 'Scheduled', 'Published']; @endphp

                    @foreach($statuses as $status)
                    <div class="flex-shrink-0 w-72 flex flex-col">
                        <div class="flex items-center justify-between mb-3 px-1">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>
                                <h3 class="font-bold text-slate-700 text-sm">{{ $status }}</h3>
                            </div>
                            <span class="bg-slate-100 text-slate-600 text-xs font-black px-2 py-0.5 rounded-md">
                                {{ isset($tasksByStatus[$status]) ? count($tasksByStatus[$status]) : 0 }}
                            </span>
                        </div>

                        <div class="bg-slate-50 rounded-2xl p-3 flex-1 border border-slate-100 min-h-[150px] flex flex-col gap-3">
                            @if(isset($tasksByStatus[$status]))
                            @foreach($tasksByStatus[$status] as $task)
                            <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-200 hover:border-blue-400 cursor-pointer transition-colors">
                                <div class="flex justify-between items-start mb-2">
                                    <span class="text-[9px] font-bold uppercase tracking-wider px-2 py-1 rounded bg-slate-100 text-slate-600">
                                        {{ $task->platform_distribution }}
                                    </span>
                                    @if($task->priority == 'High')
                                    <span class="text-[9px] font-bold text-red-600 bg-red-50 px-1.5 py-0.5 rounded">HIGH</span>
                                    @endif
                                </div>
                                <h4 class="font-bold text-slate-800 text-sm leading-snug">{{ $task->title }}</h4>
                            </div>
                            @endforeach
                            @endif

                            <button @click="showModal = true; taskStatus = '{{ $status }}'" class="w-full py-3 rounded-xl border-2 border-dashed border-slate-200 text-slate-400 font-bold text-xs hover:border-blue-400 hover:text-blue-500 transition-colors mt-auto">
                                + Add Task
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div x-show="showModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto">
            <div x-show="showModal" x-transition.opacity class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm" @click="showModal = false"></div>

            <div class="flex min-h-full items-center justify-center p-4 text-center">
                <div x-show="showModal" x-transition class="relative w-full max-w-lg transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all">
                    <div class="bg-white px-6 py-6">
                        <div class="flex justify-between items-center mb-5">
                            <h3 class="text-xl font-black text-slate-900">Buat Task Baru</h3>
                            <button @click="showModal = false" class="text-slate-400 hover:text-slate-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <form action="{{ route('tasks.store') }}" method="POST" class="space-y-4">
                            @csrf
                            <input type="hidden" name="project_id" value="{{ $project ? $project->id : '' }}">
                            <input type="hidden" name="status" x-model="taskStatus">

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Judul Konten <span class="text-red-500">*</span></label>
                                <input type="text" name="title" required class="w-full bg-slate-50 border border-slate-200 text-slate-900 px-4 py-2.5 rounded-xl text-sm focus:ring-2 focus:ring-blue-500" placeholder="Contoh: Review Laptop XYZ">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Platform</label>
                                <select name="platform_distribution" class="w-full bg-slate-50 border border-slate-200 text-slate-900 px-4 py-2.5 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                                    <option value="YouTube">YouTube</option>
                                    <option value="TikTok">TikTok</option>
                                    <option value="Instagram">Instagram</option>
                                    <option value="Blog">Blog</option>
                                </select>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Status Kolom</label>
                                    <input type="text" disabled x-model="taskStatus" class="w-full bg-slate-100 border border-slate-200 text-slate-500 px-4 py-2.5 rounded-xl text-sm font-bold cursor-not-allowed">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Prioritas</label>
                                    <select name="priority" class="w-full bg-slate-50 border border-slate-200 text-slate-900 px-4 py-2.5 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                                        <option value="Low">Low</option>
                                        <option value="Medium" selected>Medium</option>
                                        <option value="High">High</option>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Deskripsi</label>
                                <textarea name="description" rows="2" class="w-full bg-slate-50 border border-slate-200 text-slate-900 px-4 py-2.5 rounded-xl text-sm focus:ring-2 focus:ring-blue-500" placeholder="Opsional..."></textarea>
                            </div>

                            <div class="mt-6 flex justify-end gap-2 pt-4 border-t border-slate-100">
                                <button type="button" @click="showModal = false" class="px-5 py-2.5 rounded-xl font-bold text-slate-500 text-sm hover:bg-slate-100">Batal</button>
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-xl text-sm font-bold shadow-md shadow-blue-500/20">
                                    Simpan Task
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            height: 6px;
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

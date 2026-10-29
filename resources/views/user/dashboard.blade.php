<x-app-layout>
    <div x-data="{
        showModal: false,
        showEditModal: false,
        showProjectModal: false,
        showEditProjectModal: false,
        showHabitModal: false,
        showManageHabitModal: false,
        showTeamModal: false,
        taskStatus: 'Idea',
        filterPlatform: 'all',
        filterPriority: 'all',
        filterDeadline: 'all',
        filterAssignee: 'all',
        searchQuery: '',
        activeProjectData: {
            id: {{ $project ? $project->id : 'null' }},
            name: '{{ $project ? addslashes($project->name) : '' }}',
            description: '{{ $project ? addslashes($project->description ?? '') : '' }}',
            platform: '{{ $project ? addslashes($project->platform ?? 'Multi-Platform') : 'Multi-Platform' }}'
        },
        activeHabit: { id: null, name: '' },
        openManageHabitModal(habit) {
            this.activeHabit = { id: habit.id, name: habit.name };
            this.showManageHabitModal = true;
        },
        activeTask: {
            id: null,
            title: '',
            platform_distribution: 'YouTube',
            priority: 'Medium',
            status: 'Idea',
            deadline: '',
            description: '',
            assignee_ids: []
        },
        openEditModal(task) {
            this.activeTask = {
                id: task.id,
                title: task.title,
                platform_distribution: task.platform_distribution,
                priority: task.priority,
                status: task.status,
                deadline: task.deadline ? task.deadline.substring(0, 10) : '',
                description: task.description || '',
                assignee_ids: task.assignees ? task.assignees.map(a => a.id) : []
            };
            this.showEditModal = true;
        },
        draggedTaskId: null,
        dragOverStatus: null,
        onDragStart(event, taskId) {
            this.draggedTaskId = taskId;
            event.dataTransfer.setData('text/plain', taskId);
            event.dataTransfer.effectAllowed = 'move';
        },
        onDragOver(event, status) {
            event.preventDefault();
            this.dragOverStatus = status;
            event.dataTransfer.dropEffect = 'move';
        },
        onDragLeave(event, status) {
            if (this.dragOverStatus === status) {
                this.dragOverStatus = null;
            }
        },
        async onDrop(event, newStatus) {
            event.preventDefault();
            this.dragOverStatus = null;
            const taskId = this.draggedTaskId || event.dataTransfer.getData('text/plain');
            if (!taskId) return;

            try {
                const response = await fetch(`/tasks/${taskId}/status`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ status: newStatus })
                });
                const result = await response.json();
                if (result.success) {
                    window.location.reload();
                }
            } catch (error) {
                console.error('Gagal memperbarui status task:', error);
            }
        },
        matchesFilter(title, platform, priority, deadlineStr, status, assigneeIds = []) {
            const matchesQuery = this.searchQuery === '' || title.toLowerCase().includes(this.searchQuery.toLowerCase());
            const matchesPlatform = this.filterPlatform === 'all' || platform.toLowerCase() === this.filterPlatform.toLowerCase();
            const matchesPriority = this.filterPriority === 'all' || priority.toLowerCase() === this.filterPriority.toLowerCase();

            let matchesDeadline = true;
            if (this.filterDeadline !== 'all') {
                if (!deadlineStr) {
                    matchesDeadline = false;
                } else {
                    const todayStr = new Date().toISOString().substring(0, 10);
                    const taskDate = deadlineStr.substring(0, 10);
                    if (this.filterDeadline === 'overdue') {
                        matchesDeadline = taskDate < todayStr && status !== 'Published';
                    } else if (this.filterDeadline === 'today') {
                        matchesDeadline = taskDate === todayStr;
                    } else if (this.filterDeadline === 'upcoming') {
                        matchesDeadline = taskDate > todayStr;
                    }
                }
            }

            let matchesAssignee = true;
            if (this.filterAssignee === 'my') {
                matchesAssignee = assigneeIds.includes({{ Auth::id() }});
            } else if (this.filterAssignee !== 'all') {
                matchesAssignee = assigneeIds.includes(parseInt(this.filterAssignee));
            }

            return matchesQuery && matchesPlatform && matchesPriority && matchesDeadline && matchesAssignee;
        },
        pushPermission: ('Notification' in window) ? Notification.permission : 'unsupported',
        initPush() {
            if ('serviceWorker' in navigator) {
                navigator.serviceWorker.register('/sw.js').then((reg) => {
                    console.log('UrPlan Service Worker Active:', reg.scope);
                }).catch((err) => {
                    console.warn('Service Worker info:', err);
                });
            }
        },
        async requestPushPermission() {
            if (!('Notification' in window)) {
                alert('Browser Anda tidak mendukung notifikasi desktop.');
                return;
            }
            try {
                const permission = await Notification.requestPermission();
                this.pushPermission = permission;
                if (permission === 'granted') {
                    this.showNativePush('🎉 Notifikasi Our Plan Aktif!', 'Anda akan menerima pengingat deadline & penugasan tugas bahkan saat website sedang ditutup.');
                }
            } catch (e) {
                console.error(e);
            }
        },
        testPush() {
            this.showNativePush('🔔 Tes Notifikasi Our Plan', 'Notifikasi background berfungsi normal! Pengingat tugas akan muncul di desktop & HP Anda.');
        },
        showNativePush(title, body) {
            if ('Notification' in window && Notification.permission === 'granted') {
                try {
                    new Notification(title, {
                        body: body,
                        icon: '/images/logo-1.png',
                        badge: '/images/logo-1.png'
                    });
                } catch(e) {
                    if (navigator.serviceWorker && navigator.serviceWorker.ready) {
                        navigator.serviceWorker.ready.then(reg => {
                            reg.showNotification(title, { body: body, icon: '/images/logo-1.png' });
                        });
                    }
                }
            } else {
                this.requestPushPermission();
            }
        }
    }" x-init="initPush()" class="py-8 bg-slate-50 min-h-screen">

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

            {{-- Background / Desktop Push Notification Permission Banner --}}
            <div x-show="pushPermission !== 'granted' && pushPermission !== 'unsupported'" class="bg-gradient-to-r from-blue-600 to-indigo-700 text-white rounded-2xl p-4 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-3 animate-fade-in">
                <div class="flex items-center gap-3 text-center sm:text-left">
                    <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center text-xl shrink-0">
                        🔔
                    </div>
                    <div>
                        <h4 class="font-bold text-sm">Aktifkan Notifikasi Desktop / HP (Background)</h4>
                        <p class="text-xs text-blue-100 mt-0.5">Dapatkan pengingat deadline & penugasan tugas langsung di layar perangkat Anda bahkan ketika website sedang ditutup.</p>
                    </div>
                </div>
                <div class="flex items-center gap-2 shrink-0">
                    <button @click="requestPushPermission()" class="bg-white hover:bg-blue-50 text-blue-700 px-4 py-2 rounded-xl font-black text-xs shadow-md transition-all active:scale-95 flex items-center gap-1.5">
                        <span>Aktifkan Sekarang</span>
                        <span>⚡</span>
                    </button>
                </div>
            </div>

            {{-- If already granted: show quick Test Notification trigger button badge --}}
            <div x-show="pushPermission === 'granted'" class="flex items-center justify-between bg-white border border-slate-200/80 px-4 py-2.5 rounded-2xl shadow-xs text-xs">
                <div class="flex items-center gap-2 text-slate-600">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                    <span class="font-bold text-slate-700">Notifikasi Desktop / Background Aktif</span>
                    <span class="text-slate-400 hidden sm:inline">— Pengingat & tugas tetap dikirim saat website ditutup</span>
                </div>
                <button @click="testPush()" class="text-blue-600 hover:text-blue-800 font-bold bg-blue-50 hover:bg-blue-100 px-3 py-1 rounded-xl transition-colors flex items-center gap-1">
                    <span>🔔 Tes Notifikasi</span>
                </button>
            </div>

            {{-- Unread Admin Broadcast Notifications --}}
            @if(isset($unreadNotifications) && $unreadNotifications->count() > 0)
            <div class="space-y-3">
                @foreach($unreadNotifications as $notif)
                <div x-data="{ dismissed: false }" x-show="!dismissed" class="bg-gradient-to-r from-amber-500 via-orange-500 to-rose-500 p-[1px] rounded-2xl shadow-md transition-all">
                    <div class="bg-white rounded-[15px] p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div class="flex items-start gap-3.5">
                            <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600 shrink-0 font-bold text-lg">
                                📢
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="bg-amber-100 text-amber-700 text-[10px] font-extrabold uppercase px-2 py-0.5 rounded-md tracking-wider">Pengumuman Admin</span>
                                    <span class="text-xs text-slate-400 font-medium">{{ $notif->created_at->diffForHumans() }}</span>
                                </div>
                                <h4 class="text-base font-bold text-slate-800 mt-1">{{ $notif->title }}</h4>
                                <p class="text-sm text-slate-600 mt-0.5 leading-relaxed">{{ $notif->message }}</p>
                            </div>
                        </div>
                        <div class="flex items-center justify-end">
                            <form action="{{ route('notifications.read', $notif->id) }}" method="POST" @submit.prevent="
                                fetch('{{ route('notifications.read', $notif->id) }}', {
                                    method: 'PATCH',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Accept': 'application/json'
                                    }
                                }).then(() => dismissed = true);
                            ">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="text-xs font-bold text-slate-500 hover:text-slate-800 bg-slate-100 hover:bg-slate-200 px-4 py-2 rounded-xl transition-all flex items-center gap-1.5 shadow-sm active:scale-95">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    <span>Tandai Dibaca</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif

            {{-- Welcome & Daily Habit Tracker --}}
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
                        if ($hour < 12) { $greeting = 'Selamat Pagi'; }
                        elseif ($hour < 15) { $greeting = 'Selamat Siang'; }
                        elseif ($hour < 18) { $greeting = 'Selamat Sore'; }
                        else { $greeting = 'Selamat Malam'; }
                        @endphp

                        <h2 class="text-3xl md:text-4xl font-black text-slate-800 leading-tight">
                            {{ $greeting }}, <span class="text-blue-600">{{ Auth::user()->name }}</span>
                        </h2>

                        <p class="text-slate-500 mt-2 font-medium max-w-lg leading-relaxed">
                            Ayo wujudkan ide kontenmu hari ini. Rencanakan dengan rapi, eksekusi dengan fokus, dan bangun konsistensi! 🚀
                        </p>
                    </div>
                </div>

                {{-- Daily Habit Tracker Card --}}
                <div class="w-full bg-white rounded-3xl p-6 shadow-sm border border-slate-100 flex flex-col justify-between">
                    <div class="flex justify-between items-center mb-4">
                        <div class="flex items-center gap-2">
                            <span class="text-sm">🔥</span>
                            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest">Daily Habit Tracker</h3>
                        </div>
                        <button @click="showHabitModal = true" class="text-xs font-bold text-blue-600 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 px-2.5 py-1 rounded-xl transition-colors flex items-center gap-1">
                            <span>+ Habit</span>
                        </button>
                    </div>

                    <div class="space-y-3 flex-1 flex flex-col justify-center">
                        @forelse($habits as $habit)
                        <div class="bg-gradient-to-br from-blue-600 to-indigo-700 p-5 rounded-2xl text-white shadow-md shadow-blue-500/15 relative overflow-hidden flex flex-col justify-between gap-3">
                            <div class="flex justify-between items-start">
                                <div>
                                    <span class="text-[10px] text-blue-200 font-bold uppercase tracking-wider">Target Kebiasaan</span>
                                    <h4 class="text-base font-black tracking-tight leading-snug">{{ $habit->name }}</h4>
                                </div>
                                <button @click="openManageHabitModal({{ json_encode($habit) }})" class="text-white/60 hover:text-white p-1 rounded-lg hover:bg-white/10 transition-colors" title="Kelola Habit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path></svg>
                                </button>
                            </div>

                            <div class="flex items-center justify-between pt-1">
                                <div class="flex items-baseline gap-1.5">
                                    <span class="text-3xl font-black">{{ $habit->streak_count }}</span>
                                    <span class="text-blue-200 font-bold text-xs">Hari Streak 🔥</span>
                                </div>

                                @if($habit->isCompletedToday())
                                <div class="bg-emerald-400/20 text-emerald-300 border border-emerald-400/30 text-xs font-black px-3 py-1.5 rounded-xl flex items-center gap-1.5 shadow-sm">
                                    <span>✅ Selesai</span>
                                </div>
                                @else
                                <form action="{{ route('habits.checkIn', $habit->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="bg-white hover:bg-blue-50 text-blue-700 text-xs font-black px-4 py-1.5 rounded-xl shadow-md transition-all transform active:scale-95 flex items-center gap-1">
                                        <span>Check-in</span>
                                        <span>⚡</span>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </div>
                        @empty
                        <div class="bg-slate-50 p-6 rounded-2xl text-center border-2 border-dashed border-slate-200 flex flex-col items-center justify-center">
                            <span class="text-3xl mb-2">🌱</span>
                            <h4 class="font-bold text-slate-700 text-sm">Belum ada Habit</h4>
                            <p class="text-xs text-slate-400 mt-1 mb-3">Bangun konsistensi harian kreatormu.</p>
                            <button @click="showHabitModal = true" class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold px-3.5 py-1.5 rounded-xl shadow-sm transition-colors">
                                + Buat Habit Pertama
                            </button>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Stat Cards & Project Switcher --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                {{-- Active Project with Switcher & Add Project --}}
                <div class="md:col-span-2 bg-white rounded-3xl p-6 shadow-sm border border-slate-100 flex flex-col justify-between">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-slate-900 rounded-2xl flex items-center justify-center text-white shadow-sm">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <p class="text-[10px] uppercase tracking-widest text-slate-400 font-black">Active Project</p>
                                    @if($project && $project->platform)
                                    <span class="text-[9px] font-bold bg-slate-100 text-slate-600 px-2 py-0.5 rounded-md">{{ $project->platform }}</span>
                                    @endif
                                </div>
                                <h3 class="text-lg font-bold text-slate-800">{{ $project ? $project->name : 'Belum Ada Project' }}</h3>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <button @click="showTeamModal = true" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 bg-indigo-50 hover:bg-indigo-100 px-3 py-1.5 rounded-xl transition-colors flex items-center gap-1.5 shadow-sm" title="Kelola Anggota Tim Workspace">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                <span>👥 Tim ({{ $teamMembers->count() }})</span>
                            </button>
                            @if($project)
                            <button @click="showEditProjectModal = true" class="text-xs font-bold text-slate-600 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 px-3 py-1.5 rounded-xl transition-colors flex items-center gap-1" title="Pengaturan Project">
                                <span>⚙️ Edit</span>
                            </button>
                            @endif
                            <button @click="showProjectModal = true" class="text-xs font-bold text-blue-600 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-xl transition-colors flex items-center gap-1">
                                <span>+ Project Baru</span>
                            </button>
                        </div>
                    </div>

                    {{-- Switcher Dropdown if multiple projects exist --}}
                    @if(isset($projects) && $projects->count() > 1)
                    <div class="mt-2 pt-3 border-t border-slate-100 flex items-center gap-2">
                        <span class="text-xs font-bold text-slate-400">Ganti:</span>
                        <div class="flex flex-wrap gap-1.5">
                            @foreach($projects as $p)
                            <a href="{{ route('dashboard', ['project_id' => $p->id]) }}" class="text-xs font-semibold px-2.5 py-1 rounded-lg transition-all flex items-center gap-1.5 {{ $project && $project->id == $p->id ? 'bg-slate-900 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                                <span>{{ $p->name }}</span>
                                <span class="text-[10px] opacity-75 font-mono">({{ $p->tasks->count() }})</span>
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @else
                    <p class="text-xs text-slate-500 mt-1">{{ $project ? ($project->description ?? 'Workspace perencanaan konten aktif.') : 'Buat project baru untuk memulai.' }}</p>
                    @endif
                </div>

                {{-- Stat: YouTube --}}
                <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 border-b-4 border-b-red-500 flex justify-between items-center">
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">YouTube</p>
                        <h3 class="text-3xl font-black text-slate-800">{{ $platformStats['YouTube'] ?? 0 }}</h3>
                    </div>
                    <div class="w-11 h-11 bg-red-50 text-red-500 rounded-2xl flex items-center justify-center">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z" />
                        </svg>
                    </div>
                </div>

                {{-- Stat: Total Task --}}
                <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 border-b-4 border-b-blue-500 flex justify-between items-center">
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Total Task</p>
                        <h3 class="text-3xl font-black text-slate-800">{{ $project ? $project->tasks->count() : 0 }}</h3>
                    </div>
                    <div class="w-11 h-11 bg-blue-50 text-blue-500 rounded-2xl flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Kanban Board Header & Filters --}}
            <div class="mt-8 bg-white p-6 md:p-8 rounded-3xl shadow-sm border border-slate-100">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
                    <div>
                        <div class="flex items-center gap-3">
                            <h3 class="text-xl font-black text-slate-800">Content Pipeline</h3>
                            <span class="bg-blue-50 text-blue-600 text-xs font-bold px-2.5 py-0.5 rounded-full border border-blue-100">
                                Kanban Board
                            </span>
                        </div>
                        <p class="text-slate-500 text-xs font-medium mt-1">Tarik dan geser (drag & drop) kartu task untuk mengubah status workflow.</p>
                    </div>

                    <div class="flex items-center gap-2.5 w-full md:w-auto">
                        @if($project && $project->tasks->count() > 0)
                        <a href="{{ route('projects.export', $project->id) }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-4 py-2.5 rounded-xl font-bold text-xs transition-all flex items-center justify-center gap-1.5 shadow-sm" title="Download data perencanaan ke format Excel / CSV">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            <span>Export CSV</span>
                        </a>
                        @endif

                        <button @click="showModal = true; taskStatus = 'Idea'" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-bold text-sm transition-all shadow-md shadow-blue-500/20 flex items-center justify-center gap-2 flex-1 md:flex-none">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            <span>+ Task Baru</span>
                        </button>
                    </div>
                </div>

                {{-- Search & Filters Toolbar --}}
                <div class="flex flex-col lg:flex-row items-center justify-between gap-3 p-3.5 bg-slate-50 rounded-2xl mb-6 border border-slate-100">
                    <div class="relative w-full lg:w-64">
                        <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <input type="text" x-model="searchQuery" placeholder="Cari judul task..." class="w-full bg-white border border-slate-200 text-slate-800 text-xs rounded-xl pl-9 pr-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div class="flex flex-wrap items-center gap-2.5 w-full lg:w-auto justify-end">
                        {{-- Filter Assignee --}}
                        <div class="flex items-center gap-1.5">
                            <span class="text-[11px] font-bold text-slate-400 uppercase">PIC:</span>
                            <select x-model="filterAssignee" class="bg-white border border-slate-200 text-slate-700 text-xs rounded-xl py-1.5 px-3 focus:ring-2 focus:ring-blue-500">
                                <option value="all">Semua Tim</option>
                                <option value="my">⭐ Tugas Saya ({{ Auth::user()->name }})</option>
                                @foreach($teamMembers as $member)
                                <option value="{{ $member->id }}">👤 {{ $member->name }} {{ $member->position ? '(' . $member->position . ')' : '' }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex items-center gap-1.5">
                            <span class="text-[11px] font-bold text-slate-400 uppercase">Platform:</span>
                            <select x-model="filterPlatform" class="bg-white border border-slate-200 text-slate-700 text-xs rounded-xl py-1.5 px-3 focus:ring-2 focus:ring-blue-500">
                                <option value="all">Semua Platform</option>
                                <option value="YouTube">YouTube</option>
                                <option value="TikTok">TikTok</option>
                                <option value="Instagram">Instagram</option>
                                <option value="Blog">Blog</option>
                            </select>
                        </div>

                        <div class="flex items-center gap-1.5">
                            <span class="text-[11px] font-bold text-slate-400 uppercase">Prioritas:</span>
                            <select x-model="filterPriority" class="bg-white border border-slate-200 text-slate-700 text-xs rounded-xl py-1.5 px-3 focus:ring-2 focus:ring-blue-500">
                                <option value="all">Semua Prioritas</option>
                                <option value="High">High</option>
                                <option value="Medium">Medium</option>
                                <option value="Low">Low</option>
                            </select>
                        </div>

                        <div class="flex items-center gap-1.5">
                            <span class="text-[11px] font-bold text-slate-400 uppercase">Deadline:</span>
                            <select x-model="filterDeadline" class="bg-white border border-slate-200 text-slate-700 text-xs rounded-xl py-1.5 px-3 focus:ring-2 focus:ring-blue-500">
                                <option value="all">Semua Waktu</option>
                                <option value="overdue">⚠️ Terlewat (Overdue)</option>
                                <option value="today">⏰ Hari Ini</option>
                                <option value="upcoming">📅 Mendatang</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Kanban Columns --}}
                <div class="flex overflow-x-auto space-x-4 pb-6 custom-scrollbar">
                    @php
                    $columns = [
                        ['status' => 'Idea', 'color' => 'bg-amber-500', 'badge' => 'bg-amber-50 text-amber-600 border-amber-200'],
                        ['status' => 'Draft', 'color' => 'bg-sky-500', 'badge' => 'bg-sky-50 text-sky-600 border-sky-200'],
                        ['status' => 'In Progress', 'color' => 'bg-blue-500', 'badge' => 'bg-blue-50 text-blue-600 border-blue-200'],
                        ['status' => 'Review', 'color' => 'bg-purple-500', 'badge' => 'bg-purple-50 text-purple-600 border-purple-200'],
                        ['status' => 'Scheduled', 'color' => 'bg-indigo-500', 'badge' => 'bg-indigo-50 text-indigo-600 border-indigo-200'],
                        ['status' => 'Published', 'color' => 'bg-emerald-500', 'badge' => 'bg-emerald-50 text-emerald-600 border-emerald-200'],
                    ];
                    @endphp

                    @foreach($columns as $col)
                    @php $status = $col['status']; @endphp
                    <div class="flex-shrink-0 w-80 flex flex-col">
                        {{-- Column Header --}}
                        <div class="flex items-center justify-between mb-3 px-1">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full {{ $col['color'] }}"></span>
                                <h4 class="font-bold text-slate-800 text-sm">{{ $status }}</h4>
                            </div>
                            <span class="bg-slate-100 text-slate-600 text-xs font-black px-2.5 py-0.5 rounded-md">
                                {{ isset($tasksByStatus[$status]) ? count($tasksByStatus[$status]) : 0 }}
                            </span>
                        </div>

                        {{-- Column Drop Zone --}}
                        <div
                            @dragover="onDragOver($event, '{{ $status }}')"
                            @dragleave="onDragLeave($event, '{{ $status }}')"
                            @drop="onDrop($event, '{{ $status }}')"
                            :class="{ 'border-blue-500 bg-blue-50/50 ring-2 ring-blue-400': dragOverStatus === '{{ $status }}' }"
                            class="bg-slate-50/80 rounded-2xl p-3 flex-1 border border-slate-200/80 min-h-[350px] flex flex-col gap-3 transition-all duration-150">

                            @if(isset($tasksByStatus[$status]))
                            @foreach($tasksByStatus[$status] as $task)
                            <div
                                x-show="matchesFilter('{{ addslashes($task->title) }}', '{{ $task->platform_distribution }}', '{{ $task->priority }}', '{{ $task->deadline ? $task->deadline->format('Y-m-d') : '' }}', '{{ $task->status }}', {{ json_encode($task->assignees->pluck('id')) }})"
                                draggable="true"
                                @dragstart="onDragStart($event, {{ $task->id }})"
                                @click="openEditModal({{ json_encode($task) }})"
                                class="bg-white p-4 rounded-2xl shadow-sm border border-slate-200 hover:border-blue-400 hover:shadow-md cursor-grab active:cursor-grabbing transition-all group relative">

                                <div class="flex justify-between items-start mb-2 gap-2">
                                    {{-- Platform Tag --}}
                                    <span class="text-[10px] font-black uppercase tracking-wider px-2 py-0.5 rounded-lg
                                        @if($task->platform_distribution == 'YouTube') bg-red-50 text-red-600 border border-red-100
                                        @elseif($task->platform_distribution == 'TikTok') bg-slate-900 text-white
                                        @elseif($task->platform_distribution == 'Instagram') bg-pink-50 text-pink-600 border border-pink-100
                                        @else bg-slate-100 text-slate-600 border border-slate-200 @endif">
                                        {{ $task->platform_distribution }}
                                    </span>

                                    {{-- Priority Tag --}}
                                    @if($task->priority == 'High')
                                    <span class="text-[10px] font-black text-rose-600 bg-rose-50 px-2 py-0.5 rounded-lg border border-rose-100">
                                        High
                                    </span>
                                    @elseif($task->priority == 'Medium')
                                    <span class="text-[10px] font-black text-amber-600 bg-amber-50 px-2 py-0.5 rounded-lg border border-amber-100">
                                        Medium
                                    </span>
                                    @else
                                    <span class="text-[10px] font-black text-slate-500 bg-slate-100 px-2 py-0.5 rounded-lg">
                                        Low
                                    </span>
                                    @endif
                                </div>

                                <h4 class="font-bold text-slate-800 text-sm leading-snug group-hover:text-blue-600 transition-colors">
                                    {{ $task->title }}
                                </h4>

                                @if($task->description)
                                <p class="text-slate-500 text-xs line-clamp-2 mt-1.5 leading-relaxed font-normal">
                                    {{ $task->description }}
                                </p>
                                @endif

                                {{-- Footer Card: Deadline & PIC Avatars --}}
                                <div class="mt-3 pt-2.5 border-t border-slate-100 flex items-center justify-between text-[11px] font-semibold gap-2">
                                    @if($task->deadline)
                                    @php
                                    $isOverdue = $task->deadline->isPast() && !in_array($task->status, ['Published']);
                                    $isToday = $task->deadline->isToday();
                                    @endphp
                                    <span class="flex items-center gap-1.5 {{ $isOverdue ? 'text-rose-600 font-bold' : ($isToday ? 'text-amber-600 font-bold' : 'text-slate-400') }}">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        {{ $task->deadline->translatedFormat('d M') }}
                                        @if($isOverdue) (Terlewat) @elseif($isToday) (Hari Ini) @endif
                                    </span>
                                    @else
                                    <span class="text-slate-300 text-[10px]">Tanpa Deadline</span>
                                    @endif

                                    {{-- PIC Avatar Badges --}}
                                    @if($task->assignees && $task->assignees->count() > 0)
                                    <div class="flex items-center -space-x-1.5 overflow-hidden">
                                        @foreach($task->assignees as $assignee)
                                        <div class="inline-flex items-center justify-center w-6 h-6 rounded-full ring-2 ring-white text-[10px] font-black text-white shadow-xs
                                            @if($assignee->id % 4 == 0) bg-blue-500
                                            @elseif($assignee->id % 4 == 1) bg-indigo-500
                                            @elseif($assignee->id % 4 == 2) bg-emerald-500
                                            @else bg-amber-500 @endif"
                                            title="{{ $assignee->name }} ({{ $assignee->position ?? ($assignee->owner_id ? 'Anggota' : 'Leader') }})">
                                            {{ $assignee->initials }}
                                        </div>
                                        @endforeach
                                    </div>
                                    @else
                                    <span class="text-[10px] text-slate-300 italic">Belum ada PIC</span>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                            @endif

                            {{-- Add Task Button in Column --}}
                            <button @click="showModal = true; taskStatus = '{{ $status }}'" class="w-full py-2.5 rounded-xl border-2 border-dashed border-slate-200 text-slate-400 font-bold text-xs hover:border-blue-400 hover:text-blue-600 hover:bg-white transition-all mt-auto flex items-center justify-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                <span>Tambah di {{ $status }}</span>
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- MODAL 1: Create Task --}}
        <div x-show="showModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto">
            <div x-show="showModal" x-transition.opacity class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm" @click="showModal = false"></div>

            <div class="flex min-h-full items-center justify-center p-4 text-center">
                <div x-show="showModal" x-transition class="relative w-full max-w-lg transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all">
                    <div class="bg-white px-6 py-6 sm:px-8 sm:py-7">
                        <div class="flex justify-between items-center mb-5">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>
                                <h3 class="text-xl font-black text-slate-900">Buat Task Baru</h3>
                            </div>
                            <button @click="showModal = false" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-xl hover:bg-slate-100 transition-colors">
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
                                <input type="text" name="title" required class="w-full bg-slate-50 border border-slate-200 text-slate-900 px-4 py-2.5 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Contoh: Review Kamera Sony A7IV untuk Pemula">
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Platform</label>
                                    <select name="platform_distribution" class="w-full bg-slate-50 border border-slate-200 text-slate-900 px-4 py-2.5 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                                        <option value="YouTube">YouTube</option>
                                        <option value="TikTok">TikTok</option>
                                        <option value="Instagram">Instagram</option>
                                        <option value="Blog">Blog</option>
                                    </select>
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

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Status Kolom</label>
                                    <input type="text" disabled x-model="taskStatus" class="w-full bg-slate-100 border border-slate-200 text-slate-500 px-4 py-2.5 rounded-xl text-sm font-bold cursor-not-allowed">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Deadline (Target)</label>
                                    <input type="date" name="deadline" class="w-full bg-slate-50 border border-slate-200 text-slate-900 px-4 py-2.5 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">👥 Ditugaskan Ke (Bisa Pilih Banyak):</label>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 bg-slate-50 p-2.5 rounded-xl border border-slate-200 max-h-36 overflow-y-auto custom-scrollbar">
                                    @foreach($teamMembers as $member)
                                    <label class="flex items-center gap-2 p-1.5 rounded-lg hover:bg-white border border-transparent hover:border-slate-200 cursor-pointer transition-all">
                                        <input type="checkbox" name="assignee_ids[]" value="{{ $member->id }}" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500" {{ $member->id === Auth::id() ? 'checked' : '' }}>
                                        <div class="flex items-center gap-1.5 min-w-0">
                                            <div class="w-5 h-5 rounded-full bg-blue-100 text-blue-700 font-black text-[9px] flex items-center justify-center shrink-0">
                                                {{ $member->initials }}
                                            </div>
                                            <div class="truncate">
                                                <p class="text-xs font-bold text-slate-800 leading-none truncate">{{ $member->name }}</p>
                                                <p class="text-[9px] text-slate-400 leading-tight mt-0.5 truncate">{{ $member->position ?? ($member->owner_id ? 'Anggota' : 'Leader') }}</p>
                                            </div>
                                        </div>
                                    </label>
                                    @endforeach
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Deskripsi / Brief Singkat</label>
                                <textarea name="description" rows="2" class="w-full bg-slate-50 border border-slate-200 text-slate-900 px-4 py-2 rounded-xl text-sm focus:ring-2 focus:ring-blue-500" placeholder="Poin-poin penting yang ingin dibahas dalam konten..."></textarea>
                            </div>

                            <div class="mt-6 flex justify-end gap-2 pt-4 border-t border-slate-100">
                                <button type="button" @click="showModal = false" class="px-5 py-2.5 rounded-xl font-bold text-slate-500 text-sm hover:bg-slate-100 transition-colors">Batal</button>
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-xl text-sm font-bold shadow-md shadow-blue-500/20 transition-all">
                                    Simpan Task
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- MODAL 2: Edit & View Task Detail --}}
        <div x-show="showEditModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto">
            <div x-show="showEditModal" x-transition.opacity class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm" @click="showEditModal = false"></div>

            <div class="flex min-h-full items-center justify-center p-4 text-center">
                <div x-show="showEditModal" x-transition class="relative w-full max-w-lg transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all">
                    <div class="bg-white px-6 py-6 sm:px-8 sm:py-7">
                        <div class="flex justify-between items-center mb-5">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                                <h3 class="text-xl font-black text-slate-900">Detail & Edit Task</h3>
                            </div>
                            <button @click="showEditModal = false" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-xl hover:bg-slate-100 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <form :action="'/tasks/' + activeTask.id" method="POST" class="space-y-4">
                            @csrf
                            @method('PUT')

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Judul Konten <span class="text-red-500">*</span></label>
                                <input type="text" name="title" x-model="activeTask.title" required class="w-full bg-slate-50 border border-slate-200 text-slate-900 px-4 py-2.5 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Platform</label>
                                    <select name="platform_distribution" x-model="activeTask.platform_distribution" class="w-full bg-slate-50 border border-slate-200 text-slate-900 px-4 py-2.5 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                                        <option value="YouTube">YouTube</option>
                                        <option value="TikTok">TikTok</option>
                                        <option value="Instagram">Instagram</option>
                                        <option value="Blog">Blog</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Prioritas</label>
                                    <select name="priority" x-model="activeTask.priority" class="w-full bg-slate-50 border border-slate-200 text-slate-900 px-4 py-2.5 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                                        <option value="Low">Low</option>
                                        <option value="Medium">Medium</option>
                                        <option value="High">High</option>
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Status Workflow</label>
                                    <select name="status" x-model="activeTask.status" class="w-full bg-slate-50 border border-slate-200 text-slate-900 px-4 py-2.5 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                                        <option value="Idea">Idea</option>
                                        <option value="Draft">Draft</option>
                                        <option value="In Progress">In Progress</option>
                                        <option value="Review">Review</option>
                                        <option value="Scheduled">Scheduled</option>
                                        <option value="Published">Published</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Deadline (Target)</label>
                                    <input type="date" name="deadline" x-model="activeTask.deadline" class="w-full bg-slate-50 border border-slate-200 text-slate-900 px-4 py-2.5 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">👥 Ditugaskan Ke (Bisa Pilih Banyak):</label>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 bg-slate-50 p-2.5 rounded-xl border border-slate-200 max-h-36 overflow-y-auto custom-scrollbar">
                                    @foreach($teamMembers as $member)
                                    <label class="flex items-center gap-2 p-1.5 rounded-lg hover:bg-white border border-transparent hover:border-slate-200 cursor-pointer transition-all">
                                        <input type="checkbox" name="assignee_ids[]" value="{{ $member->id }}" :checked="activeTask.assignee_ids && activeTask.assignee_ids.includes({{ $member->id }})" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                        <div class="flex items-center gap-1.5 min-w-0">
                                            <div class="w-5 h-5 rounded-full bg-indigo-100 text-indigo-700 font-black text-[9px] flex items-center justify-center shrink-0">
                                                {{ $member->initials }}
                                            </div>
                                            <div class="truncate">
                                                <p class="text-xs font-bold text-slate-800 leading-none truncate">{{ $member->name }}</p>
                                                <p class="text-[9px] text-slate-400 leading-tight mt-0.5 truncate">{{ $member->position ?? ($member->owner_id ? 'Anggota' : 'Leader') }}</p>
                                            </div>
                                        </div>
                                    </label>
                                    @endforeach
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Deskripsi / Brief</label>
                                <textarea name="description" x-model="activeTask.description" rows="2" class="w-full bg-slate-50 border border-slate-200 text-slate-900 px-4 py-2 rounded-xl text-sm focus:ring-2 focus:ring-blue-500"></textarea>
                            </div>

                            <div class="mt-6 flex justify-between items-center pt-4 border-t border-slate-100">
                                <button type="button" @click="if(confirm('Yakin ingin menghapus task ini?')) { $refs.deleteTaskForm.submit(); }" class="text-rose-600 hover:text-rose-700 hover:bg-rose-50 px-4 py-2 rounded-xl text-xs font-bold transition-colors">
                                    🗑️ Hapus Task
                                </button>

                                <div class="flex gap-2">
                                    <button type="button" @click="showEditModal = false" class="px-4 py-2 rounded-xl font-bold text-slate-500 text-sm hover:bg-slate-100 transition-colors">Batal</button>
                                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-xl text-sm font-bold shadow-md shadow-blue-500/20 transition-all">
                                        Perbarui Task
                                    </button>
                                </div>
                            </div>
                        </form>

                        {{-- Hidden Delete Form --}}
                        <form x-ref="deleteTaskForm" :action="'/tasks/' + activeTask.id" method="POST" class="hidden">
                            @csrf
                            @method('DELETE')
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- MODAL 3: Create Project --}}
        <div x-show="showProjectModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto">
            <div x-show="showProjectModal" x-transition.opacity class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm" @click="showProjectModal = false"></div>

            <div class="flex min-h-full items-center justify-center p-4 text-center">
                <div x-show="showProjectModal" x-transition class="relative w-full max-w-md transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all">
                    <div class="bg-white px-6 py-6 sm:px-8 sm:py-7">
                        <div class="flex justify-between items-center mb-5">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-slate-900"></span>
                                <h3 class="text-xl font-black text-slate-900">Buat Project Baru</h3>
                            </div>
                            <button @click="showProjectModal = false" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-xl hover:bg-slate-100 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <form action="{{ route('projects.store') }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama Project <span class="text-red-500">*</span></label>
                                <input type="text" name="name" required class="w-full bg-slate-50 border border-slate-200 text-slate-900 px-4 py-2.5 rounded-xl text-sm focus:ring-2 focus:ring-blue-500" placeholder="Contoh: Channel YouTube Gaming / Brand XYZ">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Platform Utama</label>
                                <select name="platform" class="w-full bg-slate-50 border border-slate-200 text-slate-900 px-4 py-2.5 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                                    <option value="Multi-Platform">Multi-Platform</option>
                                    <option value="YouTube">YouTube</option>
                                    <option value="TikTok">TikTok</option>
                                    <option value="Instagram">Instagram</option>
                                    <option value="Blog">Blog</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Deskripsi Project</label>
                                <textarea name="description" rows="3" class="w-full bg-slate-50 border border-slate-200 text-slate-900 px-4 py-2.5 rounded-xl text-sm focus:ring-2 focus:ring-blue-500" placeholder="Tujuan atau fokus project ini..."></textarea>
                            </div>

                            <div class="mt-6 flex justify-end gap-2 pt-4 border-t border-slate-100">
                                <button type="button" @click="showProjectModal = false" class="px-5 py-2.5 rounded-xl font-bold text-slate-500 text-sm hover:bg-slate-100 transition-colors">Batal</button>
                                <button type="submit" class="bg-slate-900 hover:bg-blue-600 text-white px-6 py-2.5 rounded-xl text-sm font-bold transition-all">
                                    Simpan Project
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- MODAL 4: Create Habit --}}
        <div x-show="showHabitModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto">
            <div x-show="showHabitModal" x-transition.opacity class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm" @click="showHabitModal = false"></div>

            <div class="flex min-h-full items-center justify-center p-4 text-center">
                <div x-show="showHabitModal" x-transition class="relative w-full max-w-md transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all">
                    <div class="bg-white px-6 py-6 sm:px-8 sm:py-7">
                        <div class="flex justify-between items-center mb-5">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-blue-600"></span>
                                <h3 class="text-xl font-black text-slate-900">Tambah Habit Baru</h3>
                            </div>
                            <button @click="showHabitModal = false" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-xl hover:bg-slate-100 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <form action="{{ route('habits.store') }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama Habit / Kebiasaan <span class="text-red-500">*</span></label>
                                <input type="text" name="name" required class="w-full bg-slate-50 border border-slate-200 text-slate-900 px-4 py-2.5 rounded-xl text-sm focus:ring-2 focus:ring-blue-500" placeholder="Contoh: Riset Ide Konten 30 Menit">
                                <p class="text-[11px] text-slate-400 mt-1">Lakukan setiap hari untuk mempertahankan streak konsistensimu.</p>
                            </div>

                            <div class="mt-6 flex justify-end gap-2 pt-4 border-t border-slate-100">
                                <button type="button" @click="showHabitModal = false" class="px-5 py-2.5 rounded-xl font-bold text-slate-500 text-sm hover:bg-slate-100 transition-colors">Batal</button>
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-xl text-sm font-bold shadow-md shadow-blue-500/20 transition-all">
                                    Simpan Habit
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- MODAL 5: Manage / Edit Habit --}}
        <div x-show="showManageHabitModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto">
            <div x-show="showManageHabitModal" x-transition.opacity class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm" @click="showManageHabitModal = false"></div>

            <div class="flex min-h-full items-center justify-center p-4 text-center">
                <div x-show="showManageHabitModal" x-transition class="relative w-full max-w-md transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all">
                    <div class="bg-white px-6 py-6 sm:px-8 sm:py-7">
                        <div class="flex justify-between items-center mb-5">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-indigo-600"></span>
                                <h3 class="text-xl font-black text-slate-900">Kelola Habit</h3>
                            </div>
                            <button @click="showManageHabitModal = false" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-xl hover:bg-slate-100 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <form :action="'/habits/' + activeHabit.id" method="POST" class="space-y-4">
                            @csrf
                            @method('PUT')

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama Habit <span class="text-red-500">*</span></label>
                                <input type="text" name="name" x-model="activeHabit.name" required class="w-full bg-slate-50 border border-slate-200 text-slate-900 px-4 py-2.5 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                            </div>

                            <div class="pt-2">
                                <label class="inline-flex items-center gap-2 text-xs font-semibold text-slate-600 cursor-pointer">
                                    <input type="checkbox" name="reset_streak" value="1" class="rounded border-slate-300 text-rose-600 focus:ring-rose-500">
                                    <span>Reset Streak ke 0</span>
                                </label>
                            </div>

                            <div class="mt-6 flex justify-between items-center pt-4 border-t border-slate-100">
                                <button type="button" @click="if(confirm('Yakin ingin menghapus habit ini?')) { $refs.deleteHabitForm.submit(); }" class="text-rose-600 hover:text-rose-700 hover:bg-rose-50 px-4 py-2 rounded-xl text-xs font-bold transition-colors">
                                    🗑️ Hapus Habit
                                </button>

                                <div class="flex gap-2">
                                    <button type="button" @click="showManageHabitModal = false" class="px-4 py-2 rounded-xl font-bold text-slate-500 text-sm hover:bg-slate-100 transition-colors">Batal</button>
                                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-xl text-sm font-bold shadow-md shadow-indigo-500/20 transition-all">
                                        Perbarui
                                    </button>
                                </div>
                            </div>
                        </form>

                        {{-- Hidden Delete Habit Form --}}
                        <form x-ref="deleteHabitForm" :action="'/habits/' + activeHabit.id" method="POST" class="hidden">
                            @csrf
                            @method('DELETE')
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- MODAL 6: Edit & Delete Project --}}
        @if($project)
        <div x-show="showEditProjectModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto">
            <div x-show="showEditProjectModal" x-transition.opacity class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm" @click="showEditProjectModal = false"></div>

            <div class="flex min-h-full items-center justify-center p-4 text-center">
                <div x-show="showEditProjectModal" x-transition class="relative w-full max-w-md transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all">
                    <div class="bg-white px-6 py-6 sm:px-8 sm:py-7">
                        <div class="flex justify-between items-center mb-5">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-slate-800"></span>
                                <h3 class="text-xl font-black text-slate-900">Pengaturan Project</h3>
                            </div>
                            <button @click="showEditProjectModal = false" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-xl hover:bg-slate-100 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <form :action="'/projects/' + activeProjectData.id" method="POST" class="space-y-4">
                            @csrf
                            @method('PUT')

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama Project <span class="text-red-500">*</span></label>
                                <input type="text" name="name" x-model="activeProjectData.name" required class="w-full bg-slate-50 border border-slate-200 text-slate-900 px-4 py-2.5 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Platform Utama</label>
                                <select name="platform" x-model="activeProjectData.platform" class="w-full bg-slate-50 border border-slate-200 text-slate-900 px-4 py-2.5 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                                    <option value="Multi-Platform">Multi-Platform</option>
                                    <option value="YouTube">YouTube</option>
                                    <option value="TikTok">TikTok</option>
                                    <option value="Instagram">Instagram</option>
                                    <option value="Blog">Blog</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Deskripsi Project</label>
                                <textarea name="description" x-model="activeProjectData.description" rows="3" class="w-full bg-slate-50 border border-slate-200 text-slate-900 px-4 py-2.5 rounded-xl text-sm focus:ring-2 focus:ring-blue-500"></textarea>
                            </div>

                            <div class="mt-6 flex justify-between items-center pt-4 border-t border-slate-100">
                                <button type="button" @click="if(confirm('Peringatan: Menghapus project akan menghapus semua task di dalamnya. Yakin?')) { $refs.deleteProjectForm.submit(); }" class="text-rose-600 hover:text-rose-700 hover:bg-rose-50 px-4 py-2 rounded-xl text-xs font-bold transition-colors">
                                    🗑️ Hapus Project
                                </button>

                                <div class="flex gap-2">
                                    <button type="button" @click="showEditProjectModal = false" class="px-4 py-2 rounded-xl font-bold text-slate-500 text-sm hover:bg-slate-100 transition-colors">Batal</button>
                                    <button type="submit" class="bg-slate-900 hover:bg-blue-600 text-white px-5 py-2 rounded-xl text-sm font-bold shadow-md transition-all">
                                        Simpan
                                    </button>
                                </div>
                            </div>
                        </form>

                        {{-- Hidden Delete Project Form --}}
                        <form x-ref="deleteProjectForm" :action="'/projects/' + activeProjectData.id" method="POST" class="hidden">
                            @csrf
                            @method('DELETE')
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- MODAL 7: Manage Team & Members --}}
        <div x-show="showTeamModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto">
            <div x-show="showTeamModal" x-transition.opacity class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm" @click="showTeamModal = false"></div>

            <div class="flex min-h-full items-center justify-center p-4 text-center">
                <div x-show="showTeamModal" x-transition class="relative w-full max-w-2xl transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all">
                    <div class="bg-white px-6 py-6 sm:px-8 sm:py-7">
                        <div class="flex justify-between items-center mb-6">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-lg font-bold">
                                    👥
                                </div>
                                <div>
                                    <h3 class="text-xl font-black text-slate-900">Manajemen Tim Workspace</h3>
                                    <p class="text-xs text-slate-500 mt-0.5">Kelola akun anggota tim untuk kolaborasi penugasan tugas & jadwal konten.</p>
                                </div>
                            </div>
                            <button @click="showTeamModal = false" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-xl hover:bg-slate-100 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        @if($isTeamLeader)
                        {{-- Form Tambah Anggota --}}
                        <div class="bg-slate-50 p-5 rounded-2xl border border-slate-200/80 mb-6">
                            <h4 class="text-xs font-black uppercase tracking-wider text-slate-700 mb-3 flex items-center gap-1.5">
                                <span>➕ Tambah Akun Anggota Baru</span>
                            </h4>
                            <form action="{{ route('team.members.store') }}" method="POST" class="space-y-3">
                                @csrf
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                                        <input type="text" name="name" required placeholder="Contoh: Budi Santoso" class="w-full bg-white border border-slate-200 text-slate-900 px-3 py-2 rounded-xl text-xs focus:ring-2 focus:ring-indigo-500">
                                    </div>
                                    <div>
                                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Email Login <span class="text-red-500">*</span></label>
                                        <input type="email" name="email" required placeholder="budi@tim.com" class="w-full bg-white border border-slate-200 text-slate-900 px-3 py-2 rounded-xl text-xs focus:ring-2 focus:ring-indigo-500">
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Password Awal <span class="text-red-500">*</span></label>
                                        <input type="password" name="password" required placeholder="Minimal 6 karakter" class="w-full bg-white border border-slate-200 text-slate-900 px-3 py-2 rounded-xl text-xs focus:ring-2 focus:ring-indigo-500">
                                    </div>
                                    <div>
                                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Posisi / Role Tugas</label>
                                        <input type="text" name="position" placeholder="Contoh: Video Editor, Scriptwriter" class="w-full bg-white border border-slate-200 text-slate-900 px-3 py-2 rounded-xl text-xs focus:ring-2 focus:ring-indigo-500">
                                    </div>
                                </div>

                                <div class="flex justify-end pt-1">
                                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl text-xs font-bold transition-all shadow-sm flex items-center gap-1.5">
                                        <span>Daftarkan Anggota</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                        @endif

                        {{-- Daftar Anggota Tim --}}
                        <div>
                            <h4 class="text-xs font-black uppercase tracking-wider text-slate-700 mb-3 flex items-center justify-between">
                                <span>Anggota Tim Aktif ({{ $teamMembers->count() }})</span>
                            </h4>
                            <div class="space-y-2 max-h-60 overflow-y-auto custom-scrollbar">
                                @foreach($teamMembers as $tm)
                                <div class="flex items-center justify-between p-3 rounded-2xl bg-white border border-slate-200/80 hover:border-indigo-200 transition-colors">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-700 font-black text-xs flex items-center justify-center">
                                            {{ $tm->initials }}
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <h5 class="text-xs font-bold text-slate-800">{{ $tm->name }}</h5>
                                                @if(!$tm->owner_id)
                                                <span class="bg-amber-100 text-amber-800 text-[9px] font-extrabold uppercase px-1.5 py-0.5 rounded">👑 Leader</span>
                                                @else
                                                <span class="bg-indigo-50 text-indigo-700 text-[9px] font-extrabold px-1.5 py-0.5 rounded">{{ $tm->position ?: 'Anggota' }}</span>
                                                @endif
                                                @if($tm->id === Auth::id())
                                                <span class="bg-slate-100 text-slate-600 text-[9px] font-semibold px-1.5 py-0.5 rounded">Anda</span>
                                                @endif
                                            </div>
                                            <p class="text-[11px] text-slate-400 mt-0.5">{{ $tm->email }}</p>
                                        </div>
                                    </div>

                                    @if($isTeamLeader && $tm->owner_id && $tm->id !== Auth::id())
                                    <form action="{{ route('team.members.destroy', $tm->id) }}" method="POST" onsubmit="return confirm('Hapus anggota tim {{ $tm->name }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-rose-500 hover:text-rose-700 p-1.5 rounded-lg hover:bg-rose-50 transition-colors" title="Hapus Anggota">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end pt-4 border-t border-slate-100">
                            <button type="button" @click="showTeamModal = false" class="px-5 py-2.5 rounded-xl font-bold text-slate-600 text-xs bg-slate-100 hover:bg-slate-200 transition-colors">
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

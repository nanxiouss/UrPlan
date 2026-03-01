<!DOCTYPE html>
<html lang="id" class="scroll-smooth scroll-pt-8">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>UrPlan</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    animation: {
                        'blob': 'blob 7s infinite',
                        'float': 'float 3s ease-in-out infinite',
                        'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                    },
                    keyframes: {
                        blob: {
                            '0%': { transform: 'translate(0px, 0px) scale(1)' },
                            '33%': { transform: 'translate(30px, -50px) scale(1.1)' },
                            '66%': { transform: 'translate(-20px, 20px) scale(0.9)' },
                            '100%': { transform: 'translate(0px, 0px) scale(1)' },
                        },
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-20px)' },
                        },
                    }
                }
            }
        }
    </script>

    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        /* Sembunyikan scrollbar untuk elemen UI Mockup */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>
<body class="antialiased selection:bg-blue-600 selection:text-white bg-[#F8FAFC]">

    <div class="min-h-screen font-sans relative overflow-x-hidden">

        <div class="fixed top-0 left-0 w-full h-full overflow-hidden pointer-events-none z-0">
            <div class="absolute top-[-10%] left-[-10%] w-[40rem] h-[40rem] rounded-full bg-blue-400/20 blur-[100px] animate-blob"></div>
            <div class="absolute top-[20%] right-[-10%] w-[40rem] h-[40rem] rounded-full bg-indigo-400/20 blur-[100px] animate-blob" style="animation-delay: 2s"></div>
            <div class="absolute bottom-[-20%] left-[20%] w-[40rem] h-[40rem] rounded-full bg-cyan-400/10 blur-[100px] animate-blob" style="animation-delay: 4s"></div>
        </div>

        <nav class="sticky top-4 z-50 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 transition-all duration-300">
            <div class="flex justify-between items-center bg-white/70 backdrop-blur-2xl border border-white/50 px-6 py-4 rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)]">
                <div class="flex items-center gap-3">
                    <img src="/images/logo-1.png" alt="UrPlan Logo" class="h-14 w-auto object-contain transition-transform duration-300 hover:scale-110">
                    <span class="font-extrabold text-2xl tracking-tighter bg-gradient-to-r from-[#1F7FD1] to-[#0B4FB3] text-transparent bg-clip-text hidden sm:block -ml-2">
                            UrPlan
                        </span>
                </div>
                <div class="hidden md:flex items-center gap-8 text-sm font-bold text-slate-500">
                    <a href="#features" class="hover:text-blue-600 transition-colors">Fitur</a>
                    <a href="#views" class="hover:text-blue-600 transition-colors">Tampilan</a>
                    <a href="#habits" class="hover:text-blue-600 transition-colors">Habits</a>
                    <a href="#pricing" class="hover:text-blue-600 transition-colors">Harga</a>
                </div>
                <div>
                    <a href="/login" class="hidden sm:inline-block text-slate-500 font-bold text-sm mr-6 hover:text-blue-600 transition-colors">Log in</a>
                    <a href="/register" class="bg-slate-900 hover:bg-blue-600 text-white px-7 py-3 rounded-2xl font-bold text-sm transition-all shadow-lg hover:shadow-blue-600/25">Get Started</a>
                </div>
            </div>
        </nav>

        <header class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-20 pb-32 text-center">
            <div class="inline-flex items-center gap-2 bg-white/80 backdrop-blur-md px-5 py-2 rounded-full border border-blue-100 shadow-sm mb-8 hover:scale-105 transition-transform cursor-pointer" data-aos="fade-down">
                <span class="relative flex h-3 w-3">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-blue-600"></span>
                </span>
                <span class="text-blue-900 text-xs font-black uppercase tracking-[0.2em]">UrPlan</span>
            </div>

            <h1 class="text-5xl md:text-7xl lg:text-8xl font-black text-slate-900 leading-[1.05] mb-8 tracking-tighter" data-aos="zoom-in">
                Semua Kontenmu, <br>
                <span class="bg-gradient-to-r from-blue-600 via-indigo-500 to-cyan-500 text-transparent bg-clip-text">Satu Ruang Kerja.</span>
            </h1>

            <p class="max-w-2xl mx-auto text-slate-500 text-lg md:text-xl font-medium leading-relaxed mb-10" data-aos="fade-up" data-aos-delay="100">
                Lebih dari sekadar to-do list. UrPlan menggabungkan Manajemen Tugas, Kalender Editorial, dan Pelacak Kebiasaan khusus untuk Konten Kreator.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mb-16" data-aos="fade-up" data-aos-delay="200">
                <a href="/register" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-10 py-4 rounded-[2rem] font-bold text-lg transition-all duration-300 ease-in-out shadow-[0_0_40px_rgba(37,99,235,0.3)] hover:shadow-[0_0_60px_rgba(37,99,235,0.5)] hover:-translate-y-1">
                    Coba Gratis Sekarang
                </a>
            </div>

            <div class="mt-16 relative mx-auto max-w-5xl animate-float" data-aos="fade-out" data-aos-delay="400">
    <div class="absolute inset-0 bg-gradient-to-b from-blue-500/20 to-transparent blur-2xl rounded-3xl"></div>
    <div class="relative bg-white/90 backdrop-blur-xl border border-white/80 rounded-[2rem] shadow-2xl overflow-hidden ring-1 ring-slate-900/5">
        
        <div class="bg-slate-50/80 border-b border-slate-100 px-6 py-4 flex items-center gap-2">
            <div class="w-3 h-3 rounded-full bg-red-400"></div>
            <div class="w-3 h-3 rounded-full bg-amber-400"></div>
            <div class="w-3 h-3 rounded-full bg-green-400"></div>
            <div class="ml-4 text-xs font-bold text-slate-400 uppercase tracking-widest flex gap-6" id="tab-container">
                <span class="tab-btn text-blue-600 border-b-2 border-blue-600 pb-1 cursor-pointer transition-colors" onclick="switchTab('kanban', this)">Kanban</span>
                <span class="tab-btn hover:text-slate-600 cursor-pointer pb-1 transition-colors" onclick="switchTab('list', this)">List</span>
                <span class="tab-btn hover:text-slate-600 cursor-pointer pb-1 transition-colors" onclick="switchTab('timeline', this)">Timeline</span>
                <span class="tab-btn hover:text-slate-600 cursor-pointer pb-1 transition-colors" onclick="switchTab('calendar', this)">Calendar</span>
            </div>
        </div>

        <div class="relative min-h-[400px]">
            
            <div id="kanban" class="tab-content p-8 grid grid-cols-3 gap-6 transition-opacity duration-300">
                <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100">
                    <div class="flex justify-between items-center mb-4"><span class="text-sm font-bold text-slate-700">💡 Ideation (3)</span></div>
                    <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-100 mb-3 hover:border-blue-300 transition-colors cursor-pointer">
                        <div class="w-12 h-4 bg-purple-100 text-purple-600 text-[10px] font-bold rounded flex items-center justify-center mb-2">Tiktok</div>
                        <div class="text-sm font-bold text-slate-800 mb-1">Tren Dance Viral 2026</div>
                        <div class="h-2 w-1/2 bg-slate-100 rounded"></div>
                    </div>
                </div>
                <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100">
                    <div class="flex justify-between items-center mb-4"><span class="text-sm font-bold text-blue-600">✍️ Scripting (1)</span></div>
                    <div class="bg-white p-4 rounded-xl shadow-sm border border-blue-200 mb-3 ring-2 ring-blue-100 cursor-pointer">
                        <div class="w-14 h-4 bg-red-100 text-red-600 text-[10px] font-bold rounded flex items-center justify-center mb-2">YouTube</div>
                        <div class="text-sm font-bold text-slate-800 mb-2">Review Gadget Setup AI</div>
                        <div class="flex items-center justify-between mt-4">
                            <div class="flex -space-x-2">
                                <div class="w-6 h-6 rounded-full bg-indigo-100 border-2 border-white flex items-center justify-center text-[8px]">U1</div>
                                <div class="w-6 h-6 rounded-full bg-blue-100 border-2 border-white flex items-center justify-center text-[8px]">U2</div>
                            </div>
                            <span class="text-[10px] font-bold text-red-500">Besok</span>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100">
                    <div class="flex justify-between items-center mb-4"><span class="text-sm font-bold text-emerald-600">✅ Ready to Post</span></div>
                    <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-100 mb-3 opacity-60">
                        <div class="w-14 h-4 bg-pink-100 text-pink-600 text-[10px] font-bold rounded flex items-center justify-center mb-2">Instagram</div>
                        <div class="text-sm font-bold text-slate-800 mb-1">Carousel Tips Editing</div>
                    </div>
                </div>
            </div>

            <div id="list" class="tab-content hidden p-8 flex flex-col gap-3 transition-opacity duration-300">
                <div class="grid grid-cols-12 gap-4 px-4 pb-2 text-xs font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100">
                    <div class="col-span-5">Task Name</div>
                    <div class="col-span-3">Status</div>
                    <div class="col-span-2">Platform</div>
                    <div class="col-span-2 text-right">Due Date</div>
                </div>
                <div class="grid grid-cols-12 gap-4 items-center bg-white p-4 rounded-xl border border-slate-100 shadow-sm hover:border-blue-300 cursor-pointer transition-colors">
                    <div class="col-span-5 font-bold text-slate-800 text-sm">Review Gadget Setup AI</div>
                    <div class="col-span-3"><span class="bg-blue-50 text-blue-600 px-2 py-1 rounded text-xs font-bold">✍️ Scripting</span></div>
                    <div class="col-span-2"><div class="w-14 h-4 bg-red-100 text-red-600 text-[10px] font-bold rounded flex items-center justify-center">YouTube</div></div>
                    <div class="col-span-2 text-right text-xs font-bold text-red-500">Besok</div>
                </div>
                <div class="grid grid-cols-12 gap-4 items-center bg-white p-4 rounded-xl border border-slate-100 shadow-sm hover:border-purple-300 cursor-pointer transition-colors">
                    <div class="col-span-5 font-bold text-slate-800 text-sm">Tren Dance Viral 2026</div>
                    <div class="col-span-3"><span class="bg-slate-100 text-slate-600 px-2 py-1 rounded text-xs font-bold">💡 Ideation</span></div>
                    <div class="col-span-2"><div class="w-12 h-4 bg-purple-100 text-purple-600 text-[10px] font-bold rounded flex items-center justify-center">Tiktok</div></div>
                    <div class="col-span-2 text-right text-xs text-slate-400">-</div>
                </div>
            </div>

            <div id="timeline" class="tab-content hidden p-8 transition-opacity duration-300">
                <div class="relative border-l-2 border-slate-100 ml-4 space-y-8">
                    <div class="relative pl-8">
                        <div class="absolute -left-[9px] top-1 w-4 h-4 rounded-full bg-blue-500 ring-4 ring-white"></div>
                        <div class="text-xs font-bold text-blue-500 mb-2 uppercase tracking-wider">Besok</div>
                        <div class="bg-white p-4 rounded-xl border border-slate-100 shadow-sm inline-block min-w-[300px]">
                            <div class="flex items-center gap-2 mb-2">
                                <div class="w-14 h-4 bg-red-100 text-red-600 text-[10px] font-bold rounded flex items-center justify-center">YouTube</div>
                                <span class="text-xs font-bold text-slate-400">Scripting</span>
                            </div>
                            <div class="text-sm font-bold text-slate-800">Review Gadget Setup AI</div>
                        </div>
                    </div>
                    <div class="relative pl-8">
                        <div class="absolute -left-[9px] top-1 w-4 h-4 rounded-full bg-slate-300 ring-4 ring-white"></div>
                        <div class="text-xs font-bold text-slate-400 mb-2 uppercase tracking-wider">Lusa</div>
                        <div class="bg-white p-4 rounded-xl border border-slate-100 shadow-sm inline-block min-w-[300px] opacity-60">
                            <div class="flex items-center gap-2 mb-2">
                                <div class="w-14 h-4 bg-pink-100 text-pink-600 text-[10px] font-bold rounded flex items-center justify-center">Instagram</div>
                                <span class="text-xs font-bold text-slate-400">Ready to Post</span>
                            </div>
                            <div class="text-sm font-bold text-slate-800">Carousel Tips Editing</div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="calendar" class="tab-content hidden p-8 transition-opacity duration-300">
                <div class="grid grid-cols-7 gap-4 mb-4 text-center text-xs font-bold text-slate-400 uppercase">
                    <div>Sen</div><div>Sel</div><div>Rab</div><div>Kam</div><div>Jum</div><div>Sab</div><div>Min</div>
                </div>
                <div class="grid grid-cols-7 gap-4">
                    <div class="aspect-square bg-slate-50/50 rounded-xl border border-transparent"></div>
                    <div class="aspect-square bg-white rounded-xl border border-slate-100 p-2 relative">
                        <span class="text-xs font-bold text-slate-400">1</span>
                    </div>
                    <div class="aspect-square bg-white rounded-xl border border-blue-200 ring-1 ring-blue-100 p-2 relative">
                        <span class="text-xs font-bold text-blue-600">2</span>
                        <div class="mt-2 bg-red-50 border border-red-100 text-red-600 text-[10px] font-bold rounded px-1 py-0.5 truncate">
                            YouTube Review
                        </div>
                    </div>
                    <div class="aspect-square bg-white rounded-xl border border-slate-100 p-2 relative">
                        <span class="text-xs font-bold text-slate-400">3</span>
                        <div class="mt-2 bg-pink-50 border border-pink-100 text-pink-600 text-[10px] font-bold rounded px-1 py-0.5 truncate opacity-60">
                            IG Carousel
                        </div>
                    </div>
                    <div class="aspect-square bg-white rounded-xl border border-slate-100 p-2 relative">
                        <span class="text-xs font-bold text-slate-400">4</span>
                    </div>
                    <div class="aspect-square bg-white rounded-xl border border-slate-100 p-2 relative">
                        <span class="text-xs font-bold text-slate-400">5</span>
                    </div>
                    <div class="aspect-square bg-white rounded-xl border border-slate-100 p-2 relative">
                        <span class="text-xs font-bold text-slate-400">6</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    function switchTab(tabId, clickedElement) {
        // 1. Sembunyikan semua konten tab
        const contents = document.querySelectorAll('.tab-content');
        contents.forEach(content => {
            content.classList.add('hidden');
        });

        // 2. Tampilkan konten yang dipilih
        document.getElementById(tabId).classList.remove('hidden');

        // 3. Reset styling semua tombol tab
        const buttons = document.querySelectorAll('.tab-btn');
        buttons.forEach(btn => {
            // Hapus style aktif
            btn.classList.remove('text-blue-600', 'border-b-2', 'border-blue-600');
            // Tambahkan style non-aktif
            btn.classList.add('hover:text-slate-600');
        });

        // 4. Tambahkan styling aktif ke tombol yang baru saja diklik
        clickedElement.classList.remove('hover:text-slate-600');
        clickedElement.classList.add('text-blue-600', 'border-b-2', 'border-blue-600');
    }
</script>
        </header>

        <section id="views" class="relative z-10 py-32 bg-white border-y border-slate-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

                <div class="text-center mb-24" data-aos="fade-up">
                    <h2 class="text-4xl md:text-5xl font-black text-slate-900 mb-4">Lihat Duniamu, <br> Dengan Caramu.</h2>
                    <p class="text-slate-500 text-lg">Beda proyek, beda gaya visual. Ganti tampilan hanya dengan satu klik.</p>
                </div>

                <div class="space-y-32 relative">

                    <div class="flex flex-col lg:flex-row items-center gap-12 lg:gap-16">
                        <div class="lg:w-1/3" data-aos="fade-right">
                            <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 mb-6">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-black text-slate-900 mb-3">List View Sederhana</h3>
                            <p class="text-slate-500 leading-relaxed">Cocok untuk mencatat ide cepat dan to-do list harian. Bersih, fokus, dan bebas gangguan.</p>
                        </div>
                        <div class="lg:w-2/3 w-full" data-aos="fade-left">
                            <div class="bg-slate-50 rounded-[3rem] p-8 md:p-12 border border-slate-200 shadow-xl shadow-slate-200/50">
                                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                                    <h4 class="font-bold text-slate-800 mb-4 border-b pb-2">Hari Ini</h4>
                                    <div class="space-y-3">
                                        <div class="flex items-center gap-4 group cursor-pointer">
                                            <div class="w-5 h-5 rounded border-2 border-slate-300 group-hover:border-blue-500 transition-colors"></div>
                                            <span class="text-slate-700 font-medium">Buat thumbnail YouTube</span>
                                            <span class="ml-auto text-xs font-bold text-blue-500 bg-blue-50 px-2 py-1 rounded">High</span>
                                        </div>
                                        <div class="flex items-center gap-4 group cursor-pointer">
                                            <div class="w-5 h-5 rounded border-2 border-slate-300 group-hover:border-blue-500 transition-colors"></div>
                                            <span class="text-slate-700 font-medium">Balas email brand endorsement</span>
                                        </div>
                                        <div class="flex items-center gap-4">
                                            <div class="w-5 h-5 rounded bg-blue-500 flex items-center justify-center text-white">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </div>
                                            <span class="text-slate-400 font-medium line-through">Upload reels IG</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col lg:flex-row items-center gap-12 lg:gap-16">
                        <div class="lg:w-1/3" data-aos="fade-right">
                            <div class="w-12 h-12 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-600 mb-6">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"></path>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-black text-slate-900 mb-3">Kanban Board Visual</h3>
                            <p class="text-slate-500 leading-relaxed">Pantau status produksi konten (Pre-production, Shooting, Editing) dengan sistem drag-and-drop.</p>
                        </div>
                        <div class="lg:w-2/3 w-full" data-aos="fade-left">
                            <div class="bg-blue-50 rounded-[3rem] p-8 md:p-12 border border-blue-100 shadow-xl shadow-blue-100/50">
                                <div class="flex gap-4 overflow-x-hidden no-scrollbar">
                                    <div class="w-64 bg-white rounded-2xl shadow-sm border border-slate-100 p-4 shrink-0">
                                        <div class="font-bold text-slate-700 mb-3 border-b pb-2">To Do</div>
                                        <div class="bg-slate-50 p-3 rounded-lg border border-slate-100 mb-2">
                                            <div class="h-3 w-1/2 bg-slate-300 rounded mb-2"></div>
                                            <div class="h-2 w-full bg-slate-200 rounded"></div>
                                        </div>
                                        <div class="bg-slate-50 p-3 rounded-lg border border-slate-100">
                                            <div class="h-3 w-3/4 bg-blue-300 rounded mb-2"></div>
                                            <div class="h-2 w-full bg-slate-200 rounded"></div>
                                        </div>
                                    </div>
                                    <div class="w-64 bg-white rounded-2xl shadow-sm border border-slate-100 p-4 shrink-0">
                                        <div class="font-bold text-slate-700 mb-3 border-b pb-2">Doing</div>
                                        <div class="bg-blue-600 p-3 rounded-lg shadow-md mb-2 text-white">
                                            <div class="h-3 w-1/2 bg-white/50 rounded mb-2"></div>
                                            <div class="h-2 w-full bg-white/30 rounded"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col lg:flex-row items-center gap-12 lg:gap-16">
                        <div class="lg:w-1/3" data-aos="fade-right">
                            <div class="w-12 h-12 bg-cyan-50 rounded-2xl flex items-center justify-center text-cyan-600 mb-6">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-black text-slate-900 mb-3">Timeline & Gantt</h3>
                            <p class="text-slate-500 leading-relaxed">Rencanakan campaign bulanan. Lihat irisan jadwal antar platform media sosial secara horizontal.</p>
                        </div>
                        <div class="lg:w-2/3 w-full" data-aos="fade-left">
                            <div class="bg-indigo-50 rounded-[3rem] p-8 md:p-12 border border-indigo-100 shadow-xl shadow-indigo-100/50">
                                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 overflow-hidden">
                                    <div class="flex border-b pb-2 mb-4">
                                        <div class="w-1/4 text-xs font-bold text-slate-400">Task</div>
                                        <div class="w-3/4 flex justify-between text-xs font-bold text-slate-400"><span>1 Okt</span><span>15 Okt</span><span>30 Okt</span></div>
                                    </div>
                                    <div class="flex mb-4 items-center">
                                        <div class="w-1/4 text-sm font-bold text-slate-700 truncate pr-2">Campaign A</div>
                                        <div class="w-3/4 relative h-6 bg-slate-50 rounded-full">
                                            <div class="absolute left-0 w-1/2 h-full bg-indigo-500 rounded-full"></div>
                                        </div>
                                    </div>
                                    <div class="flex mb-4 items-center">
                                        <div class="w-1/4 text-sm font-bold text-slate-700 truncate pr-2">Vlog Series</div>
                                        <div class="w-3/4 relative h-6 bg-slate-50 rounded-full">
                                            <div class="absolute left-1/4 w-1/3 h-full bg-pink-500 rounded-full"></div>
                                        </div>
                                    </div>
                                    <div class="flex items-center">
                                        <div class="w-1/4 text-sm font-bold text-slate-700 truncate pr-2">Podcast</div>
                                        <div class="w-3/4 relative h-6 bg-slate-50 rounded-full">
                                            <div class="absolute left-1/2 w-1/2 h-full bg-amber-400 rounded-full"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <section id="habits" class="py-32 relative z-10 bg-slate-900 overflow-hidden">
            <div class="absolute top-0 right-0 w-[30rem] h-[30rem] bg-indigo-600/20 blur-[120px] rounded-full"></div>
            <div class="absolute bottom-0 left-0 w-[30rem] h-[30rem] bg-blue-600/20 blur-[120px] rounded-full"></div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="flex flex-col lg:flex-row items-center gap-16">
                    <div class="lg:w-1/2" data-aos="fade-right">
                        <span class="text-indigo-400 font-bold tracking-widest uppercase text-xs mb-4 block">New Feature</span>
                        <h2 class="text-4xl md:text-5xl font-black text-white mb-6 leading-tight">Bangun Konsistensi. <br> Pantau <span class="text-indigo-400">Habits</span>-mu.</h2>
                        <p class="text-slate-400 text-lg mb-8 leading-relaxed">
                            Kreator sukses dibangun dari rutinitas harian. Lacak kebiasaan seperti "Menulis skrip 30 menit", "Olahraga", atau "Membaca trend" langsung di dalam kalendermu.
                        </p>

                        <div class="flex items-center gap-6 mt-10">
                            <div class="text-center">
                                <div class="text-3xl font-black text-white">🔥 14</div>
                                <div class="text-xs font-bold text-slate-500 uppercase tracking-widest mt-1">Day Streak</div>
                            </div>
                            <div class="w-px h-10 bg-slate-700"></div>
                            <div class="text-center">
                                <div class="text-3xl font-black text-indigo-400">85%</div>
                                <div class="text-xs font-bold text-slate-500 uppercase tracking-widest mt-1">Completion</div>
                            </div>
                        </div>
                    </div>

                    <div class="lg:w-1/2 w-full" data-aos="fade-left">
                        <div class="bg-slate-800/50 backdrop-blur-xl p-8 rounded-[2.5rem] border border-slate-700 shadow-2xl">
                            <div class="flex justify-between items-center mb-6">
                                <h4 class="font-bold text-white text-lg">Habits Minggu Ini</h4>
                            </div>

                            <div class="space-y-4">
                                <div class="bg-slate-900 p-4 rounded-2xl border border-slate-700 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-indigo-500/20 text-indigo-400 rounded-xl flex items-center justify-center text-lg">✍️</div>
                                        <div>
                                            <div class="font-bold text-white">Tulis Skrip Video</div>
                                            <div class="text-xs text-slate-400">Setiap Hari</div>
                                        </div>
                                    </div>
                                    <div class="flex gap-1.5">
                                        <div class="w-6 h-6 rounded bg-indigo-500 flex items-center justify-center"><svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg></div>
                                        <div class="w-6 h-6 rounded bg-indigo-500 flex items-center justify-center"><svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg></div>
                                        <div class="w-6 h-6 rounded bg-indigo-500 flex items-center justify-center"><svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg></div>
                                        <div class="w-6 h-6 rounded border border-slate-600 bg-slate-800 cursor-pointer hover:border-indigo-400"></div>
                                        <div class="w-6 h-6 rounded border border-slate-600 bg-slate-800"></div>
                                    </div>
                                </div>
                                <div class="bg-slate-900 p-4 rounded-2xl border border-slate-700 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-amber-500/20 text-amber-400 rounded-xl flex items-center justify-center text-lg">🏃</div>
                                        <div>
                                            <div class="font-bold text-white">Olahraga 30 Menit</div>
                                            <div class="text-xs text-slate-400">Sen, Rab, Jum</div>
                                        </div>
                                    </div>
                                    <div class="flex gap-1.5">
                                        <div class="w-6 h-6 rounded bg-amber-500 flex items-center justify-center"><svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg></div>
                                        <div class="w-6 h-6 rounded bg-transparent"></div>
                                        <div class="w-6 h-6 rounded bg-amber-500 flex items-center justify-center"><svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg></div>
                                        <div class="w-6 h-6 rounded bg-transparent"></div>
                                        <div class="w-6 h-6 rounded border border-slate-600 bg-slate-800 cursor-pointer hover:border-amber-400"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="pricing" class="py-32 relative z-10 bg-[#F8FAFC]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-20" data-aos="fade-up">
                    <span class="text-blue-600 font-bold tracking-widest uppercase text-xs mb-4 block">Investasi Terjangkau</span>
                    <h2 class="text-4xl md:text-5xl font-black text-slate-900 mb-6">Mulai Gratis, <br> Upgrade Sesuai Kebutuhan.</h2>
                    <p class="text-slate-500 text-lg">Pilih paket yang paling pas untuk ritme kerjamu sebagai konten kreator.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-center">
                    
                    <div class="bg-white rounded-[2.5rem] p-8 md:p-10 border border-slate-200 shadow-xl shadow-slate-200/50" data-aos="fade-up" data-aos-delay="0">
                        <h3 class="text-xl font-bold text-slate-900 mb-2">Starter</h3>
                        <p class="text-slate-500 text-sm mb-6">Cocok untuk kamu yang baru mulai.</p>
                        <div class="mb-8 flex items-baseline gap-1">
                            <span class="text-4xl font-black text-slate-900">Gratis</span>
                        </div>
                        <ul class="space-y-4 mb-8">
                            <li class="flex items-center gap-3 text-sm text-slate-600 font-medium">
                                <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Maksimal 3 Proyek Aktif
                            </li>
                            <li class="flex items-center gap-3 text-sm text-slate-600 font-medium">
                                <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                List & Kanban View
                            </li>
                            <li class="flex items-center gap-3 text-sm text-slate-600 font-medium">
                                <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Pelacak Habits (Maks 3)
                            </li>
                        </ul>
                        <a href="#" class="block w-full text-center bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold py-4 rounded-2xl transition-colors">Daftar Sekarang</a>
                    </div>

                    <div class="bg-slate-900 rounded-[3rem] p-10 border border-slate-800 shadow-2xl relative md:-mt-8 md:mb-8 transform transition-transform hover:-translate-y-2" data-aos="fade-up" data-aos-delay="100">
                        <div class="absolute top-0 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-gradient-to-r from-blue-600 to-cyan-500 text-white text-[10px] font-black px-4 py-1.5 rounded-full uppercase tracking-widest shadow-lg shadow-blue-500/30">Paling Populer</div>
                        <h3 class="text-2xl font-black text-white mb-2">Kreator Pro</h3>
                        <p class="text-slate-400 text-sm mb-6">Untuk kreator konsisten dengan banyak ide.</p>
                        <div class="mb-8 flex items-baseline gap-1">
                            <span class="text-4xl font-black text-white">Rp 99rb</span>
                            <span class="text-slate-400 text-sm font-medium">/bulan</span>
                        </div>
                        <ul class="space-y-4 mb-8">
                            <li class="flex items-center gap-3 text-sm text-slate-300 font-medium">
                                <svg class="w-5 h-5 text-cyan-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Proyek Tanpa Batas
                            </li>
                            <li class="flex items-center gap-3 text-sm text-slate-300 font-medium">
                                <svg class="w-5 h-5 text-cyan-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Semua Tampilan (Termasuk Gantt/Timeline)
                            </li>
                            <li class="flex items-center gap-3 text-sm text-slate-300 font-medium">
                                <svg class="w-5 h-5 text-cyan-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Habits Tracker Tanpa Batas
                            </li>
                            <li class="flex items-center gap-3 text-sm text-slate-300 font-medium">
                                <svg class="w-5 h-5 text-cyan-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Integrasi Kalender & AI Auto-schedule
                            </li>
                        </ul>
                        <a href="#" class="block w-full text-center bg-blue-600 hover:bg-blue-500 text-white font-bold py-4 rounded-2xl transition-all shadow-[0_0_30px_rgba(37,99,235,0.4)] hover:shadow-[0_0_50px_rgba(37,99,235,0.6)]">Mulai Trial Pro 14 Hari</a>
                    </div>

                    <div class="bg-white rounded-[2.5rem] p-8 md:p-10 border border-slate-200 shadow-xl shadow-slate-200/50" data-aos="fade-up" data-aos-delay="200">
                        <h3 class="text-xl font-bold text-slate-900 mb-2">Studio Tim</h3>
                        <p class="text-slate-500 text-sm mb-6">Cocok untuk tim kolaborasi atau agensi kecil.</p>
                        <div class="mb-8 flex items-baseline gap-1">
                            <span class="text-4xl font-black text-slate-900">Rp 249rb</span>
                            <span class="text-slate-500 text-sm font-medium">/bulan</span>
                        </div>
                        <ul class="space-y-4 mb-8">
                            <li class="flex items-center gap-3 text-sm text-slate-600 font-medium">
                                <svg class="w-5 h-5 text-indigo-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Semua Fitur Kreator Pro
                            </li>
                            <li class="flex items-center gap-3 text-sm text-slate-600 font-medium">
                                <svg class="w-5 h-5 text-indigo-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Akses Hingga 5 Anggota Tim
                            </li>
                            <li class="flex items-center gap-3 text-sm text-slate-600 font-medium">
                                <svg class="w-5 h-5 text-indigo-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Pengaturan Role (Admin/Editor)
                            </li>
                        </ul>
                        <a href="#" class="block w-full text-center bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-bold py-4 rounded-2xl transition-colors">Hubungi Sales</a>
                    </div>

                </div>
            </div>
        </section>

        <section class="py-24 relative z-10">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center" data-aos="zoom-in">
                <div class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-[3rem] p-12 md:p-20 text-white shadow-2xl relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>

                    <svg class="w-16 h-16 mx-auto mb-6 animate-pulse-slow text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>

                    <h2 class="text-4xl md:text-5xl font-black mb-6">Jangan Lewatkan Deadline.</h2>
                    <p class="text-blue-100 text-lg md:text-xl mb-10 max-w-2xl mx-auto">
                        Dapatkan notifikasi pintar melalui Email, Push Notification, atau integrasikan langsung dengan kalender bawaan HP Anda.
                    </p>
                    <button class="bg-white text-blue-700 hover:scale-105 hover:text-blue-500 px-10 py-4 rounded-full font-black text-lg transition-all duration-200 shadow-xl">
                        Mulai Gunakan UrPlan — Gratis
                    </button>
                </div>
            </div>
        </section>

        <footer class="relative z-10 bg-slate-950 py-16 text-white border-t border-slate-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-10">
                <div class="text-center md:text-left">
                    <div class="flex items-center justify-center md:justify-start gap-2 mb-4">
                        <img src="/images/logo-1.png" alt="UrPlan Logo" class="h-14 w-auto object-contain transition-transform duration-300 hover:scale-110">
                        <span class="text-white font-black text-xl tracking-tight">UrPlan</span>
                    </div>
                    <p class="text-slate-400 text-sm font-medium">© 2026 UrPlan Inc. Elevating creators worldwide.</p>
                </div>
                <div class="flex gap-8 text-sm font-semibold text-slate-400">
                    <a href="#" class="hover:text-white transition-colors">Privacy Policy</a>
                    <a href="#" class="hover:text-white transition-colors">Terms of Service</a>
                    <a href="#" class="hover:text-white transition-colors">Contact Support</a>
                </div>
            </div>
        </footer>
    </div>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            once: true,
            offset: 50,
        });
    </script>
</body>
</html>
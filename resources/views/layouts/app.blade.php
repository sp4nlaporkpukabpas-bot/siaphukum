<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Siap-HUKUM KPU</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        maroon: { 50: '#fff1f1', 600: '#b91c1c', 800: '#800000', 900: '#4a0000' },
                        gold: { 400: '#fbbf24', 500: '#f59e0b' },
                        slate: { 950: '#0f172a' }
                    },
                    borderRadius: { '2xl': '1rem', '3xl': '1.5rem' }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
        .sidebar-item-active { background: rgba(255,255,255,0.1); border-left: 4px solid #fbbf24; color: #fbbf24 !important; }
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }

        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #9f1239; /* Warna maroon saat dihover */
        }
    </style>
</head>
<body class="antialiased text-slate-800">

    <div class="flex min-h-screen overflow-hidden">
        
        {{-- Panggil Sidebar di sini --}}
        @include('layouts.partials.sidebar')

        <div class="flex-1 min-w-0 flex flex-col h-screen overflow-hidden">
            <header class="h-24 flex-shrink-0 flex items-center justify-between px-8 bg-white border-b border-slate-200 z-40">
                <div class="flex items-center gap-4 min-w-0">
                    <button class="md:hidden text-maroon-900 shrink-0"><i class="fas fa-bars-staggered text-xl"></i></button>
                    <div>
                        <h2 class="text-sm font-black text-slate-900 uppercase tracking-widest italic">@yield('title')</h2>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-tight">KPU Kabupaten Pasuruan</p>
                    </div>
                </div>
                
                <div class="flex items-center gap-4 shrink-0">
                    {{-- TOMBOL BANTUAN WA --}}
                    <div class="relative group">
                        <a href="https://wa.me/6285156431103" target="_blank" 
                            class="hidden sm:flex items-center gap-2 px-4 py-2 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-100 hover:bg-emerald-600 hover:text-white transition-all">
                            <i class="fab fa-whatsapp text-lg"></i>
                            <span class="text-[10px] font-black uppercase tracking-widest">Bantuan</span>
                        </a>
                        {{-- Tooltip Admin --}}
                        <div class="absolute right-0 top-full mt-3 w-64 p-4 bg-white rounded-2xl shadow-2xl border border-slate-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible group-hover:translate-y-1 transition-all z-50 pointer-events-none">
                            <div class="absolute -top-1.5 right-5 w-3 h-3 bg-white border-t border-l border-slate-100 rotate-45"></div>
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center shrink-0">
                                    <i class="fas fa-user-gear text-emerald-600"></i>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Admin Teknis</p>
                                    <p class="text-xs font-bold text-slate-800 leading-tight">M. Assegaf Purnomo Aji</p>
                                    <p class="text-[11px] text-slate-500 mt-1 font-mono">+62 851-5643-1103</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- DROPDOWN PROFIL & AKUN --}}
                    <div class="relative group">
                        <button class="flex items-center gap-4 p-1 rounded-2xl hover:bg-slate-50 transition-all">
                            <div class="hidden sm:block text-right">
                                <p class="text-xs font-black text-slate-900 uppercase leading-none">{{ auth()->user()->name }}</p>
                                <p class="text-[9px] font-bold text-maroon-700 uppercase mt-1">Lihat Opsi Akun</p>
                            </div>
                            <div class="w-12 h-12 bg-maroon-800 rounded-2xl flex items-center justify-center text-white font-bold shadow-lg ring-4 ring-maroon-50 shrink-0 border-2 border-white transition-transform group-hover:scale-105">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                        </button>

                        {{-- Isi Dropdown --}}
                        <div class="absolute right-0 top-full mt-2 w-56 bg-white rounded-2xl shadow-2xl border border-slate-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible group-hover:translate-y-1 transition-all z-50 overflow-hidden">
                            <div class="p-4 bg-slate-50/50 border-b border-slate-100">
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.1em]">Ganti Peran</p>
                                <form action="{{ route('role.switch') }}" method="POST" class="mt-2">
                                    @csrf
                                    <select name="role_id" onchange="this.form.submit()" 
                                        class="w-full bg-white text-[10px] font-black text-slate-700 uppercase px-3 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-maroon-500 outline-none cursor-pointer">
                                        @foreach(auth()->user()->roles as $role)
                                            <option value="{{ $role->id }}" {{ auth()->user()->active_role_id == $role->id ? 'selected' : '' }}>
                                                {{ $role->display_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </form>
                            </div>

                            <div class="p-2">
                                <a href="{{ route('password.edit') }}" class="flex items-center gap-3 px-4 py-3 text-[11px] font-bold text-slate-600 hover:bg-slate-50 hover:text-maroon-800 rounded-xl transition-all">
                                    <i class="fas fa-key w-4"></i>
                                    Ubah Password
                                </a>
                                
                                <hr class="my-1 border-slate-50">

                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-[11px] font-bold text-red-600 hover:bg-red-50 rounded-xl transition-all">
                                        <i class="fas fa-sign-out-alt w-4"></i>
                                        Keluar Sistem
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-8">
                <div class="max-w-7xl mx-auto">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

</body>
</html>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Siap-HUKUM KPU</title>
    <link rel="icon" type="image/png" href="{{ asset('/assets/img/siap-hukum-01.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('/assets/img/siap-hukum-01.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        maroon: { 50: '#fff1f1', 600: '#b91c1c', 700: '#9f1239', 800: '#800000', 900: '#4a0000' },
                        gold:   { 400: '#fbbf24', 500: '#f59e0b' },
                        slate:  { 950: '#0f172a' }
                    },
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #9f1239; }
    </style>
</head>
<body class="antialiased text-slate-800" x-data="{ mobileMenuOpen: false }">

<div class="flex min-h-screen overflow-hidden">

    {{-- Overlay mobile --}}
    <div x-show="mobileMenuOpen"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="mobileMenuOpen = false"
         class="fixed inset-0 bg-slate-900/60 z-50 md:hidden backdrop-blur-sm">
    </div>

    @include('layouts.partials.sidebar')

    <div class="flex-1 min-w-0 flex flex-col h-screen overflow-hidden">

        {{-- Header --}}
        <header class="h-16 flex-shrink-0 flex items-center justify-between px-4 md:px-6 bg-white border-b border-slate-200 z-40 gap-4">

            {{-- Kiri: burger + breadcrumb --}}
            <div class="flex items-center gap-3 min-w-0">
                <button @click="mobileMenuOpen = true"
                        class="md:hidden text-slate-600 p-2 hover:bg-slate-100 rounded-lg transition-colors shrink-0">
                    <i class="fas fa-bars text-base"></i>
                </button>
                {{-- Breadcrumb/Title opsional bisa ditambah di sini --}}
            </div>

            {{-- Kanan: bantuan + profil --}}
            <div class="flex items-center gap-2 shrink-0">

                {{-- Bantuan WA --}}
                <div class="relative group">
                    <a href="https://wa.me/6285156431103" target="_blank"
                       class="hidden sm:flex items-center gap-2 px-3 py-2 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-100 hover:bg-emerald-600 hover:text-white transition-all text-xs font-bold">
                        <i class="fab fa-whatsapp text-sm"></i>
                        <span class="uppercase tracking-widest text-[10px]">Bantuan</span>
                    </a>
                    {{-- Tooltip --}}
                    <div class="absolute right-0 top-full mt-2 w-60 p-4 bg-white rounded-2xl shadow-2xl border border-slate-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all z-50 pointer-events-none">
                        <div class="absolute -top-1.5 right-5 w-3 h-3 bg-white border-t border-l border-slate-100 rotate-45"></div>
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-full bg-emerald-100 flex items-center justify-center shrink-0">
                                <i class="fas fa-user-gear text-emerald-600 text-sm"></i>
                            </div>
                            <div>
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Admin Teknis</p>
                                <p class="text-xs font-bold text-slate-800">M. Assegaf Purnomo Aji</p>
                                <p class="text-[11px] text-slate-500 mt-1 font-mono">+62 851-5643-1103</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Profil Dropdown --}}
                <div class="relative group">
                    <button class="flex items-center gap-2 p-1 rounded-xl hover:bg-slate-50 transition-all">
                        <div class="hidden sm:block text-right">
                            <p class="text-xs font-black text-slate-900 leading-none">{{ auth()->user()->name }}</p>
                            <p class="text-[9px] font-bold text-maroon-700 uppercase mt-0.5">Lihat Opsi</p>
                        </div>
                        <div class="w-9 h-9 bg-maroon-800 rounded-xl flex items-center justify-center text-white font-bold shadow ring-2 ring-maroon-50 shrink-0 transition-transform group-hover:scale-105 text-sm">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                    </button>

                    <div class="absolute right-0 top-full mt-2 w-52 bg-white rounded-2xl shadow-2xl border border-slate-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible group-hover:translate-y-1 transition-all z-50 overflow-hidden">
                        <div class="p-3 bg-slate-50/50 border-b border-slate-100">
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2">Ganti Peran</p>
                            <form action="{{ route('role.switch') }}" method="POST">
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
                            <a href="{{ route('password.edit') }}"
                               class="flex items-center gap-3 px-3 py-2.5 text-xs font-bold text-slate-600 hover:bg-slate-50 hover:text-maroon-800 rounded-xl transition-all">
                                <i class="fas fa-key w-4 text-center"></i> Ubah Password
                            </a>
                            <hr class="my-1 border-slate-100">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit"
                                        class="w-full flex items-center gap-3 px-3 py-2.5 text-xs font-bold text-red-600 hover:bg-red-50 rounded-xl transition-all">
                                    <i class="fas fa-sign-out-alt w-4 text-center"></i> Keluar Sistem
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        {{-- Flash Messages --}}
        @if(session('success') || session('error'))
        <div class="px-4 md:px-6 pt-4">
            @if(session('success'))
            <div class="flex items-center gap-3 p-3 bg-emerald-50 border border-emerald-200 rounded-xl text-sm font-semibold text-emerald-700">
                <i class="fas fa-circle-check shrink-0"></i> {{ session('success') }}
            </div>
            @endif
            @if(session('error'))
            <div class="flex items-center gap-3 p-3 bg-red-50 border border-red-200 rounded-xl text-sm font-semibold text-red-700">
                <i class="fas fa-circle-exclamation shrink-0"></i> {{ session('error') }}
            </div>
            @endif
        </div>
        @endif

        <main class="flex-1 overflow-y-auto p-4 md:p-6 lg:p-8">
            <div class="max-w-7xl mx-auto">
                @yield('content')
            </div>
        </main>
    </div>
</div>

</body>
</html>

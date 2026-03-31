<aside 
    {{-- Logic transisi mobile --}}
    :class="mobileMenuOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'"
    class="fixed inset-y-0 left-0 w-72 bg-maroon-900 text-white flex-shrink-0 flex flex-col z-[60] shadow-2xl border-r border-white/5 transition-transform duration-300 ease-in-out md:relative md:flex">
    
    {{-- Header Logo --}}
    <div class="p-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="bg-white p-1.5 rounded-xl shadow-xl flex-shrink-0">
                    <img src="{{ asset('/assets/img/siap-hukum-01.png') }}" class="w-6 h-auto" alt="Logo">
                </div>
                <div class="overflow-hidden">
                    <h1 class="font-extrabold text-lg tracking-tighter leading-none">SIAP-<span class="text-gold-400">HUKUM</span></h1>
                    <p class="text-[8px] text-white/40 font-bold uppercase tracking-widest mt-1">KPU Kabupaten Pasuruan</p>
                </div>
            </div>
            {{-- Tombol tutup khusus mobile --}}
            <button @click="mobileMenuOpen = false" class="md:hidden text-white/50 hover:text-white">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
    </div>

    <nav class="flex-1 px-3 py-2 space-y-6 overflow-y-auto custom-scrollbar">
        {{-- DASHBOARD --}}
        <div>
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all group {{ request()->routeIs('dashboard') ? 'bg-white/10 text-gold-400' : 'text-white/60 hover:bg-white/5' }}">
                <div class="w-5 flex justify-center text-sm"><i class="fas fa-chart-pie"></i></div>
                <span class="text-sm font-bold tracking-wide">Dashboard</span>
            </a>
        </div>

        {{-- DOKUMEN BERDASARKAN IZIN --}}
        <div>
            <p class="px-4 text-[10px] font-black text-white/30 uppercase tracking-[0.2em] mb-2">Akses Dokumen</p>
            <div class="space-y-1">
                @if(isset($global_categories) && $global_categories->count() > 0)
                    @foreach($global_categories as $cat)
                        @php
                            $isActive = request()->is('kategori/' . $cat->id . '*') || request()->is('categories/' . $cat->id . '*');
                        @endphp
                        <a href="{{ route('categories.show', $cat->id) }}" 
                           class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all group text-sm font-medium 
                           {{ $isActive ? 'bg-white/10 text-gold-400' : 'text-white/60 hover:text-gold-400 hover:bg-white/5' }}">
                            
                            <div class="w-5 flex justify-center">
                                <i class="fas fa-folder-open text-[12px] {{ $isActive ? 'opacity-100' : 'opacity-50 group-hover:opacity-100' }}"></i>
                            </div>
                            <span class="truncate">{{ $cat->name }}</span>
                            
                            @if($isActive)
                                <div class="ml-auto w-1.5 h-1.5 rounded-full bg-gold-400 shadow-[0_0_8px_rgba(251,191,36,0.6)]"></div>
                            @endif
                        </a>
                    @endforeach
                @else
                    <div class="px-6 py-3 rounded-xl bg-white/5 border border-white/5 text-center">
                        <p class="text-[10px] text-white/30 italic leading-relaxed">
                            <i class="fas fa-lock mb-1 block text-lg"></i> Akses Terbatas
                        </p>
                    </div>
                @endif
            </div>
        </div>

        {{-- KHUSUS ROLE ADMIN --}}
        @if(auth()->user()->activeRole->name === 'admin')
            <div class="pt-4 border-t border-white/5">
                <p class="px-4 text-[10px] font-black text-gold-400/50 uppercase tracking-[0.2em] mb-4">Panel Administrator</p>
                
                <div class="mb-4">
                    <details class="group/dropdown" {{ request()->is('master*') ? 'open' : '' }}>
                        <summary class="flex items-center justify-between px-4 py-3 rounded-xl text-white/60 hover:bg-white/5 cursor-pointer list-none transition-all">
                            <div class="flex items-center gap-3">
                                <div class="w-5 flex justify-center text-sm"><i class="fas fa-database text-gold-400/70"></i></div>
                                <span class="text-sm font-bold tracking-wide">Data Master</span>
                            </div>
                            <i class="fas fa-chevron-right text-[10px] group-open/dropdown:rotate-90 transition-transform"></i>
                        </summary>
                        <div class="mt-1 ml-4 pl-4 border-l border-white/10 space-y-1">
                            <a href="{{ route('categories.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium {{ request()->routeIs('categories.index') ? 'text-gold-400 bg-white/5' : 'text-white/50 hover:text-gold-400' }}">
                                <i class="fas fa-tags text-[11px]"></i> Kelola Kategori
                            </a>
                            <a href="{{ route('documents.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium {{ request()->routeIs('documents.index') ? 'text-gold-400 bg-white/5' : 'text-white/50 hover:text-gold-400' }}">
                                <i class="fas fa-file-shield text-[11px]"></i> Master Dokumen
                            </a>
                            <a href="{{ route('rekap-register.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium {{ request()->routeIs('rekap-register.*') ? 'text-gold-400 bg-white/5' : 'text-white/50 hover:text-gold-400' }}">
                                <i class="fas fa-book-bookmark text-[11px]"></i> Rekapitulasi Arsip
                            </a>
                        </div>
                    </details>
                </div>

                <div>
                    <details class="group/dropdown" {{ request()->is('admin*') ? 'open' : '' }}>
                        <summary class="flex items-center justify-between px-4 py-3 rounded-xl text-white/60 hover:bg-white/5 cursor-pointer list-none transition-all">
                            <div class="flex items-center gap-3">
                                <div class="w-5 flex justify-center text-sm"><i class="fas fa-user-gear text-gold-400/70"></i></div>
                                <span class="text-sm font-bold tracking-wide">User & Akses</span>
                            </div>
                            <i class="fas fa-chevron-right text-[10px] group-open/dropdown:rotate-90 transition-transform"></i>
                        </summary>
                        <div class="mt-1 ml-4 pl-4 border-l border-white/10 space-y-1">
                            <a href="{{ route('users.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium {{ request()->routeIs('users.*') ? 'text-gold-400 bg-white/5' : 'text-white/50 hover:text-gold-400' }}">
                                <i class="fas fa-users-cog text-[11px]"></i> Manajemen User
                            </a>
                            <a href="{{ route('roles.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium {{ request()->routeIs('roles.*') ? 'text-gold-400 bg-white/5' : 'text-white/50 hover:text-gold-400' }}">
                                <i class="fas fa-shield-halved text-[11px]"></i> Manajemen Role
                            </a>
                        </div>
                    </details>
                </div>
            </div>

            <div>
                <a href="{{ route('access-logs.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all group {{ request()->routeIs('dashboard') ? 'bg-white/10 text-gold-400' : 'text-white/60 hover:bg-white/5' }}">
                    <div class="w-5 flex justify-center text-sm"><i class="fas fa-cog text-gold-400/70"></i></div>
                    <span class="text-sm font-bold tracking-wide">Akses Log</span>
                </a>
            </div>
        @endif
    </nav>

    <div class="p-4 border-t border-white/5">
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
        <button onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
                class="flex items-center justify-center gap-2 w-full bg-red-500/10 hover:bg-red-600 text-red-500 hover:text-white p-3 rounded-xl font-bold text-[10px] uppercase tracking-widest transition-all shadow-sm">
            <i class="fas fa-power-off text-xs"></i> Keluar Aplikasi
        </button>
    </div>
</aside>
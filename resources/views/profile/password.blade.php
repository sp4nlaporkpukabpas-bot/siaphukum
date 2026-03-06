@extends('layouts.app')
@section('title', 'Ubah Password')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-[2.5rem] shadow-2xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
        <div class="p-10">
            <div class="flex items-center gap-4 mb-8">
                <div class="w-12 h-12 bg-maroon-900 text-gold-400 rounded-2xl flex items-center justify-center text-xl">
                    <i class="fas fa-lock"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-extrabold text-slate-900 tracking-tighter uppercase">Ubah Password</h2>
                    <p class="text-xs text-slate-400 font-bold uppercase tracking-widest">Amankan akun Anda secara berkala</p>
                </div>
            </div>

            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-100 text-green-600 rounded-2xl text-xs font-bold uppercase tracking-wide flex items-center gap-3">
                    <i class="fas fa-check-circle text-lg"></i> {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('password.update') }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1">Password Saat Ini</label>
                    <input type="password" name="current_password" required
                        class="w-full px-5 py-4 bg-slate-50 border border-slate-100 rounded-2xl focus:ring-4 focus:ring-maroon-50 focus:border-maroon-200 outline-none transition-all text-sm font-bold">
                    @error('current_password') <p class="text-red-500 text-[10px] font-bold mt-2 ml-1 uppercase italic">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1">Password Baru</label>
                    <input type="password" name="password" required
                        class="w-full px-5 py-4 bg-slate-50 border border-slate-100 rounded-2xl focus:ring-4 focus:ring-maroon-50 focus:border-maroon-200 outline-none transition-all text-sm font-bold">
                    @error('password') <p class="text-red-500 text-[10px] font-bold mt-2 ml-1 uppercase italic">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" required
                        class="w-full px-5 py-4 bg-slate-50 border border-slate-100 rounded-2xl focus:ring-4 focus:ring-maroon-50 focus:border-maroon-200 outline-none transition-all text-sm font-bold">
                </div>

                <div class="pt-4">
                    <button type="submit" 
                        class="w-full py-4 bg-maroon-900 text-gold-400 rounded-2xl font-black uppercase tracking-widest text-xs hover:bg-black transition-all shadow-xl shadow-maroon-900/20 flex items-center justify-center gap-3">
                        <i class="fas fa-save"></i> Perbarui Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Siap-HUKUM KPU Pasuruan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        maroon: { 600: '#b91c1c', 800: '#800000', 900: '#5a0000' },
                        dark: '#0f172a'
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; height: 100vh; overflow: hidden; }
        .slide-content { display: none; }
        .slide-content.active { display: block; animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1); }
        @keyframes slideUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .dot.active { width: 2rem; background: white; opacity: 1; }
    </style>
</head>
<body class="bg-slate-50 flex items-center justify-center p-4">
    
    <div class="fixed inset-0 -z-10 bg-slate-50">
        <div class="absolute top-0 right-0 w-1/2 h-full bg-white skew-x-[-8deg] translate-x-32 border-l border-slate-100 hidden md:block"></div>
    </div>

    <div class="relative z-10 w-full max-w-[1000px] bg-white shadow-2xl rounded-[32px] overflow-hidden flex flex-col md:flex-row border border-slate-200">
        
        <div class="hidden md:flex md:w-[42%] bg-maroon-900 relative p-12 flex-col justify-between overflow-hidden">
            <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')]"></div>
            
            <div class="relative z-10">
                <div class="flex items-center gap-4 mb-16">
                    <div class="bg-white p-2 rounded-2xl shadow-lg">
                        <img src="{{ asset('assets/img/logo_kpu.png') }}" class="w-10" alt="Logo KPU">
                    </div>
                    <div class="text-white">
                        <h1 class="font-black text-2xl tracking-tighter leading-none uppercase italic">Siap-<span class="text-yellow-400">HUKUM</span></h1>
                        <p class="text-[8px] font-black tracking-[0.3em] text-white/50 uppercase mt-1">Kabupaten Pasuruan</p>
                    </div>
                </div>

                <div id="slider-container" class="min-h-[150px]">
                    <div class="slide-content active">
                        <h2 class="text-white text-3xl font-extrabold leading-tight mb-4 uppercase italic">Sistem Informasi <br> <span class="text-yellow-400 text-4xl font-black">Produk Hukum.</span></h2>
                        <p class="text-white/60 text-sm leading-relaxed font-medium">Transformasi tata kelola hukum yang transparan, akuntabel, dan efisien dalam satu pintu.</p>
                    </div>
                    <div class="slide-content">
                        <h2 class="text-white text-3xl font-extrabold leading-tight mb-4 uppercase italic">Akses Data <br> <span class="text-yellow-400 text-4xl font-black">Real-Time.</span></h2>
                        <p class="text-white/60 text-sm leading-relaxed font-medium">Pencarian arsip produk hukum terbaru hanya dalam hitungan detik.</p>
                    </div>
                </div>
            </div>

            <div class="relative z-10 flex gap-2" id="paging-dots">
                <button onclick="goToSlide(0)" class="dot active h-1.5 w-6 bg-white/20 rounded-full transition-all duration-500"></button>
                <button onclick="goToSlide(1)" class="dot h-1.5 w-6 bg-white/20 rounded-full transition-all duration-500"></button>
            </div>
        </div>

        <div class="w-full md:w-[58%] p-8 sm:p-16 flex flex-col justify-center relative">
            
            <div class="max-w-[400px] mx-auto w-full">
                <div class="mb-10">
                    <h3 class="text-4xl font-black text-dark mb-3 tracking-tight">Selamat Datang</h3>
                    <p class="text-slate-400 text-sm font-bold uppercase tracking-wide">Silakan login untuk mengelola dokumen</p>
                </div>

                @if(session('error'))
                <div class="mb-6 flex items-center gap-3 bg-red-50 border border-red-100 text-red-600 p-4 rounded-2xl animate-bounce">
                    <i class="fas fa-circle-exclamation text-lg"></i>
                    <p class="text-[11px] font-black uppercase tracking-tight leading-tight">{{ session('error') }}</p>
                </div>
                @endif

                @if(session('success'))
                <div class="mb-6 flex items-center gap-3 bg-emerald-50 border border-emerald-100 text-emerald-600 p-4 rounded-2xl">
                    <i class="fas fa-check-circle text-lg"></i>
                    <p class="text-[11px] font-black uppercase tracking-tight leading-tight">{{ session('success') }}</p>
                </div>
                @endif

                <form action="{{ route('login') }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Kredensial Pengguna</label>
                        <div class="relative group">
                            <input type="text" name="nip" value="{{ old('nip') }}"
                                class="w-full px-6 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:bg-white focus:border-maroon-800 outline-none transition-all font-bold text-dark placeholder:text-slate-300" 
                                placeholder="NIP atau Username" required autofocus>
                            <i class="fas fa-user-shield absolute right-6 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-maroon-800 transition-colors"></i>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <div class="flex justify-between items-center px-1">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Kata Sandi</label>
                            <a href="#" class="text-[10px] font-black text-maroon-800 hover:text-dark transition-colors uppercase tracking-widest">Lupa Password?</a>
                        </div>
                        <div class="relative group">
                            <input type="password" name="password" id="password"
                                class="w-full px-6 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:bg-white focus:border-maroon-800 outline-none transition-all font-bold text-dark placeholder:text-slate-300" 
                                placeholder="••••••••" required>
                            <button type="button" onclick="togglePassword()" class="absolute right-6 top-1/2 -translate-y-1/2 text-slate-300 hover:text-maroon-800">
                                <i id="pass-icon" class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-maroon-900 text-white font-black py-5 rounded-2xl shadow-xl shadow-maroon-900/30 hover:bg-black hover:-translate-y-1 transition-all active:scale-[0.98] uppercase text-xs tracking-[0.3em]">
                        Masuk Sistem <i class="fas fa-arrow-right-long ml-2"></i>
                    </button>
                </form>

                <div class="mt-12 pt-8 border-t border-slate-100 flex flex-col sm:flex-row gap-4 justify-between items-center">
                    <a href="/" class="group flex items-center gap-2 text-[10px] font-black text-slate-400 hover:text-maroon-800 transition-colors uppercase tracking-widest">
                        <i class="fas fa-chevron-left text-[8px]"></i> Website Utama
                    </a>
                    <div class="flex items-center gap-2 px-4 py-2 bg-slate-50 rounded-full">
                         <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
                         <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest">System Operational</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Password Toggle
        function togglePassword() {
            const passInput = document.getElementById('password');
            const passIcon = document.getElementById('pass-icon');
            if (passInput.type === 'password') {
                passInput.type = 'text';
                passIcon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                passInput.type = 'password';
                passIcon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }

        // Slider Logic
        let currentSlide = 0;
        const slides = document.querySelectorAll('.slide-content');
        const dots = document.querySelectorAll('.dot');
        let slideInterval;

        function showSlide(index) {
            slides.forEach(s => s.classList.remove('active'));
            dots.forEach(d => d.classList.remove('active'));
            slides[index].classList.add('active');
            dots[index].classList.add('active');
            currentSlide = index;
        }

        function goToSlide(index) {
            showSlide(index);
            clearInterval(slideInterval);
            slideInterval = setInterval(nextSlide, 6000);
        }

        function nextSlide() {
            let next = (currentSlide + 1) % slides.length;
            showSlide(next);
        }

        slideInterval = setInterval(nextSlide, 6000);
    </script>
</body>
</html>
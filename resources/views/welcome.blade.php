<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Siap-HUKUM - KPU Kabupaten Pasuruan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .card-hover { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        /* Animasi halus untuk ilustrasi */
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0px); }
        }
        .float-animation { animation: float 6s ease-in-out infinite; }
    </style>
</head>
<body class="bg-white font-sans text-gray-900 overflow-x-hidden">

    <nav class="fixed w-full z-50 bg-white/90 backdrop-blur-md border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-6 lg:px-12 py-4 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <img src="https://upload.wikimedia.org/wikipedia/commons/4/46/KPU_Logo.svg" alt="Logo KPU" class="w-10">
                <span class="font-black text-2xl tracking-tighter text-gray-800 uppercase">Siap-<span class="text-red-700">Hukum</span></span>
            </div>
            <a href="{{ route('login') }}" class="bg-red-700 text-white px-6 py-2.5 rounded-full font-bold hover:bg-red-800 transition shadow-lg shadow-red-200 text-sm flex items-center gap-2">
                Masuk <i class="fas fa-sign-in-alt"></i>
            </a>
        </div>
    </nav>

    <section class="relative min-h-screen flex items-center pt-20 px-6 overflow-hidden">
        <div class="absolute top-0 right-0 -z-10 w-1/2 h-1/2 bg-red-50/50 rounded-full blur-[120px] translate-x-1/3 -translate-y-1/4"></div>
        <div class="absolute bottom-0 left-0 -z-10 w-1/3 h-1/3 bg-blue-50/50 rounded-full blur-[100px] -translate-x-1/4 translate-y-1/4"></div>

        <div class="max-w-7xl mx-auto w-full">
            <div class="flex flex-col lg:flex-row items-center gap-12 lg:gap-8">
                
                <div class="w-full lg:w-3/5 text-center lg:text-left z-10">
                    <div class="inline-flex items-center gap-2 bg-red-50 text-red-700 px-4 py-2 rounded-full text-xs font-extrabold uppercase tracking-[0.2em] mb-8 border border-red-100 shadow-sm">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-red-600"></span>
                        </span>
                        E-Arsip KPU Kabupaten Pasuruan
                    </div>
                    
                    <h1 class="text-4xl md:text-6xl lg:text-7xl font-black text-gray-900 leading-[1.1] mb-6 uppercase italic tracking-tight">
                        Sistem Informasi Arsip <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-red-700 to-red-500">Produk Hukum.</span>
                    </h1>
                    
                    <p class="text-gray-500 text-lg md:text-xl mb-10 max-w-2xl mx-auto lg:mx-0 leading-relaxed font-medium">
                        Kelola Arsip Hukum Komisi Pemilihan Umum Kabupaten Pasuruan dengan sistem <span class="text-gray-800 font-bold underline decoration-red-500/30">SIAP-HUKUM</span>. Lebih rapi, lebih cepat, dan sepenuhnya terintegrasi.
                    </p>

                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        <a href="{{ route('login') }}" class="group bg-gray-900 text-white px-10 py-4 rounded-2xl font-bold text-lg hover:bg-red-700 transition-all duration-300 shadow-2xl flex items-center justify-center gap-3">
                            Mulai Sekarang
                            <i class="fas fa-arrow-right group-hover:translate-x-2 transition-transform"></i>
                        </a>
                        <div class="flex items-center justify-center gap-4 px-6 py-4">
                            <div class="flex -space-x-3">
                                <div class="w-10 h-10 rounded-full border-2 border-white bg-gray-200 flex items-center justify-center"><i class="fas fa-user-check text-xs text-gray-600"></i></div>
                                <div class="w-10 h-10 rounded-full border-2 border-white bg-gray-300 flex items-center justify-center"><i class="fas fa-shield-halved text-xs text-gray-600"></i></div>
                            </div>
                            <span class="text-sm font-bold text-gray-400 uppercase tracking-widest">Internal Access Only</span>
                        </div>
                    </div>
                </div>

                <div class="w-full lg:w-2/5 flex justify-center items-center relative mt-12 lg:mt-0">
    <div class="relative w-full max-w-[280px] sm:max-w-md mx-auto">
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[110%] h-[110%] bg-gradient-to-tr from-red-100 to-transparent rounded-full opacity-50 -z-10"></div>
        
        <img src="https://illustrations.popsy.co/gray/data-analysis.svg" alt="Digital Archive" class="w-full h-auto float-animation block mx-auto">
        
        <div class="absolute -bottom-4 -left-2 sm:-left-4 bg-white p-3 sm:p-4 rounded-2xl shadow-xl border border-gray-50 flex items-center gap-3 scale-90 sm:scale-100">
            <div class="w-8 h-8 sm:w-10 sm:h-10 bg-green-100 text-green-600 rounded-full flex items-center justify-center shrink-0">
                <i class="fas fa-check-circle text-xs sm:text-base"></i>
            </div>
            <div>
                <p class="text-[8px] sm:text-[10px] text-gray-400 uppercase font-bold tracking-tighter">Status Sistem</p>
                <p class="text-xs sm:text-sm font-black text-gray-800 tracking-tight whitespace-nowrap">Online & Terenkripsi</p>
            </div>
        </div>
    </div>
</div>

            </div>
        </div>
    </section>

    <section class="py-24 bg-gray-50 px-6">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-black text-gray-900 uppercase italic mb-4">Keunggulan Sistem</h2>
                <div class="h-1.5 w-24 bg-red-700 mx-auto rounded-full"></div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="card-hover bg-white p-10 rounded-[2.5rem] border border-gray-100 shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all">
                    <div class="bg-red-50 w-16 h-16 rounded-2xl flex items-center justify-center text-red-700 mb-8">
                         <i class="fas fa-lock text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-black mb-4 uppercase italic tracking-tight">Penyimpanan Aman</h3>
                    <p class="text-gray-500 leading-relaxed italic">Arsip digital tersentralisasi dengan protokol keamanan data internal KPU.</p>
                </div>
                
                <div class="card-hover bg-white p-10 rounded-[2.5rem] border border-gray-100 shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all">
                    <div class="bg-blue-50 w-16 h-16 rounded-2xl flex items-center justify-center text-blue-700 mb-8">
                        <i class="fas fa-bolt text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-black mb-4 uppercase italic tracking-tight">Akses Instan</h3>
                    <p class="text-gray-500 leading-relaxed italic">Temukan dokumen dalam hitungan detik melalui fitur pencarian cerdas berbasis kategori.</p>
                </div>

                <div class="card-hover bg-white p-10 rounded-[2.5rem] border border-gray-100 shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all">
                    <div class="bg-emerald-50 w-16 h-16 rounded-2xl flex items-center justify-center text-emerald-700 mb-8">
                        <i class="fas fa-cloud-arrow-down text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-black mb-4 uppercase italic tracking-tight">Distribusi Mudah</h3>
                    <p class="text-gray-500 leading-relaxed italic">Unduh dokumen secara massal dalam format ZIP untuk kebutuhan pelaporan cepat.</p>
                </div>
            </div>
        </div>
    </section>

    <footer class="py-12 border-t border-gray-100 text-center px-6">
        <div class="flex justify-center items-center gap-3 mb-6">
            <img src="https://upload.wikimedia.org/wikipedia/commons/4/46/KPU_Logo.svg" alt="Logo KPU" class="w-8">
            <span class="font-black text-black tracking-tighter uppercase">KPU Kabupaten Pasuruan</span>
        </div>
        <p class="text-gray-400 text-xs uppercase font-bold tracking-[0.3em]">
            &copy; 2026 Siap-HUKUM. Sub Bagian Hukum & SDM.
        </p>
    </footer>

</body>
</html>
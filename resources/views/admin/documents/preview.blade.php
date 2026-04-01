@extends('layouts.app')
@section('title', 'Pratinjau: ' . $document->name)

@section('content')
<div class="max-w-5xl mx-auto">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div class="flex items-start sm:items-center gap-3">
            <div class="w-10 h-10 bg-maroon-900 text-yellow-400 rounded-xl flex items-center justify-center shadow-md shrink-0">
                <i class="fas fa-shield-check"></i>
            </div>
            <div class="min-w-0">
                <h1 class="text-base font-black text-slate-900 uppercase tracking-tight leading-tight truncate">{{ $document->name }}</h1>
                <p class="text-[10px] text-maroon-700 font-bold uppercase tracking-widest mt-1 flex items-center gap-1.5">
                    <span class="inline-block w-1.5 h-1.5 bg-red-500 rounded-full animate-pulse"></span>
                    Mode Pratinjau Terbatas
                </p>
            </div>
        </div>
        <a href="{{ url()->previous() }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-slate-200 text-slate-600 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-slate-50 transition-all shadow-sm shrink-0">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    {{-- Info Bar --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 mb-5">
        <div class="bg-white border border-slate-100 rounded-xl px-4 py-3 shadow-sm">
            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Nomor</p>
            <p class="text-xs font-bold text-slate-700 mt-1 font-mono">{{ $document->document_number }}</p>
        </div>
        <div class="bg-white border border-slate-100 rounded-xl px-4 py-3 shadow-sm">
            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Tanggal</p>
            <p class="text-xs font-bold text-slate-700 mt-1">{{ $document->document_date->translatedFormat('d F Y') }}</p>
        </div>
        <div class="bg-white border border-slate-100 rounded-xl px-4 py-3 shadow-sm col-span-2 sm:col-span-1">
            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Kategori</p>
            <p class="text-xs font-bold text-slate-700 mt-1">{{ $document->category->name }}</p>
        </div>
    </div>

    {{-- Viewer Frame --}}
    <div class="relative bg-slate-800 rounded-2xl overflow-hidden shadow-xl border-4 border-slate-700"
         style="height: clamp(400px, 70vh, 800px);">

        {{-- Shield overlay (top) - mencegah klik toolbar PDF --}}
        <div class="absolute top-0 left-0 w-full h-14 z-20" oncontextmenu="return false;"></div>

        {{-- ============================================================
             WATERMARK OVERLAY LAYER
             Layer ini berada di atas iframe namun pointer-events: none
             agar scroll PDF tetap bisa dilakukan.
             Watermark dirender lewat Canvas agar susah di-inspect/salin.
        ============================================================ --}}
        <canvas id="watermarkCanvas"
                class="absolute inset-0 w-full h-full z-10"
                style="pointer-events: none;"
                oncontextmenu="return false;"></canvas>

        <iframe
            id="documentFrame"
            src="{{ route('documents.view-secure', $document->id) }}#toolbar=0&navpanes=0&scrollbar=0"
            class="w-full h-full border-none"
            style="pointer-events: fill;"
            oncontextmenu="return false;">
        </iframe>
    </div>

    <div class="mt-4 text-center">
        <p class="text-[10px] text-slate-400 font-medium bg-slate-50 inline-block px-4 py-2 rounded-full border border-slate-100">
            <i class="fas fa-lock text-slate-300 mr-1"></i>
            Pencetakan dan pengunduhan dinonaktifkan untuk keamanan dokumen.
        </p>
    </div>
</div>

<script>
    // ================================================================
    // SECURITY: Blokir shortcut keyboard berbahaya
    // ================================================================
    document.addEventListener('keydown', function (e) {
        if ((e.ctrlKey || e.metaKey) && ['s', 'p', 'u'].includes(e.key.toLowerCase())) {
            e.preventDefault();
            alert('Aksi ini dibatasi untuk keamanan dokumen.');
        }
        if (e.ctrlKey && e.shiftKey && ['i', 'j', 'c'].includes(e.key.toLowerCase())) {
            e.preventDefault();
        }
        // Blokir Print Screen
        if (e.key === 'PrintScreen') {
            e.preventDefault();
        }
    });
    document.addEventListener('contextmenu', e => e.preventDefault());

    // ================================================================
    // WATERMARK ENGINE
    // Dirender ke Canvas dengan teks + info pengguna dinamis dari server.
    // Menggunakan pola diagonal berulang (tiled pattern).
    // ================================================================

    /**
     * Konfigurasi watermark — sesuaikan sesuai kebutuhan sistem Anda.
     */
    const WATERMARK_CONFIG = {
        // --- Mode: 'text' | 'logo' | 'both' ---
        mode: 'both',

        // --- Teks watermark (bisa diisi dari Blade / auth user) ---
        lines: [
            'PRATINJAU',                        // Baris 1: label tetap
            '{{ auth()->user()->name ?? "TAMU" }}',  // Baris 2: nama pengguna login
            '{{ $document->document_number }}', // Baris 3: nomor dokumen
        ],

        // --- URL logo (opsional, untuk mode 'logo' atau 'both') ---
        // Ganti dengan asset logo instansi Anda, contoh:
        //   logoUrl: "{{ asset('images/logo.png') }}",
        logoUrl: null,   // null = tidak pakai logo
        logoWidth: 80,   // lebar logo dalam pixel di canvas
        logoOpacity: 0.08,

        // --- Tampilan teks ---
        fontFamily: 'monospace',
        fontSizePx: 13,
        textColor: 'rgba(100, 20, 20, 0.18)',   // merah maroon transparan
        fontWeight: 'bold',

        // --- Pola tile ---
        tileWidth: 260,   // jarak horizontal antar watermark
        tileHeight: 130,  // jarak vertikal antar watermark
        rotateDeg: -35,   // sudut kemiringan (derajat)

        // --- Animasi: watermark bergerak perlahan agar susah diblok --
        animated: true,
        speedX: 0.15,  // pixel per frame arah X
        speedY: 0.08,  // pixel per frame arah Y
    };

    (function initWatermark(cfg) {
        const canvas = document.getElementById('watermarkCanvas');
        if (!canvas) return;

        const ctx = canvas.getContext('2d');
        let offsetX = 0;
        let offsetY = 0;
        let logoImage = null;

        /**
         * Sinkronkan ukuran canvas dengan elemen parent
         */
        function syncSize() {
            const parent = canvas.parentElement;
            canvas.width  = parent.offsetWidth;
            canvas.height = parent.offsetHeight;
        }

        /**
         * Gambar satu tile watermark (teks dan/atau logo)
         */
        function drawTile(tileCtx, tileW, tileH) {
            tileCtx.clearRect(0, 0, tileW, tileH);
            tileCtx.save();
            tileCtx.translate(tileW / 2, tileH / 2);
            tileCtx.rotate((cfg.rotateDeg * Math.PI) / 180);

            const mode = cfg.mode;

            // --- Gambar logo (jika ada) ---
            if ((mode === 'logo' || mode === 'both') && logoImage) {
                const ratio = logoImage.naturalHeight / logoImage.naturalWidth;
                const lw = cfg.logoWidth;
                const lh = lw * ratio;
                tileCtx.globalAlpha = cfg.logoOpacity;
                tileCtx.drawImage(logoImage, -lw / 2, -(lh / 2) - (mode === 'both' ? 20 : 0), lw, lh);
                tileCtx.globalAlpha = 1;
            }

            // --- Gambar teks (jika mode text atau both) ---
            if (mode === 'text' || mode === 'both') {
                tileCtx.font = `${cfg.fontWeight} ${cfg.fontSizePx}px ${cfg.fontFamily}`;
                tileCtx.fillStyle = cfg.textColor;
                tileCtx.textAlign = 'center';
                tileCtx.textBaseline = 'middle';

                const lineSpacing = cfg.fontSizePx + 5;
                const startY = (mode === 'both' && logoImage)
                    ? (cfg.logoWidth * 0.3)   // geser ke bawah jika ada logo
                    : -((cfg.lines.length - 1) * lineSpacing) / 2;

                cfg.lines.forEach((line, i) => {
                    tileCtx.fillText(line, 0, startY + i * lineSpacing);
                });
            }

            tileCtx.restore();
        }

        /**
         * Render seluruh canvas dengan pattern tile watermark
         */
        function render() {
            syncSize();
            ctx.clearRect(0, 0, canvas.width, canvas.height);

            // Buat offscreen canvas untuk satu tile
            const tileCanvas = document.createElement('canvas');
            tileCanvas.width  = cfg.tileWidth;
            tileCanvas.height = cfg.tileHeight;
            const tileCtx = tileCanvas.getContext('2d');
            drawTile(tileCtx, cfg.tileWidth, cfg.tileHeight);

            // Tile pattern di seluruh canvas, geser dengan offset animasi
            const startX = (offsetX % cfg.tileWidth) - cfg.tileWidth;
            const startY = (offsetY % cfg.tileHeight) - cfg.tileHeight;

            for (let x = startX; x < canvas.width + cfg.tileWidth; x += cfg.tileWidth) {
                for (let y = startY; y < canvas.height + cfg.tileHeight; y += cfg.tileHeight) {
                    ctx.drawImage(tileCanvas, x, y);
                }
            }
        }

        /**
         * Loop animasi
         */
        function animate() {
            if (cfg.animated) {
                offsetX = (offsetX + cfg.speedX) % cfg.tileWidth;
                offsetY = (offsetY + cfg.speedY) % cfg.tileHeight;
            }
            render();
            requestAnimationFrame(animate);
        }

        // --- Load logo jika ada, lalu mulai render ---
        if ((cfg.mode === 'logo' || cfg.mode === 'both') && cfg.logoUrl) {
            const img = new Image();
            img.crossOrigin = 'anonymous';
            img.onload = () => {
                logoImage = img;
                animate();
            };
            img.onerror = () => {
                // Logo gagal dimuat, tetap render teks saja
                animate();
            };
            img.src = cfg.logoUrl;
        } else {
            animate();
        }

        // Resize handler
        window.addEventListener('resize', render);

    })(WATERMARK_CONFIG);
</script>
@endsection

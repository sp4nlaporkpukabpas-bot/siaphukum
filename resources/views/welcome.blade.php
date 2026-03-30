<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Siap-HUKUM · KPU Kabupaten Pasuruan</title>
<link rel="icon" type="image/png" href="{{ asset('/assets/img/siap-hukum-01.png') }}">
<link rel="apple-touch-icon" href="{{ asset('/assets/img/siap-hukum-01.png') }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
:root {
  --bg: #09090b;
  --bg2: #111113;
  --surface: #18181b;
  --surface2: #27272a;
  --border: rgba(255,255,255,0.08);
  --border-h: rgba(255,255,255,0.14);
  --text: #fafafa;
  --text2: #a1a1aa;
  --text3: #52525b;
  --red: #dc2626;
  --red-glow: rgba(220,38,38,0.35);
  --red-soft: rgba(220,38,38,0.12);
  --gold: #f59e0b;
  --green: #22c55e;
}
[data-theme="light"] {
  --bg: #ffffff;
  --bg2: #fafafa;
  --surface: #f4f4f5;
  --surface2: #e4e4e7;
  --border: rgba(0,0,0,0.07);
  --border-h: rgba(0,0,0,0.12);
  --text: #09090b;
  --text2: #52525b;
  --text3: #a1a1aa;
  --red-glow: rgba(220,38,38,0.15);
  --red-soft: rgba(220,38,38,0.07);
}

*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
html{scroll-behavior:smooth}
body{
  font-family:'Sora',system-ui,sans-serif;
  background:var(--bg);color:var(--text);
  overflow-x:hidden;transition:background .3s,color .3s;
  -webkit-font-smoothing:antialiased;
}

/* ── GRID BG ── */
.grid-bg{
  position:fixed;inset:0;z-index:0;pointer-events:none;
  background-image:
    linear-gradient(var(--border) 1px,transparent 1px),
    linear-gradient(90deg,var(--border) 1px,transparent 1px);
  background-size:64px 64px;
  mask-image:radial-gradient(ellipse 80% 60% at 50% 0%,black 30%,transparent 100%);
  transition:background .3s;
}
.radial-glow{
  position:fixed;top:-30%;left:50%;transform:translateX(-50%);
  width:900px;height:600px;
  background:radial-gradient(ellipse,rgba(220,38,38,0.18) 0%,transparent 65%);
  pointer-events:none;z-index:0;transition:background .3s;
}

/* ── NAV ── */
nav{
  position:fixed;top:0;left:0;right:0;z-index:100;
  height:60px;display:flex;align-items:center;
  padding:0 clamp(20px,5vw,60px);
  background:rgba(255,255,255,.75);
  backdrop-filter:blur(24px) saturate(1.8);
  border-bottom:1px solid var(--border);
  transition:all .3s;
}
[data-theme="dark"] nav{background:rgba(9,9,11,.7);}
.nav-logo{display:flex;align-items:center;gap:10px;text-decoration:none}
.nav-logo img{width:30px;height:30px;object-fit:contain}
.logo-name{font-size:.95rem;font-weight:800;letter-spacing:-.02em;color:var(--text)}
.logo-name span{color:var(--red)}
.nav-right{margin-left:auto;display:flex;align-items:center;gap:8px}
.theme-btn{
  display:flex;align-items:center;gap:6px;
  padding:6px 14px;border-radius:8px;
  border:1px solid var(--border);
  background:var(--surface);color:var(--text2);
  font-family:'Sora',sans-serif;font-size:.75rem;font-weight:600;
  cursor:pointer;transition:all .2s;letter-spacing:.01em;
}
.theme-btn:hover{border-color:var(--border-h);color:var(--text);background:var(--surface2)}

/* ── HERO ── */
.hero{
  min-height:100vh;display:flex;align-items:center;
  padding:clamp(80px,10vh,120px) clamp(20px,5vw,60px) 80px;
  position:relative;z-index:1;
}
.hero-wrap{
  max-width:1240px;margin:0 auto;width:100%;
  display:grid;grid-template-columns:1fr 400px;gap:80px;align-items:center;
}

/* ── HERO LEFT ── */
.tag{
  display:inline-flex;align-items:center;gap:8px;
  padding:5px 12px 5px 8px;border-radius:100px;
  border:1px solid var(--border-h);background:var(--surface);
  font-size:.72rem;font-weight:600;color:var(--text2);
  margin-bottom:28px;
  animation:up .8s ease both;
}
.tag-dot{width:7px;height:7px;border-radius:50%;background:var(--green);box-shadow:0 0 0 3px rgba(34,197,94,.2);animation:pulse 2s infinite}
@keyframes pulse{0%,100%{box-shadow:0 0 0 3px rgba(34,197,94,.2)}50%{box-shadow:0 0 0 6px rgba(34,197,94,.08)}}

h1{
  font-size:clamp(2.6rem,5vw,4.8rem);
  font-weight:800;line-height:1.08;letter-spacing:-.04em;
  margin-bottom:20px;animation:up .8s .08s ease both;
}
h1 .accent{
  background:linear-gradient(135deg,var(--red) 0%,#ff6b6b 100%);
  -webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;
}
.hero-sub{
  font-size:1rem;color:var(--text2);line-height:1.75;
  max-width:480px;margin-bottom:40px;font-weight:400;
  animation:up .8s .16s ease both;
}
.hero-pills{
  display:flex;flex-wrap:wrap;gap:8px;
  animation:up .8s .24s ease both;
}
.pill{
  display:flex;align-items:center;gap:6px;
  padding:7px 14px;border-radius:8px;
  border:1px solid var(--border);background:var(--surface);
  font-size:.75rem;font-weight:600;color:var(--text2);
}
.pill i{font-size:.65rem;color:var(--red)}

/* ── LOGIN CARD ── */
.card{
  background:var(--surface);
  border:1px solid var(--border-h);
  border-radius:20px;
  padding:32px;
  box-shadow:0 0 0 1px var(--border),0 8px 40px rgba(0,0,0,.08);
  animation:up .8s .1s ease both;
  transition:background .3s,border-color .3s;
  position:relative;overflow:hidden;
}
[data-theme="dark"] .card{box-shadow:0 0 0 1px var(--border),0 24px 80px rgba(0,0,0,.4),0 0 60px var(--red-glow);}
.card::before{
  content:'';position:absolute;top:0;left:0;right:0;height:1px;
  background:linear-gradient(90deg,transparent,var(--red) 50%,transparent);
  opacity:.6;
}

.card-title{font-size:1.1rem;font-weight:700;letter-spacing:-.02em;margin-bottom:4px}
.card-sub{font-size:.78rem;color:var(--text3);margin-bottom:24px}

/* ── ALERT ── */
.alert{
  display:flex;align-items:flex-start;gap:10px;
  padding:12px 14px;border-radius:10px;
  font-size:.78rem;font-weight:600;line-height:1.5;
  margin-bottom:18px;
}
.alert-error{background:#fef2f2;border:1px solid #fecaca;color:#dc2626}
.alert-success{background:#f0fdf4;border:1px solid #bbf7d0;color:#16a34a}
[data-theme="dark"] .alert-error{background:rgba(220,38,38,.1);border-color:rgba(220,38,38,.25);color:#fca5a5}
[data-theme="dark"] .alert-success{background:rgba(34,197,94,.1);border-color:rgba(34,197,94,.25);color:#86efac}
.alert i{font-size:.85rem;margin-top:1px;flex-shrink:0}

/* ── INPUT ── */
.inp-group{margin-bottom:14px}
.inp-label{
  display:flex;justify-content:space-between;align-items:center;
  font-size:.72rem;font-weight:600;color:var(--text3);margin-bottom:7px;letter-spacing:.01em;
}
.inp-label a{color:var(--text3);text-decoration:none;transition:color .2s}
.inp-label a:hover{color:var(--red)}
.inp-wrap{position:relative}
.inp{
  width:100%;padding:11px 40px 11px 14px;
  background:#fff;border:1px solid var(--border);
  border-radius:10px;font-family:'Sora',sans-serif;
  font-size:.875rem;color:var(--text);outline:none;
  transition:border .2s,box-shadow .2s;
}
[data-theme="dark"] .inp{background:var(--bg2);}
.inp:focus{border-color:var(--red);box-shadow:0 0 0 3px var(--red-soft)}
.inp::placeholder{color:var(--text3)}
.inp-btn{
  position:absolute;right:12px;top:50%;transform:translateY(-50%);
  background:none;border:none;color:var(--text3);cursor:pointer;
  font-size:.8rem;transition:color .2s;padding:2px;
}
.inp-btn:hover{color:var(--text)}

.btn-main{
  width:100%;padding:12px;border-radius:10px;
  background:var(--red);border:none;color:#fff;
  font-family:'Sora',sans-serif;font-size:.875rem;font-weight:700;
  cursor:pointer;transition:all .2s;
  display:flex;align-items:center;justify-content:center;gap:8px;
  margin-top:6px;letter-spacing:-.01em;
  position:relative;overflow:hidden;
}
.btn-main::after{
  content:'';position:absolute;inset:0;
  background:linear-gradient(135deg,rgba(255,255,255,.1),transparent);
  pointer-events:none;
}
.btn-main:hover{background:#b91c1c;transform:translateY(-1px);box-shadow:0 8px 24px var(--red-glow)}
.btn-main:active{transform:none}
.btn-main.loading{pointer-events:none;opacity:.7}

.card-footer{
  display:flex;align-items:center;gap:6px;
  margin-top:20px;padding-top:18px;border-top:1px solid var(--border);
}
.c-dot{width:6px;height:6px;border-radius:50%;background:var(--green);flex-shrink:0;box-shadow:0 0 0 3px rgba(34,197,94,.2)}
.c-txt{font-size:.7rem;color:var(--text3);letter-spacing:.02em}

/* ── FEATURES ── */
.feat{padding:100px clamp(20px,5vw,60px);position:relative;z-index:1}
.feat-inner{max-width:1240px;margin:0 auto}
.sect-head{margin-bottom:56px}
.sect-eyebrow{
  font-size:.72rem;font-weight:700;letter-spacing:.15em;text-transform:uppercase;
  color:var(--red);margin-bottom:12px;
}
.sect-h{
  font-size:clamp(1.8rem,3vw,2.6rem);font-weight:800;letter-spacing:-.04em;line-height:1.15;
}
.sect-h span{
  background:linear-gradient(135deg,var(--red),#ff6b6b);
  -webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;
}
.cards{display:grid;grid-template-columns:repeat(3,1fr);gap:16px}
.fc{
  background:var(--surface);border:1px solid var(--border);
  border-radius:16px;padding:28px 24px;
  transition:all .3s;cursor:default;position:relative;overflow:hidden;
}
.fc:hover{border-color:var(--border-h);transform:translateY(-4px);box-shadow:0 8px 32px rgba(0,0,0,.08)}
[data-theme="dark"] .fc:hover{box-shadow:0 16px 48px rgba(0,0,0,.3)}
.fc::before{
  content:'';position:absolute;top:0;left:0;right:0;height:1px;
  background:linear-gradient(90deg,transparent,var(--border-h),transparent);
}
.fc:hover::before{background:linear-gradient(90deg,transparent,var(--red),transparent)}
.fc-icon{
  width:40px;height:40px;border-radius:10px;
  background:var(--red-soft);color:var(--red);
  display:flex;align-items:center;justify-content:center;
  font-size:.9rem;margin-bottom:18px;
}
.fc-icon.blue{background:rgba(59,130,246,.1);color:#3b82f6}
.fc-icon.green{background:rgba(34,197,94,.1);color:#22c55e}
.fc-title{font-size:1rem;font-weight:700;letter-spacing:-.02em;margin-bottom:10px}
.fc-desc{font-size:.84rem;color:var(--text2);line-height:1.7}

/* ── STATS ── */
.stats{
  padding:80px clamp(20px,5vw,60px);
  border-top:1px solid var(--border);border-bottom:1px solid var(--border);
  position:relative;z-index:1;
}
.stats-inner{max-width:900px;margin:0 auto;display:flex;justify-content:space-around;flex-wrap:wrap;gap:48px}
.st{text-align:center}
.st-n{
  font-size:3rem;font-weight:800;letter-spacing:-.05em;
  background:linear-gradient(135deg,var(--text) 0%,var(--text2) 100%);
  -webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;
}
.st-l{font-size:.72rem;color:var(--text3);letter-spacing:.08em;text-transform:uppercase;margin-top:4px}

/* ── FOOTER ── */
footer{
  padding:40px clamp(20px,5vw,60px);
  border-top:1px solid var(--border);
  position:relative;z-index:1;
}
.foot-row{max-width:1240px;margin:0 auto;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px}
.foot-logo{display:flex;align-items:center;gap:10px;text-decoration:none}
.foot-logo img{width:28px}
.foot-name{font-size:.875rem;font-weight:700;color:var(--text);letter-spacing:-.01em}
.foot-copy{font-size:.75rem;color:var(--text3)}

/* ── SPINNER ── */
.spinner{
  width:16px;height:16px;border:2px solid rgba(255,255,255,.3);
  border-top-color:#fff;border-radius:50%;
  animation:spin .6s linear infinite;display:none;
}
@keyframes spin{to{transform:rotate(360deg)}}

/* ── ANIM ── */
@keyframes up{from{opacity:0;transform:translateY(20px)}to{opacity:1;transform:translateY(0)}}
.reveal{opacity:0;transform:translateY(16px);transition:.5s cubic-bezier(.16,1,.3,1)}
.reveal.in{opacity:1;transform:none}
.d1{transition-delay:.1s}.d2{transition-delay:.2s}

/* ── RESPONSIVE ── */
@media(max-width:1024px){
  .hero-wrap{grid-template-columns:1fr;max-width:520px}
  .cards{grid-template-columns:1fr 1fr}
}
@media(max-width:640px){
  .cards{grid-template-columns:1fr}
  h1{font-size:2.6rem}
  .stats-inner{gap:32px}
  .foot-row{flex-direction:column;text-align:center}
}
</style>
</head>
<body>
<div class="grid-bg"></div>
<div class="radial-glow"></div>

<!-- NAV -->
<nav>
  <a href="{{ route('welcome') }}" class="nav-logo">
    <img src="{{ asset('/assets/img/siap-hukum-01.png') }}" alt="Logo Siap-HUKUM">
    <span class="logo-name">SIAP-<span>HUKUM</span></span>
  </a>
  <div class="nav-right">
    <button class="theme-btn" id="themeBtn" title="Ganti tema">
      <i class="fas fa-moon" id="themeIco"></i>
      <span id="themeTxt">Gelap</span>
    </button>
  </div>
</nav>

<!-- HERO (Landing + Login) -->
<section class="hero">
  <div class="hero-wrap">

    <!-- LEFT: Landing copy -->
    <div>
      <div class="tag">
        <div class="tag-dot"></div>
        Arsip Dokumen Hukum · KPU Kab. Pasuruan
      </div>
      <h1>Sistem Arsip<br>Produk <span class="accent">Hukum.</span></h1>
      <p class="hero-sub">
        Platform manajemen arsip digital untuk KPU Kabupaten Pasuruan.
        Cepat, aman, dan mudah diakses oleh seluruh pegawai yang berwenang.
      </p>
      <div class="hero-pills">
        {{-- <span class="pill"><i class="fas fa-shield-halved"></i> SSL Terenkripsi</span> --}}
        <span class="pill"><i class="fas fa-bolt"></i> Akses Cepat</span>
        <span class="pill"><i class="fas fa-file-zipper"></i> Batch Download</span>
        <span class="pill"><i class="fas fa-users"></i> Multi Role</span>
      </div>
    </div>

    <!-- RIGHT: Login card -->
    <div class="card">
      <div class="card-title">Masuk ke Sistem</div>
      <div class="card-sub">Akses internal · KPU Kabupaten Pasuruan</div>

      {{-- Alert Error --}}
      @if(session('error'))
      <div class="alert alert-error">
        <i class="fas fa-circle-exclamation"></i>
        <span>{{ session('error') }}</span>
      </div>
      @endif

      {{-- Alert Success (misal setelah logout) --}}
      @if(session('success'))
      <div class="alert alert-success">
        <i class="fas fa-circle-check"></i>
        <span>{{ session('success') }}</span>
      </div>
      @endif

      <form action="{{ route('login.post') }}" method="POST" id="loginForm">
        @csrf

        <div class="inp-group">
          <label class="inp-label" for="nip">NIP / Username</label>
          <div class="inp-wrap">
            <input
              class="inp"
              type="text"
              id="nip"
              name="nip"
              value="{{ old('nip') }}"
              placeholder="Masukkan NIP atau username"
              required
              autofocus
              autocomplete="username">
            <button type="button" class="inp-btn" tabindex="-1">
              <i class="fas fa-id-card"></i>
            </button>
          </div>
        </div>

        <div class="inp-group">
          <label class="inp-label" for="password">
            <span>Password</span>
            {{-- Uncomment jika ada fitur lupa password --}}
            {{-- <a href="#">Lupa password?</a> --}}
          </label>
          <div class="inp-wrap">
            <input
              class="inp"
              type="password"
              id="password"
              name="password"
              placeholder="••••••••"
              required
              autocomplete="current-password">
            <button type="button" class="inp-btn" onclick="togglePw()" title="Tampilkan/sembunyikan password">
              <i class="fas fa-eye" id="pwIco"></i>
            </button>
          </div>
        </div>

        <button type="submit" class="btn-main" id="submitBtn">
          <span id="btnText"><i class="fas fa-arrow-right-to-bracket"></i> Masuk</span>
          <div class="spinner" id="spinner"></div>
        </button>
      </form>

      <div class="card-footer">
        <div class="c-dot"></div>
        <span class="c-txt">Sistem aktif &amp; beroperasi normal</span>
      </div>
    </div>
  </div>
</section>

<!-- FEATURES -->
<section class="feat">
  <div class="feat-inner">
    <div class="sect-head reveal">
      <div class="sect-eyebrow">Fitur Unggulan</div>
      <h2 class="sect-h">Dirancang untuk <span>Efisiensi</span></h2>
    </div>
    <div class="cards">
      <div class="fc reveal">
        <div class="fc-icon"><i class="fas fa-lock"></i></div>
        <div class="fc-title">Penyimpanan Aman</div>
        <p class="fc-desc">Arsip digital tersentralisasi dengan enkripsi SSL dan kontrol akses berbasis peran (role-based access control).</p>
      </div>
      <div class="fc reveal d1">
        <div class="fc-icon blue"><i class="fas fa-magnifying-glass"></i></div>
        <div class="fc-title">Pencarian Cerdas</div>
        <p class="fc-desc">Temukan dokumen dalam hitungan detik dengan filter berdasarkan kategori, tahun, dan jenis produk hukum.</p>
      </div>
      <div class="fc reveal d2">
        <div class="fc-icon green"><i class="fas fa-cloud-arrow-down"></i></div>
        <div class="fc-title">Distribusi Massal</div>
        <p class="fc-desc">Unduh banyak dokumen sekaligus dalam format ZIP terorganisir untuk kebutuhan pelaporan yang cepat.</p>
      </div>
    </div>
  </div>
</section>

<!-- STATS -->
{{-- <div class="stats reveal">
  <div class="stats-inner">
    <div class="st"><div class="st-n">500+</div><div class="st-l">Dokumen Tersimpan</div></div>
    <div class="st"><div class="st-n">12</div><div class="st-l">Kategori Hukum</div></div>
    <div class="st"><div class="st-n">24/7</div><div class="st-l">Akses Sistem</div></div>
  </div>
</div> --}}

<!-- FOOTER -->
<footer>
  <div class="foot-row">
    <a href="{{ route('welcome') }}" class="foot-logo">
      <img src="{{ asset('/assets/img/logo-kpu.png') }}" alt="Logo KPU">
      <span class="foot-name">KPU Kabupaten Pasuruan</span>
    </a>
    <span class="foot-copy">© {{ date('Y') }} Siap-HUKUM · Sub Bagian Teknis Penyelenggaraan Pemilu &amp; Hukum</span>
  </div>
</footer>

<script>
/* ── THEME ── */
const html     = document.documentElement;
const themeBtn = document.getElementById('themeBtn');
const themeIco = document.getElementById('themeIco');
const themeTxt = document.getElementById('themeTxt');

function setTheme(t) {
  html.dataset.theme = t;
  localStorage.setItem('siaphukum-theme', t);
  themeIco.className = t === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
  themeTxt.textContent = t === 'dark' ? 'Terang' : 'Gelap';
}

// Default: light — hanya pakai localStorage jika sudah pernah diset sebelumnya
const savedTheme = localStorage.getItem('siaphukum-theme') || 'light';
setTheme(savedTheme);

themeBtn.addEventListener('click', () => {
  setTheme(html.dataset.theme === 'dark' ? 'light' : 'dark');
});

/* ── PASSWORD TOGGLE ── */
function togglePw() {
  const pw  = document.getElementById('password');
  const ico = document.getElementById('pwIco');
  if (pw.type === 'password') {
    pw.type = 'text';
    ico.className = 'fas fa-eye-slash';
  } else {
    pw.type = 'password';
    ico.className = 'fas fa-eye';
  }
}

/* ── LOADING STATE ── */
document.getElementById('loginForm').addEventListener('submit', function () {
  const btn     = document.getElementById('submitBtn');
  const btnText = document.getElementById('btnText');
  const spinner = document.getElementById('spinner');
  btn.classList.add('loading');
  btnText.style.display = 'none';
  spinner.style.display = 'block';
});

/* ── SCROLL REVEAL ── */
const obs = new IntersectionObserver(
  entries => entries.forEach(e => e.isIntersecting && e.target.classList.add('in')),
  { threshold: .1 }
);
document.querySelectorAll('.reveal').forEach(el => obs.observe(el));

/* ── AUTO-DISMISS ALERT (opsional, 6 detik) ── */
setTimeout(() => {
  document.querySelectorAll('.alert').forEach(el => {
    el.style.transition = 'opacity .5s';
    el.style.opacity = '0';
    setTimeout(() => el.remove(), 500);
  });
}, 6000);
</script>
</body>
</html>
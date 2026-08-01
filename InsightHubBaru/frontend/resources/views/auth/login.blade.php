<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InsightHub — Masuk</title>
    <meta name="description" content="Masuk ke InsightHub untuk mengakses dashboard analitik data Anda.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web@2.1.1"></script>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}?v={{ time() }}">
</head>
<body>

<!-- ═══════════════════════════════════════
     FULL-PAGE BACKGROUND
════════════════════════════════════════ -->
<div class="auth-bg" aria-hidden="true">

    <!-- PPEJP Building photo — right-side watermark -->
    <img class="bg-ppejp" src="{{ asset('assets/ppejp.jpg') }}" alt="" draggable="false">

    <!-- Gradient fade — blends photo left edge into gradient -->
    <div class="bg-photo-fade"></div>

    <!-- Dot grids -->
    <div class="bg-dots-tl"></div>
    <div class="bg-dots-bl"></div>
    <div class="bg-dots-tr"></div>

    <!-- Ring outlines -->
    <div class="bg-ring-left"></div>
    <div class="bg-ring-tr"></div>

    <!-- Small accent dot -->
    <div class="bg-dot-accent"></div>

    <!-- Radial glow left -->
    <div class="bg-glow-left"></div>

    <!-- SVG wave lines -->
    <svg class="bg-waves" viewBox="0 0 1440 800" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice">
        <path d="M0 400 Q200 320 400 400 Q600 480 800 400 Q1000 320 1200 400 Q1350 460 1440 400" stroke="rgba(38,70,197,0.08)" stroke-width="2" fill="none"/>
        <path d="M0 500 Q250 420 500 500 Q750 580 1000 500 Q1200 440 1440 500" stroke="rgba(38,70,197,0.06)" stroke-width="1.5" fill="none"/>
        <path d="M0 300 Q300 240 600 300 Q900 360 1200 300 Q1350 270 1440 300" stroke="rgba(38,70,197,0.05)" stroke-width="1" fill="none"/>
    </svg>

    <!-- Bottom fluid blob shapes -->
    <div class="bg-blob-bottom"></div>
    <div class="bg-blob-bottom-2"></div>

</div>

<!-- ═══════════════════════════════════════
     PAGE CONTENT — centered card
════════════════════════════════════════ -->
<div class="auth-page">

    <!-- ─── Login Card ─── -->
    <div class="auth-card">

        <!-- Logo -->
        <a href="{{ url('/') }}" class="auth-logo" aria-label="InsightHub Beranda">
            <span class="logo-box"><i class="ph-fill ph-chart-line-up"></i></span>
            <span class="logo-name">InsightHub</span>
        </a>

        <!-- Heading -->
        <div class="auth-heading">
            <h1 class="auth-title">Selamat Datang Kembali!</h1>
            <p class="auth-subtitle">Masuk untuk mengakses dashboard InsightHub.</p>
        </div>

        <!-- Alerts -->
        @if(session('error'))
            <div class="auth-alert alert-error" role="alert">
                <i class="ph-bold ph-warning-circle"></i>
                <span>{{ session('error') }}</span>
                <button type="button" onclick="this.parentElement.remove()" aria-label="Tutup"><i class="ph ph-x"></i></button>
            </div>
        @endif

        @if(session('success'))
            <div class="auth-alert alert-success" role="alert">
                <i class="ph-bold ph-check-circle"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="auth-alert alert-error" role="alert">
                <i class="ph-bold ph-warning-circle"></i>
                <span>Periksa kembali data yang Anda masukkan.</span>
                <button type="button" onclick="this.parentElement.remove()" aria-label="Tutup"><i class="ph ph-x"></i></button>
            </div>
        @endif

        <!-- Form -->
        <form id="login-form" action="{{ route('login.post') }}" method="POST" class="auth-form" novalidate>
            @csrf

            <!-- Email -->
            <div class="form-group @error('email') has-error @enderror">
                <label for="email" class="form-label">Email</label>
                <div class="input-wrap">
                    <i class="ph ph-envelope-simple input-icon"></i>
                    <input
                        type="email" id="email" name="email"
                        class="form-input"
                        placeholder="Masukkan email Anda"
                        value="{{ old('email') }}"
                        autocomplete="email" autofocus required
                    >
                </div>
                @error('email')
                    <span class="field-error" role="alert"><i class="ph ph-warning"></i> {{ $message }}</span>
                @enderror
            </div>

            <!-- Password -->
            <div class="form-group @error('password') has-error @enderror">
                <div class="label-row">
                    <label for="passwordInput" class="form-label">Kata Sandi</label>
                    <a href="#" class="forgot-link">Lupa Kata Sandi?</a>
                </div>
                <div class="input-wrap">
                    <i class="ph ph-lock-key input-icon"></i>
                    <input
                        type="password" id="passwordInput" name="password"
                        class="form-input"
                        placeholder="Masukkan kata sandi Anda"
                        autocomplete="current-password" required
                    >
                    <button type="button" class="pw-toggle" onclick="togglePw('passwordInput','pwIcon1')" aria-label="Tampilkan/sembunyikan kata sandi">
                        <i class="ph ph-eye" id="pwIcon1"></i>
                    </button>
                </div>
                @error('password')
                    <span class="field-error" role="alert"><i class="ph ph-warning"></i> {{ $message }}</span>
                @enderror
            </div>

            <!-- Remember Me -->
            <div class="check-row">
                <label class="check-label" for="remember">
                    <input type="checkbox" id="remember" name="remember" class="check-input" {{ old('remember') ? 'checked' : '' }}>
                    <span class="check-box" aria-hidden="true"></span>
                    <span class="check-text">Ingat Saya</span>
                </label>
            </div>

            <!-- Submit -->
            <button type="submit" class="btn-primary" id="btn-submit">
                <span class="btn-label">Masuk</span>
                <i class="ph ph-arrow-right btn-arrow"></i>
                <span class="btn-spinner" id="btn-spinner"><i class="ph ph-circle-notch spin"></i></span>
            </button>

        </form>

        <!-- Divider -->
        <div class="auth-divider"><span>atau</span></div>

        <!-- Register Link -->
        <div class="auth-switch">
            <span>Belum memiliki akun?</span>
            <a href="{{ route('register') }}" class="switch-link">Daftar Sekarang</a>
        </div>

    </div><!-- /.auth-card -->

    <!-- Footer -->
    <footer class="auth-footer" role="contentinfo">
        <p>&copy; 2024 InsightHub. All rights reserved.</p>
        <nav>
            <a href="#">Kebijakan Privasi</a>
            <span aria-hidden="true">•</span>
            <a href="#">Syarat &amp; Ketentuan</a>
            <span aria-hidden="true">•</span>
            <a href="#">Bantuan</a>
        </nav>
    </footer>

</div><!-- /.auth-page -->

<script>
    /* Toggle password visibility */
    function togglePw(inputId, iconId) {
        const inp  = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        const hidden = inp.type === 'password';
        inp.type = hidden ? 'text' : 'password';
        icon.classList.toggle('ph-eye',       !hidden);
        icon.classList.toggle('ph-eye-slash',  hidden);
    }

    /* Form submit — client validation + loader */
    document.getElementById('login-form').addEventListener('submit', function(e) {
        clearErrors();
        const emailEl = document.getElementById('email');
        const passEl  = document.getElementById('passwordInput');
        let ok = true;

        if (!emailEl.value.trim()) {
            setError(emailEl, 'Email tidak boleh kosong.'); ok = false;
        } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailEl.value.trim())) {
            setError(emailEl, 'Format email tidak valid.'); ok = false;
        }
        if (!passEl.value.trim()) {
            setError(passEl, 'Kata sandi tidak boleh kosong.'); ok = false;
        }

        if (!ok) { e.preventDefault(); return; }

        const btn = document.getElementById('btn-submit');
        btn.disabled = true;
        btn.classList.add('loading');
    });

    function setError(el, msg) {
        const g = el.closest('.form-group');
        g.classList.add('has-error');
        const s = document.createElement('span');
        s.className = 'field-error field-error-js';
        s.setAttribute('role', 'alert');
        s.innerHTML = '<i class="ph ph-warning"></i> ' + msg;
        g.appendChild(s);
        el.addEventListener('input', () => { g.classList.remove('has-error'); s.remove(); }, { once: true });
    }
    function clearErrors() {
        document.querySelectorAll('.field-error-js').forEach(e => e.remove());
        document.querySelectorAll('.form-group.has-error').forEach(e => e.classList.remove('has-error'));
    }

    /* Input focus ring */
    document.querySelectorAll('.form-input').forEach(inp => {
        inp.addEventListener('focus', () => inp.closest('.input-wrap').classList.add('focused'));
        inp.addEventListener('blur',  () => inp.closest('.input-wrap').classList.remove('focused'));
    });
</script>
</body>
</html>

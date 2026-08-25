<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SOVIE — Profil Saya</title>
    <meta name="description" content="Kelola profil dan pengaturan akun SOVIE Anda.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/style.css">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}?v={{ time() }}">
</head>
<body>

    <!-- ═══════════════════════════════
         TOP NAVIGATION BAR
    ════════════════════════════════ -->
    <header class="topnav">
        <!-- Brand -->
        <a href="{{ url('/') }}" class="topnav-brand" style="text-decoration:none; display:flex; align-items:center; padding-left: 0.5rem; flex-shrink: 0;" title="Kembali ke Beranda">
            <div style="width: 180px; height: 52px; overflow: hidden; position: relative;">
                <img src="{{ asset('assets/logo-kemendag.png') }}"
                     alt="Kementerian Perdagangan"
                     style="width: 195px !important; height: auto !important; max-width: none !important; position: absolute; top: 50%; left: -20px; transform: translateY(-50%); display: block; padding: 0; margin: 0;">
            </div>
        </a>

        <!-- Nav Links -->
        <nav class="topnav-links">
            <a class="topnav-item" href="{{ route('dashboard') }}">Ringkasan</a>
            <a class="topnav-item" href="{{ route('dashboard') }}?tab=analitik">Analitik Konten</a>
            
            @if(auth()->check() && in_array(auth()->user()->role, ['super-admin', 'admin']))
            <a class="topnav-item" href="{{ route('dashboard') }}?tab=input">Input Data</a>
            @endif
            
            <a class="topnav-item" href="{{ route('dashboard') }}?tab=laporan">Laporan</a>
            
            @if(auth()->check() && in_array(auth()->user()->role, ['super-admin', 'admin']))
            <a class="topnav-item {{ request()->routeIs('pengguna.index') ? 'active' : '' }}" href="{{ route('pengguna.index') }}">Manajemen Data Pengguna</a>
            @endif
            
            <a class="topnav-item active" href="{{ route('profile.show') }}">Profil</a>
        </nav>

        <!-- Right -->
        <div class="topnav-right">
            <div class="topnav-user-group">
                <a href="{{ route('profile.show') }}" class="topnav-avatar-link" title="{{ auth()->user()->name }}">
                    @php
                        $hasAvatar = auth()->user()->avatar && file_exists(public_path(auth()->user()->avatar));
                        $nameParts = explode(' ', trim(auth()->user()->name));
                        $initials = count($nameParts) > 1 
                            ? strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[1], 0, 1))
                            : strtoupper(substr(auth()->user()->name, 0, 2));
                    @endphp
                    @if($hasAvatar)
                    <img src="{{ asset(auth()->user()->avatar) }}" alt="{{ auth()->user()->name }}" class="topnav-avatar-img" id="topbar-avatar">
                @else
                    <div class="topnav-avatar-initials" id="topbar-avatar-initials">
                        {{ $initials }}
                    </div>
                    <img src="" alt="{{ auth()->user()->name }}" class="topnav-avatar-img" id="topbar-avatar" style="display: none;">
                @endif
                </a>
                <button class="topnav-logout-btn"
                        onclick="event.preventDefault(); document.getElementById('topnav-logout-form').submit();"
                        title="Logout">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 6px;"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                    <span>Logout</span>
                </button>
                <form id="topnav-logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
                    @csrf
                </form>
            </div>
        </div>
    </header>

    <!-- ═══════════════════════════════
         MAIN CONTENT
    ════════════════════════════════ -->
    <main class="main-content" style="margin-left:0;">

        <!-- ═══════════════════════════════
             PROFILE CONTENT
        ════════════════════════════════ -->
        <div class="profile-page">

            <!-- ── Profile Hero Header ──────────────────── -->
            <div class="profile-hero">
                <div class="hero-gradient" aria-hidden="true"></div>
                <div class="hero-content">
                    <div class="hero-avatar" onclick="document.getElementById('pf-avatar-input').click()" style="cursor: pointer;" title="Ubah Foto Profil">
                        @php
                            $hasProfileAvatar = $user->avatar && file_exists(public_path($user->avatar));
                        @endphp
                        @if($hasProfileAvatar)
                            <img
                                id="hero-avatar-img"
                                src="{{ asset($user->avatar) }}"
                                alt="{{ $user->name }}"
                                class="avatar-img"
                            >
                        @else
                            <div id="hero-avatar-initials" class="avatar-img" style="display: flex; align-items: center; justify-content: center; background: #003ea8; color: #fff; font-size: 36px; font-weight: bold; width: 96px; height: 96px; border-radius: 50%;">
                                {{ $initials }}
                            </div>
                            <img id="hero-avatar-img" src="" alt="{{ $user->name }}" class="avatar-img" style="display: none;">
                        @endif
                        <span class="avatar-badge avatar-camera-btn" aria-label="Ganti Foto">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path><circle cx="12" cy="13" r="4"></circle></svg>
                        </span>
                    </div>
                    <div class="hero-info">
                        <h1 class="hero-name">{{ $user->name }}</h1>
                        <p class="hero-role">
                            {{ strtoupper($user->roleModel->name ?? $user->role ?? 'User') }}
                            <span class="hero-dot">•</span>
                            {{ $user->email }}
                        </p>
                    </div>
                    <div class="hero-actions">
                        <button type="submit" form="profile-form" class="btn-save" id="btn-save-hero">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 6px;"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                            <span>Simpan Perubahan</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- ── Alerts (flash messages) ──────────────── -->
            @if(session('success'))
                <div class="profile-alert alert-success" role="alert">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                    <span>{{ session('success') }}</span>
                    <button type="button" onclick="this.parentElement.remove()" aria-label="Tutup"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></button>
                </div>
            @endif

            @if(session('error'))
                <div class="profile-alert alert-error" role="alert">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                    <span>{{ session('error') }}</span>
                    <button type="button" onclick="this.parentElement.remove()" aria-label="Tutup"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></button>
                </div>
            @endif

            <!-- ── Two-Column Grid ──────────────────────── -->
            <div class="profile-grid">

                <!-- LEFT COLUMN — Informasi Pribadi -->
                <section class="profile-card">
                    <div class="card-header">
                        <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="card-icon" style="margin-right: 12px; color: #003ea8; flex-shrink: 0;"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                        <div>
                            <h2 class="card-title">Informasi Pribadi</h2>
                            <p class="card-desc">Perbarui data personal, alamat email, dan lokasi tempat Anda bekerja.</p>
                        </div>
                    </div>

                    <form id="profile-form" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="profile-form">
                        @csrf
                        @method('PUT')

                        <!-- Hidden Avatar File Input -->
                        <input type="file" name="avatar" id="pf-avatar-input" style="display: none;" accept="image/*">

                        <!-- Nama Lengkap -->
                        <div class="pf-group @error('name') has-error @enderror">
                            <label for="pf-name" class="pf-label">NAMA LENGKAP</label>
                            <div class="pf-input-wrap">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="pf-input-icon"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                <input
                                    type="text" id="pf-name" name="name"
                                    class="pf-input"
                                    value="{{ old('name', $user->name) }}"
                                    placeholder="Masukkan nama lengkap"
                                    required
                                >
                            </div>
                            @error('name')
                                <span class="pf-error"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 4px;"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg> {{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Alamat Email -->
                        <div class="pf-group @error('email') has-error @enderror">
                            <label for="pf-email" class="pf-label">ALAMAT EMAIL</label>
                            <div class="pf-input-wrap">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="pf-input-icon"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                                <input
                                    type="email" id="pf-email" name="email"
                                    class="pf-input"
                                    value="{{ old('email', $user->email) }}"
                                    placeholder="email@sovie.ai"
                                    required
                                >
                            </div>
                            @error('email')
                                <span class="pf-error"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 4px;"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg> {{ $message }}</span>
                            @enderror
                        </div>



                    </form>
                </section>

                <!-- RIGHT COLUMN — Pengaturan Akun -->
                <section class="profile-card">
                    <div class="card-header">
                        <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="card-icon" style="margin-right: 12px; color: #003ea8; flex-shrink: 0;"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
                        <div>
                            <h2 class="card-title">Pengaturan Akun</h2>
                            <p class="card-desc">Kelola preferensi keamanan sandi dan detail sistem akun Anda.</p>
                        </div>
                    </div>

                    <!-- Keamanan — Ubah Password -->
                    <div class="pf-group">
                        <label class="pf-label">KEAMANAN</label>
                        <button type="button" class="pf-action-btn" id="btn-open-pw-modal" onclick="openPwModal()">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                            <span>Ubah Password</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="pf-action-arrow"><polyline points="9 18 15 12 9 6"></polyline></svg>
                        </button>
                    </div>

                    <!-- Akun Info -->
                    <div class="account-info" style="margin-top: 1.5rem;">
                        <div class="info-row">
                            <span class="info-label">Bergabung sejak</span>
                            <span class="info-value">{{ $user->created_at ? $user->created_at->translatedFormat('d F Y') : '-' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Role</span>
                            <span class="info-badge">{{ strtoupper($user->roleModel->name ?? $user->role ?? 'User') }}</span>
                        </div>
                    </div>

                    <!-- Logout -->
                    <div class="logout-section">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-logout">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 6px;"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                                <span>Keluar dari Akun</span>
                            </button>
                        </form>
                    </div>

                </section>

            </div><!-- /.profile-grid -->

        </div><!-- /.profile-page -->

    </main>

    <!-- ═══════════════════════════════════════
         MODAL — Ubah Password
    ════════════════════════════════════════ -->
    <div class="modal-overlay" id="pw-modal" role="dialog" aria-modal="true" aria-labelledby="pw-modal-title">
        <div class="modal-card">
            <div class="modal-header">
                <div class="modal-icon-wrap">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: #003ea8;"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                </div>
                <div>
                    <h3 class="modal-title" id="pw-modal-title">Ubah Kata Sandi</h3>
                    <p class="modal-desc">Pastikan kata sandi baru Anda kuat dan mudah diingat.</p>
                </div>
                <button type="button" class="modal-close" onclick="closePwModal()" aria-label="Tutup">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>

            <form action="{{ route('profile.password') }}" method="POST" class="modal-form" id="pw-form">
                @csrf
                @method('PUT')

                <!-- Kata Sandi Saat Ini -->
                <div class="pf-group @error('current_password') has-error @enderror">
                    <label for="pw-current" class="pf-label">KATA SANDI SAAT INI</label>
                    <div class="pf-input-wrap pf-pw-wrap">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="pf-input-icon"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                        <input type="password" id="pw-current" name="current_password" class="pf-input" placeholder="Masukkan kata sandi saat ini" required>
                        <button type="button" class="pw-eye" onclick="togglePw('pw-current', this)"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg></button>
                    </div>
                    @error('current_password')
                        <span class="pf-error"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 4px;"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg> {{ $message }}</span>
                    @enderror
                </div>

                <!-- Kata Sandi Baru -->
                <div class="pf-group @error('password') has-error @enderror">
                    <label for="pw-new" class="pf-label">KATA SANDI BARU</label>
                    <div class="pf-input-wrap pf-pw-wrap">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="pf-input-icon"><path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"></path></svg>
                        <input type="password" id="pw-new" name="password" class="pf-input" placeholder="Minimal 8 karakter" required>
                        <button type="button" class="pw-eye" onclick="togglePw('pw-new', this)"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg></button>
                    </div>
                    @error('password')
                        <span class="pf-error"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 4px;"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg> {{ $message }}</span>
                    @enderror
                </div>

                <!-- Konfirmasi Kata Sandi -->
                <div class="pf-group">
                    <label for="pw-confirm" class="pf-label">KONFIRMASI KATA SANDI BARU</label>
                    <div class="pf-input-wrap pf-pw-wrap">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="pf-input-icon"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path><polyline points="9 11 11 13 15 9"></polyline></svg>
                        <input type="password" id="pw-confirm" name="password_confirmation" class="pf-input" placeholder="Ulangi kata sandi baru" required>
                        <button type="button" class="pw-eye" onclick="togglePw('pw-confirm', this)"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg></button>
                    </div>
                </div>

                <div class="modal-actions">
                    <button type="button" class="btn-cancel" onclick="closePwModal()">Batal</button>
                    <button type="submit" class="btn-save">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 4px;"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg> Simpan Kata Sandi
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Global Footer -->
    @include('layouts.footer')

    <!-- ═══════════════════════════════
         SCRIPTS
    ════════════════════════════════ -->
    <script>
        /* ── Password Modal ─────────────────────── */
        function openPwModal() {
            document.getElementById('pw-modal').classList.add('is-open');
            document.body.style.overflow = 'hidden';
        }
        function closePwModal() {
            document.getElementById('pw-modal').classList.remove('is-open');
            document.body.style.overflow = '';
        }
        // Close on overlay click
        document.getElementById('pw-modal').addEventListener('click', function(e) {
            if (e.target === this) closePwModal();
        });
        // Close on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closePwModal();
        });

        /* ── Auto-open modal if password validation fails ── */
        @if(session('show_password_modal') || $errors->has('current_password') || $errors->has('password'))
            openPwModal();
        @endif

        /* ── Toggle Password Visibility ─────────── */
        function togglePw(inputId, btn) {
            const inp = document.getElementById(inputId);
            const isHidden = inp.type === 'password';
            inp.type = isHidden ? 'text' : 'password';
            if (isHidden) {
                btn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>';
            } else {
                btn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>';
            }
        }

        /* ── Input focus ring ───────────────────── */
        document.querySelectorAll('.pf-input').forEach(inp => {
            inp.addEventListener('focus', () => inp.closest('.pf-input-wrap').classList.add('focused'));
            inp.addEventListener('blur',  () => inp.closest('.pf-input-wrap').classList.remove('focused'));
        });

        /* ── Avatar Upload Preview ──────────────── */
        const avatarInput = document.getElementById('pf-avatar-input');
        const heroAvatarImg = document.getElementById('hero-avatar-img');
        const heroAvatarInitials = document.getElementById('hero-avatar-initials');
        const topbarAvatarImg = document.getElementById('topbar-avatar');
        const topbarAvatarInitials = document.getElementById('topbar-avatar-initials');

        if (avatarInput) {
            avatarInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        if (heroAvatarImg) {
                            heroAvatarImg.src = event.target.result;
                            heroAvatarImg.style.display = 'block';
                        }
                        if (heroAvatarInitials) heroAvatarInitials.style.display = 'none';
                        
                        if (topbarAvatarImg) {
                            topbarAvatarImg.src = event.target.result;
                            topbarAvatarImg.style.display = 'block';
                        }
                        if (topbarAvatarInitials) topbarAvatarInitials.style.display = 'none';
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
    </script>
</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SOVIE - Pusat Analisis dan Insight Media Sosial</title>
    <!-- Phosphor Icons -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/style.css">
    <!-- Dashboard CSS (untuk topnav biru) -->
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}?v={{ time() }}">
    <!-- Landing page CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}?v={{ time() }}">
</head>
<body>

    <!-- Top Navigation Bar (reuse dashboard topnav style) -->
    <header class="topnav" style="position: sticky; top: 0; z-index: 200;">

        <!-- Brand / Logo -->
        <a href="{{ url('/') }}" class="topnav-brand" style="text-decoration:none; display:flex; align-items:center; padding-left: 0.5rem; flex-shrink: 0;" title="Kembali ke Beranda">
            <div style="width: 180px; height: 52px; overflow: hidden; position: relative;">
                <img src="{{ asset('assets/logo-kemendag.png') }}"
                     alt="Kementerian Perdagangan"
                     style="width: 195px !important; height: auto !important; max-width: none !important; position: absolute; top: 50%; left: -20px; transform: translateY(-50%); display: block; padding: 0; margin: 0;">
            </div>
        </a>

        <!-- Nav Links -->
        <nav class="topnav-links">
            <a href="{{ route('dashboard') }}" class="topnav-item">Dashboard</a>
            <a href="#" class="topnav-item">Juknis</a>
            <a href="#" class="topnav-item">About</a>
        </nav>

        <!-- Right side: auth-aware -->
        <div class="topnav-right">
            @auth
                <div class="topnav-user-group">
                    <a href="{{ route('profile.show') }}" class="topnav-avatar-link" title="{{ auth()->user()->name }}">
                        @if(auth()->user()->avatar)
                            <img src="{{ asset(auth()->user()->avatar) }}" alt="{{ auth()->user()->name }}" class="topnav-avatar-img">
                        @else
                            <div class="topnav-avatar-initials">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
                        @endif
                    </a>
                    <button class="topnav-logout-btn"
                            onclick="event.preventDefault(); document.getElementById('welcome-logout-form').submit();"
                            title="Logout">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                        <span>Logout</span>
                    </button>
                    <form id="welcome-logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>
                </div>
            @else
                <div class="topnav-user-group">
                    <a href="{{ route('login') }}" class="topnav-logout-btn" style="color:rgba(255,255,255,0.8); font-weight:600; padding: 0.4rem 1rem; border: 1px solid transparent; transition: color 0.2s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,0.8)'">Log In</a>
                </div>
            @endauth
        </div>

    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container hero-container">
            <div class="hero-content">
                <h1 class="hero-title">SOVIE</h1>
                <h2 class="hero-subtitle-large">Social Media View</h2>
                
                <div class="hero-divider"></div>

                <p class="hero-subtitle">SOVIE merupakan platform digital yang mendukung pengelolaan data, multimedia, analisis, dan pelaporan secara terstruktur dalam satu ruang digital.</p>
            </div>
        </div>
    </section>

    <!-- Section 1: Tentang PEMTA -->
    <section class="pemta-about">
        <div class="container pemta-about-container">
            <div class="pa-left">
                <span class="pa-label">TENTANG PEMTA</span>
                <h2 class="pa-title">Mengelola Data,<br>Menghasilkan Informasi,<br>Mendukung Keputusan</h2>
                <p class="pa-desc">PEMTA hadir dari kebutuhan Divisi Multimedia dan Database (MMD) Pusat Pengembangan SDM Ekspor dan Jasa Perdagangan (PPEJP) akan pengelolaan data dan multimedia yang lebih terstruktur. Sebelumnya, proses pencatatan, pengelolaan, pemantauan, dan pelaporan data masih melibatkan berbagai sumber dan proses yang belum terpusat.</p>
                <p class="pa-desc">Melihat kebutuhan tersebut, PEMTA dikembangkan sebagai sebuah platform digital terpusat yang mengintegrasikan pengelolaan data, multimedia, aset digital, pemantauan analisis, hingga pelaporan dalam satu ruang kerja.</p>
            </div>
            <div class="pa-right">
                <div class="pa-feature">
                    <div class="pa-feature-icon"><i class="ph-fill ph-database"></i></div>
                    <h3 class="pa-feature-title">Pengelolaan Data</h3>
                    <p class="pa-feature-desc">Pengelolaan data terstruktur dalam satu sistem terpusat yang aman dan terintegrasi.</p>
                </div>
                <div class="pa-feature">
                    <div class="pa-feature-icon"><i class="ph-fill ph-image"></i></div>
                    <h3 class="pa-feature-title">Manajemen Multimedia</h3>
                    <p class="pa-feature-desc">Kelola aset digital, dokumen, dan konten multimedia dengan mudah dan efisien.</p>
                </div>
                <div class="pa-feature">
                    <div class="pa-feature-icon"><i class="ph-fill ph-chart-line-up"></i></div>
                    <h3 class="pa-feature-title">Analisis & Insight</h3>
                    <p class="pa-feature-desc">Ubah data menjadi informasi berharga melalui fitur analitik yang informatif.</p>
                </div>
                <div class="pa-feature">
                    <div class="pa-feature-icon"><i class="ph-fill ph-file-text"></i></div>
                    <h3 class="pa-feature-title">Pelaporan Terintegrasi</h3>
                    <p class="pa-feature-desc">Laporan otomatis dan terstruktur untuk mendukung pengambilan keputusan yang lebih baik.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Section 2: Perjalanan PEMTA -->
    <section class="pemta-timeline">
        <div class="container">
            <span class="pt-label">DARI DATA MENJADI INFORMASI</span>
            <h2 class="pt-title">Perjalanan PEMTA</h2>
            
            <div class="timeline-wrapper">
                <div class="timeline-step">
                    <div class="ts-number">01</div>
                    <div class="ts-icon-box">
                        <!-- Users / People icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 256 256" fill="currentColor">
                            <path d="M164.47,195.63a8,8,0,0,1-6.7,12.37H98.23a8,8,0,0,1-6.7-12.37,72,72,0,0,1,72.94,0ZM128,120a44,44,0,1,0-44-44A44.05,44.05,0,0,0,128,120Zm0-72a28,28,0,1,1-28,28A28,28,0,0,1,128,48ZM245.06,139.17a8,8,0,0,1-11,2.72L208,128.43V208a8,8,0,0,1-16,0V128.43l-26.06,13.46a8,8,0,1,1-7.33-14.22l40-20.67a8,8,0,0,1,7.33,0l40,20.67A8,8,0,0,1,245.06,139.17ZM56,208V128.43L29.94,141.89a8,8,0,1,1-7.33-14.22l40-20.67a8,8,0,0,1,7.33,0l40,20.67a8,8,0,0,1-7.33,14.22L64,128.43V208a8,8,0,0,1-16,0Z"/>
                        </svg>
                    </div>
                    <div class="ts-text-wrap">
                        <h3 class="ts-title">Kebutuhan</h3>
                        <p class="ts-desc">Muncul kebutuhan pengelolaan data dan multimedia yang lebih terstruktur di Divisi MMD PPEJP.</p>
                    </div>
                </div>
                <div class="timeline-step">
                    <div class="ts-number">02</div>
                    <div class="ts-icon-box">
                        <!-- Pencil / Design icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 256 256" fill="currentColor">
                            <path d="M227.31,73.37,182.63,28.68a16,16,0,0,0-22.63,0L36.69,152A15.86,15.86,0,0,0,32,163.31V208a16,16,0,0,0,16,16H92.69A15.86,15.86,0,0,0,104,219.31L227.31,96a16,16,0,0,0,0-22.63ZM51.31,160,136,75.31,152.69,92,68,176.68ZM48,208V179.31L76.69,208Zm48,0L80,191.31,160,111.31,176.69,128ZM192,116.69,139.31,64l24-24L216,92.69Z"/>
                        </svg>
                    </div>
                    <div class="ts-text-wrap">
                        <h3 class="ts-title">Perancangan</h3>
                        <p class="ts-desc">Dirancang sebuah platform yang mengintegrasikan data, multimedia, analitik, dan pelaporan.</p>
                    </div>
                </div>
                <div class="timeline-step">
                    <div class="ts-number">03</div>
                    <div class="ts-icon-box">
                        <!-- Code icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 256 256" fill="currentColor">
                            <path d="M69.12,94.15,28.5,128l40.62,33.85a8,8,0,1,1-10.24,12.29l-48-40a8,8,0,0,1,0-12.29l48-40a8,8,0,0,1,10.24,12.3Zm176,27.71-48-40a8,8,0,1,0-10.24,12.3L227.5,128l-40.62,33.85a8,8,0,1,0,10.24,12.29l48-40a8,8,0,0,0,0-12.29ZM162.73,32.48a8,8,0,0,0-10.25,4.79l-64,176a8,8,0,0,0,4.79,10.26A8.14,8.14,0,0,0,96,224a8,8,0,0,0,7.52-5.27l64-176A8,8,0,0,0,162.73,32.48Z"/>
                        </svg>
                    </div>
                    <div class="ts-text-wrap">
                        <h3 class="ts-title">Pengembangan</h3>
                        <p class="ts-desc">PEMTA dikembangkan sebagai ruang digital terpusat untuk Divisi MMD PPEJP.</p>
                    </div>
                </div>
                <div class="timeline-step">
                    <div class="ts-number">04</div>
                    <div class="ts-icon-box">
                        <!-- Bar Chart / Analytics icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 256 256" fill="currentColor">
                            <path d="M224,200h-8V40a8,8,0,0,0-8-8H152a8,8,0,0,0-8,8V80H96a8,8,0,0,0-8,8v40H48a8,8,0,0,0-8,8v64H32a8,8,0,0,0,0,16H224a8,8,0,0,0,0-16Zm-120,0V144h32v56Zm48,0V48h32V200Z"/>
                        </svg>
                    </div>
                    <div class="ts-text-wrap">
                        <h3 class="ts-title">Pemanfaatan</h3>
                        <p class="ts-desc">Data diolah menjadi informasi melalui dashboard, analitik, dan laporan.</p>
                    </div>
                </div>
                <div class="timeline-step">
                    <div class="ts-number">05</div>
                    <div class="ts-icon-box">
                        <!-- Rocket icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 256 256" fill="currentColor">
                            <path d="M152,224a8,8,0,0,1-8,8H112a8,8,0,0,1,0-16h32A8,8,0,0,1,152,224Zm69.66-93.66-12,12a8,8,0,0,1-11.32,0L176,120.07V152a8,8,0,0,1-2.34,5.66l-32,32a8,8,0,0,1-11.32,0L104,163.31V184a8,8,0,0,1-13.66,5.66l-48-48A8,8,0,0,1,40,136V104.07L17.66,81.66a8,8,0,0,1,0-11.32l12-12c12-12,34.38-11.44,58.64,1.27L128,40a8,8,0,0,1,12,0l40,52c23.89-12.68,45.84-13.64,57.66-1.32A8,8,0,0,1,221.66,130.34ZM192,84a32.06,32.06,0,0,1,13.46,6.09L164,40.71l-26.49,34.46L163.51,101C171.75,94.61,182.18,88.67,192,84Z"/>
                        </svg>
                    </div>
                    <div class="ts-text-wrap">
                        <h3 class="ts-title">Pengembangan Berkelanjutan</h3>
                        <p class="ts-desc">PEMTA terus dikembangkan mengikuti kebutuhan pengelolaan di lingkungan PPEJP.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section 3: Tujuan PEMTA -->
    <section class="pemta-goals">
        <div class="container">
            <span class="pg-label">TUJUAN PEMTA</span>
            <h2 class="pg-title">Menuju Pengelolaan yang Lebih Terarah</h2>
            
            <div class="goals-row">
                <div class="goal-item">
                    <div class="goal-icon"><i class="ph-bold ph-target"></i></div>
                    <h3 class="goal-title">Rapi & Terstruktur</h3>
                    <p class="goal-desc">Pengelolaan data dan multimedia menjadi lebih terorganisir dan mudah diakses.</p>
                </div>
                <div class="goal-item">
                    <div class="goal-icon"><i class="ph-bold ph-clock"></i></div>
                    <h3 class="goal-title">Efisien</h3>
                    <p class="goal-desc">Proses kerja lebih efisien dengan sistem terintegrasi dalam satu platform digital.</p>
                </div>
                <div class="goal-item">
                    <div class="goal-icon"><i class="ph-bold ph-trend-up"></i></div>
                    <h3 class="goal-title">Informatif</h3>
                    <p class="goal-desc">Data diolah menjadi informasi yang mendukung evaluasi dan pengambilan keputusan.</p>
                </div>
                <div class="goal-item">
                    <div class="goal-icon"><i class="ph-bold ph-shield-check"></i></div>
                    <h3 class="goal-title">Mendukung Transformasi Digital</h3>
                    <p class="goal-desc">Bagian dari upaya transformasi digital dalam pengelolaan informasi di lingkungan PPEJP.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Global Footer -->
    @include('layouts.footer')

</body>
</html>

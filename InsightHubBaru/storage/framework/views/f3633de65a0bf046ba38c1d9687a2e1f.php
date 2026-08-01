<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InsightHub - Pusat Analisis dan Insight Media Sosial</title>
    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <!-- Custom CSS with cache busting -->
    <link rel="stylesheet" href="<?php echo e(asset('css/style.css')); ?>?v=<?php echo e(time()); ?>">
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar">
        <div class="container navbar-container">
            <a href="#" class="logo">
                <span class="logo-mark"><i class="ph-fill ph-chart-bar"></i></span> InsightHub
            </a>
            <ul class="nav-links">
                <li><a href="#" class="active">Home</a></li>
                <li><a href="<?php echo e(route('dashboard')); ?>">Dashboard</a></li>
                <li><a href="#">Juknis</a></li>
            </ul>
            <div class="nav-actions">
                <a href="<?php echo e(route('login')); ?>" class="login-link">Log In</a>
                <a href="<?php echo e(route('dashboard')); ?>" class="btn btn-primary">Get Started</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container hero-container">
            <div class="hero-content">
                <div class="badge">
                    <i class="ph ph-check-circle"></i> Powered by MMD Division
                </div>
                <h1 class="hero-title">InsightHub: Pusat Analisis dan <span>Insight Media Sosial</span></h1>
                <p class="hero-subtitle">Optimalkan strategi digital Anda dengan pengolahan data media sosial yang presisi. Divisi MMD menghadirkan platform analitik tercanggih untuk mengubah angka menjadi keputusan strategis.</p>
                <div class="hero-actions">
                    <a href="<?php echo e(route('dashboard')); ?>" class="btn btn-primary">Mulai Sekarang</a>
                </div>
            </div>
            <div class="hero-image">
                <img src="<?php echo e(asset('assets/dashboard.png')); ?>" alt="InsightHub Ilustrasi">
            </div>
        </div>
    </section>

    <!-- Tentang Section -->
    <section class="about-section">
        <div class="container">
            <div class="about-card">
                <div class="card-badge">
                    <i class="ph ph-globe"></i> Platform Overview
                </div>
                <h2 class="card-title">Tentang InsightHub</h2>
                <p class="card-text">InsightHub adalah platform analitik media sosial terpadu yang dirancang khusus untuk Divisi MMD guna memantau, menganalisis, dan mengoptimalkan performa digital secara real-time.</p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-brand">
                    <a href="#" class="logo">
                        <span class="logo-mark"><i class="ph-fill ph-chart-bar"></i></span> InsightHub
                    </a>
                    <p class="brand-text">Solusi analitik terdepan untuk strategi media sosial yang berbasis data. Memberdayakan Divisi MMD dengan wawasan real-time.</p>
                    <div class="social-links">
                        <a href="#" aria-label="Share"><i class="ph ph-share-network"></i></a>
                        <a href="#" aria-label="Website"><i class="ph ph-globe"></i></a>
                        <a href="#" aria-label="Email"><i class="ph ph-envelope-simple"></i></a>
                    </div>
                </div>
                <div class="footer-column">
                    <h3>Produk</h3>
                    <ul>
                        <li><a href="#">Beranda</a></li>
                        <li><a href="#">Dashboard</a></li>
                        <li><a href="#">Laporan Analitik</a></li>
                        <li><a href="#">Fitur Utama</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h3>Legal</h3>
                    <ul>
                        <li><a href="#">Kebijakan Privasi</a></li>
                        <li><a href="#">Syarat & Ketentuan</a></li>
                        <li><a href="#">Keamanan Data</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h3>Bantuan</h3>
                    <ul>
                        <li><a href="#">Pusat Bantuan</a></li>
                        <li><a href="#">Hubungi Kami</a></li>
                        <li><a href="#">Dokumentasi</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2024 InsightHub oleh Divisi MMD. Seluruh hak cipta dilindungi.</p>
                <div class="footer-bottom-links">
                    <a href="#">Status Sistem</a>
                    <a href="#">Cookie Settings</a>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>
<?php /**PATH D:\Project-Magang\InsightHub\frontend\resources\views\welcome.blade.php ENDPATH**/ ?>
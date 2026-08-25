<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics Pro - Marketing Dashboard</title>
    <!-- Phosphor Icons -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/style.css">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo e(asset('css/dashboard.css')); ?>?v=<?php echo e(time()); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/input.css')); ?>?v=<?php echo e(time()); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/laporan.css')); ?>?v=<?php echo e(time()); ?>">
    <?php echo $__env->yieldPushContent('css'); ?>
</head>
<body>

    <!-- ═══════════════════════════════════════
         TOP NAVIGATION BAR
    ════════════════════════════════════════ -->
    <header class="topnav" id="topnav">

        <!-- Logo -->
        <a href="<?php echo e(url('/')); ?>" class="topnav-brand" style="text-decoration:none; display:flex; align-items:center; padding-left: 0.5rem; flex-shrink: 0;" title="Kembali ke Beranda">
            <div style="width: 180px; height: 52px; overflow: hidden; position: relative;">
                <img src="<?php echo e(asset('assets/logo-kemendag.png')); ?>"
                     alt="Kementerian Perdagangan"
                     style="width: 195px !important; height: auto !important; max-width: none !important; position: absolute; top: 50%; left: -20px; transform: translateY(-50%); display: block; padding: 0; margin: 0;">
            </div>
        </a>

        <!-- Nav Links -->
        <nav class="topnav-links" id="topnav-links">
            <?php if(request()->routeIs('dashboard')): ?>
                <a class="topnav-item active" data-tab="ringkasan" onclick="switchTab('ringkasan', this)" id="nav-ringkasan">Ringkasan</a>
                <a class="topnav-item" data-tab="analitik" onclick="switchTab('analitik', this)" id="nav-analitik">Analitik Konten</a>
                <?php if(auth()->check() && in_array(auth()->user()->role, ['super-admin', 'admin'])): ?>
                <a class="topnav-item" data-tab="input" onclick="switchTab('input', this)" id="nav-input">Input Data</a>
                <?php endif; ?>
                <a class="topnav-item" data-tab="laporan" onclick="switchTab('laporan', this)" id="nav-laporan">Laporan</a>
            <?php else: ?>
                <a class="topnav-item" href="<?php echo e(route('dashboard')); ?>?tab=ringkasan">Ringkasan</a>
                <a class="topnav-item" href="<?php echo e(route('dashboard')); ?>?tab=analitik">Analitik Konten</a>
                <?php if(auth()->check() && in_array(auth()->user()->role, ['super-admin', 'admin'])): ?>
                <a class="topnav-item" href="<?php echo e(route('dashboard')); ?>?tab=input">Input Data</a>
                <?php endif; ?>
                <a class="topnav-item" href="<?php echo e(route('dashboard')); ?>?tab=laporan">Laporan</a>
            <?php endif; ?>

            <?php if(auth()->check() && in_array(auth()->user()->role, ['super-admin', 'admin'])): ?>
            <a class="topnav-item <?php echo e(request()->routeIs('pengguna.index') ? 'active' : ''); ?>" href="<?php echo e(route('pengguna.index')); ?>">Manajemen Data Pengguna</a>
            <?php endif; ?>
            <a class="topnav-item <?php echo e(request()->routeIs('profile.show') ? 'active' : ''); ?>" href="<?php echo e(route('profile.show')); ?>" id="nav-profile">Profil</a>
        </nav>

        <!-- Right: Search + User -->
        <div class="topnav-right">
            <!-- Search -->
            <div class="topnav-search">
                <i class="ph ph-magnifying-glass"></i>
                <input type="text" id="global-search" placeholder="Cari..." autocomplete="off">
                <span class="topnav-search-shortcut">/</span>
            </div>

            <!-- User Avatar + Logout -->
            <div class="topnav-user-group">
                <a href="<?php echo e(route('profile.show')); ?>" class="topnav-avatar-link" title="<?php echo e(auth()->check() ? auth()->user()->name : ''); ?>">
                    <?php
                        if (auth()->check()) {
                            $hasAvatar = auth()->user()->avatar && file_exists(public_path(auth()->user()->avatar));
                            $nameParts = explode(' ', trim(auth()->user()->name));
                            $initials = count($nameParts) > 1 
                                ? strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[1], 0, 1))
                                : strtoupper(substr(auth()->user()->name, 0, 2));
                        } else {
                            $hasAvatar = false;
                            $initials = 'U';
                        }
                    ?>
                    <?php if($hasAvatar): ?>
                        <img src="<?php echo e(asset(auth()->user()->avatar)); ?>" alt="<?php echo e(auth()->user()->name); ?>" class="topnav-avatar-img">
                    <?php else: ?>
                        <div class="topnav-avatar-initials">
                            <?php echo e($initials); ?>

                        </div>
                    <?php endif; ?>
                </a>
                <button class="topnav-logout-btn"
                        onclick="event.preventDefault(); document.getElementById('topnav-logout-form').submit();"
                        title="Logout">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 6px;"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                    <span>Logout</span>
                </button>
                <form id="topnav-logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display:none;">
                    <?php echo csrf_field(); ?>
                </form>
            </div>
        </div>
    </header>

    <!-- ═══════════════════════════════════════
         MAIN CONTENT
    ════════════════════════════════════════ -->
    <main class="main-content">
        <?php echo $__env->yieldContent('content'); ?>
        
        <!-- Global Footer -->
        <?php echo $__env->make('layouts.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </main>

    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\laragon\www\InsightHubBaru\frontend\resources\views/layouts/app.blade.php ENDPATH**/ ?>
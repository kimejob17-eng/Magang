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
            <?php if(auth()->check() && auth()->user()->hasPermission('ringkasan.lihat', 'view')): ?>
            <a class="topnav-item active" data-tab="ringkasan" onclick="switchTab('ringkasan', this)" id="nav-ringkasan">Ringkasan</a>
            <?php endif; ?>
            <?php if(auth()->check() && auth()->user()->hasPermission('analitik.lihat', 'view')): ?>
            <a class="topnav-item" data-tab="analitik" onclick="switchTab('analitik', this)" id="nav-analitik">Analitik Konten</a>
            <?php endif; ?>
            <?php if(auth()->check() && auth()->user()->hasPermission('input.lihat', 'view')): ?>
            <a class="topnav-item" data-tab="input" onclick="switchTab('input', this)" id="nav-input">Input Data</a>
            <?php endif; ?>
            <?php if(auth()->check() && auth()->user()->hasPermission('laporan.lihat', 'view')): ?>
            <a class="topnav-item" data-tab="laporan" onclick="switchTab('laporan', this)" id="nav-laporan">Laporan</a>
            <?php endif; ?>
        <?php else: ?>
            <?php if(auth()->check() && auth()->user()->hasPermission('ringkasan.lihat', 'view')): ?>
            <a class="topnav-item" href="<?php echo e(route('dashboard')); ?>?tab=ringkasan">Ringkasan</a>
            <?php endif; ?>
            <?php if(auth()->check() && auth()->user()->hasPermission('analitik.lihat', 'view')): ?>
            <a class="topnav-item" href="<?php echo e(route('dashboard')); ?>?tab=analitik">Analitik Konten</a>
            <?php endif; ?>
            <?php if(auth()->check() && auth()->user()->hasPermission('input.lihat', 'view')): ?>
            <a class="topnav-item" href="<?php echo e(route('dashboard')); ?>?tab=input">Input Data</a>
            <?php endif; ?>
            <?php if(auth()->check() && auth()->user()->hasPermission('laporan.lihat', 'view')): ?>
            <a class="topnav-item" href="<?php echo e(route('dashboard')); ?>?tab=laporan">Laporan</a>
            <?php endif; ?>
        <?php endif; ?>

        <?php if(auth()->check() && auth()->user()->hasPermission('manajemen-pengguna.lihat', 'view')): ?>
        <a class="topnav-item <?php echo e(request()->routeIs('pengguna.index') ? 'active' : ''); ?>" href="<?php echo e(route('pengguna.index')); ?>">Manajemen Data Pengguna</a>
        <?php endif; ?>

        <?php if(auth()->check() && auth()->user()->hasPermission('manajemen-akses.kelola-role', 'view')): ?>
        <a class="topnav-item <?php echo e(request()->routeIs('role-mapping.index') ? 'active' : ''); ?>" href="<?php echo e(route('role-mapping.index')); ?>">Role Mapping</a>
        <?php endif; ?>

        <?php if(request()->routeIs('kategori.index')): ?>
        <a href="#" class="topnav-item active">Kategori Konten</a>
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
            <?php if(auth()->check() && in_array(auth()->user()->role, ['super-admin', 'admin'])): ?>
                <?php
                    $pendingCount = $exportRequests->where('status', 'pending')->count();
                ?>
                <button type="button" onclick="openApprovalModal()" title="Permintaan Ekspor Pending" style="position: relative; background: none; border: none; color: #475569; cursor: pointer; display: flex; align-items: center; justify-content: center; padding: 6px; border-radius: 50%; transition: all 0.2s; margin-right: 8px; outline: none;" onmouseover="this.style.background='#f1f5f9'; this.style.color='#003EA8';" onmouseout="this.style.background='none'; this.style.color='#475569';">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 20px; height: 20px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                    </svg>
                    <?php if($pendingCount > 0): ?>
                        <span style="position: absolute; top: 0; right: 0; background: #ef4444; color: white; font-size: 0.65rem; font-weight: 700; min-width: 16px; height: 16px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 1.5px solid #fff; padding: 0 4px; box-sizing: border-box;">
                            <?php echo e($pendingCount); ?>

                        </span>
                    <?php endif; ?>
                </button>
            <?php endif; ?>
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

<?php if(auth()->check()): ?>
    <?php if(in_array(auth()->user()->role, ['super-admin', 'admin'])): ?>
        <?php echo $__env->make('pages.Export.approval', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php endif; ?>
    <?php echo $__env->make('pages.Export.history', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php endif; ?>
<?php /**PATH D:\magang\InsightHubBaru\frontend\resources\views/layouts/navbar.blade.php ENDPATH**/ ?>
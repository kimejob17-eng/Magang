<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics Pro - Marketing Dashboard</title>
    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo e(asset('css/dashboard.css')); ?>?v=<?php echo e(time()); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/input.css')); ?>?v=<?php echo e(time()); ?>">
</head>
<body>

    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <h2>Analytics Pro</h2>
            <p>MARKETING DASHBOARD</p>
        </div>
        
        <nav class="sidebar-nav">
            <a class="nav-item active" onclick="switchTab('ringkasan', this)">
                <i class="ph ph-squares-four"></i> Ringkasan
            </a>
            <a class="nav-item" onclick="switchTab('analitik', this)">
                <i class="ph ph-chart-line-up"></i> Analitik Konten
            </a>
            <a class="nav-item" onclick="switchTab('input', this)">
                <i class="ph ph-plus-square"></i> Input Data
            </a>
            <a class="nav-item" onclick="switchTab('laporan', this)">
                <i class="ph ph-file-text"></i> Laporan
            </a>
            <a class="nav-item" style="margin-top: 1rem;" href="<?php echo e(route('profile.show')); ?>">
                <i class="ph ph-gear"></i> Profile
            </a>
        </nav>
        
        <div class="sidebar-footer">
            <a class="user-logout-card" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" title="Logout">
                <?php if(auth()->user()->avatar): ?>
                    <img src="<?php echo e(asset(auth()->user()->avatar)); ?>" alt="Logout" class="user-card-avatar">
                <?php else: ?>
                    <div class="user-card-avatar-initials">
                        <?php echo e(strtoupper(substr(auth()->user()->name, 0, 2))); ?>

                    </div>
                <?php endif; ?>
                <span class="user-card-logout-text">Logout</span>
            </a>
            <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;">
                <?php echo csrf_field(); ?>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        
        <!-- Topbar -->
        <header class="topbar">
            <div class="search-bar">
                <i class="ph ph-magnifying-glass"></i>
                <input type="text" id="global-search" placeholder="Cari data atau laporan..." autocomplete="off">
                <span class="search-shortcut">/</span>
            </div>
            
            <div class="topbar-actions">
                <button class="btn" id="topbar-export">Export</button>
                
                <div id="topbar-divider" style="width: 1px; height: 24px; background: #e2e8f0; margin: 0 0.5rem;"></div>
                
                <button class="icon-btn" id="topbar-notification"><i class="ph ph-bell"></i></button>
                <button class="icon-btn" id="topbar-calendar"><i class="ph ph-calendar-blank"></i></button>
                
                <div class="user-profile">
                    <img src="<?php echo e(auth()->user()->avatar ? asset(auth()->user()->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=003EA8&color=fff&bold=true'); ?>" alt="<?php echo e(auth()->user()->name); ?>">
                </div>
            </div>
        </header>

        <!-- Tab: Ringkasan -->
        <?php echo $__env->make('pages.Dashboard.ringkasan', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <!-- Tab: Analitik Konten -->
        <?php echo $__env->make('pages.Dashboard.analitik', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <!-- Tab: Input Data -->
        <?php echo $__env->make('pages.Input.input', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <!-- Tab: Laporan -->
        <?php echo $__env->make('pages.Report.laporan', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    </main>

    <!-- JavaScript for Tab Switching -->
    <script>
        function switchTab(tabId, element) {
            // Remove active class from all tabs
            document.querySelectorAll('.dashboard-container').forEach(tab => {
                tab.classList.remove('active');
            });
            
            // Remove active class from all nav items
            document.querySelectorAll('.nav-item').forEach(nav => {
                nav.classList.remove('active');
            });
            
            // Add active class to selected tab and nav item
            document.getElementById('tab-' + tabId).classList.add('active');
            if (element) element.classList.add('active');

            // Clear search when switching tabs
            const globalSearch = document.getElementById('global-search');
            if (globalSearch) {
                globalSearch.value = '';
                // Reset visibility of filtered elements
                document.querySelectorAll('.data-table tbody tr, .rk-kpi-card, .integration-card, .content-item').forEach(el => {
                    el.style.display = '';
                });
            }

            // Hide/Show topbar actions specifically for Input Data tab
            const topbarExport = document.getElementById('topbar-export');
            const topbarDivider = document.getElementById('topbar-divider');
            const topbarNotification = document.getElementById('topbar-notification');
            const topbarCalendar = document.getElementById('topbar-calendar');

            if (tabId === 'input') {
                if (topbarExport) topbarExport.style.display = 'none';
                if (topbarDivider) topbarDivider.style.display = 'none';
                if (topbarNotification) topbarNotification.style.display = 'none';
                if (topbarCalendar) topbarCalendar.style.display = 'none';
            } else {
                if (topbarExport) topbarExport.style.display = '';
                if (topbarDivider) topbarDivider.style.display = '';
                if (topbarNotification) topbarNotification.style.display = '';
                if (topbarCalendar) topbarCalendar.style.display = '';
            }
        }

        // ---- Auto switch tab based on URL query param ----
        (function () {
            var urlParams = new URLSearchParams(window.location.search);
            var tab = urlParams.get('tab');
            if (tab) {
                var navEl = document.querySelector('.nav-item[onclick*="' + tab + '"]');
                switchTab(tab, navEl);
                // Scroll to top of content smoothly
                window.scrollTo({ top: 0 });
            }
        })();

        // ---- Global Search Filter ----
        const globalSearch = document.getElementById('global-search');
        if (globalSearch) {
            globalSearch.addEventListener('input', function(e) {
                const query = e.target.value.toLowerCase().trim();
                const activeContainer = document.querySelector('.dashboard-container.active');
                if (!activeContainer) return;

                // 1. Filter table rows
                const rows = activeContainer.querySelectorAll('.data-table tbody tr');
                if (rows.length > 0) {
                    rows.forEach(row => {
                        if (row.id === 'empty-search-row') return;
                        const text = row.textContent.toLowerCase();
                        row.style.display = text.includes(query) ? '' : 'none';
                    });
                }

                // 2. Filter cards & list items
                const cards = activeContainer.querySelectorAll('.rk-kpi-card, .integration-card, .content-item');
                if (cards.length > 0) {
                    cards.forEach(card => {
                        const text = card.textContent.toLowerCase();
                        card.style.display = text.includes(query) ? '' : 'none';
                    });
                }
            });
        }

        // Keyboard Hotkey '/' to focus search input
        document.addEventListener('keydown', function(e) {
            if (e.key === '/' && document.activeElement !== globalSearch && document.activeElement.tagName !== 'INPUT' && document.activeElement.tagName !== 'SELECT' && document.activeElement.tagName !== 'TEXTAREA') {
                e.preventDefault();
                if (globalSearch) {
                    globalSearch.focus();
                    globalSearch.select();
                }
            }
        });
    </script>
</body>
</html><?php /**PATH D:\Project-Magang\InsightHub\frontend\resources\views/pages/Dashboard/index.blade.php ENDPATH**/ ?>
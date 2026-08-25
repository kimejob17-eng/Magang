<?php $__env->startSection('content'); ?>
    <!-- Tab: Ringkasan -->
    <?php echo $__env->make('pages.Dashboard.ringkasan', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- Tab: Analitik Konten -->
    <?php echo $__env->make('pages.Dashboard.analitik', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- Tab: Input Data -->
    <?php if(auth()->check() && in_array(auth()->user()->role, ['super-admin', 'admin'])): ?>
    <?php echo $__env->make('pages.Input.input', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php endif; ?>

    <!-- Tab: Laporan -->
    <?php echo $__env->make('pages.Report.laporan', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <!-- JavaScript for Tab Switching -->
    <script>
        function switchTab(tabId, element) {
            // Remove active class from all tabs
            document.querySelectorAll('.dashboard-container').forEach(tab => {
                tab.classList.remove('active');
            });

            // Remove active class from all nav items
            document.querySelectorAll('.topnav-item').forEach(nav => {
                nav.classList.remove('active');
            });

            // Add active class to selected tab and nav item
            const targetTab = document.getElementById('tab-' + tabId);
            if(targetTab) targetTab.classList.add('active');
            if (element) element.classList.add('active');

            // Persist active tab to sessionStorage
            sessionStorage.setItem('dashboard_active_tab', tabId);

            // Clear search when switching tabs
            const globalSearch = document.getElementById('global-search');
            if (globalSearch) {
                globalSearch.value = '';
                // Reset visibility of filtered elements
                document.querySelectorAll('.data-table tbody tr, .rk-kpi-card, .integration-card, .content-item').forEach(el => {
                    el.style.display = '';
                });
            }
        }

        // ---- Restore active tab: URL param > sessionStorage > default (ringkasan) ----
        (function () {
            var urlParams = new URLSearchParams(window.location.search);
            var tab = urlParams.get('tab') || sessionStorage.getItem('dashboard_active_tab') || 'ringkasan';
            var navEl = document.querySelector('.topnav-item[data-tab="' + tab + '"]');
            
            // If the tab link doesn't exist (e.g. hidden due to permissions), fallback to ringkasan
            if (!navEl) {
                tab = 'ringkasan';
                navEl = document.querySelector('.topnav-item[data-tab="' + tab + '"]');
            }

            switchTab(tab, navEl);
            window.scrollTo({ top: 0 });
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
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\InsightHubBaru\frontend\resources\views/pages/Dashboard/index.blade.php ENDPATH**/ ?>
@extends('layouts.app')

@section('content')
    @php
        $hasAnyTabAccess = false;
    @endphp

    <!-- Tab: Ringkasan -->
    @if(auth()->check() && auth()->user()->hasPermission('ringkasan.lihat', 'view'))
        @php $hasAnyTabAccess = true; @endphp
        @include('pages.Dashboard.ringkasan')
    @endif

    <!-- Tab: Analitik Konten -->
    @if(auth()->check() && auth()->user()->hasPermission('analitik.lihat', 'view'))
        @php $hasAnyTabAccess = true; @endphp
        @include('pages.Dashboard.analitik')
    @endif

    <!-- Tab: Input Data -->
    @if(auth()->check() && auth()->user()->hasPermission('input.lihat', 'view'))
        @php $hasAnyTabAccess = true; @endphp
        @include('pages.Input.input')
    @endif

    <!-- Tab: Laporan -->
    @if(auth()->check() && auth()->user()->hasPermission('laporan.lihat', 'view'))
        @php $hasAnyTabAccess = true; @endphp
        @include('pages.Report.laporan')
    @endif

    @if(!$hasAnyTabAccess)
        <style>
            .error-card-container {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                min-height: 440px;
                text-align: center;
                padding: 48px;
                background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
                border-radius: 20px;
                border: 1px solid #e2e8f0;
                margin: 24px;
                font-family: 'Inter', sans-serif;
                box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.03), 0 1px 3px 0 rgba(0, 0, 0, 0.02);
                animation: cardSlideIn 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
                position: relative;
                overflow: hidden;
            }

            .error-card-container::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 4px;
                background: linear-gradient(90deg, #003ea8, #3b82f6);
            }

            .icon-wrapper {
                position: relative;
                display: flex;
                align-items: center;
                justify-content: center;
                width: 100px;
                height: 100px;
                margin-bottom: 24px;
            }

            .pulse-circle {
                position: absolute;
                width: 80px;
                height: 80px;
                background-color: rgba(0, 62, 168, 0.08);
                border-radius: 50%;
                animation: pulse-ring 2.5s infinite ease-in-out;
            }

            .pulse-circle-inner {
                position: absolute;
                width: 60px;
                height: 60px;
                background-color: rgba(59, 130, 246, 0.05);
                border-radius: 50%;
                animation: pulse-ring 2.5s infinite ease-in-out 0.8s;
            }

            .floating-icon {
                font-size: 54px;
                color: #003ea8;
                z-index: 2;
                filter: drop-shadow(0 4px 8px rgba(0, 62, 168, 0.2));
                animation: float-motion 3.5s ease-in-out infinite;
            }

            .error-title {
                font-size: 22px;
                font-weight: 700;
                color: #0f172a;
                margin: 0 0 10px 0;
                letter-spacing: -0.5px;
            }

            .error-desc {
                color: #64748b;
                max-width: 440px;
                margin: 0 0 24px 0;
                font-size: 14px;
                line-height: 1.6;
            }

            .action-btn-group {
                display: flex;
                gap: 12px;
                justify-content: center;
                flex-wrap: wrap;
            }

            .btn-action-primary {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                padding: 10px 20px;
                background: linear-gradient(135deg, #003ea8 0%, #00225c 100%);
                color: #ffffff;
                font-size: 14px;
                font-weight: 600;
                border-radius: 8px;
                text-decoration: none;
                transition: all 0.25s ease;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
                border: 1px solid transparent;
                cursor: pointer;
            }

            .btn-action-primary:hover {
                transform: translateY(-2px);
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.15), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
                filter: brightness(1.1);
            }

            .btn-action-primary:active {
                transform: translateY(0);
            }

            .btn-action-secondary {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                padding: 10px 20px;
                background: #ffffff;
                color: #475569;
                font-size: 14px;
                font-weight: 600;
                border-radius: 8px;
                text-decoration: none;
                transition: all 0.25s ease;
                border: 1px solid #e2e8f0;
                cursor: pointer;
            }

            .btn-action-secondary:hover {
                background: #f8fafc;
                border-color: #cbd5e1;
                color: #1e293b;
            }

            .btn-action-secondary i {
                transition: transform 0.3s ease;
            }

            .btn-action-secondary:hover i {
                transform: rotate(180deg);
            }

            @keyframes cardSlideIn {
                0% { opacity: 0; transform: translateY(20px); }
                100% { opacity: 1; transform: translateY(0); }
            }

            @keyframes float-motion {
                0% { transform: translateY(0px); }
                50% { transform: translateY(-8px); }
                100% { transform: translateY(0px); }
            }

            @keyframes pulse-ring {
                0% { transform: scale(0.9); opacity: 0.6; }
                50% { transform: scale(1.3); opacity: 0.1; }
                100% { transform: scale(0.9); opacity: 0.6; }
            }
        </style>

        <div class="dashboard-container active">
            <div class="error-card-container">
                <div class="icon-wrapper">
                    <div class="pulse-circle"></div>
                    <div class="pulse-circle-inner"></div>
                    <i class="ph ph-shield-warning floating-icon"></i>
                </div>
                <h2 class="error-title">Tidak Ada Akses Fitur</h2>
                <p class="error-desc">Akun Anda saat ini tidak memiliki izin akses ke modul dashboard mana pun. Silakan hubungi Super Admin untuk mengonfigurasi hak akses Anda.</p>
                <div class="action-btn-group">
                    <a href="mailto:admin@sovie.com?subject=Permintaan%20Akses%20Dashboard%20Sovie" class="btn-action-primary">
                        <i class="ph ph-envelope-simple"></i>
                        <span>Hubungi Admin</span>
                    </a>
                    <button onclick="window.location.reload()" class="btn-action-secondary">
                        <i class="ph ph-arrow-counter-clockwise"></i>
                        <span>Muat Ulang Halaman</span>
                    </button>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('scripts')
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
            
            // If the tab link doesn't exist (e.g. hidden due to permissions), fallback to the first visible tab
            if (!navEl) {
                var firstVisibleTab = document.querySelector('.topnav-item[data-tab]');
                if (firstVisibleTab) {
                    tab = firstVisibleTab.getAttribute('data-tab');
                    navEl = firstVisibleTab;
                } else {
                    tab = null;
                    navEl = null;
                }
            }

            if (tab && navEl) {
                switchTab(tab, navEl);
            }
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
@endpush
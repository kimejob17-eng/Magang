<div id="tab-ringkasan" class="dashboard-container active">
    <style>
        #tab-ringkasan{ --rk-ink:#0f172a; --rk-sub:#64748b; --rk-line:#e6e9ef; }

        #tab-ringkasan .rk-kpi-grid{
            display:grid; grid-template-columns:repeat(6, minmax(0,1fr)); gap:1rem; margin-bottom:2rem;
        }
        #tab-ringkasan .rk-kpi-card{
            background:#fff; border:1px solid var(--rk-line); border-radius:14px; padding:1.25rem 1.2rem;
            box-shadow:0 1px 2px rgba(15,23,42,.04); transition:box-shadow .18s ease, transform .18s ease, border-color .18s ease;
            min-width:0;
        }
        #tab-ringkasan .rk-kpi-card:hover{ box-shadow:0 10px 24px rgba(15,23,42,.08); transform:translateY(-2px); border-color:#dfe3ec; }
        #tab-ringkasan .rk-kpi-top{ display:flex; justify-content:space-between; align-items:flex-start; gap:0.5rem; margin-bottom:1rem; }
        #tab-ringkasan .rk-kpi-icon{
            width:38px; height:38px; border-radius:10px; display:flex; align-items:center; justify-content:center;
            font-size:1.1rem; flex-shrink:0;
        }
        #tab-ringkasan .rk-kpi-delta{
            display:inline-flex; align-items:center; gap:0.2rem; padding:0.2rem 0.5rem; border-radius:999px;
            font-size:0.68rem; font-weight:700; white-space:nowrap;
        }
        #tab-ringkasan .rk-kpi-delta.up{ background:#dcfce7; color:#166534; }
        #tab-ringkasan .rk-kpi-delta.down{ background:#fee2e2; color:#b91c1c; }
        #tab-ringkasan .rk-kpi-delta.flat{ background:#fef9c3; color:#854d0e; }
        #tab-ringkasan .rk-kpi-label{
            font-size:0.78rem; color:var(--rk-sub); font-weight:600; margin-bottom:0.35rem; line-height:1.25;
        }
        #tab-ringkasan .rk-kpi-value{
            font-size:clamp(1.15rem, 1.7vw, 1.5rem); font-weight:700; color:var(--rk-ink); letter-spacing:-0.01em;
            font-variant-numeric:tabular-nums; white-space:nowrap;
        }

        @media (max-width: 1400px){ #tab-ringkasan .rk-kpi-grid{ grid-template-columns:repeat(4, 1fr); } }
        @media (max-width: 900px) { #tab-ringkasan .rk-kpi-grid{ grid-template-columns:repeat(3, 1fr); } }
        @media (max-width: 720px) { #tab-ringkasan .rk-kpi-grid{ grid-template-columns:repeat(2, 1fr); } }
        @media (max-width: 460px) { #tab-ringkasan .rk-kpi-grid{ grid-template-columns:1fr; } }
    </style>

    <div class="page-header" style="margin-bottom: 2rem;">
        <div style="margin-bottom: 1.25rem;">
            <h1 style="font-size: 1.75rem; font-weight: 700; color: #0f172a; margin-bottom: 0.5rem; letter-spacing: -0.5px;">Executive Summary</h1>
            <p style="color: #64748b; font-size: 0.95rem;">Menyajikan ringkasan metrik utama dari seluruh platform untuk memberikan gambaran umum kinerja secara menyeluruh.</p>
        </div>
        
        <!-- Filters & Export -->
        <div style="display: flex; flex-direction: row; align-items: center; justify-content: flex-start; flex-wrap: nowrap; gap: 12px; margin-top: 0.5rem;">
            <form id="filterRingkasanForm" method="GET" action="<?php echo e(route('dashboard')); ?>" style="display: contents;">
                <input type="hidden" name="tab" value="ringkasan">
                
                <select name="ringkasan_periode" id="ringkasanPeriode" onchange="toggleRingkasanFilters(); this.form.submit()" style="padding: 0.5rem 1rem; border-radius: 2rem; border: 1px solid #e2e8f0; background: #fff; color: #0f172a; font-weight: 500; font-size: 0.85rem; cursor: pointer; outline: none; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
                    <option value="semua" <?php echo e($ringkasanPeriode === 'semua' ? 'selected' : ''); ?>>Semua Waktu</option>
                    <option value="bulanan" <?php echo e($ringkasanPeriode === 'bulanan' ? 'selected' : ''); ?>>Bulanan</option>
                    <option value="tahunan" <?php echo e($ringkasanPeriode === 'tahunan' ? 'selected' : ''); ?>>Tahunan</option>
                </select>

                <?php
                    $bulanList = [
                        '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
                        '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
                        '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
                    ];
                ?>

                <select name="ringkasan_bulan" id="ringkasanBulan" onchange="this.form.submit()" style="padding: 0.5rem 1rem; border-radius: 2rem; border: 1px solid #e2e8f0; background: #fff; color: #0f172a; font-weight: 500; font-size: 0.85rem; cursor: pointer; outline: none; box-shadow: 0 1px 2px rgba(0,0,0,0.05); display: <?php echo e($ringkasanPeriode === 'bulanan' ? 'inline-block' : 'none'); ?>;">
                    <?php $__currentLoopData = $bulanList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $num => $nama): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($num); ?>" <?php echo e($ringkasanBulan == $num ? 'selected' : ''); ?>><?php echo e($nama); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>

                <select name="ringkasan_tahun" id="ringkasanTahun" onchange="this.form.submit()" style="padding: 0.5rem 1rem; border-radius: 2rem; border: 1px solid #e2e8f0; background: #fff; color: #0f172a; font-weight: 500; font-size: 0.85rem; cursor: pointer; outline: none; box-shadow: 0 1px 2px rgba(0,0,0,0.05); display: <?php echo e(in_array($ringkasanPeriode, ['bulanan', 'tahunan']) ? 'inline-block' : 'none'); ?>;">
                    <?php $__currentLoopData = $availableYears; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $thn): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($thn); ?>" <?php echo e($ringkasanTahun == $thn ? 'selected' : ''); ?>><?php echo e($thn); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>

                <select id="filterPlatform" name="ringkasan_platform" onchange="this.form.submit()" style="padding: 0.5rem 1rem; border-radius: 2rem; border: 1px solid #e2e8f0; background: #fff; color: #0f172a; font-weight: 500; font-size: 0.85rem; cursor: pointer; outline: none; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
                    <option value="Semua Platform" <?php echo e($ringkasanPlatform == 'Semua Platform' ? 'selected' : ''); ?>>Semua Platform</option>
                    <option value="Instagram" <?php echo e($ringkasanPlatform == 'Instagram' ? 'selected' : ''); ?>>Instagram</option>
                    <option value="TikTok" <?php echo e($ringkasanPlatform == 'TikTok' ? 'selected' : ''); ?>>TikTok</option>
                    <option value="Facebook" <?php echo e($ringkasanPlatform == 'Facebook' ? 'selected' : ''); ?>>Facebook</option>
                    <option value="YouTube" <?php echo e($ringkasanPlatform == 'YouTube' ? 'selected' : ''); ?>>YouTube</option>
                </select>
            </form>

            <?php if(auth()->check() && auth()->user()->role === 'user'): ?>
                <?php if(auth()->user()->hasPermission('ringkasan.lihat', 'view')): ?>
                <button type="button" onclick="openExportRingkasanModal()" style="display: inline-flex; align-items: center; justify-content: center; gap: 8px; white-space: nowrap; padding: 0.5rem 1rem; border-radius: 2rem; border: none; background: #ef4444; color: #fff; font-weight: 500; font-size: 0.85rem; cursor: pointer; box-shadow: 0 1px 2px rgba(0,0,0,0.05); text-decoration: none; outline: none;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"></path><polyline points="14 2 14 8 20 8"></polyline><path d="M9 15h6"></path><path d="M9 11h6"></path></svg> Export PDF
                </button>
                <?php endif; ?>
            <?php elseif(auth()->check() && in_array(auth()->user()->role, ['super-admin', 'admin'])): ?>
                <?php if(auth()->user()->hasPermission('ringkasan.lihat', 'view')): ?>
                <a href="<?php echo e(route('dashboard.export.ringkasan.pdf', request()->all())); ?>" style="display: inline-flex; align-items: center; justify-content: center; gap: 8px; white-space: nowrap; padding: 0.5rem 1rem; border-radius: 2rem; border: none; background: #ef4444; color: #fff; font-weight: 500; font-size: 0.85rem; cursor: pointer; box-shadow: 0 1px 2px rgba(0,0,0,0.05); text-decoration: none; outline: none;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"></path><polyline points="14 2 14 8 20 8"></polyline><path d="M9 15h6"></path><path d="M9 11h6"></path></svg> Export PDF
                </a>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
    
    <?php
        // Agregasi Global
        $totalFollowers = $metricsRingkasan->sum('followers_plus');
        $totalReach = $metricsRingkasan->sum('reach');
        $totalEngagement = $metricsRingkasan->sum('likes') + $metricsRingkasan->sum('comments') + $metricsRingkasan->sum('shares') + $metricsRingkasan->where('sumber_tabel', 'youtube_shorts')->sum('repost');
        $totalKonten = $metricsRingkasan->count();
        $engagementRate = $totalReach > 0 ? ($totalEngagement / $totalReach) * 100 : 0;
        
        // Agregasi Per Platform
        $ig = $metricsRingkasan->filter(fn($m) => strtolower($m->platform) === 'instagram');
        $tk = $metricsRingkasan->filter(fn($m) => strtolower($m->platform) === 'tiktok');
        $fb = $metricsRingkasan->filter(fn($m) => strtolower($m->platform) === 'facebook');
        $yt = $metricsRingkasan->filter(fn($m) => strtolower($m->platform) === 'youtube');

        $ytVideo  = $metricsRingkasan->filter(fn($m) => strtolower($m->sumber_tabel) === 'youtube_video');
        $ytShorts = $metricsRingkasan->filter(fn($m) => strtolower($m->sumber_tabel) === 'youtube_shorts');
        $ytLive   = $metricsRingkasan->filter(fn($m) => strtolower($m->sumber_tabel) === 'youtube_live');
        
        $platformsData = [
            'Instagram' => ['data' => $ig, 'color' => '#e1306c', 'soft' => '#fce7f0', 'icon' => 'fa-brands fa-instagram'],
            'TikTok' => ['data' => $tk, 'color' => '#000000', 'soft' => '#f1f5f9', 'icon' => 'fa-brands fa-tiktok'],
            'Facebook' => ['data' => $fb, 'color' => '#1877f2', 'soft' => '#e3edfd', 'icon' => 'fa-brands fa-facebook'],
            'YouTube' => ['data' => $yt, 'color' => '#ff0000', 'soft' => '#fde2e2', 'icon' => 'fa-brands fa-youtube'],
            'YouTube Video'  => ['data' => $ytVideo, 'color' => '#ff0000', 'soft' => '#fde2e2', 'icon' => 'fa-brands fa-youtube'],
            'YouTube Shorts' => ['data' => $ytShorts, 'color' => '#cc0000', 'soft' => '#fde2e2', 'icon' => 'fa-brands fa-youtube'],
            'YouTube Live'   => ['data' => $ytLive, 'color' => '#ff0000', 'soft' => '#fde2e2', 'icon' => 'fa-brands fa-youtube']
        ];
        
        // Insight Logic
        $highestEngPlatformName = '-';
        $highestEngRate = -1;
        $highestGrowthPlatformName = '-';
        $highestSubs = -1;
        
        foreach($platformsData as $name => $pData) {
            $eng = $pData['data']->sum('likes') + $pData['data']->sum('comments') + $pData['data']->sum('shares') + $pData['data']->where('sumber_tabel', 'youtube_shorts')->sum('repost');
            $reach = $pData['data']->sum('reach');
            $subs = $pData['data']->sum('followers_plus');
            $rate = $reach > 0 ? ($eng / $reach) * 100 : 0;
            
            if ($rate > $highestEngRate && $totalKonten > 0) {
                $highestEngRate = $rate;
                $highestEngPlatformName = $name;
            }
            if ($subs > $highestSubs && $totalKonten > 0) {
                $highestSubs = $subs;
                $highestGrowthPlatformName = $name;
            }
        }
        
        $topContent = $metricsRingkasan->sortByDesc('reach')->first();

        // Data untuk Grafik Chart.js
        $chartLabels = ['Instagram', 'TikTok', 'Facebook', 'YouTube'];
        $chartTotalKonten = [ $ig->count(), $tk->count(), $fb->count(), $yt->count() ];
        $chartReach = [ $ig->sum('reach'), $tk->sum('reach'), $fb->sum('reach'), $yt->sum('reach') ];
        $chartEngagement = [
            $ig->sum('likes') + $ig->sum('comments') + $ig->sum('shares'),
            $tk->sum('likes') + $tk->sum('comments') + $tk->sum('shares'),
            $fb->sum('likes') + $fb->sum('comments') + $fb->sum('shares'),
            $yt->sum('likes') + $yt->sum('comments') + $yt->sum('shares') + $yt->where('sumber_tabel', 'youtube_shorts')->sum('repost')
        ];
        $chartLikes = [ $ig->sum('likes'), $tk->sum('likes'), $fb->sum('likes'), $yt->sum('likes') ];
        $chartComments = [ $ig->sum('comments'), $tk->sum('comments'), $fb->sum('comments'), $yt->sum('comments') ];
        $chartShares = [ $ig->sum('shares'), $tk->sum('shares'), $fb->sum('shares'), $yt->sum('shares') ];
        
        // Data untuk Filter JS
        $jsPlatformData = [];
        foreach($platformsData as $name => $pData) {
            $pEng = $pData['data']->sum('likes') + $pData['data']->sum('comments') + $pData['data']->sum('shares') + $pData['data']->where('sumber_tabel', 'youtube_shorts')->sum('repost');
            $pReach = $pData['data']->sum('reach');
            $pSubs = $pData['data']->sum('followers_plus');
            $pCount = $pData['data']->count();
            $pRate = $pReach > 0 ? ($pEng / $pReach) * 100 : 0;

            // Distribusi jenis konten per platform (untuk pie chart platform individual)
            $jenisDistrib = [];
            foreach ($pData['data']->groupBy('jenis') as $jenis => $jItems) {
                $jenisDistrib[$jenis ?: 'Lainnya'] = $jItems->count();
            }

            $jsPlatformData[$name] = [
                'konten' => $pCount,
                'followers' => $pSubs,
                'reach' => $pReach,
                'engagement' => $pEng,
                'likes' => $pData['data']->sum('likes'),
                'comments' => $pData['data']->sum('comments'),
                'shares' => $pData['data']->sum('shares'),
                'rate' => $pRate,
                'growth' => (int)($pSubs * 0.012),
                'jenis_distribusi' => $jenisDistrib,
                'color' => $pData['color'],
            ];
        }
        $jsPlatformData['Semua Platform'] = [
            'konten' => $totalKonten,
            'followers' => $totalFollowers,
            'reach' => $totalReach,
            'engagement' => $totalEngagement,
            'likes' => $metricsRingkasan->sum('likes'),
            'comments' => $metricsRingkasan->sum('comments'),
            'shares' => $metricsRingkasan->sum('shares'),
            'rate' => $engagementRate,
            'growth' => (int)($totalFollowers * 0.012),
            'jenis_distribusi' => [],
            'color' => '#4f46e5',
        ];

        // Agregasi Current (khusus untuk badge tren; terpisah dari total KPI utama
        // supaya mode 'Semua Waktu' tetap bisa tampil persentase, bukan N/A)
        $badgeCurrRch = $metricsRingkasanBadgeCurr->sum('reach');
        $badgeCurrEng = $metricsRingkasanBadgeCurr->sum('likes') + $metricsRingkasanBadgeCurr->sum('comments') + $metricsRingkasanBadgeCurr->sum('shares') + $metricsRingkasanBadgeCurr->where('sumber_tabel', 'youtube_shorts')->sum('repost');

        $c = [
            'kpi-total-konten' => $metricsRingkasanBadgeCurr->count(),
            'kpi-total-followers' => $metricsRingkasanBadgeCurr->sum('followers_plus'),
            'kpi-total-engagement' => $badgeCurrEng,
            'kpi-total-akun-followers' => 0, // static
            'kpi-total-reach' => $badgeCurrRch,
            'kpi-engagement-rate' => $badgeCurrRch > 0 ? ($badgeCurrEng / $badgeCurrRch) * 100 : 0,
            'kpi-pertumbuhan-followers' => $metricsRingkasanBadgeCurr->sum('followers_plus')
        ];

        // Agregasi Previous (badge tren)
        $badgePrevRch = $metricsRingkasanBadgePrev->sum('reach');
        $badgePrevEng = $metricsRingkasanBadgePrev->sum('likes') + $metricsRingkasanBadgePrev->sum('comments') + $metricsRingkasanBadgePrev->sum('shares') + $metricsRingkasanBadgePrev->where('sumber_tabel', 'youtube_shorts')->sum('repost');

        $p = [
            'kpi-total-konten' => $metricsRingkasanBadgePrev->count(),
            'kpi-total-followers' => $metricsRingkasanBadgePrev->sum('followers_plus'),
            'kpi-total-engagement' => $badgePrevEng,
            'kpi-total-akun-followers' => 0,
            'kpi-total-reach' => $badgePrevRch,
            'kpi-engagement-rate' => $badgePrevRch > 0 ? ($badgePrevEng / $badgePrevRch) * 100 : 0,
            'kpi-pertumbuhan-followers' => $metricsRingkasanBadgePrev->sum('followers_plus')
        ];

        $badgeDeltas = [];
        foreach ($c as $k => $cVal) {
            $pVal = $p[$k];
            if ($k === 'kpi-total-akun-followers') {
                $badgeDeltas[$k] = null;
            } else if ($pVal == 0) {
                $badgeDeltas[$k] = 'baru';
            } else {
                $badgeDeltas[$k] = (($cVal - $pVal) / $pVal) * 100;
            }
        }
    ?>

    <!-- KPI UTAMA -->
    <div class="rk-kpi-grid">

        
        <div class="rk-kpi-card">
            <div class="rk-kpi-top">
                <div class="rk-kpi-icon" style="background:#eef2ff; color:#4f46e5;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="12 2 22 8.5 12 15 2 8.5 12 2"/>
                        <polyline points="2 13.5 12 20 22 13.5"/>
                        <polyline points="2 10.5 12 17 22 10.5"/>
                    </svg>
                </div>
                <span class="rk-kpi-delta" id="badge-kpi-total-konten"></span>
            </div>
            <div class="rk-kpi-label">Total Konten</div>
            <div class="rk-kpi-value" id="kpi-total-konten"><?php echo e(number_format($totalKonten)); ?></div>
        </div>


        
        <div class="rk-kpi-card">
            <div class="rk-kpi-top">
                <div class="rk-kpi-icon" style="background:#fce7f3; color:#db2777;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/>
                    </svg>
                </div>
                <span class="rk-kpi-delta" id="badge-kpi-total-engagement"></span>
            </div>
            <div class="rk-kpi-label">Total Engagement</div>
            <div class="rk-kpi-value" id="kpi-total-engagement"><?php echo e(number_format($totalEngagement)); ?></div>
        </div>

        
        <div class="rk-kpi-card">
            <div class="rk-kpi-top">
                <div class="rk-kpi-icon" style="background:#ffe4e6; color:#e11d48;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                    </svg>
                </div>
                <span class="rk-kpi-delta" id="badge-kpi-total-akun-followers"></span>
            </div>
            <div class="rk-kpi-label">Total Followers</div>
            <div class="rk-kpi-value" id="kpi-total-akun-followers">64.800</div>
        </div>

        
        <div class="rk-kpi-card">
            <div class="rk-kpi-top">
                <div class="rk-kpi-icon" style="background:#d1fae5; color:#059669;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4.9 19.1C1 15.2 1 8.8 4.9 4.9"/>
                        <path d="M7.8 16.2c-2.3-2.3-2.3-6.1 0-8.5"/>
                        <circle cx="12" cy="12" r="2"/>
                        <path d="M16.2 7.8c2.3 2.3 2.3 6.1 0 8.5"/>
                        <path d="M19.1 4.9C23 8.8 23 15.2 19.1 19.1"/>
                    </svg>
                </div>
                <span class="rk-kpi-delta" id="badge-kpi-total-reach"></span>
            </div>
            <div class="rk-kpi-label">Total Reach</div>
            <div class="rk-kpi-value" id="kpi-total-reach"><?php echo e(number_format($totalReach)); ?></div>
        </div>

        
        <div class="rk-kpi-card">
            <div class="rk-kpi-top">
                <div class="rk-kpi-icon" style="background:#f3e8ff; color:#9333ea;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/>
                        <polyline points="16 7 22 7 22 13"/>
                    </svg>
                </div>
                <span class="rk-kpi-delta" id="badge-kpi-engagement-rate"></span>
            </div>
            <div class="rk-kpi-label">Engagement Rate</div>
            <div class="rk-kpi-value" id="kpi-engagement-rate"><?php echo e(number_format($engagementRate, 2)); ?>%</div>
        </div>

        
        <div class="rk-kpi-card">
            <div class="rk-kpi-top">
                <div class="rk-kpi-icon" style="background:#dbeafe; color:#2563eb;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <line x1="19" y1="8" x2="19" y2="14"/>
                        <line x1="16" y1="11" x2="22" y2="11"/>
                    </svg>
                </div>
                <span class="rk-kpi-delta" id="badge-kpi-pertumbuhan-followers"></span>
            </div>
            <div class="rk-kpi-label">Pertumbuhan Followers</div>
            <div class="rk-kpi-value" id="kpi-pertumbuhan-followers">+<?php echo e(number_format((int)($totalFollowers * 0.012))); ?></div>
        </div>

    </div>
    
    
    <!-- GRAFIK TREN & RINGKASAN PLATFORM -->
    <div class="card" style="margin-bottom: 2rem; border: none; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); border-radius: 1rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
            <div class="card-title" style="margin: 0;">Grafik Tren Performa (Keseluruhan)</div>
        </div>
        
        <!-- Container untuk Grafik -->
        <div style="display: flex; flex-wrap: wrap; gap: 1.5rem; margin-bottom: 2rem;">
            <!-- Bar Chart -->
            <div style="flex: 1 1 60%; background: #fff; border: 1px solid #e2e8f0; border-radius: 0.5rem; padding: 1.5rem; min-width: 300px; display: flex; flex-direction: column; gap: 1.5rem;">
                
                <!-- Chart 1: Reach & Engagement -->
                <div>
                    <div style="display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 0.5rem; flex-wrap: wrap; gap: 0.5rem;">
                        <div>
                            <h3 style="font-size: 1rem; color: #0f172a; margin: 0 0 0.25rem 0; font-weight: 600;">Metrik Jangkauan (Besar)</h3>
                            <p id="barChartSubtitle" style="font-size: 0.78rem; color: #64748b; margin: 0;">Perbandingan semua platform</p>
                        </div>
                        <span id="chartSourceBadge" style="background: #eef2ff; color: #4f46e5; font-size: 0.72rem; font-weight: 600; padding: 0.25rem 0.6rem; border-radius: 999px;">Reach · Engagement</span>
                    </div>
                    <div style="height: 200px; position: relative;"><canvas id="barChartBig"></canvas></div>
                </div>

                <hr style="border: 0; border-top: 1px dashed #cbd5e1; margin: 0;">

                <!-- Chart 2: Interaksi Kecil -->
                <div>
                    <div style="display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 0.5rem; flex-wrap: wrap; gap: 0.5rem;">
                        <div>
                            <h3 style="font-size: 1rem; color: #0f172a; margin: 0 0 0.25rem 0; font-weight: 600;">Metrik Interaksi</h3>
                            <p id="barChartSubtitleSmall" style="font-size: 0.78rem; color: #64748b; margin: 0;">Likes, Comments, Shares, Save, Repost</p>
                        </div>
                        <span id="barChartBadge" style="background: #fdf4ff; color: #c026d3; font-size: 0.72rem; font-weight: 600; padding: 0.25rem 0.6rem; border-radius: 999px;">Likes · Comments · Shares · Save · Repost</span>
                    </div>
                    <div style="height: 200px; position: relative;"><canvas id="barChartSmall"></canvas></div>
                </div>
            </div>

            <!-- Pie Chart -->
            <div style="flex: 1 1 30%; background: #fff; border: 1px solid #e2e8f0; border-radius: 0.5rem; padding: 1.5rem; min-width: 250px;">
                <div style="margin-bottom: 1rem;">
                    <h3 style="font-size: 1rem; color: #0f172a; margin: 0 0 0.25rem 0; font-weight: 600;">Distribusi Konten</h3>
                    <p id="pieChartSubtitle" style="font-size: 0.78rem; color: #64748b; margin: 0;">Jumlah konten per platform</p>
                </div>
                <div style="height: 300px; position: relative;"><canvas id="pieChart"></canvas></div>
            </div>
        </div>

        <!-- Ringkasan 4 Platform Terpisah -->
        <div style="border-top: 1px solid #e2e8f0; padding-top: 1.5rem; margin-top: 1.5rem;">
            <h3 style="font-size: 1.1rem; color: #0f172a; margin-bottom: 1rem;">Ringkasan Per Platform</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                
                <?php $__currentLoopData = ['Instagram', 'TikTok', 'Facebook', 'YouTube']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if(isset($platformsData[$name])): ?>
                <?php
                    $pData = $platformsData[$name];
                    $pTotalEng = $pData['data']->sum('likes') + $pData['data']->sum('comments') + $pData['data']->sum('shares') + $pData['data']->where('sumber_tabel', 'youtube_shorts')->sum('repost');
                    $pTotalReach = $pData['data']->sum('reach');
                    $pTotalKonten = $pData['data']->count();
                    // Jumlah followers akun per platform (statis)
                    $staticFollowers = [
                        'Instagram' => 40308,
                        'TikTok'    => 2217,
                        'Facebook'  => 5375,
                        'YouTube'   => 16900,
                    ];
                    $pAkunFollowers = $staticFollowers[$name] ?? 0;
                ?>
                <div class="platform-card" data-platform="<?php echo e($name); ?>" style="border: 1px solid #e2e8f0; border-radius: 0.75rem; padding: 1.25rem; background: #fafafa; display: <?php echo e(($ringkasanPlatform == 'Semua Platform' || $ringkasanPlatform == $name) ? 'block' : 'none'); ?>;">
                    <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem;">
                        <div style="width: 36px; height: 36px; border-radius: 8px; display: flex; align-items: center; justify-content: center; background: <?php echo e($pData['soft']); ?>; color: <?php echo e($pData['color']); ?>;">
                            <?php if(strtolower($name) == 'instagram'): ?>
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>
                            <?php elseif(strtolower($name) == 'facebook'): ?>
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg>
                            <?php elseif(strtolower($name) == 'tiktok'): ?>
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 12a4 4 0 1 0 4 4V4a5 5 0 0 0 5 5"></path></svg>
                            <?php elseif(strtolower($name) == 'youtube'): ?>
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.25 29 29 0 0 0-.46-5.33z"></path><polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02"></polygon></svg>
                            <?php endif; ?>
                        </div>
                        <span style="font-weight: 700; color: #0f172a;"><?php echo e($name); ?></span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem; font-size: 0.85rem;">
                        <span style="color: #64748b;">Followers:</span>
                        <span style="font-weight: 600; color: #0f172a;"><?php echo e(number_format($pAkunFollowers)); ?></span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem; font-size: 0.85rem;">
                        <span style="color: #64748b;">Reach:</span>
                        <span style="font-weight: 600; color: #0f172a;"><?php echo e(number_format($pTotalReach)); ?></span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem; font-size: 0.85rem;">
                        <span style="color: #64748b;">Engagement:</span>
                        <span style="font-weight: 600; color: #0f172a;"><?php echo e(number_format($pTotalEng)); ?></span>
                    </div>
                    <div style="display: flex; justify-content: space-between; font-size: 0.85rem;">
                        <span style="color: #64748b;">Total Konten:</span>
                        <span style="font-weight: 600; color: #0f172a;"><?php echo e(number_format($pTotalKonten)); ?></span>
                    </div>
                </div>
                <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                
            </div>
        </div>
    </div>
    
    <div style="display: grid; grid-template-columns: 1fr; gap: 2rem; align-items: start;">
        
        <!-- RINGKASAN KONTEN TERBARU -->
        <div class="card" style="border: none; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); border-radius: 1rem;">
            <div class="card-title">Ringkasan Konten Terbaru</div>
            <div class="content-list" style="display: flex; flex-direction: column; gap: 1rem; margin-top: 1rem;">
                <?php $__empty_1 = true; $__currentLoopData = $metricsRingkasan->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $metric): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="content-item" style="display: flex; gap: 1rem; padding: 1rem; border: 1px solid #f1f5f9; border-radius: 0.75rem; align-items: center; background: #fff;">
                    <!-- Thumbnail/Icon -->
                    <?php
                        $platLower = strtolower($metric->platform);
                        $platBg = '#f1f5f9';
                        $platColor = '#475569';
                        if ($platLower == 'facebook') { $platBg = '#e3edfd'; $platColor = '#1877f2'; }
                        elseif ($platLower == 'instagram') { $platBg = '#fce7f0'; $platColor = '#e1306c'; }
                        elseif ($platLower == 'tiktok') { $platBg = '#f1f5f9'; $platColor = '#000000'; }
                        elseif ($platLower == 'youtube') { $platBg = '#fde2e2'; $platColor = '#ff0000'; }
                    ?>
                    <div style="width: 60px; height: 60px; background: <?php echo e($platBg); ?>; color: <?php echo e($platColor); ?>; border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <?php if($platLower == 'facebook'): ?>
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg>
                        <?php elseif($platLower == 'instagram'): ?>
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>
                        <?php elseif($platLower == 'tiktok'): ?>
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 12a4 4 0 1 0 4 4V4a5 5 0 0 0 5 5"></path></svg>
                        <?php elseif($platLower == 'youtube'): ?>
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.25 29 29 0 0 0-.46-5.33z"></path><polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02"></polygon></svg>
                        <?php else: ?>
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Info Utama -->
                    <div style="flex: 1; min-width: 0;">
                        <h4 style="margin: 0 0 0.25rem 0; font-size: 0.95rem; font-weight: 600; color: #0f172a; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                            <?php echo e($metric->judul_konten ?: 'Konten ' . ucfirst($metric->platform)); ?>

                        </h4>
                        <div style="display: flex; gap: 0.75rem; font-size: 0.8rem; color: #64748b; margin-bottom: 0.5rem; align-items: center;">
                            <span style="background: #e2e8f0; padding: 0.15rem 0.5rem; border-radius: 1rem; color: #475569; font-weight: 500;"><?php echo e($metric->kategori ?: 'Tanpa Kategori'); ?></span>
                            <span><i class="ph-bold ph-calendar-blank"></i> <?php echo e($metric->tgl_upload ? \Carbon\Carbon::parse($metric->tgl_upload)->format('d M Y') : '-'); ?></span>
                        </div>
                        
                        <!-- Metrik Details -->
                        <div style="display: flex; gap: 1.25rem; font-size: 0.85rem; color: #475569; font-weight: 500; align-items: center;">
                            <div style="display: flex; align-items: center; gap: 0.35rem;" title="Views">
                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                <span><?php echo e(number_format($metric->reach)); ?></span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 0.35rem;" title="Likes">
                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                                <span><?php echo e(number_format($metric->likes)); ?></span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 0.35rem;" title="Comments">
                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                                <span><?php echo e(number_format($metric->comments)); ?></span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 0.35rem;" title="Reposts / Shares">
                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="17 1 21 5 17 9"></polyline><path d="M3 11V9a4 4 0 0 1 4-4h14"></path><polyline points="7 23 3 19 7 15"></polyline><path d="M21 13v2a4 4 0 0 1-4 4H3"></path></svg>
                                <span><?php echo e(number_format($metric->shares)); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div style="text-align: center; color: #64748b; padding: 2rem;">Belum ada data konten.</div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- INSIGHT SINGKAT -->
        
        
    </div>
</div>

<?php if(auth()->check() && auth()->user()->role === 'user'): ?>
<div id="exportRingkasanModal" class="lap-modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; justify-content: center; align-items: center;">
    <div class="lap-modal-content" style="background: #fff; width: 400px; border-radius: 8px; padding: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <h2 style="margin-top:0; font-size: 1.2rem; font-weight: 600; margin-bottom: 15px;">Permintaan Export Dokumen</h2>
        <form action="<?php echo e(route('export-requests.store')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="type" id="exportRingkasanType" value="pdf">
            <input type="hidden" name="export_source" value="ringkasan">
            
            <input type="hidden" name="filters[ringkasan_platform]" id="modal_ringkasan_platform" value="">
            <input type="hidden" name="filters[ringkasan_periode]" id="modal_ringkasan_periode" value="">
            
            
            <input type="hidden" name="filters[chart_big]"   id="modal_chart_big"   value="">
            <input type="hidden" name="filters[chart_small]" id="modal_chart_small" value="">
            <input type="hidden" name="filters[chart_pie]"   id="modal_chart_pie"   value="">

            <div style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px; font-weight: 500; font-size: 0.9rem;">Jenis Dokumen</label>
                <input type="text" value="Ringkasan PDF" readonly style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; background: #f9f9f9;">
            </div>

            <div style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px; font-weight: 500; font-size: 0.9rem;">Alasan / Keperluan Export <span style="color:red;">*</span></label>
                <textarea name="reason" required rows="3" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;" placeholder="Contoh: Untuk laporan bulanan ke atasan..."></textarea>
            </div>

            <div style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px; font-weight: 500; font-size: 0.9rem;">Keterangan / Pihak Terkait</label>
                <input type="text" name="details" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;" placeholder="Opsional...">
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" onclick="closeExportRingkasanModal()" style="padding: 8px 16px; border: 1px solid #ccc; background: #fff; border-radius: 4px; cursor: pointer;">Batal</button>
                <button type="submit" style="padding: 8px 16px; border: none; background: #3b82f6; color: #fff; border-radius: 4px; cursor: pointer;">Kirim Permintaan</button>
            </div>
        </form>
    </div>
</div>

<script>
function openExportRingkasanModal() {
    // Ambil filter aktif
    document.getElementById('modal_ringkasan_platform').value = document.querySelector('select[name="ringkasan_platform"]').value;
    document.getElementById('modal_ringkasan_periode').value = document.querySelector('select[name="ringkasan_periode"]').value;

    // Ambil snapshot grafik sebagai Base64 PNG
    // Chart.js canvas.toDataURL() selalu menghasilkan 'data:image/png;base64,...'
    // sehingga aman — tidak ada risiko inject HTML/script
    try {
        const canvasBig   = document.getElementById('barChartBig');
        const canvasSmall = document.getElementById('barChartSmall');
        const canvasPie   = document.getElementById('pieChart');

        if (canvasBig)   document.getElementById('modal_chart_big').value   = canvasBig.toDataURL('image/png');
        if (canvasSmall) document.getElementById('modal_chart_small').value = canvasSmall.toDataURL('image/png');
        if (canvasPie)   document.getElementById('modal_chart_pie').value   = canvasPie.toDataURL('image/png');
    } catch (e) {
        // Jika gagal (misal tainted canvas), biarkan kosong — PDF tetap berjalan tanpa grafik
        console.warn('Gagal mengambil snapshot grafik:', e);
    }

    document.getElementById('exportRingkasanModal').style.display = 'flex';
}
function closeExportRingkasanModal() {
    document.getElementById('exportRingkasanModal').style.display = 'none';
}
</script>
<?php endif; ?>

<!-- Chart.js Library & Initialization -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
<script>
// Register Plugins
Chart.register(ChartDataLabels);

const noDataPlugin = {
    id: 'noData',
    afterDraw: (chart) => {
        let hasData = false;
        chart.data.datasets.forEach(dataset => {
            if (dataset.data.some(val => val > 0)) hasData = true;
        });

        if (!hasData) {
            const ctx = chart.ctx;
            const width = chart.width;
            const height = chart.height;
            chart.clear();
            ctx.save();
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            ctx.font = '500 14px "Inter", sans-serif';
            ctx.fillStyle = '#64748b';
            ctx.fillText('Belum ada data untuk ditampilkan', width / 2, height / 2);
            ctx.restore();
        }
    }
};
Chart.register(noDataPlugin);

// ============================================================
// CHART INSTANCES
// ============================================================
let barChartBigInstance   = null; // Reach & Engagement
let barChartSmallInstance = null; // Likes, Comments, Shares, Save, Repost
let pieChartInstance      = null;

const platformDataRaw = <?php echo json_encode($jsPlatformData, 15, 512) ?>;

// ── Warna platform ───────────────────────────────────────────
const jenisColorPalette = [
    '#e1306c','#1877f2','#ff0000','#10b981',
    '#f59e0b','#8b5cf6','#06b6d4','#f43f5e','#84cc16','#ec4899'
];
const platformColors = {
    'Instagram': '#e1306c', 'TikTok': '#010101',
    'Facebook': '#1877f2',  'YouTube': '#ff0000',
    'YouTube Video': '#ff0000', 'YouTube Shorts': '#cc0000', 'YouTube Live': '#ff6666'
};

// ── Formatter K / M / B ──────────────────────────────────────
function fmtKMB(value) {
    if (value === null || value === undefined) return '0';
    const abs = Math.abs(value);
    if (abs >= 1_000_000_000) return (value / 1_000_000_000).toFixed(1).replace(/\.0$/, '') + 'B';
    if (abs >= 1_000_000)     return (value / 1_000_000).toFixed(1).replace(/\.0$/, '') + 'M';
    if (abs >= 1_000)         return (value / 1_000).toFixed(1).replace(/\.0$/, '') + 'K';
    return value.toLocaleString('id-ID');
}

// Nilai asli dengan pemisah ribuan (untuk tooltip)
function fmtFull(value) {
    return Number(value || 0).toLocaleString('id-ID');
}

// ── Opsi sumbu Y dengan auto-scale + K/M/B ───────────────────
const yAxisKMB = {
    beginAtZero: true,
    ticks: {
        maxTicksLimit: 6,
        callback: (v) => fmtKMB(v)
    },
    grid: { color: 'rgba(0,0,0,0.05)' }
};

// ── Plugin tooltip umum (nilai asli) ─────────────────────────
const tooltipFull = {
    callbacks: {
        label: (ctx) => ` ${ctx.dataset.label || ctx.label}: ${fmtFull(ctx.raw)}`
    }
};

// ── Subtitle updater ─────────────────────────────────────────
function updateChartSubtitles(filter) {
    const barSub   = document.getElementById('barChartSubtitle');
    const barSub2  = document.getElementById('barChartSubtitleSmall');
    const pieSub   = document.getElementById('pieChartSubtitle');
    const badge    = document.getElementById('barChartBadge');
    if (!barSub) return;

    if (filter === 'Semua Platform') {
        barSub.textContent  = 'Semua platform — data all-time';
        barSub2.textContent = 'Likes · Comments · Shares · Save · Repost per platform';
        pieSub.textContent  = 'Distribusi jumlah konten per platform';
        badge.textContent   = 'Sumber data sama dengan grafik di atas';
    } else if (filter === 'YouTube') {
        barSub.textContent  = 'YouTube: Video · Shorts · Live';
        barSub2.textContent = 'Interaksi per jenis konten YouTube';
        pieSub.textContent  = 'Distribusi konten YouTube per jenis';
        badge.textContent   = 'Sumber data sama dengan grafik di atas';
    } else {
        barSub.textContent  = `${filter} — Reach & Engagement (all-time)`;
        barSub2.textContent = `${filter} — Likes, Comments, Shares, Save, Repost`;
        pieSub.textContent  = `Distribusi jenis konten ${filter}`;
        badge.textContent   = 'Sumber data sama dengan grafik di atas';
    }
}

// ============================================================
// DATA BUILDER — mendapatkan { bigData, smallData } untuk dua bar chart
// ============================================================
function getChartDatasets(filter, period) {

    // ── Helper: ambil nilai dari platformDataRaw ──────────────
    const fromRaw = (key, field) => (platformDataRaw[key] ? platformDataRaw[key][field] || 0 : 0);

    // ─────────────────────────────────────────────────────────
    // SEMUA PLATFORM
    // ─────────────────────────────────────────────────────────
    if (filter === 'Semua Platform') {
        const labels = ['Instagram', 'TikTok', 'Facebook', 'YouTube'];
        const bgColors = labels.map(l => (platformColors[l] || '#64748b') + 'bb');
        const bdColors = labels.map(l => platformColors[l] || '#64748b');

        return {
            labels,
            pieData: labels.map(l => fromRaw(l, 'konten')),
            pieColors: labels.map(l => platformColors[l] || '#64748b'),
            bigDatasets: [
                { label: 'Reach',      data: labels.map(l => fromRaw(l, 'reach')),
                  backgroundColor: bgColors, borderColor: bdColors, borderWidth: 1.5 },
                { label: 'Engagement', data: labels.map(l => fromRaw(l, 'engagement')),
                  backgroundColor: '#8b5cf6bb', borderColor: '#8b5cf6', borderWidth: 1.5 }
            ],
            smallDatasets: [
                { label: 'Likes',    data: labels.map(l => fromRaw(l, 'likes')),
                  backgroundColor: '#f43f5ebb', borderColor: '#f43f5e', borderWidth: 1.5 },
                { label: 'Comments', data: labels.map(l => fromRaw(l, 'comments')),
                  backgroundColor: '#3b82f6bb', borderColor: '#3b82f6', borderWidth: 1.5 },
                { label: 'Shares',   data: labels.map(l => fromRaw(l, 'shares')),
                  backgroundColor: '#10b981bb', borderColor: '#10b981', borderWidth: 1.5 },
                { label: 'Save',     data: labels.map(l => fromRaw(l, 'saves')),
                  backgroundColor: '#eab308bb', borderColor: '#eab308', borderWidth: 1.5 },
                { label: 'Repost',   data: labels.map(l => fromRaw(l, 'repost')),
                  backgroundColor: '#ec4899bb', borderColor: '#ec4899', borderWidth: 1.5 }
            ]
        };
    }

    // ─────────────────────────────────────────────────────────
    // YOUTUBE — per jenis (Video / Shorts / Live)
    // ─────────────────────────────────────────────────────────
    if (filter === 'YouTube') {
        const labels = ['Video', 'Shorts', 'Live'];
        const rawKeys = ['YouTube Video', 'YouTube Shorts', 'YouTube Live'];
        const ytBg = ['#ff0000bb', '#cc0000bb', '#ff6666bb'];
        const ytBd = ['#ff0000',   '#cc0000',   '#ff6666'];

        return {
            labels,
            pieData: rawKeys.map(k => fromRaw(k, 'konten')),
            pieColors: ytBd,
            bigDatasets: [
                { label: 'Jumlah Penayangan', data: rawKeys.map(k => fromRaw(k, 'reach')), backgroundColor: ytBg, borderColor: ytBd, borderWidth: 1.5 },
                { label: 'Engagement', data: rawKeys.map(k => fromRaw(k, 'engagement')), backgroundColor: '#8b5cf6bb', borderColor: '#8b5cf6', borderWidth: 1.5 }
            ],
            smallDatasets: [
                { label: 'Likes',    data: rawKeys.map(k => fromRaw(k, 'likes')),    backgroundColor: '#f43f5ebb', borderColor: '#f43f5e', borderWidth: 1.5 },
                { label: 'Comments', data: rawKeys.map(k => fromRaw(k, 'comments')), backgroundColor: '#3b82f6bb', borderColor: '#3b82f6', borderWidth: 1.5 },
                { label: 'Shares',   data: rawKeys.map(k => fromRaw(k, 'shares')),   backgroundColor: '#10b981bb', borderColor: '#10b981', borderWidth: 1.5 },
                { label: 'Save',     data: rawKeys.map(k => fromRaw(k, 'saves')),    backgroundColor: '#eab308bb', borderColor: '#eab308', borderWidth: 1.5 },
                { label: 'Repost',   data: rawKeys.map(k => fromRaw(k, 'repost')),   backgroundColor: '#ec4899bb', borderColor: '#ec4899', borderWidth: 1.5 }
            ]
        };
    }

    // ─────────────────────────────────────────────────────────
    // PLATFORM INDIVIDUAL (Instagram / TikTok / Facebook)
    // ─────────────────────────────────────────────────────────
    const pd  = platformDataRaw[filter] || {};
    const col = platformColors[filter]  || '#4f46e5';

    let pieLabels, pieVals, pieCols;
    const jenisRaw = pd.jenis_distribusi || {};
    const jenisEntries = Object.entries(jenisRaw);
    if (jenisEntries.length > 0) {
        pieLabels = jenisEntries.map(([k]) => k.charAt(0).toUpperCase() + k.slice(1));
        pieVals   = jenisEntries.map(([, v]) => v);
        pieCols   = pieLabels.map((_, i) => jenisColorPalette[i % jenisColorPalette.length]);
    } else {
        pieLabels = ['Likes', 'Comments', 'Shares', 'Save', 'Repost'];
        pieVals   = [pd.likes || 0, pd.comments || 0, pd.shares || 0, pd.saves || 0, pd.repost || 0];
        pieCols   = ['#f43f5e', '#3b82f6', '#10b981', '#eab308', '#ec4899'];
    }

    return {
        labels: ['Reach', 'Engagement'],
        pieData: pieVals,
        pieColors: pieCols,
        pieLabels,
        bigDatasets: [{
            label: filter,
            data: [pd.reach || 0, pd.engagement || 0],
            backgroundColor: ['#10b981bb', '#8b5cf6bb'],
            borderColor: ['#10b981', '#8b5cf6'],
            borderWidth: 1.5
        }],
        smallDatasets: [{
            label: filter,
            data: [pd.likes || 0, pd.comments || 0, pd.shares || 0, pd.saves || 0, pd.repost || 0],
            backgroundColor: ['#f43f5ebb', '#3b82f6bb', col + 'bb', '#eab308bb', '#ec4899bb'],
            borderColor: ['#f43f5e', '#3b82f6', col, '#eab308', '#ec4899'],
            borderWidth: 1.5
        }],
        smallLabels: ['Likes', 'Comments', 'Shares', 'Save', 'Repost']
    };
}

// ── Opsi chart bar umum ───────────────────────────────────────
function makeBarOptions() {
    return {
        responsive: true,
        maintainAspectRatio: false,
        animation: { duration: 400 },
        scales: {
            x: { grid: { display: false }, ticks: { font: { size: 11 } } },
            y: yAxisKMB
        },
        plugins: {
            legend: { position: 'bottom', labels: { boxWidth: 12, padding: 10, font: { size: 11 } } },
            datalabels: { display: false },
            tooltip: tooltipFull
        }
    };
}

// ── Render / update kedua bar chart dan pie chart ─────────────
function renderAllCharts(filter, period) {
    const d = getChartDatasets(filter, period);

    // --- Bar Big (Reach & Engagement) ---
    if (!barChartBigInstance) {
        barChartBigInstance = new Chart(document.getElementById('barChartBig').getContext('2d'),
            { type: 'bar', data: { labels: d.labels, datasets: d.bigDatasets }, options: makeBarOptions() });
    } else {
        barChartBigInstance.data.labels   = d.labels;
        barChartBigInstance.data.datasets = d.bigDatasets;
        barChartBigInstance.update();
    }

    // --- Bar Small (Likes, Comments, Shares, Save, Repost) ---
    const sLabels = d.smallLabels || d.labels;
    if (!barChartSmallInstance) {
        barChartSmallInstance = new Chart(document.getElementById('barChartSmall').getContext('2d'),
            { type: 'bar', data: { labels: sLabels, datasets: d.smallDatasets }, options: makeBarOptions() });
    } else {
        barChartSmallInstance.data.labels   = sLabels;
        barChartSmallInstance.data.datasets = d.smallDatasets;
        barChartSmallInstance.update();
    }

    // --- Pie Chart ---
    const pieLabelsFinal = d.pieLabels || d.labels;
    const pieOptions = {
        responsive: true,
        maintainAspectRatio: false,
        animation: { duration: 400 },
        plugins: {
            legend: { position: 'bottom', labels: { boxWidth: 12, padding: 8, font: { size: 11 } } },
            datalabels: {
                color: '#fff', font: { weight: 'bold', size: 11 },
                formatter: (value, ctx) => {
                    const total = ctx.chart._metasets[ctx.datasetIndex].total;
                    if (!total || value === 0) return null;
                    const pct = ((value / total) * 100).toFixed(1);
                    return pct < 3 ? null : pct + '%';
                }
            },
            tooltip: {
                callbacks: {
                    label: (ctx) => {
                        const val = ctx.raw || 0;
                        const total = ctx.chart._metasets[ctx.datasetIndex].total;
                        const pct = total > 0 ? ((val / total) * 100).toFixed(1) + '%' : '0%';
                        return ` ${ctx.label}: ${fmtFull(val)} (${pct})`;
                    }
                }
            }
        }
    };

    if (!pieChartInstance) {
        pieChartInstance = new Chart(document.getElementById('pieChart').getContext('2d'),
            { type: 'pie', data: { labels: pieLabelsFinal, datasets: [{ data: d.pieData, backgroundColor: d.pieColors, borderColor: '#fff', borderWidth: 2 }] }, options: pieOptions });
    } else {
        pieChartInstance.data.labels = pieLabelsFinal;
        pieChartInstance.data.datasets[0].data = d.pieData;
        pieChartInstance.data.datasets[0].backgroundColor = d.pieColors;
        pieChartInstance.update();
    }
}

// ── renderBadge ───────────────────────────────────────────────
function renderBadge(elementId, deltaValue) {
    const el = document.getElementById(elementId);
    if (!el) return;
    if (deltaValue === null || deltaValue === undefined) {
        el.className = 'rk-kpi-delta na';
        el.style.background = '#f1f5f9';
        el.style.color = '#64748b';
        el.innerHTML = 'N/A';
        return;
    }
    if (deltaValue === 'baru') {
        el.className = 'rk-kpi-delta flat';
        el.style.background = '#f1f5f9';
        el.style.color = '#64748b';
        el.innerHTML = 'Baru';
        return;
    }
    el.style = '';
    if (deltaValue > 0) {
        el.className = 'rk-kpi-delta up';
        el.innerHTML = `<i class="ph-bold ph-trend-up"></i> +${deltaValue.toFixed(2)}%`;
    } else if (deltaValue < 0) {
        el.className = 'rk-kpi-delta down';
        el.innerHTML = `<i class="ph-bold ph-trend-down"></i> ${deltaValue.toFixed(2)}%`;
    } else {
        el.className = 'rk-kpi-delta flat';
        el.style.background = '#f1f5f9';
        el.style.color = '#64748b';
        el.innerHTML = `0.00%`;
    }
}

// ── updateDashboardFilter ─────────────────────────────────────
function updateDashboardFilter() {
    const filter = document.getElementById('filterPlatform').value;
    const periodType = document.getElementById('ringkasanPeriode').value;
    const periodMonth = document.getElementById('ringkasanBulan').value;
    const periodYear = document.getElementById('ringkasanTahun').value;
    
    // Kita construct periode untuk getChartDatasets if it expects 'semua', 'bulanan', dsb
    // getChartDatasets sebenarnya tidak pakai param period secara khusus selain melempar ke argument.
    const period = periodType;


    const followerAccounts = {
        'Semua Platform': 64800, 'Instagram': 40308,
        'TikTok': 2217, 'YouTube': 16900, 'Facebook': 5375
    };

    const pd = platformDataRaw[filter];
    if (pd) {
        const _set = (id, val) => { const el = document.getElementById(id); if (el) el.innerText = val; };
        _set('kpi-total-konten',             pd.konten.toLocaleString('id-ID'));
        _set('kpi-total-followers',          pd.followers.toLocaleString('id-ID'));
        _set('kpi-total-engagement',         pd.engagement.toLocaleString('id-ID'));
        _set('kpi-total-reach',              pd.reach.toLocaleString('id-ID'));
        _set('kpi-engagement-rate',          pd.rate.toFixed(2) + '%');
        _set('kpi-pertumbuhan-followers',    '+' + pd.growth.toLocaleString('id-ID'));
    }

    const badgeDeltas = <?php echo json_encode($badgeDeltas, 15, 512) ?>;
    if (badgeDeltas) {
        Object.keys(badgeDeltas).forEach(kpiId => renderBadge('badge-' + kpiId, badgeDeltas[kpiId]));
    }

    const akunFollowers = followerAccounts[filter] ?? followerAccounts['Semua Platform'];
    document.getElementById('kpi-total-akun-followers').innerText = akunFollowers.toLocaleString('id-ID');

    updateChartSubtitles(filter);
    renderAllCharts(filter, period);
    document.querySelectorAll('.platform-card').forEach(card => {
        card.style.display =
            (filter === 'Semua Platform' || card.getAttribute('data-platform') === filter)
                ? 'block' : 'none';
    });
}

function toggleRingkasanFilters() {
    const type = document.getElementById('ringkasanPeriode').value;
    const b = document.getElementById('ringkasanBulan');
    const t = document.getElementById('ringkasanTahun');
    
    if (type === 'semua') {
        b.style.display = 'none';
        t.style.display = 'none';
    } else if (type === 'bulanan') {
        b.style.display = 'inline-block';
        t.style.display = 'inline-block';
    } else if (type === 'tahunan') {
        b.style.display = 'none';
        t.style.display = 'inline-block';
    }
}

document.addEventListener('DOMContentLoaded', updateDashboardFilter);
</script><?php /**PATH D:\magang\InsightHubBaru\frontend\resources\views/pages/Dashboard/ringkasan.blade.php ENDPATH**/ ?>
<div id="tab-analitik" class="dashboard-container">

    
    <form method="GET" action="<?php echo e(route('dashboard')); ?>" id="analitikFilterForm">
        
        <input type="hidden" name="tab" value="analitik">

        <div class="page-header" style="flex-wrap: wrap; gap: 1.5rem; align-items: flex-start;">
            <div>
                <h1>Analitik Konten</h1>
                <p>Analisis performa konten berdasarkan platform dan periode yang dipilih.</p>
            </div>

            
            <div style="display: flex; flex-wrap: wrap; gap: 1rem; align-items: flex-end; flex: 1; justify-content: flex-end;">

                
                <div style="display: flex; flex-direction: column; gap: 0.35rem;">
                    <label style="font-size: 0.78rem; font-weight: 600; color: #475569; text-transform: uppercase; letter-spacing: 0.05em;">Platform</label>
                    <select name="analitik_platform" class="filter-select dark" onchange="togglePeriodeType()" style="min-width: 160px;">
                        <option value="all"      <?php echo e(($analitikFilters['platform'] ?? 'all') === 'all'       ? 'selected' : ''); ?>>Semua Platform</option>
                        <option value="instagram" <?php echo e(($analitikFilters['platform'] ?? '') === 'instagram'   ? 'selected' : ''); ?>>Instagram</option>
                        <option value="tiktok"    <?php echo e(($analitikFilters['platform'] ?? '') === 'tiktok'      ? 'selected' : ''); ?>>TikTok</option>
                        <option value="facebook"  <?php echo e(($analitikFilters['platform'] ?? '') === 'facebook'    ? 'selected' : ''); ?>>Facebook</option>
                        <option value="youtube"   <?php echo e(($analitikFilters['platform'] ?? '') === 'youtube'     ? 'selected' : ''); ?>>YouTube</option>
                    </select>
                </div>

                
                <div style="display: flex; flex-direction: column; gap: 0.35rem;">
                    <label style="font-size: 0.78rem; font-weight: 600; color: #475569; text-transform: uppercase; letter-spacing: 0.05em;">Jenis Periode</label>
                    <select name="analitik_periode_type" id="analitikPeriodeType" class="filter-select" onchange="showPeriodeInput(this.value)" style="min-width: 160px;">
                        <option value=""       <?php echo e(($analitikFilters['periode_type'] ?? '') === ''      ? 'selected' : ''); ?>>Semua Waktu</option>
                        <option value="range"  <?php echo e(($analitikFilters['periode_type'] ?? '') === 'range' ? 'selected' : ''); ?>>Rentang Tanggal</option>
                        <option value="bulan"  <?php echo e(($analitikFilters['periode_type'] ?? '') === 'bulan' ? 'selected' : ''); ?>>Bulan & Tahun</option>
                        <option value="tahun"  <?php echo e(($analitikFilters['periode_type'] ?? '') === 'tahun' ? 'selected' : ''); ?>>Tahun</option>
                    </select>
                </div>

                
                <div id="input-range" style="display: none; gap: 0.5rem; align-items: flex-end; flex-wrap: wrap;">
                    <div style="display: flex; flex-direction: column; gap: 0.35rem;">
                        <label style="font-size: 0.78rem; font-weight: 600; color: #475569; text-transform: uppercase; letter-spacing: 0.05em;">Dari</label>
                        <input type="date" name="analitik_date_start" class="filter-select" value="<?php echo e($analitikFilters['date_start'] ?? ''); ?>">
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 0.35rem;">
                        <label style="font-size: 0.78rem; font-weight: 600; color: #475569; text-transform: uppercase; letter-spacing: 0.05em;">Sampai</label>
                        <input type="date" name="analitik_date_end" class="filter-select" value="<?php echo e($analitikFilters['date_end'] ?? ''); ?>">
                    </div>
                </div>

                
                <div id="input-bulan" style="display: none; gap: 0.5rem; align-items: flex-end; flex-wrap: wrap;">
                    <div style="display: flex; flex-direction: column; gap: 0.35rem;">
                        <label style="font-size: 0.78rem; font-weight: 600; color: #475569; text-transform: uppercase; letter-spacing: 0.05em;">Bulan</label>
                        <select name="analitik_bulan" class="filter-select">
                            <?php $__currentLoopData = ['01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April','05'=>'Mei','06'=>'Juni','07'=>'Juli','08'=>'Agustus','09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $num => $nama): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($num); ?>" <?php echo e(($analitikFilters['bulan'] ?? '') === $num ? 'selected' : ''); ?>><?php echo e($nama); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 0.35rem;">
                        <label style="font-size: 0.78rem; font-weight: 600; color: #475569; text-transform: uppercase; letter-spacing: 0.05em;">Tahun</label>
                        <select name="analitik_tahun_bulan" class="filter-select">
                            <?php for($y = date('Y'); $y >= 2020; $y--): ?>
                            <option value="<?php echo e($y); ?>" <?php echo e(($analitikFilters['tahun_bulan'] ?? date('Y')) == $y ? 'selected' : ''); ?>><?php echo e($y); ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>

                
                <div id="input-tahun" style="display: none;">
                    <div style="display: flex; flex-direction: column; gap: 0.35rem;">
                        <label style="font-size: 0.78rem; font-weight: 600; color: #475569; text-transform: uppercase; letter-spacing: 0.05em;">Tahun</label>
                        <select name="analitik_tahun" class="filter-select">
                            <?php for($y = date('Y'); $y >= 2020; $y--): ?>
                            <option value="<?php echo e($y); ?>" <?php echo e(($analitikFilters['tahun'] ?? date('Y')) == $y ? 'selected' : ''); ?>><?php echo e($y); ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>

                
                <button type="submit" class="btn btn-primary" style="height: 38px; display: flex; align-items: center; gap: 0.5rem; white-space: nowrap;">
                    <i class="ph-bold ph-funnel"></i> Terapkan Filter
                </button>

                
                <a href="<?php echo e(route('dashboard')); ?>" class="btn" style="height: 38px; display: flex; align-items: center; gap: 0.5rem; white-space: nowrap;">
                    <i class="ph-bold ph-arrow-counter-clockwise"></i> Reset
                </a>
            </div>
        </div>
    </form>

    
    <?php
        $ma = $metricsAnalitik; // alias pendek

        // --- KPI ---
        $aTotal       = $ma->count();
        $aTotalReach  = $ma->sum('reach');
        $aTotalLike   = $ma->sum('likes');
        $aTotalComment= $ma->sum('comments');
        $aTotalShare  = $ma->sum('shares');
        $aTotalEng    = $aTotalLike + $aTotalComment + $aTotalShare;
        $aEngRate     = $aTotalReach > 0 ? ($aTotalEng / $aTotalReach) * 100 : 0;
        $aAvgEng      = $aTotal > 0 ? $aTotalEng / $aTotal : 0;
        $aAvgReach    = $aTotal > 0 ? $aTotalReach / $aTotal : 0;

        // --- Analisis Platform ---
        $platformColors = [
            'instagram' => '#e1306c',
            'tiktok'    => '#010101',
            'facebook'  => '#1877f2',
            'youtube'   => '#ff0000',
            'yt-live'   => '#ff0000',
            'yt-video'  => '#ff0000',
            'yt-shorts' => '#ff0000',
        ];

        $platformStats = [];
        foreach ($ma->groupBy(fn($m) => strtolower($m->platform)) as $plat => $items) {
            $pEng = $items->sum('likes') + $items->sum('comments') + $items->sum('shares');
            $pReach = $items->sum('reach');
            $platformStats[$plat] = [
                'count'   => $items->count(),
                'reach'   => $pReach,
                'eng'     => $pEng,
                'rate'    => $pReach > 0 ? round(($pEng / $pReach) * 100, 2) : 0,
                'color'   => $platformColors[$plat] ?? '#64748b',
            ];
        }
        $maxPlatformReach = collect($platformStats)->max('reach') ?: 1;

        // --- Analisis Kategori ---
        $categoryStats = [];
        foreach ($ma->groupBy('kategori') as $cat => $items) {
            $cEng = $items->sum('likes') + $items->sum('comments') + $items->sum('shares');
            $cReach = $items->sum('reach');
            $categoryStats[$cat ?: 'Tanpa Kategori'] = [
                'count' => $items->count(),
                'reach' => $cReach,
                'eng'   => $cEng,
                'rate'  => $cReach > 0 ? round(($cEng / $cReach) * 100, 2) : 0,
            ];
        }
        arsort($categoryStats); // sort by first key (count)
        $maxCatReach = collect($categoryStats)->max('reach') ?: 1;

        // --- Analisis Jenis Konten ---
        $contentTypeStats = [];
        foreach ($ma->groupBy('jenis') as $ct => $items) {
            $ctEng = $items->sum('likes') + $items->sum('comments') + $items->sum('shares');
            $ctReach = $items->sum('reach');
            $contentTypeStats[$ct ?: 'Lainnya'] = [
                'count' => $items->count(),
                'reach' => $ctReach,
                'eng'   => $ctEng,
                'rate'  => $ctReach > 0 ? round(($ctEng / $ctReach) * 100, 2) : 0,
            ];
        }
        $maxCtReach = collect($contentTypeStats)->max('reach') ?: 1;

        // --- Top 10 & Bottom 10 by Reach ---
        $top10    = $ma->sortByDesc('reach')->take(10);
        $bottom10 = $ma->sortBy('reach')->filter(fn($m) => $m->reach > 0)->take(10);

        // --- Insight ---
        $topPlatform = collect($platformStats)->sortByDesc('rate')->keys()->first();
        $topCategory = collect($categoryStats)->sortByDesc('rate')->keys()->first();
        $topCtType   = collect($contentTypeStats)->sortByDesc('rate')->keys()->first();
        $topReachContent = $ma->sortByDesc('reach')->first();

        // Label filter aktif
        $activeLabel = ($analitikFilters['platform'] === 'all' ? 'Semua Platform' : ucfirst($analitikFilters['platform']));
        $activePeriode = 'Semua Waktu';
        if ($analitikFilters['periode_type'] === 'range' && $analitikFilters['date_start']) {
            $activePeriode = $analitikFilters['date_start'] . ' s/d ' . $analitikFilters['date_end'];
        } elseif ($analitikFilters['periode_type'] === 'bulan' && $analitikFilters['bulan']) {
            $bulanNames = ['01'=>'Jan','02'=>'Feb','03'=>'Mar','04'=>'Apr','05'=>'Mei','06'=>'Jun','07'=>'Jul','08'=>'Agu','09'=>'Sep','10'=>'Okt','11'=>'Nov','12'=>'Des'];
            $activePeriode = ($bulanNames[$analitikFilters['bulan']] ?? $analitikFilters['bulan']) . ' ' . $analitikFilters['tahun_bulan'];
        } elseif ($analitikFilters['periode_type'] === 'tahun' && $analitikFilters['tahun']) {
            $activePeriode = 'Tahun ' . $analitikFilters['tahun'];
        }
    ?>

    
    <div style="margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap;">
        <span style="font-size: 0.85rem; color: #64748b;">Menampilkan data untuk:</span>
        <span style="background: #0f172a; color: #fff; padding: 0.3rem 0.75rem; border-radius: 2rem; font-size: 0.8rem; font-weight: 600; display: flex; align-items: center; gap: 0.35rem;">
            <i class="ph-bold ph-device-mobile"></i> <?php echo e($activeLabel); ?>

        </span>
        <span style="background: #e0e7ff; color: #4f46e5; padding: 0.3rem 0.75rem; border-radius: 2rem; font-size: 0.8rem; font-weight: 600; display: flex; align-items: center; gap: 0.35rem;">
            <i class="ph-bold ph-calendar"></i> <?php echo e($activePeriode); ?>

        </span>
        <span style="background: #dcfce7; color: #166534; padding: 0.3rem 0.75rem; border-radius: 2rem; font-size: 0.8rem; font-weight: 600; display: flex; align-items: center; gap: 0.35rem;">
            <i class="ph-bold ph-stack"></i> <?php echo e($aTotal); ?> konten
        </span>
    </div>

    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 1.25rem; margin-bottom: 2rem;">

        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-title" style="margin:0; font-size: 0.8rem; color: #475569;">TOTAL REACH</div>
                <div class="stat-icon" style="background:#d1fae5; color:#059669; width:36px; height:36px;"><i class="ph-fill ph-broadcast"></i></div>
            </div>
            <div class="stat-value" style="font-size: 1.5rem;"><?php echo e(number_format($aTotalReach)); ?></div>
            <div style="font-size: 0.75rem; color: #64748b; margin-top: 0.25rem;">Total Views / Reach</div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-title" style="margin:0; font-size: 0.8rem; color: #475569;">TOTAL ENGAGEMENT</div>
                <div class="stat-icon" style="background:#fce7f3; color:#db2777; width:36px; height:36px;"><i class="ph-fill ph-heart"></i></div>
            </div>
            <div class="stat-value" style="font-size: 1.5rem;"><?php echo e(number_format($aTotalEng)); ?></div>
            <div style="font-size: 0.75rem; color: #64748b; margin-top: 0.25rem;">Like + Comment + Share</div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-title" style="margin:0; font-size: 0.8rem; color: #475569;">ENGAGEMENT RATE</div>
                <div class="stat-icon" style="background:#f3e8ff; color:#9333ea; width:36px; height:36px;"><i class="ph-fill ph-chart-line-up"></i></div>
            </div>
            <div class="stat-value" style="font-size: 1.5rem;"><?php echo e(number_format($aEngRate, 2)); ?>%</div>
            <div style="font-size: 0.75rem; color: #64748b; margin-top: 0.25rem;">Eng / Reach × 100%</div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-title" style="margin:0; font-size: 0.8rem; color: #475569;">AVG ENGAGEMENT</div>
                <div class="stat-icon" style="background:#eef2ff; color:#4f46e5; width:36px; height:36px;"><i class="ph-fill ph-trend-up"></i></div>
            </div>
            <div class="stat-value" style="font-size: 1.5rem;"><?php echo e(number_format($aAvgEng, 0)); ?></div>
            <div style="font-size: 0.75rem; color: #64748b; margin-top: 0.25rem;">Per Konten</div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-title" style="margin:0; font-size: 0.8rem; color: #475569;">AVG REACH</div>
                <div class="stat-icon" style="background:#f1f5f9; color:#475569; width:36px; height:36px;"><i class="ph-fill ph-eye"></i></div>
            </div>
            <div class="stat-value" style="font-size: 1.5rem;"><?php echo e(number_format($aAvgReach, 0)); ?></div>
            <div style="font-size: 0.75rem; color: #64748b; margin-top: 0.25rem;">Per Konten</div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-title" style="margin:0; font-size: 0.8rem; color: #475569;">TOTAL KONTEN</div>
                <div class="stat-icon" style="background:#fef3c7; color:#d97706; width:36px; height:36px;"><i class="ph-fill ph-stack"></i></div>
            </div>
            <div class="stat-value" style="font-size: 1.5rem;"><?php echo e(number_format($aTotal)); ?></div>
            <div style="font-size: 0.75rem; color: #64748b; margin-top: 0.25rem;">Konten Dianalisis</div>
        </div>
    </div>

    
    <div class="grid-2-1" style="margin-bottom: 2rem;">

        
        <div class="card">
            <div class="card-title">Analisis Platform
                <span style="font-size: 0.75rem; font-weight: normal; background: #f1f5f9; padding: 4px 8px; border-radius: 4px;">Berdasarkan Reach</span>
            </div>
            <?php if(count($platformStats) > 0): ?>
            <div style="display: flex; flex-direction: column; gap: 1rem; margin-top: 0.5rem;">
                <?php $__currentLoopData = $platformStats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $platName => $pStat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.35rem; font-size: 0.875rem;">
                        <span style="display: flex; align-items: center; gap: 0.5rem;">
                            <i class="ph-fill ph-<?php echo e($platName); ?>-logo" style="color: <?php echo e($pStat['color']); ?>;"></i>
                            <?php echo e(ucfirst($platName)); ?>

                            <span style="font-size: 0.75rem; color: #64748b;">(<?php echo e($pStat['count']); ?> konten)</span>
                        </span>
                        <span style="font-weight: 600; color: #0f172a;">
                            <?php echo e(number_format($pStat['reach'])); ?> reach
                            <span style="color: #9333ea; font-size: 0.8rem; font-weight: 500;">&nbsp;<?php echo e($pStat['rate']); ?>% ER</span>
                        </span>
                    </div>
                    <div style="width: 100%; height: 8px; background: #e2e8f0; border-radius: 4px;">
                        <div style="width: <?php echo e($maxPlatformReach > 0 ? round(($pStat['reach'] / $maxPlatformReach) * 100, 1) : 0); ?>%; height: 100%; background: <?php echo e($pStat['color']); ?>; border-radius: 4px;"></div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <?php else: ?>
            <div style="text-align: center; padding: 2rem; color: #64748b;"><i class="ph ph-empty" style="font-size: 2rem;"></i><br>Tidak ada data untuk filter ini.</div>
            <?php endif; ?>
        </div>

        
        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
            <div class="card">
                <div class="card-title" style="margin-bottom: 1rem;">Jenis Konten</div>
                <?php if(count($contentTypeStats) > 0): ?>
                <div style="display: flex; flex-direction: column; gap: 0.85rem;">
                    <?php $__currentLoopData = $contentTypeStats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ctName => $ctStat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.25rem; font-size: 0.82rem;">
                            <span><?php echo e(ucfirst($ctName ?: 'Lainnya')); ?> <span style="color:#64748b;">(<?php echo e($ctStat['count']); ?>)</span></span>
                            <b><?php echo e(number_format($ctStat['rate'], 1)); ?>% ER</b>
                        </div>
                        <div style="width: 100%; height: 6px; background: #e2e8f0; border-radius: 4px;">
                            <div style="width: <?php echo e($maxCtReach > 0 ? round(($ctStat['reach'] / $maxCtReach) * 100, 1) : 0); ?>%; height: 100%; background: #4f46e5; border-radius: 4px;"></div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <?php else: ?>
                <div style="text-align: center; padding: 1.5rem; color: #64748b; font-size: 0.875rem;">Tidak ada data.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    
    <div class="card" style="margin-bottom: 2rem;">
        <div class="card-title">Analisis Kategori Konten
            <span style="font-size: 0.75rem; font-weight: normal; background: #f1f5f9; padding: 4px 8px; border-radius: 4px;"><?php echo e($activeLabel); ?> &bull; <?php echo e($activePeriode); ?></span>
        </div>
        <?php if(count($categoryStats) > 0): ?>
        <div style="overflow-x: auto;">
            <table class="data-table" style="margin-top: 0;">
                <thead>
                    <tr>
                        <th>Kategori</th>
                        <th style="text-align: center;">Jumlah Konten</th>
                        <th style="text-align: right;">Total Reach</th>
                        <th style="text-align: right;">Total Engagement</th>
                        <th style="text-align: right;">Engagement Rate</th>
                        <th>Performa Reach</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = collect($categoryStats)->sortByDesc('reach'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $catName => $cStat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td>
                            <span style="background: #e2e8f0; padding: 0.2rem 0.6rem; border-radius: 1rem; font-size: 0.8rem; font-weight: 500;">
                                <?php echo e($catName); ?>

                            </span>
                        </td>
                        <td style="text-align: center; font-weight: 600;"><?php echo e($cStat['count']); ?></td>
                        <td style="text-align: right; font-weight: 600;"><?php echo e(number_format($cStat['reach'])); ?></td>
                        <td style="text-align: right; font-weight: 600;"><?php echo e(number_format($cStat['eng'])); ?></td>
                        <td style="text-align: right;">
                            <span class="<?php echo e($cStat['rate'] >= 3 ? 'trend-up' : ($cStat['rate'] >= 1 ? '' : 'trend-down')); ?>" style="font-weight: 600;">
                                <?php echo e($cStat['rate']); ?>%
                            </span>
                        </td>
                        <td style="min-width: 120px;">
                            <div style="width: 100%; height: 6px; background: #e2e8f0; border-radius: 4px;">
                                <div style="width: <?php echo e($maxCatReach > 0 ? round(($cStat['reach'] / $maxCatReach) * 100, 1) : 0); ?>%; height: 100%; background: #10b981; border-radius: 4px;"></div>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div style="text-align: center; padding: 2rem; color: #64748b;"><i class="ph ph-empty" style="font-size: 2rem; display: block; margin-bottom: 0.5rem;"></i>Tidak ada data kategori untuk filter ini.</div>
        <?php endif; ?>
    </div>

    
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">

        
        <div class="card" style="padding: 0;">
            <div class="card-title" style="padding: 1.25rem 1.5rem; margin-bottom: 0; border-bottom: 1px solid var(--border); display: flex; align-items: center; gap: 0.5rem;">
                <i class="ph-bold ph-trophy" style="color: #f59e0b;"></i> Top 10 Konten
                <span style="font-size: 0.75rem; font-weight: normal; color: #64748b; margin-left: auto;">berdasarkan Reach</span>
            </div>
            <div style="overflow-x: auto;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Konten</th>
                            <th>Platform</th>
                            <th style="text-align: right;">Reach</th>
                            <th style="text-align: right;">Eng. Rate</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $top10; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $metric): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            $mEng = $metric->likes + $metric->comments + $metric->shares;
                            $mRate = $metric->reach > 0 ? round(($mEng / $metric->reach) * 100, 2) : 0;
                        ?>
                        <tr>
                            <td style="font-weight: 700; color: <?php echo e($i < 3 ? '#f59e0b' : '#64748b'); ?>; font-size: 0.9rem;"><?php echo e($i + 1); ?></td>
                            <td>
                                <div style="font-size: 0.85rem; font-weight: 600; color: #0f172a; max-width: 160px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                    <?php echo e($metric->judul_konten ?: 'Konten ' . ucfirst($metric->platform)); ?>

                                </div>
                                <div style="font-size: 0.75rem; color: #94a3b8;">
                                    <?php echo e($metric->tgl_upload ? \Carbon\Carbon::parse($metric->tgl_upload)->format('d M Y') : '-'); ?>

                                </div>
                            </td>
                            <td style="font-size: 0.8rem;"><?php echo e(ucfirst($metric->platform)); ?></td>
                            <td style="text-align: right; font-weight: 600; font-size: 0.875rem;"><?php echo e(number_format($metric->reach)); ?></td>
                            <td style="text-align: right;">
                                <span class="<?php echo e($mRate >= 3 ? 'trend-up' : ''); ?>" style="font-size: 0.8rem; font-weight: 600;"><?php echo e($mRate); ?>%</span>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="5" style="text-align: center; color: #64748b; padding: 2rem;">Tidak ada data.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        
        <div class="card" style="padding: 0;">
            <div class="card-title" style="padding: 1.25rem 1.5rem; margin-bottom: 0; border-bottom: 1px solid var(--border); display: flex; align-items: center; gap: 0.5rem;">
                <i class="ph-bold ph-arrow-down" style="color: #ef4444;"></i> Bottom 10 Konten
                <span style="font-size: 0.75rem; font-weight: normal; color: #64748b; margin-left: auto;">Reach terendah</span>
            </div>
            <div style="overflow-x: auto;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Konten</th>
                            <th>Platform</th>
                            <th style="text-align: right;">Reach</th>
                            <th style="text-align: right;">Eng. Rate</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $bottom10; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $metric): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            $mEng = $metric->likes + $metric->comments + $metric->shares;
                            $mRate = $metric->reach > 0 ? round(($mEng / $metric->reach) * 100, 2) : 0;
                        ?>
                        <tr>
                            <td style="font-weight: 600; color: #94a3b8; font-size: 0.9rem;"><?php echo e($i + 1); ?></td>
                            <td>
                                <div style="font-size: 0.85rem; font-weight: 600; color: #0f172a; max-width: 160px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                    <?php echo e($metric->judul_konten ?: 'Konten ' . ucfirst($metric->platform)); ?>

                                </div>
                                <div style="font-size: 0.75rem; color: #94a3b8;">
                                    <?php echo e($metric->tgl_upload ? \Carbon\Carbon::parse($metric->tgl_upload)->format('d M Y') : '-'); ?>

                                </div>
                            </td>
                            <td style="font-size: 0.8rem;"><?php echo e(ucfirst($metric->platform)); ?></td>
                            <td style="text-align: right; font-weight: 600; font-size: 0.875rem; color: #ef4444;"><?php echo e(number_format($metric->reach)); ?></td>
                            <td style="text-align: right;">
                                <span style="font-size: 0.8rem; font-weight: 600; color: #ef4444;"><?php echo e($mRate); ?>%</span>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="5" style="text-align: center; color: #64748b; padding: 2rem;">Tidak ada data.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    
    <!-- <div style="background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 100%); border-radius: 1rem; padding: 1.75rem; color: #fff; box-shadow: 0 10px 25px -5px rgba(15,23,42,0.35); margin-bottom: 2rem;">
        <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 1px solid rgba(255,255,255,0.1);">
            <i class="ph-fill ph-lightbulb" style="font-size: 1.5rem; color: #fbbf24;"></i>
            <h3 style="margin: 0; font-size: 1.15rem; font-weight: 600;">Insight Analitik</h3>
            <span style="margin-left: auto; font-size: 0.8rem; color: #94a3b8; background: rgba(255,255,255,0.08); padding: 0.25rem 0.75rem; border-radius: 1rem;">
                <?php echo e($activeLabel); ?> &bull; <?php echo e($activePeriode); ?>

            </span>
        </div>

        <?php if($aTotal === 0): ?>
        <div style="text-align: center; padding: 1rem; color: #94a3b8;">
            <i class="ph ph-info" style="font-size: 2rem; display: block; margin-bottom: 0.5rem;"></i>
            Tidak ada data untuk filter yang dipilih. Coba ubah filter platform atau periode.
        </div>
        <?php else: ?>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.25rem;">

            <div style="background: rgba(255,255,255,0.05); border-radius: 0.75rem; padding: 1.25rem; border: 1px solid rgba(255,255,255,0.08);">
                <div style="font-size: 0.8rem; color: #94a3b8; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.35rem;">
                    <i class="ph-bold ph-star"></i> Platform Terbaik
                </div>
                <div style="font-size: 1rem; font-weight: 600;">
                    <?php if($topPlatform): ?>
                        <span style="color: #60a5fa;"><?php echo e(ucfirst($topPlatform)); ?></span> memiliki Engagement Rate tertinggi
                        <span style="color: #34d399;">(<?php echo e($platformStats[$topPlatform]['rate'] ?? 0); ?>%)</span>
                        pada periode ini.
                    <?php else: ?>
                        Data belum mencukupi.
                    <?php endif; ?>
                </div>
            </div>

            <div style="background: rgba(255,255,255,0.05); border-radius: 0.75rem; padding: 1.25rem; border: 1px solid rgba(255,255,255,0.08);">
                <div style="font-size: 0.8rem; color: #94a3b8; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.35rem;">
                    <i class="ph-bold ph-fire"></i> Kategori Terperforma
                </div>
                <div style="font-size: 1rem; font-weight: 600;">
                    <?php if($topCategory): ?>
                        Konten kategori <span style="color: #f472b6;"><?php echo e($topCategory); ?></span> menghasilkan
                        Engagement Rate tertinggi
                        <span style="color: #34d399;">(<?php echo e($categoryStats[$topCategory]['rate'] ?? 0); ?>%)</span>.
                    <?php else: ?>
                        Data kategori belum tersedia.
                    <?php endif; ?>
                </div>
            </div>

            <div style="background: rgba(255,255,255,0.05); border-radius: 0.75rem; padding: 1.25rem; border: 1px solid rgba(255,255,255,0.08);">
                <div style="font-size: 0.8rem; color: #94a3b8; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.35rem;">
                    <i class="ph-bold ph-video"></i> Jenis Konten Terbaik
                </div>
                <div style="font-size: 1rem; font-weight: 600;">
                    <?php if($topCtType): ?>
                        Format <span style="color: #c084fc;"><?php echo e(ucfirst($topCtType)); ?></span> menghasilkan
                        Engagement Rate tertinggi
                        <span style="color: #34d399;">(<?php echo e($contentTypeStats[$topCtType]['rate'] ?? 0); ?>%)</span>.
                    <?php else: ?>
                        Data jenis konten belum tersedia.
                    <?php endif; ?>
                </div>
            </div>

            <div style="background: rgba(255,255,255,0.05); border-radius: 0.75rem; padding: 1.25rem; border: 1px solid rgba(255,255,255,0.08);">
                <div style="font-size: 0.8rem; color: #94a3b8; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.35rem;">
                    <i class="ph-bold ph-crown"></i> Konten Reach Tertinggi
                </div>
                <div style="font-size: 1rem; font-weight: 600; line-height: 1.5;">
                    <?php if($topReachContent): ?>
                        &ldquo;<?php echo e(\Illuminate\Support\Str::limit($topReachContent->judul_konten ?: 'Konten ' . ucfirst($topReachContent->platform), 35)); ?>&rdquo;
                        meraih <span style="color: #34d399;"><?php echo e(number_format($topReachContent->reach)); ?> views</span>.
                    <?php else: ?>
                        Belum ada konten terpublikasi.
                    <?php endif; ?>
                </div>
            </div>

            <div style="background: rgba(255,255,255,0.05); border-radius: 0.75rem; padding: 1.25rem; border: 1px solid rgba(255,255,255,0.08);">
                <div style="font-size: 0.8rem; color: #94a3b8; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.35rem;">
                    <i class="ph-bold ph-chart-bar"></i> Ringkasan Performa
                </div>
                <div style="font-size: 0.95rem; font-weight: 500; line-height: 1.7;">
                    Dari <span style="color: #fbbf24;"><?php echo e($aTotal); ?> konten</span>, rata-rata menghasilkan
                    <span style="color: #34d399;"><?php echo e(number_format($aAvgReach, 0)); ?> reach</span> dan
                    <span style="color: #f472b6;"><?php echo e(number_format($aAvgEng, 0)); ?> engagement</span> per konten,
                    dengan Engagement Rate keseluruhan <span style="color: #60a5fa;"><?php echo e(number_format($aEngRate, 2)); ?>%</span>.
                </div>
            </div>

        </div> -->
        <?php endif; ?>
    </div>

</div>


<script>
    function showPeriodeInput(type) {
        ['range', 'bulan', 'tahun'].forEach(function(t) {
            var el = document.getElementById('input-' + t);
            if (el) el.style.display = (t === type) ? 'flex' : 'none';
        });
    }

    // On page load, restore the correct inputs
    (function() {
        var selected = document.getElementById('analitikPeriodeType');
        if (selected) showPeriodeInput(selected.value);
    })();
</script><?php /**PATH D:\Project-Magang\InsightHub\frontend\resources\views/pages/Dashboard/analitik.blade.php ENDPATH**/ ?>
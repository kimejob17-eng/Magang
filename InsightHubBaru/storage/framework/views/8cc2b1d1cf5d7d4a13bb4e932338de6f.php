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

        @media (max-width: 1400px){ #tab-ringkasan .rk-kpi-grid{ grid-template-columns:repeat(3, 1fr); } }
        @media (max-width: 720px){ #tab-ringkasan .rk-kpi-grid{ grid-template-columns:repeat(2, 1fr); } }
        @media (max-width: 460px){ #tab-ringkasan .rk-kpi-grid{ grid-template-columns:1fr; } }
    </style>

    <div class="page-header" style="margin-bottom: 2rem;">
        <div>
            <h1 style="font-size: 1.75rem; font-weight: 700; color: #0f172a; margin-bottom: 0.5rem; letter-spacing: -0.5px;">Executive Summary</h1>
            <p style="color: #64748b; font-size: 0.95rem;">Menyajikan ringkasan metrik utama dari seluruh platform untuk memberikan gambaran umum kinerja secara menyeluruh.</p>
        </div>
    </div>
    
    <?php
        // Agregasi Global
        $totalFollowers = $metrics->sum('followers_plus');
        $totalReach = $metrics->sum('reach');
        $totalEngagement = $metrics->sum('likes') + $metrics->sum('comments') + $metrics->sum('shares');
        $totalKonten = $metrics->count();
        $engagementRate = $totalReach > 0 ? ($totalEngagement / $totalReach) * 100 : 0;
        
        // Agregasi Per Platform
        $ig = $metrics->filter(fn($m) => strtolower($m->platform) === 'instagram');
        $tk = $metrics->filter(fn($m) => strtolower($m->platform) === 'tiktok');
        $fb = $metrics->filter(fn($m) => strtolower($m->platform) === 'facebook');
        $yt = $metrics->filter(fn($m) => strtolower($m->platform) === 'youtube');
        
        $platformsData = [
            'Instagram' => ['data' => $ig, 'color' => '#e1306c', 'icon' => 'ph-instagram-logo'],
            'TikTok' => ['data' => $tk, 'color' => '#000000', 'icon' => 'ph-tiktok-logo'],
            'Facebook' => ['data' => $fb, 'color' => '#1877f2', 'icon' => 'ph-facebook-logo'],
            'YouTube' => ['data' => $yt, 'color' => '#ff0000', 'icon' => 'ph-youtube-logo'],
        ];
        
        // Insight Logic
        $highestEngPlatformName = '-';
        $highestEngRate = -1;
        $highestGrowthPlatformName = '-';
        $highestSubs = -1;
        
        foreach($platformsData as $name => $pData) {
            $eng = $pData['data']->sum('likes') + $pData['data']->sum('comments') + $pData['data']->sum('shares');
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
        
        $topContent = $metrics->sortByDesc('reach')->first();

        // Data untuk Grafik Chart.js
        $chartLabels = ['Instagram', 'TikTok', 'Facebook', 'YouTube'];
        $chartTotalKonten = [ $ig->count(), $tk->count(), $fb->count(), $yt->count() ];
        $chartReach = [ $ig->sum('reach'), $tk->sum('reach'), $fb->sum('reach'), $yt->sum('reach') ];
        $chartEngagement = [
            $ig->sum('likes') + $ig->sum('comments') + $ig->sum('shares'),
            $tk->sum('likes') + $tk->sum('comments') + $tk->sum('shares'),
            $fb->sum('likes') + $fb->sum('comments') + $fb->sum('shares'),
            $yt->sum('likes') + $yt->sum('comments') + $yt->sum('shares')
        ];
        $chartLikes = [ $ig->sum('likes'), $tk->sum('likes'), $fb->sum('likes'), $yt->sum('likes') ];
        $chartComments = [ $ig->sum('comments'), $tk->sum('comments'), $fb->sum('comments'), $yt->sum('comments') ];
        $chartShares = [ $ig->sum('shares'), $tk->sum('shares'), $fb->sum('shares'), $yt->sum('shares') ];
        
        // Data untuk Filter JS
        $jsPlatformData = [];
        foreach($platformsData as $name => $pData) {
            $pEng = $pData['data']->sum('likes') + $pData['data']->sum('comments') + $pData['data']->sum('shares');
            $pReach = $pData['data']->sum('reach');
            $pSubs = $pData['data']->sum('followers_plus');
            $pCount = $pData['data']->count();
            $pRate = $pReach > 0 ? ($pEng / $pReach) * 100 : 0;
            $jsPlatformData[$name] = [
                'konten' => $pCount,
                'followers' => $pSubs,
                'reach' => $pReach,
                'engagement' => $pEng,
                'likes' => $pData['data']->sum('likes'),
                'comments' => $pData['data']->sum('comments'),
                'shares' => $pData['data']->sum('shares'),
                'rate' => $pRate,
                'growth' => (int)($pSubs * 0.012)
            ];
        }
        $jsPlatformData['Semua Platform'] = [
            'konten' => $totalKonten,
            'followers' => $totalFollowers,
            'reach' => $totalReach,
            'engagement' => $totalEngagement,
            'rate' => $engagementRate,
            'growth' => (int)($totalFollowers * 0.012)
        ];
    ?>

    <!-- KPI UTAMA -->
    <div class="rk-kpi-grid">

        
        <div class="rk-kpi-card">
            <div class="rk-kpi-top">
                <div class="rk-kpi-icon" style="background:#eef2ff; color:#4f46e5;"><i class="ph-fill ph-stack"></i></div>
            </div>
            <div class="rk-kpi-label">Total Konten</div>
            <div class="rk-kpi-value" id="kpi-total-konten"><?php echo e(number_format($totalKonten)); ?></div>
        </div>

        
        <div class="rk-kpi-card">
            <div class="rk-kpi-top">
                <div class="rk-kpi-icon" style="background:#e0e7ff; color:#4f46e5;"><i class="ph-fill ph-users-three"></i></div>
                <span class="rk-kpi-delta up"><i class="ph-bold ph-trend-up"></i> 1.2%</span>
            </div>
            <div class="rk-kpi-label">Total Followers</div>
            <div class="rk-kpi-value" id="kpi-total-followers"><?php echo e(number_format($totalFollowers)); ?></div>
        </div>

        
        <div class="rk-kpi-card">
            <div class="rk-kpi-top">
                <div class="rk-kpi-icon" style="background:#fce7f3; color:#db2777;"><i class="ph-fill ph-heart"></i></div>
                <span class="rk-kpi-delta up"><i class="ph-bold ph-trend-up"></i> 3.4%</span>
            </div>
            <div class="rk-kpi-label">Total Engagement</div>
            <div class="rk-kpi-value" id="kpi-total-engagement"><?php echo e(number_format($totalEngagement)); ?></div>
        </div>

        
        <div class="rk-kpi-card">
            <div class="rk-kpi-top">
                <div class="rk-kpi-icon" style="background:#d1fae5; color:#059669;"><i class="ph-fill ph-broadcast"></i></div>
                <span class="rk-kpi-delta up"><i class="ph-bold ph-trend-up"></i> 5.1%</span>
            </div>
            <div class="rk-kpi-label">Total Reach</div>
            <div class="rk-kpi-value" id="kpi-total-reach"><?php echo e(number_format($totalReach)); ?></div>
        </div>

        
        <div class="rk-kpi-card">
            <div class="rk-kpi-top">
                <div class="rk-kpi-icon" style="background:#f3e8ff; color:#9333ea;"><i class="ph-fill ph-chart-line-up"></i></div>
                <span class="rk-kpi-delta flat"><i class="ph-bold ph-minus"></i> 0.0%</span>
            </div>
            <div class="rk-kpi-label">Engagement Rate</div>
            <div class="rk-kpi-value" id="kpi-engagement-rate"><?php echo e(number_format($engagementRate, 2)); ?>%</div>
        </div>

        
        <div class="rk-kpi-card">
            <div class="rk-kpi-top">
                <div class="rk-kpi-icon" style="background:#dbeafe; color:#2563eb;"><i class="ph-fill ph-user-plus"></i></div>
                <span class="rk-kpi-delta up"><i class="ph-bold ph-trend-up"></i> Baru</span>
            </div>
            <div class="rk-kpi-label">Pertumbuhan Followers</div>
            <div class="rk-kpi-value" id="kpi-pertumbuhan-followers">+<?php echo e(number_format((int)($totalFollowers * 0.012))); ?></div>
        </div>

    </div>
    
    
    <!-- GRAFIK TREN & RINGKASAN PLATFORM -->
    <div class="card" style="margin-bottom: 2rem; border: none; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); border-radius: 1rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
            <div class="card-title" style="margin: 0;">Grafik Tren Performa (Keseluruhan)</div>
            
            <!-- Platform Filters -->
            <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                <select id="filterPlatform" onchange="updateDashboardFilter()" style="padding: 0.5rem 1rem; border-radius: 2rem; border: 1px solid #e2e8f0; background: #fff; color: #0f172a; font-weight: 500; font-size: 0.85rem; cursor: pointer; outline: none; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
                    <option value="Semua Platform">Semua Platform</option>
                    <option value="Instagram">Instagram</option>
                    <option value="TikTok">TikTok</option>
                    <option value="Facebook">Facebook</option>
                    <option value="YouTube">YouTube</option>
                </select>
            </div>
        </div>
        
        <!-- Container untuk Grafik -->
        <div style="display: flex; flex-wrap: wrap; gap: 1.5rem; margin-bottom: 2rem;">
            <!-- Bar Chart -->
            <div style="flex: 1 1 60%; background: #fff; border: 1px solid #e2e8f0; border-radius: 0.5rem; padding: 1.5rem; min-width: 300px;">
                <h3 style="font-size: 1rem; color: #0f172a; margin-bottom: 1rem; font-weight: 600;">Perbandingan Performa Platform</h3>
                <?php if($totalKonten > 0): ?>
                    <div style="height: 300px; position: relative;"><canvas id="barChart"></canvas></div>
                <?php else: ?>
                    <div style="height: 300px; display: flex; flex-direction: column; align-items: center; justify-content: center; background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 0.5rem;">
                        <i class="ph ph-chart-bar" style="font-size: 2.5rem; color: #94a3b8; margin-bottom: 0.5rem;"></i>
                        <p style="color: #64748b; font-weight: 500; font-size: 0.9rem;">Belum ada data</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Pie Chart -->
            <div style="flex: 1 1 30%; background: #fff; border: 1px solid #e2e8f0; border-radius: 0.5rem; padding: 1.5rem; min-width: 250px;">
                <h3 style="font-size: 1rem; color: #0f172a; margin-bottom: 1rem; font-weight: 600;">Distribusi Konten</h3>
                <?php if($totalKonten > 0): ?>
                    <div style="height: 300px; position: relative;"><canvas id="pieChart"></canvas></div>
                <?php else: ?>
                    <div style="height: 300px; display: flex; flex-direction: column; align-items: center; justify-content: center; background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 0.5rem;">
                        <i class="ph ph-chart-pie-slice" style="font-size: 2.5rem; color: #94a3b8; margin-bottom: 0.5rem;"></i>
                        <p style="color: #64748b; font-weight: 500; font-size: 0.9rem;">Belum ada data</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Ringkasan 4 Platform Terpisah -->
        <div style="border-top: 1px solid #e2e8f0; padding-top: 1.5rem; margin-top: 1.5rem;">
            <h3 style="font-size: 1.1rem; color: #0f172a; margin-bottom: 1rem;">Ringkasan Per Platform</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                
                <?php $__currentLoopData = $platformsData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $name => $pData): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $pTotalEng = $pData['data']->sum('likes') + $pData['data']->sum('comments') + $pData['data']->sum('shares');
                    $pTotalReach = $pData['data']->sum('reach');
                    $pTotalSubs = $pData['data']->sum('followers_plus');
                    $pTotalKonten = $pData['data']->count();
                ?>
                <div class="platform-card" data-platform="<?php echo e($name); ?>" style="border: 1px solid #e2e8f0; border-radius: 0.75rem; padding: 1.25rem; background: #fafafa;">
                    <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem;">
                        <i class="ph-fill <?php echo e($pData['icon']); ?>" style="color: <?php echo e($pData['color']); ?>; font-size: 1.5rem;"></i>
                        <span style="font-weight: 700; color: #0f172a;"><?php echo e($name); ?></span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem; font-size: 0.85rem;">
                        <span style="color: #64748b;">Followers:</span>
                        <span style="font-weight: 600; color: #0f172a;"><?php echo e(number_format($pTotalSubs)); ?></span>
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
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                
            </div>
        </div>
    </div>
    
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem; align-items: start;">
        
        <!-- RINGKASAN KONTEN TERBARU -->
        <div class="card" style="border: none; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); border-radius: 1rem;">
            <div class="card-title">Ringkasan Konten Terbaru</div>
            <div class="content-list" style="display: flex; flex-direction: column; gap: 1rem; margin-top: 1rem;">
                <?php $__empty_1 = true; $__currentLoopData = $metrics->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $metric): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="content-item" style="display: flex; gap: 1rem; padding: 1rem; border: 1px solid #f1f5f9; border-radius: 0.75rem; align-items: center; background: #fff;">
                    <!-- Thumbnail/Icon -->
                    <div style="width: 60px; height: 60px; background: #f8fafc; border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; font-size: 1.75rem; flex-shrink: 0;">
                        <?php if(strtolower($metric->platform) == 'facebook'): ?> <i class="ph-fill ph-facebook-logo" style="color: #1877f2;"></i>
                        <?php elseif(strtolower($metric->platform) == 'instagram'): ?> <i class="ph-fill ph-instagram-logo" style="color: #e1306c;"></i>
                        <?php elseif(strtolower($metric->platform) == 'tiktok'): ?> <i class="ph-fill ph-tiktok-logo" style="color: #000000;"></i>
                        <?php elseif(strtolower($metric->platform) == 'youtube'): ?> <i class="ph-fill ph-youtube-logo" style="color: #ff0000;"></i>
                        <?php else: ?> <i class="ph-fill ph-file-video"></i>
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
                        <div style="display: flex; gap: 1rem; font-size: 0.85rem; color: #334155;">
                            <span title="Reach"><i class="ph-bold ph-eye" style="color: #10b981;"></i> <?php echo e(number_format($metric->reach)); ?></span>
                            <span title="Like"><i class="ph-bold ph-heart" style="color: #e1306c;"></i> <?php echo e(number_format($metric->likes)); ?></span>
                            <span title="Comment"><i class="ph-bold ph-chat-circle" style="color: #3b82f6;"></i> <?php echo e(number_format($metric->comments)); ?></span>
                            <span title="Share"><i class="ph-bold ph-share-network" style="color: #8b5cf6;"></i> <?php echo e(number_format($metric->shares)); ?></span>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div style="text-align: center; color: #64748b; padding: 2rem;">Belum ada data konten.</div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- INSIGHT SINGKAT -->
        <!-- <div style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); border-radius: 1rem; padding: 1.5rem; color: #fff; box-shadow: 0 10px 15px -3px rgba(15,23,42,0.3);">
            <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 1px solid rgba(255,255,255,0.1);">
                <i class="ph-fill ph-sparkle" style="font-size: 1.5rem; color: #fbbf24;"></i>
                <h3 style="margin: 0; font-size: 1.15rem; font-weight: 600;">Insight Singkat</h3>
            </div>
            
            <div style="display: flex; flex-direction: column; gap: 1.25rem;"> -->
                
                <!-- Platform Engagement Terbaik -->
                <!-- <div>
                    <div style="font-size: 0.85rem; color: #94a3b8; margin-bottom: 0.25rem; display: flex; align-items: center; gap: 0.25rem;">
                        <i class="ph-bold ph-star"></i> Platform Engagement Terbaik
                    </div>
                    <div style="font-size: 1.05rem; font-weight: 600;">
                        <?php if($highestEngPlatformName != '-'): ?>
                            <span style="color: #60a5fa;"><?php echo e($highestEngPlatformName); ?></span> memimpin dengan rasio tertinggi.
                        <?php else: ?>
                            Data belum mencukupi.
                        <?php endif; ?>
                    </div>
                </div> -->

                <!-- Konten Reach Tertinggi -->
                <!-- <div>
                    <div style="font-size: 0.85rem; color: #94a3b8; margin-bottom: 0.25rem; display: flex; align-items: center; gap: 0.25rem;">
                        <i class="ph-bold ph-fire"></i> Konten Reach Tertinggi
                    </div>
                    <div style="font-size: 1.05rem; font-weight: 600; line-height: 1.4;">
                        <?php if($topContent): ?>
                            "<?php echo e(\Illuminate\Support\Str::limit($topContent->judul_konten ?: 'Konten ' . ucfirst($topContent->platform), 30)); ?>" 
                            <span style="color: #34d399; font-size: 0.95rem;">(<?php echo e(number_format($topContent->reach)); ?> views)</span>
                        <?php else: ?>
                            Belum ada konten dipublikasikan.
                        <?php endif; ?>
                    </div>
                </div> -->

                <!-- Pertumbuhan Terbaik -->
                <!-- <div>
                    <div style="font-size: 0.85rem; color: #94a3b8; margin-bottom: 0.25rem; display: flex; align-items: center; gap: 0.25rem;">
                        <i class="ph-bold ph-trend-up"></i> Pertumbuhan Followers
                    </div>
                    <div style="font-size: 1.05rem; font-weight: 600;">
                        <?php if($highestGrowthPlatformName != '-'): ?>
                            Fokus pada <span style="color: #c084fc;"><?php echo e($highestGrowthPlatformName); ?></span> memberikan pertumbuhan audiens terbanyak.
                        <?php else: ?>
                            Data belum mencukupi.
                        <?php endif; ?>
                    </div>
                </div>
                
            </div>
        </div> -->
        
    </div>
</div>

<!-- Chart.js Library & Initialization -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
let barChartInstance = null;
let pieChartInstance = null;
const platformDataRaw = <?php echo json_encode($jsPlatformData, 15, 512) ?>;
const defaultChartData = {
    labels: <?php echo json_encode($chartLabels, 15, 512) ?>,
    reach: <?php echo json_encode($chartReach, 15, 512) ?>,
    engagement: <?php echo json_encode($chartEngagement, 15, 512) ?>,
    likes: <?php echo json_encode($chartLikes, 15, 512) ?>,
    comments: <?php echo json_encode($chartComments, 15, 512) ?>,
    shares: <?php echo json_encode($chartShares, 15, 512) ?>,
    totalKonten: <?php echo json_encode($chartTotalKonten, 15, 512) ?>
};

function getBarChartData(filter) {
    if (filter === 'Semua Platform') {
        return {
            labels: defaultChartData.labels,
            datasets: [
                { label: 'Reach', data: defaultChartData.reach, backgroundColor: '#10b981' },
                { label: 'Engagement', data: defaultChartData.engagement, backgroundColor: '#8b5cf6' },
                { label: 'Likes', data: defaultChartData.likes, backgroundColor: '#ec4899' },
                { label: 'Comments', data: defaultChartData.comments, backgroundColor: '#3b82f6' },
                { label: 'Shares', data: defaultChartData.shares, backgroundColor: '#f59e0b' }
            ]
        };
    } else {
        const pd = platformDataRaw[filter];
        return {
            labels: [filter],
            datasets: [
                { label: 'Reach', data: [pd.reach], backgroundColor: '#10b981' },
                { label: 'Engagement', data: [pd.engagement], backgroundColor: '#8b5cf6' },
                { label: 'Likes', data: [pd.likes], backgroundColor: '#ec4899' },
                { label: 'Comments', data: [pd.comments], backgroundColor: '#3b82f6' },
                { label: 'Shares', data: [pd.shares], backgroundColor: '#f59e0b' }
            ]
        };
    }
}

function getPieChartData(filter) {
    const colors = ['#e1306c', '#000000', '#1877f2', '#ff0000'];
    if (filter === 'Semua Platform') {
        return {
            labels: defaultChartData.labels,
            datasets: [{ data: defaultChartData.totalKonten, backgroundColor: colors }]
        };
    } else {
        const pd = platformDataRaw[filter];
        let colorIndex = defaultChartData.labels.indexOf(filter);
        return {
            labels: [filter],
            datasets: [{ data: [pd.konten], backgroundColor: [colors[colorIndex]] }]
        };
    }
}

function updateDashboardFilter() {
    const filter = document.getElementById('filterPlatform').value;
    
    // 1. Update KPI Cards
    const pd = platformDataRaw[filter];
    if(pd) {
        document.getElementById('kpi-total-konten').innerText = pd.konten.toLocaleString('en-US');
        document.getElementById('kpi-total-followers').innerText = pd.followers.toLocaleString('en-US');
        document.getElementById('kpi-total-engagement').innerText = pd.engagement.toLocaleString('en-US');
        document.getElementById('kpi-total-reach').innerText = pd.reach.toLocaleString('en-US');
        document.getElementById('kpi-engagement-rate').innerText = pd.rate.toFixed(2) + '%';
        document.getElementById('kpi-pertumbuhan-followers').innerText = '+' + pd.growth.toLocaleString('en-US');
    }

    // 2. Update Charts
    if (barChartInstance && pieChartInstance) {
        barChartInstance.data = getBarChartData(filter);
        barChartInstance.update();
        
        pieChartInstance.data = getPieChartData(filter);
        pieChartInstance.update();
    }

    // 3. Update Ringkasan Per Platform
    const cards = document.querySelectorAll('.platform-card');
    cards.forEach(card => {
        if (filter === 'Semua Platform' || card.getAttribute('data-platform') === filter) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
}

function initCharts() {
    <?php if($totalKonten > 0): ?>
        const barCtx = document.getElementById('barChart').getContext('2d');
        barChartInstance = new Chart(barCtx, {
            type: 'bar',
            data: getBarChartData('Semua Platform'),
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: { y: { beginAtZero: true } },
                plugins: { legend: { position: 'bottom' } }
            }
        });

        const pieCtx = document.getElementById('pieChart').getContext('2d');
        pieChartInstance = new Chart(pieCtx, {
            type: 'pie',
            data: getPieChartData('Semua Platform'),
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom' } }
            }
        });
    <?php endif; ?>
}

document.addEventListener('DOMContentLoaded', initCharts);
</script><?php /**PATH C:\laragon\www\InsightHubBaru\frontend\resources\views/pages/Dashboard/ringkasan.blade.php ENDPATH**/ ?>
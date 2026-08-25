<div id="tab-laporan" class="dashboard-container">

    
    <form method="GET" action="<?php echo e(route('dashboard')); ?>" id="laporanFilterForm">
        <input type="hidden" name="tab" value="laporan">
        <input type="hidden" name="lap_sort" value="<?php echo e($lapFilters['sort']); ?>">
        <input type="hidden" name="lap_dir"  value="<?php echo e($lapFilters['dir']); ?>">

        
        <div class="lap-header">
            <div>
                <h1>Pusat Rekap Data</h1>
                <p>Pantau, cari, dan ekspor seluruh data performa konten secara terperinci.</p>
            </div>
            <div class="lap-export">
                <?php if(auth()->check() && auth()->user()->role === 'user'): ?>
                    <button type="button" onclick="openHistoryModal()" class="lap-btn-export" style="background:#475569; color:#fff; border-color:#475569;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 4px;"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg> Riwayat
                    </button>
                    <button type="button" onclick="openExportModal('excel')" class="lap-btn-export lap-btn-excel">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 4px;"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"></path><polyline points="14 2 14 8 20 8"></polyline><path d="M8 13h2v2H8z"></path><path d="M14 13h2v2H14z"></path><path d="M8 17h2v2H8z"></path><path d="M14 17h2v2H14z"></path></svg> Export Excel
                    </button>
                    <button type="button" onclick="openExportModal('pdf')" class="lap-btn-export lap-btn-pdf">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 4px;"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"></path><polyline points="14 2 14 8 20 8"></polyline><path d="M9 15h6"></path><path d="M9 11h6"></path></svg> Export PDF
                    </button>
                <?php else: ?>
                    <button type="button" onclick="openHistoryModal()" class="lap-btn-export" style="background:#475569; color:#fff; border-color:#475569;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 4px;"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg> Riwayat
                    </button>
                    <a href="<?php echo e(route('dashboard.export.excel', request()->all())); ?>" class="lap-btn-export lap-btn-excel">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 4px;"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"></path><polyline points="14 2 14 8 20 8"></polyline><path d="M8 13h2v2H8z"></path><path d="M14 13h2v2H14z"></path><path d="M8 17h2v2H8z"></path><path d="M14 17h2v2H14z"></path></svg> Export Excel
                    </a>
                    <a href="<?php echo e(route('dashboard.export.pdf', request()->all())); ?>" class="lap-btn-export lap-btn-pdf">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 4px;"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"></path><polyline points="14 2 14 8 20 8"></polyline><path d="M9 15h6"></path><path d="M9 11h6"></path></svg> Export PDF
                    </a>
                <?php endif; ?>
            </div>
        </div>

        
        <div class="lap-filter-card">

            
            <div class="lap-section-heading">
                <span class="lap-section-icon icon-filter"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="4" y1="21" x2="4" y2="14"></line><line x1="4" y1="10" x2="4" y2="3"></line><line x1="12" y1="21" x2="12" y2="12"></line><line x1="12" y1="8" x2="12" y2="3"></line><line x1="20" y1="21" x2="20" y2="16"></line><line x1="20" y1="12" x2="20" y2="3"></line><line x1="1" y1="14" x2="7" y2="14"></line><line x1="9" y1="8" x2="15" y2="8"></line><line x1="17" y1="16" x2="23" y2="16"></line></svg></span>
                Filter Data
                <span class="lap-section-divider"></span>
            </div>

            <div class="lap-filter-row">

                
                <div class="lap-field">
                    <label class="lap-label">Platform</label>
                    <select name="lap_platform" class="lap-select-platform">
                        <option value="all"       <?php echo e(($lapFilters['platform'] ?? 'all') === 'all'       ? 'selected' : ''); ?>>Semua Platform</option>
                        <option value="instagram" <?php echo e(($lapFilters['platform'] ?? '') === 'instagram'   ? 'selected' : ''); ?>>Instagram</option>
                        <option value="tiktok"    <?php echo e(($lapFilters['platform'] ?? '') === 'tiktok'      ? 'selected' : ''); ?>>TikTok</option>
                        <option value="facebook"  <?php echo e(($lapFilters['platform'] ?? '') === 'facebook'    ? 'selected' : ''); ?>>Facebook</option>
                        <option value="yt-live"   <?php echo e(($lapFilters['platform'] ?? '') === 'yt-live'     ? 'selected' : ''); ?>>YouTube Live</option>
                        <option value="yt-video"  <?php echo e(($lapFilters['platform'] ?? '') === 'yt-video'    ? 'selected' : ''); ?>>YouTube Video</option>
                        <option value="yt-shorts" <?php echo e(($lapFilters['platform'] ?? '') === 'yt-shorts'   ? 'selected' : ''); ?>>YouTube Shorts</option>
                    </select>
                </div>

                
                <div class="lap-field">
                    <label class="lap-label">Periode</label>
                    <select name="lap_periode_type" id="lapPeriodeType" class="lap-select-periode"
                            onchange="showLapPeriodeInput(this.value)">
                        <option value=""      <?php echo e(($lapFilters['periode_type'] ?? '') === ''      ? 'selected' : ''); ?>>Semua Waktu</option>
                        <option value="range" <?php echo e(($lapFilters['periode_type'] ?? '') === 'range' ? 'selected' : ''); ?>>Rentang Tanggal</option>
                        <option value="bulan" <?php echo e(($lapFilters['periode_type'] ?? '') === 'bulan' ? 'selected' : ''); ?>>Bulan & Tahun</option>
                        <option value="tahun" <?php echo e(($lapFilters['periode_type'] ?? '') === 'tahun' ? 'selected' : ''); ?>>Tahun</option>
                    </select>
                </div>

                
                <div id="lap-input-range" class="lap-periode-range">
                    <div class="lap-field">
                        <label class="lap-label">Dari</label>
                        <input type="date" name="lap_date_start" value="<?php echo e($lapFilters['date_start'] ?? ''); ?>">
                    </div>
                    <div class="lap-field">
                        <label class="lap-label">Sampai</label>
                        <input type="date" name="lap_date_end" value="<?php echo e($lapFilters['date_end'] ?? ''); ?>">
                    </div>
                </div>

                
                <div id="lap-input-bulan" class="lap-periode-bulan">
                    <div class="lap-field">
                        <label class="lap-label">Bulan</label>
                        <select name="lap_bulan">
                            <?php $__currentLoopData = ['01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April','05'=>'Mei','06'=>'Juni','07'=>'Juli','08'=>'Agustus','09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $num => $nama): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($num); ?>" <?php echo e(($lapFilters['bulan'] ?? '') === $num ? 'selected' : ''); ?>><?php echo e($nama); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="lap-field">
                        <label class="lap-label">Tahun</label>
                        <select name="lap_tahun_bulan">
                            <?php for($y = date('Y'); $y >= 2020; $y--): ?>
                            <option value="<?php echo e($y); ?>" <?php echo e(($lapFilters['tahun_bulan'] ?? date('Y')) == $y ? 'selected' : ''); ?>><?php echo e($y); ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>

                
                <div id="lap-input-tahun" style="display:none;">
                    <div class="lap-field">
                        <label class="lap-label">Tahun</label>
                        <select name="lap_tahun">
                            <?php for($y = date('Y'); $y >= 2020; $y--): ?>
                            <option value="<?php echo e($y); ?>" <?php echo e(($lapFilters['tahun'] ?? date('Y')) == $y ? 'selected' : ''); ?>><?php echo e($y); ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>

                
                <div class="lap-field lap-field-search">
                    <label class="lap-label">Pencarian</label>
                    <div class="lap-search">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: #64748b; margin-right: 4px;"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                        <input type="text" name="lap_search"
                               placeholder="Judul, platform, kategori..."
                               value="<?php echo e($lapFilters['search']); ?>">
                    </div>
                </div>

                
                <div class="lap-filter-actions">
                    <button type="submit" class="lap-btn-apply">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 4px;"><polyline points="20 6 9 17 4 12"></polyline></svg> Terapkan
                    </button>
                    <a href="<?php echo e(route('dashboard')); ?>?tab=laporan" class="lap-btn-reset">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 4px;"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path><polyline points="3 3 3 8 8 8"></polyline></svg> Reset
                    </a>
                </div>

            </div>
        </div>
    </form>

    
    <div class="lap-stats">
        <div class="lap-stat-card">
            <div class="lap-stat-icon icon-indigo">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 22 8.5 12 15 2 8.5 12 2"></polygon><polyline points="2 13.5 12 20 22 13.5"></polyline><polyline points="2 10.5 12 17 22 10.5"></polyline></svg>
            </div>
            <div>
                <div class="lap-stat-title">Total Konten</div>
                <div class="lap-stat-value"><?php echo e(number_format($laporanAgg['total_konten'])); ?></div>
            </div>
        </div>
        <div class="lap-stat-card">
            <div class="lap-stat-icon icon-sky">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4.9 19.1C1 15.2 1 8.8 4.9 4.9"></path><path d="M7.8 16.2c-2.3-2.3-2.3-6.1 0-8.5"></path><circle cx="12" cy="12" r="2"></circle><path d="M16.2 7.8c2.3 2.3 2.3 6.1 0 8.5"></path><path d="M19.1 4.9C23 8.8 23 15.2 19.1 19.1"></path></svg>
            </div>
            <div>
                <div class="lap-stat-title">Total Reach</div>
                <div class="lap-stat-value"><?php echo e(number_format($laporanAgg['total_reach'])); ?></div>
            </div>
        </div>
        <div class="lap-stat-card">
            <div class="lap-stat-icon icon-rose">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"></path></svg>
            </div>
            <div>
                <div class="lap-stat-title">Total Engagement</div>
                <div class="lap-stat-value"><?php echo e(number_format($laporanAgg['total_eng'])); ?></div>
            </div>
        </div>
        <div class="lap-stat-card">
            <div class="lap-stat-icon icon-emerald">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"></polyline><polyline points="16 7 22 7 22 13"></polyline></svg>
            </div>
            <div>
                <div class="lap-stat-title">Avg Eng. Rate</div>
                <div class="lap-stat-value"><?php echo e(number_format($laporanAgg['avg_er'], 2)); ?>%</div>
            </div>
        </div>
    </div>

    
    <div class="lap-table-card">

        <?php
            // Helper: build sort URL (unchanged — no backend modification)
            $buildSortUrl = function($column) use ($lapFilters) {
                $dir = ($lapFilters['sort'] === $column && $lapFilters['dir'] === 'asc') ? 'desc' : 'asc';
                $params = array_merge(request()->all(), ['tab' => 'laporan', 'lap_sort' => $column, 'lap_dir' => $dir]);
                return route('dashboard', $params);
            };
            $sortIcon = function($column) use ($lapFilters) {
                if ($lapFilters['sort'] !== $column) return '';
                return $lapFilters['dir'] === 'asc'
                    ? '<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" style="display:inline; margin-left:2px;"><polyline points="18 15 12 9 6 15"></polyline></svg>'
                    : '<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" style="display:inline; margin-left:2px;"><polyline points="6 9 12 15 18 9"></polyline></svg>';
            };
            $sortColumns = [
                'tgl_upload'     => 'Tgl Upload',
                'platform'       => 'Platform',
                'jenis'          => 'Jenis',
                'kategori'       => 'Kategori',
                'judul_konten'   => 'Judul',
                'reach'          => 'Reach',
                'likes'          => 'Likes',
                'comments'       => 'Comments',
                'shares'         => 'Shares',
                'followers_plus' => 'Followers+',
            ];

            $platformColors = [
                'instagram' => '#e1306c', 'tiktok' => '#010101',
                'facebook'  => '#1877f2', 'youtube' => '#ff0000',
                'yt-video'  => '#ff0000', 'yt-shorts' => '#ff0000', 'yt-live' => '#ff0000',
            ];
            $platformSoft = [
                'instagram' => '#fce7f0', 'tiktok' => '#f1f5f9',
                'facebook'  => '#e3edfd', 'youtube' => '#fde2e2',
                'yt-video'  => '#fde2e2', 'yt-shorts' => '#fde2e2', 'yt-live' => '#fde2e2',
            ];
            $platformIcons = [
                'instagram' => 'fa-brands fa-instagram', 'tiktok'  => 'fa-brands fa-tiktok',
                'facebook'  => 'fa-brands fa-facebook',  'youtube' => 'fa-brands fa-youtube',
                'yt-video'  => 'fa-brands fa-youtube',   'yt-shorts' => 'fa-brands fa-youtube',
                'yt-live'   => 'fa-brands fa-youtube'
            ];
        ?>

        
        <?php
            $activeLabel    = $sortColumns[$lapFilters['sort']] ?? $lapFilters['sort'];
            $isAsc          = $lapFilters['dir'] === 'asc';
            $dirLabel       = $isAsc ? '↑ Naik' : '↓ Turun';
            $currentSortUrl = $buildSortUrl($lapFilters['sort']);
        ?>
        <div class="lap-sort-bar">

            
            <div class="lap-sort-identity">
                <div class="lap-sort-icon-wrap">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><polyline points="19 12 12 19 5 12"></polyline><polyline points="5 12 12 5 19 12"></polyline></svg>
                </div>
                <div class="lap-sort-identity-text">
                    <span class="lap-sort-identity-label">Urutkan Data</span>
                    <span class="lap-sort-identity-value"><?php echo e($activeLabel); ?></span>
                </div>
            </div>

            
            <div class="lap-sort-group">
                <span class="lap-sort-group-label">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 4px;"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg> Berdasarkan
                </span>
                <select
                    id="lapSortSelect"
                    class="lap-sort-select"
                    onchange="lapApplySort()"
                    title="Pilih kolom urutan"
                >
                    <?php $__currentLoopData = $sortColumns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $col => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option
                        value="<?php echo e($buildSortUrl($col)); ?>"
                        <?php echo e($lapFilters['sort'] === $col ? 'selected' : ''); ?>

                    ><?php echo e($label); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <a
                    href="<?php echo e($currentSortUrl); ?>"
                    id="lapSortDirBtn"
                    class="lap-sort-dir-btn <?php echo e($isAsc ? 'asc' : 'desc'); ?>"
                    title="<?php echo e($isAsc ? 'Klik untuk urutan Turun (Z→A)' : 'Klik untuk urutan Naik (A→Z)'); ?>"
                >
                    <?php if($isAsc): ?>
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 4px;"><line x1="12" y1="19" x2="12" y2="5"></line><polyline points="5 12 12 5 19 12"></polyline></svg> Naik
                    <?php else: ?>
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 4px;"><line x1="12" y1="5" x2="12" y2="19"></line><polyline points="19 12 12 19 5 12"></polyline></svg> Turun
                    <?php endif; ?>
                </a>
            </div>

            
            <span class="lap-sort-active-pill">
                <span class="lap-sort-dir-dot"></span>
                <?php echo e($activeLabel); ?> &mdash; <?php echo e($dirLabel); ?>

            </span>

        </div>

        
        <div class="lap-list">
            <?php $__empty_1 = true; $__currentLoopData = $metricsLaporan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php
                $rowEng  = $row->likes + $row->comments + $row->shares + (strtolower($row->sumber_tabel) === 'youtube_shorts' ? $row->repost : 0);
                $rowEr   = $row->reach > 0 ? round(($rowEng / $row->reach) * 100, 2) : 0;
                $platKey = strtolower($row->platform);
                $pColor  = $platformColors[$platKey] ?? '#64748b';
                $pSoft   = $platformSoft[$platKey]   ?? '#f1f5f9';
                $pIcon   = $platformIcons[$platKey]  ?? 'ph-globe';
            ?>

            <div class="lap-row-card">

                
                <div class="lap-row-meta">
                    <div class="lap-row-meta-left">
                        <div class="lap-item-platform-icon" style="background: <?php echo e($pSoft); ?>; color: <?php echo e($pColor); ?>; border-radius: 6px; display: inline-flex; align-items: center; justify-content: center; width: 28px; height: 28px;">
                            <?php if($platKey == 'instagram'): ?>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>
                            <?php elseif($platKey == 'facebook'): ?>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg>
                            <?php elseif($platKey == 'tiktok'): ?>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 12a4 4 0 1 0 4 4V4a5 5 0 0 0 5 5"></path></svg>
                            <?php elseif(str_contains($platKey, 'youtube') || str_contains($platKey, 'yt')): ?>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.25 29 29 0 0 0-.46-5.33z"></path><polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02"></polygon></svg>
                            <?php else: ?>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>
                            <?php endif; ?>
                        </div>
                        <span class="lap-plat-name"><?php echo e(ucfirst($row->platform)); ?></span>
                        <span class="lap-dot"></span>
                        <span class="lap-row-date">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 2px;"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                            <?php echo e($row->tgl_upload ? \Carbon\Carbon::parse($row->tgl_upload)->format('d M Y') : '-'); ?>

                        </span>
                        <?php if($row->jenis): ?>
                        <span class="lap-dot"></span>
                        <span class="lap-pill" style="display: inline-flex; align-items: center; gap: 0.25rem;">
                            <?php
                                $n = strtolower($row->jenis);
                            ?>
                            <?php if(str_contains($n, 'shorts')): ?>
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="6" y="2" width="12" height="20" rx="2" ry="2"></rect><polygon points="10 15 15 12 10 9 10 15"></polygon></svg>
                            <?php elseif(str_contains($n, 'live')): ?>
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="2"></circle><path d="M16.2 7.8a6 6 0 0 1 0 8.5m3.9-12.4a11 11 0 0 1 0 16.3M7.8 16.2a6 6 0 0 1 0-8.5M3.9 19.1a11 11 0 0 1 0-16.3"></path></svg>
                            <?php elseif(str_contains($n, 'video') || str_contains($n, 'stream')): ?>
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="23 7 16 12 23 17 23 7"></polygon><rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect></svg>
                            <?php elseif(str_contains($n, 'post') || str_contains($n, 'image') || str_contains($n, 'feed') || str_contains($n, 'photo')): ?>
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                            <?php else: ?>
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
                            <?php endif; ?>
                            <?php echo e($row->jenis); ?>

                        </span>
                        <?php endif; ?>
                    </div>
                    <?php if($row->kategori): ?>
                    <span class="lap-cat-pill"><?php echo e($row->kategori); ?></span>
                    <?php endif; ?>
                </div>

                
                <div class="lap-row-title"><?php echo e($row->judul_konten ?: '(Tanpa Judul)'); ?></div>

                
                <div class="lap-metrics">
                    <div class="lap-metric-tile">
                        <div class="lap-metric-label">Reach</div>
                        <div class="lap-metric-value"><?php echo e(number_format($row->reach)); ?></div>
                    </div>
                    <div class="lap-metric-tile">
                        <div class="lap-metric-label">Likes</div>
                        <div class="lap-metric-value"><?php echo e(number_format($row->likes)); ?></div>
                    </div>
                    <div class="lap-metric-tile">
                        <div class="lap-metric-label">Comments</div>
                        <div class="lap-metric-value"><?php echo e(number_format($row->comments)); ?></div>
                    </div>
                    <div class="lap-metric-tile">
                        <div class="lap-metric-label">Shares</div>
                        <div class="lap-metric-value"><?php echo e(number_format($row->shares)); ?></div>
                    </div>
                    <div class="lap-metric-tile">
                        <div class="lap-metric-label">ER (%)</div>
                        <span class="lap-er-pill <?php echo e($rowEr >= 3 ? 'good' : ''); ?>"><?php echo e($rowEr); ?>%</span>
                    </div>
                    <div class="lap-metric-tile">
                        <div class="lap-metric-label">Followers+</div>
                        <div class="lap-metric-value"><?php echo e(number_format($row->followers_plus)); ?></div>
                    </div>
                </div>

                
                <div class="lap-link-row">
                    <?php if($row->tautan): ?>
                    <a href="<?php echo e($row->tautan); ?>" target="_blank" class="lap-link-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 4px;"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2 2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg> Lihat Postingan
                    </a>
                    <?php else: ?>
                    <span class="lap-no-link"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 4px;"><path d="M18.84 12.2a4.4 4.4 0 0 0-5.17-5.17"></path><path d="M5.11 11.8a4.4 4.4 0 0 0 5.17 5.17"></path><line x1="2" y1="2" x2="22" y2="22"></line></svg> Belum ada link</span>
                    <?php endif; ?>
                </div>

            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            
            <div class="lap-empty">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 0.5rem;"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                <p>Belum ada data yang sesuai filter yang dipilih.</p>
                <a href="<?php echo e(route('dashboard')); ?>?tab=laporan" class="lap-btn-reset">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 4px;"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path><polyline points="3 3 3 8 8 8"></polyline></svg> Reset Filter
                </a>
            </div>
            <?php endif; ?>
        </div>

        
        <div class="lap-pagination">
            <div>
                Menampilkan <b><?php echo e($metricsLaporan->firstItem() ?? 0); ?></b>
                sampai <b><?php echo e($metricsLaporan->lastItem() ?? 0); ?></b>
                dari <b><?php echo e($metricsLaporan->total()); ?></b> data
            </div>
            <div class="lap-page-nav">
                <?php if($metricsLaporan->onFirstPage()): ?>
                    <button class="lap-page-btn" disabled><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg> Prev</button>
                <?php else: ?>
                    <a href="<?php echo e($metricsLaporan->previousPageUrl()); ?>" class="lap-page-btn"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg> Prev</a>
                <?php endif; ?>

                <span class="lap-page-current">
                    <?php echo e($metricsLaporan->currentPage()); ?> / <?php echo e($metricsLaporan->lastPage() ?: 1); ?>

                </span>

                <?php if($metricsLaporan->hasMorePages()): ?>
                    <a href="<?php echo e($metricsLaporan->nextPageUrl()); ?>" class="lap-page-btn">Next <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg></a>
                <?php else: ?>
                    <button class="lap-page-btn" disabled>Next <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg></button>
                <?php endif; ?>
            </div>
        </div>
    </div>

</div>


<?php if(auth()->check() && auth()->user()->role === 'user'): ?>
<div id="exportModal" class="lap-modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; justify-content: center; align-items: center;">
    <div class="lap-modal-content" style="background: #fff; width: 400px; border-radius: 8px; padding: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <h2 style="margin-top:0; font-size: 1.2rem; font-weight: 600; margin-bottom: 15px;">Permintaan Export Dokumen</h2>
        <form action="<?php echo e(route('export-requests.store')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="type" id="exportType" value="pdf">
            <input type="hidden" name="export_source" value="laporan">
            
            
            <?php $__currentLoopData = request()->except(['_token', 'type', 'reason', 'details']); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if(is_array($value)): ?>
                    <?php $__currentLoopData = $value; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $arrKey => $arrVal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <input type="hidden" name="filters[<?php echo e($key); ?>][<?php echo e($arrKey); ?>]" value="<?php echo e($arrVal); ?>">
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <input type="hidden" name="filters[<?php echo e($key); ?>]" value="<?php echo e($value); ?>">
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            <div style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px; font-weight: 500; font-size: 0.9rem;">Jenis Dokumen</label>
                <input type="text" id="exportTypeLabel" readonly style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; background: #f9f9f9;">
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
                <button type="button" onclick="closeExportModal()" style="padding: 8px 16px; border: 1px solid #ccc; background: #fff; border-radius: 4px; cursor: pointer;">Batal</button>
                <button type="submit" style="padding: 8px 16px; border: none; background: #3b82f6; color: #fff; border-radius: 4px; cursor: pointer;">Kirim Permintaan</button>
            </div>
        </form>
    </div>
</div>

<script>
function openExportModal(type) {
    document.getElementById('exportType').value = type;
    document.getElementById('exportTypeLabel').value = type === 'pdf' ? 'PDF' : 'Excel';
    document.getElementById('exportModal').style.display = 'flex';
}
function closeExportModal() {
    document.getElementById('exportModal').style.display = 'none';
}
</script>
<?php endif; ?>

<?php if(auth()->check() && in_array(auth()->user()->role, ['super-admin', 'admin'])): ?>
    <?php echo $__env->make('pages.Export.approval', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('pages.Export.history', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php if($exportRequests->where('status', 'pending')->count() > 0): ?>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            if (typeof openApprovalModal === 'function') {
                openApprovalModal();
            }
        });
    </script>
    <?php endif; ?>
<?php elseif(auth()->check() && auth()->user()->role === 'user'): ?>
    <?php echo $__env->make('pages.Export.history', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php endif; ?>

<script>
    /**
     * showLapPeriodeInput — shows/hides date sub-inputs based on selected period type.
     * Called via onchange on #lapPeriodeType select.
     */
    function showLapPeriodeInput(type) {
        // IDs are used by the JS to control visibility (preserved as-is)
        var ids = ['range', 'bulan', 'tahun'];
        ids.forEach(function(t) {
            var el = document.getElementById('lap-input-' + t);
            if (!el) return;
            if (t === type) {
                el.style.display = 'flex';
            } else {
                el.style.display = 'none';
            }
        });
    }

    // Auto-show the correct period sub-input on page load
    (function () {
        var sel = document.getElementById('lapPeriodeType');
        if (sel) showLapPeriodeInput(sel.value);
    })();

    /**
     * lapApplySort — navigates to the sort URL stored in the selected <option> value.
     * Each option's value is a full URL pre-built server-side by $buildSortUrl.
     */
    function lapApplySort() {
        var sel = document.getElementById('lapSortSelect');
        if (sel && sel.value) {
            window.location.href = sel.value;
        }
    }
</script><?php /**PATH C:\laragon\www\InsightHubBaru\frontend\resources\views/pages/Report/laporan.blade.php ENDPATH**/ ?>
<div id="tab-analitik" class="dashboard-container">

    {{-- ============================================================
         FILTER PANEL
    ============================================================ --}}
    <form method="GET" action="{{ route('dashboard') }}" id="analitikFilterForm">
        {{-- Hidden: agar setelah submit, JS tahu harus membuka tab analitik --}}
        <input type="hidden" name="tab" value="analitik">

        <div class="page-header" style="flex-wrap: wrap; gap: 1.5rem; align-items: flex-start;">
            <div>
                <h1>Analitik Konten</h1>
                <p>Analisis performa konten berdasarkan platform dan periode yang dipilih.</p>
            </div>

            {{-- FILTER AREA --}}
            <div style="display: flex; flex-wrap: wrap; gap: 1rem; align-items: flex-end; flex: 1; justify-content: flex-end;">

                {{-- Filter Platform --}}
                <div style="display: flex; flex-direction: column; gap: 0.35rem;">
                    <label style="font-size: 0.78rem; font-weight: 600; color: #475569; text-transform: uppercase; letter-spacing: 0.05em;">Platform</label>
                    <select name="analitik_platform" class="filter-select dark" onchange="togglePeriodeType()" style="min-width: 160px;">
                        <option value="all"      {{ ($analitikFilters['platform'] ?? 'all') === 'all'       ? 'selected' : '' }}>Semua Platform</option>
                        <option value="instagram" {{ ($analitikFilters['platform'] ?? '') === 'instagram'   ? 'selected' : '' }}>Instagram</option>
                        <option value="tiktok"    {{ ($analitikFilters['platform'] ?? '') === 'tiktok'      ? 'selected' : '' }}>TikTok</option>
                        <option value="facebook"  {{ ($analitikFilters['platform'] ?? '') === 'facebook'    ? 'selected' : '' }}>Facebook</option>
                        <option value="youtube"   {{ ($analitikFilters['platform'] ?? '') === 'youtube'     ? 'selected' : '' }}>YouTube</option>
                    </select>
                </div>

                {{-- Filter Jenis Periode --}}
                <div style="display: flex; flex-direction: column; gap: 0.35rem;">
                    <label style="font-size: 0.78rem; font-weight: 600; color: #475569; text-transform: uppercase; letter-spacing: 0.05em;">Jenis Periode</label>
                    <select name="analitik_periode_type" id="analitikPeriodeType" class="filter-select" onchange="showPeriodeInput(this.value)" style="min-width: 160px;">
                        <option value=""       {{ ($analitikFilters['periode_type'] ?? '') === ''      ? 'selected' : '' }}>Semua Waktu</option>
                        <option value="range"  {{ ($analitikFilters['periode_type'] ?? '') === 'range' ? 'selected' : '' }}>Rentang Tanggal</option>
                        <option value="bulan"  {{ ($analitikFilters['periode_type'] ?? '') === 'bulan' ? 'selected' : '' }}>Bulan & Tahun</option>
                        <option value="tahun"  {{ ($analitikFilters['periode_type'] ?? '') === 'tahun' ? 'selected' : '' }}>Tahun</option>
                    </select>
                </div>

                {{-- Input: Rentang Tanggal --}}
                <div id="input-range" style="display: none; gap: 0.5rem; align-items: flex-end; flex-wrap: wrap;">
                    <div style="display: flex; flex-direction: column; gap: 0.35rem;">
                        <label style="font-size: 0.78rem; font-weight: 600; color: #475569; text-transform: uppercase; letter-spacing: 0.05em;">Dari</label>
                        <input type="date" name="analitik_date_start" class="filter-select" value="{{ $analitikFilters['date_start'] ?? '' }}">
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 0.35rem;">
                        <label style="font-size: 0.78rem; font-weight: 600; color: #475569; text-transform: uppercase; letter-spacing: 0.05em;">Sampai</label>
                        <input type="date" name="analitik_date_end" class="filter-select" value="{{ $analitikFilters['date_end'] ?? '' }}">
                    </div>
                </div>

                {{-- Input: Bulan & Tahun --}}
                <div id="input-bulan" style="display: none; gap: 0.5rem; align-items: flex-end; flex-wrap: wrap;">
                    <div style="display: flex; flex-direction: column; gap: 0.35rem;">
                        <label style="font-size: 0.78rem; font-weight: 600; color: #475569; text-transform: uppercase; letter-spacing: 0.05em;">Bulan</label>
                        <select name="analitik_bulan" class="filter-select">
                            @foreach(['01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April','05'=>'Mei','06'=>'Juni','07'=>'Juli','08'=>'Agustus','09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'] as $num => $nama)
                            <option value="{{ $num }}" {{ ($analitikFilters['bulan'] ?? '') === $num ? 'selected' : '' }}>{{ $nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 0.35rem;">
                        <label style="font-size: 0.78rem; font-weight: 600; color: #475569; text-transform: uppercase; letter-spacing: 0.05em;">Tahun</label>
                        <select name="analitik_tahun_bulan" class="filter-select">
                            @for($y = date('Y'); $y >= 2020; $y--)
                            <option value="{{ $y }}" {{ ($analitikFilters['tahun_bulan'] ?? date('Y')) == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                </div>

                {{-- Input: Tahun --}}
                <div id="input-tahun" style="display: none;">
                    <div style="display: flex; flex-direction: column; gap: 0.35rem;">
                        <label style="font-size: 0.78rem; font-weight: 600; color: #475569; text-transform: uppercase; letter-spacing: 0.05em;">Tahun</label>
                        <select name="analitik_tahun" class="filter-select">
                            @for($y = date('Y'); $y >= 2020; $y--)
                            <option value="{{ $y }}" {{ ($analitikFilters['tahun'] ?? date('Y')) == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                </div>

                {{-- Tombol Terapkan --}}
                <button type="submit" class="btn btn-primary" style="height: 38px; display: flex; align-items: center; gap: 0.5rem; white-space: nowrap;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="4" y1="21" x2="4" y2="14"></line><line x1="4" y1="10" x2="4" y2="3"></line><line x1="12" y1="21" x2="12" y2="12"></line><line x1="12" y1="8" x2="12" y2="3"></line><line x1="20" y1="21" x2="20" y2="16"></line><line x1="20" y1="12" x2="20" y2="3"></line><line x1="1" y1="14" x2="7" y2="14"></line><line x1="9" y1="8" x2="15" y2="8"></line><line x1="17" y1="16" x2="23" y2="16"></line></svg> Terapkan Filter
                </button>

                {{-- Tombol Reset --}}
                <a href="{{ route('dashboard') }}" class="btn" style="height: 38px; display: flex; align-items: center; gap: 0.5rem; white-space: nowrap;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path><polyline points="3 3 3 8 8 8"></polyline></svg> Reset
                </a>
            </div>
        </div>
    </form>

    {{-- ============================================================
         PHP: KALKULASI DARI $metricsAnalitik (DATA SUDAH TERFILTER)
    ============================================================ --}}
    @php
        $ma = $metricsAnalitik; // alias pendek

        // --- KPI ---
        $aTotal       = $ma->count();
        $aTotalReach  = $ma->sum('reach');
        $aTotalLike   = $ma->sum('likes');
        $aTotalComment= $ma->sum('comments');
        $aTotalShare  = $ma->sum('shares');
        $aTotalEng    = $aTotalLike + $aTotalComment + $aTotalShare + $ma->where('sumber_tabel', 'youtube_shorts')->sum('repost');
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
            $pEng = $items->sum('likes') + $items->sum('comments') + $items->sum('shares') + $items->where('sumber_tabel', 'youtube_shorts')->sum('repost');
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
            $cEng = $items->sum('likes') + $items->sum('comments') + $items->sum('shares') + $items->where('sumber_tabel', 'youtube_shorts')->sum('repost');
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
            $ctEng = $items->sum('likes') + $items->sum('comments') + $items->sum('shares') + $items->where('sumber_tabel', 'youtube_shorts')->sum('repost');
            $ctReach = $items->sum('reach');
            $contentTypeStats[$ct ?: 'Lainnya'] = [
                'count' => $items->count(),
                'reach' => $ctReach,
                'eng'   => $ctEng,
                'rate'  => $ctReach > 0 ? round(($ctEng / $ctReach) * 100, 2) : 0,
            ];
        }
        $maxCtReach = collect($contentTypeStats)->max('reach') ?: 1;

        // --- Top 10 by Reach & Top 10 by Likes ---
        $top10      = $ma->sortByDesc('reach')->take(10);
        $top10Likes = $ma->sortByDesc('likes')->take(10);

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
    @endphp

    {{-- Active filter badge --}}
    <div style="margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap;">
        <span style="font-size: 0.85rem; color: #64748b;">Menampilkan data untuk:</span>
        <span style="background: #0f172a; color: #fff; padding: 0.3rem 0.75rem; border-radius: 2rem; font-size: 0.8rem; font-weight: 600; display: flex; align-items: center; gap: 0.35rem;">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="2" width="14" height="20" rx="2" ry="2"></rect><line x1="12" y1="18" x2="12.01" y2="18"></line></svg> {{ $activeLabel }}
        </span>
        <span style="background: #e0e7ff; color: #4f46e5; padding: 0.3rem 0.75rem; border-radius: 2rem; font-size: 0.8rem; font-weight: 600; display: flex; align-items: center; gap: 0.35rem;">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg> {{ $activePeriode }}
        </span>
        <span style="background: #dcfce7; color: #166534; padding: 0.3rem 0.75rem; border-radius: 2rem; font-size: 0.8rem; font-weight: 600; display: flex; align-items: center; gap: 0.35rem;">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg> {{ $aTotal }} konten
        </span>
    </div>

    {{-- ============================================================
         KPI CARDS
    ============================================================ --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 1.25rem; margin-bottom: 2rem;">

        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-title" style="margin:0; font-size: 0.8rem; color: #475569;">TOTAL REACH</div>
                <div class="stat-icon" style="background:#d1fae5; color:#059669; width:36px; height:36px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4.9 19.1C1 15.2 1 8.8 4.9 4.9"></path><path d="M7.8 16.2c-2.3-2.3-2.3-6.1 0-8.5"></path><circle cx="12" cy="12" r="2"></circle><path d="M16.2 7.8c2.3 2.3 2.3 6.1 0 8.5"></path><path d="M19.1 4.9C23 8.8 23 15.2 19.1 19.1"></path></svg>
                </div>
            </div>
            <div class="stat-value" style="font-size: 1.5rem;">{{ number_format($aTotalReach) }}</div>
            <div style="font-size: 0.75rem; color: #64748b; margin-top: 0.25rem;">Total Views / Reach</div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-title" style="margin:0; font-size: 0.8rem; color: #475569;">TOTAL ENGAGEMENT</div>
                <div class="stat-icon" style="background:#fce7f3; color:#db2777; width:36px; height:36px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"></path></svg>
                </div>
            </div>
            <div class="stat-value" style="font-size: 1.5rem;">{{ number_format($aTotalEng) }}</div>
            <div style="font-size: 0.75rem; color: #64748b; margin-top: 0.25rem;">Like + Comment + Share</div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-title" style="margin:0; font-size: 0.8rem; color: #475569;">ENGAGEMENT RATE</div>
                <div class="stat-icon" style="background:#f3e8ff; color:#9333ea; width:36px; height:36px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"></polyline><polyline points="16 7 22 7 22 13"></polyline></svg>
                </div>
            </div>
            <div class="stat-value" style="font-size: 1.5rem;">{{ number_format($aEngRate, 2) }}%</div>
            <div style="font-size: 0.75rem; color: #64748b; margin-top: 0.25rem;">Eng / Reach × 100%</div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-title" style="margin:0; font-size: 0.8rem; color: #475569;">AVG ENGAGEMENT</div>
                <div class="stat-icon" style="background:#eef2ff; color:#4f46e5; width:36px; height:36px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 12h-4l-3 9L9 3l-3 9H2"></path></svg>
                </div>
            </div>
            <div class="stat-value" style="font-size: 1.5rem;">{{ number_format($aAvgEng, 0) }}</div>
            <div style="font-size: 0.75rem; color: #64748b; margin-top: 0.25rem;">Per Konten</div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-title" style="margin:0; font-size: 0.8rem; color: #475569;">AVG REACH</div>
                <div class="stat-icon" style="background:#f1f5f9; color:#475569; width:36px; height:36px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                </div>
            </div>
            <div class="stat-value" style="font-size: 1.5rem;">{{ number_format($aAvgReach, 0) }}</div>
            <div style="font-size: 0.75rem; color: #64748b; margin-top: 0.25rem;">Per Konten</div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-title" style="margin:0; font-size: 0.8rem; color: #475569;">TOTAL KONTEN</div>
                <div class="stat-icon" style="background:#fef3c7; color:#d97706; width:36px; height:36px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 22 8.5 12 15 2 8.5 12 2"></polygon><polyline points="2 13.5 12 20 22 13.5"></polyline><polyline points="2 10.5 12 17 22 10.5"></polyline></svg>
                </div>
            </div>
            <div class="stat-value" style="font-size: 1.5rem;">{{ number_format($aTotal) }}</div>
            <div style="font-size: 0.75rem; color: #64748b; margin-top: 0.25rem;">Konten Dianalisis</div>
        </div>
    </div>

    {{-- ============================================================
         ANALISIS PLATFORM + JENIS KONTEN
    ============================================================ --}}
    <div class="grid-2-1" style="margin-bottom: 2rem;">

        {{-- Analisis Platform (gaya leaderboard) --}}
        <div class="card">
            <div class="card-title">Platform Terbaik
                <span style="font-size: 0.75rem; font-weight: normal; background: #f1f5f9; padding: 4px 8px; border-radius: 4px;">Diurutkan berdasarkan reach</span>
            </div>
            @if(count($platformStats) > 0)
            @php $rankedPlatforms = collect($platformStats)->sortByDesc('reach'); @endphp
            <div style="display: flex; flex-direction: column; gap: 0.5rem; margin-top: 0.75rem;">
                @foreach($rankedPlatforms as $platName => $pStat)
                @php $rank = $loop->iteration; @endphp
                <div style="display: flex; align-items: center; gap: 0.75rem; padding: 0.6rem; border-radius: 10px; background: {{ $rank === 1 ? '#f8fafc' : 'transparent' }};">
                    <div style="width: 26px; height: 26px; border-radius: 50%; background: {{ $rank === 1 ? '#fac775' : '#f1f5f9' }}; color: {{ $rank === 1 ? '#633806' : '#64748b' }}; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 600; flex-shrink: 0;">{{ $rank }}</div>
                    @php
                        $platLowerName = strtolower($platName);
                        $bgSoft = '#f1f5f9';
                        if ($platLowerName == 'facebook') { $bgSoft = '#e3edfd'; }
                        elseif ($platLowerName == 'instagram') { $bgSoft = '#fce7f0'; }
                        elseif ($platLowerName == 'tiktok') { $bgSoft = '#f1f5f9'; }
                        elseif (str_contains($platLowerName, 'youtube') || str_contains($platLowerName, 'yt-')) { $bgSoft = '#fde2e2'; }
                    @endphp
                    <div style="width: 32px; height: 32px; border-radius: 6px; display: inline-flex; align-items: center; justify-content: center; background: {{ $bgSoft }}; color: {{ $pStat['color'] }}; flex-shrink: 0;">
                        @if($platName == 'instagram')
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>
                        @elseif($platName == 'facebook')
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg>
                        @elseif($platName == 'tiktok')
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 12a4 4 0 1 0 4 4V4a5 5 0 0 0 5 5"></path></svg>
                        @elseif($platName == 'youtube' || $platName == 'yt-video' || $platName == 'yt-shorts' || $platName == 'yt-live')
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.25 29 29 0 0 0-.46-5.33z"></path><polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02"></polygon></svg>
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>
                        @endif
                    </div>
                    <div style="flex: 1; min-width: 0;">
                        <div style="font-size: 0.875rem; font-weight: 600; color: #0f172a;">{{ ucfirst($platName) }}</div>
                        <div style="font-size: 0.75rem; color: #64748b;">{{ $pStat['count'] }} konten &middot; {{ $pStat['rate'] }}% ER</div>
                    </div>
                    <div style="font-size: 1rem; font-weight: 600; color: #0f172a; white-space: nowrap;">{{ number_format($pStat['reach']) }}</div>
                </div>
                @endforeach
            </div>
            @else
            <div style="text-align: center; padding: 2rem; color: #64748b;">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 0.5rem;"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                <br>Tidak ada data untuk filter ini.
            </div>
            @endif
        </div>

        {{-- Analisis Jenis Konten (gaya gauge/dial) --}}
        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
            <div class="card">
                <div class="card-title" style="margin-bottom: 0.25rem;">Format Paling Efektif</div>
                <div style="font-size: 0.75rem; color: #64748b; margin-bottom: 1rem;">Engagement rate per format konten</div>
                @if(count($contentTypeStats) > 0)
                @php $avgCtRate = collect($contentTypeStats)->avg('rate'); @endphp
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem;">
                    @foreach($contentTypeStats as $ctName => $ctStat)
                    @php
                        $gaugePct = max(0, min(100, $ctStat['rate']));
                        $gaugeColor = $ctStat['rate'] >= $avgCtRate ? '#639922' : '#94a3b8';
                    @endphp
                    <div style="background: #f8fafc; border-radius: 10px; padding: 0.75rem; text-align: center;">
                        <div style="width: 56px; height: 56px; border-radius: 50%; margin: 0 auto 6px; background: conic-gradient({{ $gaugeColor }} 0% {{ $gaugePct }}%, #e2e8f0 {{ $gaugePct }}% 100%); display: flex; align-items: center; justify-content: center;">
                            <div style="width: 40px; height: 40px; border-radius: 50%; background: #f8fafc; display: flex; align-items: center; justify-content: center; font-size: 0.72rem; font-weight: 600; color: #0f172a;">{{ number_format($ctStat['rate'], 1) }}%</div>
                        </div>
                        <div style="font-size: 0.75rem; font-weight: 600; color: #0f172a; display: inline-flex; align-items: center; gap: 0.35rem; margin: 0 auto 0.25rem; justify-content: center;">
                            @php
                                $n = strtolower($ctName);
                            @endphp
                            @if(str_contains($n, 'shorts'))
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: #ff0000;"><rect x="6" y="2" width="12" height="20" rx="2" ry="2"></rect><polygon points="10 15 15 12 10 9 10 15"></polygon></svg>
                            @elseif(str_contains($n, 'live'))
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: #ef4444;"><circle cx="12" cy="12" r="2"></circle><path d="M16.2 7.8a6 6 0 0 1 0 8.5m3.9-12.4a11 11 0 0 1 0 16.3M7.8 16.2a6 6 0 0 1 0-8.5M3.9 19.1a11 11 0 0 1 0-16.3"></path></svg>
                            @elseif(str_contains($n, 'reels'))
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: #e1306c;"><rect x="2" y="2" width="20" height="20" rx="2.18" ry="2.18"></rect><line x1="7" y1="2" x2="7" y2="22"></line><line x1="17" y1="2" x2="17" y2="22"></line><line x1="2" y1="12" x2="22" y2="12"></line><line x1="2" y1="7" x2="7" y2="7"></line><line x1="2" y1="17" x2="7" y2="17"></line><line x1="17" y1="17" x2="22" y2="17"></line><line x1="17" y1="7" x2="22" y2="7"></line></svg>
                            @elseif(str_contains($n, 'video'))
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: #3b82f6;"><polygon points="23 7 16 12 23 17 23 7"></polygon><rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect></svg>
                            @elseif(str_contains($n, 'post') || str_contains($n, 'image') || str_contains($n, 'feed'))
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: #10b981;"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: #64748b;"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                            @endif
                            <span>{{ ucfirst($ctName ?: 'Lainnya') }}</span>
                        </div>
                        <div style="font-size: 0.7rem; color: #64748b;">{{ $ctStat['count'] }} konten</div>
                    </div>
                    @endforeach
                </div>
                @else
                <div style="text-align: center; padding: 1.5rem; color: #64748b; font-size: 0.875rem;">Tidak ada data.</div>
                @endif
            </div>
        </div>
    </div>

    {{-- ============================================================
         ANALISIS KATEGORI KONTEN
    ============================================================ --}}
    <div class="card" style="margin-bottom: 2rem;">
        <div class="card-title">Analisis Kategori Konten
            <span style="font-size: 0.75rem; font-weight: normal; background: #f1f5f9; padding: 4px 8px; border-radius: 4px;">{{ $activeLabel }} &bull; {{ $activePeriode }}</span>
        </div>
        @if(count($categoryStats) > 0)
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; align-items: start;">
            
            <!-- Kiri: Tabel -->
            <div style="overflow-x: auto; background: #fff; border: 1px solid #e2e8f0; border-radius: 0.5rem;">
                <table class="data-table" style="margin-top: 0;">
                    <thead>
                        <tr>
                            <th>Kategori</th>
                            <th style="text-align: center;">Jumlah Konten</th>
                            <th style="text-align: right;">Total Reach</th>
                            <th style="text-align: right;">Total Engagement</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(collect($categoryStats)->sortByDesc('reach') as $catName => $cStat)
                        <tr>
                            <td>
                                <span style="background: #e2e8f0; padding: 0.2rem 0.6rem; border-radius: 1rem; font-size: 0.8rem; font-weight: 500;">
                                    {{ $catName }}
                                </span>
                            </td>
                            <td style="text-align: center; font-weight: 600;">{{ $cStat['count'] }}</td>
                            <td style="text-align: right; font-weight: 600;">{{ number_format($cStat['reach']) }}</td>
                            <td style="text-align: right; font-weight: 600;">{{ number_format($cStat['eng']) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Kanan: Line Chart -->
            <div style="background: #fff; border: 1px solid #e2e8f0; border-radius: 0.5rem; padding: 1.5rem; display: flex; flex-direction: column;">
                <div style="margin-bottom: 1rem;">
                    <h3 style="font-size: 1rem; font-weight: 600; color: #0f172a; margin: 0 0 0.25rem 0;">Tren Kategori Konten</h3>
                    <p style="font-size: 0.8rem; color: #64748b; margin: 0;">Jumlah Konten berdasarkan Kategori</p>
                </div>
                <div style="flex-grow: 1; position: relative; height: 350px;">
                    <canvas id="kategoriLineChart"></canvas>
                </div>
            </div>
            
        </div>
        @else
        <div style="text-align: center; padding: 2rem; color: #64748b;">
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: block; margin: 0 auto 0.5rem;"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
            Tidak ada data kategori untuk filter ini.
        </div>
        @endif
    </div>

    {{-- ============================================================
         TOP 10 & BOTTOM 10
    ============================================================ --}}
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">

        {{-- TOP 10 --}}
        <div class="card" style="padding: 0;">
            <div class="card-title" style="padding: 1.25rem 1.5rem; margin-bottom: 0; border-bottom: 1px solid var(--border); display: flex; align-items: center; gap: 0.5rem;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 2px;"><path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"></path><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"></path><path d="M4 22h16"></path><path d="M10 14.66V17c0 .55-.45 1-1 1H4v2h16v-2h-5c-.55 0-1-.45-1-1v-2.34"></path><path d="M12 2a6 6 0 0 1 6 6v5a6 6 0 0 1-6 6 6 6 0 0 1-6-6V8a6 6 0 0 1 6-6z"></path></svg> Top 10 Konten
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
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($top10 as $i => $metric)
                        <tr>
                            <td style="font-weight: 700; color: {{ $i < 3 ? '#f59e0b' : '#64748b' }}; font-size: 0.9rem;">{{ $i + 1 }}</td>
                            <td>
                                <div style="font-size: 0.85rem; font-weight: 600; color: #0f172a; max-width: 160px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                    {{ $metric->judul_konten ?: 'Konten ' . ucfirst($metric->platform) }}
                                </div>
                                <div style="font-size: 0.75rem; color: #94a3b8;">
                                    {{ $metric->tgl_upload ? \Carbon\Carbon::parse($metric->tgl_upload)->format('d M Y') : '-' }}
                                </div>
                            </td>
                            <td style="font-size: 0.8rem;">{{ ucfirst($metric->platform) }}</td>
                            <td style="text-align: right; font-weight: 600; font-size: 0.875rem;">{{ number_format($metric->reach) }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" style="text-align: center; color: #64748b; padding: 2rem;">Tidak ada data.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- TOP 10 LIKES --}}
        <div class="card" style="padding: 0;">
            <div class="card-title" style="padding: 1.25rem 1.5rem; margin-bottom: 0; border-bottom: 1px solid var(--border); display: flex; align-items: center; gap: 0.5rem;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#db2777" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 2px;"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"></path></svg> Top 10 Likes Konten
                <span style="font-size: 0.75rem; font-weight: normal; color: #64748b; margin-left: auto;">Berdasarkan jumlah Likes tertinggi</span>
            </div>
            <div style="overflow-x: auto;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Konten</th>
                            <th>Platform</th>
                            <th style="text-align: right;">Likes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($top10Likes as $i => $metric)
                        <tr>
                            <td style="font-weight: 700; color: {{ $i < 3 ? '#db2777' : '#64748b' }}; font-size: 0.9rem;">{{ $i + 1 }}</td>
                            <td>
                                <div style="font-size: 0.85rem; font-weight: 600; color: #0f172a; max-width: 160px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                    {{ $metric->judul_konten ?: 'Konten ' . ucfirst($metric->platform) }}
                                </div>
                                <div style="font-size: 0.75rem; color: #94a3b8;">
                                    {{ $metric->tgl_upload ? \Carbon\Carbon::parse($metric->tgl_upload)->format('d M Y') : '-' }}
                                </div>
                            </td>
                            <td style="font-size: 0.8rem;">{{ ucfirst($metric->platform) }}</td>
                            <td style="text-align: right; font-weight: 600; font-size: 0.875rem; color: #db2777;">{{ number_format($metric->likes) }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" style="text-align: center; color: #64748b; padding: 2rem;">Tidak ada data.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ============================================================
         INSIGHT ANALITIK
    ============================================================ --}}
    {{--
    <div style="background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 100%); border-radius: 1rem; padding: 1.75rem; color: #fff; box-shadow: 0 10px 25px -5px rgba(15,23,42,0.35); margin-bottom: 2rem;">
        <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 1px solid rgba(255,255,255,0.1);">
            <i class="ph-fill ph-lightbulb" style="font-size: 1.5rem; color: #fbbf24;"></i>
            <h3 style="margin: 0; font-size: 1.15rem; font-weight: 600;">Insight Analitik</h3>
            <span style="margin-left: auto; font-size: 0.8rem; color: #94a3b8; background: rgba(255,255,255,0.08); padding: 0.25rem 0.75rem; border-radius: 1rem;">
                {{ $activeLabel }} &bull; {{ $activePeriode }}
            </span>
        </div>

        @if($aTotal === 0)
        <div style="text-align: center; padding: 1rem; color: #94a3b8;">
            <i class="ph ph-info" style="font-size: 2rem; display: block; margin-bottom: 0.5rem;"></i>
            Tidak ada data untuk filter yang dipilih. Coba ubah filter platform atau periode.
        </div>
        @else
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.25rem;">

            <div style="background: rgba(255,255,255,0.05); border-radius: 0.75rem; padding: 1.25rem; border: 1px solid rgba(255,255,255,0.08);">
                <div style="font-size: 0.8rem; color: #94a3b8; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.35rem;">
                    <i class="ph-bold ph-star"></i> Platform Terbaik
                </div>
                <div style="font-size: 1rem; font-weight: 600;">
                    @if($topPlatform)
                        <span style="color: #60a5fa;">{{ ucfirst($topPlatform) }}</span> memiliki Engagement Rate tertinggi
                        <span style="color: #34d399;">({{ $platformStats[$topPlatform]['rate'] ?? 0 }}%)</span>
                        pada periode ini.
                    @else
                        Data belum mencukupi.
                    @endif
                </div>
            </div>

            <div style="background: rgba(255,255,255,0.05); border-radius: 0.75rem; padding: 1.25rem; border: 1px solid rgba(255,255,255,0.08);">
                <div style="font-size: 0.8rem; color: #94a3b8; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.35rem;">
                    <i class="ph-bold ph-fire"></i> Kategori Terperforma
                </div>
                <div style="font-size: 1rem; font-weight: 600;">
                    @if($topCategory)
                        Konten kategori <span style="color: #f472b6;">{{ $topCategory }}</span> menghasilkan
                        Engagement Rate tertinggi
                        <span style="color: #34d399;">({{ $categoryStats[$topCategory]['rate'] ?? 0 }}%)</span>.
                    @else
                        Data kategori belum tersedia.
                    @endif
                </div>
            </div>

            <div style="background: rgba(255,255,255,0.05); border-radius: 0.75rem; padding: 1.25rem; border: 1px solid rgba(255,255,255,0.08);">
                <div style="font-size: 0.8rem; color: #94a3b8; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.35rem;">
                    <i class="ph-bold ph-video"></i> Jenis Konten Terbaik
                </div>
                <div style="font-size: 1rem; font-weight: 600;">
                    @if($topCtType)
                        Format <span style="color: #c084fc;">{{ ucfirst($topCtType) }}</span> menghasilkan
                        Engagement Rate tertinggi
                        <span style="color: #34d399;">({{ $contentTypeStats[$topCtType]['rate'] ?? 0 }}%)</span>.
                    @else
                        Data jenis konten belum tersedia.
                    @endif
                </div>
            </div>

            <div style="background: rgba(255,255,255,0.05); border-radius: 0.75rem; padding: 1.25rem; border: 1px solid rgba(255,255,255,0.08);">
                <div style="font-size: 0.8rem; color: #94a3b8; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.35rem;">
                    <i class="ph-bold ph-crown"></i> Konten Reach Tertinggi
                </div>
                <div style="font-size: 1rem; font-weight: 600; line-height: 1.5;">
                    @if($topReachContent)
                        &ldquo;{{ \Illuminate\Support\Str::limit($topReachContent->judul_konten ?: 'Konten ' . ucfirst($topReachContent->platform), 35) }}&rdquo;
                        meraih <span style="color: #34d399;">{{ number_format($topReachContent->reach) }} views</span>.
                    @else
                        Belum ada konten terpublikasi.
                    @endif
                </div>
            </div>

            <div style="background: rgba(255,255,255,0.05); border-radius: 0.75rem; padding: 1.25rem; border: 1px solid rgba(255,255,255,0.08);">
                <div style="font-size: 0.8rem; color: #94a3b8; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.35rem;">
                    <i class="ph-bold ph-chart-bar"></i> Ringkasan Performa
                </div>
                <div style="font-size: 0.95rem; font-weight: 500; line-height: 1.7;">
                    Dari <span style="color: #fbbf24;">{{ $aTotal }} konten</span>, rata-rata menghasilkan
                    <span style="color: #34d399;">{{ number_format($aAvgReach, 0) }} reach</span> dan
                    <span style="color: #f472b6;">{{ number_format($aAvgEng, 0) }} engagement</span> per konten,
                    dengan Engagement Rate keseluruhan <span style="color: #60a5fa;">{{ number_format($aEngRate, 2) }}%</span>.
                </div>
            </div>

        </div>
        @endif
    </div>
    --}}

</div>

{{-- ============================================================
     SCRIPT: Show/Hide Periode Input + Restore State
============================================================ --}}
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

    // ---- Bersihkan URL: nonaktifkan field periode yang tidak dipakai sebelum submit ----
    // Elemen yang `disabled` tidak ikut dikirim browser sebagai query string,
    // jadi field kosong dari periode lain (range/bulan/tahun) tidak lagi menumpuk di URL.
    (function() {
        var form = document.getElementById('analitikFilterForm');
        if (!form) return;

        var groups = {
            range: ['analitik_date_start', 'analitik_date_end'],
            bulan: ['analitik_bulan', 'analitik_tahun_bulan'],
            tahun: ['analitik_tahun']
        };

        form.addEventListener('submit', function() {
            var selected = document.getElementById('analitikPeriodeType').value;
            Object.keys(groups).forEach(function(key) {
                var isActive = (key === selected);
                groups[key].forEach(function(name) {
                    var el = form.querySelector('[name="' + name + '"]');
                    if (el) el.disabled = !isActive;
                });
            });
        });
    })();
</script>

{{-- ============================================================
     SCRIPT: Line Chart Kategori Konten
============================================================ --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctxKategori = document.getElementById('kategoriLineChart');
    if (!ctxKategori) return;

    @php
        $chartKategoriData = collect($categoryStats)->sortByDesc('reach')->map(function($stat, $name) {
            return [
                'name' => $name,
                'count' => $stat['count'],
                'rate' => $stat['rate'],
                'reach' => $stat['reach'],
                'eng' => $stat['eng']
            ];
        })->values()->toArray();
    @endphp

    const rawData = @json($chartKategoriData);
    
    if (rawData.length === 0) return;

    const labels = rawData.map(d => d.name);
    const countData = rawData.map(d => d.count);

    new Chart(ctxKategori, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Jumlah Konten',
                    data: countData,
                    yAxisID: 'yCount',
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderWidth: 2,
                    pointBackgroundColor: '#3b82f6',
                    pointBorderColor: '#fff',
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    tension: 0.3,
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        boxWidth: 8,
                        padding: 20,
                        font: { family: "'Inter', sans-serif", size: 11 }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(15, 23, 42, 0.9)',
                    titleFont: { family: "'Inter', sans-serif", size: 13, weight: '600' },
                    bodyFont: { family: "'Inter', sans-serif", size: 12 },
                    padding: 10,
                    cornerRadius: 8,
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            label += context.parsed.y.toLocaleString('id-ID');
                            return label;
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: {
                        font: { family: "'Inter', sans-serif", size: 11 },
                        color: '#64748b',
                        maxRotation: 45,
                        minRotation: 0
                    }
                },
                yCount: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    title: {
                        display: true,
                        text: 'Jumlah Konten',
                        font: { family: "'Inter', sans-serif", size: 10 },
                        color: '#64748b'
                    },
                    grid: { color: 'rgba(0,0,0,0.05)' },
                    ticks: {
                        font: { family: "'Inter', sans-serif", size: 10 },
                        color: '#64748b',
                        precision: 0
                    }
                }
            }
        }
    });
});
</script>
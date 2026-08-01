<div id="tab-laporan" class="dashboard-container">

    {{-- ============================================================
         SCOPED STYLES — Laporan (elegant refresh)
         Semua class di-prefix "lap-" agar tidak bentrok dengan tab lain.
    ============================================================ --}}
    <style>
        #tab-laporan{ --lap-ink:#0f172a; --lap-sub:#64748b; --lap-line:#e6e9ef; --lap-surface:#ffffff; --lap-bg:#f8fafc; --lap-primary:#4f46e5; --lap-primary-soft:#eef2ff; }

        #tab-laporan .lap-header{
            display:flex; flex-wrap:wrap; gap:1.25rem; align-items:flex-start; justify-content:space-between;
            padding-bottom:1.5rem; margin-bottom:1.75rem; border-bottom:1px solid var(--lap-line);
        }
        #tab-laporan .lap-header h1{ font-size:1.5rem; font-weight:700; color:var(--lap-ink); letter-spacing:-0.01em; margin:0 0 0.3rem; }
        #tab-laporan .lap-header p{ color:var(--lap-sub); font-size:0.9rem; margin:0; }

        #tab-laporan .lap-export{ display:flex; gap:0.6rem; flex-wrap:wrap; }
        #tab-laporan .lap-btn-export{
            display:inline-flex; align-items:center; gap:0.5rem; padding:0.55rem 1rem; border-radius:10px;
            font-size:0.85rem; font-weight:600; text-decoration:none; border:1px solid transparent;
            transition:transform .12s ease, box-shadow .12s ease, background-color .12s ease;
        }
        #tab-laporan .lap-btn-export:hover{ transform:translateY(-1px); }
        #tab-laporan .lap-btn-excel{ background:#ecfdf5; color:#047857; border-color:#bbf0da; }
        #tab-laporan .lap-btn-excel:hover{ background:#d9f9ec; box-shadow:0 4px 10px rgba(4,120,87,.12); }
        #tab-laporan .lap-btn-pdf{ background:#fef2f2; color:#b91c1c; border-color:#fbd6d6; }
        #tab-laporan .lap-btn-pdf:hover{ background:#fde3e3; box-shadow:0 4px 10px rgba(185,28,28,.12); }

        #tab-laporan .lap-filter-card{
            background:var(--lap-surface); border:1px solid var(--lap-line); border-radius:14px;
            padding:1.25rem 1.4rem; margin-bottom:1.75rem; box-shadow:0 1px 2px rgba(15,23,42,.03);
        }
        #tab-laporan .lap-filter-row{ display:flex; flex-wrap:wrap; gap:1.1rem; align-items:flex-end; }
        #tab-laporan .lap-field{ display:flex; flex-direction:column; gap:0.4rem; }
        #tab-laporan .lap-label{ font-size:0.72rem; font-weight:700; color:var(--lap-sub); text-transform:uppercase; letter-spacing:0.04em; }
        #tab-laporan .lap-input, #tab-laporan .lap-filter-row select, #tab-laporan .lap-filter-row input[type="date"]{
            border:1px solid var(--lap-line); border-radius:9px; padding:0.55rem 0.75rem; font-size:0.85rem;
            color:var(--lap-ink); background:var(--lap-bg); min-height:38px; transition:border-color .12s ease, box-shadow .12s ease;
        }
        #tab-laporan .lap-filter-row select:focus, #tab-laporan .lap-filter-row input:focus{
            outline:none; border-color:#c7d2fe; box-shadow:0 0 0 3px rgba(79,70,229,.12); background:#fff;
        }
        #tab-laporan .lap-search{
            display:flex; align-items:center; gap:0.5rem; border:1px solid var(--lap-line); border-radius:9px;
            padding:0 0.75rem; background:var(--lap-bg); min-height:38px; flex:1; min-width:220px;
        }
        #tab-laporan .lap-search:focus-within{ border-color:#c7d2fe; box-shadow:0 0 0 3px rgba(79,70,229,.12); background:#fff; }
        #tab-laporan .lap-search i{ color:#94a3b8; font-size:1rem; }
        #tab-laporan .lap-search input{ border:none; background:transparent; font-size:0.85rem; padding:0.55rem 0; width:100%; color:var(--lap-ink); }
        #tab-laporan .lap-search input:focus{ outline:none; }

        #tab-laporan .lap-btn-apply{
            display:inline-flex; align-items:center; gap:0.5rem; height:38px; padding:0 1.1rem; border-radius:9px;
            background:var(--lap-primary); color:#fff; font-weight:600; font-size:0.85rem; border:none; text-decoration:none;
            transition:background-color .12s ease, transform .12s ease;
        }
        #tab-laporan .lap-btn-apply:hover{ background:#4338ca; transform:translateY(-1px); }
        #tab-laporan .lap-btn-reset{
            display:inline-flex; align-items:center; height:38px; padding:0 1rem; border-radius:9px; text-decoration:none;
            color:var(--lap-sub); font-size:0.85rem; font-weight:600; border:1px solid var(--lap-line); background:#fff;
            transition:background-color .12s ease;
        }
        #tab-laporan .lap-btn-reset:hover{ background:var(--lap-bg); color:var(--lap-ink); }

        #tab-laporan .lap-stats{ display:grid; grid-template-columns:repeat(4, 1fr); gap:1rem; margin-bottom:1.75rem; }
        #tab-laporan .lap-stat-card{
            background:var(--lap-surface); border:1px solid var(--lap-line); border-radius:14px; padding:1.3rem 1.4rem;
            display:flex; align-items:flex-start; gap:1rem; box-shadow:0 1px 2px rgba(15,23,42,.03);
            transition:box-shadow .15s ease, transform .15s ease;
        }
        #tab-laporan .lap-stat-card:hover{ box-shadow:0 8px 20px rgba(15,23,42,.06); transform:translateY(-2px); }
        #tab-laporan .lap-stat-icon{
            width:42px; height:42px; border-radius:11px; display:flex; align-items:center; justify-content:center;
            font-size:1.15rem; flex-shrink:0;
        }
        #tab-laporan .lap-stat-icon.icon-indigo{ background:#eef2ff; color:#4f46e5; }
        #tab-laporan .lap-stat-icon.icon-sky{ background:#e0f2fe; color:#0284c7; }
        #tab-laporan .lap-stat-icon.icon-rose{ background:#ffe4e6; color:#e11d48; }
        #tab-laporan .lap-stat-icon.icon-emerald{ background:#d1fae5; color:#059669; }
        #tab-laporan .lap-stat-title{ font-size:0.74rem; font-weight:700; color:var(--lap-sub); text-transform:uppercase; letter-spacing:0.04em; margin-bottom:0.35rem; }
        #tab-laporan .lap-stat-value{ font-size:1.55rem; font-weight:700; color:var(--lap-ink); letter-spacing:-0.01em; line-height:1.1; }

        #tab-laporan .lap-table-card{ background:var(--lap-bg); border:1px solid var(--lap-line); border-radius:16px; padding:1.1rem; box-shadow:0 1px 2px rgba(15,23,42,.03); }

        /* Toolbar sortir — pengganti header tabel, boleh wrap ke bawah, tidak pernah scroll ke samping */
        #tab-laporan .lap-sort-bar{
            display:flex; flex-wrap:wrap; gap:0.5rem; align-items:center; padding:0.9rem 1.1rem;
            background:#fff; border:1px solid var(--lap-line); border-radius:13px; margin-bottom:1rem;
            box-shadow:0 1px 2px rgba(15,23,42,.03);
        }
        #tab-laporan .lap-sort-label{
            display:inline-flex; align-items:center; gap:0.35rem; font-size:0.72rem; font-weight:700; color:var(--lap-sub);
            text-transform:uppercase; letter-spacing:0.04em; margin-right:0.25rem;
        }
        #tab-laporan .lap-sort-chip{
            display:inline-flex; align-items:center; gap:0.3rem; padding:0.34rem 0.75rem; border-radius:999px; font-size:0.78rem;
            font-weight:600; color:var(--lap-sub); background:#fff; border:1px solid var(--lap-line); text-decoration:none;
            transition:background-color .12s ease, color .12s ease, border-color .12s ease, transform .12s ease;
        }
        #tab-laporan .lap-sort-chip:hover{ border-color:#c7d2fe; color:var(--lap-primary); transform:translateY(-1px); }
        #tab-laporan .lap-sort-chip.active{ background:var(--lap-primary); color:#fff; border-color:var(--lap-primary); box-shadow:0 4px 10px rgba(79,70,229,.25); }

        /* Daftar kartu — kartu mengambang, hanya scroll vertikal */
        #tab-laporan .lap-list{ display:flex; flex-direction:column; gap:0.85rem; }
        #tab-laporan .lap-row-card{
            position:relative; background:#fff; border:1px solid var(--lap-line); border-radius:14px;
            padding:1.15rem 1.3rem 1.15rem 1.5rem; box-shadow:0 1px 2px rgba(15,23,42,.04);
            transition:box-shadow .18s ease, transform .18s ease, border-color .18s ease;
        }
        #tab-laporan .lap-row-card::before{
            content:""; position:absolute; left:0; top:0.6rem; bottom:0.6rem; width:4px; border-radius:4px;
            background:var(--lap-accent, #cbd5e1);
        }
        #tab-laporan .lap-row-card:hover{ box-shadow:0 10px 26px rgba(15,23,42,.08); transform:translateY(-2px); border-color:#dfe3ec; }

        #tab-laporan .lap-row-top{ display:flex; flex-wrap:wrap; align-items:center; gap:0.7rem; margin-bottom:0.65rem; justify-content:space-between; }
        #tab-laporan .lap-row-top-left{ display:flex; flex-wrap:wrap; align-items:center; gap:0.7rem; }
        #tab-laporan .lap-row-date{ display:inline-flex; align-items:center; gap:0.35rem; font-size:0.78rem; color:var(--lap-sub); font-weight:600; }
        #tab-laporan .lap-row-date i{ font-size:0.95rem; color:#94a3b8; }
        #tab-laporan .lap-dot{ width:3px; height:3px; border-radius:50%; background:#cbd5e1; }
        #tab-laporan .lap-platform{ display:inline-flex; align-items:center; gap:0.45rem; font-weight:600; font-size:0.85rem; color:var(--lap-ink); }
        #tab-laporan .lap-platform-icon{
            width:24px; height:24px; border-radius:50%; display:inline-flex; align-items:center; justify-content:center;
            background:var(--lap-accent-soft, #f1f5f9); flex-shrink:0;
        }
        #tab-laporan .lap-platform-icon i{ font-size:0.85rem; }
        #tab-laporan .lap-pill{ display:inline-block; background:#f1f5f9; color:#475569; padding:0.22rem 0.7rem; border-radius:999px; font-size:0.72rem; font-weight:600; white-space:nowrap; }
        #tab-laporan .lap-jenis{ font-size:0.78rem; color:var(--lap-sub); }

        #tab-laporan .lap-row-title{ font-weight:700; color:var(--lap-ink); font-size:1rem; margin-bottom:0.9rem; word-break:break-word; }

        #tab-laporan .lap-metrics{
            display:grid; grid-template-columns:repeat(auto-fill, minmax(96px, 1fr)); gap:0.6rem;
            padding-top:0.9rem; border-top:1px solid var(--lap-line);
        }
        #tab-laporan .lap-metric-tile{ background:var(--lap-bg); border-radius:10px; padding:0.55rem 0.7rem; }
        #tab-laporan .lap-metric-label{ font-size:0.66rem; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:0.03em; margin-bottom:0.25rem; }
        #tab-laporan .lap-metric-value{ font-size:0.92rem; font-weight:700; color:var(--lap-ink); font-variant-numeric:tabular-nums; }

        #tab-laporan .lap-link{ display:inline-flex; align-items:center; gap:0.3rem; color:var(--lap-primary); text-decoration:none; font-weight:700; font-size:0.85rem; }
        #tab-laporan .lap-link:hover{ text-decoration:underline; }
        #tab-laporan .lap-er-pill{ display:inline-block; padding:0.12rem 0.55rem; border-radius:999px; font-weight:700; font-size:0.82rem; background:#e2e8f0; color:#475569; }
        #tab-laporan .lap-er-pill.good{ background:#d1fae5; color:#047857; }

        #tab-laporan .lap-empty{
            text-align:center; padding:3.5rem 1rem; color:var(--lap-sub); background:#fff; border:1px dashed var(--lap-line); border-radius:14px;
        }
        #tab-laporan .lap-empty i{ font-size:2.6rem; display:block; margin-bottom:0.85rem; color:#cbd5e1; }



        #tab-laporan .lap-pagination{
            margin-top:0.85rem; padding:0.9rem 1.25rem; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:0.75rem;
            background:#fff; border:1px solid var(--lap-line); border-radius:13px; font-size:0.83rem; color:var(--lap-sub);
        }
        #tab-laporan .lap-pagination b{ color:var(--lap-ink); }
        #tab-laporan .lap-page-nav{ display:flex; gap:0.5rem; align-items:center; }
        #tab-laporan .lap-page-btn{
            display:inline-flex; align-items:center; padding:0.35rem 0.85rem; font-size:0.78rem; font-weight:600; border-radius:8px;
            border:1px solid var(--lap-line); text-decoration:none; color:var(--lap-ink); background:#fff; transition:background-color .12s ease;
        }
        #tab-laporan .lap-page-btn:hover{ background:var(--lap-bg); }
        #tab-laporan .lap-page-btn[disabled]{ opacity:0.45; cursor:not-allowed; }
        #tab-laporan .lap-page-current{ font-weight:700; color:var(--lap-ink); padding:0 0.35rem; }

        @media (max-width: 960px){
            #tab-laporan .lap-stats{ grid-template-columns:repeat(2, 1fr); }
        }
        @media (max-width: 640px){
            #tab-laporan .lap-stats{ grid-template-columns:1fr; }
        }
    </style>

    {{-- ============================================================
         FILTER & EXPORT PANEL
    ============================================================ --}}
    <form method="GET" action="{{ route('dashboard') }}" id="laporanFilterForm">
        <input type="hidden" name="tab" value="laporan">
        <input type="hidden" name="lap_sort" value="{{ $lapFilters['sort'] }}">
        <input type="hidden" name="lap_dir" value="{{ $lapFilters['dir'] }}">

        <div class="lap-header">
            <div>
                <h1>Pusat Rekap Data</h1>
                <p>Pantau, cari, dan ekspor seluruh data performa konten secara terperinci.</p>
            </div>

            <div class="lap-export">
                <a href="{{ route('dashboard.export.excel', request()->all()) }}" class="lap-btn-export lap-btn-excel">
                    <i class="ph-bold ph-file-xls"></i> Export Excel
                </a>
                <a href="{{ route('dashboard.export.pdf', request()->all()) }}" class="lap-btn-export lap-btn-pdf">
                    <i class="ph-bold ph-file-pdf"></i> Export PDF
                </a>
            </div>
        </div>

        <div class="lap-filter-card">
            <div class="lap-filter-row">
                {{-- Filter Platform --}}
                <div class="lap-field">
                    <label class="lap-label">Platform</label>
                    <select name="lap_platform" style="min-width: 150px;">
                        <option value="all"      {{ ($lapFilters['platform'] ?? 'all') === 'all'       ? 'selected' : '' }}>Semua Platform</option>
                        <option value="instagram" {{ ($lapFilters['platform'] ?? '') === 'instagram'   ? 'selected' : '' }}>Instagram</option>
                        <option value="tiktok"    {{ ($lapFilters['platform'] ?? '') === 'tiktok'      ? 'selected' : '' }}>TikTok</option>
                        <option value="facebook"  {{ ($lapFilters['platform'] ?? '') === 'facebook'    ? 'selected' : '' }}>Facebook</option>
                        <option value="yt-live"   {{ ($lapFilters['platform'] ?? '') === 'yt-live'     ? 'selected' : '' }}>YouTube Live</option>
                        <option value="yt-video"  {{ ($lapFilters['platform'] ?? '') === 'yt-video'    ? 'selected' : '' }}>YouTube Video</option>
                        <option value="yt-shorts" {{ ($lapFilters['platform'] ?? '') === 'yt-shorts'   ? 'selected' : '' }}>YouTube Shorts</option>
                    </select>
                </div>

                {{-- Filter Jenis Periode --}}
                <div class="lap-field">
                    <label class="lap-label">Periode</label>
                    <select name="lap_periode_type" id="lapPeriodeType" onchange="showLapPeriodeInput(this.value)" style="min-width: 140px;">
                        <option value=""       {{ ($lapFilters['periode_type'] ?? '') === ''      ? 'selected' : '' }}>Semua Waktu</option>
                        <option value="range"  {{ ($lapFilters['periode_type'] ?? '') === 'range' ? 'selected' : '' }}>Rentang Tanggal</option>
                        <option value="bulan"  {{ ($lapFilters['periode_type'] ?? '') === 'bulan' ? 'selected' : '' }}>Bulan & Tahun</option>
                        <option value="tahun"  {{ ($lapFilters['periode_type'] ?? '') === 'tahun' ? 'selected' : '' }}>Tahun</option>
                    </select>
                </div>

                {{-- Input: Rentang Tanggal --}}
                <div id="lap-input-range" style="display: none; gap: 0.9rem; align-items: flex-end; flex-wrap: wrap;">
                    <div class="lap-field">
                        <label class="lap-label">Dari</label>
                        <input type="date" name="lap_date_start" value="{{ $lapFilters['date_start'] ?? '' }}">
                    </div>
                    <div class="lap-field">
                        <label class="lap-label">Sampai</label>
                        <input type="date" name="lap_date_end" value="{{ $lapFilters['date_end'] ?? '' }}">
                    </div>
                </div>

                {{-- Input: Bulan & Tahun --}}
                <div id="lap-input-bulan" style="display: none; gap: 0.9rem; align-items: flex-end; flex-wrap: wrap;">
                    <div class="lap-field">
                        <label class="lap-label">Bulan</label>
                        <select name="lap_bulan">
                            @foreach(['01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April','05'=>'Mei','06'=>'Juni','07'=>'Juli','08'=>'Agustus','09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'] as $num => $nama)
                            <option value="{{ $num }}" {{ ($lapFilters['bulan'] ?? '') === $num ? 'selected' : '' }}>{{ $nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="lap-field">
                        <label class="lap-label">Tahun</label>
                        <select name="lap_tahun_bulan">
                            @for($y = date('Y'); $y >= 2020; $y--)
                            <option value="{{ $y }}" {{ ($lapFilters['tahun_bulan'] ?? date('Y')) == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                </div>

                {{-- Input: Tahun --}}
                <div id="lap-input-tahun" style="display: none;">
                    <div class="lap-field">
                        <label class="lap-label">Tahun</label>
                        <select name="lap_tahun">
                            @for($y = date('Y'); $y >= 2020; $y--)
                            <option value="{{ $y }}" {{ ($lapFilters['tahun'] ?? date('Y')) == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                </div>

                {{-- Search Bar --}}
                <div class="lap-field" style="flex: 1; min-width: 220px;">
                    <label class="lap-label">Pencarian</label>
                    <div class="lap-search">
                        <i class="ph ph-magnifying-glass"></i>
                        <input type="text" name="lap_search" placeholder="Cari judul konten, platform, kategori..." value="{{ $lapFilters['search'] }}">
                    </div>
                </div>

                {{-- Tombol Terapkan --}}
                <button type="submit" class="lap-btn-apply">
                    <i class="ph-bold ph-funnel"></i> Terapkan Filter
                </button>
                <a href="{{ route('dashboard') }}?tab=laporan" class="lap-btn-reset">Reset</a>
            </div>
        </div>
    </form>

    {{-- ============================================================
         SUMMARY CARDS
    ============================================================ --}}
    <div class="lap-stats">
        <div class="lap-stat-card">
            <div class="lap-stat-icon icon-indigo"><i class="ph-fill ph-stack"></i></div>
            <div>
                <div class="lap-stat-title">Total Konten</div>
                <div class="lap-stat-value">{{ number_format($laporanAgg['total_konten']) }}</div>
            </div>
        </div>
        <div class="lap-stat-card">
            <div class="lap-stat-icon icon-sky"><i class="ph-fill ph-eye"></i></div>
            <div>
                <div class="lap-stat-title">Total Reach</div>
                <div class="lap-stat-value">{{ number_format($laporanAgg['total_reach']) }}</div>
            </div>
        </div>
        <div class="lap-stat-card">
            <div class="lap-stat-icon icon-rose"><i class="ph-fill ph-heart"></i></div>
            <div>
                <div class="lap-stat-title">Total Engagement</div>
                <div class="lap-stat-value">{{ number_format($laporanAgg['total_eng']) }}</div>
            </div>
        </div>
        <div class="lap-stat-card">
            <div class="lap-stat-icon icon-emerald"><i class="ph-fill ph-trend-up"></i></div>
            <div>
                <div class="lap-stat-title">Avg Engagement Rate</div>
                <div class="lap-stat-value">{{ number_format($laporanAgg['avg_er'], 2) }}%</div>
            </div>
        </div>
    </div>

    {{-- ============================================================
         DATA TABLE
    ============================================================ --}}
    <div class="lap-table-card">
        @php
            // Helper untuk URL sorting
            $buildSortUrl = function($column) use ($lapFilters) {
                $dir = ($lapFilters['sort'] === $column && $lapFilters['dir'] === 'asc') ? 'desc' : 'asc';
                $params = array_merge(request()->all(), ['tab' => 'laporan', 'lap_sort' => $column, 'lap_dir' => $dir]);
                return route('dashboard', $params);
            };
            $sortIcon = function($column) use ($lapFilters) {
                if ($lapFilters['sort'] !== $column) return '';
                return $lapFilters['dir'] === 'asc' ? '<i class="ph-bold ph-caret-up"></i>' : '<i class="ph-bold ph-caret-down"></i>';
            };
            $sortColumns = [
                'tgl_upload'     => 'Tgl Upload',
                'platform'       => 'Platform',
                'jenis'          => 'Jenis',
                'kategori'       => 'Kategori',
                'judul_konten'   => 'Judul Konten',
                'reach'          => 'Reach',
                'likes'          => 'Likes',
                'comments'       => 'Comments',
                'shares'         => 'Shares',
                'followers_plus' => 'Followers+',
            ];
        @endphp

        {{-- Toolbar Sortir — chip yang bisa wrap ke bawah, tidak pernah menggeser layar --}}
        <div class="lap-sort-bar">
            <span class="lap-sort-label"><i class="ph-bold ph-arrows-down-up"></i> Urutkan</span>
            @foreach($sortColumns as $col => $label)
            <a href="{{ $buildSortUrl($col) }}" class="lap-sort-chip {{ $lapFilters['sort'] === $col ? 'active' : '' }}">
                {{ $label }} {!! $sortIcon($col) !!}
            </a>
            @endforeach
        </div>

        {{-- Daftar Konten — layout kartu bertumpuk, hanya scroll vertikal --}}
        <div class="lap-list">
            @forelse($metricsLaporan as $row)
            @php
                $rowEng = $row->likes + $row->comments + $row->shares;
                $rowEr = $row->reach > 0 ? round(($rowEng / $row->reach) * 100, 2) : 0;

                $platformLower = strtolower($row->platform);
                if (str_contains($platformLower, 'instagram')) {
                    $pIcon = 'ph-instagram-logo'; $pColor = '#e1306c'; $pSoft = '#fce7f0';
                } elseif (str_contains($platformLower, 'tiktok')) {
                    $pIcon = 'ph-tiktok-logo'; $pColor = '#000000'; $pSoft = '#f1f5f9';
                } elseif (str_contains($platformLower, 'facebook')) {
                    $pIcon = 'ph-facebook-logo'; $pColor = '#1877f2'; $pSoft = '#e3edfd';
                } else {
                    $pIcon = 'ph-youtube-logo'; $pColor = '#ff0000'; $pSoft = '#fde2e2';
                }
            @endphp
            <div class="lap-row-card" style="--lap-accent: {{ $pColor }}; --lap-accent-soft: {{ $pSoft }};">
                <div class="lap-row-top">
                    <div class="lap-row-top-left">
                        <span class="lap-row-date"><i class="ph-bold ph-calendar-blank"></i> {{ $row->tgl_upload ? \Carbon\Carbon::parse($row->tgl_upload)->format('d M Y') : '-' }}</span>
                        <span class="lap-dot"></span>
                        <span class="lap-platform">
                            <span class="lap-platform-icon"><i class="ph-fill {{ $pIcon }}" style="color: {{ $pColor }};"></i></span>
                            {{ ucfirst($row->platform) }}
                        </span>
                        <span class="lap-dot"></span>
                        <span class="lap-jenis">{{ $row->jenis ?: '-' }}</span>
                    </div>
                    <span class="lap-pill">{{ $row->kategori ?: '-' }}</span>
                </div>

                <div class="lap-row-title">{{ $row->judul_konten ?: '-' }}</div>

                <div class="lap-metrics">
                    <div class="lap-metric-tile">
                        <div class="lap-metric-label">Reach</div>
                        <div class="lap-metric-value">{{ number_format($row->reach) }}</div>
                    </div>
                    <div class="lap-metric-tile">
                        <div class="lap-metric-label">Likes</div>
                        <div class="lap-metric-value">{{ number_format($row->likes) }}</div>
                    </div>
                    <div class="lap-metric-tile">
                        <div class="lap-metric-label">Comments</div>
                        <div class="lap-metric-value">{{ number_format($row->comments) }}</div>
                    </div>
                    <div class="lap-metric-tile">
                        <div class="lap-metric-label">Shares</div>
                        <div class="lap-metric-value">{{ number_format($row->shares) }}</div>
                    </div>
                    <div class="lap-metric-tile">
                        <div class="lap-metric-label">ER (%)</div>
                        <span class="lap-er-pill {{ $rowEr >= 3 ? 'good' : '' }}">{{ $rowEr }}%</span>
                    </div>
                    <div class="lap-metric-tile">
                        <div class="lap-metric-label">Followers+</div>
                        <div class="lap-metric-value">{{ number_format($row->followers_plus) }}</div>
                    </div>
                    <div class="lap-metric-tile">
                        <div class="lap-metric-label">Link</div>
                        @if($row->tautan)
                        <a href="{{ $row->tautan }}" target="_blank" class="lap-link"><i class="ph-bold ph-link"></i> Buka</a>
                        @else
                        <div class="lap-metric-value" style="font-weight:400; color:#94a3b8;">-</div>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="lap-empty">
                <i class="ph-fill ph-folder-open"></i>
                Tidak ada data yang ditemukan sesuai filter yang dipilih.
            </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="lap-pagination">
            <div>
                Menampilkan data <b>{{ $metricsLaporan->firstItem() ?? 0 }}</b> sampai <b>{{ $metricsLaporan->lastItem() ?? 0 }}</b> dari <b>{{ $metricsLaporan->total() }}</b> total data
            </div>

            <div class="lap-page-nav">
                @if ($metricsLaporan->onFirstPage())
                    <button class="lap-page-btn" disabled>Prev</button>
                @else
                    <a href="{{ $metricsLaporan->previousPageUrl() }}" class="lap-page-btn">Prev</a>
                @endif

                <span class="lap-page-current">Halaman {{ $metricsLaporan->currentPage() }} dari {{ $metricsLaporan->lastPage() ?: 1 }}</span>

                @if ($metricsLaporan->hasMorePages())
                    <a href="{{ $metricsLaporan->nextPageUrl() }}" class="lap-page-btn">Next</a>
                @else
                    <button class="lap-page-btn" disabled>Next</button>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    function showLapPeriodeInput(type) {
        ['range', 'bulan', 'tahun'].forEach(function(t) {
            var el = document.getElementById('lap-input-' + t);
            if (el) el.style.display = (t === type) ? 'flex' : 'none';
        });
    }

    // Auto show selected periode inputs on load
    (function() {
        var selected = document.getElementById('lapPeriodeType');
        if (selected) showLapPeriodeInput(selected.value);
    })();
</script>
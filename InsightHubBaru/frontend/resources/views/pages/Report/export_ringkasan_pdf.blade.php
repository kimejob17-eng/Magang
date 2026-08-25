@php
    $generatedTime = time();

    // ── Safety defaults ──────────────────────────────────────────
    $charts               = $charts               ?? [];
    $platformStats        = $platformStats        ?? [];
    $youtubeSubStats      = $youtubeSubStats      ?? [];
    $singlePlatformDistrib = $singlePlatformDistrib ?? [];
    $logoBase64           = $logoBase64           ?? null;
    $filterPlatform       = $filterInfo['platform'] ?? 'Semua Platform';
    $filterPeriode        = $filterInfo['periode']  ?? 'Semua';

    // ── Apakah ada grafik Base64 dari browser snapshot (User export) ──
    $hasCharts = !empty($charts['big']) || !empty($charts['small']) || !empty($charts['pie']);

    // ── Tentukan data chart SVG berdasar mode filter ──────────────
    // Mode: 'all'  = Semua Platform  → bar chart per platform (4 bar)
    // Mode: 'yt'   = YouTube         → bar chart per sub-jenis (Video/Shorts/Live)
    // Mode: 'single' = single non-YT → bar chart hanya 1 platform
    if ($filterPlatform === 'Semua Platform') {
        $chartMode   = 'all';
        $svgBars     = array_values($platformStats);  // Instagram, TikTok, Facebook, YouTube
        $svgBarLabels = array_keys($platformStats);
        $pieBars     = $svgBars; // distribusi konten per platform
    } elseif ($filterPlatform === 'YouTube') {
        $chartMode   = 'yt';
        $svgBars     = array_values($youtubeSubStats); // Video, Shorts, Live
        $svgBarLabels = array_keys($youtubeSubStats);
        $pieBars     = $svgBars;
    } else {
        $chartMode   = 'single';
        $singleData  = $platformStats[$filterPlatform] ?? ['label'=>$filterPlatform,'color'=>'#4f46e5','konten'=>0,'reach'=>0,'engagement'=>0,'likes'=>0,'comments'=>0,'shares'=>0];
        $svgBars     = [$singleData];
        $svgBarLabels = [$filterPlatform];
        $pieBars     = [];
    }


    // ── Helper: format angka ringkas (K / jt) ────────────────────
    // PENTING: Gunakan closure ($rk_fmt), bukan named function (function rk_fmt),
    // karena named function di @php block bisa menyebabkan
    // "Cannot redeclare rk_fmt()" fatal error jika view dikompilasi ulang.
    $rk_fmt = function($n) {
        $n = (int)$n;
        if ($n >= 1000000) return number_format($n / 1000000, 1) . 'jt';
        if ($n >= 1000)    return number_format($n / 1000, 1) . 'rb';
        return number_format($n);
    };


    // ── Cek apakah ada data sama sekali ──────────────────────────
    $hasSvgData = false;
    foreach ($svgBars as $b) {
        if (($b['reach'] ?? 0) > 0 || ($b['engagement'] ?? 0) > 0 || ($b['konten'] ?? 0) > 0) {
            $hasSvgData = true; break;
        }
    }

    // ── SVG helper: hitung max untuk scaling ─────────────────────
    $maxReach = max(1, ...array_map(fn($b) => $b['reach'] ?? 0, $svgBars ?: [['reach'=>1]]));
    $maxEng   = max(1, ...array_map(fn($b) => $b['engagement'] ?? 0, $svgBars ?: [['engagement'=>1]]));
    $maxBig   = max($maxReach, $maxEng, 1);
    $maxSmall = max(1,
        ...array_map(fn($b) => $b['likes']    ?? 0, $svgBars ?: [['likes'=>1]]),
        ...array_map(fn($b) => $b['comments'] ?? 0, $svgBars ?: [['comments'=>1]]),
        ...array_map(fn($b) => $b['shares']   ?? 0, $svgBars ?: [['shares'=>1]]),
        ...array_map(fn($b) => $b['saves']    ?? 0, $svgBars ?: [['saves'=>1]]),
        ...array_map(fn($b) => $b['repost']   ?? 0, $svgBars ?: [['repost'=>1]])
    );
    $totalPieKonten = max(1, array_sum(array_map(fn($b) => $b['konten'] ?? 0, $pieBars ?: [['konten'=>1]])));
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Ringkasan Performa Konten</title>
    <style>
        @page { margin: 40px 40px 60px 40px; }
        body, table, th, td, div, span, p, a {
            font-family: 'Helvetica', Arial, sans-serif !important;
            color: #1e293b;
        }
        footer {
            position: fixed;
            bottom: -30px; left: 0; right: 0;
            height: 20px;
            font-size: 10px; color: #64748b; text-align: center; padding-top: 8px;
        }
        .watermark {
            position: fixed; top: 45%; left: 0; width: 100%;
            text-align: center; font-size: 140px;
            color: rgba(226,232,240,0.4); transform: rotate(-30deg);
            z-index: -1000; font-weight: 900; letter-spacing: 25px;
        }

        /* ── Banner ── */
        .banner-container {
            background: #fff; border: 1px solid #e2e8f0;
            border-left: 6px solid #ef4444; border-radius: 8px;
            padding: 14px 18px;
        }
        .badge {
            background: #ef4444; color: #fff;
            padding: 3px 9px; font-size: 9px; font-weight: bold;
            border-radius: 4px; text-transform: uppercase;
        }
        .badge-id { color: #64748b; font-size: 10px; margin-left: 8px; }
        .title {
            margin: 10px 0 3px 0; font-size: 19px; font-weight: 800;
            color: #0f172a; line-height: 1.2; text-transform: uppercase;
        }
        .subtitle { color: #64748b; font-size: 11px; }
        .date-box {
            background: #fff; border: 1px solid #e2e8f0;
            padding: 10px 14px; border-radius: 6px; display: inline-block;
        }
        .date-text { font-size: 8px; color: #64748b; text-transform: uppercase; text-align: right; }
        .date-text strong { color: #0f172a; font-size: 11px; display: block; margin-top: 3px; }

        /* ── Filter box ── */
        .filter-box {
            background: #fff; border: 1px solid #e2e8f0;
            border-radius: 8px; padding: 10px 14px; margin-bottom: 18px;
        }
        .filter-table { width: 100%; table-layout: fixed; border-collapse: collapse; }
        .filter-table td { vertical-align: middle; }
        .filter-col-border { border-right: 1px solid #e2e8f0; }
        .filter-label { font-size: 8px; font-weight: bold; color: #64748b; margin-bottom: 3px; text-transform: uppercase; }
        .filter-value { font-size: 11px; color: #0f172a; font-weight: bold; }

        /* ── KPI Cards ── */
        .cards-container {
            width: 100%; margin-bottom: 16px;
            border-collapse: separate; border-spacing: 7px 0;
            margin-left: -7px; table-layout: fixed;
        }
        .card-td {
            background: #fff; border: 1px solid #e2e8f0;
            border-radius: 8px; padding: 11px 9px;
            vertical-align: middle; height: 58px; overflow: hidden;
        }
        .card-bg-1 { background: #f8fafc; border-color: #cbd5e1; }
        .card-bg-2 { background: #f0fdf4; border-color: #bbf7d0; }
        .card-bg-3 { background: #faf5ff; border-color: #e9d5ff; }
        .card-bg-4 { background: #fff7ed; border-color: #fed7aa; }
        .card-primary { background: #ef4444 !important; border-color: #ef4444 !important; }
        .card-title {
            font-size: 7px; font-weight: bold; color: #64748b;
            margin-bottom: 4px; text-transform: uppercase; line-height: 1.2; word-wrap: break-word;
        }
        .card-primary .card-title { color: #fee2e2; }
        .card-value { font-size: 15px; font-weight: bold; color: #0f172a; }
        .card-primary .card-value { color: #fff; }

        /* ── Section ── */
        .section-title {
            font-size: 10px; font-weight: bold; color: #1e293b;
            margin: 16px 0 10px 0; text-transform: uppercase;
            letter-spacing: 0.5px; border-left: 4px solid #ef4444; padding-left: 8px;
        }

        /* ── Chart boxes ── */
        .chart-row   { width: 100%; border-collapse: collapse; }
        .chart-cell  { padding: 5px; vertical-align: top; }
        .chart-box   {
            border: 1px solid #e2e8f0; border-radius: 6px;
            padding: 10px 12px; background: #fafafa;
        }
        .chart-label {
            font-size: 8px; font-weight: bold; color: #64748b;
            text-transform: uppercase; margin-bottom: 6px; letter-spacing: 0.3px;
        }
        .chart-img   { width: 100%; height: auto; display: block; }

        /* ── Data table ── */
        .plat-table { width: 100%; border-collapse: collapse; font-size: 9px; }
        .plat-table th {
            background: #ef4444; color: #fff; font-size: 8px; font-weight: bold;
            text-transform: uppercase; padding: 6px 5px; text-align: center;
        }
        .plat-table td {
            padding: 6px 5px; border-bottom: 1px solid #f1f5f9;
            text-align: center; vertical-align: middle; color: #334155;
        }
        .plat-table tr:nth-child(even) td { background: #f8fafc; }
        .plat-table td:first-child { text-align: left; font-weight: 600; color: #0f172a; }
        .plat-table tfoot td { background: #f1f5f9; font-weight: bold; color: #0f172a; }

        /* ── Footer note ── */
        .footer-note {
            margin-top: 28px; font-size: 9px; color: #94a3b8;
            text-align: center; border-top: 1px solid #e2e8f0; padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="watermark">RINGKASAN</div>
    <footer>Dicetak pada: {{ date('d M Y H:i', $generatedTime) }} &nbsp;|&nbsp; InsightHub v2.0</footer>

    {{-- ============================================================
         HEADER: LOGO + BANNER + DATE
    ============================================================ --}}
    {{-- Logo Di Atas --}}
    <div style="margin-bottom: 12px;">
        @if($logoBase64)
            <div style="background-color:#1e293b; padding:10px 14px; display:inline-block; border-radius:8px;">
                <img src="{{ $logoBase64 }}" width="100" alt="Logo Kementerian" style="display:block;">
            </div>
        @endif
    </div>

    {{-- Banner judul & Tanggal dalam satu area --}}
    <table style="width:100%; border-collapse:collapse; margin-bottom:20px; background-color:#ffffff; border:1px solid #e2e8f0; border-radius:8px;">
        <tr>
            <td style="padding:16px; width:70%;">
                <div style="margin-bottom:6px;">
                    <span class="badge" style="background-color:#fee2e2; color:#b91c1c; border:none; border-radius:4px;">DOKUMEN RESMI</span>
                    <span class="badge-id" style="border:none; background:none;">ID: RPK-{{ date('Ymd', $generatedTime) }}-{{ substr(md5($generatedTime), 0, 5) }}</span>
                </div>
                <h1 class="title" style="margin:4px 0 2px 0; text-transform:uppercase;">Ringkasan Performa Konten</h1>
                <div class="subtitle">Data analitik agregat dari platform media sosial InsightHub.</div>
            </td>
            <td style="padding:16px; width:30%; text-align:right; border-left:1px dashed #e2e8f0; vertical-align:middle;">
                <div style="font-size:9px; color:#64748b; text-transform:uppercase; font-weight:bold; letter-spacing:0.5px; margin-bottom:4px;">
                    Tanggal Dibuat
                </div>
                <div style="font-size:12px; color:#0f172a; font-weight:bold;">
                    {{ strtoupper(date('d F Y', $generatedTime)) }}
                </div>
                <div style="font-size:10px; color:#475569; margin-top:2px;">
                    {{ date('H:i', $generatedTime) }} WIB
                </div>
            </td>
        </tr>
    </table>

    {{-- ============================================================
         FILTER INFO
    ============================================================ --}}
    <div class="filter-box">
        <table class="filter-table">
            <tr>
                <td style="width:50%;" class="filter-col-border">
                    <table style="width:100%;"><tr><td style="padding-left:10px;">
                        <div class="filter-label">Filter Platform</div>
                        <div class="filter-value">{{ $filterPlatform }}</div>
                    </td></tr></table>
                </td>
                <td style="width:50%;">
                    <table style="width:100%;"><tr><td style="padding-left:18px;">
                        <div class="filter-label">Periode Waktu</div>
                        <div class="filter-value">{{ $filterPeriode === 'Semua' ? 'Semua Waktu' : ucfirst($filterPeriode) }}</div>
                    </td></tr></table>
                </td>
            </tr>
        </table>
    </div>

    {{-- ============================================================
         KPI CARDS ROW 1
    ============================================================ --}}
    <div class="section-title">Statistik Utama</div>
    <table class="cards-container">
        <tr>
            <td class="card-td card-primary">
                <div class="card-title">TOTAL KONTEN</div>
                <div class="card-value">{{ number_format($agg['total_konten'] ?? 0, 0, ',', '.') }}</div>
            </td>
            <td class="card-td card-bg-1">
                <div class="card-title">TOTAL REACH / VIEWS</div>
                <div class="card-value">{{ number_format($agg['total_reach'] ?? 0, 0, ',', '.') }}</div>
            </td>
            <td class="card-td card-bg-2">
                <div class="card-title">TOTAL ENGAGEMENT</div>
                <div class="card-value">{{ number_format($agg['total_eng'] ?? 0, 0, ',', '.') }}</div>
            </td>
            <td class="card-td card-bg-3">
                <div class="card-title">AVG ENGAGEMENT RATE</div>
                <div class="card-value">{{ number_format($agg['avg_er'] ?? 0, 2, ',', '.') }}%</div>
            </td>
        </tr>
    </table>

    {{-- KPI CARDS ROW 2 --}}
    <table class="cards-container" style="margin-top:-4px;">
        <tr>
            <td class="card-td card-bg-4">
                <div class="card-title">PERTUMBUHAN FOLLOWERS</div>
                <div class="card-value">+{{ number_format($agg['followers_plus'] ?? 0, 0, ',', '.') }}</div>
            </td>
            <td class="card-td card-bg-1">
                <div class="card-title">TOTAL AKUN FOLLOWERS</div>
                <div class="card-value">{{ number_format($agg['total_akun_followers'] ?? 0, 0, ',', '.') }}</div>
            </td>
            <td style="width:50%;"></td>
        </tr>
    </table>

    {{-- ============================================================
         VISUALISASI GRAFIK
         Prioritas: Base64 dari browser snapshot → SVG server-side
    ============================================================ --}}
    <div class="section-title">Visualisasi Grafik</div>

    @if($hasCharts)
    {{-- ── GRAFIK BASE64 (User export setelah approved) ──────── --}}
    @if(!empty($charts['big']) || !empty($charts['small']))
    <table class="chart-row" style="margin-bottom:12px;">
        <tr>
            @if(!empty($charts['big']))
            <td class="chart-cell" style="width:{{ empty($charts['small']) ? '100%' : '50%' }};">
                <div class="chart-box">
                    <div class="chart-label">Metrik Jangkauan — Reach &amp; Engagement</div>
                    <img src="{{ $charts['big'] }}" class="chart-img" alt="Grafik Reach dan Engagement">
                </div>
            </td>
            @endif
            @if(!empty($charts['small']))
            <td class="chart-cell" style="width:{{ empty($charts['big']) ? '100%' : '50%' }};">
                <div class="chart-box">
                    <div class="chart-label">Metrik Interaksi — Likes, Comments, Shares, Repost</div>
                    <img src="{{ $charts['small'] }}" class="chart-img" alt="Grafik Interaksi">
                </div>
            </td>
            @endif
        </tr>
    </table>
    @endif

    @if(!empty($charts['pie']))
    <table class="chart-row" style="margin-bottom:12px;">
        <tr>
            <td class="chart-cell" style="width:55%;">
                <div class="chart-box">
                    <div class="chart-label">Distribusi Konten per Platform / Jenis</div>
                    <img src="{{ $charts['pie'] }}" class="chart-img" alt="Grafik Distribusi Konten">
                </div>
            </td>
            <td class="chart-cell" style="width:45%;"></td>
        </tr>
    </table>
    @endif

    @elseif($hasSvgData)
    {{-- ── GRAFIK SVG SERVER-SIDE (Admin direct export) ──────── --}}
    {{-- Konsisten 100% dengan filter: data berasal dari query yang sama --}}
    @php
        $svgW   = 480; // viewBox width
        $svgH   = 130; // bar area height
        $n      = count($svgBars);
        $groupW = $n > 0 ? $svgW / $n : $svgW;
        $barW   = $groupW * 0.28;
        $gap    = $groupW * 0.06;
        // Warna interaksi (Likes/Comments/Shares/Saves/Repost)
        $smColors = ['#f43f5e', '#3b82f6', '#10b981', '#eab308', '#ec4899'];
        $smKeys   = ['likes','comments','shares','saves','repost'];
        $smLabels = ['Likes','Comments','Shares','Save','Repost'];
        $smBW     = $groupW * 0.12;
        $smGap    = $groupW * 0.02;
    @endphp

    {{-- BARIS 1: Bar Reach & Engagement | Bar Likes/Comments/Shares --}}
    <table class="chart-row" style="margin-bottom:12px;">
        <tr>
            {{-- Bar Chart 1: Reach & Engagement --}}
            <td class="chart-cell" style="width:50%;">
                <div class="chart-box">
                    <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom: 8px;">
                        <div>
                            <div style="font-size:12px; font-weight:bold; color:#0f172a;">Metrik Jangkauan (Besar)</div>
                            <div style="font-size:9px; color:#64748b;">
                                @if($chartMode === 'all') Semua platform - data all-time
                                @elseif($chartMode === 'yt') Per Jenis YouTube
                                @else {{ $filterPlatform }}
                                @endif
                            </div>
                        </div>
                        <div style="font-size:8px; color:#6366f1; background:#e0e7ff; padding:2px 6px; border-radius:4px; font-weight:bold;">
                            Reach · Engagement
                        </div>
                    </div>
                    @php ob_start(); @endphp
                    <svg xmlns="http://www.w3.org/2000/svg"
                         width="{{ $svgW }}" height="{{ $svgH + 48 }}"
                         viewBox="0 0 {{ $svgW }} {{ $svgH + 48 }}">
                        {{-- Grid lines (5 levels) --}}
                        @for($gi = 0; $gi <= 4; $gi++)
                            @php $gy = $svgH - ($gi / 4) * $svgH; @endphp
                            <line x1="0" y1="{{ $gy }}" x2="{{ $svgW }}" y2="{{ $gy }}"
                                  stroke="#e2e8f0" stroke-width="0.6"/>
                            <text x="2" y="{{ $gy - 2 }}" font-size="7" fill="#94a3b8">{{ $rk_fmt(($gi/4)*$maxBig) }}</text>
                        @endfor

                        {{-- Bars --}}
                        @foreach($svgBars as $bi => $bar)
                            @php
                                $cx  = $bi * $groupW + $groupW / 2;
                                $hR  = $maxBig > 0 ? ($bar['reach'] / $maxBig) * $svgH : 0;
                                $hE  = $maxBig > 0 ? ($bar['engagement'] / $maxBig) * $svgH : 0;
                                $x1  = $cx - $barW - $gap / 2;
                                $x2  = $cx + $gap / 2;
                                $col = $bar['color'] ?? '#4f46e5';
                            @endphp
                            <rect x="{{ $x1 }}" y="{{ $svgH - $hR }}"
                                  width="{{ $barW }}" height="{{ max(1, $hR) }}"
                                  fill="{{ $col }}"/>
                            <rect x="{{ $x2 }}" y="{{ $svgH - $hE }}"
                                  width="{{ $barW }}" height="{{ max(1, $hE) }}"
                                  fill="#8b5cf6"/>
                            {{-- Nilai di atas bar reach --}}
                            @if($hR > 14)
                            <text x="{{ $x1 + $barW/2 }}" y="{{ $svgH - $hR - 2 }}"
                                  font-size="6" fill="{{ $col }}" text-anchor="middle">{{ $rk_fmt($bar['reach']) }}</text>
                            @endif
                            {{-- Label platform --}}
                            <text x="{{ $cx }}" y="{{ $svgH + 11 }}"
                                  font-size="8" fill="#475569" text-anchor="middle">{{ $bar['label'] }}</text>
                        @endforeach

                        {{-- Legend --}}
                        <rect x="8"  y="{{ $svgH + 26 }}" width="10" height="8" fill="#e1306c"/>
                        <text x="22" y="{{ $svgH + 34 }}" font-size="8" fill="#475569">Reach</text>
                        <rect x="72" y="{{ $svgH + 26 }}" width="10" height="8" fill="#8b5cf6"/>
                        <text x="86" y="{{ $svgH + 34 }}" font-size="8" fill="#475569">Engagement</text>
                    </svg>
                    @php
                        $svg1 = ob_get_clean();
                        $svg1Base64 = 'data:image/svg+xml;base64,' . base64_encode($svg1);
                    @endphp
                    <img src="{{ $svg1Base64 }}" style="width:100%;height:auto;display:block;" alt="Chart Reach">
                </div>
            </td>

            {{-- Bar Chart 2: Likes / Comments / Shares --}}
            <td class="chart-cell" style="width:50%;">
                <div class="chart-box">
                    <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom: 8px;">
                        <div>
                            <div style="font-size:12px; font-weight:bold; color:#0f172a;">Metrik Interaksi</div>
                            <div style="font-size:9px; color:#64748b;">
                                @if($chartMode === 'all') Likes - Comments - Shares - Save - Repost per platform
                                @elseif($chartMode === 'yt') Interaksi per Jenis YouTube
                                @else Interaksi — {{ $filterPlatform }}
                                @endif
                            </div>
                        </div>
                        <div style="font-size:8px; color:#c026d3; background:#fae8ff; padding:2px 6px; border-radius:4px; font-weight:bold;">
                            Sumber data sama dengan grafik di atas
                        </div>
                    </div>
                    @php ob_start(); @endphp
                    <svg xmlns="http://www.w3.org/2000/svg"
                         width="{{ $svgW }}" height="{{ $svgH + 50 }}"
                         viewBox="0 0 {{ $svgW }} {{ $svgH + 50 }}">
                        @for($gi = 0; $gi <= 4; $gi++)
                            @php $gy = $svgH - ($gi / 4) * $svgH; @endphp
                            <line x1="0" y1="{{ $gy }}" x2="{{ $svgW }}" y2="{{ $gy }}"
                                  stroke="#e2e8f0" stroke-width="0.6"/>
                            <text x="2" y="{{ $gy - 3 }}" font-size="9" fill="#94a3b8">{{ $rk_fmt(($gi/4)*$maxSmall) }}</text>
                        @endfor

                        @foreach($svgBars as $bi => $bar)
                            @php
                                $cx     = $bi * $groupW + $groupW / 2;
                                $startX = $cx - (count($smKeys) * ($smBW + $smGap)) / 2;
                            @endphp
                            @foreach($smKeys as $ki => $key)
                                @php
                                    $val = $bar[$key] ?? 0;
                                    $h   = $maxSmall > 0 ? ($val / $maxSmall) * $svgH : 0;
                                    $bx  = $startX + $ki * ($smBW + $smGap);
                                @endphp
                                <rect x="{{ $bx }}" y="{{ $svgH - $h }}"
                                      width="{{ $smBW }}" height="{{ max(1, $h) }}"
                                      fill="{{ $smColors[$ki] }}"/>
                            @endforeach
                            <text x="{{ $cx }}" y="{{ $svgH + 14 }}"
                                  font-size="10" fill="#475569" text-anchor="middle">{{ $bar['label'] }}</text>
                        @endforeach

                        {{-- Legend --}}
                        @php
                            // Distribusi otomatis agar tidak bertabrakan
                            $legW = $svgW / count($smLabels);
                        @endphp
                        @foreach($smLabels as $li => $lbl)
                            @php $lx = 8 + $li * $legW; @endphp
                            <rect x="{{ $lx }}" y="{{ $svgH + 30 }}" width="10" height="10" fill="{{ $smColors[$li] }}"/>
                            <text x="{{ $lx + 14 }}" y="{{ $svgH + 38 }}" font-size="9" fill="#475569">{{ $lbl }}</text>
                        @endforeach
                    </svg>
                    @php
                        $svg2 = ob_get_clean();
                        $svg2Base64 = 'data:image/svg+xml;base64,' . base64_encode($svg2);
                    @endphp
                    <img src="{{ $svg2Base64 }}" style="width:100%;height:auto;display:block;" alt="Chart Interaksi">
                </div>
            </td>
        </tr>
    </table>

    {{-- BARIS 2: Distribusi Konten (Horizontal Bar) | Tabel Ringkasan --}}
    <table class="chart-row" style="margin-bottom:12px;">
        <tr>
            {{-- Distribusi Konten (Horizontal Bar atau Jenis Konten) --}}
            <td class="chart-cell" style="width:50%;">
                <div class="chart-box">
                    @if($chartMode === 'single' && count($singlePlatformDistrib) > 0)
                        {{-- Mode single platform: distribusi jenis konten --}}
                        <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom: 8px;">
                            <div>
                                <div style="font-size:12px; font-weight:bold; color:#0f172a;">Distribusi Konten</div>
                                <div style="font-size:9px; color:#64748b;">
                                    Distribusi jenis konten — {{ $filterPlatform }}
                                </div>
                            </div>
                        </div>
                        @php
                            $distTotal = max(1, array_sum($singlePlatformDistrib));
                            $distColors = ['#e1306c','#1877f2','#ff0000','#10b981','#f59e0b','#8b5cf6','#06b6d4','#f43f5e'];
                            $svg3W = 460;
                            $svg3H = 180;
                            $cx = 150;
                            $cy = 90;
                            $r = 75;
                            $currentAngle = -90;
                            $di = 0;
                            ob_start(); 
                        @endphp
                        <svg xmlns="http://www.w3.org/2000/svg" width="{{ $svg3W }}" height="{{ $svg3H }}" viewBox="0 0 {{ $svg3W }} {{ $svg3H }}">
                            @foreach($singlePlatformDistrib as $jenis => $jCount)
                                @php
                                    $pct = $distTotal > 0 ? $jCount / $distTotal : 0;
                                    if ($pct == 0) continue;
                                    $angle = $pct * 360;
                                    $endAngle = $currentAngle + $angle;
                                    $startRad = deg2rad($currentAngle);
                                    $endRad = deg2rad($endAngle);
                                    
                                    $x1 = $cx + $r * cos($startRad);
                                    $y1 = $cy + $r * sin($startRad);
                                    $x2 = $cx + $r * cos($endRad);
                                    $y2 = $cy + $r * sin($endRad);
                                    
                                    $largeArc = $angle > 180 ? 1 : 0;
                                    $col = $distColors[$di % count($distColors)];
                                    $isFull = $pct >= 0.999;
                                    $legY = 30 + $di * 16;
                                @endphp
                                @if($isFull)
                                    <circle cx="{{ $cx }}" cy="{{ $cy }}" r="{{ $r }}" fill="{{ $col }}" />
                                @else
                                    <path d="M {{ $cx }} {{ $cy }} L {{ $x1 }} {{ $y1 }} A {{ $r }} {{ $r }} 0 {{ $largeArc }} 1 {{ $x2 }} {{ $y2 }} Z" fill="{{ $col }}" stroke="#fff" stroke-width="1.5" />
                                @endif
                                
                                @php
                                    $midAngle = $currentAngle + $angle / 2;
                                    $midRad = deg2rad($midAngle);
                                    $lx = $cx + ($r * 0.55) * cos($midRad);
                                    $ly = $cy + ($r * 0.55) * sin($midRad);
                                @endphp
                                @if($pct > 0.05)
                                    <text x="{{ $lx }}" y="{{ $ly }}" fill="#fff" font-size="8" font-weight="bold" text-anchor="middle" dominant-baseline="middle">{{ number_format($pct*100, 1) }}%</text>
                                @endif
                                
                                <rect x="280" y="{{ $legY - 5 }}" width="8" height="8" fill="{{ $col }}" />
                                <text x="294" y="{{ $legY + 2 }}" font-size="8" fill="#475569">{{ $jenis }} ({{ $jCount }})</text>
                                
                                @php 
                                    $currentAngle += $angle; 
                                    $di++;
                                @endphp
                            @endforeach
                        </svg>
                        @php
                            $svg3 = ob_get_clean();
                            $svg3Base64 = 'data:image/svg+xml;base64,' . base64_encode($svg3);
                        @endphp
                        <img src="{{ $svg3Base64 }}" style="width:100%;height:auto;display:block;" alt="Chart Distribusi 1">
                    @elseif($chartMode !== 'single' && count($pieBars) > 0)
                        {{-- Mode semua / YouTube: distribusi jumlah konten per platform/jenis --}}
                        <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom: 8px;">
                            <div>
                                <div style="font-size:12px; font-weight:bold; color:#0f172a;">Distribusi Konten</div>
                                <div style="font-size:9px; color:#64748b;">
                                    Distribusi jumlah konten per {{ $chartMode === 'yt' ? 'Jenis YouTube' : 'platform' }}
                                </div>
                            </div>
                        </div>
                        @php 
                            $svg4W = 460;
                            $svg4H = 180;
                            $cx = 150;
                            $cy = 90;
                            $r = 75;
                            $currentAngle = -90;
                            ob_start(); 
                        @endphp
                        <svg xmlns="http://www.w3.org/2000/svg" width="{{ $svg4W }}" height="{{ $svg4H }}" viewBox="0 0 {{ $svg4W }} {{ $svg4H }}">
                            @foreach($pieBars as $bi => $bar)
                                @php
                                    $pct = $totalPieKonten > 0 ? ($bar['konten'] ?? 0) / $totalPieKonten : 0;
                                    if ($pct == 0) continue;
                                    $angle = $pct * 360;
                                    $endAngle = $currentAngle + $angle;
                                    $startRad = deg2rad($currentAngle);
                                    $endRad = deg2rad($endAngle);
                                    
                                    $x1 = $cx + $r * cos($startRad);
                                    $y1 = $cy + $r * sin($startRad);
                                    $x2 = $cx + $r * cos($endRad);
                                    $y2 = $cy + $r * sin($endRad);
                                    
                                    $largeArc = $angle > 180 ? 1 : 0;
                                    $col = $bar['color'] ?? '#4f46e5';
                                    $isFull = $pct >= 0.999;
                                    $legY = 30 + $bi * 16;
                                @endphp
                                @if($isFull)
                                    <circle cx="{{ $cx }}" cy="{{ $cy }}" r="{{ $r }}" fill="{{ $col }}" />
                                @else
                                    <path d="M {{ $cx }} {{ $cy }} L {{ $x1 }} {{ $y1 }} A {{ $r }} {{ $r }} 0 {{ $largeArc }} 1 {{ $x2 }} {{ $y2 }} Z" fill="{{ $col }}" stroke="#fff" stroke-width="1.5" />
                                @endif
                                
                                @php
                                    $midAngle = $currentAngle + $angle / 2;
                                    $midRad = deg2rad($midAngle);
                                    $lx = $cx + ($r * 0.55) * cos($midRad);
                                    $ly = $cy + ($r * 0.55) * sin($midRad);
                                @endphp
                                @if($pct > 0.05)
                                    <text x="{{ $lx }}" y="{{ $ly }}" fill="#fff" font-size="8" font-weight="bold" text-anchor="middle" dominant-baseline="middle">{{ number_format($pct*100, 1) }}%</text>
                                @endif
                                
                                <rect x="280" y="{{ $legY - 5 }}" width="8" height="8" fill="{{ $col }}" />
                                <text x="294" y="{{ $legY + 2 }}" font-size="8" fill="#475569">{{ $bar['label'] }} ({{ number_format($pct*100, 1) }}%)</text>
                                
                                @php $currentAngle += $angle; @endphp
                            @endforeach
                        </svg>
                        @php
                            $svg4 = ob_get_clean();
                            $svg4Base64 = 'data:image/svg+xml;base64,' . base64_encode($svg4);
                        @endphp
                        <img src="{{ $svg4Base64 }}" style="width:100%;height:auto;display:block;" alt="Chart Distribusi 2">
                    @else
                        <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom: 8px;">
                        <div>
                            <div style="font-size:12px; font-weight:bold; color:#0f172a;">Distribusi Konten</div>
                            <div style="font-size:9px; color:#64748b;">
                                Distribusi jumlah konten per platform
                            </div>
                        </div>
                    </div>
                        <div style="font-size:9px; color:#94a3b8; text-align:center; padding:16px; font-style:italic;">
                            Tidak ada data distribusi tersedia.
                        </div>
                    @endif
                </div>
            </td>

            {{-- Tabel Ringkasan Per Platform / Jenis --}}
            <td class="chart-cell" style="width:50%;">
                <div class="chart-box">
                    <div class="chart-label">Tabel Ringkasan —
                        {{ $chartMode === 'yt' ? 'Per Jenis YouTube' : ($chartMode === 'single' ? $filterPlatform : 'Per Platform') }}
                    </div>
                    <table class="plat-table">
                        <thead>
                            <tr>
                                <th style="text-align:left;">
                                    {{ $chartMode === 'yt' ? 'Jenis' : 'Platform' }}
                                </th>
                                <th>Konten</th>
                                <th>Reach</th>
                                <th>Engagement</th>
                                <th>Eng. Rate</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($svgBars as $bar)
                            @php
                                $r = $bar['reach'] ?? 0;
                                $e = $bar['engagement'] ?? 0;
                                $er = $r > 0 ? ($e / $r) * 100 : 0;
                            @endphp
                            <tr>
                                <td>{{ $bar['label'] }}</td>
                                <td>{{ number_format($bar['konten'] ?? 0) }}</td>
                                <td>{{ $rk_fmt($r) }}</td>
                                <td>{{ $rk_fmt($e) }}</td>
                                <td>{{ number_format($er, 2, ',', '.') }}%</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td>Total</td>
                                <td>{{ number_format($agg['total_konten'] ?? 0) }}</td>
                                <td>{{ $rk_fmt($agg['total_reach'] ?? 0) }}</td>
                                <td>{{ $rk_fmt($agg['total_eng'] ?? 0) }}</td>
                                <td>{{ number_format($agg['avg_er'] ?? 0, 2, ',', '.') }}%</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </td>
        </tr>
    </table>

    @else
    {{-- Tidak ada data sama sekali --}}
    <div style="border:1px dashed #cbd5e1; border-radius:6px; padding:18px; text-align:center; margin-top:14px;">
        <div style="font-size:9px; color:#94a3b8; font-style:italic;">
            Tidak ada data tersedia untuk filter yang dipilih.
        </div>
    </div>
    @endif

    {{-- ============================================================
         FOOTER NOTE
    ============================================================ --}}
    <div class="footer-note">
        Dokumen ini dihasilkan secara otomatis oleh sistem InsightHub &mdash;
        SOVIE AI Analytics. Data bersumber dari kondisi filter pada saat permintaan ekspor dibuat.
    </div>

</body>
</html>

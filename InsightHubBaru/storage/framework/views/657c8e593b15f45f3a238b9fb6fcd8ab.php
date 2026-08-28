<?php
    $generatedTime = time();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Performa Konten</title>
    <style>
        @page {
            margin: 40px 40px 60px 40px;
        }
        body, table, th, td, div, span, p, a {
            font-family: 'Helvetica', Arial, sans-serif !important;
            color: #1e293b;
        }
        footer {
            position: fixed;
            bottom: -30px;
            left: 0px;
            right: 0px;
            height: 20px;
            font-size: 10px;
            color: #64748b;
            text-align: center;
            padding-top: 8px;
        }
        .watermark {
            position: fixed;
            top: 45%;
            left: 0;
            width: 100%;
            text-align: center;
            font-size: 140px;
            color: rgba(226, 232, 240, 0.4);
            transform: rotate(-30deg);
            z-index: -1000;
            font-weight: 900;
            letter-spacing: 25px;
        }
        /* Top Banner */
        .banner-container {
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            border-left: 6px solid #2563eb;
            border-radius: 8px;
            padding: 15px 20px;
            margin-bottom: 20px;
        }
        .badge {
            background-color: #2563eb;
            color: #fff;
            padding: 4px 10px;
            font-size: 9px;
            font-weight: bold;
            border-radius: 4px;
            text-transform: uppercase;
        }
        .badge-id {
            color: #64748b;
            font-size: 10px;
            margin-left: 10px;
        }
        .title {
            margin: 12px 0 4px 0;
            font-size: 20px;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.2;
            text-transform: uppercase;
        }
        .subtitle {
            color: #64748b;
            font-size: 11px;
        }
        .date-box {
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            padding: 10px 15px;
            border-radius: 6px;
            display: inline-block;
        }
        .date-text {
            font-size: 8px;
            color: #64748b;
            text-transform: uppercase;
            text-align: right;
        }
        .date-text strong {
            color: #0f172a;
            font-size: 11px;
            display: block;
            margin-top: 4px;
        }

        /* Filter Box */
        .filter-box {
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 12px 15px;
            margin-bottom: 20px;
        }
        .filter-table {
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
        }
        .filter-table td {
            vertical-align: middle;
        }
        .filter-col-border {
            border-right: 1px solid #e2e8f0;
        }
        .filter-label {
            font-size: 8px;
            font-weight: bold;
            color: #64748b;
            margin-bottom: 4px;
            text-transform: uppercase;
        }
        .filter-value {
            font-size: 11px;
            color: #0f172a;
            font-weight: bold;
        }
        .filter-icon-box {
            width: 32px;
            height: 32px;
            background-color: #eff6ff;
            border-radius: 8px;
            text-align: center;
            display: inline-block;
            margin-right: 12px;
        }

        /* Sections */
        .section-title {
            font-size: 11px;
            font-weight: bold;
            color: #1e293b;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-left: 5px solid #2563eb;
            padding-left: 10px;
            line-height: 1.2;
        }

        /* Cards Table */
        .cards-container {
            width: 100%;
            margin-bottom: 25px;
            border-collapse: separate;
            border-spacing: 10px 0;
            margin-left: -10px;
            margin-right: -10px;
            table-layout: fixed;
        }
        .card-td {
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 15px 12px;
            vertical-align: middle;
            height: 65px;
            overflow: hidden;
        }
        .card-bg-1 { background-color: #f8fafc; border-color: #cbd5e1; }
        .card-bg-2 { background-color: #f0fdf4; border-color: #bbf7d0; }
        .card-bg-3 { background-color: #faf5ff; border-color: #e9d5ff; }
        .card-bg-4 { background-color: #fff7ed; border-color: #fed7aa; }
        
        .card-td.card-primary {
            background-color: #2563eb;
            border-color: #2563eb;
        }
        .card-icon {
            width: 36px;
            height: 36px;
            border-radius: 18px;
            text-align: center;
            display: inline-block;
        }
        .c-icon-1 { background-color: #e2e8f0; color: #334155; }
        .c-icon-2 { background-color: #dcfce7; color: #16a34a; }
        .c-icon-3 { background-color: #f3e8ff; color: #9333ea; }
        .c-icon-4 { border: 2px solid rgba(255, 255, 255, 0.4); box-sizing: border-box; }

        .card-title {
            font-size: 8px;
            font-weight: bold;
            color: #64748b;
            margin-bottom: 6px;
            text-transform: uppercase;
            line-height: 1.2;
            word-wrap: break-word;
        }
        .card-primary .card-title {
            color: #dbeafe;
        }
        .card-value {
            font-size: 18px;
            font-weight: bold;
            color: #0f172a;
        }
        .card-primary .card-value {
            color: #ffffff;
        }
        .card-unit {
            font-size: 9px;
            font-weight: normal;
            color: #94a3b8;
        }
        .card-primary .card-unit {
            color: #bfdbfe;
        }

        /* Data Table */
        .data-table-container {
            margin-bottom: 20px;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9px;
            border: 1px solid #e2e8f0;
            border-bottom: 1px solid #e2e8f0;
        }
        .data-table th {
            background-color: #2563eb;
            color: #ffffff;
            font-weight: bold;
            text-transform: uppercase;
            padding: 10px 4px;
            font-size: 8px;
            text-align: center;
        }
        .data-table th.center {
            text-align: center;
        }
        .data-table th.border-right {
            border-right: 1px solid #3b82f6;
        }
        .data-table td {
            padding: 10px 4px;
            border-bottom: 1px solid #e2e8f0;
            border-right: 1px dashed #cbd5e1;
            color: #334155;
            vertical-align: middle;
            text-align: center;
            font-size: 8px;
        }
        .data-table td:last-child {
            border-right: none;
        }
        .data-table tr:nth-child(even) td {
            background-color: #f8fafc;
        }
        .data-table td.center {
            text-align: center;
        }
        .badge-jenis {
            background-color: #ffedd5;
            color: #ea580c;
            padding: 2px 4px;
            border-radius: 4px;
            font-size: 6.5px;
            font-weight: bold;
            text-transform: uppercase;
            white-space: nowrap;
            display: inline-block;
            margin: 0 auto;
        }

        /* Footer */
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 9px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
            padding-top: 15px;
        }
    </style>
</head>
<body>
    <footer>SOVIE - Social Media View</footer>
    <div class="watermark">SOVIE</div>

    <table cellpadding="0" cellspacing="0" border="0" style="margin-bottom: 15px;">
        <tr>
            <td style="background-color: #1e293b; border-radius: 8px; padding: 12px 18px;">
                <img src="data:image/png;base64,<?php echo e(base64_encode(file_get_contents(public_path('assets/loogo.png')))); ?>" width="130" alt="Logo Kementerian">
            </td>
        </tr>
    </table>

    <div class="banner-container">
        <table width="100%" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td style="vertical-align: middle;">
                    <span class="badge">EXECUTIVE REPORT</span> 
                    <span class="badge-id">ID: <?php echo e(date('ymd-Hi', $generatedTime)); ?></span><br>
                    <h1 class="title">LAPORAN PERFORMA KONTEN</h1>
                    <span class="subtitle">SOVIE AI Analytics</span>
                </td>
                <td align="right" style="vertical-align: middle;">
                    <div class="date-box">
                        <div class="date-text">GENERATED ON<br><strong><?php echo e(date('d M Y', $generatedTime)); ?><br><?php echo e(date('H:i', $generatedTime)); ?></strong></div>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="filter-box">
        <table class="filter-table" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td class="filter-col-border" width="33%">
                    <table cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td><div class="filter-icon-box"><img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgc3Ryb2tlPSIjMjU2M2ViIiBzdHJva2Utd2lkdGg9IjIiIHN0cm9rZS1saW5lY2FwPSJyb3VuZCIgc3Ryb2tlLWxpbmVqb2luPSJyb3VuZCI+PHJlY3QgeD0iMyIgeT0iMyIgd2lkdGg9IjciIGhlaWdodD0iNyI+PC9yZWN0PjxyZWN0IHg9IjE0IiB5PSIzIiB3aWR0aD0iNyIgaGVpZ2h0PSI3Ij48L3JlY3Q+PHJlY3QgeD0iMTQiIHk9IjE0IiB3aWR0aD0iNyIgaGVpZ2h0PSI3Ij48L3JlY3Q+PHJlY3QgeD0iMyIgeT0iMTQiIHdpZHRoPSI3IiBoZWlnaHQ9IjciPjwvcmVjdD48L3N2Zz4=" width="16" height="16" style="margin-top: 8px;"></div></td>
                            <td>
                                <div class="filter-label">PLATFORM</div>
                                <div class="filter-value"><?php echo e($filterInfo['platform']); ?></div>
                            </td>
                        </tr>
                    </table>
                </td>
                <td class="filter-col-border" width="33%" style="padding-left: 20px;">
                    <table cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td><div class="filter-icon-box"><img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgc3Ryb2tlPSIjMjU2M2ViIiBzdHJva2Utd2lkdGg9IjIiIHN0cm9rZS1saW5lY2FwPSJyb3VuZCIgc3Ryb2tlLWxpbmVqb2luPSJyb3VuZCI+PHJlY3QgeD0iMyIgeT0iNCIgd2lkdGg9IjE4IiBoZWlnaHQ9IjE4IiByeD0iMiIgcnk9IjIiPjwvcmVjdD48bGluZSB4MT0iMTYiIHkxPSIyIiB4Mj0iMTYiIHkyPSI2Ij48L2xpbmU+PGxpbmUgeDE9IjgiIHkxPSIyIiB4Mj0iOCIgeTI9IjYiPjwvbGluZT48bGluZSB4MT0iMyIgeTE9IjEwIiB4Mj0iMjEiIHkyPSIxMCI+PC9saW5lPjwvc3ZnPg==" width="16" height="16" style="margin-top: 8px;"></div></td>
                            <td>
                                <div class="filter-label">PERIODE</div>
                                <div class="filter-value"><?php echo e($filterInfo['periode']); ?></div>
                            </td>
                        </tr>
                    </table>
                </td>
                <td width="34%" style="padding-left: 20px;">
                    <table cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td><div class="filter-icon-box"><img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgc3Ryb2tlPSIjMjU2M2ViIiBzdHJva2Utd2lkdGg9IjIiIHN0cm9rZS1saW5lY2FwPSJyb3VuZCIgc3Ryb2tlLWxpbmVqb2luPSJyb3VuZCI+PGNpcmNsZSBjeD0iMTEiIGN5PSIxMSIgcj0iOCI+PC9jaXJjbGU+PGxpbmUgeDE9IjIxIiB5MT0iMjEiIHgyPSIxNi42NSIgeTI9IjE2LjY1Ij48L2xpbmU+PC9zdmc+" width="16" height="16" style="margin-top: 8px;"></div></td>
                            <td>
                                <div class="filter-label">PENCARIAN</div>
                                <div class="filter-value"><?php echo e($filterInfo['search']); ?></div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>

    <div class="section-title">
       RINGKASAN EKSEKUTIF
    </div>
    
    <table class="cards-container" cellpadding="0" cellspacing="0" border="0">
        <tr>
            <td class="card-td card-bg-1" width="20%">
                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td width="45px"><div class="card-icon c-icon-1"><img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgc3Ryb2tlPSIjMjU2M2ViIiBzdHJva2Utd2lkdGg9IjIiIHN0cm9rZS1saW5lY2FwPSJyb3VuZCIgc3Ryb2tlLWxpbmVqb2luPSJyb3VuZCI+PHBhdGggZD0iTTE0IDJINmEyIDIgMCAwIDAtMiAydjE2YTIgMiAwIDAgMCAyIDJoMTJhMiAyIDAgMCAwIDItMlY4eiI+PC9wYXRoPjxwb2x5bGluZSBwb2ludHM9IjE0IDIgMTQgOCAyMCA4Ij48L3BvbHlsaW5lPjwvc3ZnPg==" width="20" height="20" style="margin-top: 10px;"></div></td>
                        <td>
                            <div class="card-title">TOTAL KONTEN</div>
                            <div class="card-value"><?php echo e(number_format($laporanAgg['total_konten'])); ?> <br><span class="card-unit">Posts</span></div>
                        </td>
                    </tr>
                </table>
            </td>
            <td class="card-td card-bg-2" width="20%">
                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td width="45px"><div class="card-icon c-icon-2"><img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgc3Ryb2tlPSIjMTZhMzRhIiBzdHJva2Utd2lkdGg9IjIiIHN0cm9rZS1saW5lY2FwPSJyb3VuZCIgc3Ryb2tlLWxpbmVqb2luPSJyb3VuZCI+PHBhdGggZD0iTTE3IDIxdi0yYTQgNCAwIDAgMC00LTRINWE0IDQgMCAwIDAtNCA0djIiPjwvcGF0aD48Y2lyY2xlIGN4PSI5IiBjeT0iNyIgcj0iNCI+PC9jaXJjbGU+PHBhdGggZD0iTTIzIDIxdi0yYTQgNCAwIDAgMC0zLTMuODciPjwvcGF0aD48cGF0aCBkPSJNMTYgMy4xM2E0IDQgMCAwIDEgMCA3Ljc1Ij48L3BhdGg+PC9zdmc+" width="20" height="20" style="margin-top: 10px;"></div></td>
                        <td>
                            <div class="card-title">TOTAL REACH</div>
                            <div class="card-value"><?php echo e(number_format($laporanAgg['total_reach'])); ?> <br><span class="card-unit">Uniques</span></div>
                        </td>
                    </tr>
                </table>
            </td>
            <td class="card-td card-bg-3" width="20%">
                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td width="45px"><div class="card-icon c-icon-3"><img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgc3Ryb2tlPSIjOTMzM2VhIiBzdHJva2Utd2lkdGg9IjIiIHN0cm9rZS1saW5lY2FwPSJyb3VuZCIgc3Ryb2tlLWxpbmVqb2luPSJyb3VuZCI+PHBvbHlsaW5lIHBvaW50cz0iMjMgNiAxMy41IDE1LjUgOC41IDEwLjUgMSAxOCI+PC9wb2x5bGluZT48cG9seWxpbmUgcG9pbnRzPSIxNyA2IDIzIDYgMjMgMTIiPjwvcG9seWxpbmU+PC9zdmc+" width="20" height="20" style="margin-top: 10px;"></div></td>

                        <td>
                            <div class="card-title">ENGAGEMENT</div>
                            <div class="card-value"><?php echo e(number_format($laporanAgg['total_eng'])); ?> <br><span class="card-unit">Actions</span></div>
                        </td>
                    </tr>
                </table>
            </td>
            <td class="card-td card-bg-4" width="20%">
                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td width="45px"><div class="card-icon" style="background-color: #ffedd5; color: #ea580c;"><img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgc3Ryb2tlPSIjZWE1ODBjIiBzdHJva2Utd2lkdGg9IjIiIHN0cm9rZS1saW5lY2FwPSJyb3VuZCIgc3Ryb2tlLWxpbmVqb2luPSJyb3VuZCI+PHBhdGggZD0iTTE2IDIxdi0yYTQgNCAwIDAgMC00LTRINmE0IDQgMCAwIDAtNCA0djIiPjwvcGF0aD48Y2lyY2xlIGN4PSI5IiBjeT0iNyIgcj0iNCI+PC9jaXJjbGU+PGxpbmUgeDE9IjE5IiB5MT0iOCIgeDI9IjE5IiB5Mj0iMTQiPjwvbGluZT48bGluZSB4MT0iMjIiIHkxPSIxMSIgeDI9IjE2IiB5Mj0iMTEiPjwvbGluZT48L3N2Zz4=" width="20" height="20" style="margin-top: 10px;"></div></td>
                        <td>
                            <div class="card-title">TOTAL FOLLOWERS</div>
                            <div class="card-value"><?php echo e(number_format($laporanAgg['total_followers'])); ?> <br><span class="card-unit">Followers</span></div>
                        </td>
                    </tr>
                </table>
            </td>
            <td class="card-td card-primary" width="20%">
                <table width="100%" height="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td align="center" valign="middle">
                            <div class="card-title" style="color: #dbeafe; margin-bottom: 10px;">AVG ER (%)</div>
                            <div>
                                <div class="card-icon c-icon-4" style="width: 24px; height: 24px; border-radius: 12px; display: inline-block; vertical-align: middle; border: 1.5px solid rgba(255, 255, 255, 0.5);">
                                    <img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgc3Ryb2tlPSIjZmZmZmZmIiBzdHJva2Utd2lkdGg9IjIiIHN0cm9rZS1saW5lY2FwPSJyb3VuZCIgc3Ryb2tlLWxpbmVqb2luPSJyb3VuZCI+PGxpbmUgeDE9IjE5IiB5MT0iNSIgeDI9IjUiIHkyPSIxOSI+PC9saW5lPjxjaXJjbGUgY3g9IjYuNSIgY3k9IjYuNSIgcj0iMi41Ij48L2NpcmNsZT48Y2lyY2xlIGN4PSIxNy41IiBjeT0iMTcuNSIgcj0iMi41Ij48L2NpcmNsZT48L3N2Zz4=" width="12" height="12" style="margin-top: 4px;">
                                </div>
                                <span class="card-value" style="display: inline-block; vertical-align: middle; margin-left: 6px; font-size: 22px; color: #ffffff;"><?php echo e(number_format($laporanAgg['avg_er'], 2)); ?>%</span>
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <div class="section-title">
       DETAIL PERFORMA KONTEN
    </div>
    
    <div class="data-table-container">
        <table class="data-table" cellpadding="0" cellspacing="0" border="0">
            <thead>
                <tr>
                    <th class="center border-right" width="4%">No</th>
                    <th class="center border-right" width="9%">Tgl Upload</th>
                    <th class="border-right" width="10%">Platform</th>
                    <th class="border-right" width="9%">Jenis</th>
                    <th class="border-right" width="29%">Judul Konten</th>
                    <th class="center border-right" width="7%">Reach</th>
                    <th class="center border-right" width="7%">Views</th>
                    <th class="center border-right" width="7%">Saves</th>
                    <th class="center border-right" width="6%">Likes</th>
                    <th class="center border-right" width="6%">Comments</th>
                    <th class="center" width="6%">Share</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $metrics; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td class="center"><?php echo e($i + 1); ?></td>
                    <td class="center"><?php echo e($row->tgl_upload ? \Carbon\Carbon::parse($row->tgl_upload)->format('d/m/y') : '-'); ?></td>
                    <td class="center"><?php echo e($row->platform); ?></td>
                    <td class="center"><span class="badge-jenis"><?php echo e($row->jenis); ?></span></td>
                    <td class="center"><?php echo e(\Illuminate\Support\Str::limit($row->judul_konten, 30)); ?></td>
                    <td class="center"><?php echo e(number_format($row->reach)); ?></td>
                    <td class="center"><?php echo e(number_format($row->reach)); ?></td>
                    <td class="center">0</td>
                    <td class="center"><?php echo e(number_format($row->likes)); ?></td>
                    <td class="center"><?php echo e(number_format($row->comments)); ?></td>
                    <td class="center"><?php echo e(number_format($row->shares)); ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>

    <div class="footer">
        Dicetak melalui <strong>SOVIE AI Analytics</strong> pada <?php echo e(date('d F Y H:i:s', $generatedTime)); ?>

    </div>
</body>
</html><?php /**PATH D:\magang\InsightHubBaru\frontend\resources\views/pages/Report/export_pdf.blade.php ENDPATH**/ ?>
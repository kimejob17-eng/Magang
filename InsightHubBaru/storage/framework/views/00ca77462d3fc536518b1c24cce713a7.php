<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Performa Konten Media Sosial</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; margin: 0; padding: 0; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .header h1 { margin: 0 0 5px 0; font-size: 18px; text-transform: uppercase; }
        .header p { margin: 0; font-size: 12px; color: #666; }
        .info-table { width: 100%; margin-bottom: 20px; }
        .info-table td { padding: 3px 0; vertical-align: top; }
        .info-label { width: 100px; font-weight: bold; }
        .data-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .data-table th, .data-table td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        .data-table th { background-color: #f3f4f6; font-weight: bold; text-align: center; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .summary-box { border: 1px solid #ddd; padding: 15px; background-color: #f9fafb; page-break-inside: avoid; }
        .summary-box h3 { margin-top: 0; margin-bottom: 10px; font-size: 14px; border-bottom: 1px solid #ccc; padding-bottom: 5px; }
        .summary-grid { display: block; width: 100%; }
        .summary-item { display: inline-block; width: 24%; margin-bottom: 10px; }
        .summary-label { font-size: 10px; color: #666; text-transform: uppercase; }
        .summary-value { font-size: 16px; font-weight: bold; color: #111; }
    </style>
</head>
<body>

    <div class="header">
        <h1>Laporan Performa Konten Media Sosial</h1>
        <p>InsightHub Executive Report</p>
    </div>

    <table class="info-table">
        <tr>
            <td class="info-label">Platform</td>
            <td>: <?php echo e($filterInfo['platform']); ?></td>
            <td class="info-label">Dicetak Pada</td>
            <td>: <?php echo e(\Carbon\Carbon::now()->format('d M Y H:i:s')); ?></td>
        </tr>
        <tr>
            <td class="info-label">Periode</td>
            <td>: <?php echo e($filterInfo['periode']); ?></td>
            <td class="info-label">Pencarian</td>
            <td>: <?php echo e($filterInfo['search']); ?></td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 3%;">No</th>
                <th style="width: 8%;">Tgl Upload</th>
                <th style="width: 7%;">Platform</th>
                <th style="width: 8%;">Jenis</th>
                <th style="width: 9%;">Kategori</th>
                <th style="width: 20%;">Judul Konten</th>
                <th style="width: 8%;">Reach</th>
                <th style="width: 7%;">Likes</th>
                <th style="width: 7%;">Comments</th>
                <th style="width: 7%;">Shares</th>
                <th style="width: 8%;">ER (%)</th>
                <th style="width: 8%;">Followers +</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $metrics; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php
                $rowEng = $row->likes + $row->comments + $row->shares;
                $rowEr = $row->reach > 0 ? round(($rowEng / $row->reach) * 100, 2) : 0;
            ?>
            <tr>
                <td class="text-center"><?php echo e($index + 1); ?></td>
                <td class="text-center"><?php echo e($row->tgl_upload ? \Carbon\Carbon::parse($row->tgl_upload)->format('d/m/y') : '-'); ?></td>
                <td class="text-center"><?php echo e(ucfirst($row->platform)); ?></td>
                <td class="text-center"><?php echo e($row->jenis ?: '-'); ?></td>
                <td><?php echo e($row->kategori ?: '-'); ?></td>
                <td><?php echo e(\Illuminate\Support\Str::limit($row->judul_konten ?: '-', 40)); ?></td>
                <td class="text-right"><?php echo e(number_format($row->reach)); ?></td>
                <td class="text-right"><?php echo e(number_format($row->likes)); ?></td>
                <td class="text-right"><?php echo e(number_format($row->comments)); ?></td>
                <td class="text-right"><?php echo e(number_format($row->shares)); ?></td>
                <td class="text-right"><?php echo e($rowEr); ?>%</td>
                <td class="text-right"><?php echo e(number_format($row->followers_plus)); ?></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan="12" class="text-center">Tidak ada data ditemukan.</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="summary-box">
        <h3>Ringkasan Eksekutif</h3>
        <div class="summary-grid">
            <div class="summary-item">
                <div class="summary-label">Total Konten</div>
                <div class="summary-value"><?php echo e(number_format($laporanAgg['total_konten'])); ?></div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Total Reach</div>
                <div class="summary-value"><?php echo e(number_format($laporanAgg['total_reach'])); ?></div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Total Engagement</div>
                <div class="summary-value"><?php echo e(number_format($laporanAgg['total_eng'])); ?></div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Avg Engagement Rate</div>
                <div class="summary-value"><?php echo e(number_format($laporanAgg['avg_er'], 2)); ?>%</div>
            </div>
        </div>
    </div>

</body>
</html><?php /**PATH D:\Project-Magang\InsightHub\frontend\resources\views\pages\Report\export_pdf.blade.php ENDPATH**/ ?>
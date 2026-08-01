<table>
    <thead>
        <tr>
            <th colspan="14" style="font-size: 16px; font-weight: bold; text-align: center;">LAPORAN PERFORMA KONTEN MEDIA SOSIAL</th>
        </tr>
        <tr>
            <th colspan="14" style="text-align: center;">InsightHub Executive Report</th>
        </tr>
        <tr>
            <th colspan="14"></th>
        </tr>
        <tr>
            <th colspan="2">Platform</th>
            <td colspan="12">: <?php echo e($filterInfo['platform']); ?></td>
        </tr>
        <tr>
            <th colspan="2">Periode</th>
            <td colspan="12">: <?php echo e($filterInfo['periode']); ?></td>
        </tr>
        <tr>
            <th colspan="2">Pencarian</th>
            <td colspan="12">: <?php echo e($filterInfo['search']); ?></td>
        </tr>
        <tr>
            <th colspan="2">Dicetak pada</th>
            <td colspan="12">: <?php echo e(\Carbon\Carbon::now()->format('d M Y H:i:s')); ?></td>
        </tr>
        <tr>
            <th colspan="14"></th>
        </tr>
        <tr>
            <th style="background-color: #f3f4f6; font-weight: bold; border: 1px solid #000;">No</th>
            <th style="background-color: #f3f4f6; font-weight: bold; border: 1px solid #000;">Tanggal Upload</th>
            <th style="background-color: #f3f4f6; font-weight: bold; border: 1px solid #000;">Platform</th>
            <th style="background-color: #f3f4f6; font-weight: bold; border: 1px solid #000;">Jenis Konten</th>
            <th style="background-color: #f3f4f6; font-weight: bold; border: 1px solid #000;">Kategori Konten</th>
            <th style="background-color: #f3f4f6; font-weight: bold; border: 1px solid #000;">Judul Konten</th>
            <th style="background-color: #f3f4f6; font-weight: bold; border: 1px solid #000;">Link Konten</th>
            <th style="background-color: #f3f4f6; font-weight: bold; border: 1px solid #000;">Views / Penayangan</th>
            <th style="background-color: #f3f4f6; font-weight: bold; border: 1px solid #000;">Reach</th>
            <th style="background-color: #f3f4f6; font-weight: bold; border: 1px solid #000;">Likes</th>
            <th style="background-color: #f3f4f6; font-weight: bold; border: 1px solid #000;">Comments</th>
            <th style="background-color: #f3f4f6; font-weight: bold; border: 1px solid #000;">Shares</th>
            <th style="background-color: #f3f4f6; font-weight: bold; border: 1px solid #000;">Engagement Rate (%)</th>
            <th style="background-color: #f3f4f6; font-weight: bold; border: 1px solid #000;">Followers Bertambah</th>
        </tr>
    </thead>
    <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $metrics; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <?php
            $rowEng = $row->likes + $row->comments + $row->shares;
            $rowEr = $row->reach > 0 ? round(($rowEng / $row->reach) * 100, 2) : 0;
        ?>
        <tr>
            <td style="border: 1px solid #000; text-align: center;"><?php echo e($index + 1); ?></td>
            <td style="border: 1px solid #000;"><?php echo e($row->tgl_upload ? \Carbon\Carbon::parse($row->tgl_upload)->format('d-m-Y') : '-'); ?></td>
            <td style="border: 1px solid #000;"><?php echo e(ucfirst($row->platform)); ?></td>
            <td style="border: 1px solid #000;"><?php echo e($row->jenis ?: '-'); ?></td>
            <td style="border: 1px solid #000;"><?php echo e($row->kategori ?: '-'); ?></td>
            <td style="border: 1px solid #000;"><?php echo e($row->judul_konten ?: '-'); ?></td>
            <td style="border: 1px solid #000;"><?php echo e($row->link ?: '-'); ?></td>
            <td style="border: 1px solid #000;"><?php echo e($row->reach); ?></td>
            <td style="border: 1px solid #000;"><?php echo e($row->reach); ?></td>
            <td style="border: 1px solid #000;"><?php echo e($row->likes); ?></td>
            <td style="border: 1px solid #000;"><?php echo e($row->comments); ?></td>
            <td style="border: 1px solid #000;"><?php echo e($row->shares); ?></td>
            <td style="border: 1px solid #000;"><?php echo e($rowEr); ?></td>
            <td style="border: 1px solid #000;"><?php echo e($row->followers_plus); ?></td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <tr>
            <td colspan="14" style="text-align: center; border: 1px solid #000;">Tidak ada data ditemukan.</td>
        </tr>
        <?php endif; ?>
    </tbody>
    <tfoot>
        <tr>
            <th colspan="14"></th>
        </tr>
        <tr>
            <th colspan="2" style="font-weight: bold; background-color: #e5e7eb;">Total Konten</th>
            <td colspan="12" style="background-color: #f9fafb;">: <?php echo e(number_format($laporanAgg['total_konten'])); ?></td>
        </tr>
        <tr>
            <th colspan="2" style="font-weight: bold; background-color: #e5e7eb;">Total Reach</th>
            <td colspan="12" style="background-color: #f9fafb;">: <?php echo e(number_format($laporanAgg['total_reach'])); ?></td>
        </tr>
        <tr>
            <th colspan="2" style="font-weight: bold; background-color: #e5e7eb;">Total Engagement</th>
            <td colspan="12" style="background-color: #f9fafb;">: <?php echo e(number_format($laporanAgg['total_eng'])); ?></td>
        </tr>
        <tr>
            <th colspan="2" style="font-weight: bold; background-color: #e5e7eb;">Rata-rata Engagement Rate</th>
            <td colspan="12" style="background-color: #f9fafb;">: <?php echo e(number_format($laporanAgg['avg_er'], 2)); ?>%</td>
        </tr>
    </tfoot>
</table><?php /**PATH D:\Project-Magang\InsightHub\frontend\resources\views\pages\Report\export_excel.blade.php ENDPATH**/ ?>
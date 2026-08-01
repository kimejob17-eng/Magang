
<div id="content-facebook" class="platform-content active">
    <div class="platform-form-card">
        <div class="platform-form-header">
            <div class="platform-form-icon" style="background: #1877f2;">
                <i class="ph-fill ph-facebook-logo"></i>
            </div>
            <div>
                <h2 style="font-size: 1.1rem; font-weight: 700; color: #0f172a; margin-bottom: 0.25rem;">Facebook Performance Data</h2>
                <p style="font-size: 0.8rem; color: #64748b;">Manual data entry for Facebook posts and campaigns.</p>
            </div>
        </div>

        <form action="<?php echo e(route('dashboard.metrics.store')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="platform" value="facebook">
            <div class="form-row">
                <div class="form-group">
                    <label>Kategori Konten</label>
                    <div class="kategori-wrapper">
                        <select name="category" class="form-control" required>
                            <option value="">-- Pilih Kategori --</option>
                            <?php $__currentLoopData = $kategoris->where('platform.slug', 'facebook'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($kat->nama); ?>"><?php echo e($kat->nama); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <a href="javascript:void(0)" onclick="openKategoriModal('facebook', this)" class="btn-tambah-kategori" title="Kelola Kategori" style="text-decoration:none; display:flex; align-items:center;"><i class="ph-bold ph-gear" style="margin-right:4px;"></i> Kelola</a>
                    </div>
                </div>
                <div class="form-group">
                    <label>Tanggal Tayang</label>
                    <input type="date" name="publish_date" class="form-control" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Judul Konten</label>
                    <input type="text" name="title" class="form-control" placeholder="Contoh: Promo Ramadhan" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Jenis Konten</label>
                    <select name="content_type" class="form-control" required>
                        <option value="Image Post">Image Post</option>
                        <option value="Video">Video</option>
                        <option value="Carousel">Carousel</option>
                        <option value="Link">Link</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Link Konten</label>
                    <input type="url" name="url" class="form-control" placeholder="https://facebook.com/..." required>
                </div>
            </div>

            <div style="height: 1px; background: #e2e8f0; margin: 1.5rem 0;"></div>

            <div class="form-row">
                <div class="form-group">
                    <label>Tayangan (Impressions)</label>
                    <input type="number" name="views" class="form-control" placeholder="0" required>
                </div>
                <div class="form-group">
                    <label>Interaksi (Engagement)</label>
                    <input type="number" name="interactions" class="form-control" placeholder="0" required>
                </div>
            </div>

            <div class="form-row-3" style="margin-top: 1rem;">
                <div class="form-group">
                    <label>Like</label>
                    <input type="number" name="like" class="form-control" placeholder="0">
                </div>
                <div class="form-group">
                    <label>Comment</label>
                    <input type="number" name="comment" class="form-control" placeholder="0">
                </div>
                <div class="form-group">
                    <label>Share</label>
                    <input type="number" name="share" class="form-control" placeholder="0">
                </div>
            </div>

            <div class="form-row" style="margin-top: 1rem;">
                <div class="form-group" style="margin-bottom: 0;">
                    <button type="reset" class="btn" style="width: 100%; justify-content: center; background: #f8fafc; color: #64748b;">Reset Form</button>
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; background: #003EA8; border-color: #003EA8;"><i class="ph-bold ph-floppy-disk"></i> Save Data</button>
                </div>
            </div>
        </form>
    </div>

    <!-- History Table Facebook -->
    <div class="card" style="margin-top: 2rem;">
        <div class="card-title" style="margin-bottom: 1rem;">Riwayat Data Facebook</div>
        <div style="overflow-x: auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Topik</th>
                        <th>Jenis</th>
                        <th>Tayangan</th>
                        <th>Interaksi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    
                    <?php $__empty_1 = true; $__currentLoopData = $metrics->filter(fn($m) => strtolower($m->platform) === 'facebook'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $metric): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e(\Carbon\Carbon::parse($metric->tgl_upload)->format('d M, Y')); ?></td>
                        <td style="font-weight: 500;"><?php echo e($metric->judul_konten); ?></td>
                        <td><span class="badge badge-stable"><?php echo e($metric->jenis); ?></span></td>
                        <td><?php echo e(number_format($metric->reach)); ?></td>
                        <td>-</td>
                        <td><a href="<?php echo e($metric->link); ?>" target="_blank" style="color: var(--primary);">View</a></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="6" style="text-align: center; color: #64748b; padding: 2rem;">Belum ada data riwayat.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div><?php /**PATH C:\laragon\www\InsightHubBaru\frontend\resources\views/pages/Input/facebook.blade.php ENDPATH**/ ?>

<div id="yt-tab-shorts" class="yt-tab-content" style="display: none;">
    <div class="platform-form-card">
        <div class="platform-form-header" style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 1rem;">
            <div style="display: flex; gap: 1rem; align-items: center;">
                <div class="platform-form-icon" style="background: #fee2e2; color: #dc2626; display: flex; align-items: center; justify-content: center;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="6" y="2" width="12" height="20" rx="2" ry="2"></rect><polygon points="10 15 15 12 10 9 10 15"></polygon></svg>
                </div>
                <div>
                    <h2 style="font-size: 1.1rem; font-weight: 700; color: #0f172a; margin-bottom: 0.25rem;">YouTube Shorts Data</h2>
                    <p style="font-size: 0.8rem; color: #64748b; margin: 0;">Manual data entry for YouTube Shorts.</p>
                </div>
            </div>
            <button type="button" class="btn" onclick="openImportModal('yt-shorts')" style="cursor: pointer; position: relative; z-index: 10; background: #10b981; color: white; border: none; font-weight: 500; font-size: 0.85rem; padding: 0.5rem 1rem; border-radius: 2rem; display: flex; align-items: center; gap: 6px; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                Import Excel
            </button>
        </div>

        <form id="form-shorts" action="<?php echo e(route('dashboard.metrics.store')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <div id="method-container-yt-shorts"></div>
            <input type="hidden" name="platform" value="yt-shorts">
            <input type="hidden" name="metric_id" id="metric-id-yt-shorts" value="">
            <input type="hidden" name="content_type" value="YouTube Shorts">

            <div class="form-row">
                <div class="form-group">
                    <label>Kategori Konten</label>
                    <div class="kategori-wrapper">
                        <select name="category" class="form-control" required>
                            <option value="">-- Pilih Kategori --</option>
                            <?php $__currentLoopData = $kategoris->where('platform.slug', 'youtube'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($kat->nama); ?>"><?php echo e($kat->nama); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <a href="javascript:void(0)" onclick="openKategoriModal('youtube', this)" class="btn-tambah-kategori" title="Kelola Kategori" style="text-decoration:none; display:flex; align-items:center;"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 4px;"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg> Kelola</a>
                    </div>
                </div>
                <div class="form-group">
                    <label>Tanggal Penayangan</label>
                    <input type="date" name="publish_date" class="form-control" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Judul Konten</label>
                    <input type="text" name="title" class="form-control" placeholder="Contoh: Tips & Tricks #1" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Link</label>
                    <input type="url" name="url" class="form-control" placeholder="https://youtube.com/shorts/...">
                </div>
                <div class="form-group">
                    <label>Jumlah Penayangan</label>
                    <input type="text" name="views" class="form-control" placeholder="0" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Penambahan Subscriber</label>
                    <div style="position: relative;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: #64748b;"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                        <input type="text" name="subscribers" class="form-control" style="padding-left: 2.5rem;" placeholder="0" required>
                    </div>
                </div>
            </div>

            <div class="form-row-3" style="margin-top: 1rem;">
                <div class="form-group">
                    <label>Like</label>
                    <input type="text" name="like" class="form-control" placeholder="0">
                </div>
                <div class="form-group">
                    <label>Comment</label>
                    <input type="text" name="comment" class="form-control" placeholder="0">
                </div>
                <div class="form-group">
                    <label>Repost</label>
                    <input type="text" name="share" class="form-control" placeholder="0">
                </div>
            </div>

            <div class="form-row" style="margin-top: 1rem;">
                <div class="form-group" style="margin-bottom: 0;">
                    <button type="button" id="btn-cancel-shorts" class="btn" style="display:none; width: 100%; justify-content: center; background: #f8fafc; color: #64748b;" onclick="resetFormYTShorts()">Batal Edit</button>
                    <button type="reset" id="btn-reset-shorts" class="btn" style="width: 100%; justify-content: center; background: #f8fafc; color: #64748b;">Reset Form</button>
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <button type="submit" id="btn-submit-yt-shorts" class="btn btn-primary" style="width: 100%; justify-content: center; background: #ff0000; border-color: #ff0000;"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 4px;"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg> Simpan Data</button>
                </div>
            </div>
        </form>
    </div>

    <!-- History Table Shorts -->
    <div class="card" style="margin-top: 2rem;">
        <div class="card-title" style="margin-bottom: 1rem;">Riwayat Data YouTube Shorts</div>
        <div style="overflow-x: auto;">
            <table class="data-table table-hover">
                <thead>
                    <tr>
                        <th width="15%">Tanggal</th>
                        <th width="45%">Judul</th>
                        <th width="12%">Tayangan</th>
                        <th width="12%">Subscriber +</th>
                        <th width="16%" style="text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $metrics->filter(fn($m) => strtolower($m->platform) === 'youtube' && $m->jenis === 'Shorts'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $metric): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php $id_asli = explode('-', $metric->id_konten)[1] ?? $metric->id_konten; ?>
                    <tr>
                        <td><?php echo e(\Carbon\Carbon::parse($metric->tgl_upload)->format('d M, Y')); ?></td>
                        <td style="font-weight: 500; max-width: 250px;">
                            <div class="topic-text" title="<?php echo e($metric->judul_konten); ?>"><?php echo e($metric->judul_konten); ?></div>
                        </td>
                        <td><?php echo e(number_format($metric->reach)); ?></td>
                        <td><span style="color: #16a34a; font-weight: 600;">+<?php echo e(number_format($metric->followers_plus)); ?></span></td>
                        <td>
                            <div style="display:flex; gap:12px; align-items:center; justify-content: center;">
                                <a href="javascript:void(0)" onclick='openDetailModal(<?php echo json_encode($metric, 15, 512) ?>)' title="Detail" style="color: var(--primary); transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                </a>
                                <a href="javascript:void(0)" onclick="editShorts('<?php echo e($id_asli); ?>', <?php echo e(json_encode($metric)); ?>)" title="Edit" style="color: #f59e0b; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 1 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                </a>
                                <form action="<?php echo e(route('dashboard.metrics.destroy', ['yt-shorts', $id_asli])); ?>" method="POST" style="margin:0;">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button type="submit" title="Hapus" style="background:none; border:none; padding:0; color:#ef4444; cursor:pointer; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="5" style="text-align: center; color: #64748b; padding: 2rem;">Belum ada data riwayat.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
function editShorts(id_konten, data) {
    const form = document.getElementById('form-shorts');
    form.action = "<?php echo e(url('dashboard/metrics')); ?>/yt-shorts/" + id_konten;
    document.getElementById('method-container-yt-shorts').innerHTML = '<input type="hidden" name="_method" value="PUT">';
    document.getElementById('metric-id-yt-shorts').value = id_konten;
    form.querySelector('[name="category"]').value = data.kategori || '';
    if(data.tgl_upload) form.querySelector('[name="publish_date"]').value = data.tgl_upload.substring(0, 10);
    form.querySelector('[name="title"]').value = data.judul_konten || '';
    form.querySelector('[name="url"]').value = data.tautan || '';
    form.querySelector('[name="views"]').value = data.reach || 0;
    form.querySelector('[name="subscribers"]').value = data.followers_plus || 0;
    form.querySelector('[name="like"]').value = data.likes || 0;
    form.querySelector('[name="comment"]').value = data.comments || 0;
    form.querySelector('[name="share"]').value = data.repost || 0;
    document.getElementById('btn-submit-yt-shorts').innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-right:4px;"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 1 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg> Update Data';
    document.getElementById('btn-reset-shorts').style.display = 'none';
    document.getElementById('btn-cancel-shorts').style.display = 'flex';
    
    form.classList.remove('form-edit-highlight');
    void form.offsetWidth;
    form.classList.add('form-edit-highlight');
    
    window.scrollTo({ top: form.offsetTop - 80, behavior: 'smooth' });
}
function resetFormYTShorts() {
    const form = document.getElementById('form-shorts');
    form.reset();
    form.action = "<?php echo e(route('dashboard.metrics.store')); ?>";
    document.getElementById('method-container-yt-shorts').innerHTML = '';
    document.getElementById('metric-id-yt-shorts').value = '';
    document.getElementById('btn-submit-yt-shorts').innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-right:4px;"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg> Simpan Data';
    document.getElementById('btn-reset-shorts').style.display = 'flex';
    document.getElementById('btn-cancel-shorts').style.display = 'none';
    form.classList.remove('form-edit-highlight');
}
</script><?php /**PATH D:\magang\InsightHubBaru\frontend\resources\views/pages/Input/youtube/shorts.blade.php ENDPATH**/ ?>
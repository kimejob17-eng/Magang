
<div id="content-tiktok" class="platform-content" style="display: none;">
    <div class="platform-form-card">
        <div class="platform-form-header" style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 1rem;">
            <div style="display: flex; gap: 1rem; align-items: center;">
                <div class="platform-form-icon" style="background: #f1f5f9; color: #000000; display: flex; align-items: center; justify-content: center;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 12a4 4 0 1 0 4 4V4a5 5 0 0 0 5 5"></path></svg>
                </div>
                <div>
                    <h2 style="font-size: 1.1rem; font-weight: 700; color: #0f172a; margin-bottom: 0.25rem;">TikTok Performance Data</h2>
                    <p style="font-size: 0.8rem; color: #64748b; margin: 0;">Manual data entry for TikTok videos and campaigns.</p>
                </div>
            </div>
            <button type="button" class="btn" onclick="openImportModal('tiktok')" style="cursor: pointer; position: relative; z-index: 10; background: #10b981; color: white; border: none; font-weight: 500; font-size: 0.85rem; padding: 0.5rem 1rem; border-radius: 2rem; display: flex; align-items: center; gap: 6px; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                Import Excel
            </button>
        </div>

        <form id="form-tiktok" action="<?php echo e(route('dashboard.metrics.store')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <div id="method-container-tiktok"></div>
            <input type="hidden" name="platform" value="tiktok">
            <input type="hidden" name="metric_id" id="metric-id-tiktok" value="">
            <div class="form-row">
                <div class="form-group">
                    <label>Kategori Konten</label>
                    <div class="kategori-wrapper">
                        <select name="category" class="form-control" required>
                            <option value="">-- Pilih Kategori --</option>
                            <?php $__currentLoopData = $kategoris->where('platform.slug', 'tiktok'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($kat->nama); ?>"><?php echo e($kat->nama); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <a href="javascript:void(0)" onclick="openKategoriModal('tiktok', this)" class="btn-tambah-kategori" title="Kelola Kategori" style="text-decoration:none; display:flex; align-items:center;"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 4px;"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg> Kelola</a>
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
                    <input type="text" name="title" class="form-control" placeholder="Contoh: Dance Challenge" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Jenis Konten</label>
                    <select name="content_type" class="form-control" required>
                        <option value="Short Video">Short Video</option>
                        <option value="Live Stream">Live Stream</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Link Konten</label>
                    <div style="display: flex;">
                        <span style="background: #f1f5f9; border: 1px solid #e2e8f0; border-right: none; padding: 0.75rem; border-radius: 0.5rem 0 0 0.5rem; color: #64748b; font-size: 0.85rem;">tiktok.com/</span>
                        <input type="text" name="url" class="form-control" style="border-radius: 0 0.5rem 0.5rem 0;" placeholder="@username/video/123...">
                    </div>
                </div>
            </div>

            <div style="height: 1px; background: #e2e8f0; margin: 1.5rem 0;"></div>

            
            <div class="form-row">
                <div class="form-group">
                    <label>Views</label>
                    <input type="text" name="views" class="form-control" placeholder="0" required>
                </div>
                <div class="form-group">
                    <label>Save</label>
                    <input type="text" name="save" class="form-control" placeholder="0">
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
                    <label>Share</label>
                    <input type="text" name="share" class="form-control" placeholder="0">
                </div>
            </div>

            <div class="form-row" style="margin-top: 1rem;">
                <div class="form-group" style="margin-bottom: 0;">
                    <button type="button" id="btn-cancel-tiktok" class="btn" style="display:none; width: 100%; justify-content: center; background: #f8fafc; color: #64748b;" onclick="resetFormTiktok()">Batal Edit</button>
                    <button type="reset" id="btn-reset-tiktok" class="btn" style="width: 100%; justify-content: center; background: #f8fafc; color: #64748b;">Reset Form</button>
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <button type="submit" id="btn-submit-tiktok" class="btn btn-primary" style="width: 100%; justify-content: center; background: #003EA8; border-color: #003EA8;"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 4px;"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg> Save Data</button>
                </div>
            </div>
        </form>
    </div>

    <!-- History Table TikTok -->
    <div class="card" style="margin-top: 2rem;">
        <div class="card-title" style="margin-bottom: 1rem;">Riwayat Data TikTok</div>
        <div style="overflow-x: auto;">
            <table class="data-table table-hover">
                <thead>
                    <tr>
                        <th width="15%">Tanggal</th>
                        <th width="35%">Topik</th>
                        <th width="10%">Jenis</th>
                        <th width="12%">Tayangan</th>
                        <th width="12%">Interaksi</th>
                        <th width="16%" style="text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $metrics->filter(fn($m) => strtolower($m->platform) === 'tiktok'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $metric): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php $id_asli = explode('-', $metric->id_konten)[1] ?? $metric->id_konten; ?>
                    <tr>
                        <td><?php echo e(\Carbon\Carbon::parse($metric->tgl_upload)->format('d M, Y')); ?></td>
                        <td style="font-weight: 500; max-width: 250px;">
                            <div class="topic-text" title="<?php echo e($metric->judul_konten); ?>"><?php echo e($metric->judul_konten); ?></div>
                        </td>
                        <td><span class="badge badge-stable"><?php echo e($metric->jenis); ?></span></td>
                        <td><?php echo e(number_format($metric->reach)); ?></td>
                        <td><?php echo e(number_format($metric->total_interaksi ?? 0)); ?></td>
                        <td>
                            <div style="display:flex; gap:12px; align-items:center; justify-content: center;">
                                <a href="javascript:void(0)" onclick='openDetailModal(<?php echo json_encode($metric, 15, 512) ?>)' title="Detail" style="color: var(--primary); transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                </a>
                                <a href="javascript:void(0)" onclick="editTikTok('<?php echo e($id_asli); ?>', <?php echo e(json_encode($metric)); ?>)" title="Edit" style="color: #f59e0b; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 1 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                </a>
                                <form action="<?php echo e(route('dashboard.metrics.destroy', ['tiktok', $id_asli])); ?>" method="POST" style="margin:0;">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button type="submit" title="Hapus" style="background:none; border:none; padding:0; color:#ef4444; cursor:pointer; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="6" style="text-align: center; color: #64748b; padding: 2rem;">Belum ada data riwayat.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
function editTiktok(id_konten, data) {
    const form = document.getElementById('form-tiktok');
    form.action = "<?php echo e(url('dashboard/metrics')); ?>/tiktok/" + id_konten;
    document.getElementById('method-container-tiktok').innerHTML = '<input type="hidden" name="_method" value="PUT">';
    document.getElementById('metric-id-tiktok').value = id_konten;
    form.querySelector('[name="category"]').value = data.kategori || '';
    if(data.tgl_upload) form.querySelector('[name="publish_date"]').value = data.tgl_upload.substring(0, 10);
    form.querySelector('[name="title"]').value = data.judul_konten || '';
    form.querySelector('[name="content_type"]').value = data.jenis || 'Short Video';
    form.querySelector('[name="url"]').value = data.tautan || '';
    form.querySelector('[name="views"]').value = data.reach || 0;
    form.querySelector('[name="save"]').value = data.saves || 0;
    form.querySelector('[name="like"]').value = data.likes || 0;
    form.querySelector('[name="comment"]').value = data.comments || 0;
    form.querySelector('[name="share"]').value = data.shares || 0;
    
    document.getElementById('btn-submit-tiktok').innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-right:4px;"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 1 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg> Update Data';
    document.getElementById('btn-reset-tiktok').style.display = 'none';
    document.getElementById('btn-cancel-tiktok').style.display = 'flex';
    
    form.classList.remove('form-edit-highlight');
    void form.offsetWidth;
    form.classList.add('form-edit-highlight');
    
    window.scrollTo({ top: form.offsetTop - 80, behavior: 'smooth' });
}
function resetFormTiktok() {
    const form = document.getElementById('form-tiktok');
    form.reset();
    form.action = "<?php echo e(route('dashboard.metrics.store')); ?>";
    document.getElementById('method-container-tiktok').innerHTML = '';
    document.getElementById('metric-id-tiktok').value = '';
    document.getElementById('btn-submit-tiktok').innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-right:4px;"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg> Save Data';
    document.getElementById('btn-reset-tiktok').style.display = 'flex';
    document.getElementById('btn-cancel-tiktok').style.display = 'none';
    form.classList.remove('form-edit-highlight');
}
</script><?php /**PATH C:\laragon\www\InsightHubBaru\frontend\resources\views/pages/Input/tiktok.blade.php ENDPATH**/ ?>
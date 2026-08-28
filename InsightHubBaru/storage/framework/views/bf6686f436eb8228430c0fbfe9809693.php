<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap');
    
    .custom-modal-scroll::-webkit-scrollbar {
        width: 6px;
    }
    .custom-modal-scroll::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-modal-scroll::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }
    .custom-modal-scroll::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
    
    .lap-modal {
        transition: opacity 0.3s ease;
    }
</style>

<div id="modal-export-approval" class="lap-modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px); z-index: 9999; justify-content: center; align-items: center;">
    <div class="lap-modal-content" style="background: #fff; width: 440px; max-height: 85vh; overflow: hidden; border-radius: 16px; padding: 24px; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04), 0 0 0 1px rgba(0, 0, 0, 0.05); position: relative; display: flex; flex-direction: column; box-sizing: border-box; font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;">
        
        <!-- Close Button -->
        <button type="button" onclick="closeApprovalModal()" style="position: absolute; top: 18px; right: 18px; background: #f1f5f9; border: none; border-radius: 50%; width: 28px; height: 28px; display: flex; align-items: center; justify-content: center; color: #64748b; cursor: pointer; transition: all 0.2s ease; outline: none;" onmouseover="this.style.background='#e2e8f0'; this.style.color='#1e293b';" onmouseout="this.style.background='#f1f5f9'; this.style.color='#64748b';">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 14px; height: 14px;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
            </svg>
        </button>

        <!-- Header -->
        <div style="display: flex; align-items: flex-start; gap: 16px; margin-bottom: 20px;">
            <div style="flex-shrink: 0; display: flex; align-items: center; justify-content: center; width: 44px; height: 44px; border-radius: 12px; background: #fff7ed; border: 1px solid #ffedd5; color: #ea580c;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 24px; height: 24px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0-10.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.75c0 5.592 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.57-.598-3.75h-.152c-3.196 0-6.1-1.249-8.25-3.286Zm0 13.036h.008v.008H12v-.008Z" />
                </svg>
            </div>
            <div style="flex-grow: 1; padding-right: 20px; box-sizing: border-box;">
                <h2 style="margin: 0 0 4px 0; font-size: 1.15rem; font-weight: 700; color: #0f172a; letter-spacing: -0.01em;">Permintaan Export Baru</h2>
                <p style="margin: 0; font-size: 0.825rem; color: #64748b; line-height: 1.4;">Ada permintaan export dokumen yang menunggu persetujuan Anda.</p>
            </div>
        </div>
        
        <?php $pendingReqs = $exportRequests->where('status', 'pending'); ?>
        
        <?php if(session('success')): ?>
        <div style="background: #ecfdf5; border: 1px solid #d1fae5; color: #065f46; padding: 12px 14px; border-radius: 10px; margin-bottom: 18px; font-size: 0.85rem; display: flex; align-items: center; gap: 8px; font-family: inherit;">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" style="width: 18px; height: 18px; color: #10b981; flex-shrink: 0;">
                <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z" clip-rule="evenodd" />
            </svg>
            <span><?php echo e(session('success')); ?></span>
        </div>
        <?php endif; ?>
        
        <?php if($pendingReqs->count() > 0): ?>
            <div style="font-size: 0.85rem; color: #64748b; margin-bottom: 12px; font-weight: 500;">Terdapat <span style="color: #ea580c; font-weight: 600;"><?php echo e($pendingReqs->count()); ?> permintaan</span> baru.</div>
            <div class="custom-modal-scroll" style="overflow-y: auto; flex-grow: 1; padding-right: 4px; box-sizing: border-box;">
                <?php $__currentLoopData = $pendingReqs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $req): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div style="border: 1px solid #e2e8f0; border-left: 4px solid #f97316; border-radius: 10px; padding: 14px; margin-bottom: 14px; background: #f8fafc; transition: all 0.2s ease;">
                    
                    <!-- Card Header -->
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <div style="display: flex; align-items: center; justify-content: center; width: 24px; height: 24px; border-radius: 50%; background: #e2e8f0; color: #475569; font-size: 0.75rem; font-weight: 600;">
                                <?php echo e(strtoupper(substr($req->user->name ?? 'U', 0, 1))); ?>

                            </div>
                            <span style="font-weight: 600; font-size: 0.85rem; color: #1e293b;"><?php echo e($req->user->name ?? 'Unknown'); ?></span>
                        </div>
                        <span style="background: #fff7ed; color: #c2410c; border: 1px solid #ffedd5; padding: 2px 8px; border-radius: 6px; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em;">
                            <?php echo e($req->type); ?>

                        </span>
                    </div>
                    
                    <!-- Card Body -->
                    <div style="display: flex; flex-direction: column; gap: 6px; margin-bottom: 12px; font-size: 0.8rem; color: #475569; line-height: 1.45;">
                        <div>
                            <strong style="color: #64748b; font-weight: 500;">Alasan:</strong> 
                            <span style="color: #1e293b;"><?php echo e($req->reason); ?></span>
                        </div>
                        <?php if($req->details): ?>
                        <div style="background: #f1f5f9; padding: 6px 10px; border-radius: 6px; border: 1px solid #e2e8f0; font-size: 0.775rem; color: #475569; margin-top: 2px;">
                            <strong style="color: #64748b; font-weight: 500;">Ket:</strong> <?php echo e($req->details); ?>

                        </div>
                        <?php endif; ?>
                        <div style="display: flex; align-items: center; gap: 6px; color: #94a3b8; font-size: 0.75rem; margin-top: 4px;">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 13px; height: 13px;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                            <span><?php echo e($req->created_at->format('d M Y H:i')); ?></span>
                        </div>
                    </div>
                    
                    <!-- Card Actions -->
                    <div style="display: flex; justify-content: flex-end; gap: 8px; border-top: 1px dashed #e2e8f0; padding-top: 10px; margin-top: 10px;">
                        <button type="button" onclick="openRejectModal(<?php echo e($req->id); ?>)" style="padding: 6px 12px; background: #fff; color: #ef4444; border: 1px solid #fca5a5; border-radius: 6px; cursor: pointer; font-size: 0.8rem; font-weight: 600; transition: all 0.2s;" onmouseover="this.style.background='#fef2f2';" onmouseout="this.style.background='#fff';">
                            Tolak
                        </button>
                        <form action="<?php echo e(route('export-requests.approve', $req->id)); ?>" method="POST" style="margin: 0;">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PATCH'); ?>
                            <button type="submit" style="padding: 6px 12px; background: #10b981; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 0.8rem; font-weight: 600; transition: all 0.2s;" onmouseover="this.style.background='#059669';" onmouseout="this.style.background='#10b981';">
                                Setujui
                            </button>
                        </form>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php else: ?>
            <div style="text-align: center; padding: 32px 16px; font-family: inherit;">
                <div style="display: inline-flex; align-items: center; justify-content: center; width: 48px; height: 48px; border-radius: 50%; background: #f0fdf4; border: 1px solid #d1fae5; color: #15803d; margin-bottom: 12px;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 24px; height: 24px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </div>
                <h3 style="font-size: 0.9rem; font-weight: 600; color: #1e293b; margin: 0 0 4px 0;">Tidak Ada Permintaan Baru</h3>
                <p style="font-size: 0.775rem; color: #64748b; margin: 0;">Semua permintaan export telah diproses.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<div id="rejectModal" class="lap-modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px); z-index: 10000; justify-content: center; align-items: center;">
    <div class="lap-modal-content" style="background: #fff; width: 400px; border-radius: 16px; padding: 24px; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04), 0 0 0 1px rgba(0, 0, 0, 0.05); font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif; box-sizing: border-box;">
        
        <div style="display: flex; align-items: flex-start; gap: 14px; margin-bottom: 20px;">
            <div style="flex-shrink: 0; display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; border-radius: 10px; background: #fef2f2; border: 1px solid #fee2e2; color: #dc2626;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 20px; height: 20px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                </svg>
            </div>
            <div>
                <h2 style="margin: 0 0 4px 0; font-size: 1.1rem; font-weight: 700; color: #0f172a;">Tolak Permintaan</h2>
                <p style="margin: 0; font-size: 0.8rem; color: #64748b; line-height: 1.4;">Tindakan ini memerlukan alasan penolakan tertulis.</p>
            </div>
        </div>

        <form id="rejectForm" action="" method="POST" style="margin: 0;">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PATCH'); ?>
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 6px; font-weight: 600; font-size: 0.85rem; color: #334155;">Alasan Penolakan <span style="color: #ef4444;">*</span></label>
                <textarea name="reject_reason" required rows="3" style="width: 100%; box-sizing: border-box; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.85rem; font-family: inherit; transition: all 0.2s ease; outline: none; resize: vertical; min-height: 80px;" placeholder="Berikan alasan mengapa permintaan ini ditolak..." onfocus="this.style.borderColor='#ef4444'; this.style.boxShadow='0 0 0 3px rgba(239, 68, 68, 0.15)';" onblur="this.style.borderColor='#cbd5e1'; this.style.boxShadow='none';"></textarea>
            </div>
            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" onclick="closeRejectModal()" style="padding: 8px 16px; border: 1px solid #cbd5e1; background: #fff; color: #475569; border-radius: 8px; cursor: pointer; font-size: 0.85rem; font-weight: 600; transition: all 0.2s;" onmouseover="this.style.background='#f8fafc'; this.style.color='#1e293b';" onmouseout="this.style.background='#fff'; this.style.color='#475569';">Batal</button>
                <button type="submit" style="padding: 8px 16px; border: none; background: #ef4444; color: #fff; border-radius: 8px; cursor: pointer; font-size: 0.85rem; font-weight: 600; transition: all 0.2s;" onmouseover="this.style.background='#dc2626';" onmouseout="this.style.background='#ef4444';">Tolak</button>
            </div>
        </form>
    </div>
</div>

<script>
function openRejectModal(id) {
    document.getElementById('rejectForm').action = '/export-requests/' + id + '/reject';
    document.getElementById('rejectModal').style.display = 'flex';
}
function closeRejectModal() {
    document.getElementById('rejectModal').style.display = 'none';
}
function openApprovalModal() {
    document.getElementById('modal-export-approval').style.display = 'flex';
}
function closeApprovalModal() {
    document.getElementById('modal-export-approval').style.display = 'none';
}
</script>
<?php /**PATH D:\magang\InsightHubBaru\frontend\resources\views/pages/Export/approval.blade.php ENDPATH**/ ?>
<?php $__env->startPush('css'); ?>
<style>
    .mk-container {
        padding: 24px 32px;
        background-color: #f8fafc;
        min-height: calc(100vh - 60px);
        font-family: 'Inter', sans-serif;
    }
    .mk-page-header {
        width: 100%;
        margin: 0 0 32px 0;
        padding: 0;
        display: block;
    }
    .mk-page-header h1 {
        font-size: 1.75rem;
        font-weight: 700;
        color: #0f172a;
        margin: 0;
        padding: 0;
        letter-spacing: -0.5px;
        text-align: left;
    }
    .mk-page-header p {
        color: #64748b;
        margin: 8px 0 0 0;
        padding: 0;
        text-align: left;
        font-size: 15px;
    }
    .mk-alert-success {
        background-color: #ecfdf5;
        border-left: 4px solid #10b981;
        color: #065f46;
        padding: 16px;
        border-radius: 8px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        gap: 12px;
        font-weight: 500;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }
    .mk-card {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05), 0 2px 4px -2px rgba(0,0,0,0.025);
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }
    .mk-card-body {
        padding: 24px;
    }
    .mk-card-title {
        font-size: 18px;
        font-weight: 600;
        color: #1e293b;
        margin: 0 0 6px 0;
        display: flex;
        align-items: center;
        justify-content: flex-start;
        text-align: left;
        gap: 8px;
    }
    .mk-card-subtitle {
        font-size: 14px;
        color: #64748b;
        margin: 0 0 24px 0;
        line-height: 1.5;
        text-align: left;
    }
    .mk-label {
        display: block;
        font-size: 14px;
        font-weight: 600;
        color: #334155;
        margin-bottom: 8px;
    }
    .mk-input {
        width: 100%;
        height: 44px;
        padding: 0 14px;
        font-size: 15px;
        color: #0f172a;
        background-color: #fff;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        transition: all 0.2s;
        box-sizing: border-box;
    }
    .mk-input:focus {
        outline: none;
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }
    .mk-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        background-color: #2563eb;
        color: #fff;
        font-size: 15px;
        font-weight: 600;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s;
    }
    .mk-btn:hover {
        background-color: #1d4ed8;
    }
    .mk-table-wrapper {
        overflow-x: auto;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
    }
    .mk-table {
        width: 100%;
        border-collapse: collapse;
        white-space: nowrap;
    }
    .mk-table th {
        background-color: #f8fafc;
        color: #475569;
        font-size: 13px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 16px 24px;
        text-align: left;
        border-bottom: 1px solid #e2e8f0;
    }
    .mk-table td {
        padding: 16px 24px;
        color: #334155;
        font-size: 14px;
        border-bottom: 1px solid #f1f5f9;
    }
    .mk-table tbody tr:hover {
        background-color: #f8fafc;
    }
    .checkbox-cell {
        text-align: center;
    }
    .checkbox-input {
        width: 18px;
        height: 18px;
        cursor: pointer;
        accent-color: #2563eb;
        transition: transform 0.1s;
    }
    .checkbox-input:active {
        transform: scale(0.9);
    }
    .menu-header-row {
        background-color: #f1f5f9;
        font-weight: bold;
    }
    .menu-header-row td {
        color: #1e293b;
        font-size: 14px;
        border-bottom: 2px solid #cbd5e1;
        padding: 12px 24px;
    }
    .mk-badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 10px;
        font-size: 12px;
        font-weight: 600;
        border-radius: 999px;
    }
    .mk-badge-super {
        background-color: #fef2f2;
        color: #dc2626;
        border: 1px solid #fecaca;
    }
    .mk-badge-admin {
        background-color: #eff6ff;
        color: #2563eb;
        border: 1px solid #bfdbfe;
    }
    .mk-badge-user {
        background-color: #f0fdf4;
        color: #16a34a;
        border: 1px solid #bbf7d0;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="mk-container">
    <!-- Header -->
    <div class="mk-page-header">
        <h1>Manajemen Akses & User Mapping</h1>
        <p>Atur hak akses menu dan fitur dashboard secara spesifik untuk masing-masing akun karyawan dan manajemen.</p>
    </div>

    <!-- Alert Success -->
    <?php if(session('success')): ?>
        <div class="mk-alert-success">
            <i class="ph ph-check-circle" style="font-size: 20px;"></i>
            <span><?php echo e(session('success')); ?></span>
        </div>
    <?php endif; ?>

    <!-- Dropdown User Selector -->
    <div class="mk-card" style="margin-bottom: 24px;">
        <div class="mk-card-body" style="padding: 20px;">
            <form id="user-select-form" action="<?php echo e(route('role-mapping.index')); ?>" method="GET" style="display: flex; flex-direction: row; gap: 16px; align-items: flex-end; width: 100%; flex-wrap: wrap;">
                <div style="flex-grow: 1; min-width: 280px; text-align: left;">
                    <label class="mk-label" for="user_id_select">Pilih Akun Karyawan / Tim Manajemen:</label>
                    <select name="user_id" id="user_id_select" class="mk-input" onchange="document.getElementById('user-select-form').submit()" style="cursor: pointer; width: 100%; appearance: none; background-image: url('data:image/svg+xml;utf8,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%2216%22 height=%2216%22 fill=%22%23475569%22 viewBox=%220 0 256 256%22><path d=%22M213.66,101.66l-80,80a8,8,0,0,1-11.31,0l-80-80a8,8,0,0,1,11.31-11.31L128,164.69l74.34-74.34a8,8,0,0,1,11.31,11.31Z%22></path></svg>'); background-repeat: no-repeat; background-position: right 14px center; padding-right: 40px;">
                        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($user->id); ?>" <?php echo e($selectedUser->id === $user->id ? 'selected' : ''); ?>>
                                <?php echo e($user->name); ?> (<?php echo e($user->username); ?>) — Role Utama: <?php echo e(ucfirst($user->role)); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                
                <?php if($isCustomRole): ?>
                    <div style="flex-shrink: 0;">
                        <button type="button" 
                                onclick="if(confirm('Apakah Anda yakin ingin me-reset akses akun ini kembali mengikuti pengaturan default role?')) { document.getElementById('reset-form').submit(); }" 
                                class="mk-btn" 
                                style="background-color: #ef4444; color: #ffffff; height: 44px; padding: 0 16px; width: auto; margin: 0;">
                            <i class="ph ph-arrow-counter-clockwise" style="font-size: 18px; vertical-align: middle; margin-right: 4px;"></i>
                            Reset ke Default Role
                        </button>
                    </div>
                <?php endif; ?>
            </form>

            <!-- Separate reset form (POST) -->
            <?php if($isCustomRole): ?>
                <form id="reset-form" action="<?php echo e(route('role-mapping.reset')); ?>" method="POST" style="display: none;">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="user_id" value="<?php echo e($selectedUser->id); ?>">
                </form>
            <?php endif; ?>
        </div>
    </div>

    <!-- Main Grid Matrix Form -->
    <form action="<?php echo e(route('role-mapping.update')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="user_id" value="<?php echo e($selectedUser->id); ?>">

        <div class="mk-card">
            <div class="mk-card-body">
                
                <!-- Header with Action Button -->
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 12px;">
                    <div>
                        <div class="mk-card-title" style="display: flex; align-items: center; flex-wrap: wrap; gap: 8px;">
                            <span>Mengonfigurasi Akses: <strong><?php echo e($selectedUser->name); ?></strong></span>
                            <span class="mk-badge <?php echo e($selectedUser->role === 'super-admin' ? 'mk-badge-super' : ($selectedUser->role === 'admin' ? 'mk-badge-admin' : 'mk-badge-user')); ?>">
                                <?php echo e(ucfirst($selectedUser->role)); ?>

                            </span>
                            <?php if($isCustomRole): ?>
                                <span class="mk-badge" style="background-color: #fef3c7; color: #d97706; border: 1px solid #fde68a;">
                                    Izin Khusus Aktif
                                </span>
                            <?php else: ?>
                                <span class="mk-badge" style="background-color: #f3f4f6; color: #4b5563; border: 1px solid #e5e7eb;">
                                    Sesuai Default Role
                                </span>
                            <?php endif; ?>
                        </div>
                        <div style="font-size: 13px; color: #64748b; margin-top: 4px;">
                            Email: <strong><?php echo e($selectedUser->email); ?></strong> | Username: <strong><?php echo e($selectedUser->username); ?></strong>
                        </div>
                    </div>
                    <button type="submit" class="mk-btn" style="height: 40px; padding: 0 20px; font-size: 14px;">
                        <i class="ph ph-floppy-disk" style="font-size: 18px; vertical-align: middle; margin-right: 4px;"></i>
                        Simpan Hak Akses Akun
                    </button>
                </div>

                <!-- Alert for Super Admin safety -->
                <?php if($selectedUser->role === 'super-admin'): ?>
                    <div style="background-color: #fffbeb; border-left: 4px solid #f59e0b; color: #78350f; padding: 14px 16px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; font-weight: 500; display: flex; align-items: center; gap: 10px;">
                        <i class="ph ph-warning" style="font-size: 20px; color: #d97706; flex-shrink: 0;"></i>
                        <span><strong>Perhatian:</strong> Akun ini bertipe `Super Admin`. Menghapus centang pada matriks akses di bawah dapat membatasi kemampuan administrasi akun ini. Harap berhati-hati.</span>
                    </div>
                <?php endif; ?>

                <!-- Checkbox Matrix Table -->
                <div class="mk-table-wrapper">
                    <table class="mk-table">
                        <thead>
                            <tr>
                                <th style="width: 40%;">Menu & Detail Fitur</th>
                                <?php $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <th style="text-align: center; width: 15%;"><?php echo e($permission->name); ?></th>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $menus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <!-- Main Menu Header Row -->
                                <?php
                                    $menuHasActivePermission = false;
                                    foreach ($menu->details as $detail) {
                                        if (isset($activeMappings[$detail->id]) && !empty($activeMappings[$detail->id])) {
                                            $menuHasActivePermission = true;
                                            break;
                                        }
                                    }
                                ?>
                                <tr class="menu-header-row">
                                    <td colspan="<?php echo e(1 + count($permissions)); ?>">
                                        <div style="display: flex; align-items: center; justify-content: space-between;">
                                            <div>
                                                <input type="checkbox" 
                                                       class="menu-parent-checkbox" 
                                                       data-menu-id="<?php echo e($menu->id); ?>" 
                                                       <?php echo e($menuHasActivePermission ? 'checked' : ''); ?>

                                                       style="width: 18px; height: 18px; cursor: pointer; accent-color: #2563eb; margin-right: 8px; vertical-align: middle;">
                                                <i class="ph ph-folder-open" style="vertical-align: middle; margin-right: 6px; font-size: 18px; color: #1e293b;"></i>
                                                <span style="vertical-align: middle; color: #1e293b; font-weight: 700;"><?php echo e($menu->name); ?></span>
                                            </div>
                                            <span style="font-size: 11px; font-weight: 500; color: #475569; background: #e2e8f0; padding: 2px 8px; border-radius: 4px;">
                                                Menu Utama
                                            </span>
                                        </div>
                                    </td>
                                </tr>

                                <?php if($menu->details->isEmpty()): ?>
                                    <tr>
                                        <td colspan="<?php echo e(1 + count($permissions)); ?>" style="text-align: center; color: #94a3b8; padding: 16px; font-style: italic;">
                                            Tidak ada sub-detail untuk menu ini.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php $__currentLoopData = $menu->details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <!-- Detail Menu Info -->
                                            <td style="padding-left: 40px; font-weight: 500;">
                                                <div style="display: flex; align-items: center; gap: 8px;">
                                                    <i class="ph ph-arrow-bend-down-right" style="color: #94a3b8; font-size: 16px;"></i>
                                                    <div>
                                                        <div><?php echo e($detail->name); ?></div>
                                                        <div style="font-size: 11px; color: #94a3b8; font-weight: 400; margin-top: 2px;">
                                                            Slug: <?php echo e($detail->slug); ?>

                                                        </div>
                                                    </div>
                                                </div>
                                            </td>

                                            <!-- Permission Columns (View, Create, Edit, Delete) -->
                                            <?php $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php
                                                    $checked = isset($activeMappings[$detail->id]) && in_array($permission->id, $activeMappings[$detail->id]);
                                                ?>
                                                <td class="checkbox-cell">
                                                    <input type="checkbox" 
                                                           name="mapping[<?php echo e($detail->id); ?>][<?php echo e($permission->id); ?>]" 
                                                           value="1" 
                                                           class="checkbox-input child-of-<?php echo e($menu->id); ?>" 
                                                           data-menu-id="<?php echo e($menu->id); ?>"
                                                           <?php echo e($checked ? 'checked' : ''); ?>>
                                                </td>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>

                <!-- Bottom Save Button -->
                <div style="margin-top: 24px; display: flex; justify-content: flex-end;">
                    <button type="submit" class="mk-btn" style="height: 46px; padding: 0 24px;">
                        <i class="ph ph-floppy-disk" style="font-size: 20px; vertical-align: middle; margin-right: 6px;"></i>
                        Simpan Konfigurasi Akses Akun
                    </button>
                </div>

            </div>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        // 1. Event listener ketika Checkbox Menu Utama (Parent) diklik
        document.querySelectorAll('.menu-parent-checkbox').forEach(parentCheckbox => {
            parentCheckbox.addEventListener('change', function() {
                const menuId = this.getAttribute('data-menu-id');
                const children = document.querySelectorAll('.child-of-' + menuId);
                
                children.forEach(child => {
                    // Ketika Menu Utama tercentang, semua akses di bawahnya dicentang
                    // Ketika Menu Utama batal tercentang, semua di bawahnya dikosongkan
                    child.checked = this.checked;
                });
            });
        });

        // 2. Event listener ketika checkbox anak (Menu Detail) diklik secara manual
        document.querySelectorAll('.checkbox-input').forEach(childCheckbox => {
            childCheckbox.addEventListener('change', function() {
                const menuId = this.getAttribute('data-menu-id');
                if (!menuId) return;

                const parent = document.querySelector('.menu-parent-checkbox[data-menu-id="' + menuId + '"]');
                if (!parent) return;

                // Cek apakah ada minimal satu anak yang tercentang
                const children = document.querySelectorAll('.child-of-' + menuId);
                const anyChecked = Array.from(children).some(child => child.checked);
                
                // Jika ada anak tercentang, parent wajib ikut tercentang
                parent.checked = anyChecked;
            });
        });

    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\magang\InsightHubBaru\frontend\resources\views/pages/Master/role_mapping.blade.php ENDPATH**/ ?>
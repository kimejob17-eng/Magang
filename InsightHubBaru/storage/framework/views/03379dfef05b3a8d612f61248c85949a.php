<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InsightHub — Profil Saya</title>
    <meta name="description" content="Kelola profil dan pengaturan akun InsightHub Anda.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web@2.1.1"></script>
    <link rel="stylesheet" href="<?php echo e(asset('css/dashboard.css')); ?>?v=<?php echo e(time()); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/profile.css')); ?>?v=<?php echo e(time()); ?>">
</head>
<body>

    <!-- ═══════════════════════════════
         SIDEBAR (same as dashboard)
    ════════════════════════════════ -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <h2>Analytics Pro</h2>
            <p>MARKETING DASHBOARD</p>
        </div>

        <nav class="sidebar-nav">
            <a class="nav-item" href="<?php echo e(route('dashboard')); ?>">
                <i class="ph ph-squares-four"></i> Ringkasan
            </a>
            <a class="nav-item" href="<?php echo e(route('dashboard')); ?>?tab=analitik">
                <i class="ph ph-chart-line-up"></i> Analitik Konten
            </a>
            <a class="nav-item" href="<?php echo e(route('dashboard')); ?>?tab=input">
                <i class="ph ph-plus-square"></i> Input Data
            </a>
            <a class="nav-item" href="<?php echo e(route('dashboard')); ?>?tab=laporan">
                <i class="ph ph-file-text"></i> Laporan
            </a>
            <a class="nav-item active" style="margin-top: 1rem;" href="<?php echo e(route('profile.show')); ?>">
                <i class="ph ph-gear"></i> Profile
            </a>
        </nav>

        <div class="sidebar-footer">
            <a class="user-logout-card" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" title="Logout">
                <?php if(auth()->user()->avatar): ?>
                    <img src="<?php echo e(asset(auth()->user()->avatar)); ?>" alt="Logout" class="user-card-avatar">
                <?php else: ?>
                    <div class="user-card-avatar-initials">
                        <?php echo e(strtoupper(substr(auth()->user()->name, 0, 2))); ?>

                    </div>
                <?php endif; ?>
                <span class="user-card-logout-text">Logout</span>
            </a>
            <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;">
                <?php echo csrf_field(); ?>
            </form>
        </div>
    </aside>

    <!-- ═══════════════════════════════
         MAIN CONTENT
    ════════════════════════════════ -->
    <main class="main-content">

        <!-- Topbar -->
        <header class="topbar">
            <div></div> <!-- empty div to push profile actions to the right -->

            <div class="topbar-actions">
                <div class="user-profile">
                    <img id="topbar-avatar" src="<?php echo e($user->avatar ? asset($user->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=003EA8&color=fff&bold=true'); ?>" alt="<?php echo e($user->name); ?>">
                </div>
            </div>
        </header>

        <!-- ═══════════════════════════════
             PROFILE CONTENT
        ════════════════════════════════ -->
        <div class="profile-page">

            <!-- ── Profile Hero Header ──────────────────── -->
            <div class="profile-hero">
                <div class="hero-gradient" aria-hidden="true"></div>
                <div class="hero-content">
                    <div class="hero-avatar" onclick="document.getElementById('pf-avatar-input').click()" style="cursor: pointer;" title="Ubah Foto Profil">
                        <img
                            id="hero-avatar-img"
                            src="<?php echo e($user->avatar ? asset($user->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&size=96&background=003ea8&color=fff&bold=true'); ?>"
                            alt="<?php echo e($user->name); ?>"
                            class="avatar-img"
                        >
                        <span class="avatar-badge avatar-camera-btn" aria-label="Ganti Foto">
                            <i class="ph-bold ph-camera"></i>
                        </span>
                    </div>
                    <div class="hero-info">
                        <h1 class="hero-name"><?php echo e($user->name); ?></h1>
                        <p class="hero-role">
                            <?php echo e($user->role ?? 'User'); ?>

                            <span class="hero-dot">•</span>
                            <?php echo e($user->email); ?>

                        </p>
                    </div>
                    <div class="hero-actions">
                        <button type="submit" form="profile-form" class="btn-save" id="btn-save-hero">
                            <i class="ph-bold ph-floppy-disk"></i>
                            <span>Simpan Perubahan</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- ── Alerts (flash messages) ──────────────── -->
            <?php if(session('success')): ?>
                <div class="profile-alert alert-success" role="alert">
                    <i class="ph-bold ph-check-circle"></i>
                    <span><?php echo e(session('success')); ?></span>
                    <button type="button" onclick="this.parentElement.remove()" aria-label="Tutup"><i class="ph ph-x"></i></button>
                </div>
            <?php endif; ?>

            <?php if(session('error')): ?>
                <div class="profile-alert alert-error" role="alert">
                    <i class="ph-bold ph-warning-circle"></i>
                    <span><?php echo e(session('error')); ?></span>
                    <button type="button" onclick="this.parentElement.remove()" aria-label="Tutup"><i class="ph ph-x"></i></button>
                </div>
            <?php endif; ?>

            <!-- ── Two-Column Grid ──────────────────────── -->
            <div class="profile-grid">

                <!-- LEFT COLUMN — Informasi Pribadi -->
                <section class="profile-card">
                    <div class="card-header">
                        <i class="ph-fill ph-identification-card card-icon"></i>
                        <div>
                            <h2 class="card-title">Informasi Pribadi</h2>
                            <p class="card-desc">Perbarui data personal, alamat email, dan lokasi tempat Anda bekerja.</p>
                        </div>
                    </div>

                    <form id="profile-form" action="<?php echo e(route('profile.update')); ?>" method="POST" enctype="multipart/form-data" class="profile-form">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>

                        <!-- Hidden Avatar File Input -->
                        <input type="file" name="avatar" id="pf-avatar-input" style="display: none;" accept="image/*">

                        <!-- Nama Lengkap -->
                        <div class="pf-group <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> has-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <label for="pf-name" class="pf-label">NAMA LENGKAP</label>
                            <div class="pf-input-wrap">
                                <i class="ph ph-user pf-input-icon"></i>
                                <input
                                    type="text" id="pf-name" name="name"
                                    class="pf-input"
                                    value="<?php echo e(old('name', $user->name)); ?>"
                                    placeholder="Masukkan nama lengkap"
                                    required
                                >
                            </div>
                            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="pf-error"><i class="ph ph-warning"></i> <?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Alamat Email -->
                        <div class="pf-group <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> has-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <label for="pf-email" class="pf-label">ALAMAT EMAIL</label>
                            <div class="pf-input-wrap">
                                <i class="ph ph-envelope pf-input-icon"></i>
                                <input
                                    type="email" id="pf-email" name="email"
                                    class="pf-input"
                                    value="<?php echo e(old('email', $user->email)); ?>"
                                    placeholder="email@insighthub.ai"
                                    required
                                >
                            </div>
                            <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="pf-error"><i class="ph ph-warning"></i> <?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Nomor Telepon -->
                        <div class="pf-group <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> has-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <label for="pf-phone" class="pf-label">NOMOR TELEPON</label>
                            <div class="pf-input-wrap">
                                <i class="ph ph-phone pf-input-icon"></i>
                                <input
                                    type="text" id="pf-phone" name="phone"
                                    class="pf-input"
                                    value="<?php echo e(old('phone', $user->phone)); ?>"
                                    placeholder="+62 812 3456 7890"
                                >
                            </div>
                            <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="pf-error"><i class="ph ph-warning"></i> <?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Lokasi -->
                        <div class="pf-group <?php $__errorArgs = ['location'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> has-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <label for="pf-location" class="pf-label">LOKASI</label>
                            <div class="pf-select-wrap">
                                <i class="ph ph-map-pin pf-input-icon pf-select-icon"></i>
                                <select id="pf-location" name="location" class="pf-select">
                                    <option value="">Pilih lokasi</option>
                                    <option value="Jakarta, Indonesia" <?php echo e(old('location', $user->location) == 'Jakarta, Indonesia' ? 'selected' : ''); ?>>Jakarta, Indonesia</option>
                                    <option value="Bandung, Indonesia" <?php echo e(old('location', $user->location) == 'Bandung, Indonesia' ? 'selected' : ''); ?>>Bandung, Indonesia</option>
                                    <option value="Surabaya, Indonesia" <?php echo e(old('location', $user->location) == 'Surabaya, Indonesia' ? 'selected' : ''); ?>>Surabaya, Indonesia</option>
                                    <option value="Yogyakarta, Indonesia" <?php echo e(old('location', $user->location) == 'Yogyakarta, Indonesia' ? 'selected' : ''); ?>>Yogyakarta, Indonesia</option>
                                    <option value="Semarang, Indonesia" <?php echo e(old('location', $user->location) == 'Semarang, Indonesia' ? 'selected' : ''); ?>>Semarang, Indonesia</option>
                                    <option value="Medan, Indonesia" <?php echo e(old('location', $user->location) == 'Medan, Indonesia' ? 'selected' : ''); ?>>Medan, Indonesia</option>
                                    <option value="Makassar, Indonesia" <?php echo e(old('location', $user->location) == 'Makassar, Indonesia' ? 'selected' : ''); ?>>Makassar, Indonesia</option>
                                    <option value="Denpasar, Indonesia" <?php echo e(old('location', $user->location) == 'Denpasar, Indonesia' ? 'selected' : ''); ?>>Denpasar, Indonesia</option>
                                </select>
                                <i class="ph ph-caret-down pf-select-arrow"></i>
                            </div>
                            <?php $__errorArgs = ['location'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="pf-error"><i class="ph ph-warning"></i> <?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                    </form>
                </section>

                <!-- RIGHT COLUMN — Pengaturan Akun -->
                <section class="profile-card">
                    <div class="card-header">
                        <i class="ph-fill ph-gear-six card-icon"></i>
                        <div>
                            <h2 class="card-title">Pengaturan Akun</h2>
                            <p class="card-desc">Kelola preferensi keamanan sandi dan detail sistem akun Anda.</p>
                        </div>
                    </div>

                    <!-- Keamanan — Ubah Password -->
                    <div class="pf-group">
                        <label class="pf-label">KEAMANAN</label>
                        <button type="button" class="pf-action-btn" id="btn-open-pw-modal" onclick="openPwModal()">
                            <i class="ph-bold ph-shield-check"></i>
                            <span>Ubah Password</span>
                            <i class="ph ph-caret-right pf-action-arrow"></i>
                        </button>
                    </div>

                    <!-- Bahasa -->
                    <div class="pf-group" style="margin-top: 1.5rem;">
                        <label for="pf-lang" class="pf-label">BAHASA</label>
                        <div class="pf-select-wrap">
                            <i class="ph ph-translate pf-input-icon pf-select-icon"></i>
                            <select id="pf-lang" class="pf-select" disabled>
                                <option selected>Bahasa Indonesia</option>
                                <option>English</option>
                            </select>
                            <i class="ph ph-caret-down pf-select-arrow"></i>
                        </div>
                    </div>

                    <!-- Akun Info -->
                    <div class="account-info">
                        <div class="info-row">
                            <span class="info-label">Bergabung sejak</span>
                            <span class="info-value"><?php echo e($user->created_at ? $user->created_at->translatedFormat('d F Y') : '-'); ?></span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Role</span>
                            <span class="info-badge"><?php echo e($user->role ?? 'User'); ?></span>
                        </div>
                    </div>

                    <!-- Logout -->
                    <div class="logout-section">
                        <form action="<?php echo e(route('logout')); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn-logout">
                                <i class="ph ph-sign-out"></i>
                                <span>Keluar dari Akun</span>
                            </button>
                        </form>
                    </div>

                </section>

            </div><!-- /.profile-grid -->

        </div><!-- /.profile-page -->

    </main>

    <!-- ═══════════════════════════════════════
         MODAL — Ubah Password
    ════════════════════════════════════════ -->
    <div class="modal-overlay" id="pw-modal" role="dialog" aria-modal="true" aria-labelledby="pw-modal-title">
        <div class="modal-card">
            <div class="modal-header">
                <div class="modal-icon-wrap">
                    <i class="ph-fill ph-lock-key"></i>
                </div>
                <div>
                    <h3 class="modal-title" id="pw-modal-title">Ubah Kata Sandi</h3>
                    <p class="modal-desc">Pastikan kata sandi baru Anda kuat dan mudah diingat.</p>
                </div>
                <button type="button" class="modal-close" onclick="closePwModal()" aria-label="Tutup">
                    <i class="ph ph-x"></i>
                </button>
            </div>

            <form action="<?php echo e(route('profile.password')); ?>" method="POST" class="modal-form" id="pw-form">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                <!-- Kata Sandi Saat Ini -->
                <div class="pf-group <?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> has-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <label for="pw-current" class="pf-label">KATA SANDI SAAT INI</label>
                    <div class="pf-input-wrap pf-pw-wrap">
                        <i class="ph ph-lock-simple pf-input-icon"></i>
                        <input type="password" id="pw-current" name="current_password" class="pf-input" placeholder="Masukkan kata sandi saat ini" required>
                        <button type="button" class="pw-eye" onclick="togglePw('pw-current', this)"><i class="ph ph-eye"></i></button>
                    </div>
                    <?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="pf-error"><i class="ph ph-warning"></i> <?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Kata Sandi Baru -->
                <div class="pf-group <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> has-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <label for="pw-new" class="pf-label">KATA SANDI BARU</label>
                    <div class="pf-input-wrap pf-pw-wrap">
                        <i class="ph ph-key pf-input-icon"></i>
                        <input type="password" id="pw-new" name="password" class="pf-input" placeholder="Minimal 8 karakter" required>
                        <button type="button" class="pw-eye" onclick="togglePw('pw-new', this)"><i class="ph ph-eye"></i></button>
                    </div>
                    <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="pf-error"><i class="ph ph-warning"></i> <?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Konfirmasi Kata Sandi -->
                <div class="pf-group">
                    <label for="pw-confirm" class="pf-label">KONFIRMASI KATA SANDI BARU</label>
                    <div class="pf-input-wrap pf-pw-wrap">
                        <i class="ph ph-shield-check pf-input-icon"></i>
                        <input type="password" id="pw-confirm" name="password_confirmation" class="pf-input" placeholder="Ulangi kata sandi baru" required>
                        <button type="button" class="pw-eye" onclick="togglePw('pw-confirm', this)"><i class="ph ph-eye"></i></button>
                    </div>
                </div>

                <div class="modal-actions">
                    <button type="button" class="btn-cancel" onclick="closePwModal()">Batal</button>
                    <button type="submit" class="btn-save">
                        <i class="ph-bold ph-floppy-disk"></i> Simpan Kata Sandi
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ═══════════════════════════════
         SCRIPTS
    ════════════════════════════════ -->
    <script>
        /* ── Password Modal ─────────────────────── */
        function openPwModal() {
            document.getElementById('pw-modal').classList.add('is-open');
            document.body.style.overflow = 'hidden';
        }
        function closePwModal() {
            document.getElementById('pw-modal').classList.remove('is-open');
            document.body.style.overflow = '';
        }
        // Close on overlay click
        document.getElementById('pw-modal').addEventListener('click', function(e) {
            if (e.target === this) closePwModal();
        });
        // Close on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closePwModal();
        });

        /* ── Auto-open modal if password validation fails ── */
        <?php if(session('show_password_modal') || $errors->has('current_password') || $errors->has('password')): ?>
            openPwModal();
        <?php endif; ?>

        /* ── Toggle Password Visibility ─────────── */
        function togglePw(inputId, btn) {
            const inp = document.getElementById(inputId);
            const icon = btn.querySelector('i');
            const isHidden = inp.type === 'password';
            inp.type = isHidden ? 'text' : 'password';
            icon.classList.toggle('ph-eye',       !isHidden);
            icon.classList.toggle('ph-eye-slash',  isHidden);
        }

        /* ── Input focus ring ───────────────────── */
        document.querySelectorAll('.pf-input').forEach(inp => {
            inp.addEventListener('focus', () => inp.closest('.pf-input-wrap').classList.add('focused'));
            inp.addEventListener('blur',  () => inp.closest('.pf-input-wrap').classList.remove('focused'));
        });

        /* ── Avatar Upload Preview ──────────────── */
        const avatarInput = document.getElementById('pf-avatar-input');
        const heroAvatarImg = document.getElementById('hero-avatar-img');
        const topbarAvatarImg = document.getElementById('topbar-avatar');

        if (avatarInput) {
            avatarInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        if (heroAvatarImg) heroAvatarImg.src = event.target.result;
                        if (topbarAvatarImg) topbarAvatarImg.src = event.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
    </script>
</body>
</html>
<?php /**PATH D:\Project-Magang\InsightHub\frontend\resources\views/pages/Profile/profile.blade.php ENDPATH**/ ?>
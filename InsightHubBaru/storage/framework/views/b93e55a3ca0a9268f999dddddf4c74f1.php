<div id="tab-input" class="dashboard-container">
    <div class="page-header" style="align-items: flex-start; margin-bottom: 2rem; display: flex; flex-wrap: wrap; gap: 1rem;">
        <div style="flex: 1; min-width: 300px;">
            <h1 style="font-size: 1.75rem; font-weight: 700; color: #0f172a; margin-bottom: 0.5rem; letter-spacing: -0.5px;">Kelola Data Pemasaran</h1>
            <p style="color: #64748b; font-size: 0.95rem; line-height: 1.5;">Impor data secara manual media sosial Anda untuk sinkronisasi otomatis.</p>
        </div>
        <div style="display: flex; gap: 0.75rem; align-items: flex-start;">
            <button type="button" class="btn" onclick="triggerCsvExport()" style="background: #ffffff; border: 1px solid #e2e8f0; color: #0f172a; font-weight: 600;">
                <i class="ph-bold ph-download-simple" style="color: #64748b;"></i> Export CSV
            </button>
        </div>
    </div>

    <?php if(session('success')): ?>
    <div style="background-color: #dcfce7; color: #166534; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.5rem; font-weight: 500; display: flex; align-items: center; gap: 0.5rem;">
        <i class="ph-fill ph-check-circle" style="font-size: 1.25rem;"></i> <?php echo e(session('success')); ?>

    </div>
    <?php endif; ?>

    <div class="platform-layout-grid" style="display: grid; grid-template-columns: 280px 1fr; gap: 2rem;">
        <!-- Left Sidebar: Platform Selector -->
        <div class="platform-selector-panel">
            <h3 style="font-size: 0.9rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 1rem;">Pilih Platform</h3>
            
            <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                <!-- Facebook Tab -->
                <div class="platform-btn active" onclick="switchPlatform('facebook', this)">
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <i class="ph-fill ph-facebook-logo icon-logo" style="font-size: 1.5rem; color: #64748b;"></i>
                        <span style="font-weight: 600; color: #334155;">Facebook</span>
                    </div>
                    <i class="ph-bold ph-check-circle check-icon" style="color: transparent;"></i>
                </div>

                <!-- Instagram Tab -->
                <div class="platform-btn" onclick="switchPlatform('instagram', this)">
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <i class="ph-fill ph-instagram-logo icon-logo" style="font-size: 1.5rem; color: #64748b;"></i>
                        <span style="font-weight: 600; color: #334155;">Instagram</span>
                    </div>
                    <i class="ph-bold ph-check-circle check-icon" style="color: transparent;"></i>
                </div>

                <!-- TikTok Tab -->
                <div class="platform-btn" onclick="switchPlatform('tiktok', this)">
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <i class="ph-fill ph-tiktok-logo icon-logo" style="font-size: 1.5rem; color: #64748b;"></i>
                        <span style="font-weight: 600; color: #334155;">TikTok</span>
                    </div>
                    <i class="ph-bold ph-check-circle check-icon" style="color: transparent;"></i>
                </div>

                <!-- YouTube Tab -->
                <div class="platform-btn" onclick="switchPlatform('youtube', this)">
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <i class="ph-fill ph-youtube-logo icon-logo" style="font-size: 1.5rem; color: #64748b;"></i>
                        <span style="font-weight: 600; color: #334155;">YouTube</span>
                    </div>
                    <i class="ph-bold ph-check-circle check-icon" style="color: transparent;"></i>
                </div>
            </div>
        </div>

        <!-- Right Content Area -->
        <div>
            <?php echo $__env->make('pages.Input.facebook', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php echo $__env->make('pages.Input.instagram', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php echo $__env->make('pages.Input.tiktok', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            
            <div id="content-youtube" class="platform-content" style="display: none;">

                <!-- YouTube Submenu Tabs -->
                <div class="yt-submenu-wrapper">
                    <button type="button" class="yt-submenu-btn active" onclick="switchYoutubeTab('live', this)">
                        <i class="ph-bold ph-broadcast"></i>
                        <span>Live</span>
                    </button>
                    <button type="button" class="yt-submenu-btn" onclick="switchYoutubeTab('video', this)">
                        <i class="ph-bold ph-video-camera"></i>
                        <span>Video</span>
                    </button>
                    <button type="button" class="yt-submenu-btn" onclick="switchYoutubeTab('shorts', this)">
                        <i class="ph-bold ph-device-mobile"></i>
                        <span>Shorts</span>
                    </button>
                </div>

                <?php echo $__env->make('pages.Input.youtube.live', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php echo $__env->make('pages.Input.youtube.video', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php echo $__env->make('pages.Input.youtube.shorts', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            </div>

        </div>
    </div>
</div>


<script>
let currentPlatform = 'facebook';
let currentYoutubeTab = 'live';

function switchPlatform(platform, element) {
    currentPlatform = platform;
    
    // Hide all platform contents
    document.querySelectorAll('.platform-content').forEach(content => {
        content.style.display = 'none';
        content.classList.remove('active');
    });
    
    // Show selected platform content
    const selectedContent = document.getElementById('content-' + platform);
    selectedContent.style.display = 'block';
    
    // Add small delay for animation
    setTimeout(() => {
        selectedContent.classList.add('active');
    }, 10);
    
    // Update platform buttons
    document.querySelectorAll('.platform-btn').forEach(btn => {
        btn.classList.remove('active');
        btn.querySelector('.check-icon').style.color = 'transparent';
    });
    
    // Set active button
    element.classList.add('active');
    element.querySelector('.check-icon').style.color = '#ffffff';
}

function switchYoutubeTab(tab, element) {
    currentYoutubeTab = tab;
    
    // Hide all YouTube tab contents
    document.querySelectorAll('.yt-tab-content').forEach(function(el) {
        el.style.display = 'none';
    });

    // Show selected tab
    var target = document.getElementById('yt-tab-' + tab);
    if (target) target.style.display = 'block';

    // Update submenu button states
    document.querySelectorAll('.yt-submenu-btn').forEach(function(btn) {
        btn.classList.remove('active');
    });
    element.classList.add('active');
}

function triggerCsvExport() {
    let targetPlatform = currentPlatform;
    if (currentPlatform === 'youtube') {
        targetPlatform = 'yt-' + currentYoutubeTab;
    }
    window.location.href = `<?php echo e(route('dashboard.export.csv')); ?>?platform=${targetPlatform}`;
}
</script>
<?php /**PATH D:\Project-Magang\InsightHub\frontend\resources\views\pages\Input\input.blade.php ENDPATH**/ ?>
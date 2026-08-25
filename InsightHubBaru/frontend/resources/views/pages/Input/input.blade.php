<style>
    /* Global Input CSS Enhancements */
    .platform-layout-grid {
        display: grid;
        grid-template-columns: 240px 1fr;
        gap: 2rem;
        background: transparent;
    }
    .platform-selector-panel {
        background: #f8fafc;
        border-radius: 12px;
        padding: 1.25rem;
        border: 1px solid #e2e8f0;
    }
    .platform-btn {
        transition: all 0.2s ease-in-out;
        border: 1px solid transparent;
        padding: 0.75rem 1rem;
        border-radius: 8px;
        cursor: pointer;
    }
    .platform-btn:not(.active):hover {
        background: #f1f5f9;
        border-color: #e2e8f0;
    }
    
    /* Table Styling */
    .data-table.table-hover tbody tr {
        transition: background-color 0.15s ease-in-out;
    }
    .data-table.table-hover tbody tr:hover {
        background-color: #f1f5f9 !important;
    }
    .topic-text {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
        line-height: 1.4;
        max-width: 300px;
    }
    
    /* Edit Highlight Anim */
    .form-edit-highlight {
        animation: highlightForm 2s ease-out forwards;
    }
    @keyframes highlightForm {
        0% { box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.7); border-color: #f59e0b; background-color: #fef3c7; }
        70% { box-shadow: 0 0 0 10px rgba(245, 158, 11, 0); }
        100% { border-color: #f59e0b; background-color: #fffbeb; }
    }
    .edit-badge {
        display: none;
        background: #f59e0b;
        color: white;
        padding: 2px 8px;
        border-radius: 4px;
        font-size: 10px;
        font-weight: bold;
        letter-spacing: 0.5px;
        margin-left: 10px;
        vertical-align: middle;
    }
</style>

<div id="tab-input" class="dashboard-container">
    <div class="page-header" style="align-items: flex-start; margin-bottom: 2rem; display: flex; flex-wrap: wrap; gap: 1rem;">
        <div style="flex: 1; min-width: 300px;">
            <h1 style="font-size: 1.75rem; font-weight: 700; color: #0f172a; margin-bottom: 0.5rem; letter-spacing: -0.5px;">Kelola Data Pemasaran</h1>
            <p style="color: #64748b; font-size: 0.95rem; line-height: 1.5;">Impor data secara manual media sosial Anda untuk sinkronisasi otomatis.</p>
        </div>
        <div style="display: flex; gap: 0.75rem; align-items: flex-start;">
            <button type="button" class="btn" onclick="triggerCsvExport()" style="background: #ffffff; border: 1px solid #e2e8f0; color: #0f172a; font-weight: 600;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 4px; color: #64748b;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg> Export CSV
            </button>
        </div>
    </div>

    @if(session('success'))
    <div class="auto-dismiss-alert" style="background: #ecfdf5; border-left: 4px solid #10b981; color: #047857; padding: 1rem; border-radius: 4px; margin-bottom: 1.5rem; display: flex; align-items: center; justify-content: space-between; font-size: 0.9rem; transition: opacity 0.5s ease;">
        <div style="display: flex; align-items: center;">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg> 
            {{ session('success') }}
        </div>
        <button type="button" onclick="this.parentElement.remove()" style="background: transparent; border: none; color: #047857; cursor: pointer; font-size: 1.2rem; line-height: 1;">&times;</button>
    </div>
    @endif

    @if(session('warning'))
    <div class="auto-dismiss-alert" style="background: #fffbeb; border-left: 4px solid #f59e0b; color: #b45309; padding: 1rem; border-radius: 4px; margin-bottom: 1.5rem; display: flex; align-items: center; justify-content: space-between; font-size: 0.9rem; transition: opacity 0.5s ease;">
        <div style="display: flex; align-items: center;">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg> 
            {{ session('warning') }}
        </div>
        <button type="button" onclick="this.parentElement.remove()" style="background: transparent; border: none; color: #b45309; cursor: pointer; font-size: 1.2rem; line-height: 1;">&times;</button>
    </div>
    @endif

    @if(session('error'))
    <div class="auto-dismiss-alert" style="background: #fef2f2; border-left: 4px solid #ef4444; color: #b91c1c; padding: 1rem; border-radius: 4px; margin-bottom: 1.5rem; display: flex; align-items: center; justify-content: space-between; font-size: 0.9rem; transition: opacity 0.5s ease;">
        <div style="display: flex; align-items: center;">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg> 
            {{ session('error') }}
        </div>
        <button type="button" onclick="this.parentElement.remove()" style="background: transparent; border: none; color: #b91c1c; cursor: pointer; font-size: 1.2rem; line-height: 1;">&times;</button>
    </div>
    @endif

    <!-- Custom Confirm Modal -->
    <div id="custom-confirm-modal" style="display: none; position: fixed; inset: 0; z-index: 9999; background: rgba(0,0,0,0.4); align-items: center; justify-content: center; opacity: 0; transition: opacity 0.2s ease; backdrop-filter: blur(2px);">
        <div style="background: white; border-radius: 12px; padding: 24px; max-width: 400px; width: 90%; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); transform: scale(0.95); transition: transform 0.2s ease;" id="custom-confirm-content">
            <div style="display: flex; align-items: center; margin-bottom: 16px;">
                <div style="background: #fee2e2; border-radius: 50%; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; margin-right: 12px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                </div>
                <h3 style="margin: 0; font-size: 1.15rem; font-weight: 600; color: #0f172a;">Konfirmasi Hapus</h3>
            </div>
            <p style="color: #475569; margin-bottom: 24px; font-size: 0.95rem; line-height: 1.5;">Apakah Anda yakin ingin menghapus data ini? Tindakan ini permanen dan tidak dapat dibatalkan.</p>
            <div style="display: flex; justify-content: flex-end; gap: 12px;">
                <button type="button" id="custom-confirm-cancel" style="padding: 8px 16px; background: #f1f5f9; color: #475569; border: none; border-radius: 6px; cursor: pointer; font-weight: 600; font-size: 0.9rem; transition: background 0.15s;" onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f1f5f9'">Batal</button>
                <button type="button" id="custom-confirm-ok" style="padding: 8px 16px; background: #ef4444; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 600; font-size: 0.9rem; transition: background 0.15s;" onmouseover="this.style.background='#dc2626'" onmouseout="this.style.background='#ef4444'">Ya, Hapus</button>
            </div>
        </div>
    </div>

    <script>
        // Auto-dismiss top banners
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.auto-dismiss-alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 500);
                }, 5000); // 5 detik
            });
        });
    </script>

    <div class="platform-layout-grid">
        <!-- Left Sidebar: Platform Selector -->
        <div class="platform-selector-panel">
            <h3 style="font-size: 0.9rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 1rem;">Pilih Platform</h3>
            
            <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                <!-- Facebook Tab -->
                <div class="platform-btn active" onclick="switchPlatform('facebook', this)">
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <div class="platform-icon-wrapper fb-icon-wrap">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon-logo"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg>
                        </div>
                        <span style="font-weight: 600; color: #334155;">Facebook</span>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="check-icon" style="color: transparent;"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                </div>

                <!-- Instagram Tab -->
                <div class="platform-btn" onclick="switchPlatform('instagram', this)">
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <div class="platform-icon-wrapper ig-icon-wrap">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon-logo"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>
                        </div>
                        <span style="font-weight: 600; color: #334155;">Instagram</span>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="check-icon" style="color: transparent;"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                </div>

                <!-- TikTok Tab -->
                <div class="platform-btn" onclick="switchPlatform('tiktok', this)">
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <div class="platform-icon-wrapper tt-icon-wrap">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon-logo"><path d="M9 12a4 4 0 1 0 4 4V4a5 5 0 0 0 5 5"></path></svg>
                        </div>
                        <span style="font-weight: 600; color: #334155;">TikTok</span>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="check-icon" style="color: transparent;"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                </div>

                <!-- YouTube Tab -->
                <div class="platform-btn" onclick="switchPlatform('youtube', this)">
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <div class="platform-icon-wrapper yt-icon-wrap">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon-logo"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.25 29 29 0 0 0-.46-5.33z"></path><polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02"></polygon></svg>
                        </div>
                        <span style="font-weight: 600; color: #334155;">YouTube</span>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="check-icon" style="color: transparent;"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                </div>
            </div>
        </div>

        <!-- Right Content Area -->
        <div>
            @include('pages.Input.facebook')
            @include('pages.Input.instagram')
            @include('pages.Input.tiktok')

            {{-- ================= YOUTUBE CONTENT ================= --}}
            <div id="content-youtube" class="platform-content" style="display: none;">

                <!-- YouTube Submenu Tabs -->
                <div class="yt-submenu-wrapper">
                    <button type="button" class="yt-submenu-btn active" onclick="switchYoutubeTab('live', this)">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 4px;"><path d="M4.9 19.1C1 15.2 1 8.8 4.9 4.9"></path><path d="M7.8 16.2c-2.3-2.3-2.3-6.1 0-8.5"></path><circle cx="12" cy="12" r="2"></circle><path d="M16.2 7.8c2.3 2.3 2.3 6.1 0 8.5"></path><path d="M19.1 4.9C23 8.8 23 15.2 19.1 19.1"></path></svg>
                        <span>Live</span>
                    </button>
                    <button type="button" class="yt-submenu-btn" onclick="switchYoutubeTab('video', this)">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 4px;"><polygon points="23 7 16 12 23 17 23 7"></polygon><rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect></svg>
                        <span>Video</span>
                    </button>
                    <button type="button" class="yt-submenu-btn" onclick="switchYoutubeTab('shorts', this)">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 4px;"><rect x="6" y="2" width="12" height="20" rx="2" ry="2"></rect><polygon points="10 15 15 12 10 9 10 15"></polygon></svg>
                        <span>Shorts</span>
                    </button>
                </div>

                @include('pages.Input.youtube.live')
                @include('pages.Input.youtube.video')
                @include('pages.Input.youtube.shorts')

            </div>

        </div>
    </div>
</div>

<!-- Modal Detail Data -->
<div id="detailModal" class="modal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
    <div style="background: #fff; width: 100%; max-width: 550px; border-radius: 12px; padding: 0; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04); overflow: hidden;">
        <div style="display: flex; justify-content: space-between; align-items: center; background: #f8fafc; border-bottom: 1px solid #e2e8f0; padding: 16px 24px;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div id="detailPlatformIcon" style="width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; background: #e2e8f0; color: #334155;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                </div>
                <h3 style="font-size: 1.1rem; font-weight: 700; color: #0f172a; margin: 0;">Detail Konten</h3>
            </div>
            <button type="button" onclick="closeDetailModal()" style="background: none; border: none; cursor: pointer; color: #94a3b8; padding: 4px; border-radius: 4px; transition: background 0.2s;">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
        </div>
        
        <div style="padding: 24px; max-height: 70vh; overflow-y: auto;">
            <div style="margin-bottom: 20px;">
                <div style="font-size: 0.8rem; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Topik / Judul Konten</div>
                <div id="detailTitle" style="font-size: 1.1rem; font-weight: 600; color: #0f172a; line-height: 1.5;">-</div>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px;">
                <div style="background: #f8fafc; padding: 12px; border-radius: 8px; border: 1px solid #f1f5f9;">
                    <div style="font-size: 0.75rem; color: #64748b; margin-bottom: 2px;">Platform</div>
                    <div id="detailPlatform" style="font-weight: 600; color: #334155; text-transform: capitalize;">-</div>
                </div>
                <div style="background: #f8fafc; padding: 12px; border-radius: 8px; border: 1px solid #f1f5f9;">
                    <div style="font-size: 0.75rem; color: #64748b; margin-bottom: 2px;">Kategori</div>
                    <div id="detailCategory" style="font-weight: 600; color: #334155;">-</div>
                </div>
                <div style="background: #f8fafc; padding: 12px; border-radius: 8px; border: 1px solid #f1f5f9;">
                    <div style="font-size: 0.75rem; color: #64748b; margin-bottom: 2px;">Jenis Konten</div>
                    <div id="detailType" style="font-weight: 600; color: #334155;">-</div>
                </div>
                <div style="background: #f8fafc; padding: 12px; border-radius: 8px; border: 1px solid #f1f5f9;">
                    <div style="font-size: 0.75rem; color: #64748b; margin-bottom: 2px;">Tanggal Upload</div>
                    <div id="detailDate" style="font-weight: 600; color: #334155;">-</div>
                </div>
            </div>
            
            <div style="border-top: 1px dashed #cbd5e1; margin: 24px 0;"></div>
            
            <h4 style="font-size: 0.9rem; font-weight: 600; color: #0f172a; margin-bottom: 12px;">Metrik Performa</h4>
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; margin-bottom: 20px;">
                <div style="text-align: center; padding: 16px 8px; border: 1px solid #e2e8f0; border-radius: 8px;">
                    <div style="font-size: 0.75rem; color: #64748b; text-transform: uppercase;">Reach</div>
                    <div id="detailReach" style="font-size: 1.25rem; font-weight: 700; color: #0f172a; margin-top: 4px;">0</div>
                </div>
                <div style="text-align: center; padding: 16px 8px; border: 1px solid #e2e8f0; border-radius: 8px;">
                    <div style="font-size: 0.75rem; color: #64748b; text-transform: uppercase;">Views</div>
                    <div id="detailViews" style="font-size: 1.25rem; font-weight: 700; color: #0f172a; margin-top: 4px;">0</div>
                </div>
                <div style="text-align: center; padding: 16px 8px; border: 1px solid #e2e8f0; border-radius: 8px;">
                    <div style="font-size: 0.75rem; color: #64748b; text-transform: uppercase;">Likes</div>
                    <div id="detailLikes" style="font-size: 1.25rem; font-weight: 700; color: #0f172a; margin-top: 4px;">0</div>
                </div>
                <div style="text-align: center; padding: 16px 8px; border: 1px solid #e2e8f0; border-radius: 8px;">
                    <div style="font-size: 0.75rem; color: #64748b; text-transform: uppercase;">Comments</div>
                    <div id="detailComments" style="font-size: 1.25rem; font-weight: 700; color: #0f172a; margin-top: 4px;">0</div>
                </div>
                <div style="text-align: center; padding: 16px 8px; border: 1px solid #e2e8f0; border-radius: 8px;">
                    <div style="font-size: 0.75rem; color: #64748b; text-transform: uppercase;">Shares</div>
                    <div id="detailShares" style="font-size: 1.25rem; font-weight: 700; color: #0f172a; margin-top: 4px;">0</div>
                </div>
                <div style="text-align: center; padding: 16px 8px; border: 1px solid #e2e8f0; border-radius: 8px;">
                    <div style="font-size: 0.75rem; color: #64748b; text-transform: uppercase;" id="detailExtraLabel">Saves</div>
                    <div id="detailExtraVal" style="font-size: 1.25rem; font-weight: 700; color: #0f172a; margin-top: 4px;">0</div>
                </div>
            </div>
            
            <div id="detailLinkContainer" style="display: none; text-align: center; margin-top: 16px;">
                <a id="detailLink" href="#" target="_blank" style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; background: #eff6ff; color: #2563eb; font-weight: 600; border-radius: 6px; text-decoration: none; border: 1px solid #bfdbfe; transition: all 0.2s;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg> Buka Tautan Asli
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Modal CRUD Kategori -->
<div id="kategoriCrudModal" class="modal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
    <div style="background: #fff; width: 100%; max-width: 500px; border-radius: 8px; padding: 24px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);">
        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e2e8f0; padding-bottom: 12px; margin-bottom: 16px;">
            <h3 style="font-size: 1.25rem; font-weight: 700; color: #0f172a; margin: 0;">Kelola Kategori <span id="kategoriPlatformLabel" style="text-transform: capitalize;"></span></h3>
            <button type="button" onclick="closeKategoriModal()" style="background: none; border: none; cursor: pointer; color: #64748b; font-size: 1.5rem;">&times;</button>
        </div>
        
        <div style="display: flex; gap: 8px; margin-bottom: 16px;">
            <input type="text" id="newKategoriName" class="form-control" placeholder="Nama Kategori Baru..." style="flex: 1; padding: 0.5rem; border: 1px solid #cbd5e1; border-radius: 0.375rem;">
            <button type="button" class="btn btn-primary" onclick="addKategori()" style="background: #2563eb; color: #fff; border: none; padding: 8px 16px; border-radius: 6px; cursor: pointer;">Tambah</button>
        </div>

        <div style="max-height: 300px; overflow-y: auto; border: 1px solid #e2e8f0; border-radius: 6px;">
            <table style="width: 100%; border-collapse: collapse;">
                <tbody id="kategoriListBody">
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Import Excel -->
<div id="importExcelModal" class="modal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
    <div style="background: #fff; width: 100%; max-width: 450px; border-radius: 12px; padding: 24px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);">
        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e2e8f0; padding-bottom: 12px; margin-bottom: 16px;">
            <h3 style="font-size: 1.25rem; font-weight: 700; color: #0f172a; margin: 0;">Import Data <span id="importPlatformLabel" style="text-transform: capitalize; color: #2563eb;"></span></h3>
            <button type="button" onclick="closeImportModal()" style="background: none; border: none; cursor: pointer; color: #64748b; font-size: 1.5rem;">&times;</button>
        </div>
        
        <form action="{{ route('dashboard.metrics.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="platform" id="importPlatformInput" value="">
            
            <div style="margin-bottom: 16px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #334155;">Pilih File Excel/CSV</label>
                <div style="border: 2px dashed #cbd5e1; border-radius: 8px; padding: 24px; text-align: center; background: #f8fafc;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 12px;"><path d="M14 2H6a2 2 0 0 0-2 2v16c0 1.1.9 2 2 2h12a2 2 0 0 0 2-2V8l-6-6z"></path><path d="M14 3v5h5M16 13H8M16 17H8M10 9H8"></path></svg>
                    <input type="file" name="excel_file" accept=".xlsx,.xls,.csv" required style="display: block; width: 100%; font-size: 0.9rem; color: #475569; background: white; padding: 8px; border: 1px solid #cbd5e1; border-radius: 4px; margin-top: 8px;">
                </div>
                <p style="font-size: 0.75rem; color: #64748b; margin-top: 8px;">* File harus memiliki header kolom di baris pertama (misal: Kategori, Topik/Judul, Views, Likes, dll).</p>
            </div>
            
            <div style="display: flex; justify-content: flex-end; gap: 12px;">
                <button type="button" onclick="closeImportModal()" style="background: #f1f5f9; color: #475569; border: none; padding: 8px 16px; border-radius: 6px; font-weight: 500; cursor: pointer;">Batal</button>
                <button type="submit" style="background: #10b981; color: #fff; border: none; padding: 8px 16px; border-radius: 6px; font-weight: 500; cursor: pointer; display: flex; align-items: center; gap: 6px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                    Upload File
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openImportModal(platform) {
    document.getElementById('importPlatformInput').value = platform;
    
    let label = platform;
    if (platform === 'yt-live') label = 'YouTube Live';
    if (platform === 'yt-video') label = 'YouTube Video';
    if (platform === 'yt-shorts') label = 'YouTube Shorts';
    
    document.getElementById('importPlatformLabel').textContent = label;
    document.getElementById('importExcelModal').style.display = 'flex';
}

function closeImportModal() {
    document.getElementById('importExcelModal').style.display = 'none';
}

let currentPlatform = 'facebook';
let currentYoutubeTab = 'live';

let currentKategoriPlatform = '';
let currentSelectElement = null;

function openDetailModal(data) {
    document.getElementById('detailTitle').textContent = data.judul_konten || '-';
    document.getElementById('detailPlatform').textContent = data.platform || '-';
    document.getElementById('detailCategory').textContent = data.kategori || '-';
    document.getElementById('detailType').textContent = data.jenis || '-';
    
    if (data.tgl_upload) {
        const d = new Date(data.tgl_upload);
        document.getElementById('detailDate').textContent = d.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
    } else {
        document.getElementById('detailDate').textContent = '-';
    }
    
    // Formatting numbers
    const formatNum = (num) => num != null ? new Intl.NumberFormat('id-ID').format(num) : '0';
    
    document.getElementById('detailReach').textContent = formatNum(data.reach);
    document.getElementById('detailViews').textContent = formatNum(data.views);
    document.getElementById('detailLikes').textContent = formatNum(data.likes);
    document.getElementById('detailComments').textContent = formatNum(data.comments);
    document.getElementById('detailShares').textContent = formatNum(data.shares);
    
    // Dynamic Extra field based on platform
    const platformLower = (data.platform || '').toLowerCase();
    let extraLabel = 'Saves';
    let extraVal = data.saves;
    
    if (platformLower === 'instagram' || platformLower === 'tiktok') {
        extraLabel = 'Saves';
        extraVal = data.saves;
    } else if (platformLower === 'youtube') {
        extraLabel = 'Subscribers / Peak';
        extraVal = data.peak_viewers || 0; 
    }
    document.getElementById('detailExtraLabel').textContent = extraLabel;
    document.getElementById('detailExtraVal').textContent = formatNum(extraVal);
    
    // Tautan
    const linkContainer = document.getElementById('detailLinkContainer');
    if (data.tautan) {
        document.getElementById('detailLink').href = data.tautan;
        linkContainer.style.display = 'block';
    } else {
        linkContainer.style.display = 'none';
    }
    
    document.getElementById('detailModal').style.display = 'flex';
}

function closeDetailModal() {
    document.getElementById('detailModal').style.display = 'none';
}

function openKategoriModal(platformSlug, btnElement) {
    currentKategoriPlatform = platformSlug;
    currentSelectElement = btnElement.previousElementSibling;
    document.getElementById('kategoriPlatformLabel').textContent = platformSlug;
    document.getElementById('kategoriCrudModal').style.display = 'flex';
    document.getElementById('newKategoriName').value = '';
    loadKategoriList();
}

function closeKategoriModal() {
    document.getElementById('kategoriCrudModal').style.display = 'none';
}

async function loadKategoriList() {
    try {
        const response = await fetch('/ajax/kategori', { headers: { 'Accept': 'application/json' } });
        const json = await response.json();
        if (json.success) {
            const filtered = json.data.filter(k => k.platform_slug === currentKategoriPlatform);
            renderKategoriList(filtered);
            updateSelectDropdown(filtered);
        }
    } catch (e) { console.error(e); }
}

function renderKategoriList(kategoris) {
    const tbody = document.getElementById('kategoriListBody');
    tbody.innerHTML = '';
    kategoris.forEach(k => {
        const tr = document.createElement('tr');
        tr.style.borderBottom = '1px solid #e2e8f0';
        let actionsHtml = `
            <button type="button" onclick="editKategori(${k.id}, '${k.nama}')" style="background:none;border:none;color:#f59e0b;cursor:pointer;margin-right:8px;"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 1 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></button>
            <button type="button" onclick="deleteKategori(${k.id})" style="background:none;border:none;color:#ef4444;cursor:pointer;"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></button>
        `;
        tr.innerHTML = `
            <td style="padding: 12px; color: #334155;">${k.nama}</td>
            <td style="padding: 12px; text-align: right; width: 100px;">${actionsHtml}</td>
        `;
        tbody.appendChild(tr);
    });
}

function updateSelectDropdown(kategoris) {
    if (!currentSelectElement) return;
    const currentValue = currentSelectElement.value;
    currentSelectElement.innerHTML = '<option value="">-- Pilih Kategori --</option>';
    kategoris.forEach(k => {
        const option = document.createElement('option');
        option.value = k.nama;
        option.textContent = k.nama;
        if (k.nama === currentValue) option.selected = true;
        currentSelectElement.appendChild(option);
    });
}

function getCsrfToken() {
    return '{{ csrf_token() }}';
}

async function addKategori() {
    const nama = document.getElementById('newKategoriName').value;
    if (!nama) return;
    try {
        const response = await fetch('/ajax/kategori', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': getCsrfToken() },
            body: JSON.stringify({ platform: currentKategoriPlatform, nama_kategori: nama })
        });
        const json = await response.json();
        if (json.success) {
            document.getElementById('newKategoriName').value = '';
            loadKategoriList();
        } else {
            alert(json.message || 'Gagal menambahkan kategori');
        }
    } catch (e) { 
        alert('Terjadi kesalahan sistem atau sesi habis.');
        console.error(e); 
    }
}

async function deleteKategori(id) {
    if (!confirm('Hapus kategori ini?')) return;
    try {
        const response = await fetch('/ajax/kategori/' + id, {
            method: 'DELETE',
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': getCsrfToken() }
        });
        const json = await response.json();
        if (json.success) {
            loadKategoriList();
        } else {
            alert(json.message || 'Gagal menghapus kategori');
        }
    } catch (e) { 
        alert('Terjadi kesalahan sistem.');
        console.error(e); 
    }
}

async function editKategori(id, currentName) {
    const newName = prompt('Ubah nama kategori:', currentName);
    if (!newName || newName === currentName) return;
    try {
        const response = await fetch('/ajax/kategori/' + id, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': getCsrfToken() },
            body: JSON.stringify({ platform: currentKategoriPlatform, nama_kategori: newName })
        });
        const json = await response.json();
        if (json.success) {
            loadKategoriList();
        } else {
            alert(json.message || 'Gagal mengubah kategori');
        }
    } catch (e) { 
        alert('Terjadi kesalahan sistem.');
        console.error(e); 
    }
}

function switchPlatform(platform, element) {
    currentPlatform = platform;
    sessionStorage.setItem('input_active_platform', platform);
    
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
    sessionStorage.setItem('input_active_yt_tab', tab);
    
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
    window.location.href = `{{ route('dashboard.export.csv') }}?platform=${targetPlatform}`;
}

// ─── Restore active tab on page load ───────────────────────────────────────
document.addEventListener('DOMContentLoaded', function () {
    const savedPlatform = sessionStorage.getItem('input_active_platform') || 'facebook';
    const savedYtTab    = sessionStorage.getItem('input_active_yt_tab')   || 'live';

    // Restore platform tab
    const platformBtns = document.querySelectorAll('.platform-btn');
    platformBtns.forEach(function (btn) {
        const platformName = btn.querySelector('span')?.textContent?.trim().toLowerCase();
        // Map display names to platform keys
        const nameMap = { 'facebook': 'facebook', 'instagram': 'instagram', 'tiktok': 'tiktok', 'youtube': 'youtube' };
        if (nameMap[platformName] === savedPlatform) {
            switchPlatform(savedPlatform, btn);
        }
    });

    // Restore YouTube sub-tab (only if platform is youtube)
    if (savedPlatform === 'youtube') {
        const ytBtns = document.querySelectorAll('.yt-submenu-btn');
        ytBtns.forEach(function (btn) {
            const tabName = btn.querySelector('span')?.textContent?.trim().toLowerCase();
            if (tabName === savedYtTab) {
                switchYoutubeTab(savedYtTab, btn);
            }
        });
    }

    // Scroll to flash message if present
    const flash = document.getElementById('flash-message-success');
    if (flash) {
        flash.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }
});

// ============================================================
// AUTO-KONVERSI ANGKA SINGKATAN (4jt, 17.9rb, 2,5jt, 500rb, dll)
// Berlaku untuk semua field metrik angka di semua form platform.
// ============================================================

// Simple Toast Notification
function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.style.position = 'fixed';
    toast.style.top = '20px'; // Dipindah dari bottom ke top
    toast.style.right = '20px';
    toast.style.padding = '1rem';
    toast.style.borderRadius = '4px';
    toast.style.boxShadow = '0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)';
    toast.style.zIndex = '9999';
    toast.style.opacity = '0';
    toast.style.transition = 'opacity 0.3s ease, transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275)';
    toast.style.transform = 'translateY(-20px)'; // Muncul dari atas
    toast.style.display = 'flex';
    toast.style.alignItems = 'center';
    toast.style.fontSize = '0.9rem';
    
    let iconSvg = '';
    if (type === 'success') {
        toast.style.backgroundColor = '#ecfdf5';
        toast.style.borderLeft = '4px solid #10b981';
        toast.style.color = '#047857';
        iconSvg = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>';
    } else {
        toast.style.backgroundColor = '#fef2f2';
        toast.style.borderLeft = '4px solid #ef4444';
        toast.style.color = '#b91c1c';
        iconSvg = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>';
    }
    
    toast.innerHTML = `<div style="display: flex; align-items: center;">${iconSvg} ${message}</div>`;
    document.body.appendChild(toast);
    
    // Animate in
    setTimeout(() => {
        toast.style.opacity = '1';
        toast.style.transform = 'translateY(0)';
    }, 10);
    
    // Animate out after 3s
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(-20px)';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// AJAX Interceptor for Delete History Data
function showConfirmModal() {
    return new Promise((resolve) => {
        const modal = document.getElementById('custom-confirm-modal');
        const content = document.getElementById('custom-confirm-content');
        modal.style.display = 'flex';
        // Trigger reflow for transition
        void modal.offsetWidth;
        modal.style.opacity = '1';
        content.style.transform = 'scale(1)';

        const btnOk = document.getElementById('custom-confirm-ok');
        const btnCancel = document.getElementById('custom-confirm-cancel');

        const cleanup = () => {
            modal.style.opacity = '0';
            content.style.transform = 'scale(0.95)';
            setTimeout(() => { modal.style.display = 'none'; }, 200);
            btnOk.removeEventListener('click', onOk);
            btnCancel.removeEventListener('click', onCancel);
        };

        const onOk = () => { cleanup(); resolve(true); };
        const onCancel = () => { cleanup(); resolve(false); };

        btnOk.addEventListener('click', onOk);
        btnCancel.addEventListener('click', onCancel);
    });
}

document.addEventListener('submit', async function(e) {
    if (e.target && e.target.tagName === 'FORM') {
        const action = e.target.getAttribute('action');
        const methodInput = e.target.querySelector('input[name="_method"]');
        const method = methodInput ? methodInput.value.toUpperCase() : e.target.method.toUpperCase();
        
        if (action && action.includes('dashboard/metrics/') && method === 'DELETE') {
            e.preventDefault();
            
            // Tampilkan konfirmasi ringan
            const confirmed = await showConfirmModal();
            if (!confirmed) return;
            
            const tr = e.target.closest('tr');
            if (!tr) return;

            const submitBtn = e.target.querySelector('button[type="submit"]');
            const originalContent = submitBtn.innerHTML;
            submitBtn.innerHTML = '<span style="font-size:12px;color:#ef4444;">...</span>';
            submitBtn.disabled = true;

            try {
                const response = await fetch(action, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: new FormData(e.target)
                });

                const result = await response.json();
                if (response.ok && result.success) {
                    tr.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                    tr.style.opacity = '0';
                    tr.style.transform = 'translateX(20px)';
                    setTimeout(() => {
                        tr.remove();
                    }, 300);
                    showToast('Data berhasil dihapus.', 'success');
                } else {
                    showToast(result.message || 'Data gagal dihapus.', 'error');
                    submitBtn.innerHTML = originalContent;
                    submitBtn.disabled = false;
                }
            } catch (error) {
                showToast('Terjadi kesalahan sistem. Data gagal dihapus.', 'error');
                submitBtn.innerHTML = originalContent;
                submitBtn.disabled = false;
            }
        }
    }
});

document.addEventListener('DOMContentLoaded', function() {
    const shorthandFields = [
        'like', 'comment', 'share', 'save', 'saves', 'repost',
        'views', 'views_count', 'reach', 'subscribers',
        'peak_viewers', 'interactions'
    ];

    function parseShorthandNumber(raw) {
        if (raw === null || raw === undefined) return raw;
        let str = String(raw).trim().toLowerCase().replace(',', '.');
        if (str === '') return '';

        // Sudah angka polos -> bulatkan saja
        if (/^-?\d+(\.\d+)?$/.test(str)) {
            return Math.round(parseFloat(str)).toString();
        }

        // Format singkatan: angka + (jt/juta/rb/ribu/k/m), boleh ada spasi
        const match = str.match(/^(-?\d+(?:\.\d+)?)\s*(jt|juta|rb|ribu|k|m)$/);
        if (!match) return raw; // biarkan apa adanya (nanti browser/server yang menegur kalau tidak valid)

        const num = parseFloat(match[1]);
        const suffix = match[2];
        const multiplier = (suffix === 'rb' || suffix === 'ribu' || suffix === 'k') ? 1000 : 1000000;

        return Math.round(num * multiplier).toString();
    }

    shorthandFields.forEach(function(name) {
        document.querySelectorAll('input[name="' + name + '"]').forEach(function(input) {
            input.type = 'text';
            input.setAttribute('inputmode', 'decimal');
            input.placeholder = 'cth: 4jt, 17.9rb, atau 2500';

            input.addEventListener('blur', function() {
                input.value = parseShorthandNumber(input.value);
            });
        });
    });

    // Jaga-jaga: konversi ulang tepat sebelum submit, kalau user langsung
    // tekan Enter tanpa sempat blur dari field terakhir.
    document.querySelectorAll('form[id^="form-"]').forEach(function(form) {
        form.addEventListener('submit', function() {
            shorthandFields.forEach(function(name) {
                const el = form.querySelector('[name="' + name + '"]');
                if (el) el.value = parseShorthandNumber(el.value);
            });
        });
    });
});
</script>
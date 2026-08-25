<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master Data Kategori - Analytics Pro</title>
    <!-- Phosphor Icons -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/input.css') }}?v={{ time() }}">
    <style>
        .modal {
            display: none; 
            position: fixed; 
            z-index: 1000; 
            left: 0; top: 0; width: 100%; height: 100%; 
            background-color: rgba(0,0,0,0.5); 
            align-items: center; justify-content: center;
        }
        .modal.active { display: flex; }
        .modal-content {
            background-color: #fff; 
            padding: 2rem; 
            border-radius: 0.75rem; 
            width: 100%; max-width: 500px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
        .modal-header {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 1.5rem; border-bottom: 1px solid #e2e8f0; padding-bottom: 1rem;
        }
        .modal-header h2 { margin: 0; font-size: 1.25rem; color: #0f172a; }
        .close-btn { background: none; border: none; font-size: 1.5rem; cursor: pointer; color: #64748b; }
        .close-btn:hover { color: #0f172a; }
        
        .btn-warning { background: #f59e0b; color: #fff; border: 1px solid #d97706; }
        .btn-warning:hover { background: #d97706; }
        .btn-danger { background: #ef4444; color: #fff; border: 1px solid #dc2626; }
        .btn-danger:hover { background: #dc2626; }
        
        .action-group { display: flex; gap: 0.5rem; justify-content: flex-end; }
        .header-actions { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; }
        
        /* Modern Delete Modal Specifics */
        .modal-delete-content {
            background-color: #fff;
            padding: 2.25rem 2rem;
            border-radius: 1rem;
            width: 100%;
            max-width: 440px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            border: 1px solid rgba(226, 232, 240, 0.8);
            text-align: center;
            transform: scale(0.95);
            transition: transform 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        .modal.active .modal-delete-content {
            transform: scale(1);
        }
        .delete-icon-wrapper {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background-color: #fee2e2;
            color: #ef4444;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
            margin: 0 auto 1.25rem;
            box-shadow: 0 0 0 8px #fef2f2;
        }
        .delete-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 0.5rem;
        }
        .delete-message {
            font-size: 0.9rem;
            color: #64748b;
            line-height: 1.5;
            margin-bottom: 1.25rem;
        }
        .delete-message strong {
            color: #0f172a;
            font-weight: 600;
        }
        .delete-warning-box {
            background-color: #fffbeb;
            border: 1px solid #fef3c7;
            border-radius: 0.5rem;
            padding: 0.75rem;
            display: flex;
            align-items: flex-start;
            gap: 0.5rem;
            text-align: left;
            margin-bottom: 1.5rem;
        }
        .delete-warning-box i {
            color: #d97706;
            font-size: 1.15rem;
            flex-shrink: 0;
            margin-top: 1px;
        }
        .delete-warning-box span {
            font-size: 0.78rem;
            color: #b45309;
            font-weight: 500;
            line-height: 1.45;
        }
        .modal-delete-actions {
            display: flex;
            gap: 0.75rem;
        }
        .modal-delete-actions button {
            flex: 1;
            justify-content: center;
            padding: 0.75rem 1rem;
            font-size: 0.9rem;
            font-weight: 600;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: all 0.2s;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        .btn-neutral-flat {
            background-color: #f1f5f9;
            color: #475569;
        }
        .btn-neutral-flat:hover {
            background-color: #e2e8f0;
            color: #1e293b;
        }
        .btn-danger-solid {
            background-color: #ef4444;
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.25);
        }
        .btn-danger-solid:hover {
            background-color: #dc2626;
            box-shadow: 0 6px 16px rgba(239, 68, 68, 0.35);
        }
        .spin {
            animation: spinning 0.85s linear infinite;
        }
        @keyframes spinning { to { transform: rotate(360deg); } }
    </style>
</head>
<body>
    <!-- Top Navigation Bar -->
    <header class="topnav">
        <!-- Brand -->
        <a href="{{ url('/') }}" class="topnav-brand" style="text-decoration:none; display:flex; align-items:center; padding-left: 0.5rem; flex-shrink: 0;" title="Kembali ke Beranda">
            <div style="width: 180px; height: 52px; overflow: hidden; position: relative;">
                <img src="{{ asset('assets/logo-kemendag.png') }}"
                     alt="Kementerian Perdagangan"
                     style="width: 195px !important; height: auto !important; max-width: none !important; position: absolute; top: 50%; left: -20px; transform: translateY(-50%); display: block; padding: 0; margin: 0;">
            </div>
        </a>

        <!-- Nav Links -->
        <nav class="topnav-links">
            <a href="{{ route('dashboard') }}" class="topnav-item">Ringkasan</a>
            <a href="{{ route('dashboard') }}?tab=analitik" class="topnav-item">Analitik Konten</a>
            <a href="{{ route('dashboard') }}?tab=input" class="topnav-item">Input Data</a>
            <a href="{{ route('dashboard') }}?tab=laporan" class="topnav-item">Laporan</a>
            <a href="#" class="topnav-item active">Kategori Konten</a>
        </nav>

        <!-- Right -->
        <div class="topnav-right">
            <div class="topnav-search">
                <i class="ph ph-magnifying-glass"></i>
                <input type="text" id="kategori-search" placeholder="Cari kategori..." autocomplete="off">
                <span class="topnav-search-shortcut">/</span>
            </div>
            <div class="topnav-user-group">
                <a href="{{ route('profile.show') }}" class="topnav-avatar-link" title="{{ auth()->user()->name }}">
                    @php
                        $hasAvatar = auth()->user()->avatar && file_exists(public_path(auth()->user()->avatar));
                        $nameParts = explode(' ', trim(auth()->user()->name));
                        $initials = count($nameParts) > 1 
                            ? strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[1], 0, 1))
                            : strtoupper(substr(auth()->user()->name, 0, 2));
                    @endphp
                    @if($hasAvatar)
                        <img src="{{ asset(auth()->user()->avatar) }}" alt="{{ auth()->user()->name }}" class="topnav-avatar-img">
                    @else
                        <div class="topnav-avatar-initials">
                            {{ $initials }}
                        </div>
                    @endif
                </a>
                <button class="topnav-logout-btn"
                        onclick="event.preventDefault(); document.getElementById('topnav-logout-form').submit();"
                        title="Logout">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 6px;"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                    <span>Logout</span>
                </button>
                <form id="topnav-logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
                    @csrf
                </form>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-content" style="margin-left:0;">

        <div class="dashboard-container active" style="display:block;">
            <div class="page-header" style="align-items: flex-start; margin-bottom: 2rem; display: flex; flex-wrap: wrap; gap: 1rem;">
                <div style="flex: 1; min-width: 300px;">
                    <h1 style="font-size: 1.75rem; font-weight: 700; color: #0f172a; margin-bottom: 0.5rem; letter-spacing: -0.5px;">Master Data Kategori</h1>
                    <p style="color: #64748b; font-size: 0.95rem; line-height: 1.5;">Kelola daftar kategori konten untuk setiap platform media sosial.</p>
                </div>
                <div style="display: flex; gap: 0.75rem; align-items: flex-start;">
                    <a href="{{ route('dashboard') }}" class="btn" style="background: #ffffff; border: 1px solid #e2e8f0; color: #0f172a; font-weight: 600;"><i class="ph-bold ph-arrow-left" style="color: #64748b;"></i> Kembali ke Dashboard</a>
                </div>
            </div>

            @if(session('success'))
            <div style="background-color: #dcfce7; color: #166534; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.5rem; font-weight: 500; display: flex; align-items: center; gap: 0.5rem; border-left: 4px solid #16a34a; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <i class="ph-fill ph-check-circle" style="font-size: 1.25rem;"></i> {{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div style="background-color: #fee2e2; color: #991b1b; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.5rem; font-weight: 500; display: flex; align-items: center; gap: 0.5rem; border-left: 4px solid #dc2626; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <i class="ph-fill ph-warning-circle" style="font-size: 1.25rem;"></i> {{ session('error') }}
            </div>
            @endif
            
            @if($errors->any())
            <div style="background-color: #fee2e2; color: #991b1b; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.5rem; font-weight: 500; display: flex; align-items: flex-start; gap: 0.5rem; border-left: 4px solid #dc2626; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <i class="ph-fill ph-warning-circle" style="font-size: 1.25rem; margin-top: 0.1rem;"></i> 
                <ul style="margin:0; padding-left: 1.5rem;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Tabel Daftar Kategori (Full Width) -->
            <div class="card" style="box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); border-radius: 0.75rem; border: none; padding: 1.5rem;">
                <div class="header-actions">
                    <div class="card-title" style="margin: 0; font-size: 1.2rem;">Daftar Kategori Konten</div>
                    <button class="btn btn-primary" onclick="openModal('addModal')" style="display: flex; align-items: center; gap: 0.5rem;">
                        <i class="ph-bold ph-plus"></i> Tambah Kategori
                    </button>
                </div>
                <div style="overflow-x: auto;">
                    <table class="data-table" style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="border-bottom: 2px solid #e2e8f0;">
                                <th style="width: 50px; padding: 1rem 0.5rem;">No</th>
                                <th style="padding: 1rem 0.5rem;">Nama Kategori</th>
                                <th style="padding: 1rem 0.5rem;">Platform</th>
                                <th style="padding: 1rem 0.5rem;">Tanggal Dibuat</th>
                                <th style="text-align: right; padding: 1rem 0.5rem;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kategoris as $index => $kategori)
                            <tr style="border-bottom: 1px solid #f1f5f9;">
                                <td style="padding: 1rem 0.5rem; color: #64748b;">{{ $index + 1 }}</td>
                                <td style="font-weight: 600; color: #1e293b; padding: 1rem 0.5rem;">{{ $kategori->nama_kategori }}</td>
                                <td style="padding: 1rem 0.5rem;">
                                    @if($kategori->platform == 'facebook')
                                        <span style="color: #1877f2; font-weight: 600; display:flex; align-items:center; gap:4px;"><i class="fa-brands fa-facebook"></i> Facebook</span>
                                    @elseif($kategori->platform == 'instagram')
                                        <span style="color: #e1306c; font-weight: 600; display:flex; align-items:center; gap:4px;"><i class="fa-brands fa-instagram"></i> Instagram</span>
                                    @elseif($kategori->platform == 'tiktok')
                                        <span style="color: #000000; font-weight: 600; display:flex; align-items:center; gap:4px;"><i class="fa-brands fa-tiktok"></i> TikTok</span>
                                    @elseif($kategori->platform == 'yt-live')
                                        <span style="color: #ff0000; font-weight: 600; display:flex; align-items:center; gap:4px;"><i class="fa-brands fa-youtube"></i> YouTube Live</span>
                                    @elseif($kategori->platform == 'yt-video')
                                        <span style="color: #ff0000; font-weight: 600; display:flex; align-items:center; gap:4px;"><i class="fa-brands fa-youtube"></i> YouTube Video</span>
                                    @elseif($kategori->platform == 'yt-shorts')
                                        <span style="color: #ff0000; font-weight: 600; display:flex; align-items:center; gap:4px;"><i class="fa-brands fa-youtube"></i> YouTube Shorts</span>
                                    @else
                                        {{ ucfirst($kategori->platform) }}
                                    @endif
                                </td>
                                <td style="padding: 1rem 0.5rem; color: #64748b;">{{ $kategori->created_at->format('d M Y') }}</td>
                                <td style="text-align: right; padding: 1rem 0.5rem;">
                                    <div class="action-group">
                                        <button class="btn btn-warning" onclick="editKategori('{{ $kategori->id }}', '{{ $kategori->platform }}', '{{ $kategori->nama_kategori }}')" style="padding: 0.4rem 0.75rem; font-size: 0.85rem; display: flex; align-items: center; gap: 0.25rem; border-radius: 6px;">
                                            <i class="ph-bold ph-pencil-simple"></i> Edit
                                        </button>
                                        <form action="{{ route('kategori.destroy', $kategori->id) }}" method="POST" style="display:inline;" class="delete-form" data-name="{{ $kategori->nama_kategori }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-danger btn-delete-trigger" style="padding: 0.4rem 0.75rem; font-size: 0.85rem; display: flex; align-items: center; gap: 0.25rem; border-radius: 6px;">
                                                <i class="ph-bold ph-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" style="text-align: center; color: #64748b; padding: 3rem;">Belum ada kategori yang ditambahkan. Silakan klik tombol "Tambah Kategori" di atas.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Modal Tambah Kategori -->
            <div id="addModal" class="modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2>Tambah Kategori Baru</h2>
                        <button type="button" class="close-btn" onclick="closeModal('addModal')"><i class="ph-bold ph-x"></i></button>
                    </div>
                    <form action="{{ route('kategori.store') }}" method="POST">
                        @csrf
                        <div class="form-group" style="margin-bottom: 1.5rem;">
                            <label style="font-weight: 600; color: #334155; margin-bottom: 0.5rem; display: block;">Pilih Platform</label>
                            <select name="platform" class="form-control" required style="width: 100%; padding: 0.75rem; border-radius: 0.5rem; border: 1px solid #cbd5e1;">
                                <option value="">-- Pilih Platform --</option>
                                <option value="facebook">Facebook</option>
                                <option value="instagram">Instagram</option>
                                <option value="tiktok">TikTok</option>
                                <option value="yt-live">YouTube Live</option>
                                <option value="yt-video">YouTube Video</option>
                                <option value="yt-shorts">YouTube Shorts</option>
                            </select>
                        </div>
                        <div class="form-group" style="margin-bottom: 1.5rem;">
                            <label style="font-weight: 600; color: #334155; margin-bottom: 0.5rem; display: block;">Nama Kategori</label>
                            <input type="text" name="nama_kategori" class="form-control" placeholder="Contoh: Edukasi, Promosi, dll" required style="width: 100%; padding: 0.75rem; border-radius: 0.5rem; border: 1px solid #cbd5e1;">
                        </div>
                        <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                            <button type="button" class="btn" style="flex: 1; justify-content: center; background: #f1f5f9; color: #475569; font-weight: 600;" onclick="closeModal('addModal')">Batal</button>
                            <button type="submit" class="btn btn-primary" style="flex: 1; justify-content: center; display: flex; align-items: center; gap: 0.5rem;"><i class="ph-bold ph-floppy-disk"></i> Simpan Kategori</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Modal Edit Kategori -->
            <div id="editModal" class="modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2>Edit Kategori</h2>
                        <button type="button" class="close-btn" onclick="closeModal('editModal')"><i class="ph-bold ph-x"></i></button>
                    </div>
                    <form id="editForm" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group" style="margin-bottom: 1.5rem;">
                            <label style="font-weight: 600; color: #334155; margin-bottom: 0.5rem; display: block;">Platform</label>
                            <select name="platform" id="editPlatform" class="form-control" required style="width: 100%; padding: 0.75rem; border-radius: 0.5rem; border: 1px solid #cbd5e1;">
                                <option value="">-- Pilih Platform --</option>
                                <option value="facebook">Facebook</option>
                                <option value="instagram">Instagram</option>
                                <option value="tiktok">TikTok</option>
                                <option value="yt-live">YouTube Live</option>
                                <option value="yt-video">YouTube Video</option>
                                <option value="yt-shorts">YouTube Shorts</option>
                            </select>
                        </div>
                        <div class="form-group" style="margin-bottom: 1.5rem;">
                            <label style="font-weight: 600; color: #334155; margin-bottom: 0.5rem; display: block;">Nama Kategori</label>
                            <input type="text" name="nama_kategori" id="editNama" class="form-control" required style="width: 100%; padding: 0.75rem; border-radius: 0.5rem; border: 1px solid #cbd5e1;">
                        </div>
                        <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                            <button type="button" class="btn" style="flex: 1; justify-content: center; background: #f1f5f9; color: #475569; font-weight: 600;" onclick="closeModal('editModal')">Batal</button>
                            <button type="submit" class="btn btn-warning" style="flex: 1; justify-content: center; display: flex; align-items: center; gap: 0.5rem; border-color: transparent;"><i class="ph-bold ph-pencil-simple"></i> Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Modal Konfirmasi Hapus Kategori -->
            <div id="deleteConfirmModal" class="modal">
                <div class="modal-delete-content">
                    <div class="delete-icon-wrapper">
                        <i class="ph-bold ph-trash"></i>
                    </div>
                    <h3 class="delete-title">Hapus Kategori?</h3>
                    <p class="delete-message">Apakah Anda yakin ingin menghapus kategori <strong id="delete-item-name"></strong>? Tindakan ini tidak dapat dibatalkan.</p>
                    <div class="delete-warning-box">
                        <i class="ph-bold ph-info"></i>
                        <span>Catatan: Kategori tidak dapat dihapus jika masih digunakan oleh data laporan.</span>
                    </div>
                    <div class="modal-delete-actions">
                        <button type="button" class="btn-neutral-flat" onclick="closeDeleteModal()">Batal</button>
                        <button type="button" id="confirm-delete-btn" class="btn-danger-solid">
                            <i class="ph-bold ph-trash"></i> Ya, Hapus
                        </button>
                    </div>
                </div>
            </div>

            <script>
                function openModal(id) {
                    document.getElementById(id).classList.add('active');
                }
                function closeModal(id) {
                    document.getElementById(id).classList.remove('active');
                }
                function editKategori(id, platform, nama) {
                    var form = document.getElementById('editForm');
                    form.action = '/master/kategori/' + id;
                    document.getElementById('editPlatform').value = platform;
                    document.getElementById('editNama').value = nama;
                    openModal('editModal');
                }

                // Delete Confirmation Flow
                let formToSubmit = null;
                document.querySelectorAll('.btn-delete-trigger').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const form = this.closest('.delete-form');
                        const name = form.getAttribute('data-name');
                        formToSubmit = form;
                        
                        document.getElementById('delete-item-name').textContent = name;
                        openModal('deleteConfirmModal');
                    });
                });

                document.getElementById('confirm-delete-btn').addEventListener('click', function() {
                    if (formToSubmit) {
                        this.disabled = true;
                        this.innerHTML = '<i class="ph ph-circle-notch spin"></i> Menghapus...';
                        formToSubmit.submit();
                    }
                });

                function closeDeleteModal() {
                    closeModal('deleteConfirmModal');
                    formToSubmit = null;
                }

                // Table search filter
                const searchInput = document.getElementById('kategori-search');
                if (searchInput) {
                    searchInput.addEventListener('input', function(e) {
                        const query = e.target.value.toLowerCase().trim();
                        const rows = document.querySelectorAll('.data-table tbody tr');
                        let matchCount = 0;

                        rows.forEach(row => {
                            // Skip empty row if exists
                            if (row.cells.length < 3) return;
                            
                            const name = row.cells[1].textContent.toLowerCase();
                            const platform = row.cells[2].textContent.toLowerCase();
                            
                            if (name.includes(query) || platform.includes(query)) {
                                row.style.display = '';
                                matchCount++;
                            } else {
                                row.style.display = 'none';
                            }
                        });

                        // Handle empty result display
                        let emptyRow = document.getElementById('empty-search-row');
                        if (matchCount === 0) {
                            if (!emptyRow) {
                                const tbody = document.querySelector('.data-table tbody');
                                emptyRow = document.createElement('tr');
                                emptyRow.id = 'empty-search-row';
                                emptyRow.innerHTML = `<td colspan="5" style="text-align: center; color: #64748b; padding: 3rem;">Tidak ada kategori yang cocok dengan pencarian "${e.target.value}".</td>`;
                                tbody.appendChild(emptyRow);
                            }
                        } else if (emptyRow) {
                            emptyRow.remove();
                        }
                    });
                }

                // Keyboard Hotkey '/' to focus search input
                document.addEventListener('keydown', function(e) {
                    if (e.key === '/' && document.activeElement !== searchInput && document.activeElement.tagName !== 'INPUT' && document.activeElement.tagName !== 'SELECT' && document.activeElement.tagName !== 'TEXTAREA') {
                        e.preventDefault();
                        if (searchInput) {
                            searchInput.focus();
                            searchInput.select();
                        }
                    }
                });

                // Close modal if clicking outside
                window.onclick = function(event) {
                    if (event.target.classList.contains('modal')) {
                        event.target.classList.remove('active');
                        formToSubmit = null;
                    }
                }
            </script>

        </div>

        <!-- Global Footer -->
        @include('layouts.footer')
    </main>
</body>
</html>

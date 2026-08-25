@extends('layouts.app')

@push('css')
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
    .mk-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 24px;
        margin-bottom: 32px;
    }
    @media (min-width: 992px) {
        .mk-grid {
            grid-template-columns: repeat(2, 1fr);
            align-items: stretch;
        }
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
    
    /* Structural alignment for form cards using foolproof Flexbox */
    .mk-grid {
        display: flex;
        flex-direction: column;
        gap: 24px;
        margin-bottom: 32px;
    }
    @media (min-width: 992px) {
        .mk-grid {
            flex-direction: row;
            align-items: stretch;
        }
        .mk-grid .mk-card {
            flex: 1 1 50%;
            display: flex;
            flex-direction: column;
        }
        .mk-grid .mk-card-body {
            display: flex;
            flex-direction: column;
            flex-grow: 1;
            padding: 24px;
        }
    }
    .mk-grid .mk-card-subtitle {
        min-height: 42px; /* Force consistent vertical position for forms */
    }
    .mk-grid form {
        display: flex;
        flex-direction: column;
        flex-grow: 1;
        justify-content: flex-start;
        text-align: left;
    }
    
    /* Form Styles */
    .mk-form-group {
        margin-bottom: 16px;
    }
    .mk-label {
        display: block;
        font-size: 14px;
        font-weight: 500;
        color: #334155;
        margin-bottom: 6px;
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
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    .mk-error {
        display: block;
        font-size: 13px;
        color: #ef4444;
        margin-top: 6px;
    }
    .mk-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        height: 44px;
        background-color: #2563eb;
        color: #fff;
        font-size: 15px;
        font-weight: 600;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: background-color 0.2s;
        margin-top: 8px;
    }
    .mk-btn:hover {
        background-color: #1d4ed8;
    }

    /* Table Styles */
    .mk-table-wrapper {
        overflow-x: auto;
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
    .mk-table tbody tr:last-child td {
        border-bottom: none;
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
    .mk-action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 32px;
        border-radius: 6px;
        background-color: #ffffff;
        color: #475569;
        border: 1px solid #cbd5e1;
        cursor: pointer;
        transition: all 0.2s;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    }
    .mk-action-btn:hover {
        background-color: #f8fafc;
        border-color: #94a3b8;
        color: #0f172a;
    }
    
    /* Toolbar (Search & Filter) */
    .mk-toolbar {
        display: flex;
        flex-direction: column;
        gap: 16px;
        margin-bottom: 24px;
        padding: 0 24px;
    }
    @media(min-width: 768px) {
        .mk-toolbar {
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
        }
    }
    .mk-search-wrapper {
        position: relative;
        flex: 1;
        max-width: 320px;
    }
    .mk-search-wrapper i {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
    }
    .mk-search-input {
        width: 100%;
        height: 40px;
        padding: 0 16px 0 40px;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        font-size: 14px;
        outline: none;
        transition: border-color 0.2s;
        box-sizing: border-box;
    }
    .mk-search-input:focus {
        border-color: #3b82f6;
    }
    .mk-filter-select {
        height: 40px;
        padding: 0 32px 0 16px;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        font-size: 14px;
        background-color: #fff;
        outline: none;
        cursor: pointer;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748b'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 12px center;
        background-size: 16px;
    }
    .mk-pagination-wrapper {
        padding: 16px 24px;
        border-top: 1px solid #e2e8f0;
        display: flex;
        justify-content: flex-end;
    }
    .mk-pagination-wrapper ul.pagination {
        display: flex;
        padding-left: 0;
        list-style: none;
        margin: 0;
        gap: 4px;
    }
    .mk-pagination-wrapper .page-item .page-link {
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 32px;
        height: 32px;
        padding: 0 8px;
        color: #475569;
        text-decoration: none;
        background-color: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.2s;
    }
    .mk-pagination-wrapper .page-item.active .page-link {
        color: #fff;
        background-color: #2563eb;
        border-color: #2563eb;
    }
    .mk-pagination-wrapper .page-item.disabled .page-link {
        color: #cbd5e1;
        pointer-events: none;
        background-color: #f8fafc;
    }
    .mk-pagination-wrapper .page-item .page-link:hover:not(.disabled) {
        background-color: #f1f5f9;
        border-color: #cbd5e1;
        color: #0f172a;
    }
    
    /* Status Badges */
    .mk-badge-active {
        background-color: #ecfccb;
        color: #4d7c0f;
        border: 1px solid #d9f99d;
    }
    .mk-badge-pending {
        background-color: #fef9c3;
        color: #a16207;
        border: 1px solid #fef08a;
    }

    
    /* Modals */
    .mk-modal-overlay {
        display: none; position: fixed; inset: 0; background: rgba(15,23,42,0.5);
        z-index: 100; align-items: center; justify-content: center; padding: 16px;
    }
    .mk-modal-overlay.show { display: flex; }
    .mk-modal {
        background: #fff; width: 100%; max-width: 450px; border-radius: 12px;
        box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); overflow: hidden;
    }
    .mk-modal-header {
        padding: 16px 24px; border-bottom: 1px solid #e2e8f0;
        display: flex; justify-content: space-between; align-items: center;
    }
    .mk-modal-title { font-size: 16px; font-weight: 600; color: #0f172a; margin: 0; }
    .mk-modal-close {
        background: none; border: none; font-size: 20px; color: #64748b;
        cursor: pointer; padding: 4px; display: flex; align-items: center; justify-content: center;
    }
    .mk-modal-close:hover { color: #0f172a; }
    .mk-modal-body { padding: 24px; }
    .mk-modal-footer {
        padding: 16px 24px; border-top: 1px solid #e2e8f0; background: #f8fafc;
        display: flex; justify-content: flex-end; gap: 12px;
    }
    .mk-btn-outline {
        background: #fff; border: 1px solid #cbd5e1; color: #334155;
        padding: 0 16px; height: 40px; border-radius: 8px; font-weight: 500; cursor: pointer;
    }
    .mk-btn-outline:hover { background: #f1f5f9; }
    .mk-btn-danger {
        background: #ef4444; border: none; color: #fff;
        padding: 0 16px; height: 40px; border-radius: 8px; font-weight: 500; cursor: pointer;
    }
    .mk-btn-danger:hover { background: #dc2626; }
    .mk-btn-primary {
        background: #2563eb; border: none; color: #fff;
        padding: 0 16px; height: 40px; border-radius: 8px; font-weight: 500; cursor: pointer;
    }
    .mk-btn-primary:hover { background: #1d4ed8; }
</style>
@endpush

@section('content')
<div class="mk-container">
    
    <div class="mk-page-header">
        <h1>Manajemen Data Pengguna</h1>
        <p>Kelola data pengguna dan akun yang terdaftar di sistem.</p>
    </div>

    @if(session('success'))
    <div class="mk-alert-success">
        <i class="ph ph-check-circle" style="font-size: 20px;"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    <div class="mk-grid">
        @if(auth()->check() && auth()->user()->role === 'super-admin')
        <!-- CARD 1: Tambah Admin -->
        <div class="mk-card">
            <div class="mk-card-body">
                <h3 class="mk-card-title"><i class="ph ph-user-plus"></i> Tambah Admin</h3>
                <p class="mk-card-subtitle">Buat akun Admin baru untuk membantu mengelola sistem.</p>
                
                <form action="{{ route('pengguna.admin.store') }}" method="POST">
                    @csrf
                    <div class="mk-form-group">
                        <label class="mk-label">Nama Lengkap</label>
                        <input type="text" name="name" class="mk-input" value="{{ old('name') }}" placeholder="Masukkan nama lengkap" required>
                        @error('name') <span class="mk-error">{{ $message }}</span> @enderror
                    </div>
                    <div class="mk-form-group">
                        <label class="mk-label">Username</label>
                        <input type="text" name="username" class="mk-input" value="{{ old('username') }}" placeholder="cth: adminbaru" required>
                        @error('username') <span class="mk-error">{{ $message }}</span> @enderror
                    </div>
                    <div class="mk-form-group">
                        <label class="mk-label">Email</label>
                        <input type="email" name="email" class="mk-input" value="{{ old('email') }}" placeholder="email@contoh.com" required>
                        @error('email') <span class="mk-error">{{ $message }}</span> @enderror
                    </div>
                    <div class="mk-form-group">
                        <label class="mk-label">Password</label>
                        <input type="password" name="password" class="mk-input" placeholder="Minimal 8 karakter" required>
                        @error('password') <span class="mk-error">{{ $message }}</span> @enderror
                    </div>
                    <div class="mk-form-group">
                        <label class="mk-label">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="mk-input" placeholder="Ketik ulang password" required>
                    </div>
                    <button type="submit" class="mk-btn"><i class="ph ph-plus-circle"></i> Buat Admin</button>
                </form>
            </div>
        </div>
        @endif

        @if(auth()->check() && in_array(auth()->user()->role, ['super-admin', 'admin']))
        <!-- CARD 2: Tambah Pengguna -->
        <div class="mk-card">
            <div class="mk-card-body">
                <h3 class="mk-card-title"><i class="ph ph-user-circle-plus"></i> Tambah Pengguna</h3>
                <p class="mk-card-subtitle">Buat akun pengguna baru untuk pengguna SOVIE.</p>
                
                <form action="{{ route('pengguna.user.store') }}" method="POST">
                    @csrf
                    <div class="mk-form-group">
                        <label class="mk-label">Nama Lengkap</label>
                        <input type="text" name="name" class="mk-input" value="{{ old('name') }}" placeholder="Masukkan nama lengkap" required>
                        @error('name') <span class="mk-error">{{ $message }}</span> @enderror
                    </div>
                    <div class="mk-form-group">
                        <label class="mk-label">Username</label>
                        <input type="text" name="username" class="mk-input" value="{{ old('username') }}" placeholder="cth: userbaru" required>
                        @error('username') <span class="mk-error">{{ $message }}</span> @enderror
                    </div>
                    <div class="mk-form-group">
                        <label class="mk-label">Email</label>
                        <input type="email" name="email" class="mk-input" value="{{ old('email') }}" placeholder="email@contoh.com" required>
                        @error('email') <span class="mk-error">{{ $message }}</span> @enderror
                    </div>
                    <div class="mk-form-group">
                        <label class="mk-label">Password</label>
                        <input type="password" name="password" class="mk-input" placeholder="Minimal 8 karakter" required>
                        @error('password') <span class="mk-error">{{ $message }}</span> @enderror
                    </div>
                    <div class="mk-form-group">
                        <label class="mk-label">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="mk-input" placeholder="Ketik ulang password" required>
                    </div>
                    <button type="submit" class="mk-btn"><i class="ph ph-plus-circle"></i> Buat Pengguna</button>
                </form>
            </div>
        </div>
        @endif
    </div>

    <!-- TABEL Pengguna -->
    <div class="mk-card">
        <div class="mk-card-body" style="padding-bottom: 24px; border-bottom: 1px solid #e2e8f0;">
            <h3 class="mk-card-title"><i class="ph ph-list-dashes"></i> Daftar Pengguna</h3>
            <p class="mk-card-subtitle" style="margin-bottom: 0;">Kelola seluruh pengguna yang terdaftar di sistem.</p>
        </div>
        
        <!-- Search & Filter Toolbar -->
        <div style="padding-top: 24px;">
            <form action="{{ route('pengguna.index') }}" method="GET" class="mk-toolbar">
                <div class="mk-search-wrapper">
                    <i class="ph ph-magnifying-glass"></i>
                    <input type="text" name="search" class="mk-search-input" placeholder="Cari nama, username, atau email..." value="{{ request('search') }}">
                </div>
                <div>
                    <select name="role" class="mk-filter-select" onchange="this.form.submit()">
                        <option value="">Semua Role</option>
                        @if(auth()->user()->role === 'super-admin')
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        @endif
                        <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>Pengguna</option>
                    </select>
                </div>
            </form>
        </div>

        <div class="mk-table-wrapper">
            <table class="mk-table">
                <thead>
                    <tr>
                        <th>Nama Lengkap</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th style="text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employees as $emp)
                        <tr>
                            <td>
                                <div style="font-weight: 500; color: #0f172a;">{{ $emp->name }}</div>
                            </td>
                            <td><span style="color: #64748b;">{{ '@' . $emp->username }}</span></td>
                            <td>{{ $emp->email }}</td>
                            <td>
                                @if($emp->role === 'super-admin')
                                    <span class="mk-badge mk-badge-super">Super Admin</span>
                                @elseif($emp->role === 'admin')
                                    <span class="mk-badge mk-badge-admin">Admin</span>
                                @else
                                    <span class="mk-badge mk-badge-user">User</span>
                                @endif
                            </td>
                            <td>
                                @if($emp->must_change_password)
                                    <span class="mk-badge mk-badge-pending">Menunggu Setup</span>
                                @else
                                    <span class="mk-badge mk-badge-active">Aktif</span>
                                @endif
                            </td>
                            <td style="text-align: center;">
                                <div class="mk-dropdown">
                                    <button type="button" class="mk-action-btn" onclick="toggleDropdown(event, {{ $emp->id }})">
                                        <span style="font-size: 14px; font-weight: bold;">&#9662;</span>
                                    </button>
                                    <div class="mk-dropdown-menu" id="dropdown-{{ $emp->id }}">
                                        <a href="javascript:void(0)" onclick="openModal('modal-detail-{{ $emp->id }}')"><i class="ph ph-eye" style="font-size: 18px;"></i> Detail</a>
                                        
                                        @if(auth()->user()->role === 'super-admin' || (auth()->user()->role === 'admin' && $emp->role === 'user'))
                                            @if(auth()->id() !== $emp->id)
                                                <a href="javascript:void(0)" onclick="openModal('modal-edit-{{ $emp->id }}')"><i class="ph ph-pencil-simple" style="font-size: 18px;"></i> Edit</a>
                                                <a href="javascript:void(0)" onclick="openModal('modal-reset-{{ $emp->id }}')"><i class="ph ph-key" style="font-size: 18px;"></i> Reset Password</a>
                                                <a href="javascript:void(0)" onclick="openModal('modal-status-{{ $emp->id }}')"><i class="ph ph-toggle-right" style="font-size: 18px;"></i> Ubah Status</a>
                                                <div class="mk-dropdown-divider"></div>
                                                <a href="javascript:void(0)" onclick="openModal('modal-delete-{{ $emp->id }}')" class="text-danger"><i class="ph ph-trash" style="font-size: 18px;"></i> Hapus</a>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; color: #64748b; padding: 32px;">
                                <i class="ph ph-users-slash" style="font-size: 32px; display: block; margin-bottom: 8px; color: #cbd5e1;"></i>
                                Tidak ada data pengguna yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($employees->hasPages())
        <div class="mk-pagination-wrapper">
            {{ $employees->links('pagination::bootstrap-4') }}
        </div>
        @endif
    </div>

    </div>

</div>

<!-- Dropdown CSS Fixes -->
@push('css')
<style>
    .mk-dropdown { display: inline-block; position: relative; }
    .mk-dropdown-menu {
        display: none; position: fixed; min-width: 180px;
        background-color: #fff; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
        border: 1px solid #e2e8f0; border-radius: 8px; z-index: 9999;
        padding: 8px 0; text-align: left;
    }
    .mk-dropdown-menu.show { display: block; }
    .mk-dropdown-menu a {
        display: flex; align-items: center; gap: 8px; padding: 8px 16px;
        color: #334155; text-decoration: none; font-size: 14px;
    }
    .mk-dropdown-menu a:hover { background-color: #f1f5f9; }
    .mk-dropdown-menu a.text-danger { color: #ef4444; }
    .mk-dropdown-divider { height: 1px; background-color: #e2e8f0; margin: 4px 0; }
</style>
@endpush

<!-- AREA MODALS -->
@foreach($employees as $emp)

    <!-- 1. Modal Detail -->
    <div class="mk-modal-overlay" id="modal-detail-{{ $emp->id }}">
        <div class="mk-modal">
            <div class="mk-modal-header">
                <h3 class="mk-modal-title">Detail Pengguna</h3>
                <button type="button" class="mk-modal-close" onclick="closeModal('modal-detail-{{ $emp->id }}')"><i class="ph ph-x"></i></button>
            </div>
            <div class="mk-modal-body">
                <div style="margin-bottom: 12px;"><strong>Nama Lengkap:</strong> <br> {{ $emp->name }}</div>
                <div style="margin-bottom: 12px;"><strong>Username:</strong> <br> {{ $emp->username }}</div>
                <div style="margin-bottom: 12px;"><strong>Email:</strong> <br> {{ $emp->email }}</div>
                <div style="margin-bottom: 12px;"><strong>Role:</strong> <br> {{ strtoupper(str_replace('-', ' ', $emp->role)) }}</div>
                <div style="margin-bottom: 12px;"><strong>Status:</strong> <br> {{ $emp->must_change_password ? 'Menunggu Setup' : 'Aktif' }}</div>
            </div>
            <div class="mk-modal-footer">
                <button type="button" class="mk-btn-outline" onclick="closeModal('modal-detail-{{ $emp->id }}')">Tutup</button>
            </div>
        </div>
    </div>

    @if(auth()->user()->role === 'super-admin' || (auth()->user()->role === 'admin' && $emp->role === 'user'))
        @if(auth()->id() !== $emp->id)
            <!-- 2. Modal Edit -->
            <div class="mk-modal-overlay" id="modal-edit-{{ $emp->id }}">
                <form action="{{ route('pengguna.update', $emp->id) }}" method="POST" class="mk-modal">
                    @csrf
                    @method('PUT')
                    <div class="mk-modal-header">
                        <h3 class="mk-modal-title">Edit Pengguna</h3>
                        <button type="button" class="mk-modal-close" onclick="closeModal('modal-edit-{{ $emp->id }}')"><i class="ph ph-x"></i></button>
                    </div>
                    <div class="mk-modal-body">
                        <div class="mk-form-group">
                            <label class="mk-label">Nama Lengkap</label>
                            <input type="text" name="name" class="mk-input" value="{{ $emp->name }}" required>
                        </div>
                        <div class="mk-form-group">
                            <label class="mk-label">Username</label>
                            <input type="text" name="username" class="mk-input" value="{{ $emp->username }}" required>
                        </div>
                        <div class="mk-form-group">
                            <label class="mk-label">Email</label>
                            <input type="email" name="email" class="mk-input" value="{{ $emp->email }}" required>
                        </div>
                    </div>
                    <div class="mk-modal-footer">
                        <button type="button" class="mk-btn-outline" onclick="closeModal('modal-edit-{{ $emp->id }}')">Batal</button>
                        <button type="submit" class="mk-btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>

            <!-- 3. Modal Reset Password -->
            <div class="mk-modal-overlay" id="modal-reset-{{ $emp->id }}">
                <form action="{{ route('pengguna.reset', $emp->id) }}" method="POST" class="mk-modal">
                    @csrf
                    @method('PUT')
                    <div class="mk-modal-header">
                        <h3 class="mk-modal-title">Reset Password</h3>
                        <button type="button" class="mk-modal-close" onclick="closeModal('modal-reset-{{ $emp->id }}')"><i class="ph ph-x"></i></button>
                    </div>
                    <div class="mk-modal-body">
                        <p style="font-size: 14px; color: #475569; margin-bottom: 16px;">
                            Reset password untuk <strong>{{ $emp->name }}</strong>. Pengguna akan diwajibkan mengganti password pada saat login berikutnya.
                        </p>
                        <div class="mk-form-group">
                            <label class="mk-label">Password Baru</label>
                            <input type="password" name="password" class="mk-input" placeholder="Minimal 8 karakter" required>
                        </div>
                        <div class="mk-form-group">
                            <label class="mk-label">Konfirmasi Password Baru</label>
                            <input type="password" name="password_confirmation" class="mk-input" placeholder="Ketik ulang password" required>
                        </div>
                    </div>
                    <div class="mk-modal-footer">
                        <button type="button" class="mk-btn-outline" onclick="closeModal('modal-reset-{{ $emp->id }}')">Batal</button>
                        <button type="submit" class="mk-btn-primary">Reset Password</button>
                    </div>
                </form>
            </div>

            <!-- 4. Modal Ubah Status -->
            <div class="mk-modal-overlay" id="modal-status-{{ $emp->id }}">
                <form action="{{ route('pengguna.status', $emp->id) }}" method="POST" class="mk-modal">
                    @csrf
                    @method('PATCH')
                    <div class="mk-modal-header">
                        <h3 class="mk-modal-title">Ubah Status Akun</h3>
                        <button type="button" class="mk-modal-close" onclick="closeModal('modal-status-{{ $emp->id }}')"><i class="ph ph-x"></i></button>
                    </div>
                    <div class="mk-modal-body">
                        <p style="font-size: 14px; color: #475569; margin-bottom: 0;">
                            Apakah Anda yakin ingin mengubah status akun <strong>{{ $emp->name }}</strong>?
                        </p>
                    </div>
                    <div class="mk-modal-footer">
                        <button type="button" class="mk-btn-outline" onclick="closeModal('modal-status-{{ $emp->id }}')">Batal</button>
                        <button type="submit" class="mk-btn-primary">Ya, Ubah Status</button>
                    </div>
                </form>
            </div>

            <!-- 5. Modal Hapus -->
            <div class="mk-modal-overlay" id="modal-delete-{{ $emp->id }}">
                <form action="{{ route('pengguna.destroy', $emp->id) }}" method="POST" class="mk-modal">
                    @csrf
                    @method('DELETE')
                    <div class="mk-modal-header">
                        <h3 class="mk-modal-title">Hapus Akun</h3>
                        <button type="button" class="mk-modal-close" onclick="closeModal('modal-delete-{{ $emp->id }}')"><i class="ph ph-x"></i></button>
                    </div>
                    <div class="mk-modal-body">
                        <p style="font-size: 14px; color: #475569; margin-bottom: 0;">
                            Apakah Anda yakin ingin menghapus akun <strong>{{ $emp->name }}</strong>? Tindakan ini bersifat permanen dan tidak dapat dibatalkan.
                        </p>
                    </div>
                    <div class="mk-modal-footer">
                        <button type="button" class="mk-btn-outline" onclick="closeModal('modal-delete-{{ $emp->id }}')">Batal</button>
                        <button type="submit" class="mk-btn-danger">Ya, Hapus Akun</button>
                    </div>
                </form>
            </div>
        @endif
    @endif
@endforeach

@endsection

@push('scripts')
<script>
    function toggleDropdown(event, id) {
        // Cegah event bubbling agar tidak mentrigger document click
        event.stopPropagation();
        
        // Tutup dropdown lain
        document.querySelectorAll('.mk-dropdown-menu').forEach(el => {
            if(el.id !== 'dropdown-'+id) el.classList.remove('show');
        });
        
        const menu = document.getElementById('dropdown-'+id);
        const btn = event.currentTarget;
        
        if (!menu.classList.contains('show')) {
            // Kalkulasi posisi fixed agar dropdown lepas dari clipping tabel overflow
            const rect = btn.getBoundingClientRect();
            menu.style.top = (rect.bottom + 4) + 'px';
            menu.style.right = (window.innerWidth - rect.right) + 'px';
            menu.style.left = 'auto'; // pastikan left reset
            
            menu.classList.add('show');
        } else {
            menu.classList.remove('show');
        }
    }

    document.addEventListener('click', function(e) {
        if(!e.target.closest('.mk-action-btn') && !e.target.closest('.mk-dropdown-menu')) {
            document.querySelectorAll('.mk-dropdown-menu').forEach(el => el.classList.remove('show'));
        }
    });

    // Menutup dropdown jika user melakukan scroll, untuk menghindari dropdown melayang (floating)
    window.addEventListener('scroll', function() {
        document.querySelectorAll('.mk-dropdown-menu').forEach(el => el.classList.remove('show'));
    }, true);

    function openModal(id) {
        // Tutup semua dropdown
        document.querySelectorAll('.mk-dropdown-menu').forEach(el => el.classList.remove('show'));
        const modal = document.getElementById(id);
        if(modal) modal.classList.add('show');
    }

    function closeModal(id) {
        document.getElementById(id).classList.remove('show');
    }
    
    // Tampilkan notifikasi error/success dari Laravel validation jika ada modal tertentu
    // Dalam kasus standar, validasi error akan dilempar balik ke form original.
    // Jika ada error (biasanya dari edit/reset), idealnya modal dibiarkan terbuka.
    // Namun untuk kesederhanaan, kita bisa letakkan error global di atas halaman (sudah ada notif success).
</script>
@endpush

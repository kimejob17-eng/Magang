# Dokumentasi Sistem InsightHub

> Versi Dokumentasi: 1.0  
> Terakhir Diperbarui: Agustus 2026

---

## Daftar Isi

1. [Gambaran Umum Sistem](#1-gambaran-umum-sistem)
2. [Arsitektur Sistem](#2-arsitektur-sistem)
3. [Skema Database](#3-skema-database)
4. [Alur Autentikasi](#4-alur-autentikasi)
5. [Sistem Otorisasi (RBAC)](#5-sistem-otorisasi-rbac)
6. [Alur Input Data Konten](#6-alur-input-data-konten)
7. [Alur Dashboard & Ringkasan](#7-alur-dashboard--ringkasan)
8. [Alur Laporan & Export](#8-alur-laporan--export)
9. [Alur Manajemen Pengguna](#9-alur-manajemen-pengguna)
10. [Alur Manajemen Profil](#10-alur-manajemen-profil)
11. [Referensi API Endpoints](#11-referensi-api-endpoints)
12. [Referensi Web Routes](#12-referensi-web-routes)

---

## 1. Gambaran Umum Sistem

InsightHub adalah platform manajemen performa konten media sosial yang memungkinkan tim untuk:

- **Menginput** data performa konten dari berbagai platform media sosial (Instagram, Facebook, TikTok, YouTube Video/Shorts/Live)
- **Memvisualisasikan** ringkasan dan analitik performa konten melalui dashboard
- **Membuat laporan** terfilter yang dapat diekspor ke format Excel dan PDF
- **Mengelola pengguna** dengan sistem RBAC (Role-Based Access Control) bertingkat

### Stack Teknologi

| Komponen | Teknologi |
|---|---|
| Framework | Laravel (PHP) |
| Database | Microsoft SQL Server |
| View Engine | Blade Templates |
| Autentikasi Web | Laravel Session Auth |
| Autentikasi API | Laravel Sanctum (Token Bearer) |
| Export Excel | Maatwebsite/Laravel-Excel |
| Export PDF | Via Blade View |

---

## 2. Arsitektur Sistem

Sistem menggunakan arsitektur **monolitik modular** dengan dua jalur akses:

```
+-------------------------------------------------------------+
|                        CLIENT                               |
|                                                             |
|   Browser (Web)              API Client (Mobile/External)   |
+--------+-----------------------------+-----------------------+
         |                             |
         v                             v
+--------------------+       +------------------------+
|   routes/web.php   |       |   routes/api.php       |
|  (Session Auth)    |       |  (Sanctum Token Auth)  |
+--------+-----------+       +----------+-------------+
         |                              |
         v                              v
+-------------------------------------------------------------+
|                    MIDDLEWARE LAYER                         |
|  +------------------+   +------------------------------+   |
|  | ForceChange      |   | CheckPermission (RBAC)       |   |
|  | Password         |   | (menuDetailSlug + permSlug)  |   |
|  +------------------+   +------------------------------+   |
+-------------------------------------------------------------+
         |
         v
+-------------------------------------------------------------+
|                   CONTROLLER LAYER                          |
|                                                             |
|  Auth/          Api/              (root)                    |
|  WebController  AuthController    DashboardController       |
|  ChangePassword InputController   ManajemenPengguna         |
|  Controller     RingkasanCtrl     ExportRequestController   |
|                 KategoriCtrl      KategoriKontenController  |
|                 PlatformCtrl      ProfileController         |
|                                  PlatformController        |
+-------------------------------------------------------------+
         |
         v
+-------------------------------------------------------------+
|                     MODEL LAYER                             |
|                                                             |
|  Auth/          Konten/            Pengaturan/              |
|  User           KontenInstagram    Platform                 |
|  Role           KontenFacebook                              |
|  RolePermission KontenTiktok       ExportRequest (root)     |
|  Permission     KontenYoutubeVideo                          |
|  Menu           KontenYoutubeShorts                         |
|  MenuDetail     KontenYoutubeLive                           |
|                 KategoriKonten                              |
|                 RekapKonten (View)                          |
+-------------------------------------------------------------+
         |
         v
+-------------------------------------------------------------+
|              DATABASE (Microsoft SQL Server)                |
|                                                             |
|  Tabel Konten          Tabel Auth        Tabel Lainnya      |
|  konten_facebook       users             platform           |
|  konten_instagram      roles             export_requests    |
|  konten_tiktok         role_permissions  kategori_konten    |
|  konten_youtube_video  permissions                          |
|  konten_youtube_shorts menus                                |
|  konten_youtube_live   menu_details                         |
|                                                             |
|  VIEW: v_rekap_konten (UNION ALL semua tabel konten)        |
+-------------------------------------------------------------+
```

---

## 3. Skema Database

### 3.1 Tabel Pengguna & Autentikasi

#### `users`
| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | bigint | Primary key |
| `name` | string(150) | Nama lengkap |
| `username` | string(50) | Username unik untuk login Web |
| `email` | string | Email unik untuk login API |
| `password` | string | Password (hashed bcrypt) |
| `must_change_password` | boolean | Flag wajib ganti password |
| `role` | string | String role: `super-admin`, `admin`, `user` |
| `role_id` | FK → `roles` | Foreign key ke tabel RBAC roles |
| `phone` | string(20) | Nomor telepon |
| `location` | string(100) | Lokasi |
| `avatar` | string | Path foto profil |

#### `roles`
| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | bigint | Primary key |
| `name` | string(100) | Nama role (unik) |
| `slug` | string(100) | Slug role (unik), contoh: `super-admin`, `admin`, `user` |
| `description` | string(255) | Deskripsi role |
| `is_aktif` | boolean | Status aktif role |

#### `menus`
| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | bigint | Primary key |
| `name` | string(100) | Nama menu |
| `slug` | string(100) | Slug menu (unik) |
| `urutan` | integer | Urutan tampil |
| `is_aktif` | boolean | Status aktif |

#### `menu_details`
| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | bigint | Primary key |
| `menu_id` | FK → `menus` | Induk menu |
| `name` | string(150) | Nama sub-menu |
| `slug` | string(150) | Slug unik, dipakai oleh `CheckPermission` middleware |
| `urutan` | integer | Urutan tampil |
| `is_aktif` | boolean | Status aktif |

#### `permissions`
| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | bigint | Primary key |
| `name` | string(50) | Nama permission (unik) |
| `slug` | string(50) | Slug permission: `view`, `create`, `edit`, `delete` |

#### `role_permissions`
| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | bigint | Primary key |
| `role_id` | FK → `roles` | Role yang mendapat permission |
| `menu_detail_id` | FK → `menu_details` | Sub-menu yang diizinkan |
| `permission_id` | FK → `permissions` | Jenis aksi yang diizinkan |

---

### 3.2 Tabel Platform & Kategori

#### `platform`
| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | bigint | Primary key |
| `nama` | string(50) | Nama platform |
| `slug` | string(50) | Slug unik: `instagram`, `facebook`, `tiktok`, `youtube` |
| `is_aktif` | boolean | Status aktif |

> **Catatan:** Platform di-seed via SQL. Ada 4 baris: Instagram, Facebook, TikTok, YouTube. Tiga varian YouTube (Video/Shorts/Live) semuanya merujuk ke 1 baris platform `youtube`.

#### `kategori_konten`
| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | bigint | Primary key |
| `nama` | string(100) | Nama kategori |
| `platform_id` | FK → `platform` | Platform terkait |

---

### 3.3 Tabel Konten per Platform

Setiap platform memiliki tabel tersendiri dengan kolom metrik yang berbeda-beda.

#### `konten_instagram`
| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | bigint | Primary key |
| `kategori_id` | FK → `kategori_konten` | Kategori konten |
| `judul` | string(255) | Judul konten |
| `jenis_konten` | string(50) | Jenis konten (Feed Post, dll.) |
| `tautan` | string(500) | URL konten |
| `tanggal_tayang` | date | Tanggal publish |
| `reach` | integer | Jangkauan |
| `views` | integer | Penayangan |
| `likes` | integer | Suka |
| `comments` | integer | Komentar |
| `shares` | integer | Dibagikan |
| `repost` | integer | Repost |
| `saves` | integer | Disimpan |

#### `konten_facebook`
| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | bigint | Primary key |
| `kategori_id` | FK → `kategori_konten` | Kategori konten |
| `judul` | string(255) | Judul konten |
| `jenis_konten` | string(50) | Jenis konten (Image Post, dll.) |
| `tautan` | string(500) | URL konten |
| `tanggal_tayang` | date | Tanggal publish |
| `views` | integer | Penayangan |
| `likes` | integer | Suka |
| `comments` | integer | Komentar |
| `shares` | integer | Dibagikan |
| `saves` | integer | Disimpan |

#### `konten_tiktok`
| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | bigint | Primary key |
| `kategori_id` | FK → `kategori_konten` | Kategori konten |
| `judul` | string(255) | Judul konten |
| `jenis_konten` | string(50) | Jenis konten (Short Video, dll.) |
| `tautan` | string(500) | URL konten |
| `tanggal_tayang` | date | Tanggal publish |
| `tayangan` | integer | Penayangan (= `reach` di VIEW) |
| `total_interaksi` | integer | Total interaksi (disimpan langsung) |
| `likes` | integer | Suka |
| `comments` | integer | Komentar |
| `shares` | integer | Dibagikan |
| `saves` | integer | Disimpan |

#### `konten_youtube_video`
| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | bigint | Primary key |
| `kategori_id` | FK → `kategori_konten` | Kategori konten |
| `judul` | string(255) | Judul konten |
| `tautan` | string(500) | URL konten |
| `tanggal_tayang` | date | Tanggal publish |
| `jumlah_penayangan` | integer | Jumlah penayangan |
| `penambahan_subscriber` | integer | Penambahan subscriber |
| `likes` | integer | Suka |
| `comments` | integer | Komentar |
| `shares` | integer | Dibagikan |

#### `konten_youtube_shorts`
| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | bigint | Primary key |
| `kategori_id` | FK → `kategori_konten` | Kategori konten |
| `judul` | string(255) | Judul konten |
| `tautan` | string(500) | URL konten |
| `tanggal_tayang` | date | Tanggal publish |
| `jumlah_penayangan` | integer | Jumlah penayangan |
| `penambahan_subscriber` | integer | Penambahan subscriber |
| `likes` | integer | Suka |
| `comments` | integer | Komentar |
| `shares` | integer | Dibagikan |
| `repost` | integer | Repost (unik untuk Shorts) |

#### `konten_youtube_live`
| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | bigint | Primary key |
| `kategori_id` | FK → `kategori_konten` | Kategori konten |
| `judul` | string(255) | Judul konten |
| `tautan` | string(500) | URL konten |
| `tanggal_tayang` | date | Tanggal publish |
| `jumlah_penayangan` | integer | Jumlah penayangan |
| `penambahan_subscriber` | integer | Penambahan subscriber |
| `penonton_puncak` | integer | Penonton puncak (unik untuk Live) |
| `likes` | integer | Suka |
| `comments` | integer | Komentar |
| `shares` | integer | Dibagikan |

---

### 3.4 VIEW: `v_rekap_konten`

VIEW ini adalah **sumber data utama** untuk seluruh tampilan ringkasan, analitik, dan laporan. Dibuat dengan `UNION ALL` dari semua 6 tabel konten.

**Kolom standar VIEW:**

| Kolom | Keterangan |
|---|---|
| `id_konten` | ID unik lintas platform (prefix: FB-, IG-, TK-, YV-, YS-, YL-) |
| `platform_slug` | Slug platform dari tabel `platform` |
| `platform_nama` | Nama platform dari tabel `platform` |
| `kategori` | Nama kategori |
| `judul` | Judul konten |
| `jenis_konten` | Jenis konten |
| `tautan` | URL konten |
| `tgl_upload` | Tanggal tayang |
| `reach` | Jangkauan (mapping: views=Facebook, reach=Instagram, tayangan=TikTok, jumlah_penayangan=YouTube) |
| `total_interaksi` | likes + comments + shares (+ repost untuk Shorts/Instagram) |
| `suka` | Likes |
| `komentar` | Comments |
| `dibagikan` | Shares |
| `penambahan_subscriber` | Khusus YouTube |
| `penonton_puncak` | Khusus YouTube Live |
| `diinput_oleh` | Nama user yang menginput |
| `dibuat_pada` | Timestamp pembuatan |

---

### 3.5 Tabel Export Request

#### `export_requests`
| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | bigint | Primary key |
| `user_id` | FK → `users` | User yang mengajukan |
| `type` | enum(pdf, excel) | Jenis dokumen |
| `reason` | text | Alasan permintaan |
| `details` | text | Detail tambahan |
| `status` | enum(pending, approved, rejected) | Status permintaan |
| `admin_id` | FK → `users` | Admin yang memproses |
| `reject_reason` | text | Alasan penolakan |
| `filters` | json | Filter laporan yang disimpan |

---

## 4. Alur Autentikasi

Sistem menyediakan **dua jalur autentikasi**: Web (session-based) dan API (token-based).

### 4.1 Autentikasi Web (Session)

```
+----------+    +------------------+    +--------------+
|  Browser |    |  WebController   |    |   Database   |
+-----+----+    +--------+---------+    +------+-------+
      |                  |                     |
      |  GET /login      |                     |
      | ---------------► |                     |
      |                  |                     |
      | ◄- Tampil Form   |                     |
      |                  |                     |
      |  POST /login     |                     |
      |  {username, pwd} |                     |
      | ---------------► |                     |
      |                  |  Auth::attempt()    |
      |                  | ------------------► |
      |                  | ◄-- ok/fail         |
      |                  |                     |
      | [GAGAL] ◄-- Error|                     |
      |                  |                     |
      | [BERHASIL]       |                     |
      |                  | cek must_change_pwd |
      |                  |                     |
      | [must=true]      |                     |
      | ◄-- redirect /change-password          |
      |                  |                     |
      | [must=false]     |                     |
      | ◄-- redirect /dashboard               |
```

**Kredensial Login Web:** `username` + `password`

**File terkait:**
- Route: `backend/routes/web.php` (baris 19-20)
- Controller: `backend/app/Http/Controllers/Auth/WebController.php`
- View: `frontend/resources/views/auth/login.blade.php`

---

### 4.2 Autentikasi API (Token Bearer)

```
+------------+    +------------------+    +--------------+
| API Client |    |  AuthController  |    |   Database   |
+-----+------+    +--------+---------+    +------+-------+
      |                    |                     |
      |  POST /api/login   |                     |
      |  {email, password} |                     |
      | -----------------► |                     |
      |                    | Cari user by email  |
      |                    | ------------------->|
      |                    | ◄-- User ditemukan  |
      |                    |                     |
      |                    | Hash::check(pwd)    |
      |                    |                     |
      | [GAGAL] ◄-- 401    |                     |
      |                    |                     |
      | [BERHASIL]         | createToken()       |
      |                    | ------------------->|
      |                    | ◄-- Token tersimpan |
      |                    |                     |
      | ◄-- 200 {          |                     |
      |   access_token,    |                     |
      |   require_password |                     |
      |   _change: bool    |                     |
      | }                  |                     |
```

**Kredensial Login API:** `email` + `password` (+ optional `role`)

**File terkait:**
- Route: `backend/routes/api.php` (baris 12)
- Controller: `backend/app/Http/Controllers/Api/AuthController.php`

---

### 4.3 Alur Ganti Password Wajib

Ketika `must_change_password = true`, pengguna **tidak dapat mengakses route apapun** (selain `/change-password`) berkat middleware `ForceChangePassword`.

```
+--------------------------------------------------------------+
|               ForceChangePassword Middleware                 |
|                                                              |
|  Request masuk                                               |
|       |                                                      |
|       v                                                      |
|  user->must_change_password ?                                |
|       |                                                      |
|  [YA] v                                                      |
|  Request adalah API?                                         |
|  +-- [YA]    --> return 403 JSON {require_password_change}   |
|  +-- [TIDAK] --> redirect ke /change-password               |
|                                                              |
|  [TIDAK] --> lanjutkan ke controller                         |
+--------------------------------------------------------------+
```

Setelah password berhasil diganti:
- Flag `must_change_password` di-set ke `false`
- Pengguna di-redirect ke `/dashboard`

**File terkait:**
- Middleware: `backend/app/Http/Middleware/ForceChangePassword.php`
- Controller: `backend/app/Http/Controllers/Auth/ChangePasswordController.php`

---

### 4.4 Logout

**Web:** `POST /logout` → `Auth::logout()` → session invalidate → redirect ke `/`

**API:** `POST /api/logout` → `currentAccessToken()->delete()` → token dihapus

---

## 5. Sistem Otorisasi (RBAC)

InsightHub menggunakan sistem RBAC (Role-Based Access Control) berbasis database dengan 3 tingkat role.

### 5.1 Hirarki Role

```
Super Admin
+-- Dapat melihat:  Admin, User
+-- Dapat membuat: Admin (POST /pengguna/admin)
|                  User  (POST /pengguna/user)
+-- Dapat edit:    Admin, User
+-- Dapat hapus:   Admin, User
+-- Mendapat SEMUA permission

Admin
+-- Dapat melihat:  User saja
+-- Dapat membuat: User  (POST /pengguna/user)
+-- Dapat edit:    User saja
+-- Dapat hapus:   User saja
+-- Permission sesuai mapping di role_permissions

User (Pengguna biasa)
+-- Permission sesuai mapping di role_permissions

Larangan (untuk semua role):
+-- Tidak dapat mengubah/menghapus akun sendiri
```

### 5.2 Mekanisme CheckPermission Middleware

Middleware `CheckPermission` dipanggil dengan dua parameter: `menuDetailSlug` dan `permissionSlug`.

```
+--------------------------------------------------------+
|             CheckPermission Middleware                 |
|                                                        |
|  Parameter: menuDetailSlug + permissionSlug            |
|  Contoh: 'input.instagram' + 'create'                  |
|                                                        |
|  1. Ambil user dari request                            |
|  2. Cek user memiliki role_id                          |
|  3. Query: role_permissions                            |
|     JOIN menu_details WHERE slug = menuDetailSlug      |
|     JOIN permissions WHERE slug = permissionSlug       |
|     WHERE role_id = user->role_id                      |
|                                                        |
|  [EXISTS = false] --> abort(403)                       |
|  [EXISTS = true]  --> lanjutkan                        |
+--------------------------------------------------------+
```

### 5.3 Daftar Permission yang Digunakan

| Route | menuDetailSlug | permissionSlug |
|---|---|---|
| GET /dashboard | `ringkasan.lihat` | `view` |
| GET /profile | `profil.lihat` | `view` |
| PUT /profile | `profil.edit` | `edit` |
| PUT /profile/password | `profil.ubah-password` | `edit` |
| PUT /dashboard/metrics/{id} | `input.edit` | `edit` |
| DELETE /dashboard/metrics/{id} | `input.hapus` | `delete` |
| GET /dashboard/export/csv | `laporan.export-csv` | `view` |
| GET /dashboard/export/excel | `laporan.export-excel` | `view` |
| GET /dashboard/export/pdf | `laporan.export-pdf` | `view` |
| GET /pengguna | `manajemen-pengguna.lihat` | `view` |
| POST /pengguna/admin | `manajemen-pengguna.tambah` | `create` |
| POST /pengguna/user | `manajemen-pengguna.tambah-user` | `create` |
| PUT /pengguna/{id} | `manajemen-pengguna.edit` | `edit` |
| DELETE /pengguna/{id} | `manajemen-pengguna.hapus` | `delete` |
| POST /api/input/instagram | `input.instagram` | `create` |
| POST /api/input/facebook | `input.facebook` | `create` |
| POST /api/input/tiktok | `input.tiktok` | `create` |
| POST /api/input/youtube/video | `input.youtube-video` | `create` |
| POST /api/input/youtube/shorts | `input.youtube-shorts` | `create` |
| POST /api/input/youtube/live | `input.youtube-live` | `create` |

---

## 6. Alur Input Data Konten

Data konten diinput melalui **API endpoint** (`POST /api/dashboard/input/{platform}`) yang dipanggil oleh form di halaman web menggunakan fetch/AJAX.

### 6.1 Alur Umum Input Konten

```
+----------+  +------------------+  +------------------+  +-----------+
|  Form    |  |  InputController |  |  KategoriKonten  |  |  Database |
|  (Blade) |  |  (API)           |  |  (Model)         |  |           |
+----+-----+  +--------+---------+  +--------+---------+  +-----+-----+
     |                  |                    |                   |
     | POST /api/input/ |                    |                   |
     | {platform}       |                    |                   |
     | {title, date,    |                    |                   |
     |  metrics...}     |                    |                   |
     | ---------------► |                    |                   |
     |                  |                    |                   |
     |                  | Validasi request   |                   |
     |                  | (per-platform      |                   |
     |                  |  rules)            |                   |
     |                  |                    |                   |
     | [GAGAL] ◄-- 422  |                    |                   |
     |                  |                    |                   |
     |                  | resolveKategoriId()|                   |
     |                  | (category name +   |                   |
     |                  |  platform_slug)    |                   |
     |                  | ------------------>|                   |
     |                  |                    | firstOrCreate()   |
     |                  |                    | ------------------>
     |                  |                    | ◄-- kategori_id   |
     |                  | ◄-- kategori_id    |                   |
     |                  |                    |                   |
     |                  | parseIndonesian    |                   |
     |                  | Metric()           |                   |
     |                  | (1,5rb -> 1500)    |                   |
     |                  |                    |                   |
     |                  | KontenXxx::create()|                   |
     |                  | ---------------------------------------->
     |                  | ◄-- tersimpan      |                   |
     |                  |                    |                   |
     | ◄-- 201 {success,|                    |                   |
     |       data}      |                    |                   |
```

### 6.2 Parsing Format Angka Indonesia

`InputController` memiliki fungsi `parseIndonesianMetric()` yang mengonversi berbagai format angka Indonesia ke integer:

| Input | Hasil |
|---|---|
| `"1000"` | `1000` |
| `"1.000"` | `1000` |
| `"1,5rb"` | `1500` |
| `"2jt"` | `2000000` |
| `"1,5jt"` | `1500000` |

### 6.3 Spesifikasi Metrik per Platform

| Platform | Field Unik |
|---|---|
| **Instagram** | `reach`, `views`, `likes`, `comments`, `shares` |
| **Facebook** | `views`, `saves`, `likes`, `comments`, `shares` |
| **TikTok** | `tayangan` (views), `total_interaksi`, `saves`, `likes`, `comments`, `shares` |
| **YouTube Video** | `jumlah_penayangan`, `penambahan_subscriber`, `likes`, `comments`, `shares` |
| **YouTube Shorts** | `jumlah_penayangan`, `penambahan_subscriber`, `likes`, `comments`, `repost`, `shares` |
| **YouTube Live** | `jumlah_penayangan`, `penambahan_subscriber`, `penonton_puncak`, `likes`, `comments`, `shares` |

### 6.4 Mapping Platform Slug ke Model

| Form slug | Platform tabel | Model Eloquent |
|---|---|---|
| `instagram` | instagram | `KontenInstagram` |
| `facebook` | facebook | `KontenFacebook` |
| `tiktok` | tiktok | `KontenTiktok` |
| `yt-video` | youtube | `KontenYoutubeVideo` |
| `yt-shorts` | youtube | `KontenYoutubeShorts` |
| `yt-live` | youtube | `KontenYoutubeLive` |

---

## 7. Alur Dashboard & Ringkasan

### 7.1 Halaman Dashboard Web (`GET /dashboard`)

`DashboardController::index()` menyiapkan **semua data** untuk semua tab di halaman dashboard dalam satu request.

```
DashboardController::index()
|
+-- RekapKonten::orderBy('tgl_upload', 'desc')->get()
|   -> $metrics (semua data dari VIEW v_rekap_konten)
|
+-- KategoriKonten::with('platform')->get()
|   -> $kategoris (untuk dropdown)
|
+-- Filter Ringkasan (ringkasan_platform, ringkasan_periode)
|   -> $metricsRingkasan (data terfilter periode)
|   -> $metricsRingkasanPrev (data periode sebelumnya, untuk perbandingan)
|   -> $kpiGlobal (total_konten, total_reach, total_engagement, dll.)
|
+-- Filter Analitik (analitik_platform, analitik_periode)
|   -> $metricsAnalitik (data terfilter untuk chart)
|   -> $chartData (data series untuk grafik)
|
+-- Filter Laporan (lap_platform, lap_periode_type, lap_date_start/end, dll.)
    -> $metricsLaporan (data laporan terfilter + sorted)
    -> $laporanAgg (agregat: total_konten, total_reach, avg_er)
```

**Periode yang tersedia:** `semua`, `harian`, `mingguan`, `bulanan`, `tahunan`

### 7.2 API Ringkasan (`GET /api/dashboard/ringkasan`)

`RingkasanController::index()` mengembalikan data ringkasan dalam format JSON.

**Response JSON:**
```json
{
  "success": true,
  "data": {
    "kpi_global": {
      "total_konten": 150,
      "total_followers": 25000,
      "total_engagement": 8000,
      "total_reach": 120000,
      "engagement_rate": 6.67,
      "pertumbuhan_followers": 300
    },
    "per_platform": [
      {
        "platform": "Instagram",
        "slug": "instagram",
        "warna": "#e1306c",
        "total_konten": 50,
        "total_reach": 45000,
        "total_engagement": 3200,
        "total_followers": 8000
      }
    ],
    "konten_terbaru": ["..."]
  }
}
```

### 7.3 API Konten Terbaru (`GET /api/dashboard/ringkasan/konten-terbaru`)

Mengembalikan daftar konten terbaru dengan parameter `?limit=` (default 5, max 50).

---

## 8. Alur Laporan & Export

### 8.1 Alur Export Langsung (Tanpa Approval)

Tersedia untuk pengguna dengan permission `laporan.export-*`:

```
User --> GET /dashboard/export/excel?{filter params}
                   |
                   v
        DashboardController::exportExcel()
                   |
                   v
        new LaporanExport($request)
                   |
                   v
        LaporanExport::view()
        (blade: pages.Report.export_excel)
                   |
                   v
        Excel::download() --> file .xlsx
```

### 8.2 Alur Export Request (Dengan Approval)

Digunakan ketika pengguna tidak memiliki akses export langsung atau perlu persetujuan admin.

```
+----------+  +------------------------+  +----------+
|   User   |  | ExportRequestController|  |  Admin   |
+----+-----+  +----------+-------------+  +----+-----+
     |                    |                    |
     | POST /export-      |                    |
     | requests           |                    |
     | {type, reason,     |                    |
     |  details, filters} |                    |
     | -----------------> |                    |
     |                    | ExportRequest      |
     |                    | ::create()         |
     |                    | status='pending'   |
     |                    |                    |
     | ◄-- redirect back  |                    |
     |  (pesan: menunggu  |                    |
     |   persetujuan)     |                    |
     |                    |                    |
     |                    | ◄-- PATCH /approve |
     |                    |     (oleh Admin)   |
     |                    |                    |
     |                    | update status      |
     |                    | = 'approved'       |
     |                    | admin_id = id      |
     |                    |                    |
     | GET /export-       |                    |
     | requests/{id}/     |                    |
     | download           |                    |
     | -----------------> |                    |
     |                    | Cek: status=ok?    |
     |                    | Cek: user_id ok?   |
     |                    |                    |
     |                    | Panggil Dashboard  |
     |                    | exportExcel/Pdf()  |
     |                    | (filter tersimpan) |
     | ◄-- file download  |                    |
```

**Status Export Request:**
- `pending` → menunggu persetujuan admin
- `approved` → disetujui, user dapat download
- `rejected` → ditolak dengan alasan penolakan

### 8.3 Filter Laporan

Filter yang tersedia di halaman laporan dan dapat disimpan dalam `export_requests.filters`:

| Parameter | Keterangan |
|---|---|
| `lap_platform` | Platform: `all`, `instagram`, `facebook`, `tiktok`, `yt-live`, `yt-video`, `yt-shorts` |
| `lap_periode_type` | Tipe periode: `range`, `bulan`, `tahun`, atau kosong (semua) |
| `lap_date_start` | Tanggal mulai (untuk type=range) |
| `lap_date_end` | Tanggal akhir (untuk type=range) |
| `lap_bulan` | Bulan (01-12, untuk type=bulan) |
| `lap_tahun_bulan` | Tahun untuk filter bulan |
| `lap_tahun` | Tahun (untuk type=tahun) |
| `lap_search` | Pencarian kata kunci (judul, kategori, platform) |
| `lap_sort` | Kolom sort (whitelist: `tgl_upload`, `platform`, `judul_konten`, dll.) |
| `lap_dir` | Arah sort: `asc` atau `desc` |

---

## 9. Alur Manajemen Pengguna

### 9.1 Hierarki Manajemen

```
Super Admin
+-- Dapat melihat:  Admin, User
+-- Dapat membuat: Admin (POST /pengguna/admin)
|                  User  (POST /pengguna/user)
+-- Dapat edit:    Admin, User
+-- Dapat hapus:   Admin, User

Admin
+-- Dapat melihat:  User saja
+-- Dapat membuat: User  (POST /pengguna/user)
+-- Dapat edit:    User saja
+-- Dapat hapus:   User saja

Larangan (untuk semua role):
+-- Tidak dapat mengubah/menghapus akun sendiri
```

### 9.2 Alur Reset Password oleh Admin

```
Admin --> PUT /pengguna/{id}/reset-password
              |
              v
    checkTargetUserAccess()
    (pastikan tidak self-target,
     pastikan target sesuai level akses)
              |
              v
    user->update({
      password: Hash::make(newPassword),
      must_change_password: TRUE
      (User WAJIB ganti saat login berikutnya)
    })
```

### 9.3 Status Akun

> **Catatan:** Fitur toggle status (aktif/nonaktif) belum didukung karena kolom `is_active` tidak ada di tabel `users` saat ini. Endpoint `/pengguna/{id}/status` akan mengembalikan pesan error informatif.

---

## 10. Alur Manajemen Profil

### 10.1 Update Informasi Pribadi

```
User --> PUT /profile
{name, email, phone, location, avatar}
             |
             v
   ProfileController::update()
   - Validasi input
   - Upload avatar ke public/uploads/avatars/ (jika ada file)
   - Hapus avatar lama (jika ada dan ada file baru)
   - user->update(data)
             |
             v
   Redirect back dengan pesan sukses
```

### 10.2 Update Password (dari Profil)

```
User --> PUT /profile/password
{current_password, password, password_confirmation}
             |
             v
   ProfileController::updatePassword()
   - Hash::check(current_password, user->password)
   - [GAGAL]    --> redirect back + error
   - [BERHASIL] --> user->update(password: Hash::make(new))
   --> Redirect back dengan pesan sukses
```

> **Perbedaan dengan ChangePassword:**
>
> `ProfileController::updatePassword()` adalah untuk penggantian password **opsional** dari halaman profil.
>
> `ChangePasswordController::changePassword()` adalah untuk penggantian password **wajib** ketika `must_change_password = true`.

---

## 11. Referensi API Endpoints

### Public Endpoints (Tanpa Token)

| Method | URL | Controller | Keterangan |
|---|---|---|---|
| POST | `/api/login` | `Api\AuthController@login` | Login API, returns Bearer token |

### Protected Endpoints (Wajib Token Bearer)

#### Autentikasi
| Method | URL | Controller | Keterangan |
|---|---|---|---|
| POST | `/api/logout` | `Api\AuthController@logout` | Revoke token saat ini |
| POST | `/api/change-password` | `Auth\ChangePasswordController@changePassword` | Ganti password wajib |

#### Ringkasan
| Method | URL | Permission | Keterangan |
|---|---|---|---|
| GET | `/api/dashboard/ringkasan` | `ringkasan.lihat` `view` | KPI + per-platform + konten terbaru |
| GET | `/api/dashboard/ringkasan/konten-terbaru` | `ringkasan.lihat` `view` | Konten terbaru (limit via `?limit=`) |

#### Kategori
| Method | URL | Keterangan |
|---|---|---|
| GET | `/api/kategori` | List semua kategori |
| POST | `/api/kategori` | Buat kategori baru |
| PUT | `/api/kategori/{id}` | Update kategori |
| DELETE | `/api/kategori/{id}` | Hapus kategori |

#### Platform
| Method | URL | Keterangan |
|---|---|---|
| GET | `/api/platform` | List semua platform |
| PUT | `/api/platform/{id}` | Update platform (toggle is_aktif) |

#### Input Konten
| Method | URL | Permission |
|---|---|---|
| POST | `/api/dashboard/input/instagram` | `input.instagram` `create` |
| POST | `/api/dashboard/input/facebook` | `input.facebook` `create` |
| POST | `/api/dashboard/input/tiktok` | `input.tiktok` `create` |
| POST | `/api/dashboard/input/youtube/video` | `input.youtube-video` `create` |
| POST | `/api/dashboard/input/youtube/shorts` | `input.youtube-shorts` `create` |
| POST | `/api/dashboard/input/youtube/live` | `input.youtube-live` `create` |

### Request Body Input Konten

**Instagram:**
```json
{
  "title": "Judul Konten",
  "category": "Nama Kategori",
  "publish_date": "2026-08-01",
  "content_type": "Feed Post",
  "url": "https://...",
  "reach": "1,5rb",
  "views": "2.000",
  "like": "500",
  "comment": "50",
  "share": "20"
}
```

**Facebook:**
```json
{
  "title": "Judul Konten",
  "category": "Nama Kategori",
  "publish_date": "2026-08-01",
  "content_type": "Image Post",
  "url": "https://...",
  "views": "5000",
  "saves": "100",
  "like": "300",
  "comment": "40",
  "share": "15"
}
```

**TikTok:**
```json
{
  "title": "Judul Konten",
  "category": "Nama Kategori",
  "publish_date": "2026-08-01",
  "content_type": "Short Video",
  "url": "https://...",
  "views": "10rb",
  "interactions": "500",
  "saves": "200",
  "like": "800",
  "comment": "100",
  "share": "50"
}
```

**YouTube Video / Shorts / Live (basis):**
```json
{
  "title": "Judul Konten",
  "category": "Nama Kategori",
  "publish_date": "2026-08-01",
  "url": "https://...",
  "views": "50.000",
  "like": "2000",
  "comment": "300",
  "share": "150",
  "subscribers": "500"
}
```

**Tambahan YouTube Shorts:**
```json
{ "repost": "80" }
```

**Tambahan YouTube Live:**
```json
{ "peak_viewers": "3000" }
```

---

## 12. Referensi Web Routes

### Public Routes (Tanpa Login)

| Method | URL | Controller | View |
|---|---|---|---|
| GET | `/` | Closure | `welcome.blade.php` |
| GET | `/login` | `Auth\WebController@showLogin` | `auth/login.blade.php` |
| POST | `/login` | `Auth\WebController@login` | — |

### Protected Routes (Wajib Login)

#### Autentikasi
| Method | URL | Controller | Keterangan |
|---|---|---|---|
| POST | `/logout` | `Auth\WebController@logout` | — |
| GET | `/change-password` | `Auth\ChangePasswordController@showChangePasswordForm` | Form ganti password wajib |
| POST | `/change-password` | `Auth\ChangePasswordController@changePassword` | Proses ganti password wajib |

#### Profil
| Method | URL | Permission |
|---|---|---|
| GET | `/profile` | `profil.lihat` `view` |
| PUT | `/profile` | `profil.edit` `edit` |
| PUT | `/profile/password` | `profil.ubah-password` `edit` |

#### Dashboard & Metrik
| Method | URL | Permission | Keterangan |
|---|---|---|---|
| GET | `/dashboard` | `ringkasan.lihat` `view` | Halaman utama dengan semua tab |
| POST | `/dashboard/metrics` | — | Simpan metrik |
| PUT | `/dashboard/metrics/{platform}/{id}` | `input.edit` `edit` | Update metrik |
| DELETE | `/dashboard/metrics/{platform}/{id}` | `input.hapus` `delete` | Hapus metrik |

#### Export Langsung
| Method | URL | Permission |
|---|---|---|
| GET | `/dashboard/export/csv` | `laporan.export-csv` `view` |
| GET | `/dashboard/export/excel` | `laporan.export-excel` `view` |
| GET | `/dashboard/export/pdf` | `laporan.export-pdf` `view` |

#### Export Request (Approval Flow)
| Method | URL | Keterangan |
|---|---|---|
| POST | `/export-requests` | Ajukan permintaan export |
| PATCH | `/export-requests/{id}/approve` | Setujui (admin only) |
| PATCH | `/export-requests/{id}/reject` | Tolak (admin only) |
| GET | `/export-requests/{id}/download` | Download (user pemilik atau admin) |

#### Master Data – Kategori
| Method | URL | Keterangan |
|---|---|---|
| GET | `/kategori` | List kategori |
| POST | `/kategori` | Buat kategori |
| PUT | `/kategori/{id}` | Update kategori |
| DELETE | `/kategori/{id}` | Hapus kategori |
| (AJAX) | `/ajax/kategori` | API Resource untuk modal CRUD |

#### Manajemen Pengguna
| Method | URL | Permission | Keterangan |
|---|---|---|---|
| GET | `/pengguna` | `manajemen-pengguna.lihat` `view` | List pengguna (dengan filter & search) |
| POST | `/pengguna/admin` | `manajemen-pengguna.tambah` `create` | Buat akun Admin (Super Admin only) |
| POST | `/pengguna/user` | `manajemen-pengguna.tambah-user` `create` | Buat akun User |
| PUT | `/pengguna/{id}` | `manajemen-pengguna.edit` `edit` | Update info pengguna |
| PUT | `/pengguna/{id}/reset-password` | `manajemen-pengguna.edit` `edit` | Reset password pengguna |
| PATCH | `/pengguna/{id}/status` | `manajemen-pengguna.edit` `edit` | Toggle status (belum didukung) |
| DELETE | `/pengguna/{id}` | `manajemen-pengguna.hapus` `delete` | Hapus pengguna permanen |

#### Master Data – Platform
| Method | URL | Keterangan |
|---|---|---|
| GET | `/platform` | Lihat daftar platform |
| PATCH | `/platform/{id}` | Toggle `is_aktif` platform |

---

## Catatan Tambahan

### VIEW vs Tabel Konten

- **Selalu baca** dari `v_rekap_konten` (via model `RekapKonten`) untuk tampilan, laporan, dan ringkasan.
- **Selalu tulis** ke tabel konten per platform (`KontenInstagram`, `KontenFacebook`, dll.) untuk input data baru.
- Model `RekapKonten` memiliki `$guarded = ['*']` untuk mencegah mass-assignment tidak sengaja ke VIEW.

### Format ID Konten di VIEW

ID konten di VIEW bersifat **string** dengan prefix per platform dan **tidak unik antar platform**:

| Prefix | Platform |
|---|---|
| `FB-{id}` | Facebook |
| `IG-{id}` | Instagram |
| `TK-{id}` | TikTok |
| `YV-{id}` | YouTube Video |
| `YS-{id}` | YouTube Shorts |
| `YL-{id}` | YouTube Live |

Untuk mengidentifikasi baris spesifik, selalu gunakan kombinasi `sumber_tabel + id_konten`.

### Kolom `sumber_tabel` vs `platform`

- Kolom `platform` di VIEW → mengelompokkan 3 varian YouTube menjadi **1 group** (untuk KPI)
- Kolom `sumber_tabel` di VIEW → membedakan YouTube Video/Shorts/Live sebagai **3 entitas terpisah** (untuk filter laporan)

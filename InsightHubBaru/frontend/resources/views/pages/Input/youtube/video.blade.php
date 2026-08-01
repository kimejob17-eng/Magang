{{-- ---- YOUTUBE VIDEO FORM ---- --}}
<div id="yt-tab-video" class="yt-tab-content" style="display: none;">
    <div class="platform-form-card">
        <div class="platform-form-header">
            <div class="platform-form-icon" style="background: #ff0000;">
                <i class="ph-bold ph-video-camera"></i>
            </div>
            <div>
                <h2 style="font-size: 1.1rem; font-weight: 700; color: #0f172a; margin-bottom: 0.25rem;">YouTube Video</h2>
                <p style="font-size: 0.8rem; color: #64748b;">Input data performa video reguler YouTube.</p>
            </div>
        </div>

        <form action="{{ route('dashboard.metrics.store') }}" method="POST">
            @csrf
            <input type="hidden" name="platform" value="yt-video">
            <input type="hidden" name="content_type" value="Long Video">

            <div class="form-row">
                <div class="form-group">
                    <label>Kategori Konten</label>
                    <div class="kategori-wrapper">
                        <select name="category" class="form-control" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($kategoris->where('platform.slug', 'youtube') as $kat)
                                <option value="{{ $kat->nama }}">{{ $kat->nama }}</option>
                            @endforeach
                        </select>
                        <a href="javascript:void(0)" onclick="openKategoriModal('youtube', this)" class="btn-tambah-kategori" title="Kelola Kategori" style="text-decoration:none; display:flex; align-items:center;"><i class="ph-bold ph-gear" style="margin-right:4px;"></i> Kelola</a>
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
                    <input type="text" name="title" class="form-control" placeholder="Contoh: Vlog Liburan" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Link</label>
                    <input type="url" name="url" class="form-control" placeholder="https://youtube.com/watch?v=..." required>
                </div>
                <div class="form-group">
                    <label>Jumlah Penayangan</label>
                    <input type="number" name="views" class="form-control" placeholder="0" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Penambahan Subscriber</label>
                    <div style="position: relative;">
                        <i class="ph-bold ph-users" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: #64748b;"></i>
                        <input type="number" name="subscribers" class="form-control" style="padding-left: 2.5rem;" placeholder="0" required>
                    </div>
                </div>
            </div>

            <div class="form-row-3" style="margin-top: 1rem;">
                <div class="form-group">
                    <label>Like</label>
                    <input type="number" name="like" class="form-control" placeholder="0">
                </div>
                <div class="form-group">
                    <label>Comment</label>
                    <input type="number" name="comment" class="form-control" placeholder="0">
                </div>
                <div class="form-group">
                    <label>Share</label>
                    <input type="number" name="share" class="form-control" placeholder="0">
                </div>
            </div>

            <div class="form-row" style="margin-top: 1rem;">
                <div class="form-group" style="margin-bottom: 0;">
                    <button type="reset" class="btn" style="width: 100%; justify-content: center; background: #f8fafc; color: #64748b;">Reset Form</button>
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; background: #ff0000; border-color: #ff0000;"><i class="ph-bold ph-floppy-disk"></i> Simpan Data</button>
                </div>
            </div>
        </form>
    </div>

    <!-- History Table Video -->
    <div class="card" style="margin-top: 2rem;">
        <div class="card-title" style="margin-bottom: 1rem;">Riwayat Data YouTube Video</div>
        <div style="overflow-x: auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Judul</th>
                        <th>Tayangan</th>
                        <th>Subscriber +</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($metrics->filter(fn($m) => strtolower($m->platform) === 'youtube' && $m->jenis === 'Video') as $metric)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($metric->tgl_upload)->format('d M, Y') }}</td>
                        <td style="font-weight: 500;">{{ $metric->judul_konten }}</td>
                        <td>{{ number_format($metric->reach) }}</td>
                        <td><span style="color: #16a34a; font-weight: 600;">+{{ number_format($metric->followers_plus) }}</span></td>
                        <td><a href="{{ $metric->link }}" target="_blank" style="color: var(--primary);">View</a></td>
                    </tr>
                    @empty
                    <tr><td colspan="5" style="text-align: center; color: #64748b; padding: 2rem;">Belum ada data riwayat.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
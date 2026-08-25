<div id="modal-export-history" class="lap-modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; justify-content: center; align-items: center;">
    <div class="lap-modal-content" style="background: #fff; width: 80%; max-height: 80vh; overflow-y: auto; border-radius: 8px; padding: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); position: relative;">
        <button type="button" onclick="closeHistoryModal()" style="position: absolute; top: 15px; right: 15px; background: none; border: none; font-size: 1.5rem; cursor: pointer;">&times;</button>
        <div class="lap-header" style="margin-bottom: 20px;">
            <div>
                <h1 style="margin:0; font-size: 1.5rem;">Riwayat Export</h1>
                <p style="margin:5px 0 0 0; color:#64748b;">Pantau status permintaan export dokumen.</p>
            </div>
        </div>

        @if(session('success'))
        <div style="background: #d1fae5; color: #065f46; padding: 12px 16px; border-radius: 6px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
            <div style="background: #fee2e2; color: #991b1b; padding: 12px 16px; border-radius: 6px; margin-bottom: 20px;">
                {{ session('error') }}
            </div>
        @endif

        <div class="lap-table-card">
            <table class="data-table" style="width: 100%; text-align: left; border-collapse: collapse;">
                <thead>
                    <tr>
                        <th style="padding: 12px; border-bottom: 1px solid #e2e8f0;">Tanggal</th>
                        @if(in_array(auth()->user()->role, ['super-admin', 'admin']))
                        <th style="padding: 12px; border-bottom: 1px solid #e2e8f0;">User</th>
                        @endif
                        <th style="padding: 12px; border-bottom: 1px solid #e2e8f0;">Sumber</th>
                        <th style="padding: 12px; border-bottom: 1px solid #e2e8f0;">Jenis</th>
                        <th style="padding: 12px; border-bottom: 1px solid #e2e8f0;">Alasan</th>
                        <th style="padding: 12px; border-bottom: 1px solid #e2e8f0;">Status</th>
                        <th style="padding: 12px; border-bottom: 1px solid #e2e8f0; text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($exportRequests as $req)
                    <tr>
                        <td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">{{ $req->created_at->format('d M Y H:i') }}</td>
                        @if(in_array(auth()->user()->role, ['super-admin', 'admin']))
                        <td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">{{ $req->user->name ?? 'Unknown' }}</td>
                        @endif
                        <td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">
                            @if(($req->export_source ?? 'laporan') === 'ringkasan')
                                <span style="background: #e0e7ff; color: #3730a3; padding: 4px 8px; border-radius: 4px; font-size: 0.85rem; font-weight: 500;">Ringkasan</span>
                            @else
                                <span style="background: #f3f4f6; color: #1f2937; padding: 4px 8px; border-radius: 4px; font-size: 0.85rem; font-weight: 500;">Laporan</span>
                            @endif
                        </td>
                        <td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">
                            <span style="background: #f1f5f9; padding: 4px 8px; border-radius: 4px; font-size: 0.85rem; font-weight: 500;">{{ strtoupper($req->type) }}</span>
                        </td>
                        <td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">
                            <div>{{ $req->reason }}</div>
                            @if($req->details)
                            <div style="font-size: 0.8rem; color: #64748b; margin-top: 4px;">Ket: {{ $req->details }}</div>
                            @endif
                        </td>
                        <td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">
                            @if($req->status === 'pending')
                                <span style="color: #d97706; background: #fef3c7; padding: 4px 8px; border-radius: 4px; font-size: 0.85rem;">Pending</span>
                            @elseif($req->status === 'approved')
                                <span style="color: #059669; background: #d1fae5; padding: 4px 8px; border-radius: 4px; font-size: 0.85rem;">Disetujui</span>
                            @else
                                <span style="color: #dc2626; background: #fee2e2; padding: 4px 8px; border-radius: 4px; font-size: 0.85rem;">Ditolak</span>
                                <div style="font-size: 0.8rem; color: #64748b; margin-top: 4px;">Alasan: {{ $req->reject_reason }}</div>
                            @endif
                        </td>
                        <td style="padding: 12px; border-bottom: 1px solid #e2e8f0; text-align: right;">
                            @if(in_array(auth()->user()->role, ['super-admin', 'admin']))
                                @if($req->status === 'pending')
                                    <form action="{{ route('export-requests.approve', $req->id) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" style="padding: 4px 8px; background: #10b981; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 0.75rem;">Setujui</button>
                                    </form>
                                    <button type="button" onclick="openRejectModal({{ $req->id }})" style="padding: 4px 8px; background: #ef4444; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 0.75rem;">Tolak</button>
                                @endif
                            @else
                                @if($req->status === 'approved')
                                    <a href="{{ route('export-requests.download', $req->id) }}" class="lap-btn-apply" style="display:inline-block; text-decoration:none; padding: 6px 12px; font-size: 0.85rem;">
                                        Download
                                    </a>
                                @elseif($req->status === 'pending')
                                    <span style="color: #64748b; font-size: 0.85rem; font-style: italic;">Menunggu persetujuan...</span>
                                @endif
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ in_array(auth()->user()->role, ['super-admin', 'admin']) ? '7' : '6' }}" style="padding: 20px; text-align: center; color: #64748b;">Belum ada riwayat permintaan export.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function openHistoryModal() {
    document.getElementById('modal-export-history').style.display = 'flex';
}
function closeHistoryModal() {
    document.getElementById('modal-export-history').style.display = 'none';
}
</script>

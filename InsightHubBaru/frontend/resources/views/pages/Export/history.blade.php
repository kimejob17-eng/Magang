<div id="modal-export-history" class="lap-modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px); z-index: 9999; justify-content: center; align-items: center; font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;">
    <div class="lap-modal-content" style="background: #fff; width: 90%; max-width: 950px; max-height: 85vh; overflow-y: auto; border-radius: 16px; padding: 28px; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04), 0 0 0 1px rgba(0, 0, 0, 0.05); position: relative; box-sizing: border-box;">
        
        <!-- Close Button -->
        <button type="button" onclick="closeHistoryModal()" style="position: absolute; top: 20px; right: 20px; background: #f1f5f9; border: none; border-radius: 50%; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; color: #64748b; cursor: pointer; transition: all 0.2s ease; outline: none;" onmouseover="this.style.background='#e2e8f0'; this.style.color='#1e293b';" onmouseout="this.style.background='#f1f5f9'; this.style.color='#64748b';">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 16px; height: 16px;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
            </svg>
        </button>
        
        <!-- Header -->
        <div style="display: flex; align-items: flex-start; gap: 16px; margin-bottom: 24px; padding-bottom: 16px; border-bottom: 1px solid #f1f5f9;">
            <div style="flex-shrink: 0; display: flex; align-items: center; justify-content: center; width: 44px; height: 44px; border-radius: 12px; background: #eff6ff; border: 1px solid #dbeafe; color: #003EA8;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 24px; height: 24px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5.586a1 1 0 0 1 .707.293l5.414 5.414a1 1 0 0 1 .293.707V19a2 2 0 0 1-2 2z" />
                </svg>
            </div>
            <div>
                <h1 style="margin: 0 0 4px 0; font-size: 1.25rem; font-weight: 700; color: #0f172a; letter-spacing: -0.01em;">Riwayat Permintaan Ekspor</h1>
                <p style="margin: 0; font-size: 0.85rem; color: #64748b; line-height: 1.4;">Pantau status persetujuan dokumen ekspor Anda.</p>
            </div>
        </div>

        @if(session('success'))
        <div style="background: #ecfdf5; border: 1px solid #d1fae5; color: #065f46; padding: 12px 14px; border-radius: 10px; margin-bottom: 20px; font-size: 0.85rem; display: flex; align-items: center; gap: 8px; font-family: inherit;">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" style="width: 18px; height: 18px; color: #10b981; flex-shrink: 0;">
                <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z" clip-rule="evenodd" />
            </svg>
            <span>{{ session('success') }}</span>
        </div>
        @endif
        @if(session('error'))
        <div style="background: #fef2f2; border: 1px solid #fee2e2; color: #991b1b; padding: 12px 14px; border-radius: 10px; margin-bottom: 20px; font-size: 0.85rem; display: flex; align-items: center; gap: 8px; font-family: inherit;">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" style="width: 18px; height: 18px; color: #ef4444; flex-shrink: 0;">
                <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16ZM8.28 7.22a.75.75 0 0 0-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 1 0 1.06 1.06L10 11.06l1.72 1.72a.75.75 0 1 0 1.06-1.06L11.06 10l1.72-1.72a.75.75 0 0 0-1.06-1.06L10 8.94 8.28 7.22Z" clip-rule="evenodd" />
            </svg>
            <span>{{ session('error') }}</span>
        </div>
        @endif

        <div style="width: 100%; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; background: #fff;">
            <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.875rem;">
                <thead>
                    <tr style="background: #f8fafc; border-bottom: 1px solid #e2e8f0; color: #475569; font-weight: 600;">
                        <th style="padding: 14px 16px;">Tanggal</th>
                        @if(in_array(auth()->user()->role, ['super-admin', 'admin']))
                        <th style="padding: 14px 16px;">User</th>
                        @endif
                        <th style="padding: 14px 16px;">Sumber Halaman</th>
                        <th style="padding: 14px 16px;">Jenis Dokumen</th>
                        <th style="padding: 14px 16px;">Alasan</th>
                        <th style="padding: 14px 16px;">Status Permohonan</th>
                        <th style="padding: 14px 16px; text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody style="color: #334155;">
                    @forelse($exportRequests as $req)
                    <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.15s ease;" onmouseover="this.style.background='#f8fafc';" onmouseout="this.style.background='none';">
                        <td style="padding: 14px 16px; white-space: nowrap; color: #64748b;">
                            {{ $req->created_at->format('d M Y H:i') }}
                        </td>
                        @if(in_array(auth()->user()->role, ['super-admin', 'admin']))
                        <td style="padding: 14px 16px; font-weight: 600;">
                            {{ $req->user->name ?? 'Unknown' }}
                        </td>
                        @endif
                        <td style="padding: 14px 16px; white-space: nowrap;">
                            @if(($req->export_source ?? 'laporan') === 'ringkasan')
                                <span style="background: #eff6ff; color: #003EA8; padding: 4px 10px; border-radius: 6px; font-size: 0.8rem; font-weight: 600; border: 1px solid #dbeafe;">Ringkasan</span>
                            @else
                                <span style="background: #f1f5f9; color: #475569; padding: 4px 10px; border-radius: 6px; font-size: 0.8rem; font-weight: 600; border: 1px solid #e2e8f0;">Laporan</span>
                            @endif
                        </td>
                        <td style="padding: 14px 16px; white-space: nowrap;">
                            <span style="background: #f8fafc; color: #0f172a; border: 1px solid #cbd5e1; padding: 4px 10px; border-radius: 6px; font-size: 0.8rem; font-weight: 600; text-transform: uppercase;">
                                {{ $req->type }}
                            </span>
                        </td>
                        <td style="padding: 14px 16px;">
                            <div style="font-weight: 500;">{{ $req->reason }}</div>
                            @if($req->details)
                            <div style="font-size: 0.775rem; color: #64748b; margin-top: 4px; background: #f8fafc; padding: 4px 8px; border-radius: 4px; border-left: 2.5px solid #cbd5e1;">Ket: {{ $req->details }}</div>
                            @endif
                        </td>
                        <td style="padding: 14px 16px; white-space: nowrap;">
                            @if($req->status === 'pending')
                                <span style="display: inline-flex; align-items: center; gap: 4px; color: #b45309; background: #fffbeb; border: 1px solid #fde68a; padding: 4px 10px; border-radius: 6px; font-size: 0.8rem; font-weight: 600;">
                                    <span style="width: 6px; height: 6px; border-radius: 50%; background: #d97706;"></span>
                                    Pending (Menunggu)
                                </span>
                            @elseif($req->status === 'approved')
                                <span style="display: inline-flex; align-items: center; gap: 4px; color: #047857; background: #ecfdf5; border: 1px solid #a7f3d0; padding: 4px 10px; border-radius: 6px; font-size: 0.8rem; font-weight: 600;">
                                    <span style="width: 6px; height: 6px; border-radius: 50%; background: #10b981;"></span>
                                    Accepted (Disetujui)
                                </span>
                            @else
                                <span style="display: inline-flex; align-items: center; gap: 4px; color: #b91c1c; background: #fef2f2; border: 1px solid #fecaca; padding: 4px 10px; border-radius: 6px; font-size: 0.8rem; font-weight: 600;">
                                    <span style="width: 6px; height: 6px; border-radius: 50%; background: #ef4444;"></span>
                                    Rejected (Ditolak)
                                </span>
                                <div style="font-size: 0.775rem; color: #64748b; margin-top: 6px; padding: 4px 8px; background: #fef2f2; border-left: 3px solid #ef4444; border-radius: 4px; white-space: normal;">Alasan: {{ $req->reject_reason }}</div>
                            @endif
                        </td>
                        <td style="padding: 14px 16px; text-align: right; white-space: nowrap;">
                            @if(in_array(auth()->user()->role, ['super-admin', 'admin']))
                                @if($req->status === 'pending')
                                    <div style="display: flex; justify-content: flex-end; gap: 6px;">
                                        <button type="button" onclick="openRejectModal({{ $req->id }})" style="padding: 6px 12px; background: #fff; color: #ef4444; border: 1px solid #fca5a5; border-radius: 8px; cursor: pointer; font-size: 0.8rem; font-weight: 600; transition: all 0.2s;" onmouseover="this.style.background='#fef2f2';" onmouseout="this.style.background='#fff';">
                                            Tolak
                                        </button>
                                        <form action="{{ route('export-requests.approve', $req->id) }}" method="POST" style="margin: 0;">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" style="padding: 6px 12px; background: #10b981; color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 0.8rem; font-weight: 600; transition: all 0.2s;" onmouseover="this.style.background='#059669';" onmouseout="this.style.background='#10b981';">
                                                Setujui
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            @else
                                @if($req->status === 'approved')
                                    <a href="{{ route('export-requests.download', $req->id) }}" style="display: inline-flex; align-items: center; gap: 6px; text-decoration: none; padding: 6px 14px; background: linear-gradient(135deg, #003EA8 0%, #00225c 100%); color: white; font-weight: 600; border-radius: 8px; font-size: 0.8rem; box-shadow: 0 4px 6px -1px rgba(0, 62, 168, 0.15); transition: all 0.2s; border: none; cursor: pointer;" onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 6px 10px -1px rgba(0, 62, 168, 0.25)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 6px -1px rgba(0, 62, 168, 0.15)';">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 14px; height: 14px;">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                        </svg>
                                        Download
                                    </a>
                                @elseif($req->status === 'pending')
                                    <span style="color: #64748b; font-size: 0.8rem; font-style: italic; display: inline-flex; align-items: center; gap: 4px;">
                                        <svg class="animate-spin" style="width: 14px; height: 14px; color: #64748b;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle style="opacity: 0.25;" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path style="opacity: 0.75;" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Menunggu persetujuan...
                                    </span>
                                @else
                                    <span style="color: #94a3b8; font-size: 0.8rem; font-style: italic;">Ditolak</span>
                                @endif
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ in_array(auth()->user()->role, ['super-admin', 'admin']) ? '7' : '6' }}" style="padding: 32px 16px; text-align: center; color: #94a3b8;">
                            <div style="display: flex; flex-direction: column; align-items: center; gap: 8px;">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 32px; height: 32px; color: #cbd5e1;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 13.5h3.86a2.25 2.25 0 0 1 2.008 1.24l.885 1.77a2.25 2.25 0 0 0 2.007 1.24h1.98a2.25 2.25 0 0 0 2.007-1.24l.885-1.77a2.25 2.25 0 0 1 2.007-1.24h3.86m-18 0h18" />
                                </svg>
                                <span style="font-size: 0.85rem;">Belum ada riwayat permintaan ekspor.</span>
                            </div>
                        </td>
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

<style>
@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}
.animate-spin {
    animation: spin 1s linear infinite;
}
</style>

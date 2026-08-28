<?php

namespace App\Http\Controllers;

use App\Models\ExportRequest;
use Illuminate\Http\Request;

class ExportRequestController extends Controller
{
    public function store(Request $request)
    {
        if (in_array(auth()->user()->role, ['super-admin', 'admin'])) {
            abort(403, 'Admin dan Superadmin dapat melakukan export secara langsung.');
        }

        $validated = $request->validate([
            'type'          => 'required|in:pdf,excel',
            'export_source' => 'nullable|string|in:laporan,ringkasan',
            'reason'        => 'required|string',
            'details'       => 'nullable|string',
            'filters'       => 'nullable|array',
        ]);

        // Tentukan export_source: ambil dari field khusus, atau default 'laporan'
        $exportSource = $validated['export_source']
            ?? ($validated['filters']['export_source'] ?? 'laporan');

        if ($validated['type'] === 'excel' && !auth()->user()->hasPermission('laporan.export-excel', 'view')) {
            abort(403, 'Anda tidak memiliki izin untuk mengekspor data Excel.');
        }
        if ($validated['type'] === 'pdf' && $exportSource === 'laporan' && !auth()->user()->hasPermission('laporan.export-pdf', 'view')) {
            abort(403, 'Anda tidak memiliki izin untuk mengekspor data PDF.');
        }
        if ($validated['type'] === 'pdf' && $exportSource === 'ringkasan' && !auth()->user()->hasPermission('ringkasan.lihat', 'view')) {
            abort(403, 'Anda tidak memiliki izin untuk mengekspor Ringkasan PDF.');
        }

        // Validasi dan bersihkan data chart Base64 (hanya untuk export Ringkasan)
        $filters = $validated['filters'] ?? [];
        if ($exportSource === 'ringkasan') {
            $chartKeys = ['chart_big', 'chart_small', 'chart_pie'];
            foreach ($chartKeys as $key) {
                if (!empty($filters[$key])) {
                    $val = $filters[$key];
                    // Validasi: harus berformat data URL image PNG/JPEG/WebP
                    // canvas.toDataURL() selalu menghasilkan format ini — tidak ada risiko inject
                    $isValidDataUrl = preg_match('/^data:image\/(png|jpeg|webp);base64,[A-Za-z0-9+\/=]+$/', $val);
                    // Batasi ukuran: max 600 KB Base64 per gambar
                    $isValidSize = strlen($val) <= 614400;

                    if (!$isValidDataUrl || !$isValidSize) {
                        // Hapus data tidak valid daripada menolak seluruh request
                        $filters[$key] = null;
                    }
                }
            }
        }

        ExportRequest::create([
            'user_id'       => auth()->id(),
            'type'          => $validated['type'],
            'export_source' => $exportSource,
            'reason'        => $validated['reason'],
            'details'       => $validated['details'] ?? null,
            'filters'       => $filters,
            'status'        => 'pending',
        ]);

        return back()->with('success', 'Permintaan export dokumen berhasil dikirim dan sedang menunggu persetujuan admin.');
    }

    public function approve(Request $request, ExportRequest $exportRequest)
    {
        if (!in_array(auth()->user()->role, ['super-admin', 'admin'])) {
            abort(403, 'Hanya admin yang dapat menyetujui permintaan.');
        }

        $exportRequest->update([
            'status'   => 'approved',
            'admin_id' => auth()->id(),
        ]);

        return back()->with('success', 'Permintaan export berhasil disetujui.');
    }

    public function reject(Request $request, ExportRequest $exportRequest)
    {
        if (!in_array(auth()->user()->role, ['super-admin', 'admin'])) {
            abort(403, 'Hanya admin yang dapat menolak permintaan.');
        }

        $validated = $request->validate([
            'reject_reason' => 'required|string'
        ]);

        $exportRequest->update([
            'status'        => 'rejected',
            'admin_id'      => auth()->id(),
            'reject_reason' => $validated['reject_reason'],
        ]);

        return back()->with('success', 'Permintaan export ditolak.');
    }

    public function download(Request $request, ExportRequest $exportRequest)
    {
        // Pastikan hanya user yang meminta yang bisa mendownload
        if ($exportRequest->user_id !== auth()->id() && !in_array(auth()->user()->role, ['super-admin', 'admin'])) {
            abort(403, 'Anda tidak diizinkan mendownload dokumen ini.');
        }

        if ($exportRequest->status !== 'approved') {
            abort(403, 'Permintaan belum disetujui.');
        }

        // Buat request dengan filter yang tersimpan
        $exportParams = new Request($exportRequest->filters ?? []);

        // Gunakan kolom export_source sebagai sumber utama.
        // Fallback ke filters['export_source'] untuk kompatibilitas data
        // yang dibuat sebelum migration ini, lalu default ke 'laporan'.
        $source = $exportRequest->export_source
            ?? ($exportRequest->filters['export_source'] ?? 'laporan');

        if ($source === 'ringkasan') {
            $filename        = 'Ringkasan_Performa_Konten_' . date('Ymd_His');
            $ringkasanExport = new \App\Exports\RingkasanExport($exportParams);
            $data            = $ringkasanExport->getViewData();

            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pages.Report.export_ringkasan_pdf', $data)
                ->setPaper('a4', 'portrait');
            return $pdf->download($filename . '.pdf');
        } else {
            $laporanExport = new \App\Exports\LaporanExport($exportParams);
            $filename      = 'Laporan_Performa_Konten_' . date('Ymd_His');

            if ($exportRequest->type === 'excel') {
                return \Maatwebsite\Excel\Facades\Excel::download($laporanExport, $filename . '.xlsx');
            } else {
                $data = $laporanExport->getViewData();
                $pdf  = \Barryvdh\DomPDF\Facade\Pdf::loadView('pages.Report.export_pdf', $data)
                    ->setPaper('a4', 'portrait');
                return $pdf->download($filename . '.pdf');
            }
        }
    }
}

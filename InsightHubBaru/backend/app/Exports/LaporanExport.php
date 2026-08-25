<?php

namespace App\Exports;

use App\Models\Konten\RekapKonten;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;

class LaporanExport implements FromView, ShouldAutoSize, WithStyles, WithTitle
{
    // Sama dengan FILTER_PLATFORM_SUMBER di DashboardController.
    // Dropdown Laporan (lap_platform) -> kolom `sumber_tabel` di VIEW v_rekap_konten,
    // supaya 3 varian YouTube bisa difilter terpisah (Bagian 6.1 dokumentasi).
    private const FILTER_PLATFORM_SUMBER = [
        'facebook'  => 'facebook',
        'instagram' => 'instagram',
        'tiktok'    => 'tiktok',
        'yt-live'   => 'youtube_live',
        'yt-video'  => 'youtube_video',
        'yt-shorts' => 'youtube_shorts',
    ];

    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function getViewData()
    {
        $lapPlatform    = $this->request->get('lap_platform', 'all');
        $lapPeriodeType = $this->request->get('lap_periode_type', '');
        $lapDateStart   = $this->request->get('lap_date_start', '');
        $lapDateEnd     = $this->request->get('lap_date_end', '');
        $lapBulan       = $this->request->get('lap_bulan', '');
        $lapTahunBulan  = $this->request->get('lap_tahun_bulan', '');
        $lapTahun       = $this->request->get('lap_tahun', '');

        $lapSearch = $this->request->get('lap_search', '');
        $lapSort   = $this->request->get('lap_sort', 'tgl_upload');
        $lapDir    = $this->request->get('lap_dir', 'desc');

        $query = RekapKonten::query();

        // ---- Filter platform (via sumber_tabel, sama seperti DashboardController) ----
        if ($lapPlatform && $lapPlatform !== 'all') {
            if (isset(self::FILTER_PLATFORM_SUMBER[$lapPlatform])) {
                $query->where('sumber_tabel', self::FILTER_PLATFORM_SUMBER[$lapPlatform]);
            } else {
                $query->where('platform', ucfirst(strtolower($lapPlatform)));
            }
        }

        // ---- Filter periode ----
        if ($lapPeriodeType === 'range' && $lapDateStart && $lapDateEnd) {
            $query->whereBetween('tgl_upload', [$lapDateStart, $lapDateEnd]);
        } elseif ($lapPeriodeType === 'bulan' && $lapBulan && $lapTahunBulan) {
            $query->whereYear('tgl_upload', $lapTahunBulan)
                  ->whereMonth('tgl_upload', $lapBulan);
        } elseif ($lapPeriodeType === 'tahun' && $lapTahun) {
            $query->whereYear('tgl_upload', $lapTahun);
        }

        // ---- Pencarian kata kunci ----
        if ($lapSearch) {
            $query->where(function ($q) use ($lapSearch) {
                $q->where('judul_konten', 'like', "%{$lapSearch}%")
                  ->orWhere('kategori', 'like', "%{$lapSearch}%")
                  ->orWhere('platform', 'like', "%{$lapSearch}%");
            });
        }

        $query->orderBy($lapSort, $lapDir);
        $data = $query->get();

        // ---- Hardcoded Followers Logic (Sync with Dashboard JS) ----
        $followerAccounts = [
            'all'       => 64800,
            'instagram' => 40308,
            'tiktok'    => 2217,
            'facebook'  => 5375,
            'yt-live'   => 16900,
            'yt-video'  => 16900,
            'yt-shorts' => 16900,
            'youtube'   => 16900,
        ];
        $totalFollowers = $followerAccounts[$lapPlatform] ?? 64800;

        // ---- Agregat (nama kolom VIEW: reach, likes, comments, shares) ----
        $laporanAgg = [
            'total_konten'    => $data->count(),
            'total_reach'     => $data->sum('reach'),
            'total_eng'       => $data->sum('likes') + $data->sum('comments') + $data->sum('shares') + $data->where('sumber_tabel', 'youtube_shorts')->sum('repost'),
            'total_followers' => $totalFollowers,
        ];
        $laporanAgg['avg_er'] = $laporanAgg['total_reach'] > 0
            ? ($laporanAgg['total_eng'] / $laporanAgg['total_reach']) * 100
            : 0;

        // ---- Info filter aktif (ditampilkan di header Excel/PDF) ----
        $platformLabels = [
            'all'       => 'Semua Platform',
            'facebook'  => 'Facebook',
            'instagram' => 'Instagram',
            'tiktok'    => 'TikTok',
            'yt-live'   => 'YouTube Live',
            'yt-video'  => 'YouTube Video',
            'yt-shorts' => 'YouTube Shorts',
        ];
        $activeLabel = $platformLabels[$lapPlatform] ?? ucfirst($lapPlatform);

        $activePeriode = 'Semua Waktu';
        if ($lapPeriodeType === 'range' && $lapDateStart) {
            $activePeriode = $lapDateStart . ' s/d ' . $lapDateEnd;
        } elseif ($lapPeriodeType === 'bulan' && $lapBulan) {
            $bulanNames = [
                '01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr',
                '05' => 'Mei', '06' => 'Jun', '07' => 'Jul', '08' => 'Agu',
                '09' => 'Sep', '10' => 'Okt', '11' => 'Nov', '12' => 'Des',
            ];
            $activePeriode = ($bulanNames[$lapBulan] ?? $lapBulan) . ' ' . $lapTahunBulan;
        } elseif ($lapPeriodeType === 'tahun' && $lapTahun) {
            $activePeriode = 'Tahun ' . $lapTahun;
        }

        return [
            'metrics'    => $data,
            'laporanAgg' => $laporanAgg,
            'filterInfo' => [
                'platform' => $activeLabel,
                'periode'  => $activePeriode,
                'search'   => $lapSearch ?: '-',
            ],
        ];
    }

    public function view(): View
    {
        return view('pages.Report.export_excel', $this->getViewData());
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 14]], // Baris judul laporan
            6 => ['font' => ['bold' => true]],                // Baris header tabel
        ];
    }

    public function title(): string
    {
        return 'Laporan Performa Konten';
    }
}
<table>
    <thead>
        <tr>
            <th colspan="15" style="font-size: 16px; font-weight: bold; text-align: center;">LAPORAN PERFORMA KONTEN MEDIA SOSIAL</th>
        </tr>
        <tr>
            <th colspan="15" style="text-align: center;">SOVIE Executive Report</th>
        </tr>
        <tr>
            <th colspan="15"></th>
        </tr>
        <tr>
            <th colspan="2">Platform</th>
            <td colspan="13">: {{ $filterInfo['platform'] }}</td>
        </tr>
        <tr>
            <th colspan="2">Periode</th>
            <td colspan="13">: {{ $filterInfo['periode'] }}</td>
        </tr>
        <tr>
            <th colspan="2">Pencarian</th>
            <td colspan="13">: {{ $filterInfo['search'] }}</td>
        </tr>
        <tr>
            <th colspan="2">Dicetak pada</th>
            <td colspan="13">: {{ \Carbon\Carbon::now()->format('d M Y H:i:s') }}</td>
        </tr>
        <tr>
            <th colspan="15"></th>
        </tr>
        <tr>
            <th style="background-color: #f3f4f6; font-weight: bold; border: 1px solid #000;">No</th>
            <th style="background-color: #f3f4f6; font-weight: bold; border: 1px solid #000;">Tanggal Upload</th>
            <th style="background-color: #f3f4f6; font-weight: bold; border: 1px solid #000;">Platform</th>
            <th style="background-color: #f3f4f6; font-weight: bold; border: 1px solid #000;">Jenis Konten</th>
            <th style="background-color: #f3f4f6; font-weight: bold; border: 1px solid #000;">Kategori Konten</th>
            <th style="background-color: #f3f4f6; font-weight: bold; border: 1px solid #000;">Judul Konten</th>
            <th style="background-color: #f3f4f6; font-weight: bold; border: 1px solid #000;">Link Konten</th>
            <th style="background-color: #f3f4f6; font-weight: bold; border: 1px solid #000;">Views / Penayangan</th>
            <th style="background-color: #f3f4f6; font-weight: bold; border: 1px solid #000;">Saves / Tersimpan</th>
            <th style="background-color: #f3f4f6; font-weight: bold; border: 1px solid #000;">Reach</th>
            <th style="background-color: #f3f4f6; font-weight: bold; border: 1px solid #000;">Likes</th>
            <th style="background-color: #f3f4f6; font-weight: bold; border: 1px solid #000;">Comments</th>
            <th style="background-color: #f3f4f6; font-weight: bold; border: 1px solid #000;">Shares</th>
            <th style="background-color: #f3f4f6; font-weight: bold; border: 1px solid #000;">Engagement Rate (%)</th>
            <th style="background-color: #f3f4f6; font-weight: bold; border: 1px solid #000;">Followers Bertambah</th>
        </tr>
    </thead>
    <tbody>
        @forelse($metrics as $index => $row)
        @php
            $rowEng = $row->likes + $row->comments + $row->shares + (strtolower($row->sumber_tabel) === 'youtube_shorts' ? $row->repost : 0);
            $rowEr = $row->reach > 0 ? round(($rowEng / $row->reach) * 100, 2) : 0;
        @endphp
        <tr>
            <td style="border: 1px solid #000; text-align: center;">{{ $index + 1 }}</td>
            <td style="border: 1px solid #000;">{{ $row->tgl_upload ? \Carbon\Carbon::parse($row->tgl_upload)->format('d-m-Y') : '-' }}</td>
            <td style="border: 1px solid #000;">{{ ucfirst($row->platform) }}</td>
            <td style="border: 1px solid #000;">{{ $row->jenis ?: '-' }}</td>
            <td style="border: 1px solid #000;">{{ $row->kategori ?: '-' }}</td>
            <td style="border: 1px solid #000;">{{ $row->judul_konten ?: '-' }}</td>
            <td style="border: 1px solid #000;">{{ $row->tautan ?: '-' }}</td>
            <td style="border: 1px solid #000;">{{ number_format($row->reach) }}</td>
            <td style="border: 1px solid #000;">0</td>
            <td style="border: 1px solid #000;">{{ number_format($row->reach) }}</td>
            <td style="border: 1px solid #000;">{{ number_format($row->likes) }}</td>
            <td style="border: 1px solid #000;">{{ number_format($row->comments) }}</td>
            <td style="border: 1px solid #000;">{{ number_format($row->shares) }}</td>
            <td style="border: 1px solid #000;">{{ $rowEr }}</td>
            <td style="border: 1px solid #000;">{{ number_format($row->followers_plus) }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="15" style="text-align: center; border: 1px solid #000;">Tidak ada data ditemukan.</td>
        </tr>
        @endforelse
    </tbody>
    <tfoot>
        <tr>
            <th colspan="15"></th>
        </tr>
        <tr>
            <th colspan="2" style="font-weight: bold; background-color: #e5e7eb;">Total Konten</th>
            <td colspan="13" style="background-color: #f9fafb;">: {{ number_format($laporanAgg['total_konten']) }}</td>
        </tr>
        <tr>
            <th colspan="2" style="font-weight: bold; background-color: #e5e7eb;">Total Reach</th>
            <td colspan="13" style="background-color: #f9fafb;">: {{ number_format($laporanAgg['total_reach']) }}</td>
        </tr>
        <tr>
            <th colspan="2" style="font-weight: bold; background-color: #e5e7eb;">Total Engagement</th>
            <td colspan="13" style="background-color: #f9fafb;">: {{ number_format($laporanAgg['total_eng']) }}</td>
        </tr>
        <tr>
            <th colspan="2" style="font-weight: bold; background-color: #e5e7eb;">Rata-rata Engagement Rate</th>
            <td colspan="13" style="background-color: #f9fafb;">: {{ number_format($laporanAgg['avg_er'], 2) }}%</td>
        </tr>
    </tfoot>
</table>
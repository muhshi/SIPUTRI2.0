<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Rekap Presensi Pegawai PST - SIPUTRI 2.0</title>
    <style>
        :root {
            --bps-blue: #0066b2;
            --bps-light-blue: #e3f2fd;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.5;
            margin: 0;
            padding: 20px;
        }

        .header {
            display: flex;
            align-items: center;
            border-bottom: 3px solid var(--bps-blue);
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .header-text {
            flex-grow: 1;
            text-align: center;
        }

        .header-text h1 {
            margin: 0;
            font-size: 18px;
            color: var(--bps-blue);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .header-text h2 {
            margin: 5px 0 0;
            font-size: 14px;
            color: #555;
            font-weight: normal;
        }

        .report-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            background-color: var(--bps-light-blue);
            padding: 10px;
            border-radius: 5px;
        }

        .report-info div span {
            font-weight: bold;
            color: var(--bps-blue);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
        }

        th {
            background-color: var(--bps-blue);
            color: white;
            font-weight: bold;
            padding: 10px 8px;
            text-align: left;
            border: 1px solid #ddd;
        }

        td {
            padding: 8px;
            border: 1px solid #eee;
            vertical-align: top;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .badge-status {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 10px;
            background-color: #d1fae5;
            color: #065f46;
        }

        .footer {
            margin-top: 30px;
            text-align: right;
            font-style: italic;
            font-size: 10px;
            color: #777;
        }

        .signature-area {
            margin-top: 40px;
            display: flex;
            justify-content: flex-end;
        }

        .signature-box {
            text-align: center;
            width: 250px;
        }

        .signature-space {
            height: 60px;
        }

        .no-print {
            display: flex;
            justify-content: center;
            margin-bottom: 30px;
        }

        .btn-print {
            background-color: var(--bps-blue);
            color: white;
            border: none;
            padding: 12px 24px;
            font-size: 14px;
            font-weight: bold;
            border-radius: 50px;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }

        .btn-print:hover {
            background-color: #005696;
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.15);
        }

        @media print {
            .no-print, .btn-print {
                display: none !important;
            }
            body {
                padding: 0;
            }
            @page {
                size: landscape;
                margin: 1cm;
            }
            th {
                background-color: var(--bps-blue) !important;
                -webkit-print-color-adjust: exact;
            }
            .report-info {
                background-color: var(--bps-light-blue) !important;
                -webkit-print-color-adjust: exact;
            }
        }
    </style>
</head>

<body>
    <div class="no-print">
        <button onclick="window.print()" class="btn-print">
            <svg style="width:20px;height:20px;display:inline-block;vertical-align:middle;margin-right:8px" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Cetak / Simpan PDF
        </button>
    </div>

    <div class="header">
        <div class="header-text">
            <h1>Badan Pusat Statistik Kabupaten Demak</h1>
            <h2>Laporan Rekap Presensi Pegawai PST (Pelayanan Statistik Terpadu)</h2>
        </div>
    </div>

    <div class="report-info">
        <div>
            <span>Filter Pegawai:</span> {{ $selectedPegawai ? $selectedPegawai->nama_pegawai . ' (' . ($selectedPegawai->nip ?? '-') . ')' : 'Semua Petugas PST' }}
        </div>
        <div>
            <span>Periode:</span> 
            @if($bulan || $tahun)
                {{ $bulan ? 'Bulan ' . $bulan : '' }} {{ $tahun ? 'Tahun ' . $tahun : '' }}
            @else
                Keseluruhan Data
            @endif
        </div>
        <div>
            <span>Total Rekap:</span> {{ count($data) }} Presensi
        </div>
        <div>
            <span>Dicetak Pada:</span> {{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i') }} WIB
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 4%; text-align: center;">No</th>
                <th style="width: 25%;">Nama Pegawai</th>
                <th style="width: 18%;">NIP</th>
                <th style="width: 14%;">Tanggal</th>
                <th style="width: 13%;">Jam Masuk</th>
                <th style="width: 13%;">Jam Pulang</th>
                <th style="width: 13%; text-align: center;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $index => $row)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td><strong>{{ $row->pegawai?->nama_pegawai ?? '-' }}</strong></td>
                    <td>{{ $row->pegawai?->nip ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($row->tanggal)->translatedFormat('d M Y') }}</td>
                    <td>{{ $row->jam_masuk ?? '-' }}</td>
                    <td>{{ $row->jam_selesai ?? '-' }}</td>
                    <td style="text-align: center;">
                        <span class="badge-status">{{ $row->status ?? 'Hadir' }}</span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 20px;">Tidak ada data presensi yang ditemukan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="signature-area">
        <div class="signature-box">
            <div>Demak, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</div>
            <div>Penanggung Jawab PST</div>
            <div class="signature-space"></div>
            <div><strong>(.......................................................)</strong></div>
            <div>NIP. ...............................................</div>
        </div>
    </div>

    <div class="footer">
        Dicetak secara otomatis oleh SIPUTRI 2.0 - Sistem Informasi Pelayanan Umum Terpadu & Terintegrasi BPS Kabupaten Demak
    </div>
</body>

</html>

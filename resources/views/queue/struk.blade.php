<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Struk Antrian - BPS Demak</title>
    <style>
        @page {
            size: 80mm auto;
            margin: 0;
        }

        body {
            font-family: 'Courier New', Courier, monospace;
            width: 72mm;
            /* Safe width for 80mm paper */
            margin: 0 auto;
            padding: 10px;
            text-align: center;
            background: white;
            color: black;
        }

        .header {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 5px;
            text-transform: uppercase;
        }

        .sub-header {
            font-size: 10px;
            margin-bottom: 10px;
        }

        .divider {
            border-top: 1px dashed black;
            margin: 10px 0;
        }

        .layanan-title {
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 5px;
            text-transform: uppercase;
        }

        .nomor-antrian {
            font-size: 52px;
            font-weight: bold;
            margin: 15px 0;
            letter-spacing: -2px;
        }

        .info {
            font-size: 11px;
            display: flex;
            justify-content: space-between;
            margin: 5px 0;
            text-align: left;
        }

        .footer {
            font-size: 10px;
            margin-top: 15px;
            line-height: 1.4;
        }

        @media print {
            body {
                width: 100%;
                margin: 0;
                padding: 5mm;
            }
        }
    </style>
</head>

<body>
    <div class="header">
        <img src="{{ asset('bps.png') }}" style="height: 40px; margin-bottom: 5px; filter: grayscale(1);"><br>
        BADAN PUSAT STATISTIK<br>KABUPATEN DEMAK
    </div>
    <div class="sub-header">PELAYANAN STATISTIK TERPADU</div>

    <div class="divider"></div>

    <div class="layanan-title">{{ $antrian->layanan }}</div>

    @php
        $prefix = match (strtolower($antrian->jenis)) {
            'layanan' => 'L',
            'pengaduan' => 'P',
            'disabilitas' => 'D',
            default => 'A',
        };
        $label = $prefix . '-' . str_pad($antrian->nomor, 3, '0', STR_PAD_LEFT);
    @endphp

    <div class="nomor-antrian">{{ $label }}</div>

    <div class="divider"></div>

    <div class="info">
        <span>TANGGAL:</span>
        <span>{{ \Carbon\Carbon::parse($antrian->tanggal)->format('d/m/Y') }}</span>
    </div>
    <div class="info">
        <span>WAKTU:</span>
        <span>{{ \Carbon\Carbon::now()->format('H:i') }} WIB</span>
    </div>

    <div class="divider"></div>

    <div class="footer">
        Silakan menunggu hingga nomor Anda dipanggil oleh petugas loket.<br><br>
        <em>"Terima kasih atas kunjungan Anda"</em>
    </div>
</body>

</html>
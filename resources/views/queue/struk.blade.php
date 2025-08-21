<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk Antrian</title>
    <style>
        body {
            font-family: monospace;
            font-size: 14px;
            text-align: center;
            padding: 10px;
        }

        .antrian-number {
            font-size: 48px; /* PERBESAR di sini */
            font-weight: bold;
            margin: 10px 0;
        }

        hr {
            border: none;
            border-top: 1px dashed #000;
            margin: 10px 0;
        }

        @media print {
            body {
                width: 58mm;
                margin: 0 auto;
            }
        }
    </style>
</head>
<body onload="window.print()">
    <hr>
    <div>BADAN PUSAT STATISTIK</div>
    <div>Pelayanan Statistik Terpadu</div>
    <div>Kabupaten Demak</div>
    <hr>
    <div><strong>Layanan :</strong> {{ $antrian->layanan }}</div>
    <div class="antrian-number">A{{ str_pad($antrian->nomor, 3, '0', STR_PAD_LEFT) }}</div>
    <hr>
    <div><strong>Tanggal :</strong> {{ \Carbon\Carbon::parse($antrian->tanggal)->format('d-m-Y') }}</div>
    <div><strong>Waktu :</strong> {{ \Carbon\Carbon::now()->format('H:i') }} WIB</div>
    <hr>
    <div>Silakan menunggu hingga</div>
    <div>nomor Anda dipanggil oleh petugas</div>
    <hr>
    <div>Terima kasih atas kunjungan Anda</div>
    <hr>
</body>
</html>

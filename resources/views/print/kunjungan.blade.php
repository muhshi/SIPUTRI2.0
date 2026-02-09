<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Kunjungan</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
        }

        th {
            background-color: #f0f0f0;
        }

        h2 {
            text-align: center;
        }

        .no-print {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 20px;
        }

        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="no-print">
        <button onclick="window.print()" style="padding: 10px 20px; cursor: pointer;">Cetak / Simpan PDF</button>
    </div>

    <h2>Laporan Data Kunjungan</h2>
    <p>Tanggal Cetak: {{ date('d-m-Y H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Instansi</th>
                <th>Tanggal</th>
                <th>Layanan</th>
                <th>Keperluan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $key => $item)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $item->nama }}</td>
                    <td>{{ $item->instansi }}</td>
                    <td>{{ $item->tanggal }}</td>
                    <td>{{ $item->layanan }}</td>
                    <td>{{ $item->pemanfaatan }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Antrian Hari Ini</title>
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .back-button {
            position: absolute;
            top: 30px;
            left: 100px;
            display: inline-flex;
            align-items: center;
            padding: 8px 14px;
            background: transparent;
            border: 2px solid #0d6efd;
            color: #0d6efd;
            border-radius: 8px;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
        }
        .back-button:hover {
            background: #0d6efd;
            color: white;
        }
        body {
            background: #f5f7fa;
            font-family: 'Segoe UI', sans-serif;
        }

        .card-status {
            background: linear-gradient(135deg, #e0f0ff, #dceaff);
            border-left: 5px solid #0d6efd;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            text-align: center;
        }

        .card-status h4 {
            font-weight: 700;
            color: #444;
        }

        .card-status p {
            margin: 0;
            color: #777;
        }

        .total-box {
            background-color: white;
            border: 2px dashed #0d6efd;
            border-radius: 10px;
            padding: 25px;
            text-align: center;
            margin: auto;
            margin-bottom: 30px;
            width: 250px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        }

        .total-box h2 {
            color: #0d6efd;
            font-size: 36px;
            margin: 0;
        }

        .total-box small {
            color: #666;
        }

        table th, table td {
            text-align: center;
            vertical-align: middle;
        }

        .table-hover tbody tr:hover {
            background-color: #f1f1f1;
        }

        .btn-back {
            margin-bottom: 25px;
        }

        @media (max-width: 576px) {
            .total-box {
                width: 100%;
            }

            .card-status {
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="container my-5">
        <a href="/" class="btn btn-outline-primary btn-sm btn-back">← Kembali</a>
        <h2 class="text-center mb-4 fw-bold text-dark">📋 Daftar Antrian Hari Ini</h2>

        <!-- Box: Antrian yang Sedang Dipanggil -->
        <div class="card-status">
            <h5 class="text-primary">🔔 Sedang Berjalan:</h5>
            @if ($current)
                <h1 class="display-4">A{{ str_pad($current->nomor, 3, '0', STR_PAD_LEFT) }}</h1>
                <p>Layanan: {{ $current->layanan }}</p>
            @else
                <h4 class="mt-2 text-muted">Belum ada antrian</h4>
                <p>Silahkan ambil antrian terlebih dahulu.</p>
            @endif
        </div>

        <!-- Statistik Antrian Hari Ini -->
        <div class="total-box">
            <h5>Total Antrian</h5>
            <h3>{{ $total }}</h3>
        </div>

        @php
            $myAntrianId = session('antrian_id');
        @endphp

        <!-- Tabel Antrian -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover bg-white">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nomor Antrian</th>
                        <th>Layanan</th>
                        <th>Waktu Ambil</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($queues as $queue)
                        <tr @if($queue->id == $myAntrianId) style="background-color: #d4edda;" @endif>
                            <td>{{ $loop->iteration }}</td>
                            <td>A{{ str_pad($queue->nomor, 3, '0', STR_PAD_LEFT) }}</td>
                            <td>{{ $queue->layanan }}</td>
                            <td>{{ \Carbon\Carbon::parse($queue->created_at)->format('H:i') }} WIB</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">Belum ada antrian hari ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Bootstrap JS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

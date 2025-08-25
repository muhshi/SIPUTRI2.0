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
            margin-bottom: 20px;
        }
        .back-button:hover {
            background: #0d6efd;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <a href="/" class="back-button">← Kembali</a>
        <h2 class="text-center mb-4">Daftar Antrian Hari Ini</h2>

        <!-- Box: Antrian yang Sedang Dipanggil -->
        <div class="alert alert-primary text-center">
            <h4>🔔 Sedang Berjalan:</h4>
            @if ($current)
                <h1 class="display-4">A{{ str_pad($current->nomor, 3, '0', STR_PAD_LEFT) }}</h1>
                <p>Layanan: {{ $current->layanan }}</p>
            @else
                <h1 class="display-4 text-muted">Belum ada antrian</h1>
                <p>Silahkan ambil antrian terlebih dahulu.</p>
            @endif
        </div>

        <!-- Statistik Antrian Hari Ini -->
        <div class="row text-center mb-4">
            <div class="col-md-4 offset-md-4">
                <div class="card border-info">
                    <div class="card-body">
                        <h5>Total Antrian</h5>
                        <h3>{{ $total }}</h3>
                    </div>
                </div>
            </div>
        </div>

        @php
            $myAntrianId = session('antrian_id');
        @endphp

        <!-- Tabel Antrian -->
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
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
                            <td colspan="4" class="text-center">Belum ada antrian hari ini.</td>
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

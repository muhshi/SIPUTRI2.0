<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Antrian Hari Ini</title>
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <!-- Tombol Kembali -->
    <a href="/" class="btn btn-secondary m-3 position-absolute" style="top: 10px; left: 10px;">
        ← Kembali ke Halaman Utama
    </a>

    <div class="container py-4">
        <h2 class="text-center mb-4">Daftar Antrian Hari Ini</h2>

        <!-- Box: Antrian yang Sedang Dipanggil -->
        <div class="alert alert-primary text-center">
            <h4>🔔 Sedang Dipanggil:</h4>
            <!-- Ganti ini dengan data dinamis dari backend -->
            <h1 class="display-4">{{ $current->nomor }}</h1>
            <p>Layanan: {{ $current->layanan }}</p>
            <!-- Jika belum ada:
            <p>Belum ada yang dipanggil.</p>
            -->
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

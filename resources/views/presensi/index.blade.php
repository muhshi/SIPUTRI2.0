<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Presensi Pegawai</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<style>
    .back-button {
        position: absolute;
        top: 20px;
        left: 70px;
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

    table {
        border-radius: 12px;
        overflow: hidden;
    }
    .table thead th {
        background-color: #f1f3f6 !important;
        color: #333 !important;
        font-weight: 600;
        font-size: 14px;
        text-transform: uppercase;
    }
    .table tbody tr:nth-child(even) {
        background-color: #fcfcfc;
    }
    .table tbody tr {
        transition: background 0.2s ease-in-out;
    }
    .table tbody tr:hover {
        background-color: #f9fafb;
    }
    td {
        font-size: 15px;
    }
</style>

<div class="container mt-4">
    <a href="/" class="back-button">← Kembali</a>

    <h3 class="mb-3">Presensi Pegawai</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <!-- Form Presensi -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('presensi.form') }}" method="POST">
                @csrf
                <label for="pegawai_id" class="form-label">Pilih Pegawai</label>
                <select name="pegawai_id" id="pegawai_id" class="form-select mb-3">
                    <option value="" disabled selected>-- Pilih Nama Pegawai --</option>
                    @foreach($pegawais as $p)
                        <option value="{{ $p->id }}">{{ $p->nama }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-primary">Presensi</button>
            </form>
        </div>
    </div>

    <!-- Rekap Hari Ini -->
    <h5 class="mb-3">Rekap Hari Ini</h5>
    <div class="table-responsive">
        <table class="table table-bordered align-middle shadow-sm rounded">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Tanggal</th>
                    <th>Jam Masuk</th>
                    <th>Jam Selesai</th>
                </tr>
            </thead>
            <tbody>
                @forelse($presensis as $p)
                    <tr>
                        <td>{{ $p->pegawai->nama }}</td>
                        <td>{{ $p->tanggal }}</td>
                        <td>{{ $p->jam_masuk ?? '-' }}</td>
                        <td>{{ $p->jam_selesai ?? '-' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center">Belum ada data</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Presensi Pegawai PST</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container d-flex justify-content-center mt-5">
    <div class="card p-4 shadow-sm" style="min-width: 400px; max-width: 500px; width: 100%;">
        <h4 class="text-center mb-3">Presensi Pegawai PST</h4>

        <!-- Card 1 -->
        <div class="bg-light rounded p-3 mb-3 shadow-sm">
            <p class="mb-2 fw-bold text-center">Pelayanan Statistik Terpadu</p>
            <p class="mb-3 text-center">Kabupaten Demak</p>
            <p><strong>Nama Pegawai:</strong> {{ $pegawai->nama }}</p>
            <p><strong>Kantor:</strong> BPS Demak</p>
        </div>

        <!-- Card 2 & 3 -->
        <div class="d-flex justify-content-between gap-3 mb-3">
            <div class="bg-light p-3 rounded shadow-sm w-50 text-center">
                <div class="fw-bold">Jam Datang</div>
                <div>{{ $presensi->jam_masuk ?? '-' }}</div>
            </div>
            <div class="bg-light p-3 rounded shadow-sm w-50 text-center">
                <div class="fw-bold">Jam Pulang</div>
                <div>{{ $presensi->jam_selesai ?? '-' }}</div>
            </div>
        </div>

        <!-- Form -->
        <form action="{{ route('presensi.store') }}" method="POST">
            @csrf
            <input type="hidden" name="pegawai_id" value="{{ $pegawai->id }}">

            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-success">
                    @if(!$presensi)
                        Absen Masuk
                    @elseif($presensi && !$presensi->jam_selesai)
                        Absen Selesai
                    @else
                        Sudah Lengkap
                    @endif
                </button>
                <a href="{{ route('presensi.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

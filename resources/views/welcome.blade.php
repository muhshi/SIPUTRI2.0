<!DOCTYPE html>
<html>
<head>
    <title>Portal Layanan BPS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #fefefe;
            text-align: center;
            padding: 30px;
        }
        img {
            width: 250px;
            height: auto;
            margin-bottom: 10px;
        }
        footer {
            margin-top: 40px;
            color: #888;
        }
        .custom-alert {
            max-width: 600px;
            margin: 20px auto;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <!-- Notifikasi -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
            {{ session('success') }}

            <a href="{{ route('evaluasi.index') }}" class="btn btn-sm btn-primary ms-3">
                Masuk ke Halaman Evaluasi Pelayanan
            </a>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif


    <!-- Tombol Admin & Pegawai di kanan atas -->
    <div class="d-flex justify-content-end p-3 gap-2">
        <a href="{{ url('/admin') }}" class="btn btn-primary">Admin</a>
        <a href="{{ route('presensi.index') }}" class="btn btn-primary">Pegawai</a>
    </div>

    <img src="{{ asset('bpsdemak.png') }}" alt="Logo BPS">
    <h2>Selamat Datang di Portal Pelayanan Statistik Terpadu</h2>
    <h2>BPS Kabupaten Demak</h2>
    <p>Silakan pilih jenis layanan untuk Anda di bawah ini.</p>

    <!-- Modal untuk ambil antrian -->
    <div class="modal fade" id="ambilAntrianModal" tabindex="-1" aria-labelledby="ambilAntrianLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('queue.ambil') }}" method="POST">
                @csrf
                <div class="modal-header">
                <h5 class="modal-title" id="ambilAntrianLabel">Pilih Layanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
                <div class="modal-body">
                    <select class="form-select" name="layanan" required>
                        <option value="">-- Pilih Layanan --</option>
                        <option value="Permintaan Data">Permintaan Data</option>
                        <option value="Konsultasi Statistik">Konsultasi Statistik</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Ambil Nomor</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <div class="container mt-5">
        <!-- Card tunggal -->
        <div class="card mb-3 mx-auto" style="width: 52rem;">
            <div class="card-body">
                <h5 class="card-title"><strong>LAYANAN ANTRIAN PENGUNJUNG</strong></h5>
                <p class="card-text">Masuk untuk mengambil nomor antrian.</p>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ambilAntrianModal">Ambil Antrian</button>
                <a href="{{ route('queue.lihat') }}" class="btn btn-primary">Lihat Antrian</a>
            </div>
        </div>

        <!-- Dua card bersebelahan -->
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-4">
                    <div class="card text-center mb-3">
                        <div class="card-body">
                        <h5 class="card-title"><strong>BUKU TAMU</strong></h5>
                        <p class="card-text">Silahkan Anda Masuk untuk mengisi form pengunjung.</p>
                        <a href="{{ route('pengunjung.form') }}" class="btn btn-primary">Masuk</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-center mb-3">
                        <div class="card-body">
                        <h5 class="card-title"><strong>EVALUASI PELAYANAN</strong></h5>
                        <p class="card-text">Informasi Pegawai Pelayanan dan Kepuasan Pelayanan.</p>
                        <a href="{{ route('evaluasi.index') }}" class="btn btn-primary">Masuk</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer>© 2025 Badan Pusat Statistik</footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

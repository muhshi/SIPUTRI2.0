<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Form Pencatatan Pengunjung PST</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background-color: #f8f9fa;">
    <div class="container my-5">
        <div class="card shadow">
            <div class="card-header bg-success text-white">
                <h4 class="mb-0">Form Pencatatan Pengunjung PST</h4>
                <small>Form untuk mencatat identitas pengunjung PST dan kepuasan pengunjung</small>
            </div>
            <div class="card-body">
                @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
                @endif
                <form action="{{ route('pengunjung.submit') }}" method="POST">
                    @csrf
                    <div class="row">
                        <!-- Nama -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama</label>
                            <input type="text" name="nama" class="form-control" required>
                        </div>
                        <!-- Pendidikan Terakhir -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Pendidikan Terakhir</label>
                            <select name="pendidikan" class="form-select" required>
                                <option value="">Pilih</option>
                                <option>SD</option>
                                <option>SMP</option>
                                <option>SMA</option>
                                <option>D3</option>
                                <option>S1</option>
                                <option>S2</option>
                            </select>
                        </div>
                        <!-- Tanggal -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal</label>
                            <input type="date" name="tanggal" class="form-control" required>
                        </div>
                        <!-- Pekerjaan -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Pekerjaan</label>
                            <select name="pekerjaan" class="form-select" required>
                                <option value="">Pilih</option>
                                <option>Pelajar</option>
                                <option>Mahasiswa</option>
                                <option>Pegawai</option>
                                <option>Lainnya</option>
                            </select>
                        </div>
                        <!-- Jenis Kelamin -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label d-block">Jenis Kelamin</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="jenis_kelamin" value="Laki-laki" required>
                                <label class="form-check-label">Laki-laki</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="jenis_kelamin" value="Perempuan">
                                <label class="form-check-label">Perempuan</label>
                            </div>
                        </div>
                        <!-- Nama Instansi -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Instansi</label>
                            <input type="text" name="instansi" class="form-control">
                        </div>
                        <!-- Email -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control">
                        </div>
                        <!-- Pemanfaatan -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Pemanfaatan Hasil Kunjungan</label>
                            <select name="pemanfaatan" class="form-select">
                                <option>Pendidikan</option>
                                <option>Pekerjaan</option>
                                <option>Penelitian</option>
                                <option>Lainnya</option>
                            </select>
                        </div>
                        <!-- Tahun Lahir -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tahun Lahir</label>
                            <input type="number" name="tahun_lahir" class="form-control">
                        </div>
                        <!-- Layanan -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Layanan yang Diterima</label>
                            <select name="layanan" class="form-select">
                                <option>Data Publik</option>
                                <option>Konsultasi</option>
                                <option>Pelayanan Statistik Terpadu</option>
                                <option>Lainnya</option>
                            </select>
                        </div>
                        <!-- Umur -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Umur</label>
                            <input type="number" name="umur" class="form-control">
                        </div>
                        <!-- Data yang Diinginkan -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Data yang Diinginkan</label>
                            <input type="text" name="data_diinginkan" class="form-control">
                        </div>
                        <!-- Alamat -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Alamat</label>
                            <textarea name="alamat" class="form-control" rows="2"></textarea>
                        </div>
                    <!-- Tombol -->
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-success me-2">Submit</button>
                        <a href="{{ url('/') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle (JS + Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

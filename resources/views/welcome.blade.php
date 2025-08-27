<!DOCTYPE html>
<html>
<head>
    <title>Portal Layanan BPS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .custom-navbar {
            background: #d1d7f9;
            border-radius: 50px;
            padding: 6px 24px;
            max-width: 1100px;
            margin: 30px auto;
            color: black;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        }

        .custom-navbar .nav-link,
        .custom-navbar .navbar-brand,
        .custom-navbar .text-muted {
        color: black !important;
        }

        .custom-navbar .btn-danger {
            background-color: #e53935;
            border: none;
        }
        body {
            font-family: Arial, sans-serif;
            background-image: 
                linear-gradient(to bottom, rgba(0, 32, 96, 0.85), rgba(255, 204, 51, 0.85)),
                url('{{ asset('gedungbps.jpg') }}');
            background-size: 
                100% 50%, /* overlay gradien di gambar setengah atas */
                100% 50%, /* gambar gedung setengah atas */
            background-position:
                top left,
                top left,
            background-repeat: no-repeat;
            background-attachment: scroll;
            text-align: center;
            padding: 30px;
            min-height: 100vh;
        }

        .welcome-text {
            color: #fff;       /* putih */
            text-align: center;
            font-weight: bold;
            text-shadow: 2px 2px 5px rgba(0,0,0,0.6); /* biar tetap terbaca */
        }

        .welcome-title {
            color: white;          /* teks jadi putih */
            font-size: 2rem;       /* ukuran besar */
            font-weight: 700;      /* tebal */
            line-height: 1.4;
            margin: 0;      /* hilangkan margin default h1 */
            text-shadow: 2px 2px 5px rgba(0,0,0,0.6);       
        }

        /* Bagian card putih */
        .card-container {
            margin-top: 100px;   /* jarak antara judul & card */
            margin-bottom: 100px;
            max-width: 110%;
        }
        .service-card {
    background: #fff;
    border-radius: 20px;
    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease-in-out;
    cursor: pointer;
    height: 100%;
    position: relative;
    overflow: hidden;
}

.service-card::before {
    content: "";
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle at center, rgba(0,123,255,0.08), transparent 70%);
    transform: scale(0);
    transition: transform 0.5s ease;
    border-radius: 50%;
    z-index: 0;
}

.service-card:hover::before {
    transform: scale(1);
}

.service-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 12px 28px rgba(0,0,0,0.15);
}

.service-card .icon {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: #f5f7ff;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 15px;
    transition: 0.3s ease;
    z-index: 1;
    position: relative;
}

.service-card:hover .icon {
    transform: scale(1.1) rotate(5deg);
    background: #e8edff;
}

.service-card h5 {
    font-size: 1.1rem;
    margin-bottom: 12px;
    color: #222;
    z-index: 1;
    position: relative;
}

.service-card p {
    font-size: 0.9rem;
    color: #6c757d;
    min-height: 60px;
    z-index: 1;
    position: relative;
}

.service-card .btn {
    border-radius: 25px;
    padding: 6px 18px;
    font-size: 0.85rem;
    z-index: 1;
    position: relative;
}

/* Animasi masuk saat load */
@keyframes fadeUp {
    from {opacity: 0; transform: translateY(30px);}
    to {opacity: 1; transform: translateY(0);}
}
.service-card {
    animation: fadeUp 0.8s ease forwards;
}
.service-card:nth-child(1) { animation-delay: 0.2s; }
.service-card:nth-child(2) { animation-delay: 0.4s; }
.service-card:nth-child(3) { animation-delay: 0.6s; }

        .footer-custom {
            background-color: #f8f9fa; /* atau warna lain seperti #eeeeee */
        }

        img {
            width: 250px;
            height: auto;
            margin-bottom: 10px;
        }
        .custom-alert {
            max-width: 600px;
            margin: 20px auto;
            border-radius: 8px;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg shadow-sm custom-navbar">
    <div class="container-fluid d-flex justify-content-between align-items-center">

        <!-- Kiri: Logo dan Teks -->
        <a class="navbar-brand d-flex align-items-center" href="#">
            <img src="{{ asset('bps.png') }}" alt="Logo" height="36" class="me-3">
            
            <!-- Pemisah garis vertikal -->
            <div style="border-left: 2px solid #ccc; height: 40px;" class="me-3"></div>

            <div class="d-flex flex-column lh-sm">
                <strong style="font-size: 18px;">Pelayanan Statistik Terpadu</strong>
                <small class="text-muted" style="font-size: 11px;">BPS Kabupaten Demak</small>
            </div>
        </a>

        <!-- Menu Tengah -->
        <div class="collapse navbar-collapse justify-content-center" id="navbarContent">
            <ul class="navbar-nav mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="#">Tentang</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Layanan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Kontak</a>
                </li>
            </ul>
        </div>

        <!-- Kanan: Tombol Admin & Pegawai -->
        <div class="d-flex gap-2">
            <a class="nav-link" href="{{ url('/admin') }}">Admin</a>
            <a class="nav-link" href="{{ route('presensi.index') }}">Pegawai</a>
        </div>
    </div>
</nav>
<div class="hero-section text-center">
    <h1 class="welcome-title">
        Selamat Datang di Portal Pelayanan Statistik Terpadu <br>
        <span>BPS Kabupaten Demak</span>
    </h1>
</div>

<div class="container card-container">
    <div class="card shadow-lg p-4">
        <div class="card-body">
            <div class="container my-5">
    <h2 class="text-center mb-4 fw-bold">Layanan Kami</h2>

    <div class="row g-4">
        <!-- Card 1: Antrian -->
        <div class="col-md-4">
            <div class="service-card text-center p-4">
                <div class="icon mb-3">
                    <img src="https://cdn-icons-png.flaticon.com/512/2917/2917994.png" alt="Ikon Antrian" style="width:50px; height:50px;">
                    <i class="bi bi-people fs-1 text-primary"></i>
                </div>
                <h5 class="fw-bold">Layanan Antrian</h5>
                <p class="text-muted">Ambil nomor antrian dan pantau status antrian.</p>
                <div class="d-flex justify-content-center gap-2">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ambilAntrianModal">Ambil Antrian</button>
                    <a href="{{ route('queue.lihat') }}" class="btn btn-outline-primary btn-sm">Lihat Antrian</a>
                </div>
            </div>
        </div>

        <!-- Card 2: Buku Tamu -->
        <div class="col-md-4">
            <div class="service-card text-center p-4">
                <div class="icon mb-3">
                    <img src="https://cdn-icons-png.flaticon.com/512/709/709722.png" alt="Ikon Buku Tamu" style="width:50px; height:50px;">
                    <i class="bi bi-journal-text fs-1 text-success"></i>
                </div>
                <h5 class="fw-bold">Layanan Buku Tamu</h5>
                <p class="text-muted">Catat kunjungan Anda dengan mudah melalui buku tamu.</p>
                <a href="{{ route('pengunjung.form') }}" class="btn btn-success btn-sm">Masuk</a>
            </div>
        </div>

        <!-- Card 3: Evaluasi Pegawai -->
        <div class="col-md-4">
            <div class="service-card text-center p-4">
                <div class="icon mb-3">
                    <img src="https://cdn-icons-png.flaticon.com/512/1250/1250685.png" alt="Ikon Evaluasi" style="width:50px; height:50px;">
                    <i class="bi bi-bar-chart-line fs-1 text-warning"></i>
                </div>
                <h5 class="fw-bold">Evaluasi Pegawai</h5>
                <p class="text-muted">Beri masukan Anda terhadap pelayanan pegawai kami.</p>
                <a href="{{ route('evaluasi.index') }}" class="btn btn-warning btn-sm">Isi Evaluasi</a>
            </div>
        </div>
    </div>
</div>

    <!-- Notifikasi -->
    @if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                html: `{!! session('success') !!}<br><br>
                    <a href="{{ route('evaluasi.index') }}" class="btn btn-sm btn-primary">Masuk ke Halaman Evaluasi</a>`,
                showConfirmButton: false,
                timer: 6000,
                width: '28em',  // GANTI UKURAN DI SINI
                customClass: {
                    popup: 'swal2-rounded'  // opsional untuk tampilan lebih modern
                }
            });
        });
    </script>
    @endif

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
    
<footer class="footer-custom mt-5 py-5">
    <div class="container">
        <div class="row text-start">
            <div class="col-md-4 mb-3">
                <img src="{{ asset('bps.png') }}" alt="Logo BPS" width="120" class="mb-2">
                <p class="text-muted small">
                    Jl. Sultan Hadiwijaya No.23, Krajan, Mangunjiwan, Kec. Demak, Kabupaten Demak, Jawa Tengah 59515<br>
                    Telp. (0291) 685445, Fax (0291) 681754<br>
                    Email : bps3321@bps.go.id
                </p>
            </div>
            <div class="col-md-2 mb-3">
                <h6 class="fw-bold">Tentang BPS</h6>
                <ul class="list-unstyled small">
                    <li><a href="#" class="text-muted">Informasi Umum</a></li>
                    <li><a href="#" class="text-muted">Visi & Misi</a></li>
                    <li><a href="#" class="text-muted">Tugas & Fungsi</a></li>
                </ul>
            </div>
            <div class="col-md-3 mb-3">
                <h6 class="fw-bold">Tentang PST</h6>
                <ul class="list-unstyled small">
                    <li><a href="#" class="text-muted">Pelayanan Statistik Terpadu</a></li>
                    <li><a href="#" class="text-muted">Standar Pelayanan</a></li>
                    <li><a href="#" class="text-muted">Maklumat Pelayanan</a></li>
                    <li><a href="#" class="text-muted">Kompensasi Pelayanan</a></li>
                </ul>
            </div>
            <div class="col-md-3 mb-3">
                <h6 class="fw-bold">Bantuan</h6>
                <ul class="list-unstyled small">
                    <li><a href="#" class="text-muted">Survei Kepuasan</a></li>
                    <li><a href="#" class="text-muted">Pengaduan</a></li>
                    <li><a href="#" class="text-muted">Informasi Pelayanan</a></li>
                </ul>
            </div>
        </div>
        <div class="text-center text-muted small mt-4">
            Semua Hak Dilindungi © 2025, BPS Kabupaten Demak.
        </div>
    </div>
</footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>
</html>

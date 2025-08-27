<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Evaluasi Pelayanan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .back-button:hover {
            background: #0d6efd;
            color: white;
        }

        .greeting-box {
            background-color: #d6f0ff;
            padding: 15px 20px;
            border-radius: 10px;
            text-align: center;
            margin-bottom: 30px;
            font-size: 18px;
            font-weight: 500;
        }
        /* Grid Card */
        .card-container {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 16px;
        }
        .pegawai-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            padding: 15px;
        }
        .pegawai-card img {
            width: 100%;
            height: 120px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 10px;
        }
        .pegawai-card h5 {
            font-size: 14px;
            margin: 6px 0;
        }
        .pegawai-card p {
            font-size: 12px;
            color: gray;
        }
        .pegawai-card .stars {
            margin-bottom: 8px;
        }
        .pegawai-card .star {
            font-size: 18px;
            color: gray;
            cursor: pointer;
        }
        .pegawai-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        /* Saat dipilih */
        .pegawai-card.active {
            transform: scale(1.05);
            border: 2px solid #0d6efd;
            z-index: 10;
        }
        .card-container.dim .pegawai-card:not(.active) {
            filter: blur(2px) brightness(0.8);
            pointer-events: none;
        }

        /* Responsif */
        @media (max-width: 1200px) {
            .card-container { grid-template-columns: repeat(3, 1fr); }
        }
        @media (max-width: 768px) {
            .card-container { grid-template-columns: repeat(2, 1fr); }
        }
    </style>
</head>
<body>

<div class="container mt-4 mb-5">
    <a href="/" class="btn btn-outline-primary btn-sm mb-4">← Kembali</a>

    <h2 class="text-center mb-3 fw-bold">Evaluasi Pelayanan</h2>

    <div class="greeting-box">
        Halo, <strong>{{ $nama }}</strong> 👋
    </div>

    <form action="{{ route('evaluasi.store') }}" method="POST" id="evaluasiForm">
        @csrf
        <input type="hidden" name="pegawai_id" id="pegawai_id">
        <input type="hidden" name="rating" id="rating">

        <div class="card-container" id="cards">
            @foreach ($pegawai as $pgw)
            <div class="pegawai-card" data-id="{{ $pgw['id'] }}">
                <img src="{{ asset('images/' . $pgw['gambar']) }}" alt="Foto {{ $pgw['nama'] }}">
                <h5>{{ $pgw['nama'] }}</h5>
                <p>{{ $pgw['jabatan'] }}</p>
                <div class="stars">
                    @for ($i = 1; $i <= 5; $i++)
                        <span class="star" data-value="{{ $i }}">★</span>
                    @endfor
                </div>
            </div>
            @endforeach
        </div>

        <div class="text-center mt-4">
            <button
                type="submit"
                class="btn btn-primary btn-lg"
                @if(!empty($sudahEvaluasi) && $sudahEvaluasi) disabled @endif
            >
                @if(!empty($sudahEvaluasi) && $sudahEvaluasi)
                    Penilaian sudah terkirim
                @else
                    Kirim Penilaian
                @endif
            </button>
      </div>

    </form>
</div>

<script>
    const cards = document.querySelectorAll('.pegawai-card');
    const container = document.getElementById('cards');
    const inputPegawai = document.getElementById('pegawai_id');
    const inputRating = document.getElementById('rating');

    cards.forEach(card => {
        // Klik card untuk pilih pegawai
        card.addEventListener('click', () => {
            cards.forEach(c => c.classList.remove('active'));
            card.classList.add('active');
            container.classList.add('dim');
            inputPegawai.value = card.dataset.id;
        });

        // Klik bintang rating
        card.querySelectorAll('.star').forEach(star => {
            star.addEventListener('click', (e) => {
                e.stopPropagation(); // biar gak double trigger card
                const rating = star.dataset.value;
                inputRating.value = rating;
                card.querySelectorAll('.star').forEach((s, i) => {
                    s.style.color = i < rating ? 'gold' : 'gray';
                });
            });
        });
    });

        // Klik di luar card -> batal pilih
    document.addEventListener('click', function (e) {
        // cek apakah klik bukan di dalam card dan bukan di dalam tombol submit
        if (!e.target.closest('.pegawai-card') && !e.target.closest('button[type="submit"]')) {
            // hapus semua active
            document.querySelectorAll('.pegawai-card').forEach(c => c.classList.remove('active'));
            // hapus efek dim
            document.getElementById('cards').classList.remove('dim');
            // kosongkan hidden input
            document.getElementById('pegawai_id').value = '';
            document.getElementById('rating').value = '';

            // kembalikan semua bintang ke abu-abu
            document.querySelectorAll('.pegawai-card .star').forEach(s => {
            s.style.color = 'gray';
            });
        }
    });
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Flag dari server: sudah evaluasi atau belum
    const sudahEvaluasi = {{ isset($sudahEvaluasi) && $sudahEvaluasi ? 'true' : 'false' }};

    // Jika sudah evaluasi: kunci semua interaksi kartu + ubah tampilan tombol
    if (sudahEvaluasi) {
        document.querySelectorAll('.pegawai-card').forEach(card => {
            card.style.pointerEvents = 'none'; // tidak bisa diklik
            card.style.opacity = '0.6';        // indikator visual dikunci (opsional)
        });

        const btn = document.querySelector("button[type='submit']");
        if (btn) {
            btn.classList.remove('btn-primary');
            btn.classList.add('btn-secondary'); // tampilkan gaya non-aktif
        }
    }

    // Validasi ringan di sisi client sebelum submit
    const form = document.getElementById('evaluasiForm');
    if (form) {
        form.addEventListener('submit', function (e) {
            // Jika sudah evaluasi, cegah submit (pengaman tambahan di client)
            if (sudahEvaluasi) {
                e.preventDefault();
                return;
            }

            // Pastikan user sudah memilih pegawai & rating
            const pegawaiId = document.getElementById('pegawai_id').value;
            const rating = document.getElementById('rating').value;

            if (!pegawaiId || !rating) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Oops!',
                    text: 'Silakan pilih pegawai dan beri rating terlebih dahulu.',
                    confirmButtonText: 'OK'
            });
        });
    }
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    @if (session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session("success") }}',
            timer: 3500,
            timerProgressBar: true,
            showConfirmButton: false,
            position: 'top-end',
            toast: true
        });
    @endif

    @if(!session('success') && !empty($sudahEvaluasi) && $sudahEvaluasi)
        Swal.fire({
            icon: 'info',
            title: 'Perhatian',
            text: 'Anda sudah memberikan penilaian. Terimakasih🙏',
            confirmButtonText: 'OK'
        });
    @endif
});
</script>
</body>
</html>

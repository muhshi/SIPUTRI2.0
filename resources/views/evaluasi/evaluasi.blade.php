<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Evaluasi Pelayanan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Tombol kembali */
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
<body class="bg-light">

<div class="container mt-4 mb-5">
    <a href="/" class="back-button">← Kembali</a>

    <h2 class="text-center mb-4">Evaluasi Pelayanan</h2>

    <div class="alert alert-info text-center">
        Halo, <strong>{{ $nama }}</strong> 👋
    </div>

    @if (session('success'))
        <div class="alert alert-success text-center">
            {{ session('success') }}
        </div>
    @endif

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
            <button type="submit" class="btn btn-primary btn-lg">Kirim Penilaian</button>
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
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

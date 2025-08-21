<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Evaluasi Pelayanan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <a href="/" class="btn btn-secondary position-absolute m-3">← Kembali ke Halaman Utama</a>

    <div class="container mt-5 mb-5">
        <h2 class="text-center mb-4">Evaluasi Pelayanan</h2>

        <div class="alert alert-info text-center">
            Halo, <strong>{{ $nama }}</strong> 👋
        </div>

        @if (session('success'))
            <div class="alert alert-success text-center">
                {{ session('success') }}
            </div>
        @endif

        <div class="row g-4">
            @foreach ($pegawai as $index => $pgw)
            <div class="col-md-3">
                <div class="card h-100 shadow-sm">
                    <img src="{{ asset('images/' . $pgw['gambar']) }}" 
                         class="card-img-top" 
                         alt="Foto {{ $pgw['nama'] }}" 
                         height="180">

                    <div class="card-body text-center">
                        <h5 class="card-title">{{ $pgw['nama'] }}</h5>
                        <p class="card-text">{{ $pgw['jabatan'] }}</p>

                        <form action="{{ route('evaluasi.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="pegawai_id" value="{{ $index }}">
                            <input type="hidden" name="rating" id="rating-{{ $index }}" value="0">

                            <div class="mb-2">
                                @for ($i = 1; $i <= 5; $i++)
                                    <span class="star" 
                                          data-value="{{ $i }}" 
                                          data-target="rating-{{ $index }}" 
                                          style="cursor:pointer;font-size:20px;color:gray;">
                                        ★
                                    </span>
                                @endfor
                            </div>

                            <button type="submit" class="btn btn-sm btn-primary">Nilai</button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <script>
        document.querySelectorAll('.star').forEach(star => {
            star.addEventListener('click', function () {
                const rating = parseInt(this.dataset.value);
                const target = document.getElementById(this.dataset.target);
                target.value = rating;

                const stars = this.parentNode.querySelectorAll('.star');
                stars.forEach((s, i) => {
                    s.style.color = i < rating ? 'gold' : 'gray';
                });
            });

            star.addEventListener('mouseover', function () {
                const rating = parseInt(this.dataset.value);
                const stars = this.parentNode.querySelectorAll('.star');
                stars.forEach((s, i) => {
                    s.style.color = i < rating ? 'gold' : 'gray';
                });
            });

            star.addEventListener('mouseout', function () {
                const target = document.getElementById(this.dataset.target);
                const rating = parseInt(target.value);
                const stars = this.parentNode.querySelectorAll('.star');
                stars.forEach((s, i) => {
                    s.style.color = i < rating ? 'gold' : 'gray';
                });
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

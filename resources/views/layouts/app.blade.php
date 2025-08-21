<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Portal Antrian</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Bootstrap CDN --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    {{-- Navbar opsional --}}
    <nav class="navbar navbar-light bg-light mb-4">
        <div class="container">
            <a class="navbar-brand" href="/">BPS Kab. Demak</a>
        </div>
    </nav>

    {{-- Konten halaman --}}
    @yield('content')

    {{-- Footer opsional --}}
    <footer class="text-center mt-4 mb-2 text-muted">
        &copy; {{ date('Y') }} Badan Pusat Statistik
    </footer>

</body>
</html>

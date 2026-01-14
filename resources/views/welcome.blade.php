<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Layanan BPS Kabupaten Demak</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        bps: {
                            blue: '#0054a6',
                            dark: '#002060',
                            orange: '#f7941d',
                        }
                    }
                }
            }
        }
    </script>

    <!-- SweetAlert2 -->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        .hero-pattern {
            background-color: #f3f4f6;
            background-image: radial-gradient(#e5e7eb 1px, transparent 1px);
            background-size: 24px 24px;
        }
    </style>
</head>

<body class="bg-gray-50 font-sans text-gray-900 antialiased selection:bg-bps-blue selection:text-white">

    <!-- Navbar -->
    <nav
        class="fixed w-full z-20 top-0 start-0 bg-white/90 backdrop-blur-md border-b border-gray-100 transition-all duration-300">
        <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
            <a href="#" class="flex items-center space-x-3 rtl:space-x-reverse">
                <img src="{{ asset('bps.png') }}" class="h-10 w-auto" alt="BPS Logo">
                <div class="flex flex-col">
                    <span class="self-center text-lg font-bold whitespace-nowrap text-bps-dark leading-tight">BPS Kab.
                        Demak</span>
                    <span class="text-xs text-gray-500 font-medium tracking-wider">PELAYANAN STATISTIK TERPADU</span>
                </div>
            </a>
            <div class="flex md:order-2 space-x-3 md:space-x-4 rtl:space-x-reverse">
                <a href="{{ route('presensi.index') }}"
                    class="text-gray-600 hover:text-bps-blue font-medium text-sm px-4 py-2 transition-colors">Pegawai</a>
                <a href="{{ url('/admin') }}"
                    class="text-white bg-bps-blue hover:bg-bps-dark focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-full text-sm px-5 py-2.5 text-center transition-all shadow-md hover:shadow-lg">Admin</a>
            </div>
            <!-- Mobile menu button could go here -->
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="min-h-[80vh] flex items-center justify-center pt-24 hero-pattern relative overflow-hidden">
        <div class="absolute inset-0 z-0 opacity-10 pointer-events-none">
            <!-- Abstract shapes or logo opacity -->
            <img src="{{ asset('bps.png') }}"
                class="absolute -right-20 -top-20 w-96 h-96 opacity-10 blur-3xl rounded-full" alt="Bg">
            <div
                class="absolute left-1/4 bottom-10 w-64 h-64 bg-bps-orange rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob">
            </div>
            <div
                class="absolute right-1/4 top-10 w-64 h-64 bg-bps-blue rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000">
            </div>
        </div>

        <div class="py-8 px-4 mx-auto max-w-screen-xl text-center lg:py-16 z-10 relative">
            <h1 class="mb-4 text-4xl font-extrabold tracking-tight leading-none text-gray-900 md:text-5xl lg:text-6xl">
                Selamat Datang di <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-bps-blue to-bps-dark">Pelayanan
                    Statistik Terpadu</span>
            </h1>
            <p class="mb-8 text-lg font-normal text-gray-600 lg:text-xl sm:px-16 lg:px-48">
                Badan Pusat Statistik Kabupaten Demak siap melayani kebutuhan data dan konsultasi statistik Anda dengan
                profesional, integritas, dan amanah.
            </p>
            <div class="flex flex-col space-y-4 sm:flex-row sm:justify-center sm:space-y-0 sm:space-x-4">
                <a href="#layanan"
                    class="inline-flex justify-center items-center py-3 px-6 text-base font-medium text-white rounded-full bg-bps-blue hover:bg-bps-dark focus:ring-4 focus:ring-blue-300 transition-all shadow-lg hover:shadow-xl hover:-translate-y-1">
                    Mulai Layanan
                    <svg class="w-3.5 h-3.5 ms-2 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 14 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M1 5h12m0 0L9 1m4 4L9 9" />
                    </svg>
                </a>
                <a href="{{ route('queue.lihat') }}"
                    class="inline-flex justify-center items-center py-3 px-6 text-base font-medium text-gray-900 focus:outline-none bg-white rounded-full border border-gray-200 hover:bg-gray-100 hover:text-bps-blue focus:z-10 focus:ring-4 focus:ring-gray-100 transition-all shadow-sm hover:shadow-md">
                    Pantau Antrian
                </a>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="layanan" class="py-16 bg-white relative">
        <div class="py-8 px-4 mx-auto max-w-screen-xl lg:py-16">
            <div class="mx-auto max-w-screen-sm text-center mb-12">
                <h2 class="mb-4 text-3xl tracking-tight font-extrabold text-gray-900">Layanan Unggulan</h2>
                <p class="font-light text-gray-500 lg:mb-16 sm:text-xl">Kami menyediakan berbagai kemudahan akses
                    layanan untuk Anda.</p>
            </div>
            <div class="grid gap-8 mb-6 lg:mb-16 md:grid-cols-3">

                <!-- Card 1: Antrian -->
                <div
                    class="group bg-white rounded-3xl p-8 border border-gray-100 shadow-xl shadow-gray-200/50 hover:shadow-2xl hover:shadow-bps-blue/10 transition-all duration-300 hover:-translate-y-2 cursor-pointer flex flex-col items-center text-center">
                    <div
                        class="flex justify-center items-center w-16 h-16 rounded-2xl bg-blue-50 text-bps-blue mb-6 group-hover:bg-bps-blue group-hover:text-white transition-colors duration-300">
                        <svg class="w-8 h-8" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                            viewBox="0 0 20 20">
                            <path
                                d="M7 9a4.5 4.5 0 1 0 0-9 4.5 4.5 0 0 0 0 9Zm2 1H5a5.002 5.002 0 0 0-4.992 4.992A.996.996 0 0 0 1 16h12a.996.996 0 0 0 .992-1.008A5.002 5.002 0 0 0 9 10Zm-2 4.457c.908 0 1.641.734 1.641 1.642 0 .615-.323 1.157-.81 1.447l.808 1.401a.995.995 0 0 1-1.724.996l-.809-1.401a2.986 2.986 0 0 1-2.906-1.447L2.4 15.698A.996.996 0 0 1 3.2 14h.015l1.385.795A1.643 1.643 0 0 1 7 14.457Z" />
                            <path
                                d="M13 0a1 1 0 0 1 1 1v6.586l2.293-2.293a1 1 0 1 1 1.414 1.414l-4 4a1 1 0 0 1-1.414 0l-4-4a1 1 0 1 1 1.414-1.414L12 7.586V1a1 1 0 0 1 1-1Z" />
                        </svg>
                    </div>
                    <h3 class="mb-3 text-xl font-bold text-gray-900">Ambil Antrian</h3>
                    <p class="text-gray-500 mb-6 flex-grow">Dapatkan nomor antrian layanan statistik tanpa perlu
                        menunggu lama.</p>
                    <button onclick="openModal()"
                        class="w-full text-white bg-bps-blue hover:bg-bps-dark focus:ring-4 focus:ring-blue-300 font-medium rounded-xl text-sm px-5 py-3 transition-colors">
                        Ambil Nomor
                    </button>
                    <!-- Small Link -->
                    <a href="{{ route('queue.lihat') }}"
                        class="mt-4 text-sm text-bps-blue hover:text-bps-dark font-medium underline decoration-1 underline-offset-4">Lihat
                        Antrian Saat Ini</a>
                </div>

                <!-- Card 2: Buku Tamu -->
                <div
                    class="group bg-white rounded-3xl p-8 border border-gray-100 shadow-xl shadow-gray-200/50 hover:shadow-2xl hover:shadow-green-500/10 transition-all duration-300 hover:-translate-y-2 cursor-pointer flex flex-col items-center text-center">
                    <div
                        class="flex justify-center items-center w-16 h-16 rounded-2xl bg-green-50 text-green-600 mb-6 group-hover:bg-green-600 group-hover:text-white transition-colors duration-300">
                        <svg class="w-8 h-8" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <h3 class="mb-3 text-xl font-bold text-gray-900">Buku Tamu</h3>
                    <p class="text-gray-500 mb-6 flex-grow">Catat kunjungan Anda secara digital untuk pelayanan yang
                        lebih baik.</p>
                    <a href="{{ route('pengunjung.form') }}"
                        class="w-full text-white bg-green-600 hover:bg-green-700 focus:ring-4 focus:ring-green-300 font-medium rounded-xl text-sm px-5 py-3 transition-colors block">
                        Isi Buku Tamu
                    </a>
                </div>

                <!-- Card 3: Evaluasi -->
                <div
                    class="group bg-white rounded-3xl p-8 border border-gray-100 shadow-xl shadow-gray-200/50 hover:shadow-2xl hover:shadow-bps-orange/10 transition-all duration-300 hover:-translate-y-2 cursor-pointer flex flex-col items-center text-center">
                    <div
                        class="flex justify-center items-center w-16 h-16 rounded-2xl bg-orange-50 text-bps-orange mb-6 group-hover:bg-bps-orange group-hover:text-white transition-colors duration-300">
                        <svg class="w-8 h-8" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                        </svg>
                    </div>
                    <h3 class="mb-3 text-xl font-bold text-gray-900">Evaluasi Pelayanan</h3>
                    <p class="text-gray-500 mb-6 flex-grow">Berikan penilaian Anda untuk membantu kami meningkatkan
                        kualitas layanan.</p>
                    <a href="{{ route('evaluasi.index') }}"
                        class="w-full text-white bg-bps-orange hover:bg-orange-600 focus:ring-4 focus:ring-orange-300 font-medium rounded-xl text-sm px-5 py-3 transition-colors block">
                        Isi Evaluasi
                    </a>
                </div>

            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200">
        <div class="mx-auto w-full max-w-screen-xl p-4 py-6 lg:py-8">
            <div class="md:flex md:justify-between">
                <div class="mb-6 md:mb-0 max-w-sm">
                    <a href="https://demakkab.bps.go.id" class="flex items-center space-x-3 rtl:space-x-reverse mb-4">
                        <img src="{{ asset('bps.png') }}" class="h-12 me-3" alt="BPS Logo" />
                        <div>
                            <span class="self-center text-xl font-bold whitespace-nowrap text-bps-dark">BPS Kab.
                                Demak</span>
                            <p class="text-xs text-gray-500 mt-1">Badan Pusat Statistik Kabupaten Demak</p>
                        </div>
                    </a>
                    <p class="text-gray-500 text-sm leading-relaxed">
                        Jl. Sultan Hadiwijaya No.23, Krajan, Mangunjiwan, Kec. Demak, Kabupaten Demak, Jawa Tengah 59515
                    </p>
                    <div class="mt-4 flex space-x-4 text-sm text-gray-500">
                        <div class="flex items-center gap-2"><svg class="w-4 h-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg> (0291) 685445</div>
                        <div class="flex items-center gap-2"><svg class="w-4 h-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg> bps3321@bps.go.id</div>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-8 sm:gap-6 sm:grid-cols-3">
                    <div>
                        <h2 class="mb-4 text-sm font-semibold text-gray-900 uppercase">Tentang BPS</h2>
                        <ul class="text-gray-500 font-medium">
                            <li class="mb-2"><a href="#" class="hover:underline">Informasi Umum</a></li>
                            <li class="mb-2"><a href="#" class="hover:underline">Visi & Misi</a></li>
                            <li class="mb-2"><a href="#" class="hover:underline">Tugas & Fungsi</a></li>
                        </ul>
                    </div>
                    <div>
                        <h2 class="mb-4 text-sm font-semibold text-gray-900 uppercase">Layanan</h2>
                        <ul class="text-gray-500 font-medium">
                            <li class="mb-2"><a href="#" class="hover:underline">Konsultasi</a></li>
                            <li class="mb-2"><a href="#" class="hover:underline">Rekomendasi</a></li>
                            <li class="mb-2"><a href="#" class="hover:underline">Publikasi</a></li>
                        </ul>
                    </div>
                    <div>
                        <h2 class="mb-4 text-sm font-semibold text-gray-900 uppercase">Legal</h2>
                        <ul class="text-gray-500 font-medium">
                            <li class="mb-2"><a href="#" class="hover:underline">Kebijakan Privasi</a></li>
                            <li class="mb-2"><a href="#" class="hover:underline">Syarat & Ketentuan</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <hr class="my-6 border-gray-200 sm:mx-auto lg:my-8" />
            <div class="sm:flex sm:items-center sm:justify-between">
                <span class="text-sm text-gray-500 sm:text-center">© 2025 <a href="https://demakkab.bps.go.id/"
                        class="hover:underline">BPS Kabupaten Demak</a>. All Rights Reserved.</span>
                <div class="flex mt-4 sm:justify-center sm:mt-0 space-x-5">
                    <a href="#" class="text-gray-500 hover:text-gray-900">
                        <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                            viewBox="0 0 8 19">
                            <path fill-rule="evenodd"
                                d="M6.135 3H8V0H6.135a4.147 4.147 0 0 0-4.142 4.142V6H0v3h2v9.938h3V9h2.021l.592-3H5V3.591A.6.6 0 0 1 5.592 3h.543Z"
                                clip-rule="evenodd" />
                        </svg>
                        <span class="sr-only">Facebook page</span>
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Modal (Ambil Antrian) -->
    <div id="antrianModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">
        <!-- Background backdrop -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity backdrop-blur-sm" onclick="closeModal()">
        </div>

        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <!-- Modal panel -->
            <div
                class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                <form action="{{ route('queue.ambil') }}" method="POST">
                    @csrf
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div
                                class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-bps-blue" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                                <h3 class="text-xl font-bold leading-6 text-gray-900" id="modal-title">Pilih Layanan
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500 mb-4">Silakan pilih jenis layanan yang Anda
                                        butuhkan.</p>

                                    <label for="layanan" class="block mb-2 text-sm font-medium text-gray-900">Jenis
                                        Layanan</label>
                                    <select id="layanan" name="layanan" required
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-bps-blue focus:border-bps-blue block w-full p-2.5">
                                        <option value="">-- Pilih Layanan --</option>
                                        <option value="Permintaan Data">Permintaan Data</option>
                                        <option value="Konsultasi Statistik">Konsultasi Statistik</option>
                                        <option value="Rekomendasi Statistik">Rekomendasi Statistik</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                        <button type="submit"
                            class="inline-flex w-full justify-center rounded-lg bg-bps-blue px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-bps-dark sm:ml-3 sm:w-auto transition-colors">
                            Ambil Nomor
                        </button>
                        <button type="button" onclick="closeModal()"
                            class="mt-3 inline-flex w-full justify-center rounded-lg bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto transition-colors">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        function openModal() {
            const modal = document.getElementById('antrianModal');
            modal.classList.remove('hidden');
        }

        function closeModal() {
            const modal = document.getElementById('antrianModal');
            modal.classList.add('hidden');
        }

        // Close modal when clicking escape
        document.addEventListener('keydown', function (event) {
            if (event.key === "Escape") {
                closeModal();
            }
        });

        // SweetAlert Integration
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                html: `{!! session('success') !!}<br><div class="mt-4"><a href="{{ route('evaluasi.index') }}" class="text-white bg-bps-blue hover:bg-bps-dark focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 focus:outline-none">Masuk ke Halaman Evaluasi</a></div>`,
                showConfirmButton: false,
                timer: 6000,
                customClass: {
                    popup: 'swal2-rounded'
                }
            });
        @endif
    </script>
</body>

</html>
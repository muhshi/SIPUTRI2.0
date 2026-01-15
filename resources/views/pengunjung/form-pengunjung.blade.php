<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buku Tamu PST - Badan Pusat Statistik</title>

    <!-- Tailwind CSS (v3.4.16 Stable) -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        bps: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            500: '#0ea5e9', // Sky blue-ish
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e',
                        }
                    }
                }
            }
        }
    </script>

    <style>
        /* Custom scrollbar for better aesthetics */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex flex-col">

    <!-- Navbar Simple -->
    <nav class="bg-white border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center gap-3">
                        <!-- Logo Placeholder / Icon -->
                        <div
                            class="w-10 h-10 bg-bps-600 rounded-lg flex items-center justify-center text-white font-bold shadow-sm">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-xl font-bold text-slate-900 tracking-tight">Pelayanan Statistik Terpadu</h1>
                            <p class="text-xs text-slate-500 font-medium">Badan Pusat Statistik</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-5xl mx-auto">

            <div class="mb-10 text-center">
                <h2 class="text-3xl font-extrabold text-slate-900 sm:text-4xl">Buku Tamu Pengunjung</h2>
                <p class="mt-4 text-lg text-slate-600 max-w-2xl mx-auto">
                    Mohon lengkapi formulir di bawah ini untuk membantu kami meningkatkan kualitas layanan data
                    statistik.
                </p>
            </div>

            @if(session('success'))
                <div class="mb-8 rounded-xl bg-green-50 p-4 border border-green-200 shadow-sm flex items-start gap-3">
                    <div class="flex-shrink-0 text-green-500">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-green-800">Berhasil!</h3>
                        <div class="mt-1 text-sm text-green-700">{{ session('success') }}</div>
                    </div>
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <form action="{{ route('pengunjung.submit') }}" method="POST" class="p-8 md:p-10">
                    @csrf

                    <!-- Section 1: Data Pribadi -->
                    <div class="space-y-8">
                        <div>
                            <div class="flex items-center gap-3 mb-6">
                                <span
                                    class="flex items-center justify-center w-8 h-8 rounded-full bg-bps-100 text-bps-700 text-sm font-bold ring-4 ring-white">1</span>
                                <h3 class="text-lg font-semibold text-slate-900">Identitas Diri</h3>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                                <div class="col-span-1 md:col-span-2">
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Nama Lengkap</label>
                                    <input type="text" name="nama" required
                                        class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-bps-500 focus:ring-bps-500 sm:text-sm py-2.5 px-3 border transition-colors hover:border-slate-400"
                                        placeholder="Nama sesuai tanda pengenal">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Email</label>
                                    <input type="email" name="email"
                                        class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-bps-500 focus:ring-bps-500 sm:text-sm py-2.5 px-3 border transition-colors hover:border-slate-400"
                                        placeholder="nama@email.com">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Jenis Kelamin</label>
                                    <div class="flex gap-4">
                                        <label
                                            class="relative flex cursor-pointer rounded-lg border bg-white p-3 shadow-sm focus:outline-none w-full hover:bg-slate-50 transition-colors border-slate-200 has-[:checked]:border-bps-500 has-[:checked]:ring-1 has-[:checked]:ring-bps-500">
                                            <input type="radio" name="jenis_kelamin" value="Laki-laki" class="sr-only"
                                                required>
                                            <span class="flex items-center">
                                                <span class="flex flex-col">
                                                    <span
                                                        class="block text-sm font-medium text-slate-900">Laki-laki</span>
                                                </span>
                                            </span>
                                            <span
                                                class="pointer-events-none absolute -inset-px rounded-lg border-2 border-transparent"
                                                aria-hidden="true"></span>
                                        </label>
                                        <label
                                            class="relative flex cursor-pointer rounded-lg border bg-white p-3 shadow-sm focus:outline-none w-full hover:bg-slate-50 transition-colors border-slate-200 has-[:checked]:border-bps-500 has-[:checked]:ring-1 has-[:checked]:ring-bps-500">
                                            <input type="radio" name="jenis_kelamin" value="Perempuan" class="sr-only">
                                            <span class="flex items-center">
                                                <span class="flex flex-col">
                                                    <span
                                                        class="block text-sm font-medium text-slate-900">Perempuan</span>
                                                </span>
                                            </span>
                                            <span
                                                class="pointer-events-none absolute -inset-px rounded-lg border-2 border-transparent"
                                                aria-hidden="true"></span>
                                        </label>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Tahun Lahir</label>
                                    <input type="number" name="tahun_lahir"
                                        class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-bps-500 focus:ring-bps-500 sm:text-sm py-2.5 px-3 border transition-colors hover:border-slate-400"
                                        placeholder="YYYY">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Umur</label>
                                    <input type="number" name="umur"
                                        class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-bps-500 focus:ring-bps-500 sm:text-sm py-2.5 px-3 border transition-colors hover:border-slate-400"
                                        placeholder="Tahun">
                                </div>

                                <div class="col-span-1 md:col-span-2">
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Alamat Domisili</label>
                                    <textarea name="alamat" rows="2"
                                        class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-bps-500 focus:ring-bps-500 sm:text-sm py-2.5 px-3 border transition-colors hover:border-slate-400"
                                        placeholder="Alamat lengkap tempat tinggal saat ini"></textarea>
                                </div>
                            </div>
                        </div>

                        <hr class="border-slate-100">

                        <!-- Section 2: Latar Belakang -->
                        <div>
                            <div class="flex items-center gap-3 mb-6">
                                <span
                                    class="flex items-center justify-center w-8 h-8 rounded-full bg-bps-100 text-bps-700 text-sm font-bold ring-4 ring-white">2</span>
                                <h3 class="text-lg font-semibold text-slate-900">Latar Belakang</h3>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Pendidikan
                                        Terakhir</label>
                                    <select name="pendidikan"
                                        class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-bps-500 focus:ring-bps-500 sm:text-sm py-2.5 px-3 border cursor-pointer hover:border-slate-400"
                                        required>
                                        <option value="" disabled selected>Pilih jenjang pendidikan</option>
                                        <option>SD</option>
                                        <option>SMP</option>
                                        <option>SMA</option>
                                        <option>D3</option>
                                        <option>S1</option>
                                        <option>S2</option>
                                        <option>S3</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Pekerjaan Utama</label>
                                    <select name="pekerjaan"
                                        class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-bps-500 focus:ring-bps-500 sm:text-sm py-2.5 px-3 border cursor-pointer hover:border-slate-400"
                                        required>
                                        <option value="" disabled selected>Pilih pekerjaan</option>
                                        <option>Pelajar</option>
                                        <option>Mahasiswa</option>
                                        <option>Pegawai Swasta</option>
                                        <option>PNS/TNI/Polri</option>
                                        <option>Wiraswasta</option>
                                        <option>Lainnya</option>
                                    </select>
                                </div>

                                <div class="col-span-1 md:col-span-2">
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Asal Instansi</label>
                                    <input type="text" name="instansi"
                                        class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-bps-500 focus:ring-bps-500 sm:text-sm py-2.5 px-3 border transition-colors hover:border-slate-400"
                                        placeholder="Nama Universitas, Kantor, atau Sekolah">
                                </div>
                            </div>
                        </div>

                        <hr class="border-slate-100">

                        <!-- Section 3: Kunjungan -->
                        <div>
                            <div class="flex items-center gap-3 mb-6">
                                <span
                                    class="flex items-center justify-center w-8 h-8 rounded-full bg-bps-100 text-bps-700 text-sm font-bold ring-4 ring-white">3</span>
                                <h3 class="text-lg font-semibold text-slate-900">Detail Layanan</h3>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Tanggal
                                        Kunjungan</label>
                                    <input type="date" name="tanggal" required
                                        class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-bps-500 focus:ring-bps-500 sm:text-sm py-2.5 px-3 border transition-colors hover:border-slate-400">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Jenis Layanan</label>
                                    <select name="layanan"
                                        class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-bps-500 focus:ring-bps-500 sm:text-sm py-2.5 px-3 border cursor-pointer hover:border-slate-400">
                                        <option value="" disabled selected>Pilih layanan yang dibutuhkan</option>
                                        <option>Data Mikro</option>
                                        <option>Data Publikasi</option>
                                        <option>Konsultasi Statistik</option>
                                        <option>Rekomendasi Statistik</option>
                                        <option>Pustaka</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Tujuan
                                        Pemanfaatan</label>
                                    <select name="pemanfaatan"
                                        class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-bps-500 focus:ring-bps-500 sm:text-sm py-2.5 px-3 border cursor-pointer hover:border-slate-400">
                                        <option value="" disabled selected>Pilih tujuan</option>
                                        <option>Tugas Sekolah/Kuliah</option>
                                        <option>Penelitian/Skripsi/Tesis</option>
                                        <option>Perencanaan Bisnis</option>
                                        <option>Kebijakan Pemerintah</option>
                                        <option>Lainnya</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Rincian Data</label>
                                    <input type="text" name="data_diinginkan"
                                        class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-bps-500 focus:ring-bps-500 sm:text-sm py-2.5 px-3 border transition-colors hover:border-slate-400"
                                        placeholder="Cth: Data PDRB Tahun 2023">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Action -->
                    <div class="mt-10 pt-6 border-t border-slate-100 flex items-center justify-end gap-4">
                        <a href="{{ url('/') }}"
                            class="text-sm font-medium text-slate-500 hover:text-slate-800 px-4 py-2 transition-colors">
                            Batal
                        </a>
                        <button type="submit"
                            class="inline-flex justify-center rounded-lg border border-transparent bg-bps-700 py-3 px-8 text-sm font-semibold text-white shadow-sm hover:bg-bps-800 focus:outline-none focus:ring-2 focus:ring-bps-500 focus:ring-offset-2 transition-all hover:shadow-md">
                            Simpan Data Pengunjung
                        </button>
                    </div>
                </form>
            </div>

            <p class="text-center text-xs text-slate-400 mt-8">
                &copy; {{ date('Y') }} Badan Pusat Statistik. Dilindungi Undang-Undang.
            </p>

        </div>
    </main>
</body>

</html>
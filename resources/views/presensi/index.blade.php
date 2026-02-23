<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Presensi Pegawai - Camera</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- jQuery (required by Select2) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Select2 CSS & JS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.min.js"></script>
    <style>
        /* Select2 custom styling to match Tailwind design */
        .select2-container--default .select2-selection--single {
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            height: 46px;
            padding: 6px 12px;
            font-size: 0.875rem;
            color: #111827;
            transition: all 0.15s;
        }

        .select2-container--default .select2-selection--single:hover {
            border-color: #93c5fd;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 32px;
            padding-left: 0;
            color: #111827;
        }

        .select2-container--default .select2-selection--single .select2-selection__placeholder {
            color: #9ca3af;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 44px;
            right: 8px;
        }

        .select2-container--default.select2-container--open .select2-selection--single {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        }

        .select2-dropdown {
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .select2-results__option--highlighted[aria-selected] {
            background-color: #3b82f6 !important;
        }

        .select2-search--dropdown .select2-search__field {
            border: 1px solid #e5e7eb;
            border-radius: 0.375rem;
            padding: 8px 12px;
            font-size: 0.875rem;
        }

        .select2-search--dropdown .select2-search__field:focus {
            border-color: #3b82f6;
            outline: none;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        }
    </style>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4 font-sans">

    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl overflow-hidden">
        <!-- Header -->
        <div class="bg-blue-600 p-6 text-center relative">
            <a href="/"
                class="absolute left-4 top-1/2 -translate-y-1/2 text-white/80 hover:text-white transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <h1 class="text-xl font-bold text-white tracking-wide">PRESENSI PEGAWAI</h1>
            <p class="text-blue-100 text-sm mt-1">BPS Kabupaten Demak</p>
        </div>

        <div class="p-6">
            <!-- Camera View -->
            <div id="camera_wrapper"
                class="relative w-full h-80 bg-black rounded-xl overflow-hidden mb-6 shadow-inner ring-4 ring-gray-100">
                <div id="my_camera" class="w-full h-full object-cover"></div>
                <!-- Shutter Flash Animation -->
                <div id="shutter_flash"
                    class="absolute inset-0 bg-white opacity-0 pointer-events-none transition-opacity duration-200">
                </div>
            </div>

            <!-- Result View (Hidden by default) -->
            <div id="result_wrapper"
                class="hidden relative w-full h-80 bg-gray-900 rounded-xl overflow-hidden mb-6 shadow-inner ring-4 ring-gray-100">
                <div id="results" class="w-full h-full flex items-center justify-center"></div>
                <button onclick="retakePhoto()"
                    class="absolute top-2 right-2 bg-white/20 hover:bg-white/40 backdrop-blur-sm text-white p-2 rounded-full transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L11.414 10 7.121 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
            </div>

            <!-- Controls -->
            <div class="space-y-4">
                <!-- Employee Selection (Ideally this should be auto-detected from Auth) -->
                <div class="relative">
                    <select id="pegawai_id" style="width: 100%">
                        <option value="">-- Pilih Nama Anda --</option>
                        @foreach($pegawais as $p)
                            <option value="{{ $p->id }}">{{ $p->nama_pegawai }}</option>
                        @endforeach
                    </select>

                </div>

                <input type="hidden" name="image" id="image_data">

                <!-- Capture Button -->
                <button type="button" onClick="take_snapshot()" id="btn-capture"
                    class="w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-bold rounded-xl text-lg px-5 py-4 w-full transition-all shadow-lg shadow-blue-500/30 flex items-center justify-center gap-2 group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 group-hover:scale-110 transition-transform"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Ambil Foto Absen
                </button>

                <!-- Submit Button (Hidden initially) -->
                <button type="button" onClick="submitPresensi()" id="btn-submit"
                    class="hidden w-full text-white bg-green-500 hover:bg-green-600 focus:ring-4 focus:ring-green-300 font-bold rounded-xl text-lg px-5 py-4 w-full transition-all shadow-lg shadow-green-500/30 flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Kirim Absensi
                </button>
            </div>

            <div class="mt-6 text-center">
                <p class="text-xs text-gray-400">Pastikan wajah terlihat jelas di kamera.</p>
            </div>
        </div>
    </div>

    <script>
        const presensiHariIni = @json($todayPresensi);

        $(document).ready(function () {

            $('#pegawai_id').select2({
                placeholder: '-- Pilih Nama Anda --',
                allowClear: true
            });

            function updateButton() {

                const pegawaiId = $('#pegawai_id').val();
                const btn = $('#btn-capture');

                if (!pegawaiId) return;

                if (presensiHariIni[pegawaiId]) {

                    if (!presensiHariIni[pegawaiId].jam_selesai) {
                        btn.html('📸 Ambil Foto Pulang');
                        btn.prop('disabled', false);
                        btn.removeClass('bg-blue-600 bg-gray-400')
                            .addClass('bg-yellow-500');
                    } else {
                        btn.html('✔️ Presensi Sudah Lengkap');
                        btn.prop('disabled', true);
                        btn.removeClass('bg-blue-600 bg-yellow-500')
                            .addClass('bg-gray-400');
                    }

                } else {
                    btn.html('📸 Ambil Foto Masuk');
                    btn.prop('disabled', false);
                    btn.removeClass('bg-yellow-500 bg-gray-400')
                        .addClass('bg-blue-600');
                }
            }

            // Trigger saat dropdown berubah
            $('#pegawai_id').on('change', updateButton);

            // 🔥 TAMBAHAN PENTING: jalankan sekali saat halaman load
            updateButton();

        });
    </script>


    <script language="JavaScript">
        Webcam.set({
            width: 400,
            height: 400,
            image_format: 'jpeg',
            jpeg_quality: 90,
            flip_horiz: true
        });

        // Initialize camera
        function initCamera() {
            Webcam.attach('#my_camera');
        }

        // Run init on load
        document.addEventListener('DOMContentLoaded', initCamera);

        function take_snapshot() {
            const pegawaiId = document.getElementById('pegawai_id').value;
            if (!pegawaiId) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Pilih Pegawai',
                    text: 'Silakan pilih nama pegawai terlebih dahulu!',
                    confirmButtonColor: '#3085d6',
                });
                return;
            }

            // Play shutter sound effect
            // const audio = new Audio('path/to/shutter.mp3'); audio.play(); 

            // Visual flash effect
            const flash = document.getElementById('shutter_flash');
            flash.classList.remove('opacity-0');
            setTimeout(() => flash.classList.add('opacity-0'), 100);

            Webcam.snap(function (data_uri) {
                document.getElementById('image_data').value = data_uri;
                document.getElementById('results').innerHTML = '<img src="' + data_uri + '" class="w-full h-full object-cover"/>';

                // Toggle views
                document.getElementById('camera_wrapper').classList.add('hidden');
                document.getElementById('result_wrapper').classList.remove('hidden');
                document.getElementById('btn-capture').classList.add('hidden');
                document.getElementById('btn-submit').classList.remove('hidden');
            });
        }

        function retakePhoto() {
            document.getElementById('image_data').value = '';
            document.getElementById('camera_wrapper').classList.remove('hidden');
            document.getElementById('result_wrapper').classList.add('hidden');
            document.getElementById('btn-capture').classList.remove('hidden');
            document.getElementById('btn-submit').classList.add('hidden');
        }

        function submitPresensi() {
            const pegawaiId = document.getElementById('pegawai_id').value;
            const imageData = document.getElementById('image_data').value;

            if (!pegawaiId || !imageData) return;

            // Show loading state
            Swal.fire({
                title: 'Memproses Absensi...',
                text: 'Mohon tunggu sebentar',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch('/presensi', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    pegawai_id: pegawaiId,
                    image: imageData
                })
            })
                .then(async response => {
                    if (!response.ok) {
                        const text = await response.text();
                        // Try to parse JSON error if possible
                        try {
                            const json = JSON.parse(text);
                            if (json.message) throw new Error(json.message);
                        } catch (e) {
                            // ignore
                        }
                        throw new Error(`Server Error: ${response.status} ${response.statusText}\n${text.substring(0, 500)}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: data.type === 'masuk' ? 'Absen Masuk Berhasil!' : 'Absen Pulang Berhasil!',
                            text: data.message,
                            timer: 3000,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: data.message
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi Kesalahan',
                        text: error.message || 'Gagal menghubungi server.',
                    });
                });
        }
    </script>
</body>

</html>
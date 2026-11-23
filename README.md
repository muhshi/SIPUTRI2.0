# SIPUTRI2.0 - Sistem Informasi Pelayanan Umum Terpadu & Terintegrasi

SIPUTRI2.0 adalah aplikasi portal layanan untuk BPS Kabupaten Demak yang mencakup fitur Buku Tamu, Antrian, Evaluasi Pelayanan, dan Sistem Presensi Pegawai. Aplikasi ini dibangun menggunakan **Laravel 13** dan **Filament 5**.

## Tech Stack

| Teknologi | Versi |
|---|---|
| PHP | >= 8.3 |
| Laravel | 13.x |
| Filament | 5.x |
| Livewire | 4.x |
| Node.js | LTS |

## Persyaratan Sistem

Pastikan komputer Anda sudah terinstall:
- [PHP](https://www.php.net/) >= 8.3
- [Composer](https://getcomposer.org/)
- [Node.js](https://nodejs.org/) & NPM
- Database (SQLite / MySQL / PostgreSQL)

## Cara Instalasi

Ikuti langkah-langkah di bawah ini untuk menjalankan project di komputer lokal Anda.

### 1. Clone Repository
Clone project ini dari GitHub ke direktori lokal Anda:

```bash
git clone https://github.com/muhshi/SIPUTRI2.0.git
cd SIPUTRI2.0
```

### 2. Install Dependensi
Install dependensi PHP dan JavaScript:

```bash
composer install
npm install
```

### 3. Konfigurasi Environment
Duplikat file konfigurasi `.env.example` menjadi `.env`:

```bash
cp .env.example .env
```

Buka file `.env` dan sesuaikan konfigurasi database Anda. 
Jika menggunakan **SQLite** (default untuk development), Anda bisa membiarkannya atau pastikan baris berikut ada:

```env
DB_CONNECTION=sqlite
# DB_HOST=127.0.0.1
# DB_PORT=3306
# ...
```

### 4. Generate Application Key
Generate key enkripsi aplikasi:

```bash
php artisan key:generate
```

### 5. Setup Database
Buat file database SQLite (jika menggunakan SQLite):

```bash
touch database/database.sqlite
```

Jalankan migrasi database untuk membuat tabel:

```bash
php artisan migrate
```

### 6. Seeding Data (Penting!)
Project ini membutuhkan data awal (Super Admin & Data Pegawai) agar bisa digunakan. Jalankan perintah seeder berikut:

```bash
php artisan db:seed
```
*Perintah ini akan menjalankan `DatabaseSeeder`, yang secara otomatis memanggil `AdminSeeder` dan `PegawaiSeeder`.*

### 7. Build Aset Frontend
Compile aset CSS dan JS menggunakan Vite:

```bash
npm run build
```

### 8. Jalankan Aplikasi
Jalankan server lokal Laravel:

```bash
php artisan serve
```

Aplikasi sekarang dapat diakses di: [http://127.0.0.1:8000](http://127.0.0.1:8000)

## Akun Login Default

Jika Anda menjalankan seeder bawaan, berikut adalah kredensial untuk login ke panel admin:

- **URL Admin**: [http://127.0.0.1:8000/admin](http://127.0.0.1:8000/admin)
- **Email**: `admin@bps.go.id` (Cek `database/seeders/AdminSeeder.php` untuk memastikan)
- **Password**: `password` (Default)

## Masalah Umum (Troubleshooting)

**Error: "Vite manifest not found"**
- Solusi: Jalankan `npm run build` untuk membuat file manifest.

**Error: "No such function: MONTH" (SQLite)**
- Solusi: Fitur chart dan filter telah disesuaikan agar kompatibel dengan SQLite. Jika masih error, pastikan Anda telah menarik kode terbaru (`git pull`).

## Deployment ke Server (`deploy.sh`)

Telah disediakan script otomatisasi deploy cerdas (`deploy.sh`) yang mendeteksi perubahan commit secara otomatis dan **hanya melakukan build aset/install dependensi saat dibutuhkan**:

```bash
# Memberikan izin eksekusi (jika belum)
chmod +x deploy.sh

# Jalankan deploy cerdas (otomatis git pull, cek diff, migrasi, dan cache)
./deploy.sh

# Atau jika ingin memaksa build ulang aset frontend:
./deploy.sh --force-build

# Melewati build frontend:
./deploy.sh --skip-build
```

### Keunggulan `deploy.sh`:
1. **Smart Build**: Hanya menjalankan `npm run build` jika terdeteksi perubahan pada folder `resources/`, `vite.config.js`, `package.json`, atau jika `public/build/manifest.json` belum ada.
2. **Smart Composer**: Hanya menjalankan `composer install` jika `composer.json` atau `composer.lock` berubah.
3. **Safe Migration**: Menjalankan `php artisan migrate --force` secara aman tanpa menghapus/merusak data yang sudah ada.
4. **Auto Caching**: Mengoptimalkan cache Laravel (`config:cache`, `route:cache`, `view:cache`, `filament:cache-components`).
5. **Auto Reload Docker/FrankenPHP**: Otomatis me-restart container `siputri-franken` jika server menggunakan docker-compose.

## Changelog

### 2026-11-23
- **Fix Tampilan Dashboard & Ukuran Foto Petugas Piket (Anti-Overflow & Bulletproof CSS)**:
  - Memperbaiki gambar foto petugas piket yang sebelumnya tampil penuh satu layar dan merusak layout dashboard karena ketiadaan styling bawaan untuk class Tailwind pada Filament standalone CSS.
  - Menerapkan pembatasan ukuran avatar petugas yang aman dan terkunci (48x48px circle, `object-fit: cover`, `border-radius: 9999px`) menggunakan kombinasi atribut HTML `width/height`, inline styles, class avatar Filament, dan wrapper berpelindung overflow.
  - Memperbaiki layout kartu petugas piket menjadi grid responsif modern (1 kolom mobile, 2 kolom desktop) dengan styling kartu, avatar fallback inisial nama, teks elipsis rapi untuk nama/NIP/jabatan, serta badge jenis piket, shift, dan status presensi yang proporsional.
  - Menambahkan global safeguard rule pada `AdminPanelProvider` agar seluruh gambar dan avatar dalam widget/section Filament tidak dapat meluap melebihi batas kontainernya.
  - Merapikan spacing vertikal dan container header laporan statistik pada halaman dashboard.

### 2026-11-21
- **Fix & Hardening Styling Login SSO (Bulletproof Scoped CSS & Inline Styles)**:
  - Menyematkan scoped dedicated CSS dan styling inline pada `login.blade.php` agar komponen antarmuka (tombol hero SSO biru `#005596`, badge logo BPS 34x34px, teks putih tebal, icon arrow, flash alert error/status, serta accordion fallback) tampil rapi, simetris, dan pixel-perfect di semua browser tanpa ketergantungan pada class utilitas Tailwind eksternal atau vendor Filament v5 CSS treeshaking.
  - Mempercantik kartu login Filament (`.fi-simple-main`) dengan border halus, shadow lembut, serta padding proporsional.
- **Implementasi Login Tunggal SIPETRA SSO (Single Sign-On BPS Kab. Demak)**:
  - **Custom Filament Login Page**: Antarmuka autentikasi modern berbasis SIPETRA SSO dengan tombol heroik single-button biru BPS (`#005596`), badge logo BPS putih, feedback interaktif saat diklik ("Menghubungkan ke SIPETRA..."), SVG spinner, serta pencegahan klik ganda.
  - **Akses Darurat / Pengembang**: Accordion tersembunyi interaktif (Alpine.js) untuk form login email/password bawaan Filament, aktif otomatis jika parameter URL `?fallback=1` disertakan.
  - **Pembersihan Auth Legacy**: Menghapus render hook lama `PanelsRenderHook::AUTH_LOGIN_FORM_AFTER` dan view legacy `sso-button.blade.php`, serta memastikan registrasi lokal mandiri tetap nonaktif.
  - **Penanganan Callback SSO Terproteksi**: Redirect error callback diarahkan aman ke `filament.admin.auth.login` disertai flash session error (anti-infinite loop), auto-provisioning akun baru dengan role default `Pegawai`, dan auto-linking ke petugas PST yang relevan.
  - **Harmonisasi Halaman Manajemen Profil/Pengguna**: Penambahan helper text edukatif pada input password bahwa kredensial dan sandi utama dikelola terpusat di SIPETRA SSO.
- **Fix Layout & Styling Kalender Piket (Mobile-Friendly & Bulletproof CSS)**:
  - Memperbaiki tampilan kalender yang unstyled / runtuh vertikal di browser & mobile dengan menyematkan scoped dedicated CSS terisolasi (grid 7 kolom, styling cell, badge shift, dan tombol aksi) yang mandiri di dalam blade widget tanpa ketergantungan utility CSS eksternal.
  - Menambahkan container scrollable responsif horizontal (`overflow-x: auto; min-width: 680px; -webkit-overflow-scrolling: touch;`) sehingga kalender bulanan 7 kolom dapat dilihat secara utuh, rapi, dan mudah digeser di smartphone tanpa gepeng atau berantakan.

### 2026-11-20
- **Pembaruan Alur & Form Pengaturan Jadwal Piket Petugas PST**:
  - **Matriks Input Harian 4-Slot**: Pengelompokan pengisian jadwal harian dalam satu form ringkas dan intuitif: Shift Pagi (08.00 - 12.00) untuk Petugas Pelayanan & Pengaduan/Disabilitas, serta Shift Siang (12.00 - 15.30) untuk Petugas Pelayanan & Pengaduan/Disabilitas.
  - **Pencegahan Bentrok Petugas**: Validasi real-time dan backend agar petugas yang sama tidak dapat dijadwalkan pada lebih dari satu slot di hari yang sama.
  - **Generator Rotasi Otomatis (Auto-Scheduler Bulanan)**: Fitur "Rotasi Otomatis (Generate)" pada widget kalender untuk mengenerate jadwal sebulan penuh (Senin s.d. Jumat) secara adil menggunakan rotasi round-robin dari daftar petugas aktif, lengkap dengan opsi overwrite jadwal existing.
  - **Visualisasi Kalender Piket Terstruktur**: Tampilan kalender widget yang dikelompokkan rapi per shift (Pagi ☀️ dan Siang 🌤️) lengkap dengan avatar nama petugas dan trigger modal ubah cepat.
  - **Sinkronisasi Create & Edit Record Filament**: Integrasi penuh `CreateJadwalPiket` dan `EditJadwalPiket` dengan `JadwalPiketService` untuk multi-slot saving/clearing yang seamless dan aman tanpa merusak struktur database.

### 2026-10-31
- **Fix Proporsi Foto Presensi (Anti-Mleyot Total)**: Migrasi dari library lawas WebcamJS ke native HTML5 MediaDevices API (`getUserMedia`) dengan kalkulasi center-crop dinamis berdasarkan resolusi riil perangkat (`videoWidth` & `videoHeight`), serta preview mirror natural. Menghilangkan distorsi/kompresi paksa sensor sehingga foto 100% proporsional di semua jenis kamera (laptop 16:9, webcam 4:3, maupun smartphone).
- **Profil & Password Terpusat SIPETRA & Sinkronisasi 1-Klik**:
  - Menambahkan menu "Profil & Password (SIPETRA)" pada dropdown user Filament yang membuka modal pengaturan akun terpusat di `https://bpsdemak.com/account`.
  - Menambahkan opsi "Sinkronkan Profil dari SIPETRA" untuk menyegarkan data nama, NIP, email, dan jabatan secara instan dari SIPETRA SSO.
- **Federated Single Logout (SLO) & Anti Double-Click SSO Button**:
  - Implementasi `SsoLogoutResponse` pada Filament agar logout pengguna otomatis memutus sesi terpusat di SIPETRA (`/oauth/logout`) dan mencegah login otomatis tanpa kredensial.
  - Menambahkan animasi loading spinner dan disable state pada tombol "Masuk dengan SIPETRA SSO" untuk mencegah submit ganda.
- **Default Sort & Filter Presensi**: Perubahan default sorting pada tabel Presensi ke `tanggal` descending (data terbaru paling atas) dan penambahan filter berdasarkan Pegawai
- **Widget Petugas Piket Hari Ini**: Widget monitoring piket PST & pengaduan harian di Dashboard Admin Filament dengan deteksi live status kehadiran (Belum Presensi / Hadir / Selesai Piket)
- **Kompresi Otomatis Foto Presensi**: Optimasi penyimpanan dengan kompresi GD (resize proporsional maks. 800px & JPEG 75%) pada `PresensiController` untuk menghemat storage server
- **Sanitasi Respons Error**: Peningkatan keamanan API presensi dengan sanitasi respons error (mencegah kebocoran trace/path internal server ke klien) dan logging server terpusat
- **Ekspor Excel & PDF Presensi**: Penambahan tombol aksi *Export Excel (.xlsx)* via OpenSpout dan *Print / Save PDF* rekapitulasi presensi berdesain resmi BPS
- **Optimasi Indeks Database**: Penambahan composite index non-destruktif pada tabel `presensis` (`tanggal, pegawai_id`) dan `jadwal_pikets` (`tanggal, is_notified`)

### 2026-09-11
- Peningkatan `deploy.sh`: Dukungan eksekusi perintah PHP/Artisan/Composer via container Docker (`siputri-franken`) dan auto-fallback build frontend via image `node:22-alpine` jika Node di host server usang (< 20)
- Pembuatan script deploy cerdas `deploy.sh` dengan deteksi otomatis perubahan commit (hanya build frontend & composer saat dibutuhkan)
- Fitur penambahan Petugas PST dari Pengguna: penambahan dropdown pilihan pegawai (`user_id`) pada `PegawaiPstForm` dengan auto-fill nama, NIP, dan jabatan
- Migrasi penambahan kolom `user_id` pada tabel `pegawai_psts` untuk menghubungkan akun pengguna secara langsung
- Penambahan tombol aksi *"Jadikan Petugas PST"* pada tabel Pengguna (`UsersTable`) untuk pengguna yang belum terhubung sebagai petugas PST
- Peningkatan relasi model `User` dan `PegawaiPst`: sinkronisasi otomatis `user_id`, nama, NIP, serta auto-linking saat login/presensi
- Penambahan kolom Akun Pengguna pada tabel Petugas PST (`PegawaiPstsTable`)
- Perbaikan hak akses presensi: Pengguna login yang bukan petugas PST dan bukan admin tidak lagi dapat melihat dropdown pegawai lain, melainkan menampilkan peringatan status akun dan menonaktifkan tombol presensi (proteksi backend 403)

### 2026-05-06
- Merge branch `feature/presensi-auto-name` ke `main`
- Implementasi auto-detect pegawai berdasarkan nama pada login SSO Presensi (fallback jika NIP tidak cocok)
- Update relasi model `User`: Relasi `pegawai()` kini menggunakan mapping nama (name) ke nama_pegawai
- Perbaikan format tanggal pada ekspor data Kunjungan

### 2026-05-05
- Merge branch `feature/presensi-update` ke `main`
- Implementasi Mode Presensi Manual (tanggal, jam masuk, dan jam selesai kustom)
- Perbaikan logika pengecekan status tombol Masuk/Pulang pada halaman Presensi
- Sinkronisasi data presensi dengan user yang sedang login (auth user pegawai)
- Penambahan filter dan optimasi query pada halaman list presensi admin

### 2026-10-16
- Merge branch `adit` ke `main`
- Penambahan fitur Jadwal Piket Petugas PST (Resource Filament, kalender jadwal piket, form input 2 kolom: Pelayanan & Pengaduan)
- Penambahan command notifikasi WhatsApp otomatis untuk jadwal piket (`SendPiketNotification`)
- Penambahan kolom `no_hp` pada data Pegawai PST
- Perbaikan dependensi `composer.json` (mempertahankan Filament `^5.0`) dan fallback kompatibilitas PHP pada `config/database.php`
- Penyempurnaan `deploy.sh`: penambahan deteksi pintar perubahan file migrasi database (skip `migrate` jika tidak ada file migrasi baru)

### 2026-04-29
- Merge branch `feat/sso-integration-and-updates` ke `main`
- Implementasi integrasi SIPETRA SSO (Socialite Passport)
- Penambahan tombol login SSO pada halaman autentikasi
- Update aset Filament dan vendor Laravel ERD
- Perbaikan error `password cannot be null` saat pendaftaran user baru via SSO
- Update `UserResource`: Penambahan field NIP, Jabatan, dan relasi ke data Pegawai
- Update Model `User`: Penambahan relasi `pegawai()` untuk menghubungkan user dengan data Pegawai PST via NIP

### 2026-04-14
- **Upgrade Laravel 12 → 13** dan **Filament 4 → 5** (beserta Livewire 3 → 4)
- Update `laravel/tinker` ke versi `^3.0` untuk kompatibilitas Laravel 13
- Resolve ulang dependency tree secara penuh (composer.lock diperbarui)
- Jalankan `php artisan filament:upgrade` — semua asset Filament v5 dipublish ulang
- Update persyaratan PHP minimum menjadi >= 8.3

### 2026-04-13
- Upgrade fitur unduhan dari CSV menjadi **format Excel asli (.xlsx)** menggunakan OpenSpout
- Redesain total **halaman cetak/unduhan PDF** dengan estetika BPS premium dan layout landscape profesional
- Perbaikan bug filter pada fitur ekspor; sekarang unduhan Excel dan PDF telah sesuai dengan filter (Tahun/Triwulan) yang aktif di tabel
- Sinkronisasi parameter filter antara Filament Table dan pengontrol cetak laporan

### 2026-04-09
- Modernisasi UI Landing Page Microsite dengan tema gelap premium dan animasi dinamis
- Pembuatan **Project Look Book** untuk standarisasi desain visual (warna, tipografi, komponen)
- Penambahan input field "Nomor Telepon" pada form Identitas Diri (Pengunjung)
- Integrasi dan lokalisasi **SurveyJS Builder** di dalam Filament Admin Panel
- Pembuatan portal khusus untuk petugas (Field Enumerator) dengan sistem otentikasi terpisah

### 2026-04-07
- Rebranding Halaman Presensi menjadi **PRESENSI JAKET PADU**
- Update UI Halaman Presensi (Camera) & Form Presensi
- Update Judul Widget Presensi di Dashboard Admin
### 2026-03-06
- Merge branch `feature/dashboard-antrian-baru` ke `main`
- Install & integrasi **Filament Shield** (role & permission management)
- Register `ShieldPlugin` di `AdminPanelProvider`
- Tambah `HasRoles` trait ke model `User`
- Buat **UserResource** (CRUD Pengguna) dengan fitur:
  - Form: nama, email, password, role selector
  - Tabel: nama, email, role (badge), tanggal dibuat
  - Navigation group: Manajemen

### 2026-02-21
- Merge branch `feature/dashboard-refinement` ke `main`
- Merge branch `feature/update_presensi_evaluasi` ke `main`
- Jalankan migrasi tabel `pegawai_psts`

---
**BPS Kabupaten Demak**


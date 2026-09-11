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

### 2026-09-11
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


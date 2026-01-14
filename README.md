# SIPUTRI2.0 - Sistem Informasi Pelayanan Umum Terpadu & Terintegrasi

SIPUTRI2.0 adalah aplikasi portal layanan untuk BPS Kabupaten Demak yang mencakup fitur Buku Tamu, Antrian, Evaluasi Pelayanan, dan Sistem Presensi Pegawai. Aplikasi ini dibangun menggunakan **Laravel** dan **Filament**.

## Persyaratan Sistem

Pastikan komputer Anda sudah terinstall:
- [PHP](https://www.php.net/) >= 8.2
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

## Kontribusi
Silakan buat *Pull Request* atau laporkan *Issues* jika menemukan bug atau ingin menambahkan fitur baru.

---
**BPS Kabupaten Demak**

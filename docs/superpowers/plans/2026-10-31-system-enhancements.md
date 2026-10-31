# SIPUTRI 2.0 System Enhancements Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Implement 5 system enhancements for SIPUTRI 2.0: safe database indexes, presensi image compression, error response sanitization, presensi export (Excel & PDF), and a "Petugas Piket Hari Ini & Status Presensi" Filament dashboard widget.

**Architecture:** Add non-destructive database indexes via standard Laravel migrations; enhance `PresensiController` with GD-based image compression and secure error logging; implement streaming XLSX export via OpenSpout and landscape PDF print view with dedicated controller; build a real-time Livewire/Filament widget displaying today's scheduled officers with live attendance badges.

**Tech Stack:** Laravel 13, Filament 5, Livewire 4, OpenSpout 4, Blade, PHP GD, SQLite / MySQL.

**Spec:** User requested features 1, 2, 3, 5, 6 from observation report:
1. Widget Petugas Piket Hari Ini & Status Kehadiran di Dashboard Admin Filament.
2. Kompresi otomatis foto presensi sebelum disimpan ke disk storage.
3. Ekspor Rekap Presensi (Excel & PDF) di Panel Presensi Filament.
4. Database indexing untuk tabel `presensis` dan `jadwal_pikets`.
5. Sanitasi respons error pada `PresensiController` (keamanan internal path).

## Global Constraints
- Database safety rule: DO NOT drop, truncate, or delete any existing database tables or records.
- Preserve Filament 5 and Livewire 4 conventions and namespaces.
- Keep all unit and feature tests passing.
- Follow Git workflow: update `README.md` Changelog, commit with clear message, push to `main`, and execute SSH deploy.

## Review Focus
- Database migration must be non-destructive and succeed on both SQLite (local/testing) and MySQL (production).
- Base64 image decoding in `PresensiController` must handle varied image sizes without memory exhaustion, fallback gracefully if GD fails.
- Presensi export must respect active table filters (pegawai, date range).
- Dashboard widget must handle days with no scheduled piket without throwing null errors.
- Internal server traces must not leak in HTTP API responses.

---

### Task 1: Non-Destructive Database Indexes Migration

**Files:**
- Create: `database/migrations/2026_10_31_080000_add_indexes_to_presensis_and_jadwal_pikets_tables.php`
- Test: `tests/Feature/DatabaseIndexesTest.php`

**Interfaces:**
- Consumes: `presensis` table (`tanggal`, `pegawai_id`), `jadwal_pikets` table (`tanggal`, `is_notified`).
- Produces: Composite indexes `presensis_tanggal_pegawai_id_index` and `jadwal_pikets_tanggal_is_notified_index`.

- [ ] **Step 1: Write the failing test**
Create `tests/Feature/DatabaseIndexesTest.php` asserting that index exists on `presensis` and `jadwal_pikets`.

- [ ] **Step 2: Run test to verify it fails**
Run: `php artisan test --filter=DatabaseIndexesTest`
Expected: FAIL (index not yet created).

- [ ] **Step 3: Implement migration**
Create `database/migrations/2026_10_31_080000_add_indexes_to_presensis_and_jadwal_pikets_tables.php` adding indexes in `up()` and dropping in `down()`. Run `php artisan migrate`.

- [ ] **Step 4: Run test to verify it passes**
Run: `php artisan test --filter=DatabaseIndexesTest`
Expected: PASS.

- [ ] **Step 5: Commit**
```bash
git add database/migrations/*add_indexes* tests/Feature/DatabaseIndexesTest.php
git commit -m "perf(db): tambah composite index pada presensis dan jadwal_pikets"
```

---

### Task 2: Presensi Controller Error Sanitization & Image Compression

**Files:**
- Modify: `app/Http/Controllers/PresensiController.php:110-185`
- Test: `tests/Feature/PresensiCompressionAndErrorTest.php`

**Interfaces:**
- Consumes: POST `/presensi` base64 image payload.
- Produces: Compressed JPEG stored in `storage/app/public/presensi/` (max 800px width/height, 75% quality) and sanitized error response (status 500 without `file`/`line` details).

- [ ] **Step 1: Write the failing test**
Create `tests/Feature/PresensiCompressionAndErrorTest.php` testing:
1. Error response sanitization (no `file` or `line` key exposed in JSON error).
2. Saved image file is smaller/compressed properly.

- [ ] **Step 2: Run test to verify it fails**
Run: `php artisan test --filter=PresensiCompressionAndErrorTest`
Expected: FAIL on response keys or image handling.

- [ ] **Step 3: Implement compression and error sanitization in `PresensiController.php`**
Add helper method `compressAndSaveImage(string $base64Image, string $filePath): void` using GD library with fallback, and sanitize `catch (\Throwable $e)` block with `Log::error(...)`.

- [ ] **Step 4: Run test to verify it passes**
Run: `php artisan test --filter=PresensiCompressionAndErrorTest`
Expected: PASS.

- [ ] **Step 5: Commit**
```bash
git add app/Http/Controllers/PresensiController.php tests/Feature/PresensiCompressionAndErrorTest.php
git commit -m "feat(presensi): kompresi otomatis foto presensi dan sanitasi respons error"
```

---

### Task 3: Presensi Export to Excel (.xlsx) and PDF Print

**Files:**
- Create: `app/Http/Controllers/PrintPresensiController.php`
- Create: `resources/views/print/presensi.blade.php`
- Modify: `routes/web.php`
- Modify: `app/Filament/Resources/Presensis/Pages/ListPresensis.php`
- Test: `tests/Feature/PresensiExportTest.php`

**Interfaces:**
- Consumes: `ListPresensis::getFilteredTableQuery()`, GET `/print/presensi` with filters `pegawai_id`, `bulan`, `tahun`.
- Produces: Streaming `.xlsx` download via OpenSpout and printable PDF HTML layout in `print/presensi.blade.php`.

- [ ] **Step 1: Write the failing test**
Create `tests/Feature/PresensiExportTest.php` verifying:
1. GET `/print/presensi` returns HTTP 200 with attendance data.
2. `ListPresensis` has header actions `export_xlsx` and `print_pdf`.

- [ ] **Step 2: Run test to verify it fails**
Run: `php artisan test --filter=PresensiExportTest`
Expected: FAIL.

- [ ] **Step 3: Implement PrintPresensiController, view, route, and ListPresensis actions**
Implement `PrintPresensiController` reading `pegawai_id` and date filters; create `resources/views/print/presensi.blade.php` with BPS official letterhead; add header actions to `ListPresensis`.

- [ ] **Step 4: Run test to verify it passes**
Run: `php artisan test --filter=PresensiExportTest`
Expected: PASS.

- [ ] **Step 5: Commit**
```bash
git add app/Http/Controllers/PrintPresensiController.php resources/views/print/presensi.blade.php routes/web.php app/Filament/Resources/Presensis/Pages/ListPresensis.php tests/Feature/PresensiExportTest.php
git commit -m "feat(presensi): tambah fitur ekspor excel dan cetak pdf rekap presensi"
```

---

### Task 4: Petugas Piket Hari Ini Widget on Filament Dashboard

**Files:**
- Create: `app/Filament/Widgets/PetugasPiketHariIniWidget.php`
- Create: `resources/views/filament/widgets/petugas-piket-hari-ini.blade.php`
- Modify: `resources/views/filament/pages/dashboard.blade.php`
- Test: `tests/Feature/PetugasPiketHariIniWidgetTest.php`

**Interfaces:**
- Consumes: `JadwalPiket::whereDate('tanggal', today())`, `Presensi::whereDate('tanggal', today())`.
- Produces: Visual card displaying duty roster for today with real-time attendance badges (Belum Presensi / Hadir / Selesai Piket).

- [ ] **Step 1: Write the failing test**
Create `tests/Feature/PetugasPiketHariIniWidgetTest.php` checking widget data for today's piket and attendance status calculation.

- [ ] **Step 2: Run test to verify it fails**
Run: `php artisan test --filter=PetugasPiketHariIniWidgetTest`
Expected: FAIL.

- [ ] **Step 3: Implement widget and blade template**
Create `PetugasPiketHariIniWidget` and template `resources/views/filament/widgets/petugas-piket-hari-ini.blade.php`. Include widget in `dashboard.blade.php` above charts.

- [ ] **Step 4: Run test to verify it passes**
Run: `php artisan test --filter=PetugasPiketHariIniWidgetTest`
Expected: PASS.

- [ ] **Step 5: Commit**
```bash
git add app/Filament/Widgets/PetugasPiketHariIniWidget.php resources/views/filament/widgets/petugas-piket-hari-ini.blade.php resources/views/filament/pages/dashboard.blade.php tests/Feature/PetugasPiketHariIniWidgetTest.php
git commit -m "feat(dashboard): tambah widget petugas piket hari ini dengan status presensi live"
```

---

### Task 5: Full Verification, Changelog Update, Git Push, and Remote Deployment

**Files:**
- Modify: `README.md`

- [ ] **Step 1: Run full test suite**
Run: `php artisan test`
Expected: All tests PASS.

- [ ] **Step 2: Update Changelog in `README.md`**
Add comprehensive list of all 5 completed features to `README.md` under `### 2026-10-31`.

- [ ] **Step 3: Commit and Push**
```bash
git add README.md
git commit -m "docs: update changelog dengan fitur optimasi sistem, ekspor presensi, dan widget piket"
git push origin main
```

- [ ] **Step 4: Deploy to Remote Server via SSH**
Execute: `ssh bpsdemak@10.133.21.24 "cd apps/siputri && ./deploy.sh"`
Verify deploy completes with code 0 and application is live.

# Jadwal Piket 4-Slot Matrix & Auto-Scheduler Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Simplify and streamline the PST officer scheduling flow by replacing the confusing single-entry/arbitrary-split forms with an intuitive 4-slot daily matrix form (Pagi & Siang: Pelayanan & Pengaduan) and adding an automated monthly round-robin rotation generator.

**Architecture:** 
1. Build a daily 4-slot matrix service/handler to save and retrieve the 4 canonical daily slots (`Pagi-Pelayanan`, `Pagi-Pengaduan/Disabilitas`, `Siang-Pelayanan`, `Siang-Pengaduan/Disabilitas`).
2. Upgrade `JadwalPiketCalendarWidget` modal from arbitrary checkbox list to the structured 4-slot matrix with pre-filling, clear-slot capability, and duplicate officer detection.
3. Add a "Generate Rotasi Otomatis" action in `JadwalPiketCalendarWidget` to distribute active officers evenly across all working days (Mon-Fri) of any month without overlap.
4. Enhance `JadwalPiketForm` and `CreateJadwalPiket` so that creating a schedule from `/admin/jadwal-pikets/create` also presents the 4-slot matrix for a chosen date.
5. Upgrade the calendar cell UI to visually organize morning and afternoon shifts clearly.

**Tech Stack:** Laravel 13, Filament 5, Livewire 4, Carbon, Blade, SQLite / MySQL.

**Spec:**
- Daily roster consists of exactly 4 roles:
  1. Shift Pagi (08.00 - 12.00, Jumat 08.00 - 11.30): 1 Petugas Pelayanan
  2. Shift Pagi: 1 Petugas Pengaduan/Disabilitas
  3. Shift Siang (12.00 - 15.30, Jumat 11.30 - 15.30): 1 Petugas Pelayanan
  4. Shift Siang: 1 Petugas Pengaduan/Disabilitas
- Officers should not be double-booked on the same day across the 4 roles.
- Existing database structure `jadwal_pikets` (`pegawai_pst_id`, `tanggal`, `jenis_piket`, `shift`, `is_notified`) is strictly maintained for backward compatibility.

## Global Constraints
- Database safety rule: DO NOT drop, truncate, or delete any existing database tables or records.
- Preserve Filament 5 and Livewire 4 conventions and namespaces.
- Keep all unit and feature tests passing.
- Follow Git workflow: update `README.md` Changelog, commit with clear message, push to `main`, and execute SSH deploy.

## Review Focus
- Modal opening on a date with existing assignments must pre-fill all 4 dropdowns accurately.
- Clearing a slot in the matrix must delete that assignment without corrupting other slots on that date.
- Duplicate officer validation must catch if the same officer is assigned twice on the same date.
- Auto-scheduler must skip weekends (Saturday and Sunday) and balance slot assignments fairly without assigning an officer twice on the same day.
- Single-slot deletions must continue to work safely.

---

### Task 1: 4-Slot Daily Matrix Logic & Duplicate Detection Unit Tests

**Files:**
- Create: `app/Services/JadwalPiketService.php`
- Create: `tests/Unit/JadwalPiketServiceTest.php`

**Interfaces:**
- Consumes: `JadwalPiket`, `PegawaiPst`, Carbon.
- Produces: 
  - `JadwalPiketService::getDailySlots(string $date): array`
  - `JadwalPiketService::saveDailySlots(string $date, array $slots): array`
  - `JadwalPiketService::generateMonthlyRotation(int $year, int $month, array $officerIds, bool $overwriteExisting = false): int`

- [ ] **Step 1: Write the failing test**
Create `tests/Unit/JadwalPiketServiceTest.php` testing slot retrieval, saving 4 slots with duplicate prevention, and auto-rotation generation skipping weekends.

- [ ] **Step 2: Run test to verify it fails**
Run: `php artisan test --filter=JadwalPiketServiceTest`
Expected: FAIL (class does not exist).

- [ ] **Step 3: Implement `JadwalPiketService`**
Write methods:
- `getDailySlots($date)`: returns `['pagi_pelayanan' => id|null, 'pagi_pengaduan' => id|null, 'siang_pelayanan' => id|null, 'siang_pengaduan' => id|null]`
- `saveDailySlots($date, $slots)`: validates uniqueness among non-null IDs; updates, creates, or deletes records per role.
- `generateMonthlyRotation($year, $month, $officerIds, $overwrite)`: iterates through weekdays, rotates available officers into 4 slots, respects existing records if `$overwrite` is false.

- [ ] **Step 4: Run test to verify it passes**
Run: `php artisan test --filter=JadwalPiketServiceTest`
Expected: PASS.

- [ ] **Step 5: Commit**
```bash
git add app/Services/JadwalPiketService.php tests/Unit/JadwalPiketServiceTest.php
git commit -m "feat(piket): tambahkan JadwalPiketService untuk manajemen 4-slot matriks dan auto-rotasi"
```

---

### Task 2: Upgrade Calendar Modal to 4-Slot Daily Matrix & Add Auto-Scheduler Action

**Files:**
- Modify: `app/Filament/Widgets/JadwalPiketCalendarWidget.php`
- Modify: `resources/views/filament/widgets/jadwal-piket-calendar-widget.blade.php`
- Create: `tests/Feature/JadwalPiketCalendarMatrixTest.php`

**Interfaces:**
- Consumes: `JadwalPiketService`, `addJadwalAction`, `generateRotationAction`.
- Produces: Modal form with two distinct shift sections (Pagi & Siang) and 4 direct dropdowns; Auto-generate modal action.

- [ ] **Step 1: Write the failing test**
Create `tests/Feature/JadwalPiketCalendarMatrixTest.php` asserting that `JadwalPiketCalendarWidget` has the upgraded action and successfully saves 4 slots from modal data.

- [ ] **Step 2: Run test to verify it fails**
Run: `php artisan test --filter=JadwalPiketCalendarMatrixTest`
Expected: FAIL.

- [ ] **Step 3: Update `JadwalPiketCalendarWidget.php` and its Blade view**
- In `addJadwalAction`: Change form to 2 sections (☀️ Shift Pagi and 🌤️ Shift Siang), each containing `Select` for Pelayanan and Pengaduan. Pre-fill existing data from `JadwalPiketService::getDailySlots`.
- Add `generateRotationAction`: Header action allowing admin to choose month, year, and select active officers to auto-populate weekdays.
- In `jadwal-piket-calendar-widget.blade.php`: Add "Generate Rotasi Otomatis" button in header; format daily cell badges grouped cleanly by Pagi and Siang.

- [ ] **Step 4: Run test to verify it passes**
Run: `php artisan test --filter=JadwalPiketCalendarMatrixTest`
Expected: PASS.

- [ ] **Step 5: Commit**
```bash
git add app/Filament/Widgets/JadwalPiketCalendarWidget.php resources/views/filament/widgets/jadwal-piket-calendar-widget.blade.php tests/Feature/JadwalPiketCalendarMatrixTest.php
git commit -m "feat(piket): implementasi form matriks 4-slot dan auto-scheduler pada kalender piket"
```

---

### Task 3: Upgrade Standalone `CreateJadwalPiket` Form to 4-Slot Matrix

**Files:**
- Modify: `app/Filament/Resources/JadwalPikets/Schemas/JadwalPiketForm.php`
- Modify: `app/Filament/Resources/JadwalPikets/Pages/CreateJadwalPiket.php`
- Test: `tests/Feature/CreateJadwalPiketPageTest.php`

**Interfaces:**
- Consumes: `/admin/jadwal-pikets/create`.
- Produces: Page form with DatePicker and 4 slot dropdowns that creates/updates up to 4 rows in `jadwal_pikets`.

- [ ] **Step 1: Write the failing test**
Create `tests/Feature/CreateJadwalPiketPageTest.php` asserting that submitting the create page with a date and 4 selected officers creates the 4 records.

- [ ] **Step 2: Run test to verify it fails**
Run: `php artisan test --filter=CreateJadwalPiketPageTest`
Expected: FAIL.

- [ ] **Step 3: Implement matrix schema in `JadwalPiketForm.php` and handler in `CreateJadwalPiket.php`**
- In `JadwalPiketForm`: Define `tanggal` DatePicker followed by 2 Grid columns (Shift Pagi: Pelayanan & Pengaduan, Shift Siang: Pelayanan & Pengaduan).
- In `CreateJadwalPiket`: Override `handleRecordCreation(array $data)` to use `JadwalPiketService::saveDailySlots($data['tanggal'], ...)`. Redirect to list page with success notification.

- [ ] **Step 4: Run test to verify it passes**
Run: `php artisan test --filter=CreateJadwalPiketPageTest`
Expected: PASS.

- [ ] **Step 5: Commit**
```bash
git add app/Filament/Resources/JadwalPikets/Schemas/JadwalPiketForm.php app/Filament/Resources/JadwalPikets/Pages/CreateJadwalPiket.php tests/Feature/CreateJadwalPiketPageTest.php
git commit -m "feat(piket): upgrade form create jadwal piket ke matriks 4-slot harian"
```

---

### Task 4: Full Verification, Changelog Update, Git Push, and Remote Deployment

**Files:**
- Modify: `README.md`

- [ ] **Step 1: Run full test suite**
Run: `php artisan test`
Expected: All tests PASS.

- [ ] **Step 2: Update Changelog in `README.md`**
Document the new 4-slot matrix form, auto-rotation generator, and improved calendar layout under `### 2026-11-20`.

- [ ] **Step 3: Commit and Push**
```bash
git add README.md
git commit -m "docs: update changelog dengan fitur form matriks 4-slot dan generator rotasi piket"
git push origin main
```

- [ ] **Step 4: Deploy to Remote Server via SSH**
Execute: `ssh bpsdemak@10.133.21.24 "cd apps/siputri && ./deploy.sh"`
Verify deployment completes with code 0 and application is live.

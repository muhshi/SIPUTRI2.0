<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     * SQLite does not support ALTER TABLE DROP FOREIGN KEY,
     * so we recreate the table with the correct FK.
     */
    public function up(): void
    {
        DB::statement('PRAGMA foreign_keys = OFF;');

        // 1. Create new table with correct FK to pegawai_psts
        DB::statement('
            CREATE TABLE presensis_new (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                pegawai_id INTEGER NOT NULL,
                tanggal DATE NOT NULL,
                jam_masuk TIME NULL,
                jam_selesai TIME NULL,
                status VARCHAR NOT NULL DEFAULT "Hadir",
                foto_masuk VARCHAR NULL,
                foto_keluar VARCHAR NULL,
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL,
                FOREIGN KEY (pegawai_id) REFERENCES pegawai_psts(id) ON DELETE CASCADE
            )
        ');

        // 2. Copy existing data
        DB::statement('
            INSERT INTO presensis_new
            SELECT id, pegawai_id, tanggal, jam_masuk, jam_selesai, status, foto_masuk, foto_keluar, created_at, updated_at
            FROM presensis
        ');

        // 3. Drop old table and rename new one
        DB::statement('DROP TABLE presensis');
        DB::statement('ALTER TABLE presensis_new RENAME TO presensis');

        DB::statement('PRAGMA foreign_keys = ON;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('PRAGMA foreign_keys = OFF;');

        DB::statement('
            CREATE TABLE presensis_new (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                pegawai_id INTEGER NOT NULL,
                tanggal DATE NOT NULL,
                jam_masuk TIME NULL,
                jam_selesai TIME NULL,
                status VARCHAR NOT NULL DEFAULT "Hadir",
                foto_masuk VARCHAR NULL,
                foto_keluar VARCHAR NULL,
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL,
                FOREIGN KEY (pegawai_id) REFERENCES pegawais(id) ON DELETE CASCADE
            )
        ');

        DB::statement('
            INSERT INTO presensis_new
            SELECT id, pegawai_id, tanggal, jam_masuk, jam_selesai, status, foto_masuk, foto_keluar, created_at, updated_at
            FROM presensis
        ');

        DB::statement('DROP TABLE presensis');
        DB::statement('ALTER TABLE presensis_new RENAME TO presensis');

        DB::statement('PRAGMA foreign_keys = ON;');
    }
};

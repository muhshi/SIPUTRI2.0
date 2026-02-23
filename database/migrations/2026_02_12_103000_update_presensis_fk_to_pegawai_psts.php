<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     * Update FK on presensis.pegawai_id from pegawais → pegawai_psts.
     * Works on both SQLite and MySQL.
     */
    public function up(): void
    {
        $driver = DB::connection()->getDriverName();

        if ($driver === 'sqlite') {
            // SQLite doesn't support ALTER TABLE DROP FOREIGN KEY,
            // so we recreate the table with the correct FK.
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
                    FOREIGN KEY (pegawai_id) REFERENCES pegawai_psts(id) ON DELETE CASCADE
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
        } else {
            // MySQL / MariaDB / PostgreSQL — use standard Schema Builder
            Schema::table('presensis', function (Blueprint $table) {
                $table->dropForeign(['pegawai_id']);
            });

            Schema::table('presensis', function (Blueprint $table) {
                $table->foreign('pegawai_id')
                    ->references('id')
                    ->on('pegawai_psts')
                    ->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $driver = DB::connection()->getDriverName();

        if ($driver === 'sqlite') {
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
            DB::statement('ALTER TABLE presenis_new RENAME TO presensis');

            DB::statement('PRAGMA foreign_keys = ON;');
        } else {
            Schema::table('presensis', function (Blueprint $table) {
                $table->dropForeign(['pegawai_id']);
            });

            Schema::table('presensis', function (Blueprint $table) {
                $table->foreign('pegawai_id')
                    ->references('id')
                    ->on('pegawais')
                    ->onDelete('cascade');
            });
        }
    }
};

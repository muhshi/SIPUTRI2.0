<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('presensis', function (Blueprint $table) {
            if (!Schema::hasIndex('presensis', ['tanggal', 'pegawai_id'])) {
                $table->index(['tanggal', 'pegawai_id']);
            }
        });

        Schema::table('jadwal_pikets', function (Blueprint $table) {
            if (!Schema::hasIndex('jadwal_pikets', ['tanggal', 'is_notified'])) {
                $table->index(['tanggal', 'is_notified']);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('presensis', function (Blueprint $table) {
            if (Schema::hasIndex('presensis', ['tanggal', 'pegawai_id'])) {
                $table->dropIndex(['tanggal', 'pegawai_id']);
            }
        });

        Schema::table('jadwal_pikets', function (Blueprint $table) {
            if (Schema::hasIndex('jadwal_pikets', ['tanggal', 'is_notified'])) {
                $table->dropIndex(['tanggal', 'is_notified']);
            }
        });
    }
};

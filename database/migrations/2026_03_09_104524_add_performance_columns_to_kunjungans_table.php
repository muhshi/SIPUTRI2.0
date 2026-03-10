<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('kunjungans', function (Blueprint $table) {
            $table->foreignId('pegawai_id')->nullable()->after('id')->constrained('pegawai_psts')->onDelete('set null');
            $table->timestamp('waktu_mulai')->nullable()->after('tanggal');
            $table->timestamp('waktu_selesai')->nullable()->after('waktu_mulai');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kunjungans', function (Blueprint $table) {
            $table->dropForeign(['pegawai_id']);
            $table->dropColumn(['pegawai_id', 'waktu_mulai', 'waktu_selesai']);
        });
    }
};

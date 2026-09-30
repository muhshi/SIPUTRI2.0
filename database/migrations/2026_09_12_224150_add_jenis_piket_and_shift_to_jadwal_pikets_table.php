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
        Schema::table('jadwal_pikets', function (Blueprint $table) {
            $table->string('jenis_piket')->default('Pelayanan')->after('tanggal');
            $table->string('shift')->default('Pagi')->after('jenis_piket');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jadwal_pikets', function (Blueprint $table) {
            $table->dropColumn(['jenis_piket', 'shift']);
        });
    }
};

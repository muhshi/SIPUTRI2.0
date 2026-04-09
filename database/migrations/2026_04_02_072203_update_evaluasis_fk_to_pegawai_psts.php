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
        Schema::table('evaluasis', function (Blueprint $table) {
            // Hapus constraint lama
            $table->dropForeign('evaluasis_pegawai_id_foreign');
            
            // Tambahkan constraint baru yang mengarah ke table pegawai_psts
            $table->foreign('pegawai_id')
                  ->references('id')
                  ->on('pegawai_psts')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evaluasis', function (Blueprint $table) {
            // Hapus constraint baru
            $table->dropForeign(['pegawai_id']);
            
            // Kembalikan constraint lama
            $table->foreign('pegawai_id')
                  ->references('id')
                  ->on('pegawais')
                  ->onDelete('cascade');
        });
    }
};

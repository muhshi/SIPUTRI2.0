<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pegawai_psts', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->nullOnDelete();
        });

        // Hubungkan data pegawai_psts yang sudah ada dengan user jika NIP cocok
        try {
            $users = DB::table('users')->whereNotNull('nip')->get();
            foreach ($users as $user) {
                DB::table('pegawai_psts')
                    ->where('nip', $user->nip)
                    ->whereNull('user_id')
                    ->update(['user_id' => $user->id]);
            }
        } catch (\Throwable $e) {
            // Abaikan jika tabel atau data belum siap saat migrasi awal
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pegawai_psts', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
        });
    }
};

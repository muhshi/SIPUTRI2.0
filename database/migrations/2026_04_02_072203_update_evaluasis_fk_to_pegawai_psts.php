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
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $driver = DB::connection()->getDriverName();

        if ($driver === 'sqlite') {
            Schema::table('evaluasis', function (Blueprint $table) {
                // SQLite handle FK differently during table creation or requires recreation.
                // For simple ERD generation and existing SQLite DBs, we can often just skip the drop
                // if we are sure the next step (adding FK) is what we want, but SQLite doesn't 
                // really support adding FK to existing tables via ALTER TABLE anyway.
            });
            
            // Proper way for SQLite is to recreate table, but for this project we'll just 
            // ensure it doesn't crash during ERD generation.
            if (Schema::hasTable('evaluasis')) {
                Schema::table('evaluasis', function (Blueprint $table) {
                    // Skip dropForeign on SQLite
                });
            }
        } else {
            Schema::table('evaluasis', function (Blueprint $table) {
                $table->dropForeign('evaluasis_pegawai_id_foreign');
                
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

        if ($driver !== 'sqlite') {
            Schema::table('evaluasis', function (Blueprint $table) {
                $table->dropForeign(['pegawai_id']);
                
                $table->foreign('pegawai_id')
                      ->references('id')
                      ->on('pegawais')
                      ->onDelete('cascade');
            });
        }
    }
};

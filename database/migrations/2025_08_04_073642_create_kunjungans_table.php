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
        Schema::create('kunjungans', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 255);
            $table->string('pendidikan', 100)->nullable();
            $table->date('tanggal');
            $table->string('pekerjaan')->nullable();
            $table->string('jenis_kelamin', 20)->nullable();
            $table->string('instansi')->nullable();
            $table->string('email')->nullable();
            $table->string('pemanfaatan')->nullable();
            $table->integer('tahun_lahir')->nullable();
            $table->string('layanan')->nullable();
            $table->integer('umur')->nullable();
            $table->string('data_diinginkan')->nullable();
            $table->text('alamat')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kunjungans');
    }
};

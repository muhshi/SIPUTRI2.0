<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class DatabaseIndexesTest extends TestCase
{
    use RefreshDatabase;

    public function test_presensis_table_has_composite_index_on_tanggal_and_pegawai_id(): void
    {
        $this->assertTrue(
            Schema::hasIndex('presensis', ['tanggal', 'pegawai_id']),
            'Tabel presensis harus memiliki index pada kolom [tanggal, pegawai_id]'
        );
    }

    public function test_jadwal_pikets_table_has_composite_index_on_tanggal_and_is_notified(): void
    {
        $this->assertTrue(
            Schema::hasIndex('jadwal_pikets', ['tanggal', 'is_notified']),
            'Tabel jadwal_pikets harus memiliki index pada kolom [tanggal, is_notified]'
        );
    }
}

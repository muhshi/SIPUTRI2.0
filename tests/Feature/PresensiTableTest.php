<?php

namespace Tests\Feature;

use App\Filament\Resources\Presensis\Pages\ListPresensis;
use App\Filament\Resources\Presensis\PresensiResource;
use App\Filament\Resources\Presensis\Tables\PresensisTable;
use App\Models\PegawaiPst;
use App\Models\Presensi;
use App\Models\User;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PresensiTableTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $admin = User::factory()->create([
            'email' => 'admin@bps.go.id',
            'is_admin' => true,
        ]);
        $this->actingAs($admin);
    }

    public function test_presensi_table_has_default_sort_descending_by_tanggal(): void
    {
        $page = new ListPresensis();
        $table = PresensisTable::configure(Table::make($page));

        $this->assertEquals('tanggal', $table->getDefaultSortColumn());
        $this->assertEquals('desc', $table->getDefaultSortDirection());
    }

    public function test_presensi_table_has_filter_by_pegawai(): void
    {
        $page = new ListPresensis();
        $table = PresensisTable::configure(Table::make($page));

        $filter = $table->getFilter('pegawai');
        $this->assertInstanceOf(SelectFilter::class, $filter);
        $this->assertEquals('Pegawai', $filter->getLabel());
        $this->assertEquals('pegawai', $filter->getRelationshipName());
        $this->assertEquals('nama_pegawai', $filter->getRelationshipTitleAttribute());
    }
}

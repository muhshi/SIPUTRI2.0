<x-filament-widgets::widget>
    <x-filament::card>
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem;">
            <div style="display: flex; gap: 0.5rem; align-items: center;">
                <select wire:model.live="currentMonth" style="font-size: 1.25rem; font-weight: bold; border: 1px solid #e5e7eb; border-radius: 0.375rem; padding: 0.25rem 2rem 0.25rem 0.5rem; background-color: transparent; cursor: pointer; appearance: none; background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%23111827%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E'); background-repeat: no-repeat; background-position: right 0.7rem top 50%; background-size: 0.65rem auto;">
                    @foreach(range(1, 12) as $m)
                        <option value="{{ $m }}">{{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}</option>
                    @endforeach
                </select>
                <select wire:model.live="currentYear" style="font-size: 1.25rem; font-weight: bold; border: 1px solid #e5e7eb; border-radius: 0.375rem; padding: 0.25rem 2rem 0.25rem 0.5rem; background-color: transparent; cursor: pointer; appearance: none; background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%23111827%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E'); background-repeat: no-repeat; background-position: right 0.7rem top 50%; background-size: 0.65rem auto;">
                    @foreach(range(2020, 2035) as $y)
                        <option value="{{ $y }}">{{ $y }}</option>
                    @endforeach
                </select>
            </div>
            <div style="display: flex; gap: 0.5rem;">
                <!-- Tombol Edit Mode -->
                @if($isEditMode)
                    <x-filament::button wire:click="toggleEditMode" color="success" size="sm">
                        Simpan (Selesai Edit)
                    </x-filament::button>
                @else
                    <x-filament::button wire:click="toggleEditMode" color="danger" size="sm" icon="heroicon-o-pencil-square">
                        Edit
                    </x-filament::button>
                @endif

                <x-filament::button wire:click="previousMonth" color="gray" size="sm">
                    &laquo; Bulan Sebelumnya
                </x-filament::button>
                <x-filament::button wire:click="nextMonth" color="gray" size="sm">
                    Bulan Berikutnya &raquo;
                </x-filament::button>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(7, minmax(0, 1fr)); gap: 4px; text-align: center; font-size: 0.875rem; font-weight: 600; color: #6b7280; margin-bottom: 0.5rem;">
            <div>Min</div><div>Sen</div><div>Sel</div><div>Rab</div><div>Kam</div><div>Jum</div><div>Sab</div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(7, minmax(0, 1fr)); gap: 4px;">
            @foreach ($days as $day)
                <div style="min-height: 120px; border: 1px solid #e5e7eb; border-radius: 0.5rem; padding: 0.5rem; position: relative; {{ $day['isCurrentMonth'] ? 'background-color: #ffffff;' : 'background-color: #f9fafb;' }}">
                    <div style="text-align: right; font-size: 0.875rem; font-weight: 500; {{ $day['isCurrentMonth'] ? 'color: #111827;' : 'color: #9ca3af;' }}">
                        {{ $day['date']->format('j') }}
                    </div>
                    
                    <div style="margin-top: 0.5rem; display: flex; flex-direction: column; gap: 4px;">
                        @foreach ($day['jadwals'] as $jadwal)
                            @php
                                $bgColor = '';
                                $textColor = '';
                                if ($jadwal->jenis_piket == 'Pelayanan') {
                                    $bgColor = $jadwal->shift == 'Pagi' ? '#F4D03F' : '#D5F5E3';
                                    $textColor = '#000000';
                                } else {
                                    $bgColor = $jadwal->shift == 'Pagi' ? '#FCF3CF' : '#3498DB';
                                    $textColor = $jadwal->shift == 'Pagi' ? '#000000' : '#ffffff';
                                }
                            @endphp
                            <div style="position: relative; font-size: 0.75rem; padding: 4px 6px; border-radius: 0.25rem; background-color: {{ $bgColor }}; color: {{ $textColor }}; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);">
                                <div style="font-weight: bold; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; padding-right: {{ $isEditMode ? '20px' : '0' }};">{{ $jadwal->pegawaiPst->nama_pegawai ?? 'Unknown' }}</div>
                                <div style="font-size: 10px; opacity: 0.8;">{{ $jadwal->jenis_piket }} ({{ $jadwal->shift }})</div>
                                
                                @if($isEditMode)
                                    <button 
                                        wire:click="mountAction('deleteJadwal', { id: {{ $jadwal->id }} })"
                                        style="position: absolute; right: 4px; top: 50%; transform: translateY(-50%); color: red; background: white; border-radius: 50%; width: 16px; height: 16px; display: flex; align-items: center; justify-content: center; font-size: 10px; font-weight: bold; cursor: pointer; border: 1px solid red;"
                                    >
                                        x
                                    </button>
                                @endif
                            </div>
                        @endforeach
                        
                        @if($isEditMode && !$day['isFull'])
                            <button 
                                wire:click="mountAction('addJadwal', { tanggal: '{{ $day['dateString'] }}' })"
                                style="margin-top: 4px; display: flex; align-items: center; justify-content: center; padding: 4px; border: 2px dashed #f87171; border-radius: 0.25rem; color: #ef4444; cursor: pointer; background: transparent; transition: all 0.2s;"
                                onmouseover="this.style.backgroundColor='#fee2e2'"
                                onmouseout="this.style.backgroundColor='transparent'"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" style="width: 20px; height: 20px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
        
        <x-filament-actions::modals />
    </x-filament::card>
</x-filament-widgets::widget>

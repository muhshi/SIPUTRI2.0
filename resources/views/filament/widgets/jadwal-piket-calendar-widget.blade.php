<x-filament-widgets::widget>
    <x-filament::card>
        <!-- Header Navigasi & Aksi -->
        <div class="flex flex-col md:flex-row items-center justify-between gap-4 mb-4 pb-4 border-b border-gray-100 dark:border-gray-800">
            <div class="flex items-center gap-2">
                <select wire:model.live="currentMonth" class="text-lg font-bold rounded-lg border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-3 py-1.5 focus:ring-primary-500">
                    @foreach(range(1, 12) as $m)
                        <option value="{{ $m }}">{{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}</option>
                    @endforeach
                </select>
                <select wire:model.live="currentYear" class="text-lg font-bold rounded-lg border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-3 py-1.5 focus:ring-primary-500">
                    @foreach(range(2025, 2035) as $y)
                        <option value="{{ $y }}">{{ $y }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <!-- Tombol Generate Rotasi Otomatis -->
                <x-filament::button
                    wire:click="mountAction('generateRotation')"
                    color="primary"
                    size="sm"
                    icon="heroicon-o-sparkles"
                >
                    Rotasi Otomatis (Generate)
                </x-filament::button>

                <!-- Tombol Edit Mode -->
                @if($isEditMode)
                    <x-filament::button wire:click="toggleEditMode" color="success" size="sm" icon="heroicon-o-check">
                        Selesai Edit
                    </x-filament::button>
                @else
                    <x-filament::button wire:click="toggleEditMode" color="warning" size="sm" icon="heroicon-o-pencil-square">
                        Mode Edit
                    </x-filament::button>
                @endif

                <x-filament::button wire:click="previousMonth" color="gray" size="sm">
                    &laquo; Bulan Lalu
                </x-filament::button>
                <x-filament::button wire:click="nextMonth" color="gray" size="sm">
                    Bulan Depan &raquo;
                </x-filament::button>
            </div>
        </div>

        <!-- Legend Shift -->
        <div class="flex flex-wrap items-center justify-between text-xs text-gray-500 dark:text-gray-400 mb-3 px-1 gap-2">
            <div class="flex items-center gap-4">
                <span class="flex items-center gap-1.5">
                    <span class="w-3 h-3 rounded-full bg-emerald-500"></span> Pelayanan (Pagi/Siang)
                </span>
                <span class="flex items-center gap-1.5">
                    <span class="w-3 h-3 rounded-full bg-amber-500"></span> Pengaduan/Disabilitas (Pagi/Siang)
                </span>
            </div>
            <div>
                <span class="text-primary-600 dark:text-primary-400 font-medium">Klik pada tanggal / tombol edit untuk mengatur 4 slot harian</span>
            </div>
        </div>

        <!-- Header Hari -->
        <div class="grid grid-cols-7 gap-1.5 text-center text-xs font-bold text-gray-600 dark:text-gray-300 mb-2">
            <div class="py-1 text-red-500">Min</div>
            <div class="py-1">Sen</div>
            <div class="py-1">Sel</div>
            <div class="py-1">Rab</div>
            <div class="py-1">Kam</div>
            <div class="py-1">Jum</div>
            <div class="py-1 text-red-500">Sab</div>
        </div>

        <!-- Grid Kalender -->
        <div class="grid grid-cols-7 gap-1.5">
            @foreach ($days as $day)
                @php
                    $isWeekend = $day['isWeekend'];
                    $isCurrentMonth = $day['isCurrentMonth'];
                    $slots = $day['slots'];
                @endphp
                <div class="min-h-[140px] rounded-xl border border-gray-200 dark:border-gray-800 p-2 flex flex-col justify-between transition-all {{ $isCurrentMonth ? ($isWeekend ? 'bg-gray-50/60 dark:bg-gray-900/40' : 'bg-white dark:bg-gray-900 shadow-2xs') : 'bg-gray-50/30 dark:bg-gray-950/20 opacity-40' }}">
                    <!-- Header Cell Tanggal -->
                    <div class="flex items-center justify-between mb-1.5">
                        <span class="text-xs font-bold {{ $isWeekend ? 'text-red-500' : ($isCurrentMonth ? 'text-gray-900 dark:text-white' : 'text-gray-400') }}">
                            {{ $day['date']->format('j') }}
                        </span>

                        @if($isEditMode || $day['filledCount'] > 0)
                            <button
                                wire:click="mountAction('addJadwal', { tanggal: '{{ $day['dateString'] }}' })"
                                class="text-[10px] text-primary-600 hover:text-primary-700 dark:text-primary-400 font-semibold flex items-center gap-0.5 hover:underline"
                                title="Atur 4 Slot Piket"
                            >
                                <x-filament::icon icon="heroicon-m-pencil" class="w-3 h-3" />
                                <span>{{ $day['filledCount'] }}/4</span>
                            </button>
                        @endif
                    </div>

                    <!-- Slot Kontainer -->
                    <div class="space-y-1.5 flex-1 flex flex-col justify-center">
                        @if($day['filledCount'] === 0 && !$isWeekend && $isEditMode)
                            <button
                                wire:click="mountAction('addJadwal', { tanggal: '{{ $day['dateString'] }}' })"
                                class="w-full py-3 border border-dashed border-gray-300 dark:border-gray-700 rounded-lg text-[11px] text-gray-400 hover:text-primary-600 hover:border-primary-400 flex flex-col items-center justify-center gap-1 transition-all"
                            >
                                <x-filament::icon icon="heroicon-o-plus" class="w-4 h-4" />
                                <span>Isi 4 Slot</span>
                            </button>
                        @elseif($day['filledCount'] === 0 && $isWeekend)
                            <div class="text-center py-4 text-[10px] text-gray-400 italic">Libur</div>
                        @else
                            <!-- Shift Pagi -->
                            <div class="rounded-md bg-gray-50 dark:bg-gray-800/60 p-1 border border-gray-100 dark:border-gray-800">
                                <div class="text-[9px] font-bold text-gray-500 uppercase tracking-wider mb-0.5 flex items-center gap-1">
                                    <span>☀️ Pagi</span>
                                </div>
                                <div class="space-y-0.5">
                                    <!-- Pelayanan Pagi -->
                                    @if($slots['pagi_pelayanan'])
                                        <div class="text-[10px] bg-emerald-50 text-emerald-900 border border-emerald-200 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-800 px-1.5 py-0.5 rounded flex items-center justify-between">
                                            <span class="truncate font-medium" title="Pelayanan Pagi">{{ $slots['pagi_pelayanan']->pegawaiPst->nama_pegawai ?? '-' }}</span>
                                            @if($isEditMode)
                                                <button wire:click="mountAction('deleteJadwal', { id: {{ $slots['pagi_pelayanan']->id }} })" class="text-red-500 hover:text-red-700 ml-1 font-bold">&times;</button>
                                            @endif
                                        </div>
                                    @endif

                                    <!-- Pengaduan Pagi -->
                                    @if($slots['pagi_pengaduan'])
                                        <div class="text-[10px] bg-amber-50 text-amber-900 border border-amber-200 dark:bg-amber-950/40 dark:text-amber-300 dark:border-amber-800 px-1.5 py-0.5 rounded flex items-center justify-between">
                                            <span class="truncate font-medium" title="Pengaduan Pagi">{{ $slots['pagi_pengaduan']->pegawaiPst->nama_pegawai ?? '-' }}</span>
                                            @if($isEditMode)
                                                <button wire:click="mountAction('deleteJadwal', { id: {{ $slots['pagi_pengaduan']->id }} })" class="text-red-500 hover:text-red-700 ml-1 font-bold">&times;</button>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Shift Siang -->
                            <div class="rounded-md bg-gray-50 dark:bg-gray-800/60 p-1 border border-gray-100 dark:border-gray-800">
                                <div class="text-[9px] font-bold text-gray-500 uppercase tracking-wider mb-0.5 flex items-center gap-1">
                                    <span>🌤️ Siang</span>
                                </div>
                                <div class="space-y-0.5">
                                    <!-- Pelayanan Siang -->
                                    @if($slots['siang_pelayanan'])
                                        <div class="text-[10px] bg-emerald-50 text-emerald-900 border border-emerald-200 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-800 px-1.5 py-0.5 rounded flex items-center justify-between">
                                            <span class="truncate font-medium" title="Pelayanan Siang">{{ $slots['siang_pelayanan']->pegawaiPst->nama_pegawai ?? '-' }}</span>
                                            @if($isEditMode)
                                                <button wire:click="mountAction('deleteJadwal', { id: {{ $slots['siang_pelayanan']->id }} })" class="text-red-500 hover:text-red-700 ml-1 font-bold">&times;</button>
                                            @endif
                                        </div>
                                    @endif

                                    <!-- Pengaduan Siang -->
                                    @if($slots['siang_pengaduan'])
                                        <div class="text-[10px] bg-amber-50 text-amber-900 border border-amber-200 dark:bg-amber-950/40 dark:text-amber-300 dark:border-amber-800 px-1.5 py-0.5 rounded flex items-center justify-between">
                                            <span class="truncate font-medium" title="Pengaduan Siang">{{ $slots['siang_pengaduan']->pegawaiPst->nama_pegawai ?? '-' }}</span>
                                            @if($isEditMode)
                                                <button wire:click="mountAction('deleteJadwal', { id: {{ $slots['siang_pengaduan']->id }} })" class="text-red-500 hover:text-red-700 ml-1 font-bold">&times;</button>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <x-filament-actions::modals />
    </x-filament::card>
</x-filament-widgets::widget>

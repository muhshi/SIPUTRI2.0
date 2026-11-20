<x-filament-widgets::widget>
    <x-filament::card>
        <style>
            .sp-cal-wrapper {
                font-family: inherit;
                width: 100%;
            }
            .sp-cal-header {
                display: flex;
                flex-direction: row;
                flex-wrap: wrap;
                align-items: center;
                justify-content: space-between;
                gap: 0.75rem;
                margin-bottom: 1rem;
                padding-bottom: 0.75rem;
                border-bottom: 1px solid #e5e7eb;
            }
            .sp-cal-selects {
                display: flex;
                align-items: center;
                gap: 0.5rem;
            }
            .sp-cal-select {
                font-size: 1rem;
                font-weight: 700;
                padding: 0.375rem 0.75rem;
                border-radius: 0.5rem;
                border: 1px solid #d1d5db;
                background-color: #ffffff;
                color: #111827;
                cursor: pointer;
            }
            .sp-cal-actions {
                display: flex;
                flex-wrap: wrap;
                align-items: center;
                gap: 0.5rem;
            }
            .sp-cal-legend {
                display: flex;
                flex-wrap: wrap;
                align-items: center;
                justify-content: space-between;
                font-size: 0.75rem;
                color: #6b7280;
                margin-bottom: 0.75rem;
                gap: 0.5rem;
            }
            .sp-cal-legend-items {
                display: flex;
                align-items: center;
                gap: 1rem;
                flex-wrap: wrap;
            }
            .sp-cal-dot {
                display: inline-block;
                width: 10px;
                height: 10px;
                border-radius: 50%;
                margin-right: 4px;
            }
            .sp-cal-dot-pelayanan {
                background-color: #10b981;
            }
            .sp-cal-dot-pengaduan {
                background-color: #f59e0b;
            }
            .sp-cal-scroll-area {
                width: 100%;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
                padding-bottom: 0.5rem;
            }
            .sp-cal-grid-canvas {
                min-width: 680px;
                width: 100%;
            }
            .sp-cal-weekdays {
                display: grid;
                grid-template-columns: repeat(7, minmax(0, 1fr));
                gap: 6px;
                text-align: center;
                font-size: 0.8125rem;
                font-weight: 700;
                color: #4b5563;
                margin-bottom: 0.5rem;
            }
            .sp-cal-weekday-item {
                padding: 0.35rem 0;
            }
            .sp-cal-weekday-item.weekend {
                color: #ef4444;
            }
            .sp-cal-days-grid {
                display: grid;
                grid-template-columns: repeat(7, minmax(0, 1fr));
                gap: 6px;
            }
            .sp-cal-cell {
                min-height: 145px;
                border-radius: 0.625rem;
                border: 1px solid #e5e7eb;
                padding: 0.5rem;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                background-color: #ffffff;
                box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
                transition: all 0.15s ease;
            }
            .sp-cal-cell.is-weekend {
                background-color: #fbfbfb;
            }
            .sp-cal-cell.not-current-month {
                background-color: #f9fafb;
                opacity: 0.45;
            }
            .sp-cal-cell:hover {
                border-color: #93c5fd;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.08);
            }
            .sp-cal-cell-top {
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-bottom: 0.375rem;
            }
            .sp-cal-day-number {
                font-size: 0.8125rem;
                font-weight: 700;
                color: #111827;
            }
            .sp-cal-day-number.weekend {
                color: #ef4444;
            }
            .sp-cal-edit-trigger {
                font-size: 0.6875rem;
                font-weight: 600;
                color: #2563eb;
                background: transparent;
                border: none;
                cursor: pointer;
                display: flex;
                align-items: center;
                gap: 2px;
                padding: 1px 4px;
                border-radius: 4px;
            }
            .sp-cal-edit-trigger:hover {
                background-color: #eff6ff;
                text-decoration: underline;
            }
            .sp-cal-slots-wrapper {
                display: flex;
                flex-direction: column;
                gap: 0.35rem;
                flex-grow: 1;
                justify-content: center;
            }
            .sp-cal-empty-state {
                width: 100%;
                padding: 0.625rem 0.25rem;
                border: 1px dashed #cbd5e1;
                border-radius: 0.5rem;
                background-color: #f8fafc;
                color: #64748b;
                font-size: 0.6875rem;
                font-weight: 600;
                cursor: pointer;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                gap: 0.25rem;
                transition: all 0.15s ease;
            }
            .sp-cal-empty-state:hover {
                border-color: #3b82f6;
                color: #2563eb;
                background-color: #eff6ff;
            }
            .sp-cal-holiday-state {
                text-align: center;
                padding: 0.75rem 0;
                font-size: 0.6875rem;
                color: #94a3b8;
                font-style: italic;
            }
            .sp-cal-shift-block {
                background-color: #f8fafc;
                border: 1px solid #e2e8f0;
                border-radius: 0.375rem;
                padding: 0.25rem 0.375rem;
            }
            .sp-cal-shift-header {
                font-size: 0.625rem;
                font-weight: 800;
                text-transform: uppercase;
                color: #64748b;
                letter-spacing: 0.025em;
                margin-bottom: 0.2rem;
                display: flex;
                align-items: center;
                gap: 0.25rem;
            }
            .sp-cal-slot-row {
                display: flex;
                flex-direction: column;
                gap: 0.2rem;
            }
            .sp-cal-badge {
                font-size: 0.6875rem;
                padding: 0.2rem 0.375rem;
                border-radius: 0.25rem;
                display: flex;
                align-items: center;
                justify-content: space-between;
                line-height: 1.2;
            }
            .sp-cal-badge-pelayanan {
                background-color: #ecfdf5;
                color: #065f46;
                border: 1px solid #a7f3d0;
            }
            .sp-cal-badge-pengaduan {
                background-color: #fffbeb;
                color: #92400e;
                border: 1px solid #fde68a;
            }
            .sp-cal-badge-name {
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                font-weight: 600;
                max-width: 100%;
            }
            .sp-cal-delete-action {
                background: transparent;
                border: none;
                color: #ef4444;
                font-weight: bold;
                cursor: pointer;
                padding: 0 0.25rem;
                font-size: 0.8125rem;
                line-height: 1;
            }
            .sp-cal-delete-action:hover {
                color: #b91c1c;
            }
        </style>

        <div class="sp-cal-wrapper">
            <!-- Header Navigasi & Aksi -->
            <div class="sp-cal-header">
                <div class="sp-cal-selects">
                    <select wire:model.live="currentMonth" class="sp-cal-select">
                        @foreach(range(1, 12) as $m)
                            <option value="{{ $m }}">{{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}</option>
                        @endforeach
                    </select>
                    <select wire:model.live="currentYear" class="sp-cal-select">
                        @foreach(range(2025, 2035) as $y)
                            <option value="{{ $y }}">{{ $y }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="sp-cal-actions">
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
            <div class="sp-cal-legend">
                <div class="sp-cal-legend-items">
                    <span><span class="sp-cal-dot sp-cal-dot-pelayanan"></span> Pelayanan (Pagi/Siang)</span>
                    <span><span class="sp-cal-dot sp-cal-dot-pengaduan"></span> Pengaduan/Disabilitas (Pagi/Siang)</span>
                </div>
                <div>
                    <span style="color: #2563eb; font-weight: 600;">Klik pada tanggal / tombol edit untuk mengatur 4 slot harian</span>
                </div>
            </div>

            <!-- Area Scroll Horizontal untuk Mobile/Tablet -->
            <div class="sp-cal-scroll-area">
                <div class="sp-cal-grid-canvas">
                    <!-- Header Hari (Min - Sab) -->
                    <div class="sp-cal-weekdays">
                        <div class="sp-cal-weekday-item weekend">Min</div>
                        <div class="sp-cal-weekday-item">Sen</div>
                        <div class="sp-cal-weekday-item">Sel</div>
                        <div class="sp-cal-weekday-item">Rab</div>
                        <div class="sp-cal-weekday-item">Kam</div>
                        <div class="sp-cal-weekday-item">Jum</div>
                        <div class="sp-cal-weekday-item weekend">Sab</div>
                    </div>

                    <!-- Grid Kalender 7 Kolom -->
                    <div class="sp-cal-days-grid">
                        @foreach ($days as $day)
                            @php
                                $isWeekend = $day['isWeekend'];
                                $isCurrentMonth = $day['isCurrentMonth'];
                                $slots = $day['slots'];
                            @endphp
                            <div class="sp-cal-cell {{ $isWeekend ? 'is-weekend' : '' }} {{ !$isCurrentMonth ? 'not-current-month' : '' }}">
                                <!-- Header Cell Tanggal -->
                                <div class="sp-cal-cell-top">
                                    <span class="sp-cal-day-number {{ $isWeekend ? 'weekend' : '' }}">
                                        {{ $day['date']->format('j') }}
                                    </span>

                                    @if($isEditMode || $day['filledCount'] > 0)
                                        <button
                                            type="button"
                                            wire:click="mountAction('addJadwal', { tanggal: '{{ $day['dateString'] }}' })"
                                            class="sp-cal-edit-trigger"
                                            title="Atur 4 Slot Piket Tanggal {{ $day['dateString'] }}"
                                        >
                                            <x-filament::icon icon="heroicon-m-pencil" style="width: 12px; height: 12px;" />
                                            <span>{{ $day['filledCount'] }}/4</span>
                                        </button>
                                    @endif
                                </div>

                                <!-- Slot Kontainer -->
                                <div class="sp-cal-slots-wrapper">
                                    @if($day['filledCount'] === 0 && !$isWeekend && $isEditMode)
                                        <button
                                            type="button"
                                            wire:click="mountAction('addJadwal', { tanggal: '{{ $day['dateString'] }}' })"
                                            class="sp-cal-empty-state"
                                        >
                                            <x-filament::icon icon="heroicon-o-plus" style="width: 14px; height: 14px;" />
                                            <span>Isi 4 Slot</span>
                                        </button>
                                    @elseif($day['filledCount'] === 0 && $isWeekend)
                                        <div class="sp-cal-holiday-state">Libur</div>
                                    @else
                                        <!-- Shift Pagi -->
                                        <div class="sp-cal-shift-block">
                                            <div class="sp-cal-shift-header">
                                                <span>☀️ Pagi</span>
                                            </div>
                                            <div class="sp-cal-slot-row">
                                                <!-- Pelayanan Pagi -->
                                                @if($slots['pagi_pelayanan'])
                                                    <div class="sp-cal-badge sp-cal-badge-pelayanan" title="Pelayanan Pagi: {{ $slots['pagi_pelayanan']->pegawaiPst->nama_pegawai ?? '-' }}">
                                                        <span class="sp-cal-badge-name">{{ $slots['pagi_pelayanan']->pegawaiPst->nama_pegawai ?? '-' }}</span>
                                                        @if($isEditMode)
                                                            <button type="button" wire:click="mountAction('deleteJadwal', { id: {{ $slots['pagi_pelayanan']->id }} })" class="sp-cal-delete-action">&times;</button>
                                                        @endif
                                                    </div>
                                                @endif

                                                <!-- Pengaduan Pagi -->
                                                @if($slots['pagi_pengaduan'])
                                                    <div class="sp-cal-badge sp-cal-badge-pengaduan" title="Pengaduan Pagi: {{ $slots['pagi_pengaduan']->pegawaiPst->nama_pegawai ?? '-' }}">
                                                        <span class="sp-cal-badge-name">{{ $slots['pagi_pengaduan']->pegawaiPst->nama_pegawai ?? '-' }}</span>
                                                        @if($isEditMode)
                                                            <button type="button" wire:click="mountAction('deleteJadwal', { id: {{ $slots['pagi_pengaduan']->id }} })" class="sp-cal-delete-action">&times;</button>
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Shift Siang -->
                                        <div class="sp-cal-shift-block">
                                            <div class="sp-cal-shift-header">
                                                <span>🌤️ Siang</span>
                                            </div>
                                            <div class="sp-cal-slot-row">
                                                <!-- Pelayanan Siang -->
                                                @if($slots['siang_pelayanan'])
                                                    <div class="sp-cal-badge sp-cal-badge-pelayanan" title="Pelayanan Siang: {{ $slots['siang_pelayanan']->pegawaiPst->nama_pegawai ?? '-' }}">
                                                        <span class="sp-cal-badge-name">{{ $slots['siang_pelayanan']->pegawaiPst->nama_pegawai ?? '-' }}</span>
                                                        @if($isEditMode)
                                                            <button type="button" wire:click="mountAction('deleteJadwal', { id: {{ $slots['siang_pelayanan']->id }} })" class="sp-cal-delete-action">&times;</button>
                                                        @endif
                                                    </div>
                                                @endif

                                                <!-- Pengaduan Siang -->
                                                @if($slots['siang_pengaduan'])
                                                    <div class="sp-cal-badge sp-cal-badge-pengaduan" title="Pengaduan Siang: {{ $slots['siang_pengaduan']->pegawaiPst->nama_pegawai ?? '-' }}">
                                                        <span class="sp-cal-badge-name">{{ $slots['siang_pengaduan']->pegawaiPst->nama_pegawai ?? '-' }}</span>
                                                        @if($isEditMode)
                                                            <button type="button" wire:click="mountAction('deleteJadwal', { id: {{ $slots['siang_pengaduan']->id }} })" class="sp-cal-delete-action">&times;</button>
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
                </div>
            </div>

            <x-filament-actions::modals />
        </div>
    </x-filament::card>
</x-filament-widgets::widget>

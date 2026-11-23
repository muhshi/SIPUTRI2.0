<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center gap-2" style="display: flex; align-items: center; gap: 0.5rem;">
                <x-filament::icon
                    icon="heroicon-o-calendar-days"
                    class="h-5 w-5 text-primary-600 dark:text-primary-400"
                    style="width: 20px; height: 20px; color: #2563eb;"
                />
                <span class="text-base font-semibold text-gray-950 dark:text-white" style="font-size: 1rem; font-weight: 600;">
                    Petugas Piket Hari Ini
                </span>
                <span class="text-xs font-normal text-gray-500 dark:text-gray-400" style="font-size: 0.75rem; color: #6b7280;">
                    ({{ $formattedDate }})
                </span>
            </div>
        </x-slot>

        <x-slot name="headerEnd">
            <a
                href="/admin/jadwal-pikets"
                class="inline-flex items-center gap-1 text-xs font-medium text-primary-600 hover:text-primary-500 dark:text-primary-400 hover:underline"
                style="display: inline-flex; align-items: center; gap: 0.25rem; font-size: 0.75rem; font-weight: 500; color: #2563eb; text-decoration: none;"
            >
                Kelola Jadwal
                <x-filament::icon icon="heroicon-m-arrow-right" class="h-3.5 w-3.5" style="width: 14px; height: 14px;" />
            </a>
        </x-slot>

        @if($piketData->isEmpty())
            <div class="piket-empty-state" style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 1.5rem 1rem; text-align: center;">
                <div class="piket-empty-icon" style="display: flex; width: 48px; height: 48px; min-width: 48px; min-height: 48px; align-items: center; justify-content: center; border-radius: 9999px; background-color: #f3f4f6; color: #9ca3af; margin-bottom: 0.75rem;">
                    <x-filament::icon icon="heroicon-o-calendar" class="h-6 w-6" style="width: 24px; height: 24px;" />
                </div>
                <h3 class="piket-empty-title" style="font-size: 0.875rem; font-weight: 500; color: #111827; margin: 0;">
                    Belum ada jadwal piket yang ditetapkan untuk hari ini.
                </h3>
                <p class="piket-empty-desc" style="font-size: 0.75rem; color: #6b7280; margin: 0.25rem 0 0 0; max-width: 24rem;">
                    Silakan atur penugasan petugas piket pelayanan PST dan pengaduan melalui menu Jadwal Piket.
                </p>
                <div style="margin-top: 1rem;">
                    <x-filament::button
                        href="/admin/jadwal-pikets/create"
                        tag="a"
                        size="xs"
                        icon="heroicon-o-plus"
                    >
                        Tambah Jadwal Piket
                    </x-filament::button>
                </div>
            </div>
        @else
            <div class="piket-grid">
                @foreach($piketData as $item)
                    @php
                        $pegawai = $item['pegawai'];
                        $jadwal = $item['jadwal'];
                        $presensi = $item['presensi'];
                        $photoUrl = $pegawai && $pegawai->foto_pegawai
                            ? asset('storage/' . $pegawai->foto_pegawai)
                            : null;
                    @endphp
                    <div class="piket-card">
                        <div class="piket-card-header">
                            <div class="piket-avatar-wrap">
                                @if($photoUrl)
                                    <img
                                        src="{{ $photoUrl }}"
                                        alt="{{ $pegawai->nama_pegawai ?? 'Petugas' }}"
                                        class="fi-avatar fi-circular piket-avatar-img"
                                        style="width: 48px !important; height: 48px !important; min-width: 48px !important; min-height: 48px !important; max-width: 48px !important; max-height: 48px !important; border-radius: 9999px !important; object-fit: cover !important; object-position: center !important; display: block !important;"
                                        width="48"
                                        height="48"
                                        loading="lazy"
                                    />
                                @else
                                    <div class="piket-avatar-initials">
                                        {{ $pegawai ? strtoupper(substr($pegawai->nama_pegawai, 0, 2)) : '?' }}
                                    </div>
                                @endif
                            </div>

                            <div class="piket-officer-info">
                                <h4 class="piket-officer-name" title="{{ $pegawai ? $pegawai->nama_pegawai : 'Petugas Belum Dipilih' }}">
                                    {{ $pegawai ? $pegawai->nama_pegawai : 'Petugas Belum Dipilih' }}
                                </h4>
                                <p class="piket-officer-meta">
                                    NIP. {{ $pegawai && $pegawai->nip ? $pegawai->nip : '-' }}
                                </p>
                                <p class="piket-officer-meta">
                                    {{ $pegawai && $pegawai->jabatan ? $pegawai->jabatan : 'Staf BPS Demak' }}
                                </p>
                            </div>
                        </div>

                        <div class="piket-card-footer">
                            <div class="piket-tags">
                                <x-filament::badge :color="$jadwal->jenis_piket === 'Pelayanan' ? 'success' : 'warning'" size="sm">
                                    {{ $jadwal->jenis_piket }}
                                </x-filament::badge>

                                <x-filament::badge color="gray" size="sm">
                                    Shift {{ $jadwal->shift }} ({{ $item['shift_time'] }})
                                </x-filament::badge>
                            </div>

                            <div class="piket-status">
                                <x-filament::badge :color="$item['status_color']" :icon="$item['status_icon']" size="sm">
                                    {{ $item['status_label'] }}
                                </x-filament::badge>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <style>
            .fi-wi-petugas-piket-hari-ini .fi-section-content {
                justify-content: flex-start !important;
            }
            .piket-grid {
                display: grid;
                grid-template-columns: 1fr;
                gap: 1rem;
                width: 100%;
            }
            @media (min-width: 640px) {
                .piket-grid {
                    grid-template-columns: repeat(2, minmax(0, 1fr));
                }
            }
            .piket-card {
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                border-radius: 14px;
                border: 1px solid #e5e7eb;
                background-color: #ffffff;
                padding: 1rem;
                box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
                transition: border-color 0.15s ease, box-shadow 0.15s ease;
                min-width: 0;
            }
            .piket-card:hover {
                border-color: #d1d5db;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.07);
            }
            .piket-card-header {
                display: flex;
                align-items: center;
                gap: 0.875rem;
                min-width: 0;
            }
            .piket-avatar-wrap {
                position: relative;
                width: 48px;
                height: 48px;
                min-width: 48px;
                min-height: 48px;
                max-width: 48px;
                max-height: 48px;
                flex-shrink: 0;
                border-radius: 9999px;
                overflow: hidden;
                border: 2px solid #e5e7eb;
                box-sizing: border-box;
            }
            .piket-avatar-img {
                width: 48px !important;
                height: 48px !important;
                min-width: 48px !important;
                min-height: 48px !important;
                max-width: 48px !important;
                max-height: 48px !important;
                border-radius: 9999px !important;
                object-fit: cover !important;
                object-position: center !important;
                display: block !important;
            }
            .piket-avatar-initials {
                width: 100%;
                height: 100%;
                display: flex;
                align-items: center;
                justify-content: center;
                background-color: #dbeafe;
                color: #1e40af;
                font-weight: 700;
                font-size: 0.875rem;
                text-transform: uppercase;
                user-select: none;
            }
            .piket-officer-info {
                flex: 1;
                min-width: 0;
                overflow: hidden;
            }
            .piket-officer-name {
                font-size: 0.875rem;
                font-weight: 600;
                color: #111827;
                margin: 0;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                line-height: 1.25rem;
            }
            .piket-officer-meta {
                font-size: 0.75rem;
                color: #6b7280;
                margin: 0.125rem 0 0 0;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                line-height: 1rem;
            }
            .piket-card-footer {
                margin-top: 0.75rem;
                padding-top: 0.75rem;
                border-top: 1px solid #f3f4f6;
                display: flex;
                flex-wrap: wrap;
                align-items: center;
                justify-content: space-between;
                gap: 0.5rem;
            }
            .piket-tags {
                display: flex;
                flex-wrap: wrap;
                align-items: center;
                gap: 0.375rem;
            }
            .piket-status {
                display: flex;
                align-items: center;
            }

            /* Dark mode */
            .dark .piket-card {
                background-color: #111827;
                border-color: #1f2937;
            }
            .dark .piket-card:hover {
                border-color: #374151;
            }
            .dark .piket-avatar-wrap {
                border-color: #374151;
            }
            .dark .piket-avatar-initials {
                background-color: rgba(30, 58, 138, 0.4);
                color: #93c5fd;
            }
            .dark .piket-officer-name {
                color: #f9fafb;
            }
            .dark .piket-officer-meta {
                color: #9ca3af;
            }
            .dark .piket-card-footer {
                border-top-color: #1f2937;
            }
            .dark .piket-empty-icon {
                background-color: #1f2937 !important;
                color: #9ca3af !important;
            }
            .dark .piket-empty-title {
                color: #f9fafb !important;
            }
            .dark .piket-empty-desc {
                color: #9ca3af !important;
            }
        </style>
    </x-filament::section>
</x-filament-widgets::widget>

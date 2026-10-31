<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center gap-2">
                <x-filament::icon
                    icon="heroicon-o-calendar-days"
                    class="h-5 w-5 text-primary-600 dark:text-primary-400"
                />
                <span class="text-base font-semibold text-gray-950 dark:text-white">
                    Petugas Piket Hari Ini
                </span>
                <span class="text-xs font-normal text-gray-500 dark:text-gray-400">
                    ({{ $formattedDate }})
                </span>
            </div>
        </x-slot>

        <x-slot name="headerEnd">
            <a
                href="/admin/jadwal-pikets"
                class="inline-flex items-center gap-1 text-xs font-medium text-primary-600 hover:text-primary-500 dark:text-primary-400 hover:underline"
            >
                Kelola Jadwal
                <x-filament::icon icon="heroicon-m-arrow-right" class="h-3.5 w-3.5" />
            </a>
        </x-slot>

        @if($piketData->isEmpty())
            <div class="flex flex-col items-center justify-center py-6 text-center">
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800 text-gray-400 mb-3">
                    <x-filament::icon icon="heroicon-o-calendar" class="h-6 w-6" />
                </div>
                <h3 class="text-sm font-medium text-gray-900 dark:text-white">
                    Belum ada jadwal piket yang ditetapkan untuk hari ini.
                </h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 max-w-sm">
                    Silakan atur penugasan petugas piket pelayanan PST dan pengaduan melalui menu Jadwal Piket.
                </p>
                <div class="mt-4">
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
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-2 gap-4">
                @foreach($piketData as $item)
                    @php
                        $pegawai = $item['pegawai'];
                        $jadwal = $item['jadwal'];
                        $presensi = $item['presensi'];
                        $photoUrl = $pegawai && $pegawai->foto_pegawai
                            ? asset('storage/' . $pegawai->foto_pegawai)
                            : null;
                    @endphp
                    <div class="flex flex-col justify-between rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900 shadow-xs">
                        <div class="flex items-start gap-3">
                            <div class="relative shrink-0">
                                @if($photoUrl)
                                    <img
                                        src="{{ $photoUrl }}"
                                        alt="{{ $pegawai->nama_pegawai ?? 'Petugas' }}"
                                        class="h-12 w-12 rounded-full object-cover border border-gray-200 dark:border-gray-700"
                                    />
                                @else
                                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-primary-100 text-primary-700 font-bold dark:bg-primary-900/40 dark:text-primary-300">
                                        {{ $pegawai ? substr($pegawai->nama_pegawai, 0, 2) : '?' }}
                                    </div>
                                @endif
                            </div>

                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm font-semibold text-gray-950 dark:text-white truncate">
                                    {{ $pegawai ? $pegawai->nama_pegawai : 'Petugas Belum Dipilih' }}
                                </h4>
                                <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                    NIP. {{ $pegawai && $pegawai->nip ? $pegawai->nip : '-' }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 truncate mt-0.5">
                                    {{ $pegawai && $pegawai->jabatan ? $pegawai->jabatan : 'Staf BPS Demak' }}
                                </p>
                            </div>
                        </div>

                        <div class="mt-4 pt-3 border-t border-gray-100 dark:border-gray-800 flex flex-wrap items-center justify-between gap-2">
                            <div class="flex flex-wrap items-center gap-1.5">
                                <x-filament::badge :color="$jadwal->jenis_piket === 'Pelayanan' ? 'success' : 'warning'" size="sm">
                                    {{ $jadwal->jenis_piket }}
                                </x-filament::badge>

                                <x-filament::badge color="gray" size="sm">
                                    Shift {{ $jadwal->shift }} ({{ $item['shift_time'] }})
                                </x-filament::badge>
                            </div>

                            <div>
                                <x-filament::badge :color="$item['status_color']" :icon="$item['status_icon']" size="sm">
                                    {{ $item['status_label'] }}
                                </x-filament::badge>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </x-filament::section>
</x-filament-widgets::widget>

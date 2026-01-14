<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex items-center justify-between gap-x-3">
            <div class="flex-1">
                <h2 class="text-lg font-bold tracking-tight text-gray-950 dark:text-white">
                    <b>Presensi Pegawai </b>
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 ">
                    Klik tombol di bawah untuk melakukan pengambilan foto presensi (Masuk/Pulang).
                </p>
            </div>

            <x-filament::button href="/presensi" tag="a" size="lg" color="primary" icon="heroicon-o-camera">
                Masuk ke Halaman Presensi
            </x-filament::button>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Kontainer Filter -->
        <div style="margin-bottom: 2rem;">
            {{ $this->form }}
        </div>

        <!-- Kontainer Tabel -->
        <div class="fi-ta-ctn" style="margin-top: 1rem;">
            {{ $this->table }}
        </div>
    </div>
</x-filament-panels::page>
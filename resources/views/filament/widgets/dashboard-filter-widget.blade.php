@php
    $year = $this->data['year'] ?? now()->year;
    $quarter = $this->data['quarter'] ?? 'q' . ceil(now()->month / 3);

    $months = match ($quarter) {
        'q1' => 'Jan–Mar',
        'q2' => 'Apr–Jun',
        'q3' => 'Jul–Sep',
        'q4' => 'Okt–Des',
        default => 'Jan–Mar',
    };

    $quarterLabel = match ($quarter) {
        'q1' => 'Triwulan I',
        'q2' => 'Triwulan II',
        'q3' => 'Triwulan III',
        'q4' => 'Triwulan IV',
        default => 'Triwulan I',
    };
@endphp

<x-filament-widgets::widget>
    <div
        class="flex flex-col items-center justify-center rounded-xl bg-[#F5F7FA] p-6 shadow-sm ring-1 ring-gray-950/5 backdrop-blur-xl dark:bg-gray-900 dark:ring-white/10">

        <!-- Filter Form -->
        <div class="dashboard-filter-form w-auto">
            {{ $this->form }}
        </div>

        <!-- Info Section -->
        <div class="mt-4 w-full flex flex-row items-center justify-center gap-3 text-sm font-medium text-gray-600 dark:text-gray-400"
            style="width: 100%; display: flex; justify-content: center; align-items: center; margin-top: 16px;">

            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                stroke="currentColor" class="w-5 h-5" style="width: 20px; height: 20px;">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
            </svg>

            <span>
                Periode Aktif - {{ $year }} - {{ $quarterLabel }} ({{ $months }})
            </span>
        </div>
    </div>

    <style>
        /* Custom overrides for tighter form spacing in this specific widget */
        .dashboard-filter-form .fi-fo-grid {
            gap: 0.75rem !important;
            /* gap-3 */
        }

        .dashboard-filter-form .fi-fo-field-wrp-label {
            display: none;
            /* Hide labels as requested */
        }
    </style>
</x-filament-widgets::widget>
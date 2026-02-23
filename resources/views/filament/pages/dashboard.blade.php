<x-filament-panels::page>
    @php
        $visibleWidgets = $this->getVisibleWidgets();
        $topStats = [];
        $filter = [];
        $charts = [];

        $topStatClasses = [
            \App\Filament\Widgets\KunjunganStatsOverview::class,
            \App\Filament\Widgets\PresensiShortcut::class,
            \App\Filament\Widgets\RatingStat::class,
        ];

        $filterClass = \App\Filament\Widgets\DashboardFilterWidget::class;

        foreach ($visibleWidgets as $widget) {
            if (in_array($widget, $topStatClasses)) {
                $topStats[] = $widget;
            } elseif ($widget === $filterClass) {
                $filter[] = $widget;
            } else {
                $charts[] = $widget;
            }
        }
    @endphp

    @if (!empty($topStats))
        <x-filament-widgets::widgets :widgets="$topStats" :columns="$this->getColumns()" />
    @endif

    @if (!empty($filter))
        <x-filament-widgets::widgets :widgets="$filter" :columns="$this->getColumns()" />
    @endif

    @if (!empty($charts))
        <div id="chart-report-section" class="mt-8 space-y-8">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-900">Laporan Statistik</h2>
                        <p class="text-gray-500 text-sm mt-1">Ringkasan data triwulan dalam format PNG.</p>
                    </div>
                    <x-filament::button type="button" color="primary" icon="heroicon-o-arrow-down-tray" onclick="downloadChartReport()">
                        Download PNG
                    </x-filament::button>
                </div>
            </div>
            <div id="charts-container" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                <x-filament-widgets::widgets :widgets="$charts" :columns="1" />
            </div>
        </div>
    @endif

    @push('scripts')
        <script>
            function downloadChartReport() {
                const canvases = document.querySelectorAll('#charts-container canvas');
                if (canvases.length < 4) {
                    alert('Grafik belum siap.');
                    return;
                }

                const width = 2400;
                const height = 2600;
                const canvasFinal = document.createElement('canvas');
                canvasFinal.width = width;
                canvasFinal.height = height;
                const ctx = canvasFinal.getContext('2d');

                // Background & Header
                ctx.fillStyle = "#F9FAFB";
                ctx.fillRect(0, 0, width, height);
                ctx.fillStyle = "#1E40AF";
                ctx.fillRect(0, 0, width, 10);

                ctx.fillStyle = "#111827";
                ctx.font = "bold 72px sans-serif";
                ctx.fillText("LAPORAN STATISTIK TRIWULAN", 160, 130);
                ctx.font = "32px sans-serif";
                ctx.fillStyle = "#374151";
                ctx.fillText("SIPUTRI – BPS Kabupaten Demak", 160, 180);
                ctx.font = "26px sans-serif";
                ctx.fillStyle = "#6B7280";
                ctx.fillText("Periode: TW I 2026 (Jan–Mar)", 160, 220);
                ctx.fillText("Dicetak: 19 Februari 2026", width - 650, 220);

                ctx.strokeStyle = "#E5E7EB";
                ctx.lineWidth = 2;
                ctx.beginPath(); ctx.moveTo(160, 260); ctx.lineTo(width - 160, 260); ctx.stroke();

                // Summary Cards
                let summaryY = 330;
                const boxWidth = (width - 320 - 80) / 3;
                const summaries = [
                    { title: "Total Kunjungan", value: "12" },
                    { title: "Rata-rata / Bulan", value: "6" },
                    { title: "Dominan Perempuan", value: "80%" }
                ];

                summaries.forEach((item, index) => {
                    const x = 160 + (boxWidth + 40) * index;
                    ctx.fillStyle = "#FFFFFF";
                    ctx.fillRect(x, summaryY, boxWidth, 180);
                    ctx.fillStyle = "#1E3A8A";
                    ctx.font = "bold 64px sans-serif";
                    ctx.fillText(item.value, x + 40, summaryY + 100);
                    ctx.fillStyle = "#6B7280";
                    ctx.font = "26px sans-serif";
                    ctx.fillText(item.title, x + 40, summaryY + 145);
                });

                // ================= CHART AREA (TANPA INSIGHT) =================
                let currentY = 630;
                const marginX = 160;
                const totalWidth = width - (marginX * 2);

                // --- 01 Tren Kunjungan (Sekarang Full Width) ---
                currentY = drawSectionTitle(ctx, "01  Tren Kunjungan Bulanan", marginX, currentY);
                drawChartProporsional(ctx, canvases[0], marginX, currentY, totalWidth, 500);
                currentY += 620;

                // --- 02 & 03 Side by Side ---
                const colGap = 120;
                const colWidth = (totalWidth - colGap) / 2;
                
                let sideBySideY = currentY;
                drawSectionTitle(ctx, "02  Distribusi Jenis Kelamin", marginX, sideBySideY);
                drawChartProporsional(ctx, canvases[1], marginX, sideBySideY + 70, colWidth, 450);

                drawSectionTitle(ctx, "03  Distribusi Pendidikan", marginX + colWidth + colGap, sideBySideY);
                drawChartProporsional(ctx, canvases[2], marginX + colWidth + colGap, sideBySideY + 70, colWidth, 450);
                currentY += 620;

                // --- 04 Rekap Tahunan ---
                currentY = drawSectionTitle(ctx, "04  Rekap Kunjungan Tahunan", marginX, currentY);
                drawChartProporsional(ctx, canvases[3], marginX, currentY, totalWidth, 550);

                // Download Link
                const link = document.createElement('a');
                link.download = "Laporan_Statistik_Siputri.png";
                link.href = canvasFinal.toDataURL("image/png", 1.0);
                link.click();
            }

            function drawChartProporsional(ctx, source, x, y, maxW, maxH) {
                let dW = maxW;
                let dH = (source.height / source.width) * dW;
                if (dH > maxH) {
                    dH = maxH;
                    dW = (source.width / source.height) * dH;
                }
                ctx.drawImage(source, x, y, dW, dH);
            }

            function drawSectionTitle(ctx, title, x, y) {
                ctx.fillStyle = "#111827";
                ctx.font = "bold 42px sans-serif";
                ctx.fillText(title, x, y);
                ctx.fillStyle = "#1E40AF";
                ctx.fillRect(x, y + 15, 140, 8);
                return y + 70;
            }
        </script>
    @endpush
</x-filament-panels::page>
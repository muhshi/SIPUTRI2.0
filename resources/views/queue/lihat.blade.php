<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Antrian Hari Ini</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #f4f7f9;
            font-family: 'Inter', 'Segoe UI', Roboto, sans-serif;
            color: #1e293b;
        }

        .page-wrapper {
            max-width: 900px;
            margin: 0 auto;
            padding: 40px 20px 60px;
        }

        /* Back Button */
        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 18px;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            color: #475569;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s ease;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        }

        .btn-back:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
            color: #0f172a;
            transform: translateY(-1px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.04);
        }

        /* Page Title */
        .page-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: #0f172a;
            text-align: center;
            margin: 32px 0 28px;
            letter-spacing: -0.5px;
        }

        /* Status Banner */
        .status-banner {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-left: 6px solid #3b82f6;
            border-radius: 16px;
            padding: 28px 36px;
            margin-bottom: 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.01);
            position: relative;
            overflow: hidden;
        }

        .status-banner::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at top right, rgba(219, 234, 254, 0.4) 0%, transparent 60%);
            z-index: 0;
            pointer-events: none;
        }

        .status-content,
        .time-widget {
            position: relative;
            z-index: 1;
        }

        .status-content {
            text-align: left;
        }

        .time-widget {
            text-align: right;
            border-left: 1.5px dashed #e2e8f0;
            padding-left: 36px;
        }

        .status-banner .status-label {
            font-size: 0.95rem;
            font-weight: 600;
            color: #2563eb;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .status-banner .status-number {
            font-size: 3rem;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -1px;
            margin: 0 0 4px 0;
            line-height: 1;
        }

        .status-banner .status-detail {
            font-size: 0.95rem;
            color: #64748b;
        }

        .status-banner .status-detail strong {
            color: #334155;
            font-weight: 600;
        }

        .status-banner .status-empty {
            font-size: 1.25rem;
            font-weight: 700;
            color: #475569;
            margin-bottom: 4px;
        }

        .status-banner .status-sub {
            font-size: 0.95rem;
            color: #94a3b8;
        }

        .time-widget .time-main {
            font-size: 2.75rem;
            font-weight: 800;
            color: #0f172a;
            font-variant-numeric: tabular-nums;
            line-height: 1;
            letter-spacing: -1px;
            margin-bottom: 6px;
        }

        .time-widget .date-sub {
            font-size: 1rem;
            color: #64748b;
            font-weight: 500;
        }

        /* ========== Section Card ========== */
        .section-card {
            background: #ffffff;
            border: 1px solid #f1f5f9;
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.025);
            overflow: hidden;
            margin-bottom: 24px;
            transition: all 0.3s ease;
            position: relative;
        }

        .section-card:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.08), 0 4px 6px -4px rgba(0, 0, 0, 0.04);
            transform: translateY(-2px);
        }

        /* Accent bar */
        .section-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 6px;
            height: 100%;
            border-radius: 16px 0 0 16px;
        }

        .card-layanan::before {
            background: #3b82f6;
        }

        .card-pengaduan::before {
            background: #f97316;
        }

        .card-disabilitas::before {
            background: #10b981;
        }

        /* Card Header */
        .section-card .card-header {
            background: #ffffff;
            border-bottom: 1px solid #f1f5f9;
            padding: 16px 24px 16px 28px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .section-card .section-title {
            font-size: 1.05rem;
            font-weight: 700;
            color: #0f172a;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .section-title .icon-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            display: inline-block;
            flex-shrink: 0;
        }

        .dot-layanan {
            background: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15);
        }

        .dot-pengaduan {
            background: #f97316;
            box-shadow: 0 0 0 4px rgba(249, 115, 22, 0.15);
        }

        .dot-disabilitas {
            background: #10b981;
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.15);
        }

        /* Badge */
        .badge-count {
            font-size: 0.75rem;
            font-weight: 600;
            padding: 5px 12px;
            border-radius: 20px;
            letter-spacing: 0.3px;
        }

        .badge-layanan {
            background: #eff6ff;
            color: #1d4ed8;
        }

        .badge-pengaduan {
            background: #fff7ed;
            color: #c2410c;
        }

        .badge-disabilitas {
            background: #ecfdf5;
            color: #047857;
        }

        /* ========== Table ========== */
        .section-card .card-body {
            padding: 0;
            background: #ffffff;
        }

        .section-card .queue-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin: 0;
            font-size: 0.875rem;
        }

        .queue-table thead th {
            background: #f8fafc;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #64748b;
            padding: 14px 24px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
            border-top: 1px solid #e2e8f0;
        }

        .queue-table thead th:first-child {
            text-align: center;
        }

        .queue-table tbody td {
            padding: 14px 24px;
            color: #475569;
            text-align: left;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
            transition: all 0.2s ease;
        }

        .queue-table tbody td:first-child {
            text-align: center;
        }

        .queue-table tbody tr:last-child td {
            border-bottom: none;
        }

        .queue-table tbody tr:hover td {
            background: #f8fafc;
        }

        .nomor-cell {
            font-weight: 700;
            color: #0f172a;
            font-family: 'SF Mono', 'Fira Code', 'Cascadia Code', monospace;
            letter-spacing: 0.5px;
            font-size: 0.95rem;
        }

        .empty-row td {
            color: #94a3b8 !important;
            font-style: italic;
            padding: 24px 24px !important;
            font-size: 0.9rem;
            text-align: center !important;
        }

        .empty-row:hover td {
            background: transparent !important;
        }

        .my-queue td {
            background-color: #f0fdf4 !important;
            font-weight: 500;
            color: #166534 !important;
        }

        .my-queue .nomor-cell {
            color: #166534 !important;
        }

        @media (max-width: 640px) {
            .page-wrapper {
                padding: 24px 16px 40px;
            }

            .status-banner {
                flex-direction: column;
                align-items: flex-start;
                gap: 24px;
                padding: 20px;
            }

            .time-widget {
                text-align: left;
                border-left: none;
                border-top: 1.5px dashed #e2e8f0;
                padding-left: 0;
                padding-top: 20px;
                width: 100%;
            }

            .status-banner .status-number {
                font-size: 2.25rem;
            }

            .time-widget .time-main {
                font-size: 2rem;
            }

            .queue-table thead th,
            .queue-table tbody td {
                padding: 12px 16px;
                font-size: 0.8rem;
            }
        }
    </style>
</head>

<body>
    <div class="page-wrapper">
        <a href="/" class="btn-back">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round" viewBox="0 0 24 24">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Kembali
        </a>

        <h1 class="page-title">📋 Daftar Antrian Hari Ini</h1>

        <div class="status-banner">
            <div class="status-content">
                <div class="status-label">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                        <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                    </svg>
                    Sedang Berjalan
                </div>
                @if ($current)
                    @php
                        $prefix = match (strtolower($current->jenis)) {
                            'layanan' => 'L',
                            'pengaduan' => 'P',
                            'disabilitas' => 'D',
                            default => 'A',
                        };
                    @endphp
                    <div class="status-number">{{ $prefix }}-{{ str_pad($current->nomor, 3, '0', STR_PAD_LEFT) }}</div>
                    <div class="status-detail">Jenis Layanan: <strong>{{ ucfirst($current->layanan) }}</strong></div>
                @else
                    <div class="status-empty">Belum ada antrian</div>
                    <div class="status-sub">Silahkan ambil antrian terlebih dahulu.</div>
                @endif
            </div>

            <div class="time-widget">
                <div class="time-main" id="realTimeClock">00:00:00</div>
                <div class="date-sub" id="realTimeDate">Memuat tanggal...</div>
            </div>
        </div>

        @php $myAntrianId = session('antrian_id'); @endphp

        {{-- ================= ANTRIAN LAYANAN ================= --}}
        <div class="section-card card-layanan">
            <div class="card-header">
                <h6 class="section-title">
                    <span class="icon-dot dot-layanan"></span>
                    Antrian Layanan
                </h6>
                <span class="badge badge-count badge-layanan">{{ $layanan->count() }} antrian</span>
            </div>
            <div class="card-body">
                <table class="queue-table">
                    <thead>
                        <tr>
                            <th style="width: 60px;">No</th>
                            <th>Nomor Antrian</th>
                            <th>Layanan</th>
                            <th>Waktu Ambil</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($layanan as $index => $queue)
                            <tr @if($queue->id == $myAntrianId) class="my-queue" @endif>
                                <td>{{ $index + 1 }}</td>
                                <td class="nomor-cell">L-{{ str_pad($queue->nomor, 3, '0', STR_PAD_LEFT) }}</td>
                                <td>{{ ucfirst($queue->layanan) }}</td>
                                <td>{{ \Carbon\Carbon::parse($queue->created_at)->format('H:i') }} WIB</td>
                            </tr>
                        @empty
                            <tr class="empty-row">
                                <td colspan="4">Belum ada antrian hari ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ================= ANTRIAN PENGADUAN ================= --}}
        <div class="section-card card-pengaduan">
            <div class="card-header">
                <h6 class="section-title">
                    <span class="icon-dot dot-pengaduan"></span>
                    Antrian Pengaduan
                </h6>
                <span class="badge badge-count badge-pengaduan">{{ $pengaduan->count() }} antrian</span>
            </div>
            <div class="card-body">
                <table class="queue-table">
                    <thead>
                        <tr>
                            <th style="width: 60px;">No</th>
                            <th>Nomor Antrian</th>
                            <th>Layanan</th>
                            <th>Waktu Ambil</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pengaduan as $index => $queue)
                            <tr @if($queue->id == $myAntrianId) class="my-queue" @endif>
                                <td>{{ $index + 1 }}</td>
                                <td class="nomor-cell">P-{{ str_pad($queue->nomor, 3, '0', STR_PAD_LEFT) }}</td>
                                <td>{{ ucfirst($queue->layanan) }}</td>
                                <td>{{ \Carbon\Carbon::parse($queue->created_at)->format('H:i') }} WIB</td>
                            </tr>
                        @empty
                            <tr class="empty-row">
                                <td colspan="4">Belum ada antrian hari ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ================= ANTRIAN DISABILITAS ================= --}}
        <div class="section-card card-disabilitas">
            <div class="card-header">
                <h6 class="section-title">
                    <span class="icon-dot dot-disabilitas"></span>
                    Antrian Disabilitas
                </h6>
                <span class="badge badge-count badge-disabilitas">{{ $disabilitas->count() }} antrian</span>
            </div>
            <div class="card-body">
                <table class="queue-table">
                    <thead>
                        <tr>
                            <th style="width: 60px;">No</th>
                            <th>Nomor Antrian</th>
                            <th>Layanan</th>
                            <th>Waktu Ambil</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($disabilitas as $index => $queue)
                            <tr @if($queue->id == $myAntrianId) class="my-queue" @endif>
                                <td>{{ $index + 1 }}</td>
                                <td class="nomor-cell">D-{{ str_pad($queue->nomor, 3, '0', STR_PAD_LEFT) }}</td>
                                <td>{{ ucfirst($queue->layanan) }}</td>
                                <td>{{ \Carbon\Carbon::parse($queue->created_at)->format('H:i') }} WIB</td>
                            </tr>
                        @empty
                            <tr class="empty-row">
                                <td colspan="4">Belum ada antrian hari ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function updateClock() {
            const now = new Date();

            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');

            document.getElementById('realTimeClock').textContent = `${hours}:${minutes}:${seconds}`;

            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            document.getElementById('realTimeDate').textContent = now.toLocaleDateString('id-ID', options);
        }

        updateClock();
        setInterval(updateClock, 1000);
    </script>
</body>

</html>
@extends('layouts.app')

@section('content')
    <style>
        .section-card {
            background: #fff;
            border: none;
            border-radius: 14px;
            box-shadow: 0 1px 3px rgba(16, 24, 40, 0.06), 0 1px 2px rgba(16, 24, 40, 0.04);
            overflow: hidden;
            margin-bottom: 20px;
            transition: box-shadow 0.2s ease, transform 0.2s ease;
            position: relative;
        }

        .section-card:hover {
            box-shadow: 0 4px 12px rgba(16, 24, 40, 0.10), 0 2px 6px rgba(16, 24, 40, 0.06);
            transform: translateY(-1px);
        }

        .section-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 5px;
            height: 100%;
            border-radius: 14px 0 0 14px;
        }

        .card-layanan::before {
            background: linear-gradient(180deg, #2e90fa, #1570ef);
        }

        .card-pengaduan::before {
            background: linear-gradient(180deg, #f79009, #dc6803);
        }

        .card-disabilitas::before {
            background: linear-gradient(180deg, #12b76a, #039855);
        }

        .section-card .card-hdr {
            background: #fff;
            border-bottom: 1px solid #eaecf0;
            padding: 14px 20px 14px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .section-card .section-title {
            font-size: 0.92rem;
            font-weight: 700;
            color: #101828;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 9px;
        }

        .section-title .icon-dot {
            width: 9px;
            height: 9px;
            border-radius: 50%;
            display: inline-block;
            flex-shrink: 0;
        }

        .dot-layanan {
            background: #2e90fa;
            box-shadow: 0 0 0 3px rgba(46, 144, 250, 0.18);
        }

        .dot-pengaduan {
            background: #f79009;
            box-shadow: 0 0 0 3px rgba(247, 144, 9, 0.18);
        }

        .dot-disabilitas {
            background: #12b76a;
            box-shadow: 0 0 0 3px rgba(18, 183, 106, 0.18);
        }

        .badge-count {
            font-size: 0.72rem;
            font-weight: 600;
            padding: 4px 11px;
            border-radius: 999px;
            letter-spacing: 0.2px;
            opacity: 0.92;
        }

        .badge-layanan {
            background: #eff8ff;
            color: #175cd3;
        }

        .badge-pengaduan {
            background: #fffaeb;
            color: #b54708;
        }

        .badge-disabilitas {
            background: #ecfdf3;
            color: #027a48;
        }

        .queue-table {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
            font-size: 0.84rem;
        }

        .queue-table thead th {
            background: #f8fafc;
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            color: #667085;
            padding: 11px 16px;
            text-align: center;
            border-bottom: 1px solid #eaecf0;
        }

        .queue-table tbody td {
            padding: 11px 16px;
            color: #344054;
            text-align: center;
            vertical-align: middle;
            border-bottom: 1px solid #f2f4f7;
            transition: background 0.15s ease;
        }

        .queue-table tbody tr:last-child td {
            border-bottom: none;
        }

        .queue-table tbody tr:hover td {
            background: #f5f8ff;
        }

        .nomor-cell {
            font-weight: 600;
            color: #101828;
            font-family: 'SF Mono', 'Fira Code', 'Cascadia Code', monospace;
            letter-spacing: 0.5px;
        }

        .empty-row td {
            color: #98a2b3;
            font-style: italic;
            padding: 18px 16px !important;
            font-size: 0.83rem;
        }

        .empty-row:hover td {
            background: transparent !important;
        }
    </style>

    <div class="container" style="max-width: 880px;">
        <h2 class="mb-4 fw-bold" style="font-size: 1.5rem; color: #101828;">📋 Daftar Antrian Hari Ini</h2>

        {{-- ================= ANTRIAN LAYANAN ================= --}}
        <div class="section-card card-layanan">
            <div class="card-hdr">
                <h6 class="section-title">
                    <span class="icon-dot dot-layanan"></span>
                    Antrian Layanan
                </h6>
                <span class="badge badge-count badge-layanan">{{ $layanan->count() }} antrian</span>
            </div>
            <div style="padding:0;">
                <table class="queue-table">
                    <thead>
                        <tr>
                            <th style="width:50px;">No</th>
                            <th>Nomor Antrian</th>
                            <th>Layanan</th>
                            <th>Waktu Ambil</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($layanan as $index => $queue)
                            <tr>
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
            <div class="card-hdr">
                <h6 class="section-title">
                    <span class="icon-dot dot-pengaduan"></span>
                    Antrian Pengaduan
                </h6>
                <span class="badge badge-count badge-pengaduan">{{ $pengaduan->count() }} antrian</span>
            </div>
            <div style="padding:0;">
                <table class="queue-table">
                    <thead>
                        <tr>
                            <th style="width:50px;">No</th>
                            <th>Nomor Antrian</th>
                            <th>Layanan</th>
                            <th>Waktu Ambil</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pengaduan as $index => $queue)
                            <tr>
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
            <div class="card-hdr">
                <h6 class="section-title">
                    <span class="icon-dot dot-disabilitas"></span>
                    Antrian Disabilitas
                </h6>
                <span class="badge badge-count badge-disabilitas">{{ $disabilitas->count() }} antrian</span>
            </div>
            <div style="padding:0;">
                <table class="queue-table">
                    <thead>
                        <tr>
                            <th style="width:50px;">No</th>
                            <th>Nomor Antrian</th>
                            <th>Layanan</th>
                            <th>Waktu Ambil</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($disabilitas as $index => $queue)
                            <tr>
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
@endsection
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Antrian Hari Ini | SIPUTRI</title>

    <!-- Auto Refresh for TV Display -->
    <meta http-equiv="refresh" content="30">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@500;700&display=swap"
        rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                        mono: ['JetBrains Mono', 'monospace'],
                    },
                    colors: {
                        bps: {
                            blue: '#0054a6',
                            dark: '#003a7d',
                            accent: '#3b82f6',
                            orange: '#f97316',
                            green: '#10b981',
                        }
                    }
                }
            }
        }
    </script>

    <style>
        body {
            background-color: #f0f4f8;
            background-image:
                radial-gradient(at 0% 0%, rgba(59, 130, 246, 0.12) 0px, transparent 50%),
                radial-gradient(at 100% 0%, rgba(249, 115, 22, 0.1) 0px, transparent 50%),
                radial-gradient(at 50% 100%, rgba(16, 185, 129, 0.08) 0px, transparent 50%);
            min-height: 100vh;
            overflow-x: hidden;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(25px);
            -webkit-backdrop-filter: blur(25px);
            border: 1px solid rgba(255, 255, 255, 0.8);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.08);
        }

        .active-glow {
            box-shadow: 0 0 40px rgba(59, 130, 246, 0.5);
        }

        .tv-glow-text {
            text-shadow: 0 0 30px rgba(59, 130, 246, 0.3);
        }

        @keyframes loket-call {

            0%,
            100% {
                transform: scale(1);
                box-shadow: 0 0 40px rgba(59, 130, 246, 0.5);
            }

            50% {
                transform: scale(1.03);
                box-shadow: 0 0 60px rgba(59, 130, 246, 0.8);
            }
        }

        .animate-loket-call {
            animation: loket-call 2s infinite ease-in-out;
        }

        @keyframes fade-in-up {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-entrance {
            animation: fade-in-up 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        /* High Contrast Priority Green */
        .priority-green-text {
            color: #065f46 !important; /* emerald-800 */
        }
        .priority-green-bg {
            background-color: #059669 !important; /* emerald-600 */
        }

        .status-muted {
            opacity: 0.5;
            filter: grayscale(0.8);
            background: rgba(241, 245, 249, 0.5) !important;
        }

        .status-active-row {
            background: linear-gradient(to right, rgba(59, 130, 246, 0.05), transparent) !important;
            border-left: 4px solid #3b82f6 !important;
        }

        @keyframes subtle-bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
        }

        .animate-subtle-bounce {
            animation: subtle-bounce 4.s infinite ease-in-out;
        }

        /* Prevent scroll on TV if content fits */
        @media (min-height: 1000px) {
            body { overflow: hidden; }
        }
    </style>
</head>

<body class="font-sans text-slate-800 antialiased">
    <div class="w-full px-6 sm:px-10 lg:px-14 py-8 lg:py-10">

        <!-- Header & Nav -->
        <div class="flex flex-col sm:flex-row items-center justify-between mb-10 gap-6">
            <div class="flex items-center gap-3">
                <a href="/" class="group flex items-center gap-3 px-5 py-2.5 bg-white border border-slate-200 rounded-2xl shadow-sm hover:shadow-md hover:border-blue-200 transition-all duration-300">
                    <svg class="w-5 h-5 text-slate-400 group-hover:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span class="text-sm font-semibold text-slate-600 group-hover:text-slate-900 transition-colors">Beranda</span>
                </a>
                
                <button onclick="toggleFullscreen()" id="btnModeTV" class="group flex items-center gap-3 px-5 py-2.5 bg-blue-600 text-white rounded-2xl shadow-lg shadow-blue-500/20 hover:bg-blue-700 transition-all duration-300">
                    <svg class="w-5 h-5" id="soundIcon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z" />
                    </svg>
                    <span class="text-sm font-bold" id="modeTVText">Aktifkan Suara & Mode TV</span>
                </button>
            </div>

            <div
                class="flex items-center gap-4 bg-white/50 backdrop-blur p-2 rounded-2xl border border-white/50 shadow-sm">
                <div class="flex flex-col items-end px-3">
                    <div id="realTimeClock" class="text-2xl font-extrabold text-slate-800 font-mono tracking-tighter">
                        00:00:00</div>
                    <div id="realTimeDate" class="text-[10px] uppercase tracking-widest font-bold text-slate-500">Memuat
                        Tanggal...</div>
                </div>
                <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="text-center mb-12">
            <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-slate-900 mb-2">Daftar Antrian Hari Ini
            </h1>
            <p class="text-slate-500 font-medium">Pantau jalannya antrian layanan secara real-time.</p>
        </div>

        <!-- Main Status Card -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-12">
            <div
                class="lg:col-span-8 bg-white border border-slate-200 rounded-[32px] overflow-hidden shadow-2xl shadow-blue-200/20 relative flex flex-col items-center justify-center min-h-[500px] h-full">
                <div
                    class="absolute top-0 right-0 w-80 h-80 bg-blue-100 rounded-full blur-[100px] opacity-30 -mr-32 -mt-32 pointer-events-none">
                </div>

                <div class="relative w-full p-8 sm:p-10 flex flex-col sm:flex-row items-center justify-around gap-8">
                    <div class="flex flex-col items-center sm:items-start text-center sm:text-left animate-entrance">
                        <span class="inline-flex items-center gap-2 px-6 py-2 bg-blue-600 text-white text-xs font-black uppercase tracking-[0.3em] rounded-full mb-8 shadow-xl shadow-blue-500/40 border border-blue-400">
                            <span class="w-3 h-3 rounded-full bg-white animate-ping"></span>
                            PEMANGGILAN AKTIF
                        </span>

                        @if ($myAntrian->isNotEmpty())
                            @php
                                $current = $myAntrian->first();
                                $prefix = match (strtolower($current->jenis)) {
                                    'layanan' => 'L',
                                    'pengaduan' => 'P',
                                    'disabilitas' => 'D',
                                    default => 'A',
                                };
                            @endphp
                            <div class="animate-entrance text-center sm:text-left">
                                <div class="text-7xl sm:text-[8rem] font-black text-slate-900 mb-6 font-mono tracking-tighter leading-none tv-glow-text">
                                    {{ $prefix }}-<span class="text-blue-600">{{ str_pad($current->nomor, 3, '0', STR_PAD_LEFT) }}</span>
                                </div>
                                <div class="flex flex-col gap-1">
                                    <span class="text-blue-500 text-[10px] font-black uppercase tracking-[0.4em] mb-1">BIDANG LAYANAN</span>
                                    <span class="text-3xl sm:text-4xl font-black text-slate-800 tracking-tight leading-tight">{{ ucfirst($current->layanan) }}</span>
                                </div>
                            </div>
                        @else
                            <div class="py-12">
                                <h3 class="text-7xl font-black text-slate-200 uppercase tracking-tighter">STANDBY</h3>
                                <p class="text-blue-400 text-2xl font-bold mt-4 tracking-widest leading-relaxed">MENUNGGU ANTRIAN<br>TERHUBUNG KE SISTEM</p>
                            </div>
                        @endif
                    </div>

                    <div class="flex-shrink-0 flex flex-col items-center gap-4 group animate-entrance" style="animation-delay: 0.2s;">
                        <div class="w-48 h-48 sm:w-[280px] sm:h-[280px] rounded-[56px] bg-gradient-to-br from-blue-400 via-blue-600 to-indigo-800 p-1.5 shadow-[0_20px_50px_rgba(59,130,246,0.4)] {{ $myAntrian->isNotEmpty() ? 'animate-loket-call' : '' }} transition-all duration-700">
                            <div class="w-full h-full rounded-[50px] bg-white flex flex-col items-center justify-center p-8 text-center relative overflow-hidden">
                                <div class="absolute inset-0 bg-blue-50/30 {{ $myAntrian->isNotEmpty() ? 'animate-pulse' : '' }}"></div>
                                <div class="relative z-10">
                                    @if($myAntrian->isNotEmpty())
                                        <div class="text-[9px] font-black text-slate-400 uppercase tracking-[0.4em] mb-2">LOKET AKTIF</div>
                                        <div class="flex flex-wrap justify-center gap-2">
                                            <div class="text-8xl font-black text-transparent bg-clip-text bg-gradient-to-br from-blue-600 to-indigo-900 font-mono leading-none">01</div>
                                        </div>
                                    @else
                                        <svg class="w-24 h-24 text-slate-100" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1-4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-4 flex flex-col gap-6">
                <!-- Stat Mini Cards -->
                <div
                    class="bg-gradient-to-br from-white to-blue-50/30 border border-slate-200 p-7 rounded-[32px] shadow-xl shadow-slate-200/30 flex items-center justify-between group hover:border-blue-300 transition-all duration-300">
                    <div>
                        <div class="text-[10px] font-black text-blue-400 uppercase tracking-[0.25em] mb-2">Total Hari
                            Ini</div>
                        <div class="text-4xl font-black text-slate-900 leading-none">
                            {{ $layanan->count() + $pengaduan->count() + $disabilitas->count() }} <small
                                class="text-slate-300 text-sm font-bold uppercase ml-1">Tiket</small></div>
                    </div>
                    <div
                        class="w-14 h-14 rounded-[22px] bg-blue-600 text-white flex items-center justify-center shadow-lg shadow-blue-200 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 p-8 rounded-[40px] shadow-2xl shadow-slate-900/40 flex flex-col justify-between flex-grow relative overflow-hidden group border border-white/5">
                    <!-- Decor -->
                    <div class="absolute -top-10 -right-10 w-40 h-40 bg-blue-500/20 rounded-full blur-3xl"></div>
                    <div class="absolute -bottom-10 -left-10 w-40 h-40 bg-indigo-500/10 rounded-full blur-3xl"></div>

                    @php
                        $prevQueueNum = null;
                        // Get the most recent finished/cancelled queue
                        $prevQueue = \App\Models\Queue::whereDate('tanggal', \Carbon\Carbon::today())
                            ->whereIn('status', ['finished', 'cancelled'])
                            ->orderBy('updated_at', 'desc')
                            ->first();
                        
                        if ($prevQueue) {
                            $prevPrefix = match (strtolower($prevQueue->jenis)) {
                                'layanan' => 'L',
                                'pengaduan' => 'P',
                                'disabilitas' => 'D',
                                default => 'A',
                            };
                            $prevQueueNum = $prevPrefix . '-' . str_pad($prevQueue->nomor, 3, '0', STR_PAD_LEFT);
                        }
                        
                        $totalWaiting = 0;
                        foreach([$layanan, $pengaduan, $disabilitas] as $collection) {
                            $totalWaiting += $collection->where('status', 'waiting')->count();
                        }
                    @endphp

                    <div class="relative z-10 flex flex-col h-full">
                        <!-- Top: Active -->
                        <div class="flex items-center justify-between mb-8">
                            <div class="flex flex-col gap-2">
                                <span class="text-[9px] font-black text-blue-400 uppercase tracking-[0.3em]">Sedang Dilayani</span>
                                <div class="flex flex-col gap-1">
                                    @forelse($myAntrian->take(3) as $active)
                                        @php
                                            $aPrefix = match (strtolower($active->jenis)) {
                                                'layanan' => 'L',
                                                'pengaduan' => 'P',
                                                'disabilitas' => 'D',
                                                default => 'A',
                                            };
                                        @endphp
                                        <span class="text-2xl font-black text-white/90 font-mono tracking-tighter">
                                            {{ $aPrefix }}-{{ str_pad($active->nomor, 3, '0', STR_PAD_LEFT) }}
                                        </span>
                                    @empty
                                        <span class="text-2xl font-black text-white/40 font-mono tracking-tighter">---</span>
                                    @endforelse
                                </div>
                            </div>
                            <div class="w-12 h-12 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center text-blue-400 group-hover:rotate-12 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            </div>
                        </div>

                        <!-- Middle: Big Count -->
                        <div class="flex-grow flex flex-col justify-center py-4">
                            <span class="text-7xl font-black text-white leading-none font-mono tracking-tighter mb-2">{{ $totalWaiting }}</span>
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.4em]">Sisa Antrean</span>
                        </div>

                        <!-- Bottom: Just Finished -->
                        <div class="mt-8 pt-6 border-t border-white/5">
                            <div class="flex flex-col">
                                <span class="text-[9px] font-black text-slate-500 uppercase tracking-[0.2em] mb-1">Baru Saja Selesai</span>
                                <span class="text-lg font-black text-slate-400 font-mono tracking-tight">{{ $prevQueueNum ?? '---' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Daftar Tunggu Header -->
        <div class="mb-8 flex items-center gap-4 animate-entrance" style="animation-delay: 0.2s;">
            <div class="h-px flex-grow bg-gradient-to-r from-transparent via-slate-200 to-transparent"></div>
            <div class="flex items-center gap-3 px-6 py-2 bg-slate-100/50 rounded-full border border-slate-200/50 backdrop-blur-sm">
                <span class="w-2 h-2 rounded-full bg-slate-400 animate-pulse"></span>
                <h2 class="text-xs font-black text-slate-500 uppercase tracking-[0.4em]">Daftar Tunggu & Riwayat Pelayanan</h2>
            </div>
            <div class="h-px flex-grow bg-gradient-to-r from-transparent via-slate-200 to-transparent"></div>
        </div>

        <!-- Tables Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 pb-12">
            @php 
                $myAntrianId = session('antrian_id');
            @endphp

            {{-- ================= LAYANAN ================= --}}
            <div class="flex flex-col animate-entrance" style="animation-delay: 0.3s;">
                <div class="flex items-center justify-between mb-5 px-3">
                    <div class="flex items-center gap-3">
                        <div class="w-11 h-11 rounded-2xl bg-blue-600 text-white flex items-center justify-center shadow-lg shadow-blue-200">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <h2 class="font-extrabold text-slate-900 tracking-tight text-lg">Layanan Utama</h2>
                    </div>
                    <span class="text-[10px] font-black text-white bg-blue-600 px-3 py-1.5 rounded-xl uppercase shadow-md shadow-blue-100">{{ $layanan->count() }}</span>
                </div>

                <div
                    class="bg-white border-2 border-slate-100 rounded-[32px] shadow-2xl shadow-slate-200/20 overflow-hidden flex-grow group hover:border-blue-200 transition-colors">
                    <div class="max-h-[500px] overflow-y-auto queue-table-container">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-blue-50/50 border-b-2 border-blue-100/30">
                                    <th class="py-5 px-6 text-[10px] font-black text-blue-600 uppercase tracking-[0.2em]">Antrian</th>
                                    <th class="py-5 px-6 text-[10px] font-black text-blue-600 uppercase tracking-[0.2em]">Bidang</th>
                                    <th class="py-5 px-6 text-[10px] font-black text-blue-600 uppercase tracking-[0.2em]">Waktu</th>
                                    <th class="py-5 px-6 text-[10px] font-black text-blue-600 uppercase tracking-[0.2em] text-center">Status</th>
                                    @auth
                                        <th class="py-5 px-6 text-[10px] font-black text-blue-600 uppercase tracking-[0.2em] text-center">Aksi</th>
                                    @endauth
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($layanan as $queue)
                                    @php
                                        $isFinished = ($queue->status == 'finished');
                                        $isCancelled = ($queue->status == 'cancelled');
                                        $isActive = ($queue->status == 'calling');
                                    @endphp
                                    <tr class="group/row transition-all border-b border-slate-50 last:border-0 
                                        {{ ($isFinished || $isCancelled) ? 'status-muted' : '' }} 
                                        {{ $isActive ? 'status-active-row' : '' }}
                                        {{ $queue->id == $myAntrianId ? 'bg-blue-50/50' : '' }}">
                                        <td class="py-4 px-6">
                                            <span class="font-mono font-black text-lg {{ $isFinished ? 'text-slate-400' : 'text-slate-900' }}">L-{{ str_pad($queue->nomor, 3, '0', STR_PAD_LEFT) }}</span>
                                        </td>
                                        <td class="py-4 px-6">
                                            <span class="text-xs font-bold {{ $isFinished ? 'text-slate-400' : 'text-slate-700' }} truncate block max-w-[80px]">{{ ucfirst($queue->layanan) }}</span>
                                        </td>
                                        <td class="py-4 px-6">
                                            <span class="text-[10px] font-black {{ $isFinished ? 'text-slate-300' : 'text-blue-500' }}">{{ \Carbon\Carbon::parse($queue->created_at)->format('H:i') }}</span>
                                        </td>
                                        <td class="py-4 px-6 text-center">
                                            @if($isFinished)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[8px] font-black bg-slate-100 text-slate-400 uppercase tracking-tighter">Selesai</span>
                                            @elseif($isCancelled)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[8px] font-black bg-red-50 text-red-300 uppercase tracking-tighter">Batal</span>
                                            @elseif($isActive)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[8px] font-black bg-blue-600 text-white uppercase tracking-tighter animate-pulse">Dipanggil</span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[8px] font-black bg-blue-100 text-blue-600 uppercase tracking-tighter">Menunggu</span>
                                            @endif
                                        </td>
                                        @auth
                                        <td class="py-4 px-6 text-center">
                                            @if($isActive)
                                                <div class="flex items-center justify-center gap-2">
                                                    <button onclick="handleStatus('{{ $queue->id }}', 'calling')" title="Panggil Ulang" class="p-2 bg-blue-500 text-white rounded-xl hover:bg-blue-600 transition-colors shadow-lg shadow-blue-200">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z" /></svg>
                                                    </button>
                                                    <button onclick="handleStatus('{{ $queue->id }}', 'finished')" title="Selesai" class="p-2 bg-emerald-500 text-white rounded-xl hover:bg-emerald-600 transition-colors shadow-lg shadow-emerald-200">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                                    </button>
                                                    <button onclick="handleStatus('{{ $queue->id }}', 'cancelled')" title="Batal" class="p-2 bg-red-500 text-white rounded-xl hover:bg-red-600 transition-colors shadow-lg shadow-red-200">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
                                                    </button>
                                                </div>
                                            @elseif($queue->status == 'waiting')
                                                <button onclick="handleStatus('{{ $queue->id }}', 'calling')" class="text-xs font-bold text-blue-500 hover:text-blue-700 hover:underline">Panggil</button>
                                            @endif
                                        </td>
                                        @endauth
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="py-20 px-6 text-center">
                                            <div class="flex flex-col items-center">
                                                <div
                                                    class="w-16 h-16 rounded-full bg-slate-50 flex items-center justify-center mb-4 border border-slate-100">
                                                    <svg class="w-8 h-8 text-slate-200" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                </div>
                                                <span
                                                    class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-300">Belum
                                                    Ada Antrian</span>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- ================= PENGADUAN ================= --}}
            <div class="flex flex-col animate-entrance" style="animation-delay: 0.4s;">
                <div class="flex items-center justify-between mb-5 px-3">
                    <div class="flex items-center gap-3">
                        <div class="w-11 h-11 rounded-2xl bg-orange-500 text-white flex items-center justify-center shadow-lg shadow-orange-200">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <h2 class="font-extrabold text-slate-900 tracking-tight text-lg">Pengaduan</h2>
                    </div>
                    <span class="text-[10px] font-black text-white bg-orange-500 px-3 py-1.5 rounded-xl uppercase shadow-md shadow-orange-100">{{ $pengaduan->count() }}</span>
                </div>

                <div
                    class="bg-white border-2 border-slate-100 rounded-[32px] shadow-2xl shadow-slate-200/20 overflow-hidden flex-grow group hover:border-orange-200 transition-colors">
                    <div class="max-h-[500px] overflow-y-auto queue-table-container">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-orange-50/50 border-b-2 border-orange-100/30">
                                    <th class="py-5 px-6 text-[10px] font-black text-orange-600 uppercase tracking-[0.2em]">Antrian</th>
                                    <th class="py-5 px-6 text-[10px] font-black text-orange-600 uppercase tracking-[0.2em]">Bidang</th>
                                    <th class="py-5 px-6 text-[10px] font-black text-orange-600 uppercase tracking-[0.2em]">Waktu</th>
                                    <th class="py-5 px-6 text-[10px] font-black text-orange-600 uppercase tracking-[0.2em] text-center">Status</th>
                                    @auth
                                        <th class="py-5 px-6 text-[10px] font-black text-orange-600 uppercase tracking-[0.2em] text-center">Aksi</th>
                                    @endauth
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($pengaduan as $queue)
                                    @php
                                        $isFinished = ($queue->status == 'finished');
                                        $isCancelled = ($queue->status == 'cancelled');
                                        $isActive = ($queue->status == 'calling');
                                    @endphp
                                    <tr class="group/row transition-all border-b border-slate-50 last:border-0 
                                        {{ ($isFinished || $isCancelled) ? 'status-muted' : '' }} 
                                        {{ $isActive ? 'status-active-row' : '' }}
                                        {{ $queue->id == $myAntrianId ? 'bg-orange-50/50' : '' }}">
                                        <td class="py-4 px-6">
                                            <span class="font-mono font-black text-lg {{ $isFinished ? 'text-slate-400' : 'text-slate-900' }}">P-{{ str_pad($queue->nomor, 3, '0', STR_PAD_LEFT) }}</span>
                                        </td>
                                        <td class="py-4 px-6">
                                            <span class="text-xs font-bold {{ $isFinished ? 'text-slate-400' : 'text-slate-700' }} truncate block max-w-[80px]">{{ ucfirst($queue->layanan) }}</span>
                                        </td>
                                        <td class="py-4 px-6">
                                            <span class="text-[10px] font-black {{ $isFinished ? 'text-slate-300' : 'text-orange-500' }}">{{ \Carbon\Carbon::parse($queue->created_at)->format('H:i') }}</span>
                                        </td>
                                        <td class="py-4 px-6 text-center">
                                            @if($isFinished)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[8px] font-black bg-slate-100 text-slate-400 uppercase tracking-tighter">Selesai</span>
                                            @elseif($isCancelled)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[8px] font-black bg-red-50 text-red-300 uppercase tracking-tighter">Batal</span>
                                            @elseif($isActive)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[8px] font-black bg-orange-600 text-white uppercase tracking-tighter animate-pulse">Dipanggil</span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[8px] font-black bg-orange-100 text-orange-600 uppercase tracking-tighter">Menunggu</span>
                                            @endif
                                        </td>
                                        @auth
                                        <td class="py-4 px-6 text-center">
                                            @if($isActive)
                                                <div class="flex items-center justify-center gap-2">
                                                    <button onclick="handleStatus('{{ $queue->id }}', 'calling')" title="Panggil Ulang" class="p-2 bg-orange-500 text-white rounded-xl hover:bg-orange-600 transition-colors shadow-lg shadow-orange-200">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z" /></svg>
                                                    </button>
                                                    <button onclick="handleStatus('{{ $queue->id }}', 'finished')" title="Selesai" class="p-2 bg-emerald-500 text-white rounded-xl hover:bg-emerald-600 transition-colors shadow-lg shadow-emerald-200">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                                    </button>
                                                    <button onclick="handleStatus('{{ $queue->id }}', 'cancelled')" title="Batal" class="p-2 bg-red-500 text-white rounded-xl hover:bg-red-600 transition-colors shadow-lg shadow-red-200">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
                                                    </button>
                                                </div>
                                            @elseif($queue->status == 'waiting')
                                                <button onclick="handleStatus('{{ $queue->id }}', 'calling')" class="text-xs font-bold text-orange-500 hover:text-orange-700 hover:underline">Panggil</button>
                                            @endif
                                        </td>
                                        @endauth
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="py-20 px-6 text-center">
                                            <div class="flex flex-col items-center">
                                                <div
                                                    class="w-16 h-16 rounded-full bg-slate-50 flex items-center justify-center mb-4 border border-slate-100">
                                                    <svg class="w-8 h-8 text-slate-200" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                </div>
                                                <span
                                                    class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-300">Belum
                                                    Ada Antrian</span>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- ================= DISABILITAS ================= --}}
            <div class="flex flex-col animate-entrance" style="animation-delay: 0.5s;">
                <div class="flex items-center justify-between mb-5 px-3">
                    <div class="flex items-center gap-3">
                        <div class="w-11 h-11 rounded-2xl priority-green-bg text-white flex items-center justify-center shadow-lg shadow-emerald-200">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <h2 class="font-extrabold text-slate-900 tracking-tight text-lg">Prioritas</h2>
                    </div>
                    <span class="text-[10px] font-black text-white priority-green-bg px-3 py-1.5 rounded-xl uppercase shadow-md shadow-emerald-100">{{ $disabilitas->count() }}</span>
                </div>

                <div
                    class="bg-white border-2 border-slate-100 rounded-[32px] shadow-2xl shadow-slate-200/20 overflow-hidden flex-grow group hover:border-emerald-200 transition-colors">
                    <div class="max-h-[500px] overflow-y-auto queue-table-container">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-emerald-50/50 border-b-2 border-emerald-100/30">
                                    <th class="py-5 px-6 text-[10px] font-black text-emerald-600 uppercase tracking-[0.2em]">Antrian</th>
                                    <th class="py-5 px-6 text-[10px] font-black text-emerald-600 uppercase tracking-[0.2em]">Bidang</th>
                                    <th class="py-5 px-6 text-[10px] font-black text-emerald-600 uppercase tracking-[0.2em]">Waktu</th>
                                    <th class="py-5 px-6 text-[10px] font-black text-emerald-600 uppercase tracking-[0.2em] text-center">Status</th>
                                    @auth
                                        <th class="py-5 px-6 text-[10px] font-black text-emerald-600 uppercase tracking-[0.2em] text-center">Aksi</th>
                                    @endauth
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($disabilitas as $queue)
                                    @php
                                        $isFinished = ($queue->status == 'finished');
                                        $isCancelled = ($queue->status == 'cancelled');
                                        $isActive = ($queue->status == 'calling');
                                    @endphp
                                    <tr class="group/row transition-all border-b border-slate-50 last:border-0 
                                        {{ ($isFinished || $isCancelled) ? 'status-muted' : '' }} 
                                        {{ $isActive ? 'status-active-row' : '' }}
                                        {{ $queue->id == $myAntrianId ? 'bg-emerald-50/50' : '' }}">
                                        <td class="py-4 px-6">
                                            <span class="font-mono font-black text-lg {{ $isFinished ? 'text-slate-400' : 'text-slate-900' }} priority-green-text">D-{{ str_pad($queue->nomor, 3, '0', STR_PAD_LEFT) }}</span>
                                        </td>
                                        <td class="py-4 px-6">
                                            <span class="text-xs font-bold {{ $isFinished ? 'text-slate-400' : 'text-slate-700' }} truncate block max-w-[80px]">{{ ucfirst($queue->layanan) }}</span>
                                        </td>
                                        <td class="py-4 px-6">
                                            <span class="text-[10px] font-black {{ $isFinished ? 'text-slate-300' : 'text-emerald-500' }}">{{ \Carbon\Carbon::parse($queue->created_at)->format('H:i') }}</span>
                                        </td>
                                        <td class="py-4 px-6 text-center">
                                            @if($isFinished)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[8px] font-black bg-slate-100 text-slate-400 uppercase tracking-tighter">Selesai</span>
                                            @elseif($isCancelled)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[8px] font-black bg-red-50 text-red-300 uppercase tracking-tighter">Batal</span>
                                            @elseif($isActive)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[8px] font-black bg-emerald-600 text-white uppercase tracking-tighter animate-pulse">Dipanggil</span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[8px] font-black bg-emerald-100 text-emerald-600 uppercase tracking-tighter">Menunggu</span>
                                            @endif
                                        </td>
                                        @auth
                                        <td class="py-4 px-6 text-center">
                                            @if($isActive)
                                                <div class="flex items-center justify-center gap-2">
                                                    <button onclick="handleStatus('{{ $queue->id }}', 'calling')" title="Panggil Ulang" class="p-2 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 transition-colors shadow-lg shadow-emerald-200">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z" /></svg>
                                                    </button>
                                                    <button onclick="handleStatus('{{ $queue->id }}', 'finished')" title="Selesai" class="p-2 bg-emerald-500 text-white rounded-xl hover:bg-emerald-600 transition-colors shadow-lg shadow-emerald-200">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                                    </button>
                                                    <button onclick="handleStatus('{{ $queue->id }}', 'cancelled')" title="Batal" class="p-2 bg-red-500 text-white rounded-xl hover:bg-red-600 transition-colors shadow-lg shadow-red-200">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
                                                    </button>
                                                </div>
                                            @elseif($queue->status == 'waiting')
                                                <button onclick="handleStatus('{{ $queue->id }}', 'calling')" class="text-xs font-bold text-emerald-500 hover:text-emerald-700 hover:underline">Panggil</button>
                                            @endif
                                        </td>
                                        @endauth
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="py-20 px-6 text-center">
                                            <div class="flex flex-col items-center">
                                                <div
                                                    class="w-16 h-16 rounded-full bg-slate-50 flex items-center justify-center mb-4 border border-slate-100">
                                                    <svg class="w-8 h-8 text-slate-200" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                </div>
                                                <span
                                                    class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-300">Belum
                                                    Ada Antrian</span>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Info -->
        <div
            class="mt-12 p-8 glass-card rounded-[32px] border border-white flex flex-col sm:flex-row items-center justify-between gap-6">
            <div class="flex items-center gap-4">
                <img src="{{ asset('bps.png') }}" class="h-10 opacity-80" alt="BPS logo">
                <div class="flex flex-col">
                    <span class="text-slate-500 text-[10px] font-extrabold uppercase tracking-widest">BPS Kabupaten
                        Demak</span>
                    <span class="text-sm font-bold text-slate-800 tracking-tight">Pelayanan Statistik Terpadu</span>
                </div>
            </div>
            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] hidden sm:block">
                Sistem Informasi Pelayanan Terpadu & Terintegrasi
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        // Data for Voice
        @php
            $latest = $myAntrian->first();
            $label = '';
            if ($latest) {
                $pfx = match (strtolower($latest->jenis)) {
                    'layanan' => 'L',
                    'pengaduan' => 'P',
                    'disabilitas' => 'D',
                    default => 'A',
                };
                $label = $pfx . '-' . str_pad($latest->nomor, 3, '0', STR_PAD_LEFT);
            }
        @endphp
        const currentQueue = "{{ $label }}";
        const currentLoket = "01";

        function updateClock() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            document.getElementById('realTimeClock').textContent = `${hours}:${minutes}:${seconds}`;
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            document.getElementById('realTimeDate').textContent = now.toLocaleDateString('id-ID', options);
        }
        
        function announceQueue(queueNum, loketNum) {
            if (!queueNum) return;
            
            // Check if already announced
            const lastAnnounced = localStorage.getItem('last_announced_queue');
            if (lastAnnounced === queueNum) return;

            const synth = window.speechSynthesis;
            const textToSpeak = `Nomor antrian, ${queueNum.split('').join(' ')}, silakan menuju loket, ${loketNum}`;
            
            const utterThis = new SpeechSynthesisUtterance(textToSpeak);
            utterThis.lang = 'id-ID';
            utterThis.rate = 0.9;
            utterThis.pitch = 1;

            // Optional: Find Indonesian Voice if available
            const voices = synth.getVoices();
            const idVoice = voices.find(v => v.lang.includes('id-ID'));
            if (idVoice) utterThis.voice = idVoice;

            synth.speak(utterThis);
            localStorage.setItem('last_announced_queue', queueNum);
        }

        function toggleFullscreen() {
            // Fullscreen logic
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen();
            } else {
                if (document.exitFullscreen) document.exitFullscreen();
            }

            // Unmute/Trigger Sound Logic (Browser requirement: user gesture)
            localStorage.setItem('sound_enabled', 'true');
            document.getElementById('modeTVText').textContent = "Mode TV Aktif";
            document.getElementById('btnModeTV').classList.replace('bg-blue-600', 'bg-emerald-600');
            
            // Re-trigger announcement if enabled
            if (currentQueue) announceQueue(currentQueue, currentLoket);
        }

        // Initialize
        updateClock();
        setInterval(updateClock, 1000);

        // Run Announcement on Load if enabled
        window.addEventListener('load', () => {
            if (localStorage.getItem('sound_enabled') === 'true' && currentQueue) {
                // Small delay to ensure voices are loaded
                setTimeout(() => {
                    announceQueue(currentQueue, currentLoket);
                }, 1000);
            }
        });

        // Ensure voices are loaded (needed for some browsers)
        window.speechSynthesis.getVoices();

        async function handleStatus(id, newStatus) {
            let url = "";
            if (newStatus === 'calling') {
                url = `/queue/panggil/${id}`;
                // Clear voice announcement cache to allow re-playing the same number
                localStorage.removeItem('last_announced_queue');
            }
            else if (newStatus === 'finished') url = `/queue/selesai/${id}`;
            else if (newStatus === 'cancelled') url = `/queue/batal/${id}`;
            
            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (response.ok) {
                    // Refresh data without full page reload
                    // In a production app, we would use Livewire or specifically update DOM parts.
                    // For now, since auto-refresh is already 30s, we just location.reload() to show change immediately.
                    location.reload(); 
                }
            } catch (error) {
                console.error('Error updating status:', error);
            }
        }
    </script>
</body>

</html>
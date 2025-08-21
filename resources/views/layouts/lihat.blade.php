@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Daftar Antrian Hari Ini</h2>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No.</th>
                <th>Nomor Antrian</th>
                <th>Layanan</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($queues as $index => $queue)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $queue->nomor }}</td>
                    <td>{{ $queue->layanan }}</td>
                    <td>{{ \Carbon\Carbon::parse($queue->tanggal)->format('d-m-Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">Belum ada antrian hari ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

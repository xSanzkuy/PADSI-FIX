@extends('layouts.layout')

@section('title', 'Laporan Transaksi')

@section('content')
<div class="container mt-5">
    <div class="d-flex justify-content-between mb-4">
        <h3 class="fw-bold">Laporan Transaksi</h3>
        <!-- Tombol Export to PDF -->
        <a href="{{ route('reports.exportPDF', ['start_date' => $startDate, 'end_date' => $endDate]) }}" class="btn btn-danger" style="border-radius: 50px;">
            <i class="fas fa-file-pdf"></i> Export to PDF
        </a>
    </div>
    <div class="card shadow-lg mb-4">
        <div class="card-header bg-primary text-white text-center">
            <h4>Filter Laporan Transaksi</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('reports.index') }}" method="GET" class="row mb-4">
                <div class="col-md-4">
                    <label for="start_date" class="form-label">Tanggal Mulai</label>
                    <input type="date" name="start_date" id="start_date" value="{{ $startDate }}" class="form-control">
                </div>
                <div class="col-md-4">
                    <label for="end_date" class="form-label">Tanggal Akhir</label>
                    <input type="date" name="end_date" id="end_date" value="{{ $endDate }}" class="form-control">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100" style="border-radius: 50px;">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                </div>
            </form>

            @if($transactions->isEmpty())
                <div class="alert alert-warning text-center">
                    Tidak ada transaksi yang ditemukan untuk tanggal yang dipilih.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="bg-light">
                            <tr class="text-center">
                                <th>ID Transaksi</th>
                                <th>Tanggal</th>
                                <th>Pegawai</th>
                                <th>Total Bayar</th>
                                <th>Nominal Pembayaran</th>
                                <th>Kembalian</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transactions as $transaction)
                                <tr>
                                    <td class="text-center">{{ $transaction->id }}</td>
                                    <td>{{ $transaction->tanggal }}</td>
                                    <td>{{ $transaction->pegawai ? $transaction->pegawai->nama : 'Pegawai tidak tersedia' }}</td>
                                    <td>Rp {{ number_format($transaction->total_bayar, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($transaction->nominal, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($transaction->kembalian, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@extends('layouts.layout')

@section('title', 'Laporan Transaksi')

@section('content')
<div class="container mt-5">
    <div class="d-flex justify-content-between mb-4">
        <!-- Judul Halaman -->
        <h1 class="display-4 fw-bold text-primary-custom">📊 Laporan Transaksi</h1>
        
        <!-- Tombol Export to PDF -->
        <a href="{{ route('reports.exportPDF', ['start_date' => $startDate, 'end_date' => $endDate]) }}" class="btn btn-danger btn-lg rounded-pill">
            <i class="fas fa-file-pdf"></i> Export to PDF
        </a>
    </div>
    
    <!-- Filter Laporan -->
    <div class="card shadow-lg mb-4 border-0 rounded-lg">
        <div class="card-header bg-primary text-white text-center">
            <h4 class="mb-0">Filter Laporan Transaksi</h4>
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
                    <button type="submit" class="btn btn-primary-custom w-100 rounded-pill">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                </div>
            </form>

            @if($transactions->isEmpty())
                <div class="alert alert-warning text-center">
                    Tidak ada transaksi yang ditemukan untuk tanggal yang dipilih.
                </div>
            @else
                <!-- Tabel Transaksi -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-header">
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
                                    <td class="text-end">Rp {{ number_format($transaction->total_bayar, 0, ',', '.') }}</td>
                                    <td class="text-end">Rp {{ number_format($transaction->nominal, 0, ',', '.') }}</td>
                                    <td class="text-end">Rp {{ number_format($transaction->kembalian, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $transactions->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

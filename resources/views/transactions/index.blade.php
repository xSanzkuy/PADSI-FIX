@extends('layouts.layout')

@section('title', 'Daftar Transaksi')

@section('content')
<div class="container mt-5">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Sukses!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold page-title">📋 Daftar Transaksi</h3>
        <a href="{{ route('transactions.create') }}" class="btn btn-primary shadow rounded-pill px-4 py-2">
            <i class="fas fa-plus-circle me-2"></i> Tambah Transaksi
        </a>
    </div>

    <div class="card shadow-lg mb-4 border-0 rounded-lg">
        <div class="card-header text-white text-center table-header">
            <h4 class="mb-0">Daftar Transaksi</h4>
        </div>
        <div class="card-body p-4">
            @if($transactions->isEmpty())
                <div class="alert alert-warning text-center">
                    Tidak ada transaksi yang ditemukan.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="bg-light">
                            <tr class="text-center table-head-row">
                                <th>ID Transaksi</th>
                                <th>Tanggal</th>
                                <th>Pegawai</th>
                                <th>Nama Member</th>
                                <th>Total Bayar</th>
                                <th>Nominal Pembayaran</th>
                                <th>Kembalian</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transactions as $transaction)
                                <tr>
                                    <td class="text-center">{{ $transaction->id }}</td>
                                    <td>{{ $transaction->tanggal }}</td>
                                    <td>{{ $transaction->pegawai ? $transaction->pegawai->nama : 'Pegawai tidak tersedia' }}</td>
                                    <td>{{ $transaction->member ? $transaction->member->nama : 'Tidak Ada Member' }}</td>
                                    <td class="text-end">Rp {{ number_format($transaction->total_bayar, 0, ',', '.') }}</td>
                                    <td class="text-end">Rp {{ number_format($transaction->nominal, 0, ',', '.') }}</td>
                                    <td class="text-end">Rp {{ number_format($transaction->kembalian, 0, ',', '.') }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('transactions.details', $transaction->id) }}" class="btn btn-info btn-sm me-2 shadow-sm rounded-pill">
                                            <i class="fas fa-eye"></i> Detail
                                        </a>
                                        <a href="{{ route('transactions.print', $transaction->id) }}" class="btn btn-primary btn-sm shadow-sm rounded-pill" target="_blank">
                                            <i class="fas fa-print"></i> Cetak
                                        </a>
                                    </td>
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

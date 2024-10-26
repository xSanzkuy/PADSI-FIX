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
    <div class="d-flex justify-content-between mb-4">
        <h3 class="fw-bold">Daftar Transaksi</h3>
        <a href="{{ route('transactions.create') }}" class="btn btn-success btn-lg" style="border-radius: 50px; padding: 10px 30px; box-shadow: 0px 4px 10px rgba(0, 123, 255, 0.2); transition: all 0.3s;"><i class="fas fa-plus-circle"></i> Tambah Transaksi</a>
    </div>
    <div class="card shadow-lg mb-4">
        <div class="card-header bg-primary text-white text-center">
            <h4>Daftar Transaksi</h4>
        </div>
        <div class="card-body">
            @if($transactions->isEmpty())
                <div class="alert alert-warning text-center">
                    Tidak ada transaksi yang ditemukan.
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
                                <th>Aksi</th>
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
                                    <td class="text-center">
                                        <a href="{{ route('transactions.details', $transaction->id) }}" class="btn btn-info btn-sm me-2" style="border-radius: 50px; box-shadow: 0px 4px 8px rgba(0, 123, 255, 0.1); transition: all 0.3s;"><i class="fas fa-eye"></i> Detail</a>
                                        <form action="{{ route('transactions.destroy', $transaction->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" style="border-radius: 50px; box-shadow: 0px 4px 8px rgba(255, 0, 0, 0.1); transition: all 0.3s;"><i class="fas fa-trash-alt"></i> Hapus</button>
                                        </form>
                                    </td>
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

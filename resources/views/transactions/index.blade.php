@extends('layouts.layout')

@section('title', 'Daftar Transaksi')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3">Daftar Transaksi</h1>
        <a href="{{ route('transactions.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Transaksi
        </a>
    </div>
    
    <div class="card">
        <div class="card-body">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Pegawai</th>
                        <th>Total Bayar</th>
                        <th>Nominal</th>
                        <th>Kembalian</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transactions as $transaction)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $transaction->tanggal }}</td>
                            <td>{{ $transaction->pegawai->nama }}</td>
                            <td>Rp {{ number_format($transaction->total_bayar, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($transaction->nominal, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($transaction->kembalian, 0, ',', '.') }}</td>
                            <td class="d-flex gap-2">
                                <a href="{{ route('transactions.details', $transaction->id) }}" class="btn btn-info btn-sm">Detail</a>
                                <a href="{{ route('transactions.edit', $transaction->id) }}" class="btn btn-warning btn-sm ml-2">Edit</a>
                                <form action="{{ route('transactions.destroy', $transaction->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm ml-2" onclick="return confirm('Yakin ingin menghapus transaksi ini?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada transaksi!</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

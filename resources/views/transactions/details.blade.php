@extends('layouts.layout')

@section('title', 'Detail Transaksi')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h3>Detail Transaksi #{{ $transaction->id }}</h3>
        </div>
        <div class="card-body">
            <p><strong>Tanggal:</strong> {{ $transaction->tanggal }}</p>
            <p><strong>Pegawai:</strong> {{ $transaction->pegawai ? $transaction->pegawai->nama : 'Pegawai tidak tersedia' }}</p>
            <p><strong>Total Bayar:</strong> Rp {{ number_format($transaction->total_bayar, 0, ',', '.') }}</p>
            <p><strong>Nominal Pembayaran:</strong> Rp {{ number_format($transaction->nominal, 0, ',', '.') }}</p>
            <p><strong>Kembalian:</strong> Rp {{ number_format($transaction->kembalian, 0, ',', '.') }}</p>
            <hr>
            <h4>Daftar Produk</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Gambar</th>
                        <th>Nama Produk</th>
                        <th>Harga Satuan</th>
                        <th>Jumlah</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($transaction->details as $detail)
                    @if($detail->product) <!-- Pastikan data produk tersedia -->
                        <tr>
                            <td>
                                <img src="{{ asset('storage/' . $detail->product->gambar) }}" alt="{{ $detail->product->nama_produk }}" class="img-thumbnail" style="width: 100px; height: 100px;">
                            </td>
                            <td>{{ $detail->product->nama_produk }}</td>
                            <td>Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                            <td>{{ $detail->jumlah }}</td>
                            <td>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @else
                        <tr>
                            <td colspan="5">Produk tidak tersedia</td>
                        </tr>
                    @endif
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            <a href="{{ route('transactions.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </div>
</div>
@endsection

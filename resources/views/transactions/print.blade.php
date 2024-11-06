@extends('layouts.print')

@section('title', 'Cetak Transaksi')

@section('content')
<div class="container mt-5">
    <div class="text-center mb-4">
        <h2 class="fw-bold">🧾 Rincian Transaksi</h2>
        <p>ID Transaksi: {{ $transaction->id }}</p>
        <p>Tanggal Transaksi: {{ \Carbon\Carbon::parse($transaction->tanggal)->format('d-m-Y H:i') }}</p>
    </div>

    <table class="table table-bordered">
        <tr>
            <th width="30%">Tanggal</th>
            <td>{{ \Carbon\Carbon::parse($transaction->tanggal)->format('d-m-Y') }}</td>
        </tr>
        <tr>
            <th>Pegawai</th>
            <td>{{ $transaction->pegawai ? $transaction->pegawai->nama : 'Pegawai tidak tersedia' }}</td>
        </tr>
        <tr>
            <th>Member</th>
            <td>{{ $transaction->member ? $transaction->member->nama . ' (' . ucfirst($transaction->member->tingkat) . ')' : 'Tidak ada member' }}</td>
        </tr>
        <tr>
            <th>Nomor Telepon Pelanggan</th>
            <td>{{ $transaction->telp_pelanggan ?? '-' }}</td>
        </tr>
        <tr>
            <th>Total Bayar</th>
            <td>Rp {{ number_format($transaction->total_bayar, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <th>Nominal Pembayaran</th>
            <td>Rp {{ number_format($transaction->nominal, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <th>Kembalian</th>
            <td>Rp {{ number_format($transaction->kembalian, 0, ',', '.') }}</td>
        </tr>
    </table>

    <h4 class="mt-4">Detail Produk yang Dibeli</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Produk</th>
                <th>Jumlah</th>
                <th>Harga Satuan</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
    @foreach($transaction->details as $detail)
        <tr>
            <td>
                {{ $detail->product ? $detail->product->nama_produk : 'Produk tidak ditemukan' }}
            </td>
            <td>{{ $detail->jumlah }}</td>
            <td>Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
            <td>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
        </tr>
    @endforeach
</tbody>

    </table>

    <div class="text-center mt-4">
        <button onclick="window.print()" class="btn btn-primary">
            <i class="fas fa-print"></i> Cetak
        </button>
    </div>
</div>
@endsection

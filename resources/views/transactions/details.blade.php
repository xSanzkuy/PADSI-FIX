@extends('layouts.layout')

@section('title', 'Detail Transaksi')

@section('content')
<div class="container mt-5">
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white text-center">
            <h3>Detail Transaksi #{{ $transaction->id }}</h3>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <p><i class="fas fa-calendar-alt"></i> <strong>Tanggal:</strong> {{ $transaction->tanggal }}</p>
                    <p><i class="fas fa-user"></i> <strong>Pegawai:</strong> {{ $transaction->pegawai ? $transaction->pegawai->nama : 'Pegawai tidak tersedia' }}</p>
                </div>
                <div class="col-md-6">
                    @if ($transaction->telp_pelanggan)
                        @php
                            $member = \App\Models\Member::where('no_hp', $transaction->telp_pelanggan)->first();
                            $diskonPersentase = 0;
                            $diskonText = 'Tidak ada';
                            if ($member) {
                                switch ($member->tingkat) {
                                    case 'bronze':
                                        $diskonPersentase = 5;
                                        $diskonText = '5%';
                                        break;
                                    case 'silver':
                                        $diskonPersentase = 10;
                                        $diskonText = '10%';
                                        break;
                                    case 'gold':
                                        $diskonPersentase = 15;
                                        $diskonText = '15%';
                                        break;
                                }
                            }
                            $totalDiskon = ($transaction->total_bayar / (1 - $diskonPersentase / 100)) - $transaction->total_bayar;
                        @endphp
                        <p><i class="fas fa-id-card"></i> <strong>Member:</strong> {{ $member->nama }} ({{ ucfirst($member->tingkat) }})</p>
                        <p><i class="fas fa-percent"></i> <strong>Diskon Member:</strong> {{ $diskonText }}</p>
                        <p><i class="fas fa-tags"></i> <strong>Total Diskon:</strong> Rp {{ number_format($totalDiskon, 0, ',', '.') }}</p>
                    @endif
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="alert alert-info">
                        <strong>Total Bayar:</strong> Rp {{ number_format($transaction->total_bayar, 0, ',', '.') }}
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="alert alert-warning">
                        <strong>Nominal Pembayaran:</strong> Rp {{ number_format($transaction->nominal, 0, ',', '.') }}
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="alert alert-success">
                        <strong>Kembalian:</strong> Rp {{ number_format($transaction->kembalian, 0, ',', '.') }}
                    </div>
                </div>
            </div>
            <hr>
            <h4 class="text-center mb-4"><i class="fas fa-shopping-cart"></i> Daftar Produk</h4>
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="bg-light">
                        <tr class="text-center">
                            <th>Gambar</th>
                            <th>Nama Produk</th>
                            <th>Harga Satuan</th>
                            <th>Diskon (%)</th>
                            <th>Harga Setelah Diskon</th>
                            <th>Jumlah</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($transaction->details as $detail)
                        @if($detail->product) <!-- Pastikan data produk tersedia -->
                            @php
                                $hargaDiskon = $detail->harga_satuan;
                                if (isset($diskonPersentase) && $diskonPersentase > 0) {
                                    $hargaDiskon = $detail->harga_satuan * (1 - $diskonPersentase / 100);
                                }
                            @endphp
                            <tr>
                                <td class="text-center">
                                    <img src="{{ asset('storage/' . $detail->product->gambar) }}" alt="{{ $detail->product->nama_produk }}" class="img-thumbnail" style="width: 80px; height: 80px;">
                                </td>
                                <td>{{ $detail->product->nama_produk }}</td>
                                <td>Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                                <td class="text-center">{{ $diskonPersentase }}%</td>
                                <td>Rp {{ number_format($hargaDiskon, 0, ',', '.') }}</td>
                                <td class="text-center">{{ $detail->jumlah }}</td>
                                <td>Rp {{ number_format($hargaDiskon * $detail->jumlah, 0, ',', '.') }}</td>
                            </tr>
                        @else
                            <tr>
                                <td colspan="7" class="text-center">Produk tidak tersedia</td>
                            </tr>
                        @endif
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer text-center">
            <a href="{{ route('transactions.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
        </div>
    </div>
</div>
@endsection

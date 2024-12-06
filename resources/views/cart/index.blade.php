@extends('layouts.layout')

@section('title', 'Keranjang Belanja Anda')

@section('content')
<div class="container mt-5">
    <h2 class="text-center mb-4">Keranjang Belanja Anda</h2>
    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Gambar</th>
                        <th>Produk</th>
                        <th>Harga</th>
                        <th>Jumlah</th>
                        <th>Total Harga</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cartItems as $item)
                    <tr>
                        <td>
                            @if ($item->product)
                                <img src="{{ asset('storage/' . $item->product->gambar) }}" alt="{{ $item->product->nama_produk }}" width="80">
                            @else
                                <span class="text-danger">Produk tidak ditemukan</span>
                            @endif
                        </td>
                        <td>{{ $item->product ? $item->product->nama_produk : 'Tidak diketahui' }}</td>
                        <td>{{ $item->product ? 'Rp ' . number_format($item->product->harga, 0, ',', '.') : '-' }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ $item->product ? 'Rp ' . number_format($item->total_price, 0, ',', '.') : '-' }}</td>
                        <td>
                            <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">Keranjang Anda kosong</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            @if($cartItems->isNotEmpty())
            <!-- Total Harga & Tombol Checkout -->
            <div class="text-right">
                <h5>Total: Rp {{ number_format($cartItems->sum('total_price'), 0, ',', '.') }}</h5>
                <a href="{{ route('transactions.create') }}" class="btn btn-primary mt-3">Checkout</a>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
    .container {
        max-width: 900px;
    }
    .table img {
        border-radius: 8px;
    }
    .text-right h5 {
        margin-top: 20px;
    }
</style>
@endsection

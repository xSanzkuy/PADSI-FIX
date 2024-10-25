@extends('layouts.layout')

@section('title', 'Menu Restoran')

@section('content')
<div class="container mt-5">
    <div class="row text-center mb-4">
        <h1 class="display-4 fw-bold text-danger">Menu Restoran Kami</h1>
        <p class="lead">Kami menyajikan hidangan lezat dengan bahan segar dan rasa yang tak terlupakan. Pilih menu favorit Anda!</p>
    </div>

    <!-- Form Pencarian Produk -->
    <div class="text-center mb-4">
        <form action="{{ route('products.index') }}" method="GET" class="d-flex justify-content-center">
            <input type="text" name="search" class="form-control w-50 me-2" placeholder="Cari produk..." value="{{ request()->input('search') }}">
            <button type="submit" class="btn btn-primary" style="background: #e63946; color: #fff; font-weight: bold;">Cari</button>
        </form>
    </div>

    <!-- Tombol Tambah Produk -->
    <div class="text-center mb-4">
        <a href="{{ route('products.create') }}" class="btn btn-lg btn-primary shadow" style="background: #e63946; color: #fff; font-weight: bold;">+ Tambah Produk Baru</a>
    </div>

    <!-- Daftar Produk -->
    <div class="row g-4">
        @foreach($products as $product)
        <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="card shadow-sm h-100 border-0 rounded-lg overflow-hidden">
                <div class="position-relative">
                    <img src="{{ asset('storage/' . $product->gambar) }}" class="card-img-top" alt="{{ $product->nama_produk }}" style="height: 250px; object-fit: cover;">
                </div>
                <div class="card-body text-center">
                    <h5 class="card-title fw-bold text-primary">{{ ucfirst($product->nama_produk) }}</h5>
                    <p class="card-text text-muted">Harga: <span class="text-success fw-bold">Rp {{ number_format($product->harga, 0, ',', '.') }}</span></p>
                    <p class="card-text">Stok Tersedia: <span class="fw-bold">{{ $product->stok }}</span></p>
                    <div class="d-flex justify-content-between mt-3">
                        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning btn-sm w-45 text-white">Edit Produk</a>
                        <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="w-45">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm w-100" onclick="return confirm('Yakin ingin menghapus produk ini?')">Hapus Produk</button>
                        </form>
                    </div>
                    <button class="btn btn-danger btn-block w-100 mt-2">Pesan Sekarang</button>
                </div>
            </div>
        </div>
        @endforeach

        @if($products->isEmpty())
            <div class="col-12">
                <div class="alert alert-warning text-center">
                    Produk tidak ditemukan.
                </div>
            </div>
        @endif
    </div>
</div>

<style>
    body {
        background: #f9f5f0; /* Latar belakang dengan warna lembut */
    }
    .card {
        transition: transform 0.3s, box-shadow 0.3s; /* Efek transisi untuk kartu */
    }
    .card:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2); /* Efek bayangan pada hover */
    }
    .card-title {
        font-size: 1.4rem;
    }
    .btn-danger {
        background-color: #e63946; /* Warna merah untuk tombol Pesan Sekarang */
        border: none;
    }
    .btn-danger:hover {
        background-color: #d62828; /* Warna lebih gelap saat di-hover */
    }
    .btn-primary {
        border-radius: 50px; /* Membuat tombol Tambah Produk lebih menarik */
    }
    .btn-warning {
        background-color: #f4a261; /* Warna oranye yang lebih lembut */
        border: none;
    }
    .btn-warning:hover {
        background-color: #e76f51; /* Warna lebih gelap saat di-hover */
    }
    .container h1 {
        font-size: 3rem;
    }
    @media only screen and (max-width: 450px) {
        .form {
            max-width: 25rem;
        }
    }
</style>
@endsection

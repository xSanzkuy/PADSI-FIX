@extends('layouts.layout')

@section('title', 'Tambah Produk')

@section('content')
<div class="container mt-5">
    <div class="row text-center mb-4">
        <h1 class="display-4 fw-bold text-danger">Tambah Produk Baru</h1>
        <p class="lead">Isi detail produk di bawah ini dan tambahkan ke menu restoran kami.</p>
    </div>

    {{-- Alert untuk pesan sukses --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Alert untuk pesan error --}}
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="shadow p-4 rounded bg-white">
        @csrf
        <div class="mb-3">
            <label for="nama_produk" class="form-label">Nama Produk</label>
            <input type="text" class="form-control" id="nama_produk" name="nama_produk" required>
        </div>
        <div class="mb-3">
            <label for="stok" class="form-label">Stok</label>
            <input type="number" class="form-control" id="stok" name="stok" required>
        </div>
        <div class="mb-3">
            <label for="harga" class="form-label">Harga</label>
            <input type="number" class="form-control" id="harga" name="harga" required>
        </div>
        <div class="mb-3">
            <label for="gambar" class="form-label">Gambar Produk</label>
            <input type="file" class="form-control" id="gambar" name="gambar" required>
        </div>
        <div class="text-center">
            <button type="submit" class="btn btn-lg btn-primary mt-3" style="background-color: #e63946; border: none;">Simpan Produk</button>
            <a href="{{ route('products.index') }}" class="btn btn-lg btn-secondary mt-3">Batal</a>
        </div>
    </form>
</div>

<style>
    body {
        background: #f9f5f0;
    }
    .shadow {
        box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.1);
    }
    .form-label {
        font-weight: bold;
        color: #333;
    }
    .btn-primary {
        border-radius: 50px;
    }
    .btn-secondary {
        color: #fff;
        background-color: #6c757d;
        border: none;
        border-radius: 50px;
    }
    .btn-secondary:hover {
        background-color: #5a6268;
    }
</style>
@endsection

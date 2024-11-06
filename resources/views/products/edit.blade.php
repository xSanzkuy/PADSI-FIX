@extends('layouts.layout')

@section('title', 'Edit Produk')

@section('content')
<div class="container mt-5">
    <div class="row text-center mb-4">
        <h1 class="display-4 fw-bold text-danger">Edit Produk</h1>
        <p class="lead">Perbarui detail produk Anda di bawah ini.</p>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm p-4">
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

                <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data" id="updateForm">
                    @csrf
                    @method('PUT')
                    
                    <!-- Nama Produk -->
                    <div class="mb-3">
                        <label for="nama_produk" class="form-label">Nama Produk</label>
                        <input type="text" class="form-control @error('nama_produk') is-invalid @enderror" id="nama_produk" name="nama_produk" value="{{ old('nama_produk', $product->nama_produk) }}" required>
                        @error('nama_produk')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <!-- Stok Produk -->
                    <div class="mb-3">
                        <label for="stok" class="form-label">Stok</label>
                        <input type="number" class="form-control @error('stok') is-invalid @enderror" id="stok" name="stok" value="{{ old('stok', $product->stok) }}" required min="0">
                        @error('stok')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <!-- Harga Produk -->
                    <div class="mb-3">
                        <label for="harga" class="form-label">Harga</label>
                        <input type="number" class="form-control @error('harga') is-invalid @enderror" id="harga" name="harga" value="{{ old('harga', $product->harga) }}" required min="0">
                        @error('harga')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <!-- Gambar Produk -->
                    <div class="mb-3">
                        <label for="gambar" class="form-label">Gambar Produk (Opsional)</label>
                        <input type="file" class="form-control @error('gambar') is-invalid @enderror" id="gambar" name="gambar" accept="image/*">
                        @error('gambar')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror

                        @if($product->gambar)
                        <div class="mt-3">
                            <p>Gambar Saat Ini:</p>
                            <img src="{{ asset('storage/' . $product->gambar) }}" alt="{{ $product->nama_produk }}" class="img-thumbnail" style="height: 150px; object-fit: cover;">
                        </div>
                        @endif
                    </div>

                    <!-- Tombol Simpan -->
                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-success btn-lg">Perbarui Produk</button>
                        <a href="{{ route('products.index') }}" class="btn btn-secondary btn-lg ms-3">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Cek jika ada session sukses, maka tampilkan alert JavaScript
        @if(session('success'))
            alert('Produk berhasil diperbarui!');
        @endif
    });
</script>

<!-- Custom CSS -->
<style>
    body {
        background: #f9f5f0; /* Latar belakang dengan warna lembut */
    }
    .card {
        border-radius: 15px;
    }
    .btn-success {
        background-color: #38b000; /* Hijau terang */
        border: none;
    }
    .btn-success:hover {
        background-color: #2d6a4f; /* Hijau lebih gelap */
    }
    .btn-secondary {
        border-radius: 50px; /* Membuat tombol Batal lebih menarik */
    }
</style>
@endsection

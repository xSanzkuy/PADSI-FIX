@extends('layouts.layout')

@section('title', 'Menu Restoran')

@section('content')
<div class="container mt-5">
    <div class="row text-center mb-4">
        <h1 class="display-4 fw-bold text-danger">Menu Restoran Kami</h1>
        <p class="lead">Kami menyajikan hidangan lezat dengan bahan segar dan rasa yang tak terlupakan. Pilih menu favorit Anda!</p>
    </div>

    <!-- Alert Pesan -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Form Pencarian Produk -->
    <div class="text-center mb-4">
        <form action="{{ route('products.index') }}" method="GET" class="d-flex justify-content-center">
            <input type="text" name="search" class="form-control w-50 me-2 rounded-pill" placeholder="Cari produk..." value="{{ request()->input('search') }}">
            <button type="submit" class="btn btn-primary rounded-pill px-4" style="background: #e63946; color: #fff; font-weight: bold;">Cari</button>
        </form>
    </div>

    <!-- Tombol Tambah Produk -->
    <div class="text-center mb-4">
        <a href="{{ route('products.create') }}" class="btn btn-lg btn-primary shadow rounded-pill px-4" style="background: #e63946; color: #fff; font-weight: bold;">+ Tambah Produk Baru</a>
    </div>

    <!-- Daftar Produk -->
    <div class="row g-4">
        @foreach($products as $product)
        <div class="col-lg-4 col-md-6 col-sm-12" id="product-card-{{ $product->id }}">
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
                        <button type="button" class="btn btn-danger btn-sm w-45" onclick="confirmDelete({{ $product->id }})">Hapus Produk</button>
                    </div>
                    <!-- Tombol Tambah ke Keranjang -->
                    <button class="btn btn-danger btn-block w-100 mt-2 add-to-cart-btn" data-id="{{ $product->id }}" data-url="{{ route('add.to.cart', $product->id) }}">Tambah ke Keranjang</button>
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

@push('scripts')
<script src="{{ mix('js/cartAnimation.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- Import SweetAlert2 -->

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Tombol Tambah ke Keranjang
        const addToCartButtons = document.querySelectorAll(".add-to-cart-btn");

        // Pastikan event listener hanya dipasang sekali
        addToCartButtons.forEach(button => {
            button.addEventListener("click", debounce(handleAddToCart, 500));
        });
    });

    // Fungsi untuk menangani tombol Tambah ke Keranjang
    function handleAddToCart(event) {
        const button = event.currentTarget;
        const productId = button.getAttribute("data-id");
        const url = button.getAttribute("data-url");

        // Disable tombol sementara
        button.disabled = true;

        fetch(url, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                "Accept": "application/json"
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                animateCart(button); // Jalankan animasi
            } else {
                console.error("Gagal menambahkan ke keranjang:", data.message);
            }
        })
        .catch(error => {
            console.error("Error:", error);
        })
        .finally(() => {
            // Aktifkan kembali tombol setelah selesai
            button.disabled = false;
        });
    }

    // Fungsi untuk animasi keranjang
    function animateCart(button) {
        const cartIcon = document.querySelector('.cart-icon');
        const img = button.closest(".card").querySelector("img");

        const imgClone = img.cloneNode();
        imgClone.style.position = "absolute";
        imgClone.style.zIndex = "1000";
        imgClone.style.width = "100px";
        imgClone.style.height = "100px";
        imgClone.style.transition = "all 1s ease-in-out";

        document.body.appendChild(imgClone);

        const rect = img.getBoundingClientRect();
        imgClone.style.top = rect.top + "px";
        imgClone.style.left = rect.left + "px";

        const cartRect = cartIcon.getBoundingClientRect();
        setTimeout(() => {
            imgClone.style.top = cartRect.top + "px";
            imgClone.style.left = cartRect.left + "px";
            imgClone.style.transform = "scale(0.1)";
            imgClone.style.opacity = "0";
        }, 10);

        setTimeout(() => imgClone.remove(), 1000);
    }

    // Fungsi konfirmasi hapus produk
   function confirmDelete(productId) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Produk ini tidak dapat dikembalikan setelah dihapus.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#e63946',
        cancelButtonColor: '#d6d6d6'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/master/products/${productId}`, { // Sesuaikan endpoint
                method: 'DELETE',
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                    "Accept": "application/json"
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: 'Produk berhasil dihapus.',
                        icon: 'success',
                        confirmButtonColor: '#4e73df'
                    }).then(() => {
                        document.getElementById(`product-card-${productId}`).remove();
                    });
                } else {
                    Swal.fire({
                        title: 'Gagal!',
                        text: data.message || 'Terjadi kesalahan saat menghapus produk.',
                        icon: 'error',
                        confirmButtonColor: '#e63946'
                    });
                }
            })
            .catch(() => {
                Swal.fire({
                    title: 'Gagal!',
                    text: 'Terjadi kesalahan pada server.',
                    icon: 'error',
                    confirmButtonColor: '#e63946'
                });
            });
        }
    });
}


    // Fungsi debounce untuk mencegah klik berulang dalam waktu singkat
    function debounce(func, wait) {
        let timeout;
        return function (...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), wait);
        };
    }
</script>
@endpush

<style>
    body {
        background: #f9f5f0;
    }
    .card {
        transition: transform 0.3s, box-shadow 0.3s;
    }
    .card:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }
    .card-title {
        font-size: 1.4rem;
    }
    .btn-primary {
        border-radius: 50px;
    }
    .btn-warning {
        background-color: #f4a261;
        border: none;
    }
    .btn-warning:hover {
        background-color: #e76f51;
    }
    .btn-danger {
        background-color: #e63946;
        border: none;
    }
    .btn-danger:hover {
        background-color: #d62828;
    }
    .container h1 {
        font-size: 3rem;
    }
</style>
@endsection

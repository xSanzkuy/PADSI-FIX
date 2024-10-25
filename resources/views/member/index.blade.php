@extends('layouts.layout')

@section('title', 'Daftar Member')

@section('content')
<div class="container mt-5">
    <div class="row mb-4">
        <div class="col-md-6 d-flex mb-3 mb-md-0">
            <!-- Form Pencarian -->
            <form action="{{ route('member.index') }}" method="GET" class="d-flex w-100">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Member..." class="form-control me-2 shadow-sm">
                <button type="submit" class="btn btn-primary shadow-sm">Cari</button>
            </form>
        </div>
        <div class="col-md-6 d-flex justify-content-md-end justify-content-start">
            <!-- Tombol Tambah Member -->
            <a href="{{ route('member.create') }}" class="btn btn-success shadow-sm fw-bold">+ Tambah Member Baru</a>
        </div>
    </div>

    @if($members->isEmpty())
        <div class="alert alert-warning text-center">Tidak ada member yang ditemukan.</div>
    @else
        <div class="row row-cols-1 row-cols-md-3 g-4">
            @foreach($members as $member)
                <div class="col">
                    <div class="card shadow-sm h-100 border-0 rounded-lg overflow-hidden">
                        <div class="card-body text-center">
                            <div class="avatar mb-3">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($member->nama) }}&background=4e73df&color=fff&size=100" class="rounded-circle img-fluid" alt="{{ $member->nama }}">
                            </div>
                            <h5 class="card-title fw-bold text-primary">{{ ucfirst($member->nama) }}</h5>
                            <p class="card-text text-muted">Nomor HP: <span class="fw-bold">{{ $member->no_hp }}</span></p>
                            <p class="card-text">Tingkat: <span class="badge bg-secondary">{{ ucfirst($member->tingkat) }}</span></p>
                            <p class="card-text">Total Transaksi: <span class="fw-bold">Rp {{ number_format($member->total_transaksi, 0, ',', '.') }}</span></p>
                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ route('member.edit', $member->id) }}" class="btn btn-warning btn-sm text-white shadow-sm">Edit</a>
                                <form action="{{ route('member.destroy', $member->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm shadow-sm" onclick="return confirm('Yakin ingin menghapus member ini?')">Hapus</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
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
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15); /* Efek bayangan pada hover */
    }
    .card-title {
        font-size: 1.5rem;
    }
    .avatar img {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border: 4px solid #e9ecef;
    }
    .btn-success {
        background-color: #28a745;
        border: none;
        padding: 0.75rem 1.25rem;
        font-size: 1rem;
    }
    .btn-warning {
        background-color: #f4a261;
        border: none;
    }
    .btn-warning:hover {
        background-color: #e76f51;
    }
    .btn-primary {
        background-color: #4e73df;
        border: none;
    }
    .btn-danger {
        background-color: #e63946;
        border: none;
    }
    .btn-danger:hover {
        background-color: #d62828;
    }
</style>
@endsection
@extends('layouts.layout')

@section('title', 'Daftar Member')

@section('content')
<div class="container mt-5">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row mb-4">
        <div class="col-md-6 d-flex mb-3 mb-md-0">
            <form action="{{ route('member.index') }}" method="GET" class="d-flex w-100">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Member..." class="form-control me-2 shadow-sm">
                <button type="submit" class="btn btn-primary shadow-sm">Cari</button>
            </form>
        </div>
        <div class="col-md-6 d-flex justify-content-md-end justify-content-start">
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
                            <p class="card-text">Tingkat: 
                                <span 
                                    class="badge" 
                                    style="background-color: {{ $member->tingkat == 'gold' ? '#FFD700' : ($member->tingkat == 'silver' ? '#C0C0C0' : '#CD7F32') }}; color: #000;">
                                    {{ $member->tingkat ?? 'Tidak Ada' }}
                                </span>
                            </p>
                            <p class="card-text">Total Transaksi: <span class="fw-bold">Rp {{ number_format($member->total_transaksi, 0, ',', '.') }}</span></p>
                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ route('member.edit', $member->id) }}" class="btn btn-warning btn-sm text-white shadow-sm">Edit</a>
                                <button type="button" class="btn btn-danger btn-sm shadow-sm" onclick="confirmDelete({{ $member->id }})">Hapus</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(memberId) {
        Swal.fire({
            title: 'Yakin ingin menghapus?',
            text: "Member ini tidak dapat dikembalikan setelah dihapus!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d62828',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/master/member/${memberId}`, {
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
                            text: 'Member telah dihapus.',
                            icon: 'success',
                            confirmButtonColor: '#4e73df'
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            title: 'Gagal!',
                            text: 'Terjadi kesalahan saat menghapus member.',
                            icon: 'error',
                            confirmButtonColor: '#d62828'
                        });
                    }
                })
                .catch(() => {
                    Swal.fire({
                        title: 'Gagal!',
                        text: 'Terjadi kesalahan pada server.',
                        icon: 'error',
                        confirmButtonColor: '#d62828'
                    });
                });
            }
        });
    }
</script>
@endpush

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

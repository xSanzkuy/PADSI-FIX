@extends('layouts.layout')

@section('title', 'Tambah Pegawai')

@section('content')
<div class="container mt-5">
    <div class="card shadow p-4 border-0 rounded-lg">
        <h1 class="mb-4 text-center text-primary fw-bold">Tambah Pegawai Baru</h1>

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

        <form action="{{ route('pegawai.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="nama" class="form-label fw-semibold">Nama Pegawai</label>
                <input type="text" class="form-control" id="nama" name="nama" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label fw-semibold">Email Pegawai</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="alamat" class="form-label fw-semibold">Alamat</label>
                <input type="text" class="form-control" id="alamat" name="alamat" required>
            </div>
            <div class="mb-3">
                <label for="role" class="form-label fw-semibold">Role</label>
                <select name="id_role" id="role" class="form-select" required>
                    <option value="">Pilih Role</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}">{{ $role->nama_role }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary w-100 btn-lg mt-3">Simpan</button>
        </form>
    </div>
</div>

<style>
    .card {
        background: #f9f9f9;
        transition: box-shadow 0.3s ease-in-out;
    }
    .card:hover {
        box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.1);
    }
    .form-label {
        color: #343a40;
    }
</style>
@endsection

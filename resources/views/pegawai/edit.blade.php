@extends('layouts.layout')

@section('title', 'Edit Pegawai')

@section('content')
<div class="container mt-5">
    <div class="card shadow-lg p-5 border-0 rounded-lg">
        <!-- Judul Halaman -->
        <h1 class="text-center fw-bold mb-4" style="color: #003366; font-size: 2.5rem;">
            <i class="fas fa-user-edit"></i> Edit Data Pegawai
        </h1>

        <!-- Tampilkan Pesan Error Jika Ada -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form Edit Pegawai -->
        <form action="{{ route('pegawai.update', $pegawai->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Input Nama Pegawai -->
            <div class="mb-4">
                <label for="nama" class="form-label fw-semibold">Nama Pegawai</label>
                <input type="text" class="form-control form-control-lg rounded-pill" id="nama" name="nama" value="{{ $pegawai->nama }}" required>
            </div>
            
            <!-- Input Email Pegawai -->
            <div class="mb-4">
                <label for="email" class="form-label fw-semibold">Email Pegawai</label>
                <input type="email" class="form-control form-control-lg rounded-pill" id="email" name="email" value="{{ $pegawai->email }}" required>
            </div>
            
            <!-- Input Alamat Pegawai -->
            <div class="mb-4">
                <label for="alamat" class="form-label fw-semibold">Alamat</label>
                <input type="text" class="form-control form-control-lg rounded-pill" id="alamat" name="alamat" value="{{ $pegawai->alamat }}" required>
            </div>
            
            <!-- Pilihan Role Pegawai -->
            <div class="mb-4">
                <label for="role" class="form-label fw-semibold">Role</label>
                <select name="id_role" id="role" class="form-select form-select-lg rounded-pill" required>
                    <option value="">Pilih Role</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}" {{ $pegawai->id_role == $role->id ? 'selected' : '' }}>{{ $role->nama_role }}</option>
                    @endforeach
                </select>
            </div>
            
            <!-- Tombol Update -->
            <button type="submit" class="btn btn-primary-custom w-100 btn-lg rounded-pill mt-3">Update</button>
        </form>
    </div>
</div>
@endsection

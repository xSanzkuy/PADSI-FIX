@extends('layouts.layout')

@section('title', 'Edit Pegawai')

@section('content')
<div class="container mt-5">
    <div class="card shadow p-4 border-0 rounded-lg">
        <h1 class="mb-4 text-center text-success fw-bold">Edit Data Pegawai</h1>
        <form action="{{ route('pegawai.update', $pegawai->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="nama" class="form-label fw-semibold">Nama Pegawai</label>
                <input type="text" class="form-control" id="nama" name="nama" value="{{ $pegawai->nama }}" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label fw-semibold">Email Pegawai</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ $pegawai->email }}" required>
            </div>
            <div class="mb-3">
                <label for="alamat" class="form-label fw-semibold">Alamat</label>
                <input type="text" class="form-control" id="alamat" name="alamat" value="{{ $pegawai->alamat }}" required>
            </div>
            <div class="mb-3">
                <label for="role" class="form-label fw-semibold">Role</label>
                <select name="id_role" id="role" class="form-select" required>
                    <option value="">Pilih Role</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}" {{ $pegawai->id_role == $role->id ? 'selected' : '' }}>{{ $role->nama_role }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-success w-100 btn-lg mt-3">Update</button>
        </form>
    </div>
</div>
@endsection

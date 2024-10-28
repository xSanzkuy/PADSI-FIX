@extends('layouts.layout')

@section('title', 'Tambah Member')

@section('content')
<div class="container mt-5">
    <div class="card shadow p-4 border-0 rounded-lg">
        <h1 class="mb-4 text-center text-primary fw-bold">Tambah Member Baru</h1>
        <form action="{{ route('member.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="nama" class="form-label fw-semibold">Nama Member</label>
                <input type="text" class="form-control" id="nama" name="nama" required>
            </div>
            <div class="mb-3">
                <label for="no_hp" class="form-label fw-semibold">Nomor HP</label>
                <input type="text" class="form-control" id="no_hp" name="no_hp" required>
            </div>
            <div class="mb-3">
                <label for="tingkat" class="form-label fw-semibold">Tingkat Member</label>
                <select name="tingkat" id="tingkat" class="form-select">
                    <option value="" selected>Tidak Ada</option>
                    <option value="bronze">Bronze</option>
                    <option value="silver">Silver</option>
                    <option value="gold">Gold</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary w-100 btn-lg mt-3">Simpan</button>
        </form>
    </div>
</div>
@endsection

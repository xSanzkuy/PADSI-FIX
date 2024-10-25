@extends('layouts.layout')

@section('title', 'Edit Member')

@section('content')
<div class="container mt-5">
    <div class="card shadow p-4 border-0 rounded-lg">
        <h1 class="mb-4 text-center text-success fw-bold">Edit Data Member</h1>
        <form action="{{ route('member.update', $member->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="nama" class="form-label fw-semibold">Nama Member</label>
                <input type="text" class="form-control" id="nama" name="nama" value="{{ $member->nama }}" required>
            </div>
            <div class="mb-3">
                <label for="no_hp" class="form-label fw-semibold">Nomor HP</label>
                <input type="text" class="form-control" id="no_hp" name="no_hp" value="{{ $member->no_hp }}" required>
            </div>
            <div class="mb-3">
                <label for="tingkat" class="form-label fw-semibold">Tingkat Member</label>
                <select name="tingkat" id="tingkat" class="form-select" required>
                    <option value="silver" {{ $member->tingkat == 'silver' ? 'selected' : '' }}>Silver</option>
                    <option value="bronze" {{ $member->tingkat == 'bronze' ? 'selected' : '' }}>Bronze</option>
                    <option value="gold" {{ $member->tingkat == 'gold' ? 'selected' : '' }}>Gold</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success w-100 btn-lg mt-3">Update</button>
        </form>
    </div>
</div>
@endsection

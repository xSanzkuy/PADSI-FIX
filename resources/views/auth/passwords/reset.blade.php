@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center">{{ __('Reset Password') }}</h1>
    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        
        <div class="form-group mb-4">
            <label for="email">{{ __('Email Address') }}</label>
            <input type="email" class="form-control" id="email" name="email" required autofocus>
        </div>
        
        <div class="form-group mb-4">
            <label for="password">{{ __('Password') }}</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        
        <div class="form-group mb-4">
            <label for="password-confirm">{{ __('Confirm Password') }}</label>
            <input type="password" class="form-control" id="password-confirm" name="password_confirmation" required>
        </div>
        
        <button type="submit" class="btn btn-primary btn-block">{{ __('Reset Password') }}</button>
    </form>
</div>

<style>
    body {
        background: rgb(245, 245, 245);
    }
    .container {
        max-width: 500px;
        margin: 50px auto; /* Margin atas untuk memberi jarak dari atas */
        padding: 20px;
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }
    h1 {
        margin-bottom: 20px;
        font-size: 24px;
        color: #333;
    }
    @media (max-width: 450px) {
        .container {
            width: 90%; /* Membuat kontainer responsif pada perangkat kecil */
        }
    }
</style>
@endsection

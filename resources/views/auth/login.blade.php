@extends('auth.authApp')
@section('title')
SISCIS | LOGIN
@endsection

@section('content')
<div class="login">
    <div class="logo">
        <img src="{{ asset('images/Logo.png') }}" class="img-fluid" alt="Logo SISCIS">
    </div>
    <form method="POST" action="{{ route('login.submit') }}" class="form">
        @csrf
        <div class="col-lg-12 login-title">
            ADMIN PANEL
        </div>
        @if(Session::has('error'))
        <div class="alert alert-danger text-center" role="alert">
            {{ Session::get('error') }}
        </div>
        @endif

        <!-- Username Address -->
        <div class="form-outline mb-4">
            <label class="form-label" for="username">Username</label>
            <input class="form-control" id="username" type="text" name="username" placeholder="Masukkan username atau email" required autofocus>
        </div>

        <!-- Password -->
        <div class="form-outline mb-4">
            <label for="password">Password</label>
            <input id="password" class="form-control" type="password" name="password" placeholder="Masukkan password" required autocomplete="current-password"> 
        </div>

        <!-- Remember Me -->
        <div class="row mb-1">
            <div class="col d-flex justify-content-center">
                <div class="form-check">
                    <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                    <label for="remember_me" class="form-check-label">{{ __('Remember me') }}</label>
                </div>
            </div>
        </div>
        <div class="d-flex align-items-center justify-content-center mt-4">
            <button class="btn btn-primary btn-block" style="padding: 10px 20px;">Login</button>
        </div>

        <!-- Reset Password Link -->
        <div class="text-center mt-3">
            <a href="{{ route('password.request') }}" class="text-decoration-none">Lupa password?</a>
        </div>
    </form>
</div>

<style>
    body {
        background: rgb(245, 245, 245);
        font-family: 'Arial', sans-serif;
    }
    .login {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        height: 100vh;
        padding: 0 20px;
    }
    .login-title {
        margin-bottom: 20px;
        text-align: center;
        font-size: 32px;
        letter-spacing: 2px;
        font-weight: bold;
        color: #333;
    }
    .form {
        padding: 2rem;
        max-width: 400px; /* Ukuran maksimal untuk form */
        width: 100%; /* Membuat form responsif */
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        background-color: white;
    }
    .logo {
        max-width: 20rem;
        margin-bottom: 20px; /* Spasi bawah logo */
    }
    .img-fluid {
        max-width: 100%;
        height: auto;
    }
    @media (max-width: 450px) {
        .form {
            max-width: 90%; /* Memastikan form tidak melebihi lebar layar */
        }
        .login-title {
            font-size: 28px; /* Ukuran font yang lebih kecil untuk layar kecil */
        }
    }
</style>
@endsection

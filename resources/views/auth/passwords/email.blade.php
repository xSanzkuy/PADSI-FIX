@extends('auth.authApp')

@section('title')
    SISCIS | Reset Password
@endsection

@section('content')
<div class="login">
    <div class="logo">
        <img src="{{ asset('images/Logo.png') }}" class="img-fluid" alt="Logo SISCIS">
    </div>
    <form method="POST" action="{{ route('password.email') }}" class="form">
        @csrf
        <div class="col-lg-12 login-title">
            Reset Password
        </div>

        @if(Session::has('status'))
            <div class="alert alert-success text-center" role="alert">
                {{ Session::get('status') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger text-center" role="alert">
                {{ $errors->first() }}
            </div>
        @endif

        <!-- Email Address -->
        <div class="form-outline mb-4">
            <label class="form-label" for="email">Email</label>
            <input class="form-control" id="email" type="email" name="email" placeholder="Masukkan email terdaftar" required autofocus>
        </div>

        <div class="d-flex align-items-center justify-content-center mt-4">
            <button class="btn btn-primary btn-block" style="padding: 10px;">Kirim Link Reset Password</button>
        </div>
        
        <div class="text-center mt-3">
            <a href="{{ route('login.form') }}" class="text-decoration-none">Kembali ke Login</a>
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

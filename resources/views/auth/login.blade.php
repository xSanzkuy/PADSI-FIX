@extends('auth.authApp')
@section('title')
SISCIS | LOGIN
@endsection

@section('content')
<div class="login-container">
    <div class="login">
        <div class="logo" data-aos="zoom-in">
            <img src="{{ asset('images/salby.jpg') }}" class="img-fluid" alt="Logo SISCIS">
        </div>
        <form method="POST" action="{{ route('login.submit') }}" class="form" data-aos="fade-up">
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
                <input class="form-control" id="username" type="text" name="username" placeholder="Masukkan username" required autofocus>
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
                <a href="{{ route('password.request') }}" class="text-decoration-none forgot-link">Lupa password?</a>
            </div>
        </form>
    </div>
</div>

<style>
    body {
        background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('{{ asset('images/login.jpg') }}') no-repeat center center;
        background-size: cover;
        font-family: 'Arial', sans-serif;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
        overflow: hidden;
    }

    .login-container {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
        height: 100%;
    }

    .login {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 20px;
        max-width: 360px; /* Mengurangi lebar maksimal form */
        width: 100%;
        background: lightgray;
        border-radius: 12px;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.2);
        backdrop-filter: blur(8px);
        transition: transform 0.3s ease;
    }

    .login:hover {
        transform: translateY(-3px);
    }

    .login-title {
        margin-bottom: 15px;
        text-align: center;
        font-size: 26px;
        letter-spacing: 1px;
        font-weight: bold;
        color: #333;
        animation: fadeIn 1s ease-in-out;
    }

    .form {
        width: 100%;
    }

    .form-label {
        color: #333;
        font-weight: 600;
    }

    .form-control {
        border: 1px solid #ddd;
        border-radius: 5px;
        padding: 10px;
        transition: all 0.3s;
    }

    .form-control:focus {
        border-color: #ff6347;
        box-shadow: 0 0 8px rgba(255, 99, 71, 0.5);
    }

    .logo {
        max-width: 90px; /* Mengurangi ukuran logo */
        margin-bottom: 15px;
        transition: transform 0.5s;
    }

    .logo img {
        max-width: 100%;
        height: auto;
        transition: transform 0.3s ease;
    }

    .logo:hover img {
        transform: rotate(10deg) scale(1.05);
    }

    .btn-primary {
        background-color: #ff6347;
        border: none;
        transition: background-color 0.3s ease, transform 0.3s ease;
        padding: 10px 25px; /* Menambahkan padding tombol */
        font-size: 1rem; /* Menyesuaikan ukuran font tombol */
    }

    .btn-primary:hover {
        background-color: #ff4500;
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(255, 99, 71, 0.3);
    }

    .btn-primary:active {
        transform: scale(0.98);
    }

    .forgot-link {
        color: #ff6347;
        transition: color 0.3s;
        font-size: 0.9rem; /* Mengurangi ukuran font link */
    }

    .forgot-link:hover {
        color: #ff4500;
        text-decoration: underline;
    }

    @media (max-width: 450px) {
        .login {
            padding: 1.5rem;
            width: 90%;
        }

        .login-title {
            font-size: 24px;
        }
    }

    /* Keyframes */
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
</style>

<script src="https://unpkg.com/aos@next/dist/aos.js"></script>
<script>
    AOS.init({
        duration: 1000,
        once: true,
    });
</script>
@endsection

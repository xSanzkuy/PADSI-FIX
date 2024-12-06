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
                LOGIN  
            </div>  
            @if(Session::has('error'))  
            <div class="alert alert-danger text-center" role="alert">  
                {{ Session::get('error') }}  
            </div>  
            @endif  

            <!-- Username Address -->  
            <div class="form-outline mb-4">  
                <label class="form-label" for="username">Username/Email</label>  
                <input class="form-control" id="username" type="text" name="username" placeholder="Masukkan username/email" required autofocus>  
            </div>  

            <!-- Password -->  
            <div class="form-outline mb-4 position-relative">  
                <label for="password">Password</label>  
                <input id="password" class="form-control" type="password" name="password" placeholder="Masukkan password" required autocomplete="current-password">  
                <span toggle="#password" class="fa fa-fw fa-eye field-icon toggle-password" onclick="togglePassword()"></span>  
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

    /* Efek Background Bergerak (Hamburger, Kentang, Ayam) */  
    .background-animation {  
        position: absolute;  
        top: 0;  
        left: 0;  
        right: 0;  
        bottom: 0;  
        z-index: -1;  
        overflow: hidden;  
    }  

    .background-animation img {  
        position: absolute;  
        animation: moveBackground 15s infinite linear;  
    }  

    .background-animation .hamburger {  
        top: 10%;  
        left: 20%;  
        animation-delay: 0s;  
    }  

    .background-animation .kentang {  
        top: 30%;  
        left: 50%;  
        animation-delay: 5s;  
    }  

    .background-animation .ayam {  
        top: 50%;  
        left: 70%;  
        animation-delay: 10s;  
    }  

    @keyframes moveBackground {  
        0% {  
            transform: translateX(0) rotate(0deg);  
        }  
        50% {  
            transform: translateX(500px) rotate(180deg);  
        }  
        100% {  
            transform: translateX(0) rotate(360deg);  
        }  
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
        max-width: 360px;  
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

    /* Form Styling */
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
        max-width: 90px;  
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
        padding: 10px 25px;  
        font-size: 1rem;  
    }  

    .btn-primary:hover {  
        background-color: #ff4500;  
        transform: translateY(-3px);  
        box-shadow: 0 4px 12px rgba(255, 99, 71, 0.3);  
    }  

    .forgot-link {  
        color: #ff6347;  
        transition: color 0.3s;  
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

    /* Keyframes for Fade-In */
    @keyframes fadeIn {  
        from { opacity: 0; }  
        to { opacity: 1; }  
    }  

    .field-icon {  
        position: absolute;  
        right: 10px;  
        top: 38px;  
        cursor: pointer;  
        color: #aaa;  
        transition: color 0.3s;  
    }  

    .field-icon:hover {  
        color: #ff6347;  
    }  
</style>  

<script src="https://unpkg.com/aos@next/dist/aos.js"></script>  
<script>  
    AOS.init({  
        duration: 1000,  
        once: true,  
    });  

    function togglePassword() {  
        const passwordInput = document.getElementById('password');  
        const passwordIcon = document.querySelector('.toggle-password');  

        if (passwordInput.type === 'password') {  
            passwordInput.type = 'text';  
            passwordIcon.classList.remove('fa-eye');  
            passwordIcon.classList.add('fa-eye-slash');  
        } else {  
            passwordInput.type = 'password';  
            passwordIcon.classList.remove('fa-eye-slash');  
            passwordIcon.classList.add('fa-eye');  
        }  
    }  
</script>  

<!-- Background Animation -->
<div class="background-animation">
    <img src="{{ asset('images/burger.jpg') }}" class="burger" alt="Burger">
    <img src="{{ asset('images/kentang.png') }}" class="kentang" alt="Kentang">
    <img src="{{ asset('images/ayam.png') }}" class="ayam" alt="Ayam">
</div>
@endsection

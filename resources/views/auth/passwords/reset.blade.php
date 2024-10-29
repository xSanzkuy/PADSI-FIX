@extends('layouts.app')

@section('content')
<div class="container-fluid" style="background-image: url('{{ asset('images/login.jpg') }}'); background-size: cover; background-position: center; height: 100vh; overflow: hidden;">
    <div class="row justify-content-center align-items-center h-100">
        <div class="col-md-6 col-lg-4">
            <div class="card shadow-lg" style="opacity: 0.95; border-radius: 15px;">
                <div class="card-header text-center" style="background-color: rgba(52, 58, 64, 0.8); color: white;">
                    <h4 class="mb-0">Reset Password</h4>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <!-- Token Input (Required for Reset) -->
                        <input type="hidden" name="token" value="{{ $token }}">

                        <!-- Email Input -->
                        <div class="form-group mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" required autofocus placeholder="Enter your email address">
                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Password Input -->
                        <div class="form-group mb-3">
                            <label for="password" class="form-label">New Password</label>
                            <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" required placeholder="Enter your new password">
                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Confirm Password Input -->
                        <div class="form-group mb-3">
                            <label for="password-confirm" class="form-label">Confirm New Password</label>
                            <input type="password" name="password_confirmation" id="password-confirm" class="form-control" required placeholder="Confirm your new password">
                        </div>

                        <button type="submit" class="btn btn-primary btn-block mt-3">
                            Reset Password
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    body {
        margin: 0;
        overflow: hidden;
    }

    .btn-primary {
        background-color: #ff6347;
        border-color: #ff6347;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        background-color: #ff4500;
        border-color: #ff4500;
    }

    .form-control {
        border-radius: 8px;
        padding: 10px;
        border: 1px solid #ced4da; /* Menambahkan border yang simpel */
        transition: border-color 0.3s ease;
    }

    .form-control:focus {
        border-color: #ff6347;
        /* Menghapus box-shadow untuk menghilangkan efek blur */
        box-shadow: none;
    }

    /* Responsive Styles */
    @media (max-width: 768px) {
        .container-fluid {
            padding: 20px;
        }

        .card {
            width: 100%;
            margin: 0 auto;
        }

        .form-control {
            font-size: 14px;
        }
    }

    @media (max-width: 576px) {
        .container-fluid {
            padding: 15px;
        }

        .btn-primary {
            font-size: 14px;
            padding: 10px 20px;
        }
    }
</style>

<!-- Toastify Notifikasi -->
<script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
<script>
    @if(session('status'))
        Toastify({
            text: "{{ session('status') }}",
            duration: 3000,
            close: true,
            gravity: "top",
            position: "center",
            backgroundColor: "#4caf50",
            stopOnFocus: true,
        }).showToast();
    @endif

    @if($errors->any())
        Toastify({
            text: "Gabisa bos.",
            duration: 3000,
            close: true,
            gravity: "top",
            position: "center",
            backgroundColor: "#f44336",
            stopOnFocus: true,
        }).showToast();
    @endif
</script>
@endsection

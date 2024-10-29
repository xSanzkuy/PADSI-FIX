@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center align-items-center min-vh-100">
        <div class="col-md-6">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white text-center">
                    <h4>{{ __('Confirm Password') }}</h4>
                </div>

                <div class="card-body">
                    <p class="text-center mb-4">{{ __('Please confirm your password before continuing.') }}</p>

                    <form method="POST" action="{{ route('password.confirm') }}">
                        @csrf

                        <div class="form-group mb-3">
                            <label for="password" class="form-label">{{ __('Password') }}</label>
                            <input id="password" type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   name="password" required autofocus placeholder="Enter your password">
                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary btn-block">
                                {{ __('Confirm Password') }}
                            </button>
                        </div>

                        @if (Route::has('password.request'))
                            <div class="text-center">
                                <a class="btn btn-link text-decoration-none" href="{{ route('password.request') }}">
                                    {{ __('Forgot Your Password?') }}
                                </a>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Styling tambahan */
</style>

<script>
    // Tampilkan notifikasi jika ada pesan sukses atau error
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
            text: "{{ $errors->first() }}",
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

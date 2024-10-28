@extends('layouts.pp')

@section('content')
<div class="container-fluid d-flex align-items-center justify-content-center" style="min-height: 100vh; padding: 0;">
    <div class="background-overlay" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('{{ asset('images/ingredients-near-pizza.jpg') }}') no-repeat center center; background-size: cover; z-index: -1;"></div>

    <div class="card shadow-lg p-4"
         style="background: rgba(255, 255, 255, 0.9); border-radius: 10px; max-width: 500px; width: 100%;" 
         data-aos="fade-up" data-aos-duration="800">
        <div class="card-body text-center">
            <!-- Animated Member Icon -->
            <div class="icon-container mb-4" data-aos="zoom-in" data-aos-duration="600">
                <i class="fas fa-user-circle" style="font-size: 4rem; color: #ff6347;"></i>
            </div>
            
            <!-- Member Details -->
            <h2 class="text-center" style="font-weight: bold; color: #333;" data-aos="fade-down" data-aos-duration="800">Detail Member</h2>
            
            <div class="mt-4" style="font-size: 1.1rem;">
                <p class="card-text" style="color: #666;">
                    <strong style="color: #ff6347;">Nama:</strong> 
                    <span style="color: #333;" data-aos="fade-left" data-aos-delay="100">{{ $member->nama }}</span>
                </p>
                <p class="card-text" style="color: #666;">
                    <strong style="color: #ff6347;">Nomor Telepon:</strong> 
                    <span style="color: #333;" data-aos="fade-left" data-aos-delay="200">{{ $member->no_hp }}</span>
                </p>
                <p class="card-text" style="color: #666;">
                    <strong style="color: #ff6347;">Tingkat Member:</strong> 
                    <span style="color: #333;" data-aos="fade-left" data-aos-delay="300">{{ ucfirst($member->tingkat) }}</span>
                </p>
                <p class="card-text" style="color: #666;">
                    <strong style="color: #ff6347;">Total Transaksi:</strong> 
                    <span style="color: #333;" data-aos="fade-left" data-aos-delay="400">Rp {{ number_format($member->total_transaksi, 0, ',', '.') }}</span>
                </p>
            </div>

            <!-- Back Button -->
            <a href="{{ route('check.member.form') }}" 
               class="btn btn-secondary w-100 mt-4" 
               style="background-color: #ff6347; border: none; font-weight: bold;" 
               data-aos="fade-up" data-aos-delay="500">
                Kembali
            </a>
        </div>
    </div>
</div>

<!-- Styles for Additional Interactivity -->
<style>
    /* Hover effect on the card for slight scaling */
    .card:hover {
        transform: scale(1.02);
        transition: transform 0.3s ease-in-out;
    }

    /* Animation for icon bouncing */
    .icon-container i {
        animation: bounce 1s infinite alternate;
    }

    @keyframes bounce {
        0% { transform: translateY(0); }
        100% { transform: translateY(-10px); }
    }

    /* Responsive adjustments */
    @media (max-width: 576px) {
        .card-body {
            padding: 1.5rem;
        }
        .card-text {
            font-size: 1rem;
        }
        .icon-container i {
            font-size: 3rem;
        }
    }
</style>

<!-- Scripts and Initialization for AOS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@next/dist/aos.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        AOS.init({ duration: 1000, once: true });
    });
</script>
@endsection

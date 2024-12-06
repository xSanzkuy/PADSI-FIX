    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Home - Salby Chicken Smash</title>
        <link rel="icon" type="image/png" href="{{ asset('images/Logo.png') }}">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
        <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
        <style>
            /* General Styling */
            body {
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                margin: 0;
                overflow-x: hidden;
            }
            .hero-section {
                background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('{{ asset('images/ingredients-near-pizza.jpg') }}') no-repeat center center;
                background-size: cover;
                height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                position: relative;
                text-align: center;
                opacity: 0; /* Awalnya disembunyikan */
                transition: opacity 0.8s ease-in-out;
            }
            .hero-section.visible {
                opacity: 1; /* Muncul setelah gambar selesai dimuat */
            }
            .hero-section h1 {
                font-size: 4rem;
                font-weight: bold;
                text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.5);
                margin-bottom: 20px;
            }
            .hero-section p {
                font-size: 1.5rem;
                color: #ffdd99;
                text-shadow: 1px 1px 5px rgba(0, 0, 0, 0.3);
            }
            .hero-buttons {
                margin-top: 40px;
            }
            .hero-buttons .btn {
                padding: 15px 30px;
                font-size: 1.2rem;
            }

            /* Floating Login Button */
            .floating-login {
                position: absolute;
                top: 20px;
                right: 20px;
                background-color: #ff6347;
                color: white;
                padding: 10px 20px;
                border-radius: 5px;
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
                z-index: 10;
                transition: transform 0.3s ease;
            }
            .floating-login:hover {
                transform: scale(1.05);
            }

            /* Features Section */
            .features-section {
                padding: 50px 0;
                background-color: #f0f4f8;
            }
            .feature {
                text-align: center;
                padding: 20px;
            }
            .feature i {
                font-size: 3rem;
                color: #ff6347;
                margin-bottom: 15px;
            }

            /* Member Check Section */
            .member-check-section {
                background: linear-gradient(120deg, #ffdd99, #ff6347);
                padding: 50px 0;
                color: white;
            }
            .member-check-section h2 {
                font-size: 2.5rem;
                font-weight: bold;
            }

            /* Contact Section */
            .contact-section {
                padding: 50px 0;
            }
            .footer {
                background-color: #333;
                color: white;
                padding: 20px 0;
            }
        </style>
    </head>
    <body>

        <!-- Floating Login Button -->
        <a href="{{ route('login') }}" class="floating-login">
            <i class="fas fa-user-circle"></i> Login
        </a>

        <!-- Hero Section -->
        <div class="hero-section" id="hero-section">
            <div class="hero-content" data-aos="fade-up">
                <h1>Selamat Datang di Salby Chicken Smash</h1>
                <p>Menghadirkan Kenikmatan Ayam Berkualitas dengan Pelayanan Terbaik</p>
                <div class="hero-buttons" data-aos="fade-up" data-aos-delay="200">
                    <a href="#features" class="btn btn-warning text-dark">Pelajari Lebih Lanjut</a>
                </div>
            </div>
        </div>

        <!-- Features Section -->
        <div id="features" class="features-section container">
            <div class="row text-center mb-5">
                <h2 class="fw-bold" data-aos="fade-up">Kenapa Memilih Kami?</h2>
                <p class="text-muted" data-aos="fade-up" data-aos-delay="100">Beberapa alasan kenapa Salby Chicken Smash adalah pilihan terbaik untuk Anda.</p>
            </div>
            <div class="row">
                <div class="col-md-4 feature" data-aos="zoom-in" data-aos-delay="200">
                    <i class="fa fa-drumstick-bite"></i>
                    <h4 class="mt-3">Rasa yang Lezat</h4>
                    <p>Setiap gigitan memberikan rasa yang luar biasa, menggunakan bahan berkualitas dan resep spesial kami.</p>
                </div>
                <div class="col-md-4 feature" data-aos="zoom-in" data-aos-delay="300">
                    <i class="fa fa-bolt"></i>
                    <h4 class="mt-3">Pelayanan Cepat</h4>
                    <p>Kami menjamin pelayanan yang cepat dan ramah, agar setiap pelanggan merasa puas.</p>
                </div>
                <div class="col-md-4 feature" data-aos="zoom-in" data-aos-delay="400">
                    <i class="fa fa-heart"></i>
                    <h4 class="mt-3">Pelanggan adalah Prioritas</h4>
                    <p>Kami sangat menghargai pelanggan kami dengan menghadirkan program loyalitas yang menarik.</p>
                </div>
            </div>
        </div>

        <!-- Member Check Section -->
        <div class="member-check-section text-center">
            <h2 data-aos="fade-up">Cek Tingkat Member Anda</h2>
            <p data-aos="fade-up" data-aos-delay="100">Masukkan nomor telepon Anda untuk melihat tingkat keanggotaan.</p>
            <a href="{{ route('check.member.form') }}" class="btn btn-dark btn-lg mt-3" data-aos="zoom-in" data-aos-delay="200">Cek Tingkat Member</a>
        </div>

        <!-- Contact Section -->
        <div id="contact" class="contact-section">
            <div class="container">
                <div class="row text-center mb-5">
                    <h2 data-aos="fade-up">Kontak Kami</h2>
                    <p class="text-muted" data-aos="fade-up" data-aos-delay="100">Hubungi kami untuk informasi lebih lanjut atau reservasi.</p>
                </div>
                <div class="row">
                    <div class="col-md-8 offset-md-2">
                        <form action="/contact" method="POST" data-aos="fade-up" data-aos-delay="200">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="message" class="form-label">Pesan</label>
                                <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Kirim Pesan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="footer text-center">
            <div class="container">
                <p>&copy; 2024 Salby Chicken Smash. All Rights Reserved.</p>
            </div>
        </footer>

        <!-- Scripts -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                AOS.init({
                    duration: 1000,
                    once: true,
                });
                
                // Remove the loading screen once background is loaded
                const heroSection = document.getElementById('hero-section');
                const backgroundImage = new Image();
                backgroundImage.src = "{{ asset('images/ingredients-near-pizza.jpg') }}";
                backgroundImage.onload = () => {
                    heroSection.classList.add('visible');
                };
            });
        </script>
    </body>
    </html>

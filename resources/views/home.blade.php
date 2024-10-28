<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Home - Salby bos</title>
    <link rel="icon" type="image/png" href="{{ asset('images/Logo.png')}}">
    <meta name="keywords" content="restaurant, food, chicken, quality service">
    <meta name="description" content="Selamat datang di Salby Chicken Smash. Kami menghadirkan rasa ayam terbaik dengan pelayanan berkualitas untuk pengalaman kuliner yang luar biasa.">
    <meta name="author" content="Salby Chicken Smash">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .hero-section {
            background: url('https://source.unsplash.com/1600x900/?restaurant') no-repeat center center;
            background-size: cover;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
        }
        .hero-section h1 {
            font-size: 4rem;
            font-weight: bold;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.5);
        }
        .hero-section p {
            font-size: 1.5rem;
            margin-top: 15px;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.5);
        }
        .hero-button {
            margin-top: 30px;
            padding: 15px 30px;
            font-size: 1.2rem;
        }
        .features-section {
            padding: 50px 0;
        }
        .feature {
            text-align: center;
            padding: 20px;
        }
        .feature i {
            font-size: 3rem;
            color: #007bff;
        }
        .contact-section {
            background: #f8f9fa;
            padding: 50px 0;
        }
        .contact-section h2 {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 30px;
        }
        .contact-section form {
            max-width: 600px;
            margin: 0 auto;
        }
    </style>
</head>
<body>

    <div class="hero-section">
        <div>
            <h1>Selamat Datang di Salby Chicken Smash</h1>
            <p>Menghadirkan Kenikmatan Ayam Berkualitas dengan Pelayanan Terbaik</p>
            <a href="#features" class="btn btn-primary hero-button">Pelajari Lebih Lanjut</a>
        </div>
    </div>

    <div id="features" class="features-section container">
        <div class="row text-center mb-5">
            <h2 class="fw-bold">Kenapa Memilih Kami?</h2>
            <p class="text-muted">Beberapa alasan kenapa Salby Chicken Smash adalah pilihan terbaik untuk Anda.</p>
        </div>
        <div class="row">
            <div class="col-md-4 feature">
                <i class="fa fa-drumstick-bite"></i>
                <h4 class="mt-3">Rasa yang Lezat</h4>
                <p>Setiap gigitan memberikan rasa yang luar biasa, menggunakan bahan berkualitas dan resep spesial kami.</p>
            </div>
            <div class="col-md-4 feature">
                <i class="fa fa-bolt"></i>
                <h4 class="mt-3">Pelayanan Cepat</h4>
                <p>Kami menjamin pelayanan yang cepat dan ramah, agar setiap pelanggan merasa puas.</p>
            </div>
            <div class="col-md-4 feature">
                <i class="fa fa-heart"></i>
                <h4 class="mt-3">Pelanggan adalah Prioritas</h4>
                <p>Kami sangat menghargai pelanggan kami dengan menghadirkan program loyalitas yang menarik.</p>
            </div>
        </div>
    </div>

    <div id="contact" class="contact-section">
        <div class="container">
            <div class="row text-center mb-5">
                <h2>Kontak Kami</h2>
                <p class="text-muted">Hubungi kami untuk informasi lebih lanjut atau reservasi.</p>
            </div>
            <div class="row">
                <div class="col-md-8 offset-md-2">
                    <form action="/contact" method="POST">
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

    <footer class="bg-dark text-white text-center py-4 mt-5">
        <p>&copy; 2024 Salby Chicken Smash. All Rights Reserved.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

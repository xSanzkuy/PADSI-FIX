<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cek Tingkat Member - Salby Chicken Smash</title>
    <link rel="icon" type="image/png" href="{{ asset('images/Logo.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('{{ asset('images/ingredients-near-pizza.jpg') }}') no-repeat center center;
            background-size: cover;
            color: #fff;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .form-container {
            background: rgba(255, 255, 255, 0.85);
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
            text-align: center;
            position: relative;
        }
        .form-container h2 {
            font-weight: bold;
            color: #333;
            margin-bottom: 20px;
        }
        .form-container .form-label {
            color: #333;
            font-weight: 600;
        }
        .btn-primary {
            background-color: #ff6347;
            border: none;
            transition: background-color 0.3s, transform 0.3s;
        }
        .btn-primary:hover {
            background-color: #ff4500;
            transform: translateY(-3px);
        }
        .btn-primary:active {
            transform: scale(0.98);
        }
        .back-to-home {
            position: absolute;
            top: -30px;
            left: 50%;
            transform: translateX(-50%);
            background: #ff6347;
            color: white;
            padding: 10px 25px;
            border-radius: 50px;
            font-weight: bold;
            text-transform: uppercase;
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
            text-decoration: none;
            transition: background-color 0.3s, transform 0.3s;
        }
        .back-to-home:hover {
            background-color: #ff4500;
            transform: scale(1.1);
        }
    </style>
</head>
<body>

<div class="form-container" data-aos="fade-up">
    <!-- Tombol Kembali ke Homepage -->
    <a href="{{ route('home') }}" class="back-to-home">Kembali</a>

    <h2>Cek Tingkat Member Anda</h2>
    <form action="{{ route('check.member') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="phone" class="form-label">Nomor Telepon</label>
            <input type="text" class="form-control" id="phone" name="phone" placeholder="Masukkan nomor telepon Anda" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Cek Tingkat Member</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@next/dist/aos.js"></script>
<script>
    AOS.init({ duration: 1000, once: true });
</script>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">

    <style>
        body {
            background-color: #f0f2f5; /* Warna latar belakang yang lebih terang */
            font-family: 'Montserrat', sans-serif; /* Menggunakan font Montserrat */
            overflow-y: auto; /* Mengizinkan scrolling vertikal */
            margin: 0; /* Menghapus margin default */
        }

        #wrapper {
            display: flex;
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }

        #sidebar {
            width: 250px;
            max-width: 250px;
            background: #212529; /* Latar belakang sidebar hitam gelap */
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: transform 0.3s ease;
            border-radius: 0 20px 20px 0; /* Rounded corners untuk sidebar */
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.3); /* Efek bayangan */
        }

        #sidebar.active {
            transform: translateX(-100%);
        }

        .sidebar-header {
            font-size: 1.5rem;
            font-weight: 600;
            padding: 1.5rem;
            text-align: center;
            color: royalblue; /* Warna header */
            margin-bottom: 1rem;
        }

        .nav-link {
            color: #b0bec5; /* Warna teks link */
            font-size: 1.1rem;
            padding: 15px 20px;
            transition: background-color 0.3s, color 0.3s;
            border-radius: 5px;
            display: flex; /* Flex untuk ikon dan teks sejajar */
            align-items: center;
        }

        .nav-link:hover {
            background-color: #007bff; /* Warna latar belakang saat hover */
            color: white; /* Warna teks saat hover */
            transform: scale(1.05); /* Efek zoom saat hover */
        }

        .nav-link.active {
            background-color: #0056b3; /* Warna saat aktif */
            color: white;
        }

        #content {
            flex-grow: 1;
            padding: 2rem;
            transition: margin-left 0.3s ease;
            width: calc(100% - 250px);
            min-width: 700px;
            overflow-y: auto; /* Mengizinkan scrolling pada konten */
        }

        .logout-section {
            margin-top: auto;
            padding: 15px;
        }

        .logout-section button {
            width: 100%;
            font-size: 1.1rem;
        }

        .btn-toggle-sidebar {
            position: fixed;
            top: 20px; /* Menempatkan tombol sedikit lebih jauh dari atas */
            left: 15px;
            background-color: darkblue; /* Warna latar belakang tombol */
            color: white;
            border: none;
            padding: 10px 12px;
            border-radius: 5px; /* Rounded corners untuk tombol */
            transition: transform 0.3s, background-color 0.3s;
            z-index: 1000;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Menambahkan bayangan untuk efek 3D */
            font-size: 1.2rem; /* Ukuran font lebih besar */
        }

        .btn-toggle-sidebar:hover {
            background-color: #0056b3; /* Warna saat hover */
        }

        .btn-toggle-sidebar.rotate {
            transform: rotate(90deg);
        }

        @media (max-width: 768px) {
            #sidebar {
                transform: translateX(-100%);
                position: fixed;
                z-index: 999;
            }

            #sidebar.active {
                transform: translateX(0);
            }
        }
    </style>
</head>
<body>

    <button class="btn-toggle-sidebar">
        <i class="fas fa-bars"></i>
    </button>

    <div id="wrapper">
        <!-- Sidebar -->
        <nav id="sidebar">
            <div>
                <div class="sidebar-header">Salby</div>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}">
                            <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('products.index') }}" class="nav-link {{ request()->is('products*') ? 'active' : '' }}">
                            <i class="fas fa-box-open me-2"></i> Produk
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('pegawai.index') }}" class="nav-link {{ request()->is('pegawai*') ? 'active' : '' }}">
                            <i class="fas fa-users me-2"></i> Pegawai
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('member.index') }}" class="nav-link {{ request()->is('member*') ? 'active' : '' }}">
                            <i class="fas fa-user-friends me-2"></i> Member
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('transactions.index') }}" class="nav-link {{ request()->is('transactions*') ? 'active' : '' }}">
                            <i class="fas fa-cash-register me-2"></i> Transaksi
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('reports.index') }}" class="nav-link {{ request()->is('reports*') ? 'active' : '' }}">
                            <i class="fas fa-chart-line me-2"></i> Laporan Transaksi
                        </a>
                    </li>
                </ul>
            </div>

            <div class="logout-section">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                    </button>
                </form>
            </div>
        </nav>

        <!-- Content -->
        <div id="content">
            @yield('content')
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            $('.btn-toggle-sidebar').on('click', function () {
                $('#sidebar').toggleClass('active');
                $('#wrapper').toggleClass('toggled');
                $(this).toggleClass('rotate'); // Tambahkan efek rotasi pada tombol
            });
        });
    </script>

    @stack('scripts')
</body>
</html>

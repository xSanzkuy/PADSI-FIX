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

    <style>
        body {
            background-color: #f8f9fa;
        }

        #wrapper {
            display: flex;
            min-height: 100vh;
            transition: margin-left 0.3s ease; /* Animasi saat sidebar toggle */
        }

        #sidebar {
            width: 250px; /* Tentukan lebar sidebar */
            max-width: 250px;
            background-color: #343a40;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: transform 0.3s ease; /* Animasi saat toggle */
        }

        #sidebar.active {
            transform: translateX(-100%); /* Menggeser sidebar ke kiri */
        }

        .sidebar-header {
            font-size: 2rem;
            font-weight: bold;
            padding: 1.5rem;
            text-align: center;
            color: #ffcc00;
            margin-bottom: 1rem;
        }

        .nav-link {
            color: #d1d1d1;
            font-size: 1.1rem;
            padding: 15px;
            transition: background-color 0.3s, color 0.3s;
        }

        .nav-link:hover {
            background-color: #495057;
            color: #ffcc00;
        }

        .nav-link.active {
            background-color: #007bff;
            color: white;
        }

        #content {
            flex-grow: 1;
            padding: 2rem;
            transition: margin-left 0.3s ease; /* Animasi margin */
            margin-left: 0; /* Margin default */
            width: calc(100% - 250px); /* Atur lebar konten dengan mengurangi lebar sidebar */
            min-width: 700px; /* Minimum width untuk konten */
        }

        #wrapper.toggled #content {
            margin-left: 0; /* Ketika sidebar tertutup, hilangkan margin */
            width: calc(100%); /* Atur ulang lebar konten */
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
            top: 15px;
            left: 15px;
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 50%;
            transition: transform 0.3s;
            z-index: 1000;
        }

        .btn-toggle-sidebar:hover {
            background-color: #0056b3;
        }

        .btn-toggle-sidebar.rotate {
            transform: rotate(90deg); /* Tambahkan efek rotasi */
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
                <div class="sidebar-header">Salby Dashboard</div>
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
                $('#wrapper').toggleClass('toggled'); // Menambahkan atau menghapus kelas 'toggled'
                $(this).toggleClass('rotate'); // Tambahkan efek rotasi pada tombol
            });
        });
    </script>

    @stack('scripts')
</body>
</html>

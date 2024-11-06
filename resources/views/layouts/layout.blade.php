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
        html, body {
            height: 100%;
            overflow-y: auto;
            margin: 0;
            font-family: 'Montserrat', sans-serif;
            background-color: #f0f2f5;
        }

        #wrapper {
            display: flex;
            min-height: 100vh;
        }

        #sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            height: 100vh;
            background-color: #212529;
            color: white;
            z-index: 1000;
            padding-top: 20px;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.3);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: transform 0.3s ease;
        }

        #sidebar.active {
            transform: translateX(-100%);
        }

        .sidebar-header {
            font-size: 1.5rem;
            font-weight: 600;
            padding: 1.5rem;
            text-align: center;
            color: royalblue;
        }

        .sidebar-header img {
            max-width: 80px;
            height: auto;
            margin-bottom: 10px;
        }

        #sidebar .nav-link {
            color: #b0bec5;
            font-size: 1.1rem;
            padding: 12px 20px;
            border-radius: 5px;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: background-color 0.3s, color 0.3s, transform 0.3s;
        }

        #sidebar .nav-link:hover {
            background-color: #0056b3;
            color: #ffffff;
            transform: translateX(10px);
            box-shadow: 2px 4px 10px rgba(0, 0, 0, 0.2);
        }

        #sidebar .nav-link.active {
            background-color: #007bff;
            color: #ffffff;
        }

        #sidebar .nav-link i {
            font-size: 1.25rem;
        }

        .btn-toggle-sidebar {
            position: fixed;
            top: 15px;
            left: 15px;
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            z-index: 1100;
            cursor: pointer;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease;
        }

        .btn-toggle-sidebar.rotate {
            transform: rotate(90deg);
        }

        #content {
            margin-left: 250px;
            padding: 2rem;
            width: calc(100% - 250px);
            overflow-y: auto;
            transition: margin-left 0.3s ease, width 0.3s ease;
        }

        #content.centered {
            margin-left: 0;
            width: 100%;
        }

        .logout-section {
            padding: 20px;
            margin-top: auto;
            text-align: center;
        }

        .logout-section button {
            width: 100%;
            font-size: 1rem;
            font-weight: bold;
            border-radius: 5px;
        }

        @media (max-width: 768px) {
            #sidebar {
                transform: translateX(-100%);
            }
            #sidebar.active {
                transform: translateX(0);
            }
            #content {
                margin-left: 0;
                width: 100%;
            }
        }
    </style>
</head>
<body>

    <button class="btn-toggle-sidebar">
        <i class="fas fa-bars"></i>
    </button>

    <nav id="sidebar">
        <div>
            <div class="sidebar-header">
                <img src="{{ asset('images/transparan.png') }}" alt="Logo">
            </div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('products.index') }}" class="nav-link {{ request()->is('products*') ? 'active' : '' }}">
                        <i class="fas fa-box-open"></i> Produk
                    </a>
                </li>

                <!-- Tampilkan menu Pegawai hanya jika pengguna memiliki peran Owner atau Superadmin -->
                @if(Auth::check() && (Auth::user()->roles->pluck('nama_role')->contains('Owner') || Auth::user()->roles->pluck('nama_role')->contains('Superadmin')))
                    <li class="nav-item">
                        <a href="{{ route('pegawai.index') }}" class="nav-link {{ request()->is('pegawai*') ? 'active' : '' }}">
                            <i class="fas fa-users"></i> Pegawai
                        </a>
                    </li>
                @endif

                <li class="nav-item">
                    <a href="{{ route('member.index') }}" class="nav-link {{ request()->is('member*') ? 'active' : '' }}">
                        <i class="fas fa-user-friends"></i> Member
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('transactions.index') }}" class="nav-link {{ request()->is('transactions*') ? 'active' : '' }}">
                        <i class="fas fa-cash-register"></i> Transaksi
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('reports.index') }}" class="nav-link {{ request()->is('reports*') ? 'active' : '' }}">
                        <i class="fas fa-chart-line"></i> Laporan Transaksi
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

    <div id="content">
        @yield('content')
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            $('.btn-toggle-sidebar').on('click', function () {
                $('#sidebar').toggleClass('active');
                $('#content').toggleClass('centered');
                $(this).toggleClass('rotate');
            });
        });
    </script>

    @stack('scripts')
</body>
</html>
    
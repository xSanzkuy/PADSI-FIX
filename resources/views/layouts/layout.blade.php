<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Tambahkan link ke FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Wrapper untuk mengatur sidebar dan konten */
        #wrapper {
            display: flex;
            min-height: 100vh;
            transition: all 0.3s ease;
        }

        /* Sidebar Styling */
        #sidebar-wrapper {
            width: 250px;
            background-color: #343a40;
            color: white;
            padding-top: 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: all 0.3s ease;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        }

        .sidebar-heading {
            font-size: 1.5rem;
            font-weight: bold;
            text-align: center;
            padding-bottom: 1rem;
        }

        /* List Group Item Styling */
        .list-group-item {
            color: #d1d1d1;
            border: none;
            padding: 15px 20px;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            transition: background-color 0.3s, color 0.3s, border-left 0.3s;
            border-left: 4px solid transparent;
        }

        .list-group-item:hover {
            background-color: #495057;
            color: #ffcc00;
            border-left: 4px solid #ffcc00;
        }

        .list-group-item.active {
            background-color: #007bff;
            color: white;
            border-left: 4px solid #007bff;
        }

        #logout-section {
            padding: 20px;
        }

        #logout-section button {
            transition: background-color 0.3s, transform 0.3s;
        }

        #logout-section button:hover {
            background-color: #ff3d3d;
            transform: translateY(-3px);
        }

        #content-wrapper {
            flex-grow: 1;
            padding: 40px;
            background-color: #f8f9fa;
            transition: margin-left 0.3s ease;
        }

        /* Tombol Toggle Sidebar */
        #toggle-sidebar {
            position: fixed;
            top: 15px;
            left: 15px;
            z-index: 1000;
            background-color: #007bff;
            border: none;
            color: white;
            padding: 15px;
            border-radius: 50%;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            transition: background-color 0.3s, transform 0.3s, box-shadow 0.3s;
        }

        #toggle-sidebar:hover {
            background-color: #0056b3;
            transform: rotate(90deg);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
        }

        /* Styling untuk memastikan layout responsif */
        @media (max-width: 768px) {
            #wrapper {
                flex-direction: column;
            }
            #sidebar-wrapper {
                width: 100%;
            }
            #content-wrapper {
                margin-left: 0;
            }
        }

        /* Styling untuk ikon di sidebar */
        .list-group-item i {
            margin-right: 10px;
            transition: transform 0.3s;
        }

        .list-group-item:hover i {
            transform: scale(1.2);
        }

        /* Tambahkan sedikit styling untuk konten agar lebih menarik */
        #content-wrapper h1 {
            font-size: 2.5rem;
            color: #343a40;
        }

        #content-wrapper p {
            font-size: 1.2rem;
            color: #6c757d;
        }

    </style>
</head>
<body>
    <!-- Tombol untuk toggle sidebar -->
    <button id="toggle-sidebar"><i class="fas fa-bars"></i></button>

    <div id="wrapper">
        <!-- Sidebar -->
        <div id="sidebar-wrapper" class="bg-dark border-end">
            <div>
                <div class="sidebar-heading text-white p-3">Salby</div>
                <div class="list-group list-group-flush">
                    <a href="{{ route('dashboard') }}" class="list-group-item list-group-item-action bg-dark" id="menu-dashboard">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                    <a href="{{ route('products.index') }}" class="list-group-item list-group-item-action bg-dark" id="menu-produk">
                        <i class="fas fa-box-open"></i> Produk
                    </a>
                    <a href="{{ route('pegawai.index') }}" class="list-group-item list-group-item-action bg-dark" id="menu-pegawai">
                        <i class="fas fa-users"></i> Pegawai
                    </a>
                    <a href="{{ route('member.index') }}" class="list-group-item list-group-item-action bg-dark" id="menu-member">
                        <i class="fas fa-user-friends"></i> Member
                    </a>
                    <a href="{{ route('transactions.index') }}" class="list-group-item list-group-item-action bg-dark" id="menu-transactions">
                        <i class="fas fa-cash-register"></i> Transaksi
                    </a>
                </div>
            </div>

            <!-- Tombol Logout -->
            <div id="logout-section" class="bg-dark">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-danger w-100">Logout</button>
                </form>
            </div>
        </div>

        <!-- Page Content -->
        <div id="content-wrapper">
            @yield('content')
        </div>
    </div>

    <script>
        // Menambahkan efek aktif saat menu ditekan
        $(document).ready(function() {
            // Set semua menu ke non-aktif terlebih dahulu
            $('.list-group-item').on('click', function () {
                $('.list-group-item').removeClass('active');
                $(this).addClass('active');
            });

            // Menandai menu yang aktif berdasarkan URL saat ini
            const currentPath = window.location.pathname;
            $('.list-group-item').each(function() {
                const href = $(this).attr('href');
                if (href && currentPath === new URL(href, window.location.origin).pathname) {
                    $(this).addClass('active');
                }
            });

            // Toggle sidebar
            $('#toggle-sidebar').on('click', function() {
                $('#sidebar-wrapper').toggleClass('d-none');
                $('#content-wrapper').toggleClass('full-width');
            });
        });
    </script>
</body>
</html>

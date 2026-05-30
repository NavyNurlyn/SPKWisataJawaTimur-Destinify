<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Destinify')</title>

    <link rel="icon" type="image/jpeg" href="{{ asset('destinify.png') }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-image: url('background.png');
            background-size: cover;
            background-position: bottom;
            background-repeat: no-repeat;
            background-attachment: fixed;

            padding: 0;
            margin: 0;
        }

        /* ===== SIDEBAR (GLASSMORPHISM) ===== */
        .sidebar {
            height: 100vh;
            width: 240px;
            position: fixed;
            top: 0;
            left: 0;
            padding-top: 1rem;

            background: rgba(170, 130, 255, 0.25);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);

            border-right: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 4px 0 25px rgba(0, 0, 0, 0.05);

            color: #4a4a4a;
        }

        .sidebar h4 {
            text-align: center;
            color: #6e77ff;
            /* lavender bold */
            margin-bottom: 1.5rem;
            font-weight: 700;
        }

        .sidebar a {
            color: #555;
            text-decoration: none;
            display: block;
            padding: 10px 20px;
            margin: 6px 12px;
            border-radius: 10px;
            transition: 0.25s;
        }

        .sidebar a.active,
        .sidebar a:hover {
            background: linear-gradient(135deg, #c7ceff, #b3e5ff);
            color: #2a2a2a;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        /* ===== CONTENT AREA ===== */
        .content {
            margin-left: 250px;
            padding: 30px;
            transition: 0.3s;
        }

        .topbar {
            background: rgba(255, 255, 255, 0.4);
            backdrop-filter: blur(10px);
            border-radius: 12px;
            padding: 15px 25px;
            margin-bottom: 25px;
            border: 1px solid rgba(255, 255, 255, 0.35);
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.05);
        }

        .topbar h5 {
            color: #4751c4;
            font-weight: 600;
            margin: 0;
        }

        /* ===== MOBILE NAVBAR (GLASS) ===== */
        .navbar {
            background: rgba(255, 255, 255, 0.35) !important;
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.4);
        }

        .navbar-brand {
            color: #6e77ff !important;
        }

        #mobileMenu .bg-dark {
            background: rgba(255, 255, 255, 0.15) !important;
            backdrop-filter: blur(10px);
        }

        #mobileMenu a {
            color: #333 !important;
            padding: 10px 15px;
            border-radius: 8px;
            transition: .25s;
        }

        #mobileMenu a:hover {
            background: rgba(255, 255, 255, 0.4);
        }

        /* ===== GENERAL RESPONSIVE ===== */
        @media (max-width: 992px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            .content {
                margin-left: 0;
            }

            table {
                font-size: 0.9rem;
            }
        }
    </style>
</head>

<body>

    <!-- Sidebar -->
    <div class="sidebar d-lg-block d-none">
        <div class="text-center mb-3">
            <img src="{{ asset('destinify.jpg') }}" alt="Destinify Logo"
                style="width: 100px; height: 100px; object-fit: cover; border-radius: 15px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
            <h4 class="mt-2">Destinify</h4>
        </div>

        <a href="{{ url('/') }}" class="{{ request()->is('/') ? 'active' : '' }}">
            <i class="bi bi-speedometer2 me-2"></i> Dashboard
        </a>

        <a href="{{ route('alternatif.index') }}" class="{{ request()->is('alternatif*') ? 'active' : '' }}">
            <i class="bi bi-map me-2"></i> Data Wisata
        </a>

        <a href="{{ route('kriteria.index') }}" class="{{ request()->is('kriteria*') ? 'active' : '' }}">
            <i class="bi bi-list-check me-2"></i> Kriteria
        </a>

        <a href="{{ route('pembobotan.index') }}" class="{{ request()->is('pembobotan*') ? 'active' : '' }}">
            <i class="bi bi-calculator me-2"></i> Pembobotan AHP
        </a>

        <a href="{{ route('rekomendasi.index') }}" class="{{ request()->is('rekomendasi*') ? 'active' : '' }}">
            <i class="bi bi-star me-2"></i> Rekomendasi
        </a>
    </div>

    <!-- Navbar Mobile -->
    <nav class="navbar navbar-dark bg-dark d-lg-none">
        <div class="container-fluid">
            <a class="navbar-brand text-warning fw-bold" href="#">Destinify</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mobileMenu">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>

        <div class="collapse navbar-collapse" id="mobileMenu">
            <div class="bg-dark p-2">
                <a href="{{ url('/') }}"
                    class="d-block text-white py-1 {{ request()->is('/') ? 'active' : '' }}">
                    Dashboard
                </a>
                <a href="{{ route('alternatif.index') }}"
                    class="d-block text-white py-1 {{ request()->is('alternatif*') ? 'active' : '' }}">
                    Data Wisata
                </a>
                <a href="{{ route('kriteria.index') }}"
                    class="d-block text-white py-1 {{ request()->is('kriteria*') ? 'active' : '' }}">
                    Kriteria
                </a>
                <a href="{{ route('pembobotan.index') }}"
                    class="d-block text-white py-1 {{ request()->is('pembobotan*') ? 'active' : '' }}">
                    Pembobotan AHP
                </a>
                <a href="{{ route('rekomendasi.index') }}"
                    class="d-block text-white py-1 {{ request()->is('rekomendasi*') ? 'active' : '' }}">
                    Rekomendasi
                </a>
            </div>
        </div>
    </nav>
    <!-- Content -->
    <div class="content">
        <div class="topbar">
            <h5 class="m-0">@yield('page-title')</h5>
        </div>

        <div class="table-responsive">
            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <title>Digestex V2 - Industrial Intelligence</title>
    <link href="https://jsdelivr.net" rel="stylesheet">
    <link href="https://cloudflare.com" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <style>
        .bg-navy { background-color: #0a192f; }
        .text-gold { color: #ffc107 !important; }
        .oswald { font-family: 'Oswald', sans-serif; }
    </style>
</head>
<body>
    <!-- COPY NAVBAR DARI CI4 KE SINI -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-navy border-bottom border-warning shadow-sm">
        <div class="container">
            <a class="navbar-brand oswald fw-bold text-gold" href="{{ url('/') }}">
                <img src="{{ asset('images/logo_api_digestex2.png') }}" height="40" class="me-2"> 
                DIGESTEX <span class="text-white">V2</span>
            </a>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/') }}">HOME</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/members') }}">MEMBER AREA</a>
                    </li>
                    
                    <!-- LOGIKA LOGIN: Menampilkan Nama Perusahaan Jika Sudah Login -->
                    @auth
                        <li class="nav-item ms-3">
                            <span class="badge bg-warning text-dark px-3 rounded-pill fw-bold">
                                <i class="fas fa-user-check me-1"></i> {{ Auth::user()->nama_perusahaan }}
                            </span>
                        </li>
                    @else
                        <li class="nav-item ms-3">
                            <a href="{{ url('/login') }}" class="btn btn-gold btn-sm rounded-pill px-4">LOGIN</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Harga tiker kapas -->
     <!-- TICKER HARGA (Running Text) -->
<div class="bg-dark py-1 border-bottom border-secondary overflow-hidden">
    <div class="container-fluid">
        <marquee class="small text-white-50 oswald letter-spacing-1" scrollamount="4">
            <span class="text-warning fw-bold">MARKET UPDATE:</span> 
            <i class="fas fa-chart-line text-danger mx-2"></i> NY/ICE COTTON: <span class="text-white">71.31 USD/lb</span>
            <span class="mx-4">|</span>
            <i class="fas fa-coins text-success mx-2"></i> USD/IDR: <span class="text-white">Rp 16.025</span>
            <span class="mx-4">|</span>
            <span class="text-warning fw-bold small badge bg-danger mx-2">LIVE</span> 
            Global Sourcing Intelligence Status: <span class="text-white">Stable</span>
        </marquee>
    </div>
</div>

<!-- NAVBAR UTAMA -->
<nav class="navbar navbar-expand-lg navbar-dark bg-navy sticky-top shadow-lg border-bottom border-warning">
    <div class="container">
        <a class="navbar-brand oswald fw-bold text-warning" href="{{ url('/') }}">
            <img src="{{ asset('images/logo_api_digestex2.png') }}" height="40" class="me-2"> 
            DIGESTEX <span class="text-white">V2</span>
        </a>
        
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item px-2">
                    <a class="nav-link fw-bold small text-uppercase" href="{{ url('/') }}">Home</a>
                </li>
                <li class="nav-item px-2">
                    <a class="nav-link fw-bold small text-uppercase" href="{{ url('/members') }}">Member Intelligence</a>
                </li>
                <!-- Tombol Switcher Bahasa (ID/EN) -->
<li class="nav-item dropdown px-2 border-start border-white border-opacity-10 ms-2">
    <a class="nav-link dropdown-toggle fw-bold small text-white" href="#" id="langDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
        @if(app()->getLocale() == 'id')
            <img src="https://flagcdn.com" class="me-1"> ID
        @else
            <img src="https://flagcdn.com" class="me-1"> EN
        @endif
    </a>
    <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg mt-2" aria-labelledby="langDropdown">
        <li>
            <a class="dropdown-item small d-flex align-items-center {{ app()->getLocale() == 'id' ? 'active' : '' }}" href="{{ url('lang/id') }}">
                <img src="https://flagcdn.com" class="me-2"> Indonesia
            </a>
        </li>
        <li>
            <a class="dropdown-item small d-flex align-items-center {{ app()->getLocale() == 'en' ? 'active' : '' }}" href="{{ url('lang/en') }}">
                <img src="https://flagcdn.com" class="me-2"> English
            </a>
        </li>
    </ul>
</li>


                <!-- LOGIN / USER STATUS -->
                @auth
                    <li class="nav-item dropdown ms-lg-3">
                        <a class="btn btn-warning btn-sm rounded-pill px-4 fw-bold dropdown-toggle text-navy" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-1"></i> {{ Auth::user()->nama_perusahaan }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg mt-2">
                            <li><a class="dropdown-item small fw-bold" href="#"><i class="fas fa-cog me-2"></i> Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button class="dropdown-item small fw-bold text-danger"><i class="fas fa-sign-out-alt me-2"></i> Logout</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item ms-lg-3 mt-2 mt-lg-0">
                        <a href="{{ url('/login') }}" class="btn btn-warning btn-sm rounded-pill px-4 fw-bold text-navy shadow">
                            <i class="fas fa-sign-in-alt me-1"></i> MEMBER LOGIN
                        </a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

     <!-- Batas Harga Kapas -->

    <!-- TEMPAT ISI HALAMAN (CONTENT) -->
    <main>
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

</body>
</html>

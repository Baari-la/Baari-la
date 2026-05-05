<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Digestex V2 - Member Login</title>
    <link href="https://jsdelivr.net" rel="stylesheet">
    <link href="https://cloudflare.com" rel="stylesheet">
    <style>
        body { background-color: #0a192f; color: white; font-family: 'Inter', sans-serif; display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; }
        .login-card { background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 20px; backdrop-filter: blur(10px); width: 100%; max-width: 400px; padding: 40px; box-shadow: 0 15px 35px rgba(0,0,0,0.5); }
        .text-gold { color: #ffc107 !important; }
        .oswald { font-family: 'Oswald', sans-serif; }
        .form-control { background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 255, 255, 0.2); color: white; border-radius: 10px; padding: 12px; }
        .form-control:focus { background: rgba(255, 255, 255, 0.15); border-color: #ffc107; box-shadow: none; color: white; }
        .btn-gold { background: #ffc107; color: #0a192f; font-weight: bold; border-radius: 10px; padding: 12px; border: none; width: 100%; transition: all 0.3s; }
        .btn-gold:hover { background: #e0a800; transform: translateY(-2px); }
    </style>
</head>
<body>
    <div class="login-card animate__animated animate__fadeIn">
        <div class="text-center mb-4">
            <img src="{{ asset('images/logo_api_digestex2.png') }}" height="60" class="mb-3">
            <h4 class="oswald fw-bold text-uppercase text-gold">Member Login</h4>
            <p class="small text-white-50">Access Global Textile Intelligence</p>
        </div>

        <form action="{{ url('/login') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="small fw-bold text-white-50 mb-2">EMAIL ADDRESS</label>
                <input type="email" name="email" class="form-control" placeholder="name@company.com" required>
            </div>
            <div class="mb-4">
                <label class="small fw-bold text-white-50 mb-2">PASSWORD</label>
                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>
            <button type="submit" class="btn-gold">AUTHENTICATE ACCESS</button>
        </form>

        <div class="text-center mt-4">
            <a href="{{ url('/') }}" class="text-white-50 small text-decoration-none"><i class="fas fa-arrow-left me-2"></i> Back to Home</a>
        </div>
    </div>
</body>
</html>

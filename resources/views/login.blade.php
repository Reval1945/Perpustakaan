<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>Masuk - Perpustakaan SMK4BJN</title>

    <!-- SB Admin 2 -->
    <link href="{{ asset('template/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('template/css/sb-admin-2.min.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,800,900" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #2C5AA0;
            --primary-light: #4A7BC8;
            --secondary: #F9A826;
            --accent: #E63946;
            --light: #F8F9FA;
            --dark: #212529;
            --gray: #6C757D;
            --gray-light: #E9ECEF;
            --border-color: #E0E0E0;
            --shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            --radius: 8px;
        }

        html, body {
            height: 100%;
            width: 100%;
            overflow: hidden;
            font-family: 'Inter', sans-serif;
            background: #fff;
        }

        .container-fluid {
            height: 100%;
            width: 100%;
            padding: 0;
        }

        .row {
            height: 100%;
            width: 100%;
            margin: 0;
        }

        [class*="col-"] {
            padding: 0;
        }

        /* LEFT SIDE */
        .left-side {
            background: #fff;
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 0 80px;
        }

        .left-side h1 {
            font-weight: 700;
            font-size: 36px;
            color: #000;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }

        .left-side .subtitle {
            color: var(--gray);
            font-size: 16px;
            margin-bottom: 40px;
            font-weight: 400;
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-group label {
            font-weight: 600;
            font-size: 13px;
            color: #000;
            margin-bottom: 8px;
            display: block;
            letter-spacing: 0.3px;
            text-transform: uppercase;
        }

        .form-control {
            width: 100%;
            height: 48px;
            border: 1px solid var(--border-color);
            border-radius: var(--radius);
            padding: 0 16px;
            font-size: 15px;
            font-family: 'Inter', sans-serif;
            background: #fff;
            transition: border-color 0.2s;
        }

        .form-control:focus {
            border-color: var(--primary);
            outline: none;
        }

        .form-control::placeholder {
            color: #B0B0B0;
            font-size: 15px;
        }

        .input-wrapper {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray);
            cursor: pointer;
            font-size: 16px;
        }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 16px 0 32px;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .remember-me input[type="checkbox"] {
            width: 16px;
            height: 16px;
            border: 1.5px solid var(--border-color);
            border-radius: 3px;
            cursor: pointer;
            accent-color: var(--primary);
        }

        .remember-me label {
            color: var(--gray);
            font-size: 14px;
            cursor: pointer;
            font-weight: 400;
        }

        .forgot-link {
            color: var(--gray);
            text-decoration: none;
            font-size: 14px;
            font-weight: 400;
        }

        .forgot-link:hover {
            color: #000;
        }

        .btn-login {
            width: 100%;
            height: 48px;
            background: var(--primary);
            border: none;
            border-radius: var(--radius);
            color: white;
            font-weight: 600;
            font-size: 14px;
            font-family: 'Inter', sans-serif;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: background 0.2s;
        }

        .btn-login:hover {
            background: var(--primary-light);
        }

        .btn-login i {
            font-size: 14px;
        }

        .btn-login:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

        /* Register Link */
        .register-section {
            text-align: center;
            margin-top: 24px;
            padding-top: 24px;
            border-top: 1px solid var(--border-color);
        }

        .register-text {
            color: var(--gray);
            font-size: 14px;
        }

        .register-link {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            margin-left: 5px;
        }

        .register-link:hover {
            color: var(--primary-light);
            text-decoration: underline;
        }

        /* RIGHT SIDE */
        .right-side {
            background: linear-gradient(135deg, var(--primary), #1e3a5f);
            color: white;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        /* Background pattern */
        .right-side::before {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="none"><path d="M0 0 L100 100 L0 100 Z" fill="rgba(255,255,255,0.03)"/><path d="M100 0 L0 100 L100 100 Z" fill="rgba(255,255,255,0.03)"/></svg>');
            background-size: cover;
        }

        .right-content {
            text-align: center;
            max-width: 80%;
            position: relative;
            z-index: 2;
        }

        /* Icons container */
        .icons-container {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin-bottom: 30px;
        }

        .icon-circle {
            width: 70px;
            height: 70px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            animation: float 3s ease-in-out infinite;
        }

        .icon-circle:nth-child(2) {
            animation-delay: 0.5s;
        }

        .icon-circle:nth-child(3) {
            animation-delay: 1s;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-10px);
            }
        }

        .icon-circle i {
            font-size: 30px;
            color: white;
        }

        .right-content h2 {
            font-weight: 700;
            font-size: 32px;
            margin-bottom: 16px;
            line-height: 1.3;
        }

        .right-content p {
            font-size: 15px;
            line-height: 1.6;
            opacity: 0.9;
            margin-bottom: 32px;
            font-weight: 400;
            max-width: 340px;
        }

        .badge-container {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 24px;
            margin-bottom: 20px;
        }

        .badge-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            font-weight: 500;
            background: rgba(255, 255, 255, 0.1);
            padding: 8px 16px;
            border-radius: 30px;
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .badge-item i {
            font-size: 14px;
        }

        .badge-item .fa-circle {
            color: rgba(255, 255, 255, 0.5);
        }

        .badge-item .fa-circle-check {
            color: #4CAF50;
        }

        .secure-badge {
            font-size: 13px;
            font-weight: 500;
            opacity: 0.7;
            letter-spacing: 0.3px;
            display: inline-block;
            padding: 6px 16px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 30px;
            backdrop-filter: blur(5px);
        }

        /* Loading Spinner */
        .spinner-border-sm {
            width: 16px;
            height: 16px;
            border-width: 2px;
        }

        .d-none {
            display: none;
        }

        /* RESPONSIVE */
        @media (max-width: 1200px) {
            .left-side {
                padding: 0 60px;
            }
        }

        @media (max-width: 992px) {
            .right-side {
                display: none;
            }
            
            .left-side {
                padding: 0 40px;
            }
        }

        @media (max-width: 576px) {
            .left-side {
                padding: 0 24px;
            }
            
            .left-side h1 {
                font-size: 32px;
            }
            
            .form-options {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }
        }
    </style>
</head>

<body>

<div class="container-fluid">
    <div class="row">

        <!-- SISI KIRI -->
        <div class="col-lg-6 left-side">

            <h1>Selamat Datang</h1>
            <div class="subtitle">Silakan masukkan detail Anda</div>

            <form id="loginForm">
                @csrf

                <div class="form-group">
                    <label>Alamat Email</label>
                    <input type="email" id="email" class="form-control" placeholder="Masukkan email Anda" required>
                </div>

                <div class="form-group">
                    <label>Kata Sandi</label>
                    <div class="input-wrapper">
                        <input type="password" id="password" class="form-control" placeholder="Masukkan kata sandi" required>
                        <span class="password-toggle" id="togglePassword">
                            <i class="fas fa-eye" id="eyeIcon"></i>
                        </span>
                    </div>
                </div>

                <button type="submit" class="btn-login" id="loginButton">
                    <i class="fas fa-lock"></i>
                    <span id="buttonText">Masuk</span>
                    <span id="loadingSpinner" class="d-none">
                        <span class="spinner-border spinner-border-sm" role="status"></span>
                    </span>
                </button>
            </form>

            <!-- Link Registrasi -->
            <div class="register-section">
                <span class="register-text">Belum punya akun?</span>
                <a href="/register" class="register-link">Daftar sekarang</a>
            </div>
        </div>

        <!-- SISI KANAN -->
        <div class="col-lg-6 right-side">
            <div class="right-content">
                <!-- Ikon di atas -->
                <div class="icons-container">
                    <div class="icon-circle">
                        <i class="fas fa-book"></i>
                    </div>
                    <div class="icon-circle">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="icon-circle">
                        <i class="fas fa-chart-line"></i>
                    </div>
                </div>

                <h2>Perpustakaan<br>SMK 4 BJN</h2>
                <p>
                    Kelola peminjaman buku, data anggota, dan aktivitas
                    perpustakaan secara modern, cepat, dan aman.
                </p>
                
            </div>
        </div>

    </div>
</div>

<!-- JS -->
<script src="{{ asset('template/vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('template/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('template/js/sb-admin-2.min.js') }}"></script>

<script>
// Toggle Kata Sandi
document.getElementById('togglePassword').addEventListener('click', function () {
    const passwordInput = document.getElementById('password');
    const eyeIcon = document.getElementById('eyeIcon');

    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        eyeIcon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        eyeIcon.classList.replace('fa-eye-slash', 'fa-eye');
    }
});

// Submit Form
$('#loginForm').on('submit', async function (e) {
    e.preventDefault();

    $('#loginButton').prop('disabled', true);
    $('#buttonText').text('Memproses...');
    $('#loadingSpinner').removeClass('d-none');

    try {
        const res = await fetch('/api/login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                email: $('#email').val(),
                password: $('#password').val()
            })
        });

        if (!res.ok) throw new Error();

        const data = await res.json();

        localStorage.setItem('token', data.token);
        localStorage.setItem('user', JSON.stringify(data.user));

        if (data.user.role === 'admin') {
            window.location.href = '/admin/dashboard';
        } else if (data.user.role === 'user') {
            window.location.href = '/anggota/dashboard';
        } else if (data.user.role === 'superadmin') {
            window.location.href = '/superadmin/dashboard';
        }

    } catch {
        alert('Email atau kata sandi salah');
    } finally {
        $('#loginButton').prop('disabled', false);
        $('#buttonText').text('Masuk');
        $('#loadingSpinner').addClass('d-none');
    }
});
</script>

</body>
</html>
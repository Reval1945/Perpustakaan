<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>Daftar - Perpustakaan SMK4BJN</title>

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

        /* RIGHT SIDE - FORM */
        .right-side {
            background: #fff;
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 30px 60px;
            overflow: hidden;
        }

        .right-side h1 {
            font-weight: 700;
            font-size: 32px;
            color: #000;
            margin-bottom: 4px;
            letter-spacing: -0.5px;
        }

        .right-side .subtitle {
            color: var(--gray);
            font-size: 14px;
            margin-bottom: 20px;
            font-weight: 400;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px 20px;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 0;
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            font-weight: 600;
            font-size: 12px;
            color: #000;
            margin-bottom: 6px;
            display: flex;
            align-items: center;
            letter-spacing: 0.3px;
            text-transform: uppercase;
        }

        .form-control {
            width: 100%;
            height: 42px;
            border: 1px solid var(--border-color);
            border-radius: var(--radius);
            padding: 0 14px;
            font-size: 14px;
            font-family: 'Inter', sans-serif;
            background: #fff;
            transition: all 0.2s;
        }

        .form-control:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(44, 90, 160, 0.1);
        }

        .form-control::placeholder {
            color: #B0B0B0;
            font-size: 13px;
        }

        .input-wrapper {
            position: relative;
            width: 100%;
        }

        .password-toggle {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray);
            cursor: pointer;
            font-size: 16px;
            z-index: 10;
        }

        .btn-register {
            width: 100%;
            height: 44px;
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
            transition: all 0.2s;
            margin-top: 5px;
        }

        .btn-register:hover {
            background: var(--primary-light);
            transform: translateY(-1px);
            box-shadow: var(--shadow);
        }

        .btn-register i {
            font-size: 14px;
        }

        .btn-register:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        /* Login Link */
        .login-section {
            text-align: center;
            margin-top: 18px;
            padding-top: 18px;
            border-top: 1px solid var(--border-color);
        }

        .login-text {
            color: var(--gray);
            font-size: 14px;
        }

        .login-link {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            margin-left: 5px;
            font-size: 14px;
        }

        .login-link:hover {
            color: var(--primary-light);
            text-decoration: underline;
        }

        /* LEFT SIDE - ILUSTRASI */
        .left-side {
            background: linear-gradient(135deg, var(--primary), #1e3a5f);
            color: white;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .left-side::before {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="none"><path d="M0 0 L100 100 L0 100 Z" fill="rgba(255,255,255,0.03)"/><path d="M100 0 L0 100 L100 100 Z" fill="rgba(255,255,255,0.03)"/></svg>');
            background-size: cover;
        }

        .left-content {
            text-align: center;
            max-width: 80%;
            position: relative;
            z-index: 2;
        }

        .icons-container {
            display: flex;
            justify-content: center;
            gap: 25px;
            margin-bottom: 25px;
        }

        .icon-circle {
            width: 65px;
            height: 65px;
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
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        .icon-circle i {
            font-size: 28px;
            color: white;
        }

        .left-content h2 {
            font-weight: 700;
            font-size: 30px;
            margin-bottom: 15px;
            line-height: 1.3;
        }

        .left-content p {
            font-size: 14px;
            line-height: 1.6;
            opacity: 0.9;
            margin-bottom: 25px;
            font-weight: 400;
            max-width: 300px;
            margin-left: auto;
            margin-right: auto;
        }

        .badge-container {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 20px;
            margin-bottom: 20px;
        }

        .badge-item {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            font-weight: 500;
            background: rgba(255, 255, 255, 0.1);
            padding: 6px 16px;
            border-radius: 30px;
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .secure-badge {
            font-size: 12px;
            font-weight: 500;
            opacity: 0.7;
            letter-spacing: 0.3px;
            display: inline-block;
            padding: 6px 16px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 30px;
            backdrop-filter: blur(5px);
        }

        /* Alert Styles */
        .alert {
            padding: 10px 14px;
            border-radius: var(--radius);
            margin-bottom: 18px;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        /* Optional field indicator */
        .optional-badge {
            color: var(--gray);
            font-size: 10px;
            font-weight: 400;
            margin-left: 6px;
            background: var(--gray-light);
            padding: 2px 6px;
            border-radius: 4px;
            text-transform: lowercase;
        }

        /* Error Feedback */
        .invalid-feedback {
            color: var(--accent);
            font-size: 11px;
            margin-top: 4px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .is-invalid {
            border-color: var(--accent) !important;
        }

        .is-invalid:focus {
            box-shadow: 0 0 0 3px rgba(230, 57, 70, 0.1);
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

        /* Scale for different heights */
        @media (max-height: 700px) {
            .right-side { padding: 20px 60px; }
            .form-control { height: 40px; }
            .btn-register { height: 42px; }
            .form-grid { gap: 14px 20px; }
            .icons-container { gap: 20px; }
            .icon-circle { width: 55px; height: 55px; }
            .icon-circle i { font-size: 24px; }
        }

        @media (max-height: 600px) {
            .right-side { padding: 15px 60px; }
            .form-control { height: 38px; font-size: 13px; }
            .btn-register { height: 40px; }
            .right-side h1 { font-size: 28px; }
            .subtitle { margin-bottom: 15px; }
            .form-grid { gap: 10px 20px; margin-bottom: 15px; }
            .login-section { margin-top: 12px; padding-top: 12px; }
        }

        /* Responsive */
        @media (max-width: 992px) {
            .left-side { display: none; }
            .right-side { padding: 30px; }
        }

        @media (max-width: 576px) {
            .form-grid { grid-template-columns: 1fr; }
            .right-side { padding: 20px; }
        }
    </style>
</head>

<body>

<div class="container-fluid">
    <div class="row">

        <!-- SISI KIRI - ILUSTRASI -->
        <div class="col-lg-6 left-side">
            <div class="left-content">
                <div class="icons-container">
                    <div class="icon-circle"><i class="fas fa-book"></i></div>
                    <div class="icon-circle"><i class="fas fa-users"></i></div>
                    <div class="icon-circle"><i class="fas fa-chart-line"></i></div>
                </div>

                <h2>Bergabunglah<br>Dengan Kami</h2>
                <p>
                    Dapatkan akses ke ribuan koleksi buku digital dan nikmati kemudahan peminjaman online.
                </p>
                
                <div class="badge-container">
                    <div class="badge-item"><i class="far fa-circle"></i><span>Gratis</span></div>
                    <div class="badge-item"><i class="fas fa-circle-check"></i><span>Mudah</span></div>
                </div>
                
                <div class="secure-badge">
                    <i class="fas fa-shield-alt" style="margin-right: 5px;"></i> Data Terenkripsi
                </div>
            </div>
        </div>

        <!-- SISI KANAN - FORM REGISTRASI 2 KOLOM -->
        <div class="col-lg-6 right-side">
            <h1>Buat Akun</h1>
            <div class="subtitle">Isi data diri Anda untuk mendaftar</div>

            @if(session('success'))
            <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
            @endif
            
            @if(session('error') || $errors->any())
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i> 
                @if(session('error')) {{ session('error') }} @else Harap periksa kembali data Anda @endif
            </div>
            @endif

            <form id="registerForm">
                @csrf

                <div class="form-grid">
                    <!-- Nama Lengkap -->
                    <div class="form-group">
                        <label><i class="fas fa-user" style="margin-right: 6px; color: var(--primary);"></i>Nama Lengkap</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Nama lengkap" required value="{{ old('name') }}">
                        @error('name')<div class="invalid-feedback"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                    </div>

                    <!-- Email -->
                    <div class="form-group">
                        <label><i class="fas fa-envelope" style="margin-right: 6px; color: var(--primary);"></i>Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="Alamat email" required value="{{ old('email') }}">
                        @error('email')<div class="invalid-feedback"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                    </div>

                    <!-- Password -->
                    <div class="form-group">
                        <label><i class="fas fa-lock" style="margin-right: 6px; color: var(--primary);"></i>Kata Sandi</label>
                        <div class="input-wrapper">
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Kata sandi" required>
                            <span class="password-toggle" id="togglePassword"><i class="fas fa-eye" id="eyeIcon"></i></span>
                        </div>
                        @error('password')<div class="invalid-feedback"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                    </div>

                    <!-- No Telepon -->
                    <div class="form-group">
                        <label><i class="fas fa-phone" style="margin-right: 6px; color: var(--primary);"></i>No Telepon</label>
                        <input type="text" class="form-control @error('no_telp') is-invalid @enderror" id="no_telp" name="phone" placeholder="Nomor telepon" required value="{{ old('no_telp') }}">
                        @error('no_telp')<div class="invalid-feedback"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                    </div>

                    <!-- NISN (Opsional) -->
                    <div class="form-group">
                        <label><i class="fas fa-id-card" style="margin-right: 6px; color: var(--primary);"></i>NISN <span class="optional-badge">opsional</span></label>
                        <input type="text" class="form-control @error('nisn') is-invalid @enderror" id="nisn" name="nisn" placeholder="NISN" value="{{ old('nisn') }}">
                        @error('nisn')<div class="invalid-feedback"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                    </div>

                    <!-- Kelas (Opsional) -->
                    <div class="form-group">
                        <label><i class="fas fa-school" style="margin-right: 6px; color: var(--primary);"></i>Kelas <span class="optional-badge">opsional</span></label>
                        <input type="text" class="form-control @error('kelas') is-invalid @enderror" id="kelas" name="class" placeholder="Kelas" value="{{ old('kelas') }}">
                        @error('kelas')<div class="invalid-feedback"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                    </div>

                    <!-- No Absen (Opsional) -->
                    <div class="form-group">
                        <label><i class="fas fa-list-ol" style="margin-right: 6px; color: var(--primary);"></i>No Absen <span class="optional-badge">opsional</span></label>
                        <input type="number" class="form-control @error('no_absen') is-invalid @enderror" id="no_absen" name="roll_number" placeholder="No absen" value="{{ old('no_absen') }}">
                        @error('no_absen')<div class="invalid-feedback"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                    </div>
                </div>

                <button type="submit" class="btn-register" id="registerButton">
                    <i class="fas fa-user-plus"></i>
                    <span id="buttonText">Daftar</span>
                    <span id="loadingSpinner" class="d-none"><span class="spinner-border spinner-border-sm"></span></span>
                </button>
            </form>

            <div class="login-section">
                <span class="login-text">Sudah punya akun?</span>
                <a href="/login" class="login-link">Masuk</a>
            </div>
        </div>

    </div>
</div>

<script src="{{ asset('template/vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('template/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('template/js/sb-admin-2.min.js') }}"></script>

<script>
$(document).ready(function() {
    $('#togglePassword').click(function() {
        const passwordInput = $('#password');
        const eyeIcon = $('#eyeIcon');
        
        if (passwordInput.attr('type') === 'password') {
            passwordInput.attr('type', 'text');
            eyeIcon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            passwordInput.attr('type', 'password');
            eyeIcon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });
    
    $('.form-control').focus(function() {
        $(this).removeClass('is-invalid');
    });
    
    $('#name').focus();
});

$('#registerForm').on('submit', async function (e) {
    e.preventDefault();

    $('#registerButton').prop('disabled', true);
    $('#buttonText').text('Memproses...');
    $('#loadingSpinner').removeClass('d-none');

    const payload = {
        name: $('#name').val(),
        email: $('#email').val(),
        password: $('#password').val(),
        phone: $('#no_telp').val(),
        ...($('#nisn').val() && { nisn: $('#nisn').val() }),
        ...($('#kelas').val() && { class: $('#kelas').val() }),
        ...($('#no_absen').val() && { roll_number: $('#no_absen').val() })
    };

    try {
        const res = await fetch('http://localhost:8000/api/register', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify(payload)
        });

        const data = await res.json();

        if (data.token) {
            alert('Pendaftaran berhasil! Silakan login.');
            window.location.href = '/login';
        } else {
            alert(data.message || 'Pendaftaran gagal');
        }
    } catch (err) {
        console.error(err);
        alert('Terjadi kesalahan server');
    } finally {
        $('#registerButton').prop('disabled', false);
        $('#buttonText').text('Daftar');
        $('#loadingSpinner').addClass('d-none');
    }
});
</script>

</body>
</html>
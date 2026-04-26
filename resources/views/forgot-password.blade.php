<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>Reset Password - Perpustakaan SMK4BJN</title>

    <link href="{{ asset('template/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('template/css/sb-admin-2.min.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,800,900" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        :root {
            --primary: #2C5AA0;
            --primary-light: #4A7BC8;
            --light: #F8F9FA;
            --gray: #6C757D;
            --border-color: #E0E0E0;
            --radius: 8px;
        }

        /* Perbaikan agar bisa scroll di HP */
        html, body { min-height: 100%; width: 100%; font-family: 'Inter', sans-serif; background: #fff; }
        .container-fluid, .row { min-height: 100vh; width: 100%; margin: 0; padding: 0; }

        .left-side {
            background: #fff;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 40px 80px;
        }

        .left-side h1 { font-weight: 700; font-size: 32px; color: #000; margin-bottom: 8px; letter-spacing: -0.5px; }
        .left-side .subtitle { color: var(--gray); font-size: 15px; margin-bottom: 35px; font-weight: 400; line-height: 1.5; }

        .form-group { margin-bottom: 20px; }
        .form-group label { font-weight: 600; font-size: 13px; color: #000; margin-bottom: 8px; display: block; text-transform: uppercase; }
        
        .form-control {
            width: 100%; height: 48px; border: 1px solid var(--border-color);
            border-radius: var(--radius); padding: 0 16px; font-size: 15px; transition: 0.2s;
        }
        .form-control:focus { border-color: var(--primary); outline: none; box-shadow: none; }

        /* Styling untuk Toggle Password */
        .input-wrapper { position: relative; }
        .password-toggle {
            position: absolute; right: 16px; top: 50%; transform: translateY(-50%);
            color: var(--gray); cursor: pointer; font-size: 16px; z-index: 10;
        }

        .btn-reset {
            width: 100%; height: 48px; background: var(--primary); border: none;
            border-radius: var(--radius); color: white; font-weight: 600;
            text-transform: uppercase; letter-spacing: 0.5px; cursor: pointer;
            transition: 0.2s; margin-top: 10px;
        }
        .btn-reset:hover { background: var(--primary-light); }
        .btn-reset:disabled { opacity: 0.7; cursor: not-allowed; }

        .right-side {
            background: linear-gradient(135deg, var(--primary), #1e3a5f);
            color: white; height: 100vh; display: flex; align-items: center; justify-content: center;
            position: relative; overflow: hidden;
        }
        .right-side::before {
            content: ''; position: absolute; width: 100%; height: 100%;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><path d="M0 0 L100 100 L0 100 Z" fill="rgba(255,255,255,0.03)"/></svg>');
            background-size: cover;
        }
        .right-content { text-align: center; max-width: 80%; z-index: 2; }
        
        .icon-circle {
            width: 70px; height: 70px; background: rgba(255, 255, 255, 0.15);
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            margin: 0 auto 20px; backdrop-filter: blur(5px); border: 1px solid rgba(255, 255, 255, 0.2);
            animation: float 3s ease-in-out infinite;
        }
        @keyframes float { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-10px); } }

        .back-link { text-align: center; margin-top: 25px; padding-top: 20px; border-top: 1px solid var(--border-color); }
        .d-none { display: none; }

        @media (max-width: 992px) { 
            .right-side { display: none; } 
            .left-side { padding: 40px 24px; } 
        }
    </style>
</head>

<body>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-6 left-side">
            <h1 id="ui-title">Lupa Password?</h1>
            <div class="subtitle" id="ui-subtitle">Verifikasi NISN dan Email Anda untuk mereset password akun perpustakaan.</div>

            <form id="resetForm">
                <div id="step-1">
                    <div class="form-group">
                        <label><i class="fas fa-envelope mr-2 text-primary"></i>Alamat Email</label>
                        <input type="email" id="email" class="form-control" placeholder="nama@email.com" required>
                    </div>
                    
                </div>

                <div id="step-2" class="d-none">
                    <div class="form-group">
                        <label><i class="fas fa-key mr-2 text-primary"></i>Password Baru</label>
                        <div class="input-wrapper">
                            <input type="password" id="new_password" class="form-control" placeholder="Minimal 8 karakter">
                            <span class="password-toggle" onclick="togglePassword('new_password', 'eye1')">
                                <i class="fas fa-eye" id="eye1"></i>
                            </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-check-double mr-2 text-primary"></i>Konfirmasi Password</label>
                        <div class="input-wrapper">
                            <input type="password" id="confirm_password" class="form-control" placeholder="Ulangi password baru">
                            <span class="password-toggle" onclick="togglePassword('confirm_password', 'eye2')">
                                <i class="fas fa-eye" id="eye2"></i>
                            </span>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn-reset" id="btnSubmit">
                    <span id="btnText">Verifikasi Identitas</span>
                </button>
            </form>

            <div class="back-link">
                <a href="/login" class="text-decoration-none" style="color: var(--primary); font-weight: 600; font-size: 14px;">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali ke Login
                </a>
            </div>
        </div>

        <div class="col-lg-6 right-side">
            <div class="right-content">
                <div class="icon-circle">
                    <i class="fas fa-shield-alt fa-2x"></i>
                </div>
                <h2 class="font-weight-bold">Keamanan Akun</h2>
                <p class="opacity-75">
                    Kami membantu Anda memulihkan akses ke koleksi buku digital dan layanan perpustakaan SMK 4 BJN dengan aman.
                </p>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('template/vendor/jquery/jquery.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// Fungsi Toggle Password
function togglePassword(inputId, eyeId) {
    const passwordInput = document.getElementById(inputId);
    const eyeIcon = document.getElementById(eyeId);

    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        eyeIcon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        eyeIcon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}

let currentStep = 1;
let userId = null;

$('#resetForm').on('submit', async function(e) {
    e.preventDefault();
    const btn = $('#btnSubmit');
    const btnText = $('#btnText');

    if (currentStep === 1) {
        btn.prop('disabled', true).text('Memverifikasi...');
        
        try {
            const response = await fetch('http://localhost:8000/api/forgot-password', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify({
                    email: $('#email').val(),
                    
                })
            });
            const data = await response.json();

            if (response.ok) {
                userId = data.user_id;
                $('#step-1').addClass('d-none');
                $('#step-2').removeClass('d-none');
                $('#ui-title').text('Buat Password Baru');
                $('#ui-subtitle').text('Gunakan kombinasi password yang kuat dan mudah diingat.');
                btnText.text('Update Password');
                currentStep = 2;
                
                Swal.fire({ icon: 'success', title: 'Identitas Cocok', text: 'Silakan masukkan password baru Anda.', timer: 2000, showConfirmButton: false });
            } else {
                Swal.fire('Gagal', data.message || 'Data tidak ditemukan', 'error');
            }
        } catch (err) {
            Swal.fire('Error', 'Gagal menghubungi server', 'error');
        } finally {
            btn.prop('disabled', false);
            if(currentStep === 1) btnText.text('Verifikasi Identitas');
        }

    } else {
        const p1 = $('#new_password').val();
        const p2 = $('#confirm_password').val();

        if (p1.length < 8) return Swal.fire('Peringatan', 'Password minimal 8 karakter!', 'warning');
        if (p1 !== p2) return Swal.fire('Error', 'Konfirmasi password tidak cocok!', 'error');

        btn.prop('disabled', true).text('Memperbarui...');
        try {
            const response = await fetch('http://localhost:8000/api/reset-password', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify({
                    user_id: userId,
                    password: p1,
                    password_confirmation: p2
                })
            });

            if (response.ok) {
                Swal.fire('Berhasil!', 'Password diperbarui. Silakan login kembali.', 'success')
                .then(() => window.location.href = '/login');
            } else {
                Swal.fire('Gagal', 'Terjadi kesalahan saat update.', 'error');
            }
        } catch (err) {
            Swal.fire('Error', 'Server tidak merespon', 'error');
        } finally {
            btn.prop('disabled', false).text('Update Password');
        }
    }
});
</script>
</body>
</html>
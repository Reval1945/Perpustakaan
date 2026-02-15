<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Login</title>

    <!-- Custom fonts for this template -->
    <link href="{{ asset('template/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="{{ asset('template/css/sb-admin-2.css') }}" rel="stylesheet">
    
    <style>
        /* Hanya style minimal untuk fungsionalitas */
        .password-toggle {
            cursor: pointer;
            user-select: none;
        }
        
        .spinner-border-sm {
            width: 1rem;
            height: 1rem;
            border-width: 0.2em;
        }
        
        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
    </style>
</head>

<body class="bg-gradient-primary">

    <div class="container-fluid">
        <!-- Outer Row -->
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-xl-10 col-lg-12 col-md-9">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block position-relative">
                                <div class="bg-login-image position-absolute w-100 h-100" style="background: url('https://source.unsplash.com/random/600x800?technology,digital') center center no-repeat; background-size: cover;"></div>
                                <div class="position-absolute w-100 h-100 d-flex flex-column justify-content-center align-items-center p-5" style="background: linear-gradient(135deg, rgba(78, 115, 223, 0.85) 0%, rgba(78, 115, 223, 0.7) 100%);">
                                    <div class="text-center text-white">
                                        <i class="fas fa-lock fa-4x mb-4"></i>
                                        <h2 class="font-weight-bold mb-3">Masuk Aman</h2>
                                        <p class="mb-0">Akses akun Anda dengan autentikasi yang aman</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <div class="mb-4">
                                            <i class="fas fa-user-circle fa-3x text-primary"></i>
                                        </div>
                                        <h1 class="h4 text-gray-900 mb-2">Selamat Datang!</h1>
                                        <p class="text-muted mb-4">Silakan masuk ke akun Anda</p>
                                    </div>
                                    
                                    <!-- Alert untuk pesan error/success -->
                                    @if(session('success'))
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    @endif
                                    
                                    @if(session('error'))
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    @endif
                                    
                                    @if ($errors->any())
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <i class="fas fa-exclamation-triangle mr-2"></i> Please check the form below for errors
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    @endif

                                    <form id="loginForm">
                                        @csrf

                                        <div class="form-group">
                                            <label for="email" class="form-label font-weight-bold text-gray-700">Alamat Email</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="fas fa-envelope text-primary"></i>
                                                    </span>
                                                </div>
                                                <input
                                                    type="email"
                                                    class="form-control form-control-user @error('email') is-invalid @enderror"
                                                    id="email"
                                                    name="email"
                                                    placeholder="Enter your email address"
                                                    required
                                                    value="{{ old('email') }}"
                                                >
                                            </div>
                                            @error('email')
                                                <div class="invalid-feedback d-block">
                                                    <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="password" class="form-label font-weight-bold text-gray-700">Password</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="fas fa-lock text-primary"></i>
                                                    </span>
                                                </div>
                                                <input
                                                    type="password"
                                                    class="form-control form-control-user @error('password') is-invalid @enderror"
                                                    id="password"
                                                    name="password"
                                                    placeholder="Enter your password"
                                                    required
                                                >
                                                <div class="input-group-append">
                                                    <span class="input-group-text password-toggle" id="togglePassword">
                                                        <i class="fas fa-eye" id="eyeIcon"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            @error('password')
                                                <div class="invalid-feedback d-block">
                                                    <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <button
                                            type="submit"
                                            class="btn btn-primary btn-user btn-block fs-3"
                                            id="loginButton"
                                        >
                                            <span id="buttonText">Login</span>
                                            <span id="loadingSpinner" class="spinner-border spinner-border-sm ml-2 d-none" role="status" aria-hidden="true"></span>
                                        </button>
                                        
                                        <hr>
                                    </form>
                                    <hr>
                                    <div class="text-center">
                                        <a class="small" href="/register">Belum memiliki akun? Registrasi!</a>
                                    </div>
                                    
                                    <div class="text-center mt-4">
                                        <p class="small text-muted">
                                            <i class="fas fa-shield-alt mr-1"></i> Your information is secure and encrypted
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('template/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('template/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ asset('template/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{ asset('template/js/sb-admin-2.min.js') }}"></script>
    
    <script>
    $(document).ready(function () {

        $('#loginForm').on('submit', async function (e) {
            e.preventDefault();

            // loading state
            $('#loginButton').prop('disabled', true);
            $('#buttonText').text('Logging in...');
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

                if (!res.ok) {
                    throw new Error('Login gagal');
                }

                const data = await res.json();
                console.log(data);

                // SIMPAN TOKEN
                localStorage.setItem('token', data.token);
                localStorage.setItem('user', JSON.stringify(data.user));

                // REDIRECT SESUAI ROLE
                if (data.user.role === 'admin') {
                    window.location.href = '/admin/dashboard';
                } else if (data.user.role === 'user') {
                    window.location.href = '/anggota/dashboard';
                } else if (data.user.role === 'superadmin') {
                    window.location.href = '/superadmin/dashboard';
                } else {
                    alert('Role tidak dikenali');
                }

            } catch (err) {
                alert('Email atau password salah');
            } finally {
                $('#loginButton').prop('disabled', false);
                $('#buttonText').text('Login');
                $('#loadingSpinner').addClass('d-none');
            }
        });

    });
    </script>

    // logic untuk icon mata password
    <script>
    document.getElementById('togglePassword').addEventListener('click', function () {
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.classList.remove('fa-eye');
            eyeIcon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            eyeIcon.classList.remove('fa-eye-slash');
            eyeIcon.classList.add('fa-eye');
        }
    });
    </script>
</body>

</html>
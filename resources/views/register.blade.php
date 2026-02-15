<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Register</title>

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
                                        <i class="fas fa-user-plus fa-4x mb-4"></i>
                                        <h2 class="font-weight-bold mb-3">Buat Akun</h2>
                                        <p class="mb-0">Bergabung dengan sistem kami dan mulai dengan autentikasi yang aman</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <div class="mb-4">
                                            <i class="fas fa-user-circle fa-3x text-primary"></i>
                                        </div>
                                        <h1 class="h4 text-gray-900 mb-2">Buat Akun Anda</h1>
                                        <p class="text-muted mb-4">Bergabung dengan sistem kami dan mulai dengan autentikasi yang aman</p>
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
                                        <i class="fas fa-exclamation-triangle mr-2"></i> Harap periksa formulir di bawah ini untuk kesalahan
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    @endif

                                    <form class="user" id="registerForm">
                                        @csrf

                                        <!-- Nama -->
                                        <div class="form-group">
                                            <label for="name" class="form-label font-weight-bold text-gray-700">Nama Lengkap</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="fas fa-user text-primary"></i>
                                                    </span>
                                                </div>
                                                <input
                                                    type="text"
                                                    class="form-control form-control-user @error('name') is-invalid @enderror"
                                                    id="name"
                                                    name="name"
                                                    placeholder="Enter your full name"
                                                    required
                                                    value="{{ old('name') }}"
                                                >
                                            </div>
                                            @error('name')
                                                <div class="invalid-feedback d-block">
                                                    <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <!-- Email -->
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

                                        <!-- Password -->
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

                                        <!-- NISN -->
                                        <div class="form-group">
                                            <label for="nisn" class="form-label font-weight-bold text-gray-700">NISN</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="fas fa-id-card text-primary"></i>
                                                    </span>
                                                </div>
                                                <input
                                                    type="text"
                                                    class="form-control form-control-user @error('nisn') is-invalid @enderror"
                                                    id="nisn"
                                                    name="nisn"
                                                    placeholder="Enter your NISN"
                                                    required
                                                    value="{{ old('nisn') }}"
                                                >
                                            </div>
                                            @error('nisn')
                                                <div class="invalid-feedback d-block">
                                                    <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <!-- No Telp -->
                                        <div class="form-group">
                                            <label for="no_telp" class="form-label font-weight-bold text-gray-700">No Telp</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="fas fa-phone text-primary"></i>
                                                    </span>
                                                </div>
                                                <input
                                                    type="text"
                                                    class="form-control form-control-user @error('no_telp') is-invalid @enderror"
                                                    id="no_telp"
                                                    name="phone"
                                                    placeholder="Enter your phone number"
                                                    required
                                                    value="{{ old('no_telp') }}"
                                                >
                                            </div>
                                            @error('no_telp')
                                                <div class="invalid-feedback d-block">
                                                    <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <!-- Kelas -->
                                        <div class="form-group">
                                            <label for="kelas" class="form-label font-weight-bold text-gray-700">Kelas</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="fas fa-school text-primary"></i>
                                                    </span>
                                                </div>
                                                <input
                                                    type="text"
                                                    class="form-control form-control-user @error('kelas') is-invalid @enderror"
                                                    id="kelas"
                                                    name="class"
                                                    placeholder="Enter your class"
                                                    required
                                                    value="{{ old('kelas') }}"
                                                >
                                            </div>
                                            @error('kelas')
                                                <div class="invalid-feedback d-block">
                                                    <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <!-- No Absen -->
                                        <div class="form-group">
                                            <label for="no_absen" class="form-label font-weight-bold text-gray-700">No Absen</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="fas fa-list-ol text-primary"></i>
                                                    </span>
                                                </div>
                                                <input
                                                    type="number"
                                                    class="form-control form-control-user @error('no_absen') is-invalid @enderror"
                                                    id="no_absen"
                                                    name="roll_number"
                                                    placeholder="Enter your absent number"
                                                    required
                                                    value="{{ old('no_absen') }}"
                                                >
                                            </div>
                                            @error('no_absen')
                                                <div class="invalid-feedback d-block">
                                                    <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <button
                                            type="submit"
                                            class="btn btn-primary btn-user btn-block"
                                            id="registerButton"
                                        >
                                            <span id="buttonText">Register</span>
                                            <span id="loadingSpinner" class="spinner-border spinner-border-sm ml-2 d-none" role="status" aria-hidden="true"></span>
                                        </button>
                                    </form>
                                    
                                    <hr>
                                    <div class="text-center">
                                        <a class="small" href="/login">Sudah memiliki akun? Login!</a>
                                    </div>
                                    
                                    <div class="text-center mt-4">
                                        <p class="small text-muted">
                                            <i class="fas fa-shield-alt mr-1"></i> Informasi Anda aman dan dienkripsi.
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
    $('#registerForm').submit(function(e) {
        e.preventDefault();

        $('#registerButton').prop('disabled', true);
        $('#buttonText').text('Registering...');
        $('#loadingSpinner').removeClass('d-none');

        const payload = {
            name: $('#name').val(),
            email: $('#email').val(),
            password: $('#password').val(),
            class: $('#kelas').val(),
            roll_number: $('#no_absen').val(),
            phone: $('#no_telp').val(),
            nisn: $('#nisn').val(),
        };

        fetch('http://localhost:8000/api/register', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(payload)
        })
        .then(res => res.json())
        .then(data => {
            if (data.token) {
                alert(data.message);

                // simpan token (opsional)
                localStorage.setItem('token', data.token);

                window.location.href = '/login';
            } else {
                alert(data.message || 'Registrasi gagal');
            }
        })
        .catch(err => {
            console.error(err);
            alert('Terjadi kesalahan server');
        })
        .finally(() => {
            $('#registerButton').prop('disabled', false);
            $('#buttonText').text('Register');
            $('#loadingSpinner').addClass('d-none');
        });
    });
    </script>

    <script>
        $(document).ready(function() {
            // Toggle password visibility
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
            
            // Form submission loading state
            $('#registerForm').submit(function() {
                $('#registerButton').prop('disabled', true);
                $('#buttonText').text('Registering...');
                $('#loadingSpinner').removeClass('d-none');
            });
            
            // Auto focus on name field
            $('#name').focus();
            
            // Add animation to form elements
            $('.form-group').addClass('animate__animated animate__fadeInUp');
            
            // Remove is-invalid class on input focus
            $('.form-control').focus(function() {
                $(this).removeClass('is-invalid');
            });
            
            // Show password strength (optional feature)
            $('#password').on('keyup', function() {
                const password = $(this).val();
                const strengthBar = $('#passwordStrength');
                
                if (password.length === 0) {
                    strengthBar.removeClass().addClass('progress-bar bg-secondary');
                    strengthBar.css('width', '0%');
                } else if (password.length < 6) {
                    strengthBar.removeClass().addClass('progress-bar bg-danger');
                    strengthBar.css('width', '30%');
                } else if (password.length < 10) {
                    strengthBar.removeClass().addClass('progress-bar bg-warning');
                    strengthBar.css('width', '60%');
                } else {
                    strengthBar.removeClass().addClass('progress-bar bg-success');
                    strengthBar.css('width', '100%');
                }
            });
        });
    </script>
</body>

</html>
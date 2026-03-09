<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>@yield('title', 'Adminn')</title>

    <!-- Fonts -->
    <link href="{{ asset('template/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet">

    <!-- SB Admin 2 -->
    <link href="{{ asset('template/css/sb-admin-2.min.css') }}" rel="stylesheet">

    <style>
        :root {
            --primary: #2C5AA0;
            --primary-light: #4A7BC8;
            --primary-soft: #e8f0fe;
            --success: #10b981;
            --danger: #ef4444;
            --dark: #1e293b;
            --gray: #64748b;
            --gray-light: #f1f5f9;
            --border: #e2e8f0;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #f8fafc;
        }

        /* Sidebar */
        .bg-gradient-primary {
            background: linear-gradient(180deg, var(--primary) 0%, #1e3a5f 100%) !important;
        }

        .sidebar .nav-item .nav-link {
            font-weight: 500;
            font-size: 0.9rem;
            padding: 0.75rem 1rem;
            margin: 0.2rem 1rem;
            border-radius: 8px;
            transition: all 0.2s;
        }

        .sidebar .nav-item .nav-link i {
            font-size: 1rem;
            width: 1.5rem;
        }

        .sidebar .nav-item.active .nav-link {
            background: rgba(255,255,255,0.15);
            font-weight: 600;
        }

        .sidebar .nav-item .nav-link:hover {
            background: rgba(255,255,255,0.1);
        }

        .sidebar-brand {
            padding: 1.5rem 1rem;
            font-weight: 700;
        }

        .sidebar-brand-icon i {
            font-size: 1.8rem;
        }

        /* Topbar */
        .topbar {
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.02);
            padding: 0.5rem 1.5rem;
        }

        .navbar-search .input-group {
            border: 1px solid var(--border);
            border-radius: 30px;
            overflow: hidden;
        }

        .navbar-search .form-control {
            border: none;
            background: white;
            font-size: 0.9rem;
            padding: 0.5rem 1.2rem;
            height: auto;
        }

        .navbar-search .btn {
            border: none;
            background: white;
            color: var(--primary);
            padding: 0.5rem 1.2rem;
        }

        .navbar-search .btn:hover {
            background: var(--primary-soft);
        }

        #filterTanggal {
            border: 1px solid var(--border);
            border-radius: 30px;
            padding: 0.4rem 1rem;
            font-size: 0.9rem;
            height: auto;
            width: 170px;
        }

        #filterTanggal:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(44,90,160,0.1);
        }

        .dropdown-menu {
            border: none;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            border-radius: 12px;
            padding: 0.5rem;
        }

        .dropdown-item {
            border-radius: 8px;
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
        }

        .dropdown-item i {
            width: 1.5rem;
            color: var(--gray);
        }

        .dropdown-item:hover {
            background: var(--primary-soft);
            color: var(--dark);
        }

        .img-profile {
            width: 38px;
            height: 38px;
            object-fit: cover;
            border: 2px solid white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        #navName {
            font-weight: 600;
            color: var(--dark) !important;
        }

        /* Buttons */
        .btn-outline-primary {
            border-color: var(--border);
            color: var(--dark);
        }

        .btn-outline-primary:hover {
            background: var(--primary-soft);
            border-color: var(--primary);
            color: var(--primary);
        }

        .btn-primary {
            background: var(--primary);
            border: none;
        }

        .btn-primary:hover {
            background: var(--primary-light);
        }

        .btn-danger {
            background: var(--danger);
            border: none;
        }

        /* Modal */
        .modal-content {
            border: none;
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }

        .modal-header {
            border-bottom: 1px solid var(--border);
            padding: 1.2rem 1.5rem;
        }

        .modal-header .modal-title {
            font-weight: 700;
        }

        .modal-body {
            padding: 1.5rem;
        }

        .modal-footer {
            border-top: 1px solid var(--border);
            padding: 1.2rem 1.5rem;
        }

        .form-control {
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 0.6rem 1rem;
            font-size: 0.95rem;
        }

        .form-control:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(44,90,160,0.1);
        }

        #previewPhoto {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid white;
            box-shadow: 0 5px 10px rgba(0,0,0,0.1);
        }

        /* Footer */
        .sticky-footer {
            background: white;
            border-top: 1px solid var(--border);
            padding: 1rem 0;
        }

        .copyright {
            color: var(--gray);
            font-size: 0.9rem;
        }

        /* Scroll to top */
        .scroll-to-top {
            background: var(--primary);
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .scroll-to-top:hover {
            background: var(--primary-light);
        }

        /* Badge */
        .badge-counter {
            background: var(--danger);
            color: white;
            font-size: 0.7rem;
            padding: 0.2rem 0.5rem;
            border-radius: 30px;
            margin-left: 0.5rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .topbar {
                padding: 0.5rem 1rem;
            }
            
            #filterTanggal {
                width: 140px;
            }
        }
    </style>
</head>

<body id="page-top">

<div id="wrapper">

    <!-- SIDEBAR -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="#">
            <div class="sidebar-brand-icon">
                <i class="fas fa-book"></i>
            </div>
            <div class="sidebar-brand-text mx-3">Perpustakaan</div>
        </a>

        <hr class="sidebar-divider my-0">

        <!-- Nav Item - Dashboard -->
            <li class="nav-item {{ Request::is('superadmin/dashboard') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('/superadmin/dashboard') }}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <li class="nav-item {{ Request::is('superadmin/admin*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('/superadmin/admin') }}">
                    <i class="fas fa-user-tie"></i>
                    <span>Admin</span>
                </a>
            </li>

        <hr class="sidebar-divider d-none d-md-block">
    </ul>
    <!-- END SIDEBAR -->

    <!-- CONTENT WRAPPER -->
    <div id="content-wrapper" class="d-flex flex-column">

        <div id="content">

            <!-- TOPBAR -->
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 shadow">
                <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                    <i class="fa fa-bars"></i>
                </button>

                <ul class="navbar-nav ml-auto">
                    <li class="nav-item dropdown no-arrow">
                        <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">
                            <span id="navName" class="mr-2 text-gray-600 small">Loading...</span>
                            <img id="navPhoto"
                                class="img-profile rounded-circle"
                                src="{{ asset('template/img/undraw_profile.svg') }}">
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow">
                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#modalProfile">
                                <i class="fas fa-user-edit fa-sm fa-fw mr-2 text-gray-400"></i>
                                Edit Profil
                            </a>
                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                Logout
                            </a>
                        </div>
                    </li>
                </ul>
            </nav>
            <!-- END TOPBAR -->

            <!-- PAGE CONTENT -->
            <div class="container-fluid">
                @yield('content')
            </div>

        </div>

        <!-- FOOTER -->
        <footer class="sticky-footer bg-white">
            <div class="container my-auto">
                <div class="copyright text-center my-auto">
                    <span>© Perpustakaan 2026</span>
                </div>
            </div>
        </footer>

    </div>
</div>

<!-- MODAL EDIT PROFILE -->
<div class="modal fade" id="modalProfile" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <form id="formProfile" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Edit Profil</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">

                    <div class="text-center mb-4">
                        <img id="previewPhoto"
                             src="{{ asset('template/img/undraw_profile.svg') }}"
                             class="rounded-circle img-thumbnail"
                             style="width:100px;height:100px;object-fit:cover;">
                    </div>

                    <div class="form-group">
                        <label for="inputName">Nama Lengkap</label>
                        <input type="text" name="name" id="inputName" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="inputPhoto">Foto Profil</label>
                        <div class="custom-file">
                            <input type="file" name="photo" id="inputPhoto" class="custom-file-input" accept="image/*">
                            <label class="custom-file-label" for="inputPhoto">Pilih file</label>
                        </div>
                        <small class="form-text text-muted">Format: JPG, PNG. Maks: 2MB</small>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>

        </div>
    </div>
</div>

<!-- Scroll -->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

<!-- JS -->
<script src="{{ asset('template/vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('template/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('template/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
<script src="{{ asset('template/js/sb-admin-2.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="modal fade" id="logoutModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Logout</h5>
                <button class="close" data-dismiss="modal">×</button>
            </div>
            <div class="modal-body">
                Apakah kamu yakin ingin logout?
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal">Tidak</button>
                <button id="btnLogout" class="btn btn-danger">Logout</button>
            </div>
        </div>
    </div>
</div>
<script>
if (!localStorage.getItem('token')) {
    window.location.href = '/';
}

document.getElementById('btnLogout').addEventListener('click', function () {
    fetch('http://127.0.0.1:8000/api/logout', {
        method: 'POST',
        headers: {
            'Authorization': `Bearer ${localStorage.getItem('token')}`,
            'Accept': 'application/json'
        }
    }).finally(() => {
        localStorage.removeItem('token');
        window.location.href = '/';
    });
});

</script>

<script>
document.addEventListener("DOMContentLoaded", function(){

    // =====================
    // LOAD USER NAVBAR
    // =====================
    async function loadUser(){
        const token = localStorage.getItem('token');
        if(!token) return;

        const res = await fetch('/api/me2',{
            headers:{ Authorization:'Bearer '+token }
        });

        if(!res.ok) return;

        const data = await res.json();

        document.getElementById('navName').innerText = data.name;
        document.getElementById('navPhoto').src =
            data.photo + '?t=' + new Date().getTime();

        // isi modal otomatis
        document.getElementById('inputName').value = data.name;
        document.getElementById('previewPhoto').src = data.photo;
    }

    loadUser();

    // PREVIEW FOTO & VALIDASI
    // =====================
    const inputPhoto = document.getElementById('inputPhoto');

    // Inisialisasi Toast SweetAlert2
    const Toast = Swal.mixin({
        toast: true,
        position: 'center',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true
    });

    if(inputPhoto){
        inputPhoto.addEventListener('change', e => {
            const file = e.target.files[0];
            if(!file) return;

            // 1. Validasi Ukuran (2MB = 2048 * 1024 bytes)
            const maxSize = 2 * 1024 * 1024; 
            if(file.size > maxSize) {
                Toast.fire({
                    icon: 'error',
                    title: 'Ukuran file terlalu besar! Maksimal 2MB.'
                });
                inputPhoto.value = ""; // Reset input
                return;
            }

            // 2. Validasi Format
            const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
            if(!allowedTypes.includes(file.type)) {
                Toast.fire({
                    icon: 'error',
                    title: 'Format tidak didukung! Gunakan JPG atau PNG.'
                });
                inputPhoto.value = ""; // Reset input
                return;
            }

            // Jika lolos validasi, tampilkan preview
            document.getElementById('previewPhoto').src = URL.createObjectURL(file);
            
            // Update label input file
            const fileName = file.name;
            document.querySelector('.custom-file-label').innerText = fileName;
        });
    }


    // =====================
    // SUBMIT PROFILE
    // =====================
    const formProfile = document.getElementById('formProfile');

    if(formProfile){
        formProfile.addEventListener('submit',async e=>{
            e.preventDefault();

            const token = localStorage.getItem('token');
            const form = new FormData(e.target);

            console.log([...form]); // ← DEBUG

            const res = await fetch('/api/update-profile2',{
               method: 'POST',
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('token')}`,
                    'Accept': 'application/json'
                },
                body:form
            });

            const data = await res.json();
            console.log(data);

            if (res.ok) {
                Toast.fire({
                    icon: 'success',
                    title: data.message || 'Profil berhasil diperbarui'
                });
                $('#modalProfile').modal('hide');
                loadUser(); 
            } else {
                Toast.fire({
                    icon: 'error',
                    title: data.message || 'Gagal memperbarui profil'
                });
            }
        });
    }

});
</script>
@yield('scripts')
</body>
</html>

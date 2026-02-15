<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>@yield('title', 'Admin')</title>

    <!-- Fonts -->
    <link href="{{ asset('template/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet">

    <!-- SB Admin 2 -->
    <link href="{{ asset('template/css/sb-admin-2.min.css') }}" rel="stylesheet">
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

        <li class="nav-item {{ Request::is('admin/dashboard') ? 'active' : '' }}">
            <a class="nav-link" href="{{ url('/admin/dashboard') }}">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <li class="nav-item {{ Request::is('admin/anggota') ? 'active' : '' }}">
            <a class="nav-link" href="{{ url('/admin/anggota') }}">
                <i class="fas fa-fw fa-users"></i>
                <span>Anggota</span>
            </a>
        </li>

        <li class="nav-item {{ Request::is('admin/pengunjung') ? 'active' : '' }}">
            <a class="nav-link" href="{{ url('/admin/pengunjung') }}">
                <i class="fas fa-fw fa-user"></i>
                <span>Pengunjung</span>
            </a>
        </li>

        <li class="nav-item {{ Request::is('admin/buku') ? 'active' : '' }}">
            <a class="nav-link" href="{{ url('/admin/buku') }}">
                <i class="fas fa-fw fa-book"></i>
                <span>Buku</span>
            </a>
        </li>

        <li class="nav-item {{ Request::is('admin/aturanpeminjaman') ? 'active' : '' }}">
            <a class="nav-link" href="{{ url('/admin/aturanpeminjaman') }}">
                <i class="fas fa-clipboard-list"></i>
                <span>Aturan Peminjaman</span>
            </a>
        </li>

        <li class="nav-item {{ Request::is('admin/kategori') ? 'active' : '' }}">
            <a class="nav-link" href="{{ url('/admin/kategori') }}">
                <i class="fas fa-list"></i>
                <span>Kategori</span>
            </a>
        </li>

        <li class="nav-item {{ Request::is('admin/transaksi/peminjaman') ? 'active' : '' }}">
            <a class="nav-link" href="{{ url('/admin/transaksi/peminjaman') }}">
                <i class="fas fa-money-check"></i>
                <span>Transaksi</span>
            </a>
        </li>

        <li class="nav-item {{ Request::is('admin/laporanpeminjaman') ? 'active' : '' }}">
            <a class="nav-link" href="{{ url('/admin/laporanpeminjaman') }}">
                <i class="fas fa-fw fa-file-alt"></i>
                <span>Laporan</span>
            </a>
        </li>
        <li class="nav-item {{ Request::is('admin/laporandenda') ? 'active' : '' }}">
            <a class="nav-link" href="{{ url('/admin/laporandenda') }}">
                <i class="fas fa-fw fa-exclamation-triangle"></i>
                <span>Laporan Denda</span>
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

                @php
                    use App\Models\Category;
                    $search = request('search');
                    $categoryId = request('category');
                    $allCategories = Category::all();
                @endphp

                @if (!Request::is('admin/dashboard'))
                    
                        <form class="d-none d-sm-inline-block form-inline mr-2 my-2 my-md-0 mw-100 navbar-search" id="searchFormAnggota">
                            <div class="input-group">
                                <input type="text" name="search" id="searchInput"
                                    class="form-control bg-light border-0 small"
                                    placeholder="Cari"
                                    value="{{ $search }}">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit">Cari</button>
                                </div>
                            </div>
                        </form>
                        @if (Request::routeIs('admin.buku'))
                        <div class="d-flex align-items-center mb-3">

                            {{-- Search Box --}}
                            <input type="text" id="searchInputAdmin" class="form-control mr-2" placeholder="Cari buku...">

                            {{-- Dropdown Kategori --}}
                            <div class="dropdown ml-2" id="category-filter-admin">
                                <button class="btn btn-outline-primary dropdown-toggle" type="button"
                                    id="categoryDropdownAdmin" data-toggle="dropdown" data-selected="">
                                    Semua Kategori
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="#" data-category="">Semua</a>
                                    @foreach($allCategories as $cat)
                                        <a class="dropdown-item" href="#" data-category="{{ $cat->id }}">
                                            {{ $cat->name }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                 @endif

                <ul class="navbar-nav ml-auto">
                    <li class="nav-item dropdown no-arrow">
                        <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">
                            <span class="mr-2 text-gray-600 small">Admin</span>
                            <img class="img-profile rounded-circle"
                                 src="{{ asset('template/img/undraw_profile.svg') }}">
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow">
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

<!-- Scroll -->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

<!-- JS -->
<script src="{{ asset('template/vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('template/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('template/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
<script src="{{ asset('template/js/sb-admin-2.min.js') }}"></script>

<script>
    document.getElementById("togglePassword").addEventListener("click", function () {
        const password = document.getElementById("passwordInput");
        const icon = document.getElementById("iconPassword");

        if (password.type === "password") {
            password.type = "text";
            icon.classList.remove("fa-eye-slash");
            icon.classList.add("fa-eye");
        } else {
            password.type = "password";
            icon.classList.remove("fa-eye");
            icon.classList.add("fa-eye-slash");
        }
    });
</script>

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
    window.location.href = '/login';
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
        window.location.href = '/login';
    });
});

</script>
@yield('scripts')

</body>
</html>

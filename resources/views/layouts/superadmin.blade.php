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

                @if (!Request::is('superadmin/dashboard'))
                <form class="d-none d-sm-inline-block form-inline mr-2 my-2 my-md-0 mw-100 navbar-search" id="searchForm">
                    <div class="input-group">
                        <input type="text" name="search" id="searchInput"
                            class="form-control bg-light border-0 small"
                            placeholder="Cari buku...">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit">Cari</button>
                        </div>
                    </div>
                </form>
                @endif

                <ul class="navbar-nav ml-auto">
                    <li class="nav-item dropdown no-arrow">
                        <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">
                            <span class="mr-2 text-gray-600 small">Super Admin</span>
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

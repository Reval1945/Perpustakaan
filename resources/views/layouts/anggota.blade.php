<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('title', 'Anggota')</title>

    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="{{ asset('template/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('template/css/sb-admin-2.min.css') }}" rel="stylesheet">

    <style>
    /* ====================================================
       VARIABLES
    ==================================================== */
    :root {
        --primary:        #2C5AA0;
        --primary-light:  #4A7BC8;
        --primary-soft:   #e8f0fe;
        --success:        #10b981;
        --success-soft:   #ecfdf5;
        --warning:        #f59e0b;
        --warning-soft:   #fffbeb;
        --danger:         #ef4444;
        --danger-soft:    #fef2f2;
        --dark:           #1e293b;
        --gray:           #64748b;
        --gray-light:     #f1f5f9;
        --border:         #e2e8f0;
        --sidebar-w:      230px;
        --sidebar-icon-w: 72px;
        --topbar-h:       62px;
        --radius:         10px;
    }

    /* ====================================================
       BASE
    ==================================================== */
    *, *::before, *::after { box-sizing: border-box; }
    html, body { height: 100%; }
    body {
        font-family: 'Sora', sans-serif !important;
        background: #f8fafc;
        margin: 0; padding: 0;
    }

    /* ====================================================
       LAYOUT
    ==================================================== */
    #wrapper {
        display: flex;
        width: 100%;
        min-height: 100vh;
    }
    #content-wrapper {
        display: flex;
        flex-direction: column;
        flex: 1;
        min-width: 0;
        overflow-x: hidden;
    }
    #content { flex: 1; }

    /* ====================================================
       SIDEBAR
    ==================================================== */
    .sidebar {
        position: sticky;
        top: 0;
        height: 100vh;
        width: var(--sidebar-w) !important;
        flex-shrink: 0;
        overflow-y: auto;
        overflow-x: hidden;
        transition: width 0.25s ease;
        background: linear-gradient(180deg, var(--primary) 0%, #1e3a5f 100%) !important;
        z-index: 100;
    }

    /* Collapsed (desktop) */
    .sidebar.toggled {
        width: var(--sidebar-icon-w) !important;
    }
    .sidebar.toggled .sidebar-brand-text,
    .sidebar.toggled .sidebar-heading,
    .sidebar.toggled .nav-item .nav-link span {
        display: none !important;
    }
    .sidebar.toggled .nav-item .nav-link {
        justify-content: center !important;
        padding: 0.75rem 0 !important;
        margin: 0.15rem 0.4rem !important;
        gap: 0 !important;
    }
    .sidebar.toggled .sidebar-brand {
        justify-content: center !important;
        padding-left: 0 !important;
    }
    .sidebar.toggled .sidebar-divider {
        margin: 0.4rem 0.6rem !important;
    }

    /* Brand */
    .sidebar-brand {
        display: flex !important;
        align-items: center;
        padding: 1.25rem 1rem 1.25rem 1.25rem;
        font-weight: 700;
        text-decoration: none !important;
        color: white !important;
        white-space: nowrap;
        gap: 0.6rem;
    }
    .sidebar-brand-icon i { font-size: 1.6rem; flex-shrink: 0; }
    .sidebar-brand-text   { font-size: 1rem; font-weight: 700; }

    /* Heading */
    .sidebar-heading {
        font-size: 0.62rem;
        font-weight: 700;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: rgba(255,255,255,0.38);
        padding: 0.5rem 1.25rem 0.2rem;
        white-space: nowrap;
    }

    /* Divider */
    .sidebar-divider {
        border: none;
        border-top: 1px solid rgba(255,255,255,0.1);
        margin: 0.4rem 1rem;
    }

    /* Nav items */
    .sidebar .nav-item { list-style: none; }
    .sidebar .nav-item .nav-link {
        display: flex;
        align-items: center;
        font-weight: 500;
        font-size: 0.875rem;
        padding: 0.65rem 0.9rem;
        margin: 0.15rem 0.75rem;
        border-radius: var(--radius);
        color: rgba(255,255,255,0.82) !important;
        text-decoration: none !important;
        white-space: nowrap;
        transition: background 0.18s, color 0.18s;
        gap: 0.65rem;
    }
    .sidebar .nav-item .nav-link i {
        font-size: 0.9rem;
        width: 1.2rem;
        text-align: center;
        flex-shrink: 0;
    }
    .sidebar .nav-item.active .nav-link {
        background: rgba(255,255,255,0.16);
        color: #fff !important;
        font-weight: 600;
    }
    .sidebar .nav-item .nav-link:hover {
        background: rgba(255,255,255,0.1);
        color: #fff !important;
    }

    /* ====================================================
       TOPBAR
    ==================================================== */
    .topbar {
        position: sticky;
        top: 0;
        z-index: 99;
        height: var(--topbar-h);
        background: #fff;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        padding: 0 1.25rem;
        gap: 0.75rem;
        flex-shrink: 0;
        box-shadow: 0 1px 6px rgba(0,0,0,0.04);
    }

    /* Hamburger */
    .btn-hamburger {
        width: 38px; height: 38px;
        border-radius: 10px;
        border: 1px solid var(--border);
        background: transparent;
        color: var(--gray);
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; flex-shrink: 0;
        transition: background 0.18s, color 0.18s, border-color 0.18s;
        padding: 0; font-size: 0.9rem; line-height: 1;
    }
    .btn-hamburger:hover {
        background: var(--primary-soft);
        color: var(--primary);
        border-color: var(--primary-soft);
    }
    .btn-hamburger:focus { outline: none; }

    /* Right section */
    .topbar-right {
        margin-left: auto;
        display: flex;
        align-items: center;
    }

    /* ── Profile: style ANGGOTA (nama teks + foto, dropdown klasik) ── */
    .nav-item.dropdown .nav-link {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.3rem 0.5rem;
        border-radius: 50px;
        color: var(--dark) !important;
        text-decoration: none !important;
        transition: background 0.18s;
    }
    .img-profile {
        width: 36px; height: 36px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid white;
        box-shadow: 0 2px 6px rgba(0,0,0,0.12);
        flex-shrink: 0;
    }
    #navName {
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--dark);
        white-space: nowrap;
    }

    /* Dropdown menu */
    .dropdown-menu {
        border: none;
        box-shadow: 0 8px 24px rgba(0,0,0,0.11);
        border-radius: 14px;
        padding: 0.5rem;
        min-width: 210px;
        margin-top: 0.5rem !important;
    }
    .dropdown-item {
        border-radius: 8px;
        padding: 0.5rem 0.9rem;
        font-size: 0.875rem;
        display: flex; align-items: center; gap: 0.6rem;
        color: var(--dark);
        transition: background 0.15s;
    }
    .dropdown-item i {
        width: 1.1rem;
        color: var(--gray);
        font-size: 0.82rem;
    }
    .dropdown-item:hover { background: var(--primary-soft); color: var(--primary); }
    .dropdown-item:hover i { color: var(--primary); }
    .dropdown-item.text-danger { color: var(--danger) !important; }
    .dropdown-item.text-danger i { color: var(--danger) !important; }
    .dropdown-item.text-danger:hover { background: var(--danger-soft); }
    .dropdown-divider { margin: 0.3rem 0.5rem; border-color: var(--border); }

    /* ====================================================
       PAGE CONTENT
    ==================================================== */
    .container-fluid { padding: 1.5rem 1.5rem 2rem; }

    /* ====================================================
       FOOTER
    ==================================================== */
    .sticky-footer {
        background: white;
        border-top: 1px solid var(--border);
        padding: 0.875rem 0;
        margin-top: auto;
    }
    .copyright { color: var(--gray); font-size: 0.85rem; text-align: center; }

    /* ====================================================
       SCROLL TO TOP
    ==================================================== */
    .scroll-to-top {
        background: var(--primary);
        width: 38px; height: 38px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        color: white; text-decoration: none;
        position: fixed; bottom: 1.5rem; right: 1.5rem; z-index: 500;
        box-shadow: 0 4px 12px rgba(44,90,160,0.35);
        transition: background 0.18s, transform 0.18s;
    }
    .scroll-to-top:hover {
        background: var(--primary-light);
        transform: translateY(-2px);
        color: white; text-decoration: none;
    }

    /* ====================================================
       GLOBAL OVERRIDES
    ==================================================== */
    .btn-primary  { background: var(--primary); border-color: var(--primary); }
    .btn-primary:hover { background: var(--primary-light); border-color: var(--primary-light); }
    .btn-danger   { background: var(--danger); border-color: var(--danger); }
    .btn-outline-primary {
        border-color: var(--border); color: var(--dark);
    }
    .btn-outline-primary:hover {
        background: var(--primary-soft); border-color: var(--primary); color: var(--primary);
    }

    .form-control {
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 0.55rem 0.9rem;
        font-size: 0.9rem;
    }
    .form-control:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(44,90,160,0.1);
    }

    .modal-content {
        border: none; border-radius: 16px;
        box-shadow: 0 20px 48px rgba(0,0,0,0.12);
    }
    .modal-header { border-bottom: 1px solid var(--border); padding: 1.1rem 1.4rem; }
    .modal-title  { font-weight: 700; font-size: 1rem; color: var(--dark); }
    .modal-body   { padding: 1.4rem; }
    .modal-footer { border-top: 1px solid var(--border); padding: 1rem 1.4rem; }

    #previewPhoto {
        width: 90px; height: 90px;
        object-fit: cover; border-radius: 50%;
        border: 3px solid #fff;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    /* ====================================================
       OVERLAY (mobile)
    ==================================================== */
    #sidebarOverlay {
        display: none;
        position: fixed; inset: 0;
        background: rgba(0,0,0,0.42);
        z-index: 1049;
        opacity: 0;
        transition: opacity 0.25s ease;
    }
    #sidebarOverlay.show { display: block; opacity: 1; }

    /* ====================================================
       SEMBUNYIKAN HAMBURGER by default
    ==================================================== */
    #sidebarToggleTop,
    #sidebarToggle { display: none !important; }

    /* ====================================================
       TABLET (768px – 1199px) — sidebar icon-only
    ==================================================== */
    @media (min-width: 768px) and (max-width: 1199.98px) {
        .sidebar { width: var(--sidebar-icon-w) !important; }
        .sidebar .sidebar-brand-text,
        .sidebar .sidebar-heading,
        .sidebar .nav-item .nav-link span { display: none !important; }
        .sidebar .nav-item .nav-link {
            justify-content: center !important;
            padding: 0.75rem 0 !important;
            margin: 0.15rem 0.4rem !important;
            gap: 0 !important;
        }
        .sidebar .sidebar-brand { justify-content: center !important; padding-left: 0 !important; }
        .sidebar .sidebar-divider { margin: 0.4rem 0.6rem !important; }
        #navName { display: none !important; }
    }

    /* ====================================================
       MOBILE (< 768px) — off-canvas sidebar
    ==================================================== */
    @media (max-width: 767.98px) {
        .sidebar {
            position: fixed !important;
            top: 0; left: 0;
            height: 100vh !important;
            width: var(--sidebar-w) !important;
            z-index: 1050;
            transform: translateX(-100%);
            transition: transform 0.28s cubic-bezier(0.4,0,0.2,1) !important;
        }
        .sidebar .sidebar-brand-text,
        .sidebar .sidebar-heading,
        .sidebar .nav-item .nav-link span {
            display: inline-block !important;
            opacity: 1 !important;
            width: auto !important;
            overflow: visible !important;
        }
        .sidebar .nav-item .nav-link {
            justify-content: flex-start !important;
            padding: 0.65rem 0.9rem !important;
            margin: 0.15rem 0.75rem !important;
            gap: 0.65rem !important;
        }
        .sidebar .sidebar-brand {
            justify-content: flex-start !important;
            padding-left: 1.25rem !important;
        }
        .sidebar.mobile-open {
            transform: translateX(0) !important;
            box-shadow: 8px 0 32px rgba(0,0,0,0.2);
        }

        #wrapper         { display: block; }
        #content-wrapper { width: 100%; }

        .topbar { padding: 0 0.875rem; }

        /* Tampilkan hamburger mobile saja */
        #sidebarToggleTop { display: flex !important; }
        #sidebarToggle    { display: none !important; }

        #navName { display: none !important; }

        .container-fluid { padding: 1rem 0.875rem 1.5rem; }
        .modal-dialog    { margin: 0.75rem; }
    }
    </style>
</head>

<body id="page-top">

<div id="sidebarOverlay"></div>

<div id="wrapper">

    <!-- ================================================
         SIDEBAR
    ================================================ -->
    <ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar">

        <a class="sidebar-brand" href="#">
            <div class="sidebar-brand-icon"><i class="fas fa-book"></i></div>
            <div class="sidebar-brand-text">Perpustakaan</div>
        </a>

        <hr class="sidebar-divider my-0">

        <li class="nav-item {{ Request::routeIs('anggota.dashboard') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('anggota.dashboard') }}" title="Dashboard">
                <i class="fas fa-home"></i><span>Dashboard</span>
            </a>
        </li>

        <li class="nav-item {{ Request::routeIs('anggota.kehadiran') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('anggota.kehadiran') }}" title="Kehadiran">
                <i class="fas fa-calendar-check"></i><span>Kehadiran</span>
            </a>
        </li>

        <li class="nav-item {{ Request::routeIs('anggota.buku') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('anggota.buku') }}" title="Daftar Buku">
                <i class="fas fa-book"></i><span>Daftar Buku</span>
            </a>
        </li>

        <li class="nav-item {{ Request::routeIs('anggota.daftarpinjam') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('anggota.daftarpinjam') }}" title="Daftar Peminjaman">
                <i class="fas fa-list"></i><span>Daftar Peminjaman</span>
            </a>
        </li>

        <li class="nav-item {{ Request::routeIs('anggota.riwayatpinjam') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('anggota.riwayatpinjam') }}" title="Riwayat Peminjaman">
                <i class="fas fa-history"></i><span>Riwayat Peminjaman</span>
            </a>
        </li>

        <hr class="sidebar-divider">
    </ul>
    <!-- END SIDEBAR -->

    <!-- ================================================
         CONTENT WRAPPER
    ================================================ -->
    <div id="content-wrapper">
        <div id="content">

            <!-- TOPBAR -->
            <nav class="topbar">
                {{-- Mobile toggle --}}
                <button id="sidebarToggleTop" class="btn-hamburger" aria-label="Buka menu">
                    <i class="fas fa-bars"></i>
                </button>

                {{-- Desktop toggle (opsional, tersembunyi secara default) --}}
                <button id="sidebarToggle" class="btn-hamburger" aria-label="Toggle sidebar">
                    <i class="fas fa-bars"></i>
                </button>

                <!-- Profile — style anggota (foto + nama + dropdown) -->
                <div class="topbar-right">
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#"
                               id="userDropdown" data-toggle="dropdown"
                               aria-haspopup="true" aria-expanded="false">
                                <span id="navName" class="mr-2">Loading...</span>
                                <img id="navPhoto" class="img-profile rounded-circle"
                                     src="{{ asset('template/img/undraw_profile.svg') }}" alt="Profil">
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#"
                                   data-toggle="modal" data-target="#modalProfile">
                                    <i class="fas fa-user-edit fa-fw"></i> Edit Profil
                                </a>
                                <a class="dropdown-item" href="javascript:void(0)" onclick="cetakKartu()">
                                    <i class="fas fa-file-pdf fa-fw"></i> Cetak Kartu Anggota
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item text-danger" href="#"
                                   data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-fw"></i> Logout
                                </a>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
            <!-- END TOPBAR -->

            <!-- PAGE CONTENT -->
            <div class="container-fluid">
                @yield('content')
            </div>

        </div>

        <!-- FOOTER -->
        <footer class="sticky-footer">
            <div class="copyright">&copy; Perpustakaan 2026</div>
        </footer>
    </div>

</div>

<!-- ================================================
     MODAL — Edit Profil
================================================ -->
<div class="modal fade" id="modalProfile" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form id="formProfile" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Edit Profil</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <img id="previewPhoto"
                             src="{{ asset('template/img/undraw_profile.svg') }}"
                             class="rounded-circle img-thumbnail"
                             style="width:90px;height:90px;object-fit:cover;">
                    </div>
                    <div class="form-group">
                        <label for="inputName">Nama Lengkap</label>
                        <input type="text" name="name" id="inputName" class="form-control" required>
                    </div>
                    <div class="form-group mb-0">
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

<!-- ================================================
     MODAL — Logout
================================================ -->
<div class="modal fade" id="logoutModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Logout</h5>
                <button class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">Apakah kamu yakin ingin logout?</div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal">Tidak</button>
                <button id="btnLogout" class="btn btn-danger">Logout</button>
            </div>
        </div>
    </div>
</div>

<a class="scroll-to-top" href="#page-top" title="Kembali ke atas">
    <i class="fas fa-angle-up"></i>
</a>

<!-- ================================================
     SCRIPTS
================================================ -->
<script src="{{ asset('template/vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('template/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('template/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
<script src="{{ asset('template/js/sb-admin-2.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
/**
 * SIDEBAR CONTROLLER — sama persis dengan admin.blade.php
 * Clone node trick: membunuh semua listener sb-admin-2, pasang listener bersih.
 */
document.addEventListener('DOMContentLoaded', function () {

    var MOBILE_BP = 768;
    var sidebar   = document.getElementById('accordionSidebar');
    var overlay   = document.getElementById('sidebarOverlay');
    var isOpen    = false;

    function freshClone(id) {
        var el = document.getElementById(id);
        if (!el) return null;
        var clone = el.cloneNode(true);
        el.parentNode.replaceChild(clone, el);
        return clone;
    }

    var btnMobile  = freshClone('sidebarToggleTop');
    var btnDesktop = freshClone('sidebarToggle');

    if (!sidebar) return;

    function openSidebar() {
        isOpen = true;
        sidebar.classList.add('mobile-open');
        overlay.classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function closeSidebar() {
        isOpen = false;
        sidebar.classList.remove('mobile-open');
        overlay.classList.remove('show');
        document.body.style.overflow = '';
    }

    function toggleDesktop() {
        sidebar.classList.toggle('toggled');
    }

    if (btnMobile) {
        btnMobile.addEventListener('click', function (e) {
            e.stopPropagation();
            isOpen ? closeSidebar() : openSidebar();
        });
    }

    if (btnDesktop) {
        btnDesktop.addEventListener('click', function (e) {
            e.stopPropagation();
            toggleDesktop();
        });
    }

    if (overlay) {
        overlay.addEventListener('click', closeSidebar);
    }

    sidebar.querySelectorAll('.nav-link').forEach(function (link) {
        link.addEventListener('click', function () {
            if (window.innerWidth < MOBILE_BP) closeSidebar();
        });
    });

    window.addEventListener('resize', function () {
        if (window.innerWidth >= MOBILE_BP && isOpen) closeSidebar();
    });

});
</script>

<script>
/* Auth guard */
if (!localStorage.getItem('token')) {
    window.location.href = '/';
}

/* Logout */
document.getElementById('btnLogout').addEventListener('click', function () {
    fetch('http://127.0.0.1:8000/api/logout', {
        method: 'POST',
        headers: {
            'Authorization': 'Bearer ' + localStorage.getItem('token'),
            'Accept': 'application/json'
        }
    }).finally(function () {
        localStorage.removeItem('token');
        window.location.href = '/';
    });
});

/* Search filter (jika ada di halaman) */
var searchInput = document.getElementById('searchInput');
if (searchInput) {
    searchInput.addEventListener('input', function () {
        var keyword = this.value.toLowerCase();
        var hasilFilter = semuaTransaksi.map(function (trx) {
            return Object.assign({}, trx, {
                details: trx.details.filter(function (d) {
                    return d.judul_buku.toLowerCase().includes(keyword);
                })
            });
        }).filter(function (trx) { return trx.details.length > 0; });
        renderTabel(hasilFilter);
    });
}
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    var Toast = Swal.mixin({
        toast: true, position: 'center',
        showConfirmButton: false,
        timer: 3000, timerProgressBar: true
    });

    /* Load user info */
    async function loadUser() {
        var token = localStorage.getItem('token');
        if (!token) return;
        try {
            var res = await fetch('/api/me', { headers: { Authorization: 'Bearer ' + token } });
            if (!res.ok) return;
            var d = await res.json();
            document.getElementById('navName').textContent = d.name;
            document.getElementById('navPhoto').src = d.photo + '?t=' + Date.now();
            document.getElementById('inputName').value = d.name;
            document.getElementById('previewPhoto').src = d.photo;
        } catch(e) {}
    }
    loadUser();

    /* Preview foto */
    var inputPhoto = document.getElementById('inputPhoto');
    if (inputPhoto) {
        inputPhoto.addEventListener('change', function (e) {
            var file = e.target.files[0];
            if (!file) return;
            if (file.size > 2 * 1024 * 1024) {
                Toast.fire({ icon: 'error', title: 'Ukuran file terlalu besar! Maksimal 2MB.' });
                inputPhoto.value = ''; return;
            }
            if (!['image/jpeg','image/jpg','image/png'].includes(file.type)) {
                Toast.fire({ icon: 'error', title: 'Format tidak didukung! Gunakan JPG atau PNG.' });
                inputPhoto.value = ''; return;
            }
            document.getElementById('previewPhoto').src = URL.createObjectURL(file);
            var lbl = document.querySelector('.custom-file-label');
            if (lbl) lbl.textContent = file.name;
        });
    }

    /* Submit profil */
    var formProfile = document.getElementById('formProfile');
    if (formProfile) {
        formProfile.addEventListener('submit', async function (e) {
            e.preventDefault();
            try {
                var res = await fetch('/api/update-profile', {
                    method: 'POST',
                    headers: {
                        'Authorization': 'Bearer ' + localStorage.getItem('token'),
                        'Accept': 'application/json'
                    },
                    body: new FormData(e.target)
                });
                var d = await res.json();
                if (res.ok) {
                    Toast.fire({ icon: 'success', title: d.message || 'Profil berhasil diperbarui' });
                    $('#modalProfile').modal('hide');
                    loadUser();
                } else {
                    Toast.fire({ icon: 'error', title: d.message || 'Gagal memperbarui profil' });
                }
            } catch(err) {
                Toast.fire({ icon: 'error', title: 'Terjadi kesalahan. Coba lagi.' });
            }
        });
    }

});
</script>

<script>
/* Cetak Kartu Anggota */
async function cetakKartu() {
    var token = localStorage.getItem('token');

    Swal.fire({
        title: 'Memproses Kartu...',
        text: 'Mohon tunggu sebentar.',
        allowOutsideClick: false,
        showConfirmButton: false,
        didOpen: function () { Swal.showLoading(); }
    });

    try {
        var res = await fetch('http://127.0.0.1:8000/api/user/print-my-card', {
            headers: {
                'Authorization': 'Bearer ' + token,
                'Accept': 'application/pdf'
            }
        });

        if (!res.ok) throw new Error('Gagal generate kartu. Silakan coba lagi nanti.');

        var blob = await res.blob();
        
        // PENTING: Gunakan type yang spesifik
        var pdfBlob = new Blob([blob], { type: 'application/pdf' });
        var url = window.URL.createObjectURL(pdfBlob);

        // Tutup loading SEBELUM membuka window agar tidak terjadi tabrakan fokus
        Swal.close();

        // Gunakan bantuan link sementara untuk memicu pembukaan tab
        const win = window.open(url, '_blank');
        if (!win) {
            throw new Error('Pop-up diblokir! Harap izinkan pop-up untuk situs ini.');
        }

        // Opsional: Bersihkan memori setelah beberapa saat
        setTimeout(() => window.URL.revokeObjectURL(url), 10000);

        Swal.fire({
            toast: true,
            position: 'center',
            icon: 'success',
            title: 'Kartu berhasil dibuat',
            showConfirmButton: false,
            timer: 3000
        });

    } catch (err) {
        Swal.fire({ 
            icon: 'error', 
            title: 'Oops...', 
            text: err.message, 
            confirmButtonColor: '#3085d6' 
        });
    }
}
</script>

@yield('scripts')

</body>
</html>
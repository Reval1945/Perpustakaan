<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perpustakaan - SMKN 4 Bojonegoro</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Montserrat:wght@800;900&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: #2C5AA0;
            --primary-light: #4A7BC8;
            --secondary: #F9A826;
            --accent: #E63946;
            --light: #F8F9FA;
            --dark: #212529;
            --gray: #6C757D;
            --gray-light: #E9ECEF;
            --shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            --radius: 12px;
            --transition: all 0.3s ease;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            line-height: 1.6;
            color: var(--dark);
            overflow-x: hidden;
        }
        
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Montserrat', sans-serif;
            font-weight: 800;
            line-height: 1.2;
        }
        
        .section-title {
            text-align: center;
            margin-bottom: 3rem;
        }
        
        .section-title h2 {
            color: var(--primary);
            position: relative;
            display: inline-block;
            padding-bottom: 15px;
        }
        
        .section-title h2::after {
            content: '';
            position: absolute;
            width: 70px;
            height: 4px;
            background-color: var(--secondary);
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            border-radius: 2px;
        }
        
        .section-title p {
            color: var(--gray);
            max-width: 600px;
            margin: 0 auto;
        }
        
        /* Navbar Customization */
        .navbar {
            background-color: white;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
            padding: 15px 0;
            transition: var(--transition);
        }
        
        .navbar-brand {
            display: flex;
            align-items: center;
            font-weight: 800;
            color: var(--primary);
            font-size: 1.5rem;
        }
        
        .logo-icon {
            background-color: var(--primary);
            color: white;
            width: 45px;
            height: 45px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            margin-right: 12px;
        }
        
        .navbar-brand span {
            color: var(--secondary);
        }
        
        .nav-link {
            font-weight: 500;
            color: var(--dark) !important;
            margin: 0 8px;
            transition: var(--transition);
            position: relative;
        }
        
        .nav-link:hover {
            color: var(--primary) !important;
        }
        
        .nav-link.active {
            color: var(--primary) !important;
            font-weight: 600;
        }
        
        .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 15%;
            width: 70%;
            height: 3px;
            background-color: var(--secondary);
            border-radius: 3px;
            transition: var(--transition);
        }
        
        .btn-custom {
            padding: 10px 25px;
            border-radius: 50px;
            font-weight: 600;
            transition: var(--transition);
            border: none;
        }
        
        .btn-primary-custom {
            background-color: var(--primary);
            color: white;
        }
        
        .btn-primary-custom:hover {
            background-color: var(--primary-light);
            color: white;
            transform: translateY(-3px);
            box-shadow: var(--shadow);
        }
        
        .btn-secondary-custom {
            background-color: var(--secondary);
            color: white;
        }
        
        .btn-secondary-custom:hover {
            background-color: #e69900;
            color: white;
            transform: translateY(-3px);
            box-shadow: var(--shadow);
        }
        
        .btn-outline-custom {
            background-color: transparent;
            color: var(--primary);
            border: 2px solid var(--primary);
        }
        
        .btn-outline-custom:hover {
            background-color: var(--primary);
            color: white;
            transform: translateY(-3px);
        }
        
        /* Hero Section */
        .hero-section {
            background-color: #ffff;
            padding-top: 120px;
            padding-bottom: 80px;
            overflow: hidden;
        }
        
        .hero-content h1 {
            color: var(--primary);
            font-size: 3.2rem;
            margin-bottom: 20px;
        }
        
        .hero-content h1 span {
            color: var(--secondary);
        }
        
        .hero-content p {
            font-size: 1.2rem;
            color: var(--gray);
            margin-bottom: 30px;
        }
        
        .hero-stats {
            display: flex;
            gap: 40px;
            margin-top: 40px;
        }
        
        .stat-item h3 {
            font-size: 2.2rem;
            color: var(--primary);
            margin-bottom: 5px;
        }
        
        .stat-item p {
            font-size: 1rem;
            color: var(--gray);
            margin: 0;
        }
        
        .hero-image img {
            transition: var(--transition);
        }
        
        /* Features Section */
        .feature-card {
            background-color: white;
            border-radius: var(--radius);
            padding: 40px 30px;
            text-align: center;
            box-shadow: var(--shadow);
            transition: var(--transition);
            height: 100%;
            border-top: 5px solid transparent;
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
            border-top-color: var(--primary);
        }
        
        .feature-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            color: white;
            font-size: 32px;
        }
        
        .feature-card h3 {
            color: var(--primary);
            margin-bottom: 15px;
        }
        
        /* Collections Section */
        .collections-section {
            background-color: var(--light);
        }
        
        .collection-card {
            background-color: white;
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: var(--shadow);
            transition: var(--transition);
            height: 100%;
        }
        
        .collection-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
        }
        
        .collection-img {
            height: 200px;
            background-color: var(--gray-light);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            font-size: 60px;
        }
        
        .collection-content {
            padding: 25px;
        }
        
        .collection-content h3 {
            color: var(--primary);
            margin-bottom: 10px;
        }
        
        .collection-count {
            display: flex;
            align-items: center;
            justify-content: space-between;
            color: var(--secondary);
            font-weight: 600;
        }
        
        /* Steps Section */
        .step-card {
            text-align: center;
            position: relative;
            padding: 20px;
        }
        
        .step-number {
            width: 80px;
            height: 80px;
            background-color: var(--primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2rem;
            font-weight: 700;
            margin: 0 auto 25px;
            border: 8px solid white;
            box-shadow: var(--shadow);
        }
        
        /* About Section */
        .about-section {
            padding: 100px 0;
            background-color: white;
        }
        
        .about-content h2 {
            color: var(--primary);
            margin-bottom: 20px;
        }
        
        .about-features {
            margin-top: 30px;
        }
        
        .about-feature-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 20px;
        }
        
        .about-feature-icon {
            background-color: var(--primary-light);
            color: white;
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-right: 15px;
            flex-shrink: 0;
        }
        
        .about-stats {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
            margin-top: 40px;
        }
        
        .about-stat {
            text-align: center;
            flex: 1;
            min-width: 150px;
        }
        
        .about-stat-number {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--primary);
            line-height: 1;
        }
        
        .about-stat-label {
            color: var(--gray);
            font-size: 1rem;
            margin-top: 5px;
        }
        
        .about-image {
            position: relative;
        }
        
        .about-badge {
            position: absolute;
            top: -20px;
            right: -20px;
            background-color: var(--secondary);
            color: white;
            padding: 15px 20px;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            font-weight: 600;
            z-index: 2;
        }
        
        /* CTA Section */
        .cta-section {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            color: white;
            padding: 100px 0;
        }
        
        .cta-section h2 {
            color: white;
            margin-bottom: 20px;
        }
        
        .cta-section p {
            font-size: 1.2rem;
            max-width: 700px;
            margin: 0 auto 40px;
            opacity: 0.9;
        }
        
        /* Footer */
        footer {
            background-color: var(--dark);
            color: white;
            padding: 70px 0 30px;
        }
        
        .footer-col h3 {
            color: white;
            margin-bottom: 25px;
            position: relative;
            padding-bottom: 10px;
        }
        
        .footer-col h3::after {
            content: '';
            position: absolute;
            width: 50px;
            height: 3px;
            background-color: var(--secondary);
            bottom: 0;
            left: 0;
        }
        
        .footer-links {
            list-style: none;
            padding: 0;
        }
        
        .footer-links li {
            margin-bottom: 12px;
        }
        
        .footer-links a {
            color: #aaa;
            text-decoration: none;
            transition: var(--transition);
        }
        
        .footer-links a:hover {
            color: var(--secondary);
            padding-left: 5px;
        }
        
        .social-icons a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            color: white;
            text-decoration: none;
            transition: var(--transition);
            margin-right: 10px;
        }
        
        .social-icons a:hover {
            background-color: var(--secondary);
            transform: translateY(-3px);
        }
        
        .copyright {
            text-align: center;
            padding-top: 30px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            color: #aaa;
            font-size: 0.9rem;
        }
        
        /* Animation Classes */
        .fade-in {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.6s ease, transform 0.6s ease;
        }
        
        .fade-in.visible {
            opacity: 1;
            transform: translateY(0);
        }
        
        /* Responsive Adjustments */
        @media (max-width: 992px) {
            .hero-content h1 {
                font-size: 2.5rem;
            }
            
            .hero-stats {
                flex-direction: column;
                gap: 25px;
            }
            
            .nav-link.active::after {
                left: 0;
                width: 100%;
            }
            
            .about-section {
                padding: 70px 0;
            }
        }
        
        @media (max-width: 768px) {
            .hero-section {
                padding-top: 100px;
                text-align: center;
            }
            
            .hero-content h1 {
                font-size: 2.2rem;
            }
            
            .section-title h2 {
                font-size: 1.8rem;
            }
            
            .cta-section {
                padding: 70px 0;
            }
            
            .nav-link {
                margin: 5px 0;
            }
            
            .nav-link.active::after {
                display: none;
            }
            
            .about-badge {
                position: relative;
                top: 0;
                right: 0;
                margin-bottom: 20px;
                text-align: center;
            }
            
            .about-stats {
                justify-content: center;
            }
            
            .about-stat {
                min-width: 120px;
            }
        }
    </style>
</head>
<body data-bs-spy="scroll" data-bs-target="#navbar" data-bs-offset="100">
    <!-- Navigation -->
    <nav id="navbar" class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#home">
                <div class="logo-icon">
                    <i class="bi bi-book"></i>
                </div> 
                Perpustakaan <span> SMKN 4</span>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" href="#home">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#features">Fitur</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#collections">Koleksi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#how-it-works">Cara Kerja</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">Tentang</a>
                    </li>
                </ul>
                
                <div class="d-flex ms-lg-3">
                    <a href="/login" class="btn btn-outline-custom btn-custom me-2">Masuk</a>
                    <a href="/register" class="btn btn-primary-custom btn-custom">Daftar</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 fade-in">
                    <div class="hero-content">
                        <h1>Jelajahi Dunia <span>Pengetahuan</span> di Perpustakaan Kami</h1>
                        <p class="lead">Akses buku, dan materi pembelajaran dari mana saja. Sistem perpustakaan modern untuk siswa dan guru SMKN 4 Bojonegoro.</p>
                        
                        <div class="d-flex flex-wrap gap-3 mb-4">
                            <a href="#collections" class="btn btn-primary-custom btn-custom">Jelajahi Koleksi</a>
                            <a href="#about" class="btn btn-outline-custom btn-custom">Tentang Kami</a>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-6 fade-in">
                    <div class="hero-image mt-5 mt-lg-0">
                        <img src="{{ asset('template/img/Hero Perpus.png') }}" 
                             alt="Perpustakaan Digital" class="img-fluid">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-5">
        <div class="container">
            <div class="section-title fade-in">
                <h2>Fitur Unggulan</h2>
                <p>Nikmati pengalaman perpustakaan yang modern dengan fitur-fitur terbaik</p>
            </div>
            
            <div class="row g-4">
                <div class="col-md-4 fade-in">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-search"></i>
                        </div>
                        <h3>Pencarian Cerdas</h3>
                        <p>Temukan buku dengan mudah menggunakan sistem pencarian yang canggih berdasarkan judul, penulis, kategori, atau kata kunci.</p>
                    </div>
                </div>
                
                <div class="col-md-4 fade-in">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-search"></i>
                        </div>
                        <h3>Pencarian Buku</h3>
                        <p>Cari buku berdasarkan judul, penulis, atau kategori dengan sistem pencarian yang cepat dan akurat.</p>
                    </div>
                </div>

                <div class="col-md-4 fade-in">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-calendar-check"></i>
                        </div>
                        <h3>Peminjaman Online</h3>
                        <p>Pinjam buku fisik secara online dan ambil di perpustakaan. Dapatkan notifikasi pengingat via email dan WhatsApp.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Collections Section -->
    <section id="collections" class="collections-section py-5">
        <div class="container">
            <div class="section-title fade-in">
                <h2>Koleksi Kami</h2>
                <p>Jelajahi berbagai kategori buku yang tersedia di perpustakaan digital kami</p>
            </div>
            
            <div class="row g-4">
                <div class="col-md-6 col-lg-3 fade-in">
                    <div class="collection-card">
                        <div class="collection-img">
                            <i class="bi bi-cpu"></i>
                        </div>
                        <div class="collection-content">
                            <h3>Teknologi & Komputer</h3>
                            <p class="text-muted">Buku-buku terbaru tentang pemrograman, jaringan, desain grafis, dan teknologi informasi.</p>
                            <div class="collection-count">
                                <span>1,250 buku</span>
                                <i class="bi bi-arrow-right"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-3 fade-in">
                    <div class="collection-card">
                        <div class="collection-img">
                            <i class="bi bi-briefcase"></i>
                        </div>
                        <div class="collection-content">
                            <h3>Bisnis & Ekonomi</h3>
                            <p class="text-muted">Koleksi buku manajemen, kewirausahaan, akuntansi, dan ekonomi untuk pengembangan skill bisnis.</p>
                            <div class="collection-count">
                                <span>890 buku</span>
                                <i class="bi bi-arrow-right"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-3 fade-in">
                    <div class="collection-card">
                        <div class="collection-img">
                            <i class="bi bi-book"></i>
                        </div>
                        <div class="collection-content">
                            <h3>Sastra & Bahasa</h3>
                            <p class="text-muted">Kumpulan karya sastra, novel, puisi, dan buku pembelajaran bahasa Indonesia serta asing.</p>
                            <div class="collection-count">
                                <span>1,540 buku</span>
                                <i class="bi bi-arrow-right"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-3 fade-in">
                    <div class="collection-card">
                        <div class="collection-img">
                            <i class="bi bi-flask"></i>
                        </div>
                        <div class="collection-content">
                            <h3>Sains & Matematika</h3>
                            <p class="text-muted">Buku referensi ilmiah, matematika, fisika, kimia, biologi, dan penelitian terbaru.</p>
                            <div class="collection-count">
                                <span>1,120 buku</span>
                                <i class="bi bi-arrow-right"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section id="how-it-works" class="py-5">
        <div class="container">
            <div class="section-title fade-in">
                <h2>Cara Bergabung</h2>
                <p>Ikuti 3 langkah mudah untuk menjadi anggota perpustakaan digital kami</p>
            </div>
            
            <div class="row g-4">
                <div class="col-md-4 fade-in">
                    <div class="step-card">
                        <div class="step-number">1</div>
                        <h3>Daftar Akun</h3>
                        <p class="text-muted">
                            Buat akun baru dengan mengisi formulir pendaftaran sesuai data diri.
                        </p>
                    </div>
                </div>
                
                <div class="col-md-4 fade-in">
                    <div class="step-card">
                        <div class="step-number">2</div>
                        <h3>Login Akun</h3>
                        <p class="text-muted">
                            Masuk ke sistem menggunakan email dan password yang telah didaftarkan.
                        </p>
                    </div>
                </div>
                
                <div class="col-md-4 fade-in">
                    <div class="step-card">
                        <div class="step-number">3</div>
                        <h3>Mulai Jelajahi</h3>
                        <p class="text-muted">
                            Jelajahi koleksi buku, lakukan peminjaman, dan nikmati layanan perpustakaan digital.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="about-section">
        <div class="container">
            <div class="section-title fade-in">
                <h2>Tentang Kami</h2>
                <p>Mengenal lebih dekat Perpustakaan SMKN 4 Bojonegoro</p>
            </div>
            
            <div class="row align-items-center">
                <div class="col-lg-6 fade-in">
                    <div class="about-content">
                        <h2>Visi & Misi Perpustakaan</h2>
                        <p class="lead">Menjadi pusat sumber belajar digital yang modern, inovatif, dan terdepan dalam mendukung pendidikan di SMKN 4 Bojonegoro.</p>
                        
                        <p>Perpustakaan Digital SMKN 4 Bojonegoro didirikan dengan tujuan untuk menyediakan akses mudah dan cepat terhadap berbagai sumber belajar bagi siswa, guru, dan seluruh civitas akademika. Kami berkomitmen untuk terus mengembangkan koleksi dan layanan sesuai dengan perkembangan teknologi.</p>
                        
                        <div class="about-features">
                            <div class="about-feature-item">
                                <div class="about-feature-icon">
                                    <i class="bi bi-lightbulb"></i>
                                </div>
                                <div>
                                    <h5>Inovasi Pendidikan</h5>
                                    <p class="text-muted mb-0">Terus berinovasi dalam menyediakan layanan perpustakaan yang sesuai dengan kebutuhan pendidikan modern.</p>
                                </div>
                            </div>
                            
                            <div class="about-feature-item">
                                <div class="about-feature-icon">
                                    <i class="bi bi-people"></i>
                                </div>
                                <div>
                                    <h5>Akses untuk Semua</h5>
                                    <p class="text-muted mb-0">Menyediakan akses yang setara bagi seluruh siswa dan guru tanpa terkecuali.</p>
                                </div>
                            </div>
                            
                            <div class="about-feature-item">
                                <div class="about-feature-icon">
                                    <i class="bi bi-award"></i>
                                </div>
                                <div>
                                    <h5>Kualitas Terjamin</h5>
                                    <p class="text-muted mb-0">Memastikan kualitas koleksi dan layanan yang sesuai dengan standar pendidikan nasional.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-6 fade-in">
                    <div class="about-image mt-5 mt-lg-0 position-relative">
                        <div class="about-badge">
                            <i class="bi bi-star-fill me-2"></i>
                            Perpustakaan Digital Terbaik 2024
                        </div>
                        <img src="https://images.unsplash.com/photo-1589998059171-988d887df646?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80" 
                             alt="Perpustakaan SMKN 4" class="img-fluid rounded shadow">
                        
                        <div class="about-stats">
                            <div class="about-stat">
                                <div class="about-stat-number">10+</div>
                                <div class="about-stat-label">Tahun Pengalaman</div>
                            </div>
                            <div class="about-stat">
                                <div class="about-stat-number">5,000+</div>
                                <div class="about-stat-label">Koleksi Buku</div>
                            </div>
                            <div class="about-stat">
                                <div class="about-stat-number">2,500+</div>
                                <div class="about-stat-label">Anggota Aktif</div>
                            </div>
                            <div class="about-stat">
                                <div class="about-stat-number">98%</div>
                                <div class="about-stat-label">Kepuasan Pengguna</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section id="cta" class="cta-section">
        <div class="container">
            <div class="row justify-content-center text-center fade-in">
                <div class="col-lg-8">
                    <h2>Siap Bergabung dengan Perpustakaan Kami?</h2>
                    <p>Daftar sekarang dan dapatkan akses ke seluruh koleksi buku, jurnal ilmiah, dan materi pembelajaran lainnya secara gratis untuk siswa dan guru SMKN 4 Bojonegoro.</p>
                    
                    <div class="d-flex flex-wrap justify-content-center gap-3">
                        <a href="/pilihrole" class="btn btn-warning btn-custom btn-custom">Daftar Sekarang</a>
                        <a href="#about" class="btn btn-outline-custom btn-custom" style="color: white; border-color: white;">Pelajari Lebih Lanjut</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row g-4 mb-5">
                <div class="col-lg-4 fade-in">
                    <div class="footer-col">
                        <h3>Perpustakaan SMKN 4</h3>
                        <p class="text-white-50">Sistem perpustakaan modern untuk mendukung proses belajar mengajar di SMK Negeri 4 Bojonegoro.</p>
                    </div>
                </div>
                
                <div class="col-lg-2 col-md-6 fade-in">
                    <div class="footer-col">
                        <h3>Link Cepat</h3>
                        <ul class="footer-links">
                            <li><a href="#home">Beranda</a></li>
                            <li><a href="#features">Fitur</a></li>
                            <li><a href="#collections">Koleksi</a></li>
                            <li><a href="#how-it-works">Cara Kerja</a></li>
                            <li><a href="#about">Tentang Kami</a></li>
                        </ul>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6 fade-in">
                    <div class="footer-col">
                        <h3>Kontak Kami</h3>
                        <ul class="footer-links">
                            <li><i class="bi bi-geo-alt me-2"></i>Jalan Raya Surabaya, Sukowati, Kec. Kapas, Kabupaten Bojonegoro, Jawa Timur</li>
                            <li><i class="bi bi-telephone me-2"></i> (0353) 123456</li>
                            <li><i class="bi bi-envelope me-2"></i> perpustakaan@smkn4bojonegoro.sch.id</li>
                            <li><i class="bi bi-clock me-2"></i> Senin-Jumat: 07.30 - 15.00 WIB</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="copyright fade-in">
                <p>&copy; 2025 Perpustakaan SMKN 4 Bojonegoro.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Scroll Spy dengan optimasi
        document.addEventListener('DOMContentLoaded', function() {
            // Inisialisasi Bootstrap ScrollSpy
            const scrollSpy = new bootstrap.ScrollSpy(document.body, {
                target: '#navbar',
                offset: 100,
                smoothScroll: true
            });
            
            // Fungsi untuk update active nav link
            function updateActiveNavLink() {
                const sections = document.querySelectorAll('section[id], footer[id]');
                const navLinks = document.querySelectorAll('#navbar .nav-link');
                
                let currentSection = '';
                const scrollPosition = window.scrollY + 100;
                
                // Cari section yang sedang aktif
                sections.forEach(section => {
                    const sectionTop = section.offsetTop;
                    const sectionHeight = section.clientHeight;
                    
                    if (scrollPosition >= sectionTop && scrollPosition < sectionTop + sectionHeight) {
                        currentSection = section.id;
                    }
                });
                
                // Jika di bagian paling atas, set home sebagai aktif
                if (scrollPosition < 150) {
                    currentSection = 'home';
                }
                
                // Update class active pada nav links
                navLinks.forEach(link => {
                    link.classList.remove('active');
                    const href = link.getAttribute('href');
                    
                    if (href === `#${currentSection}`) {
                        link.classList.add('active');
                    }
                });
                
                // Jika tidak ada yang aktif, set home sebagai aktif
                if (!currentSection && navLinks.length > 0) {
                    navLinks[0].classList.add('active');
                }
            }
            
            // Update active nav link saat scroll
            window.addEventListener('scroll', updateActiveNavLink);
            
            // Update active nav link saat halaman dimuat
            window.addEventListener('load', updateActiveNavLink);
            
            // Smooth scroll untuk anchor links di navbar
            document.querySelectorAll('#navbar .nav-link').forEach(link => {
                link.addEventListener('click', function(e) {
                    const targetId = this.getAttribute('href');
                    if (targetId.startsWith('#')) {
                        e.preventDefault();
                        
                        const targetElement = document.querySelector(targetId);
                        if (targetElement) {
                            // Remove active class from all links
                            document.querySelectorAll('#navbar .nav-link').forEach(item => {
                                item.classList.remove('active');
                            });
                            
                            // Add active class to clicked link
                            this.classList.add('active');
                            
                            // Smooth scroll to target
                            window.scrollTo({
                                top: targetElement.offsetTop - 80,
                                behavior: 'smooth'
                            });
                            
                            // Update URL hash
                            history.pushState(null, null, targetId);
                            
                            // Close navbar on mobile after click
                            if (window.innerWidth < 992) {
                                const navbarToggler = document.querySelector('.navbar-toggler');
                                const navbarCollapse = document.querySelector('#navbarNav');
                                if (navbarCollapse && navbarCollapse.classList.contains('show')) {
                                    navbarToggler.click();
                                }
                            }
                        }
                    }
                });
            });
            
            // Update active nav link ketika hash di URL berubah
            window.addEventListener('hashchange', updateActiveNavLink);
            
            // Fade-in animation on scroll
            const fadeElements = document.querySelectorAll('.fade-in');
            
            const fadeInOnScroll = function() {
                fadeElements.forEach(element => {
                    const elementTop = element.getBoundingClientRect().top;
                    const elementVisible = 150;
                    
                    if (elementTop < window.innerHeight - elementVisible) {
                        element.classList.add('visible');
                    }
                });
            };
            
            // Initial check
            fadeInOnScroll();
            
            // Check on scroll
            window.addEventListener('scroll', fadeInOnScroll);
            
            // Navbar background on scroll
            window.addEventListener('scroll', function() {
                const navbar = document.getElementById('navbar');
                if (window.scrollY > 50) {
                    navbar.style.boxShadow = '0 5px 20px rgba(0, 0, 0, 0.1)';
                    navbar.style.padding = '10px 0';
                } else {
                    navbar.style.boxShadow = '0 5px 20px rgba(0, 0, 0, 0.05)';
                    navbar.style.padding = '15px 0';
                }
            });
            
            // Handle browser back/forward button
            window.addEventListener('popstate', function() {
                setTimeout(updateActiveNavLink, 100);
            });
            
            // Update active nav link ketika section terlihat
            const observerOptions = {
                root: null,
                rootMargin: '0px',
                threshold: 0.3
            };
            
            const observerCallback = function(entries) {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const sectionId = entry.target.id;
                        document.querySelectorAll('#navbar .nav-link').forEach(link => {
                            link.classList.remove('active');
                            if (link.getAttribute('href') === `#${sectionId}`) {
                                link.classList.add('active');
                            }
                        });
                    }
                });
            };
            
            // Buat observer
            const observer = new IntersectionObserver(observerCallback, observerOptions);
            
            // Observasi semua section
            document.querySelectorAll('section[id], footer[id]').forEach(section => {
                observer.observe(section);
            });
        });
        
        // Fallback untuk browser yang tidak support IntersectionObserver
        if (!('IntersectionObserver' in window)) {
            console.log('IntersectionObserver tidak didukung, menggunakan fallback scroll spy');
            
            window.addEventListener('scroll', function() {
                const sections = document.querySelectorAll('section[id], footer[id]');
                const navLinks = document.querySelectorAll('#navbar .nav-link');
                
                let current = '';
                const scrollPos = window.scrollY + 100;
                
                sections.forEach(section => {
                    const sectionTop = section.offsetTop;
                    const sectionHeight = section.clientHeight;
                    
                    if (scrollPos >= sectionTop && scrollPos < sectionTop + sectionHeight) {
                        current = section.id;
                    }
                });
                
                navLinks.forEach(link => {
                    link.classList.remove('active');
                    if (link.getAttribute('href') === `#${current}`) {
                        link.classList.add('active');
                    }
                });
                
                // Highlight home if at top
                if (scrollPos < 150) {
                    navLinks.forEach(link => link.classList.remove('active'));
                    const homeLink = document.querySelector('#navbar a[href="#home"]');
                    if (homeLink) homeLink.classList.add('active');
                }
            });
        }
    </script>
</body>
</html>
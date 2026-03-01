<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        /* Ukuran standar ID-1: 85.6mm x 54mm */
        @page { 
            margin: 0; 
            size: 242.65pt 153.07pt; 
        }
        html, body {
            margin: 0;
            padding: 0;
            width: 242.65pt;
            height: 153.07pt;
            overflow: hidden;
            font-family: 'Helvetica', Arial, sans-serif;
            background-color: #ffffff;
        }
        .card {
            width: 242.65pt;
            height: 153.07pt;
            position: relative;
            background: white;
            overflow: hidden;
        }
        
        /* Background Gedung Sekolah (Ganti URL dengan gambar asli) */
        .bg-image {
            position: absolute;
            top: 40pt; /* Mulai dari bawah header */
            left: 0;
            width: 100%;
            height: 113pt;
            background-image: url("file://{{ public_path('template/img/bg-smk.png') }}");
            background-size: cover;
            background-position: center;
            opacity: 0.25; /* Transparansi agar teks terbaca */
            z-index: 0;
        }

        /* Konten Utama diletakkan di atas background */
        .main-content {
            position: relative;
            z-index: 1;
            width: 100%;
            height: 100%;
        }

        /* Header Biru */
        .header {
            background-color: #3b5a9a; /* Warna biru sesuai gambar */
            color: white;
            height: 40pt;
            width: 100%;
            position: absolute;
            top: 0;
            left: 0;
            text-align: center;
        }
        
        .logo-kiri {
            position: absolute;
            top: 4pt;
            left: 5pt;
            height: 32pt;
            width: auto;
        }

        .logo-kanan {
            position: absolute;
            top: 4pt;
            right: 5pt;
            height: 20pt;
            width: auto;
        }

        .kop-teks {
            margin-left: 35pt;
            margin-right: 35pt;
            padding-top: 4pt;
            line-height: 1.1;
        }

        .kop-1 { font-size: 4.5pt; font-weight: normal; }
        .kop-2 { font-size: 6pt; font-weight: bold; margin-top: 1pt; margin-bottom: 1pt; }
        .kop-3 { font-size: 3.5pt; font-weight: normal; font-style: italic; }

        /* Judul Kartu */
        .title-section {
            position: absolute;
            top: 48pt;
            width: 100%;
            text-align: center;
        }
        .title-text {
            font-size: 9pt;
            font-weight: 900;
            color: #000000;
            letter-spacing: 0.5pt;
            margin-bottom: 2pt;
        }
        .line-thick {
            border-top: 1.5pt solid #000;
            margin: 0 40pt;
        }
        .line-thin {
            border-top: 0.5pt solid #000;
            margin: 1pt 40pt 0 40pt;
        }

        /* Area Data (Foto & Biodata) */
        .data-section {
            position: absolute;
            top: 70pt;
            left: 15pt;
            width: 100%;
        }

        /* Kotak Foto */
        .photo-box {
            position: absolute;
            top: 0;
            left: 0;
            width: 45pt;
            height: 60pt;
            background-color: #e2e2e2;
            border: 0.5pt solid #999;
            text-align: center;
        }
        
        .photo-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .photo-text {
            font-size: 6pt;
            font-weight: bold;
            color: #000;
            margin-top: 26pt; /* Vertikal center manual untuk PDF */
        }

        /* Tabel Info */
        .info-table {
            position: absolute;
            top: 0;
            left: 55pt;
            border-collapse: collapse;
        }
        
        .info-table td {
            font-size: 8pt;
            font-weight: bold;
            color: #000000;
            padding: 3.5pt 0; /* Jarak antar baris */
            vertical-align: middle;
        }
        
        .td-label { width: 35pt; }
        .td-colon { width: 10pt; text-align: center; }
        .td-value { width: 120pt; }

    </style>
</head>
<body>
    <div class="card">
        <div class="bg-image"></div>
        
        <div class="main-content">
            <div class="header">
                <img src="{{ public_path('template/img/smk.png') }}" class="logo-kiri" alt="Logo Jatim">
                <img src="{{ public_path('template/img/logo.png') }}" class="logo-kanan" alt="Logo SMKN">
                
                <div class="kop-teks">
                    <div class="kop-1">PEMERINTAH PROVINSI JAWA TIMUR<br>DINAS PENDIDIKAN</div>
                    <div class="kop-2">SEKOLAH MENENGAH KEJURUAN NEGERI 4 BOJONEGORO</div>
                    <div class="kop-3">
                        Jl. Raya Surabaya - Bojonegoro, Desa Sukowati, Kecamatan Kapas, Kabupaten Bojonegoro, Jawa Timur<br>
                        Web: www.smkn4bojonegoro.sch.id / Email: smkn4bojonegoro@yahoo.co.id
                    </div>
                </div>
            </div>

            <div class="title-section">
                <div class="title-text">KARTU ANGGOTA PERPUSTAKAAN</div>
                <div class="line-thick"></div>
                <div class="line-thin"></div>
            </div>

            <div class="data-section">
                <div class="photo-box">
                    @if(isset($user->photo) && $user->photo)
                        {{-- Pastikan path mengarah ke storage/profile/nama_file --}}
                        <img src="{{ public_path('storage/profile/' . $user->photo) }}" style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        <div class="photo-text">FOTO 2 X 3</div>
                    @endif
                </div>

                <table class="info-table">
                    <tr>
                        <td class="td-label">Nama</td>
                        <td class="td-colon">:</td>
                        <td class="td-value">{{ isset($user->name) ? $user->name : '-' }}</td>
                    </tr>
                    <tr>
                        <td class="td-label">Email</td>
                        <td class="td-colon">:</td>
                        <td class="td-value">{{ isset($user->email) ? $user->email : '-' }}</td>
                    </tr>
                    <tr>
                        <td class="td-label">Kelas</td>
                        <td class="td-colon">:</td>
                        <td class="td-value">{{ isset($user->class) ? $user->class : '-' }}</td>
                    </tr>
                    <tr>
                        <td class="td-label">NISN</td>
                        <td class="td-colon">:</td>
                        <td class="td-value">{{ isset($user->nisn) ? $user->nisn : '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
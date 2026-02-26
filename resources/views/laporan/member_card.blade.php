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
            background-color: #f8f9fa;
        }
        .card {
            width: 242.65pt;
            height: 153.07pt;
            position: relative;
            background: white;
            overflow: hidden;
        }
        
        /* Aksen Dekoratif - Gelombang di background */
        .bg-accent {
            position: absolute;
            bottom: -20pt;
            right: -20pt;
            width: 120pt;
            height: 120pt;
            background: #004aad;
            opacity: 0.05;
            border-radius: 50%;
        }

        /* Header Modern */
        .header {
            background-color: #004aad;
            background: linear-gradient(135deg, #00337c 0%, #004aad 100%);
            color: white;
            height: 42pt;
            width: 100%;
            position: absolute;
            top: 0;
            left: 0;
            border-bottom: 2pt solid #ffcc00; /* Aksen Kuning Emas khas sekolah */
        }
        .header-content {
            padding: 6pt 12pt;
            display: block;
        }
        .logo {
            float: left;
            width: 30pt;
            height: 30pt;
            margin-top: 1pt;
        }
        .title-container {
            margin-left: 38pt;
        }
        .title-main {
            font-size: 10.5pt;
            font-weight: bold;
            margin: 0;
            letter-spacing: 0.5pt;
            text-transform: uppercase;
        }
        .title-sub {
            font-size: 8pt;
            margin: 0;
            font-weight: normal;
            opacity: 0.9;
        }

        /* Konten */
        .content {
            position: absolute;
            top: 50pt;
            left: 12pt;
            right: 12pt;
        }
        
        .photo-container {
            float: left;
            width: 50pt;
            height: 65pt;
            border: 1pt solid #dee2e6;
            padding: 1pt;
            background: white;
            border-radius: 2pt;
        }
        .photo-placeholder {
            width: 100%;
            height: 100%;
            background-color: #e9ecef;
            text-align: center;
        }
        
        .info-container {
            margin-left: 60pt;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        td {
            font-size: 7.5pt;
            padding: 1.8pt 0;
            vertical-align: top;
            color: #333;
            line-height: 1.1;
        }
        .label {
            font-weight: bold;
            color: #666;
            width: 35pt;
            font-size: 6.5pt;
            text-transform: uppercase;
        }
        .value {
            font-weight: bold;
            color: #00337c;
        }

        /* Footer / Barcode Area */
        .footer {
            position: absolute;
            bottom: 8pt;
            left: 12pt;
            right: 12pt;
            border-top: 0.5pt solid #eee;
            padding-top: 4pt;
        }
        .valid-until {
            font-size: 6pt;
            color: #999;
            font-style: italic;
            float: left;
        }
        .signature {
            font-size: 6pt;
            text-align: right;
            color: #444;
            float: right;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="bg-accent"></div>
        
        <div class="header">
            <div class="header-content">
                <img src="{{ public_path('img/logo.png') }}" class="logo">
                <div class="title-container">
                    <p class="title-main">KARTU PERPUSTAKAAN</p>
                    <p class="title-sub">SMKN 4 BOJONEGORO</p>
                </div>
            </div>
        </div>

        <div class="content">
            <div class="photo-container">
                @if($user->photo)
                    <img src="{{ public_path('storage/'.$user->photo) }}" style="width: 100%; height: 100%; object-fit: cover;">
                @else
                    <div class="photo-placeholder">
                        <div style="margin-top: 20pt; font-size: 6pt; color: #adb5bd;">FOTO 2x3</div>
                    </div>
                @endif
            </div>

            <div class="info-container">
                <table>
                    <tr>
                        <td class="label">NAMA</td>
                        <td class="value">: {{ strtoupper($user->name) }}</td>
                    </tr>
                    <tr>
                        <td class="label">NISN</td>
                        <td class="value">: {{ $user->nisn ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label">KELAS</td>
                        <td class="value">: {{ $user->class ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label">EMAIL</td>
                        <td class="value">: {{ strtolower($user->email) }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="footer">
            <div class="valid-until">Berlaku selama menjadi siswa aktif</div>
            <div class="signature">Bojonegoro, {{ date('d/m/Y') }}</div>
        </div>
    </div>
</body>
</html>
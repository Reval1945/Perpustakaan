<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Denda Individu - SMKN 4 Bojonegoro</title>
    <style>
        body { font-family: 'Arial', sans-serif; color: #333; line-height: 1.4; margin: 0; padding: 20px; }
        
        /* Header / Kop Surat */
        .header { text-align: center; position: relative; border-bottom: 3px double #000; padding-bottom: 10px; margin-bottom: 20px; }
        .header img { position: absolute; top: 0; width: 70px; }
        .logo-left { left: 0; }
        .logo-right { right: 0; }
        .header h2, .header h3, .header p { margin: 2px 0; }
        .header h2 { font-size: 18px; }
        .header h3 { font-size: 14px; }
        .header p { font-size: 10px; }

        .title { text-align: center; text-transform: uppercase; font-weight: bold; margin: 20px 0; font-size: 18px; }

        /* Section Styling */
        .section-header { background-color: #f2f2f2; padding: 8px 15px; font-weight: bold; margin-top: 20px; text-transform: uppercase; border-left: 5px solid #3498DB; }
        
        .data-table { width: 100%; margin-top: 10px; border-collapse: collapse; }
        .data-table td { padding: 8px 0; vertical-align: top; font-size: 13px; }
        .data-table td.label { width: 150px; font-weight: bold; }
        .data-table td.separator { width: 20px; text-align: center; }

        /* Catatan Styling */
        .catatan-box { 
            padding: 10px; 
            background-color: #FFF9C4; 
            border-left: 3px solid #FBC02D; 
            font-style: italic; 
            font-weight: bold;
            color: #333;
            margin-top: 5px;
        }

        .footer-note { margin-top: 50px; border-top: 1px solid #ccc; padding-top: 10px; font-size: 11px; font-style: italic; color: #666; }

        /* Print Optimization */
        @media print {
            .page-break { page-break-after: always; }
            body { padding: 0; }
        }
    </style>
</head>
<body>

@foreach ($details as $detail)
<div class="page-break">
    <div class="header">
        <img src="{{ public_path('template/img/smk.png') }}" class="logo-left" alt="Logo Jatim">
        <img src="{{ public_path('template/img/logo.png') }}" class="logo-right" alt="Logo SMK">
        <h3>PEMERINTAH PROVINSI JAWA TIMUR</h3>
        <h3>DINAS PENDIDIKAN</h3>
        <h2>SEKOLAH MENENGAH KEJURUAN NEGERI 4 BOJONEGORO</h2>
        <p>Jl. Raya Surabaya - Bojonegoro, Desa Sukowati, Kecamatan Kapas, Kabupaten Bojonegoro, Jawa Timur</p>
        <p>Web: www.smkn4bojonegoro.sch.id / Email: smkn4bojonegoro@yahoo.co.id</p>
    </div>

    <div class="title">Detail Informasi Denda</div>

    <div class="section-header">Identitas & Data Buku</div>
    <table class="data-table">
        <tr>
            <td class="label">Nama Peminjam</td>
            <td class="separator">:</td>
            <td>{{ $detail->transaction->user->name ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Kelas</td>
            <td class="separator">:</td>
            <td>{{ $detail->transaction->user->class ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Judul Buku</td>
            <td class="separator">:</td>
            <td>{{ $detail->judul_buku }}</td>
        </tr>
        <tr>
            <td class="label">Tanggal Pinjam</td>
            <td class="separator">:</td>
            <td>{{ $detail->transaction->tanggal_pinjam ? \Carbon\Carbon::parse($detail->transaction->tanggal_pinjam)->format('d F Y') : '-' }}</td>
        </tr>
        <tr>
            <td class="label">Tanggal Kembali</td>
            <td class="separator">:</td>
            <td>{{ $detail->tanggal_kembali ? \Carbon\Carbon::parse($detail->tanggal_kembali)->format('d F Y') : '-' }}</td>
        </tr>
    </table>

    <div class="section-header">Rincian Pelanggaran & Denda</div>
    <table class="data-table">
        <tr>
            <td class="label">Jenis Pelanggaran</td>
            <td class="separator">:</td>
            <td>{{ ucfirst(str_replace('_', ' ', $detail->jenis_denda ?? 'Keterlambatan')) }}</td>
        </tr>
        <tr>
            <td class="label">Keterlambatan</td>
            <td class="separator">:</td>
            <td>{{ $detail->jumlah_hari_telat ?? 0 }} Hari</td>
        </tr>
        <tr>
            <td class="label">Jumlah Denda</td>
            <td class="separator">:</td>
            <td style="font-size: 16px; font-weight: bold; color: #E74C3C;">
                Rp {{ number_format($detail->denda, 0, ',', '.') }}
            </td>
        </tr>
        <tr>
            <td class="label">Status Pembayaran</td>
            <td class="separator">:</td>
            <td>
                <span style="padding: 2px 8px; background-color: #eee; border-radius: 4px;">
                    {{ strtoupper(str_replace('_', ' ', $detail->status_denda)) }}
                </span>
            </td>
        </tr>
        {{-- BAGIAN CATATAN BARU DI SINI --}}
        <tr>
            <td class="label">Catatan Petugas</td>
            <td class="separator">:</td>
            <td>
                @if($detail->catatan)
                    <div class="catatan-box">
                        {{ $detail->catatan }}
                    </div>
                @else
                    <span style="color: #999;">-</span>
                @endif
            </td>
        </tr>
    </table>

    <div class="footer-note">
        * Laporan ini merupakan bukti sah transaksi denda perpustakaan.<br>
        * Dicetak pada {{ date('d F Y, H:i') }} WIB oleh Sistem Informasi Perpustakaan SMKN 4 BOJONEGORO.
    </div>
</div>
@endforeach

</body>
</html>
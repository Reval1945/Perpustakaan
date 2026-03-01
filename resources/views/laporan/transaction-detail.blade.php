<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Transaksi - SMKN 4 Bojonegoro</title>
    <style>
        body { font-family: 'Arial', sans-serif; color: #333; line-height: 1.4; margin: 0; padding: 20px; }
        
        /* Header / Kop Surat */
        .header { text-align: center; position: relative; border-bottom: 3px double #000; padding-bottom: 10px; margin-bottom: 20px; }
        .header img { position: absolute; top: 0; width: 70px; }
        .logo-left { left: 0; }
        .logo-right { right: 0; }
        .header h2, .header h3, .header p { margin: 2px 0; }
        .header p { font-size: 11px; }

        .title { text-align: center; text-transform: uppercase; font-weight: bold; margin: 20px 0; font-size: 18px; }

        /* Section Styling */
        .section-header { background-color: #f2f2f2; padding: 8px 15px; font-weight: bold; margin-top: 20px; text-transform: uppercase; }
        
        .data-table { width: 100%; margin-top: 10px; border-collapse: collapse; }
        .data-table td { padding: 8px 0; vertical-align: top; }
        .data-table td.label { width: 150px; font-weight: bold; }
        .data-table td.separator { width: 20px; text-align: center; }

        .footer-note { margin-top: 50px; border-top: 1px solid #ccc; padding-top: 10px; font-size: 12px; font-style: italic; color: #666; }

        /* Print Optimization */
        @media print {
            .page-break { page-break-after: always; }
        }
    </style>
</head>
<body>

@foreach ($details as $detail)
<div class="page-break">
    <div class="header">
        <img src="path_ke_logo_jatim.png" class="logo-left" alt="Logo Jatim">
        <img src="path_ke_logo_smk.png" class="logo-right" alt="Logo SMK">
        <h3>PEMERINTAH PROVINSI JAWA TIMUR</h3>
        <h3>DINAS PENDIDIKAN</h3>
        <h2>SEKOLAH MENENGAH KEJURUAN NEGERI 4 BOJONEGORO</h2>
        <p>Jl. Raya Surabaya - Bojonegoro, Desa Sukowati, Kecamatan Kapas, Kabupaten Bojonegoro, Jawa Timur</p>
        <p>Web: www.smkn4bojonegoro.sch.id / Email: smkn4bojonegoro@yahoo.co.id</p>
    </div>

    <div class="title">Cetak Transaksi</div>

    <div class="section-header">Data Transaksi</div>
    <table class="data-table">
        <tr>
            <td class="label">Kode transaksi</td>
            <td class="separator">:</td>
            <td>{{ $detail->transaction->kode_transaksi ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Nama</td>
            <td class="separator">:</td>
            <td>{{ $detail->transaction->user->name ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Kelas</td>
            <td class="separator">:</td>
            <td>{{ $detail->transaction->user->class ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Kode Buku</td>
            <td class="separator">:</td>
            <td>{{ $detail->kode_buku }}</td>
        </tr>
        <tr>
            <td class="label">Judul Buku</td>
            <td class="separator">:</td>
            <td>{{ $detail->judul_buku }}</td>
        </tr>
        <tr>
            <td class="label">Tgl pinjam</td>
            <td class="separator">:</td>
            <td>{{ optional($detail->transaction->tanggal_pinjam)->format('d F Y') ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Tgl Kembali</td>
            <td class="separator">:</td>
            <td>{{ optional($detail->tanggal_kembali)->format('d F Y') ?? '-' }}</td>
        </tr>
    </table>

    <div class="section-header">Status & Denda</div>
    <table class="data-table">
        <tr>
            <td class="label">Status</td>
            <td class="separator">:</td>
            <td><strong>{{ ucfirst($detail->status) }}</strong></td>
        </tr>
        <tr>
            <td class="label">Denda</td>
            <td class="separator">:</td>
            <td>Rp {{ number_format($detail->denda, 0, ',', '.') }}</td>
        </tr>
    </table>

    <div class="footer-note">
        *Dicetak otomatis oleh Perpustakaan SMKN 4 BOJONEGORO pada {{ date('d F Y , H:i') }} WIB
    </div>
</div>
@endforeach

</body>
</html>                         
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice Peminjaman - Perpustakaan SMKN 4 Bojonegoro</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Arial', sans-serif; color: #333; line-height: 1.4; margin: 0; padding: 20px; font-size: 13px; }
        .header { text-align: center; position: relative; border-bottom: 3px double #000; padding-bottom: 10px; margin-bottom: 20px; }
        .header img { position: absolute; top: 0; width: 70px; }
        .logo-left { left: 0; }
        .logo-right { right: 0; }
        .header h2, .header h3, .header p { margin: 2px 0; }
        .header p { font-size: 11px; }
        .header h2 { font-size: 15px; }
        .header h3 { font-size: 13px; }
        .title { text-align: center; text-transform: uppercase; font-weight: bold; margin: 16px 0 4px; font-size: 16px; letter-spacing: 1px; }
        .subtitle { text-align: center; font-size: 11px; color: #555; margin-bottom: 20px; }
        .section-header { background-color: #2C5AA0; color: #fff; padding: 7px 15px; font-weight: bold; margin-top: 18px; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px; }
        .data-table { width: 100%; margin-top: 8px; border-collapse: collapse; }
        .data-table td { padding: 6px 4px; vertical-align: top; font-size: 12px; }
        .data-table td.label { width: 160px; font-weight: bold; color: #444; }
        .data-table td.separator { width: 16px; text-align: center; color: #999; }
        .data-table td.value { color: #222; }
        .status-badge { display: inline-block; padding: 3px 12px; border-radius: 20px; font-size: 11px; font-weight: bold; }
        .status-dikembalikan { background: #dcfce7; color: #16a34a; }
        .status-dipinjam { background: #dbeafe; color: #2563eb; }
        .status-diperpanjang { background: #e0f2fe; color: #0284c7; }
        .status-terlambat { background: #fee2e2; color: #dc2626; }
        .status-rusak { background: #fef3c7; color: #d97706; }
        .status-hilang { background: #fee2e2; color: #dc2626; }
        .status-ditolak { background: #fee2e2; color: #dc2626; }
        .status-default { background: #f3f4f6; color: #6b7280; }
        .denda-box { margin-top: 10px; padding: 10px 15px; background: #fff7ed; border-left: 4px solid #f59e0b; border-radius: 4px; font-size: 12px; }
        .denda-box .denda-amount { font-size: 16px; font-weight: bold; color: #d97706; }
        .denda-lunas { border-left-color: #10b981; background: #f0fdf4; }
        .denda-lunas .denda-amount { color: #10b981; }
        .ttd-section { margin-top: 40px; display: table; width: 100%; }
        .ttd-box { display: table-cell; width: 50%; text-align: center; font-size: 12px; padding: 0 20px; }
        .ttd-box .ttd-title { font-weight: bold; margin-bottom: 60px; }
        .ttd-box .ttd-name { border-top: 1px solid #333; padding-top: 4px; margin-top: 4px; font-weight: bold; }
        .footer-note { margin-top: 30px; border-top: 1px solid #ccc; padding-top: 8px; font-size: 10px; font-style: italic; color: #888; text-align: center; }
        @media print { .page-break { page-break-after: always; } body { padding: 10px; } }
    </style>
</head>
<body>

@foreach ($details as $detail)
@php
    $status = $detail->status ?? 'unknown';
    $statusClass = match($status) {
        'dikembalikan' => 'status-dikembalikan',
        'dipinjam'     => 'status-dipinjam',
        'diperpanjang' => 'status-diperpanjang',
        'terlambat'    => 'status-terlambat',
        'rusak'        => 'status-rusak',
        'hilang'       => 'status-hilang',
        'ditolak'      => 'status-ditolak',
        default        => 'status-default',
    };
    $statusLabel = match($status) {
        'dikembalikan' => 'Dikembalikan',
        'dipinjam'     => 'Dipinjam',
        'diperpanjang' => 'Diperpanjang',
        'terlambat'    => 'Terlambat',
        'rusak'        => 'Rusak',
        'hilang'       => 'Hilang',
        'ditolak'      => 'Ditolak',
        default        => ucfirst($status),
    };
    $denda = $detail->denda ?? 0;
    $statusDenda = $detail->status_denda ?? null;
    $dendaLunas = $statusDenda === 'lunas';
@endphp

<div class="{{ !$loop->last ? 'page-break' : '' }}">

    <div class="header">
        <img src="{{ public_path('template/img/smk.png') }}" class="logo-left" alt="Logo Jatim">
        <img src="{{ public_path('template/img/logo.png') }}" class="logo-right" alt="Logo SMK">
        <h3>PEMERINTAH PROVINSI JAWA TIMUR</h3>
        <h3>DINAS PENDIDIKAN</h3>
        <h2>SEKOLAH MENENGAH KEJURUAN NEGERI 4 BOJONEGORO</h2>
        <p>Jl. Raya Surabaya - Bojonegoro, Desa Sukowati, Kecamatan Kapas, Kabupaten Bojonegoro, Jawa Timur</p>
        <p>Web: www.smkn4bojonegoro.sch.id &nbsp;|&nbsp; Email: smkn4bojonegoro@yahoo.co.id</p>
    </div>

    <div class="title">Invoice Peminjaman Buku</div>
    <div class="subtitle">No. Transaksi: {{ $detail->transaction->kode_transaksi ?? '-' }}</div>

    <div class="section-header">Data Peminjam</div>
    <table class="data-table">
        <tr>
            <td class="label">Nama</td>
            <td class="separator">:</td>
            <td class="value">{{ $detail->transaction->user->name ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Kelas / Jurusan</td>
            <td class="separator">:</td>
            <td class="value">{{ $detail->transaction->user->class ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">No. Anggota</td>
            <td class="separator">:</td>
            <td class="value">{{ $detail->transaction->user->member_number ?? $detail->transaction->user->id ?? '-' }}</td>
        </tr>
    </table>

    <div class="section-header">Data Buku</div>
    <table class="data-table">
        <tr>
            <td class="label">Judul Buku</td>
            <td class="separator">:</td>
            <td class="value"><strong>{{ $detail->judul_buku }}</strong></td>
        </tr>
        <tr>
            <td class="label">Kode Buku</td>
            <td class="separator">:</td>
            <td class="value">{{ $detail->bookStock->kode_eksemplar ?? $detail->kode_buku ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Penulis</td>
            <td class="separator">:</td>
            <td class="value">{{ $detail->book->penulis ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Penerbit</td>
            <td class="separator">:</td>
            <td class="value">{{ $detail->book->penerbit ?? '-' }}</td>
        </tr>
    </table>

    <div class="section-header">Data Transaksi</div>
    <table class="data-table">
        <tr>
            <td class="label">Kode Transaksi</td>
            <td class="separator">:</td>
            <td class="value">{{ $detail->transaction->kode_transaksi ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Tanggal Pinjam</td>
            <td class="separator">:</td>
            <td class="value">{{ optional($detail->transaction->tanggal_pinjam)->format('d F Y') ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Tanggal Jatuh Tempo</td>
            <td class="separator">:</td>
            <td>{{ $detail->transaction->tanggal_jatuh_tempo ? \Carbon\Carbon::parse($detail->transaction->tanggal_jatuh_tempo)->format('d F Y') : '-' }}</td>
        </tr>
        <tr>
            <td class="label">Tanggal Kembali</td>
            <td class="separator">:</td>
            <td class="value">{{ optional($detail->tanggal_kembali)->format('d F Y') ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Status</td>
            <td class="separator">:</td>
            <td class="value">
                <span class="status-badge {{ $statusClass }}">{{ $statusLabel }}</span>
            </td>
        </tr>
        @if($detail->catatan)
        <tr>
            <td class="label">Catatan</td>
            <td class="separator">:</td>
            <td class="value" style="color: #dc2626;">{{ $detail->catatan }}</td>
        </tr>
        @endif
    </table>

    <div class="ttd-section">
        <div class="ttd-box">
            <div class="ttd-title">Peminjam</div>
            <div class="ttd-name">{{ $detail->transaction->user->name ?? '____________________' }}</div>
        </div>
        <div class="ttd-box">
            <div class="ttd-title">Petugas Perpustakaan</div>
            <div class="ttd-name">____________________</div>
        </div>
    </div>

    <div class="footer-note">
        * Dicetak pada {{ \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('d F Y, H:i') }} WIB oleh Sistem Informasi Perpustakaan SMKN 4 BOJONEGORO.
    </div>

</div>
@endforeach

</body>
</html>
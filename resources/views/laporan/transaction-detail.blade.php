<!DOCTYPE html>
<html>
<head>
    <title>Laporan Transaksi</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 6px; font-size: 12px; }
        th { background: #eee; }
    </style>
</head>
<body>

<h3 align="center">Laporan Transaksi</h3>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Kode Transaksi</th>
            <th>Nama</th>
            <th>Kelas</th>
            <th>Kode Buku</th>
            <th>Judul Buku</th>
            <th>Tgl Pinjam</th>
            <th>Jatuh Tempo</th>
            <th>Tgl Kembali</th>
            <th>Status</th>
            <th>Denda</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($details as $i => $detail)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $detail->transaction->kode_transaksi ?? '-' }}</td>
            <td>{{ $detail->transaction->user->name ?? '-' }}</td>
            <td>{{ $detail->transaction->user->class ?? '-' }}</td>
            <td>{{ $detail->kode_buku }}</td>
            <td>{{ $detail->judul_buku }}</td>
            <td>
                {{ optional($detail->transaction->tanggal_pinjam)->format('d-m-Y') ?? '-' }}
            </td>

            <td>
                {{ optional($detail->tanggal_jatuh_tempo)->format('d-m-Y') }}
            </td>

            <td>
                {{ optional($detail->tanggal_kembali)->format('d-m-Y') ?? '-' }}
            </td>

            <td>{{ ucfirst($detail->status) }}</td>
            <td>Rp {{ number_format($detail->denda, 0, ',', '.') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>

<!DOCTYPE html>
<html>
<head>
    <title>Laporan Peminjaman</title>
    <style>
        body { font-family: sans-serif; }
        h2 { text-align: center; margin-bottom: 10px; }
        p { font-size: 12px; }
        table { width: 100%; border-collapse: collapse; font-size: 11px; }
        th, td { border: 1px solid #000; padding: 5px; text-align: center; }
        th { background-color: #eee; }
    </style>
</head>
<body>

<h2>LAPORAN PEMINJAMAN BUKU</h2>
<p>Tanggal Cetak: {{ date('d-m-Y') }}</p>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Kode Transaksi</th>
            <th>Nama</th>
            <th>Buku</th>
            <th>Tgl Pinjam</th>
            <th>Jatuh Tempo</th>
            <th>Status</th>
            <th>Denda</th>
        </tr>
    </thead>
    <tbody>
        @forelse($data as $i => $item)
        <tr>
            <td>{{ $i+1 }}</td>

            <!-- Kode Transaksi -->
            <td>
                {{ $item->transaction ? $item->transaction->kode_transaksi : '-' }}
            </td>

            <!-- Nama User -->
            <td>
                {{ ($item->transaction && $item->transaction->user) 
                    ? $item->transaction->user->name 
                    : '-' }}
            </td>

            <!-- Buku -->
            <td>{{ $item->judul_buku ?? '-' }}</td>

            <!-- Tanggal Pinjam -->
            <td>
                {{ ($item->transaction && $item->transaction->tanggal_pinjam)
                    ? \Carbon\Carbon::parse($item->transaction->tanggal_pinjam)->format('d-m-Y')
                    : '-' }}
            </td>

            <!-- Jatuh Tempo -->
            <td>
                {{ $item->tanggal_jatuh_tempo 
                    ? \Carbon\Carbon::parse($item->tanggal_jatuh_tempo)->format('d-m-Y')
                    : '-' }}
            </td>

            <!-- Status -->
            <td>{{ $item->status ?? '-' }}</td>

            <!-- Denda -->
            <td>
                Rp {{ number_format((float)$item->denda, 0, ',', '.') }}
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="8">Data tidak ditemukan</td>
        </tr>
        @endforelse
    </tbody>
</table>

</body>
</html>
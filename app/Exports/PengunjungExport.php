<?php

namespace App\Exports;

use App\Models\Pengunjung;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithMapping;

class PengunjungExport implements FromCollection, WithHeadings, WithMapping, WithColumnWidths
{
    protected $pengunjung;

    public function __construct(Collection $pengunjung)
    {
        $this->pengunjung = $pengunjung;
    }

    public function collection()
    {
        return $this->pengunjung;
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama',
            'Kelas',
            'NISN',
            'Keperluan',
            'Tanggal Kunjungan',
        ];
    }

    public function map($row): array
    {
        static $no = 1;

        return [
            $no++,
            $row->nama,
            $row->kelas,
            $row->nisn,
            $row->keperluan,
            $row->tanggal_kunjungan,
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 6,
            'B' => 25,
            'C' => 15,
            'D' => 18,
            'E' => 30,
            'F' => 20,
        ];
    }
}

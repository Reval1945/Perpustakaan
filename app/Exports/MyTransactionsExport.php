<?php

namespace App\Exports;

use App\Models\Transactions;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Events\AfterSheet;

class MyTransactionsExport implements
    FromArray,
    WithHeadings,
    WithStyles,
    WithEvents,
    ShouldAutoSize,
    WithStartRow
{
    protected $userId;
    protected $rows = [];

    public function __construct($userId)
    {
        $this->userId = $userId;
        $this->prepareData();
    }

    private function prepareData()
    {
        $transaksi = Transactions::with('details')
            ->where('user_id', $this->userId)
            ->get();

        $no = 1;

        foreach ($transaksi as $trx) {
            foreach ($trx->details as $detail) {
                $this->rows[] = [
                    $no++,
                    $detail->judul_buku,
                    $trx->tanggal_pinjam,
                    $detail->tanggal_jatuh_tempo,
                    ucfirst(str_replace('_', ' ', $detail->status)),
                ];
            }
        }
    }

    public function array(): array
    {
        return $this->rows;
    }

    public function headings(): array
    {
        return [
            'No',
            'Judul Buku',
            'Tanggal Pinjam',
            'Tanggal Jatuh Tempo',
            'Status',
        ];
    }

    public function startRow(): int
    {
        return 4; // data mulai baris ke-4
    }

    public function styles(Worksheet $sheet)
    {
        return [
            4 => ['font' => ['bold' => true]],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $sheet = $event->sheet->getDelegate();
                $lastRow = count($this->rows) + 4;

                // JUDUL - LEBIH BESAR DAN CENTER
                $sheet->mergeCells('A1:E1');
                $sheet->setCellValue('A1', 'LAPORAN PEMINJAMAN BUKU');
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 16,
                        'color' => ['rgb' => '2C3E50'],
                    ],
                    'alignment' => [
                        'horizontal' => 'center',
                        'vertical' => 'center',
                    ],
                    'fill' => [
                        'fillType' => 'solid',
                        'startColor' => ['rgb' => 'ECF0F1'],
                    ],
                    'borders' => [
                        'bottom' => [
                            'borderStyle' => 'medium',
                            'color' => ['rgb' => '3498DB'],
                        ],
                    ],
                ]);

                // TINGGI BARIS JUDUL
                $sheet->getRowDimension(1)->setRowHeight(40);

                // HEADER TABEL - WARNA LEBIH MODERN
                $sheet->getStyle('A4:E4')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 12,
                        'color' => ['rgb' => 'FFFFFF'],
                    ],
                    'fill' => [
                        'fillType' => 'solid',
                        'startColor' => ['rgb' => '3498DB'],
                    ],
                    'alignment' => [
                        'horizontal' => 'center',
                        'vertical' => 'center',
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => 'thin',
                            'color' => ['rgb' => '2980B9'],
                        ],
                    ],
                ]);

                // TINGGI BARIS HEADER
                $sheet->getRowDimension(4)->setRowHeight(30);

                // WARNA BARIS BERGANTIAN (ZEBRA STRIPES)
                for ($row = 5; $row <= $lastRow; $row++) {
                    $fillColor = ($row % 2 == 0) ? 'F8F9FA' : 'FFFFFF';
                    $sheet->getStyle("A{$row}:E{$row}")
                        ->getFill()
                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()->setRGB($fillColor);
                        
                    // TINGGI BARIS DATA
                    $sheet->getRowDimension($row)->setRowHeight(25);
                }

                // BORDER TABEL LEBIH HALUS
                $sheet->getStyle("A4:E{$lastRow}")
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle('thin')
                    ->getColor()->setRGB('BDC3C7');

                // ALIGNMENT LEBIH RAPI
                // No - Center
                $sheet->getStyle("A5:A{$lastRow}")
                    ->getAlignment()
                    ->setHorizontal('center')
                    ->setVertical('center');

                // Tanggal Pinjam & Jatuh Tempo - Center
                $sheet->getStyle("C5:D{$lastRow}")
                    ->getAlignment()
                    ->setHorizontal('center')
                    ->setVertical('center');

                // Status - Center
                $sheet->getStyle("E5:E{$lastRow}")
                    ->getAlignment()
                    ->setHorizontal('center')
                    ->setVertical('center');

                // Judul Buku - Left dengan wrap text
                $sheet->getStyle("B5:B{$lastRow}")
                    ->getAlignment()
                    ->setVertical('center')
                    ->setWrapText(true);

                // WARNING: Jangan diubah - auto size tetap dipertahankan
                // Hanya atur lebar khusus untuk kolom judul buku
                $sheet->getColumnDimension('B')->setWidth(35);

                // WARNA STATUS BERDASARKAN NILAI
                for ($row = 5; $row <= $lastRow; $row++) {
                    $statusCell = $sheet->getCell("E{$row}")->getValue();
                    $statusColor = '808080'; // default gray
                    
                    if (stripos($statusCell, 'dipinjam') !== false) {
                        $statusColor = 'FFD700'; // yellow
                    } elseif (stripos($statusCell, 'dikembalikan') !== false) {
                        $statusColor = '32CD32'; // green
                    } elseif (stripos($statusCell, 'terlambat') !== false) {
                        $statusColor = 'FF4500'; // red
                    } elseif (stripos($statusCell, 'hilang') !== false || stripos($statusCell, 'rusak') !== false) {
                        $statusColor = 'DC143C'; // crimson
                    }
                    
                    $sheet->getStyle("E{$row}")
                        ->getFont()
                        ->setBold(true)
                        ->getColor()->setRGB('FFFFFF');
                    
                    $sheet->getStyle("E{$row}")
                        ->getFill()
                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()->setRGB($statusColor);
                }

                // FOOTER INFORMASI
                $footerRow = $lastRow + 2;
                $sheet->setCellValue("A{$footerRow}", "Total Data: " . count($this->rows) . " transaksi");
                $sheet->getStyle("A{$footerRow}")->applyFromArray([
                    'font' => [
                        'italic' => true,
                        'color' => ['rgb' => '7F8C8D'],
                    ],
                ]);
            },
        ];
    }
}
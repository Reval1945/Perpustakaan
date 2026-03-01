<?php

namespace App\Exports;

use App\Models\TransactionDetail;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

class LaporanpeminjamanExport implements 
    FromCollection, 
    WithHeadings, 
    WithStyles, 
    WithDrawings, 
    WithCustomStartCell,
    WithMapping,
    WithEvents
    // Hapus ShouldAutoSize agar kita bisa kontrol manual lebar kolomnya
{
    private $rowNumber = 0;

    public function collection()
    {
        return TransactionDetail::with(['transaction.user'])->get();
    }

    public function startCell(): string
    {
        return 'A9';
    }

    public function map($detail): array
    {
        $this->rowNumber++;
        return [
            $this->rowNumber,
            $detail->judul_buku,
            $detail->transaction->tanggal_pinjam ? \Carbon\Carbon::parse($detail->transaction->tanggal_pinjam)->format('d/m/Y') : '-',
            $detail->transaction->tanggal_jatuh_tempo ? \Carbon\Carbon::parse($detail->transaction->tanggal_jatuh_tempo)->format('d/m/Y') : '-',
            strtoupper(str_replace('_', ' ', $detail->status))
        ];
    }

    public function headings(): array
    {
        return [
            'NO',
            'JUDUL BUKU',
            'TANGGAL PINJAM',
            'TANGGAL KEMBALI',
            'STATUS',
        ];
    }

    public function drawings()
    {
        $drawings = [];
        if (file_exists(public_path('template/img/logo.png'))) {
            $drawingLeft = new Drawing();
            $drawingLeft->setName('Logo Kiri');
            $drawingLeft->setPath(public_path('template/img/smk.png'));
            $drawingLeft->setHeight(65);
            $drawingLeft->setCoordinates('A1');
            $drawingLeft->setOffsetX(5); // Offset kecil agar tidak memakan ruang kolom
            $drawingLeft->setOffsetY(10);
            $drawings[] = $drawingLeft;

            $drawingRight = new Drawing();
            $drawingRight->setName('Logo Kanan');
            $drawingRight->setPath(public_path('template/img/logo.png'));
            $drawingRight->setHeight(65);
            $drawingRight->setCoordinates('E1');
            $drawingRight->setOffsetX(-5);
            $drawingRight->setOffsetY(10);
            $drawings[] = $drawingRight;
        }
        return $drawings;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            9 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '0D3370']
                ],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // 1. SETTING KERTAS A4
                $sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_A4);
                $sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_PORTRAIT);
                $sheet->getPageSetup()->setFitToPage(true);
                $sheet->getPageSetup()->setFitToWidth(1);
                $sheet->getPageSetup()->setFitToHeight(0);

                // 2. ATUR LEBAR KOLOM MANUAL (Agar Kolom NO tidak lebar mengikuti Logo)
                $sheet->getColumnDimension('A')->setWidth(5);   // Lebar NO kecil saja
                $sheet->getColumnDimension('B')->setWidth(40);  // Judul Buku lebar
                $sheet->getColumnDimension('C')->setWidth(20);  // Tgl Pinjam
                $sheet->getColumnDimension('D')->setWidth(30);  // Tgl Kembali
                $sheet->getColumnDimension('E')->setWidth(20);  // Status

                // 3. MEMBERSIHKAN AREA KOP (Fill Putih)
                $sheet->getStyle('A1:E8')->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('FFFFFF');

                // 4. SET TINGGI BARIS KOP
                $sheet->getRowDimension(1)->setRowHeight(15);
                $sheet->getRowDimension(2)->setRowHeight(15);
                $sheet->getRowDimension(3)->setRowHeight(20);
                $sheet->getRowDimension(6)->setRowHeight(10); 
                $sheet->getRowDimension(9)->setRowHeight(25);

                // 5. ISI KOP SURAT
                $sheet->mergeCells('B1:D1');
                $sheet->setCellValue('B1', 'PEMERINTAH PROVINSI JAWA TIMUR');
                $sheet->getStyle('B1')->getFont()->setSize(10)->setBold(true);
                
                $sheet->mergeCells('B2:D2');
                $sheet->setCellValue('B2', 'DINAS PENDIDIKAN');
                $sheet->getStyle('B2')->getFont()->setSize(11)->setBold(true);
                
                $sheet->mergeCells('B3:D3');
                $sheet->setCellValue('B3', 'SEKOLAH MENENGAH KEJURUAN NEGERI 4 BOJONEGORO');
                $sheet->getStyle('B3')->getFont()->setSize(12)->setBold(true);
                
                $sheet->mergeCells('B4:D5');
                $sheet->setCellValue('B4', "Jl. Raya Surabaya - Bojonegoro, Desa Sukowati, Kec. Kapas, Bojonegoro, Jawa Timur\nWeb: www.smkn4bojonegoro.sch.id / Email: smkn4bojonegoro@yahoo.co.id");
                $sheet->getStyle('B4')->getAlignment()->setWrapText(true);
                $sheet->getStyle('B4')->getFont()->setSize(8)->setItalic(true);

                $sheet->getStyle('B1:D5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('B1:D5')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

                // 6. GARIS DOUBLE & JUDUL
                $sheet->getStyle('A6:E6')->getBorders()->getBottom()->setBorderStyle(Border::BORDER_DOUBLE);
                $sheet->mergeCells('A8:E8');
                $sheet->setCellValue('A8', 'LAPORAN PEMINJAMAN BUKU');
                $sheet->getStyle('A8')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('A8')->getFont()->setBold(true)->setSize(14);

                // 7. BORDER & ALIGNMENT TABEL
                $lastRow = $sheet->getHighestRow();
                $sheet->getStyle('A9:E' . $lastRow)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                $sheet->getStyle('A10:A' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('C10:E' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Hilangkan baris sisa yang mungkin tinggi karena logo
                for ($i = 10; $i <= $lastRow; $i++) {
                    $sheet->getRowDimension($i)->setRowHeight(-1);
                }
            },
        ];
    }
}
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
use Carbon\Carbon;

class MyTransactionsExport implements 
    FromCollection, 
    WithHeadings, 
    WithStyles, 
    WithDrawings, 
    WithCustomStartCell,
    WithMapping,
    WithEvents
{
    protected $userId;
    private $rowNumber = 0;

    public function __construct($userId)
    {
        $this->userId = $userId;
    }

    public function collection()
    {
        return TransactionDetail::with(['transaction'])
            ->whereHas('transaction', function($q) {
                $q->where('user_id', $this->userId);
            })->get();
    }

    public function startCell(): string
    {
        return 'A9';
    }

    public function map($detail): array
    {
        $this->rowNumber++;
        
        $statusDenda = '-';
        if ($detail->denda > 0 || in_array($detail->status, ['rusak', 'hilang'])) {
            $statusDenda = $detail->status_denda === 'lunas' ? 'LUNAS' : 'BELUM LUNAS';
        }

        return [
            $this->rowNumber,
            $detail->judul_buku,
            $detail->transaction->tanggal_pinjam ? Carbon::parse($detail->transaction->tanggal_pinjam)->format('d/m/Y') : '-',
            $detail->tanggal_kembali ? Carbon::parse($detail->tanggal_kembali)->format('d/m/Y') : 'BELUM KEMBALI',
            strtoupper(str_replace('_', ' ', $detail->status)),
            $detail->denda > 0 ? $detail->denda : 0,
            $statusDenda,
            $detail->catatan ?? '-'
        ];
    }

    public function headings(): array
    {
        return [
            'NO',
            'JUDUL BUKU',
            'TANGGAL PINJAM',
            'TANGGAL KEMBALI', // Kolom baru ditambahkan kembali
            'STATUS BUKU',
            'DENDA (RP)',
            'STATUS DENDA',
            'CATATAN'
        ];
    }

    public function drawings()
    {
        $drawings = [];
        if (file_exists(public_path('template/img/smk.png'))) {
            $drawingLeft = new Drawing();
            $drawingLeft->setName('Logo Kiri');
            $drawingLeft->setPath(public_path('template/img/smk.png'));
            $drawingLeft->setHeight(65);
            $drawingLeft->setCoordinates('A1');
            $drawingLeft->setOffsetX(5);
            $drawingLeft->setOffsetY(10);
            $drawings[] = $drawingLeft;
        }

        if (file_exists(public_path('template/img/logo.png'))) {
            $drawingRight = new Drawing();
            $drawingRight->setName('Logo Kanan');
            $drawingRight->setPath(public_path('template/img/logo.png'));
            $drawingRight->setHeight(65);
            $drawingRight->setCoordinates('H1'); // Geser ke kolom H karena kolom bertambah
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
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER
                ]
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_A4);
                $sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);

                // Lebar Kolom (A sampai H)
                $sheet->getColumnDimension('A')->setWidth(5);   // NO
                $sheet->getColumnDimension('B')->setWidth(25);  // JUDUL
                $sheet->getColumnDimension('C')->setWidth(20);  // TGL PINJAM
                $sheet->getColumnDimension('D')->setWidth(20);  // TGL KEMBALI
                $sheet->getColumnDimension('E')->setWidth(15);  // STATUS BUKU
                $sheet->getColumnDimension('F')->setWidth(15);  // DENDA
                $sheet->getColumnDimension('G')->setWidth(18);  // STATUS DENDA
                $sheet->getColumnDimension('H')->setWidth(25);  // CATATAN

                // Styling Area Kop (Sampai H)
                $sheet->getStyle('A1:H8')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('FFFFFF');

                // Isi Kop Surat (Merge B-G agar simetris)
                $sheet->mergeCells('B1:G1');
                $sheet->setCellValue('B1', 'PEMERINTAH PROVINSI JAWA TIMUR');
                $sheet->mergeCells('B2:G2');
                $sheet->setCellValue('B2', 'DINAS PENDIDIKAN');
                $sheet->mergeCells('B3:G3');
                $sheet->setCellValue('B3', 'SEKOLAH MENENGAH KEJURUAN NEGERI 4 BOJONEGORO');
                
                $sheet->mergeCells('B4:G5');
                $sheet->setCellValue('B4', "Jl. Raya Surabaya - Bojonegoro, Desa Sukowati, Kec. Kapas, Bojonegoro, Jawa Timur\nWeb: www.smkn4bojonegoro.sch.id / Email: smkn4bojonegoro@yahoo.co.id");
                
                $sheet->getStyle('B1:G3')->getFont()->setBold(true);
                $sheet->getStyle('B4')->getAlignment()->setWrapText(true);
                $sheet->getStyle('B4')->getFont()->setSize(8)->setItalic(true);
                $sheet->getStyle('B1:G5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Garis Pembatas Kop
                $sheet->getStyle('A6:H6')->getBorders()->getBottom()->setBorderStyle(Border::BORDER_DOUBLE);

                // Judul Laporan
                $sheet->mergeCells('A8:H8');
                $sheet->setCellValue('A8', 'LAPORAN RIWAYAT PEMINJAMAN & DENDA');
                $sheet->getStyle('A8')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('A8')->getFont()->setBold(true)->setSize(14);

                // Border Tabel Data & Alignment
                $lastRow = $sheet->getHighestRow();
                $sheet->getStyle('A9:H' . $lastRow)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                
                $sheet->getStyle('A10:A' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('C10:G' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('H10:H' . $lastRow)->getAlignment()->setWrapText(true);
                
                // Format Rupiah untuk kolom F (Denda)
                $sheet->getStyle('F10:F' . $lastRow)->getNumberFormat()->setFormatCode('#,##0');

                $sheet->getRowDimension(9)->setRowHeight(25);
            },
        ];
    }
}
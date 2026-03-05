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
        
        $tglPinjam = Carbon::parse($detail->transaction->tanggal_pinjam)->startOfDay();
        $tglKembali = $detail->tanggal_kembali ? Carbon::parse($detail->tanggal_kembali)->startOfDay() : Carbon::now()->startOfDay();
        
        // Menggunakan (int) untuk memastikan tidak ada koma sama sekali
        $selisihHari = (int) $tglPinjam->diffInDays($tglKembali);
        
        // Format: "0 Hari" atau "5 Hari" (Bulat)
        $lamaPinjam = $selisihHari . ' Hari';

        return [
            $this->rowNumber,
            $detail->judul_buku,
            $detail->transaction->tanggal_pinjam ? Carbon::parse($detail->transaction->tanggal_pinjam)->format('d/m/Y') : '-',
            $detail->tanggal_kembali ? Carbon::parse($detail->tanggal_kembali)->format('d/m/Y') : 'Belum Kembali',
            $lamaPinjam,
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
            'LAMA PINJAM',
            'STATUS',
        ];
    }

    public function drawings()
    {
        $drawings = [];
        // Pastikan file logo tersedia di public/template/img/
        if (file_exists(public_path('template/img/logo.png'))) {
            $drawingLeft = new Drawing();
            $drawingLeft->setName('Logo Kiri');
            $drawingLeft->setPath(public_path('template/img/smk.png'));
            $drawingLeft->setHeight(65);
            $drawingLeft->setCoordinates('A1');
            $drawingLeft->setOffsetX(5);
            $drawingLeft->setOffsetY(10);
            $drawings[] = $drawingLeft;

            $drawingRight = new Drawing();
            $drawingRight->setName('Logo Kanan');
            $drawingRight->setPath(public_path('template/img/logo.png'));
            $drawingRight->setHeight(65);
            $drawingRight->setCoordinates('F1'); // Ujung kolom F
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

                // 1. Setting Kertas A4
                $sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_A4);
                $sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_PORTRAIT);
                $sheet->getPageSetup()->setFitToPage(true);
                $sheet->getPageSetup()->setFitToWidth(1);
                $sheet->getPageSetup()->setFitToHeight(0);

                // 2. Lebar Kolom Manual (Mencegah kolom A melebar karena logo)
                $sheet->getColumnDimension('A')->setWidth(6);   // NO
                $sheet->getColumnDimension('B')->setWidth(35);  // JUDUL BUKU
                $sheet->getColumnDimension('C')->setWidth(18);  // TGL PINJAM
                $sheet->getColumnDimension('D')->setWidth(18);  // TGL KEMBALI
                $sheet->getColumnDimension('E')->setWidth(15);  // LAMA PINJAM
                $sheet->getColumnDimension('F')->setWidth(35);  // STATUS

                // 3. Styling Area Kop
                $sheet->getStyle('A1:F8')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('FFFFFF');

                // 4. Isi Kop Surat (B-E agar tengah di antara Logo A dan Logo F)
                $sheet->mergeCells('B1:E1');
                $sheet->setCellValue('B1', 'PEMERINTAH PROVINSI JAWA TIMUR');
                $sheet->mergeCells('B2:E2');
                $sheet->setCellValue('B2', 'DINAS PENDIDIKAN');
                $sheet->mergeCells('B3:E3');
                $sheet->setCellValue('B3', 'SEKOLAH MENENGAH KEJURUAN NEGERI 4 BOJONEGORO');
                
                $sheet->mergeCells('B4:E5');
                $sheet->setCellValue('B4', "Jl. Raya Surabaya - Bojonegoro, Desa Sukowati, Kec. Kapas, Bojonegoro, Jawa Timur\nWeb: www.smkn4bojonegoro.sch.id / Email: smkn4bojonegoro@yahoo.co.id");
                
                $sheet->getStyle('B1:E3')->getFont()->setBold(true);
                $sheet->getStyle('B4')->getAlignment()->setWrapText(true);
                $sheet->getStyle('B4')->getFont()->setSize(8)->setItalic(true);
                $sheet->getStyle('B1:E5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('B1:E5')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

                // 5. Garis Pembatas Kop
                $sheet->getStyle('A6:F6')->getBorders()->getBottom()->setBorderStyle(Border::BORDER_DOUBLE);

                // 6. Judul Laporan
                $sheet->mergeCells('A8:F8');
                $sheet->setCellValue('A8', 'RIWAYAT PEMINJAMAN SAYA');
                $sheet->getStyle('A8')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('A8')->getFont()->setBold(true)->setSize(14);

                // 7. Border Tabel Data & Alignment
                $lastRow = $sheet->getHighestRow();
                $sheet->getStyle('A9:F' . $lastRow)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                
                // Rata tengah kolom No, Tanggal, Lama, dan Status
                $sheet->getStyle('A10:A' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('C10:F' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                
                // Tinggi baris Header
                $sheet->getRowDimension(9)->setRowHeight(25);
            },
        ];
    }
}
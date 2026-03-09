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

class LaporanDendaExport implements 
    FromCollection, 
    WithHeadings, 
    WithStyles, 
    WithDrawings, 
    WithCustomStartCell,
    WithMapping,
    WithEvents
{
    private $rowNumber = 0;

    /**
     * Mengambil data dengan filter yang lebih luas (Termasuk Hilang/Rusak)
     */
    public function collection()
    {
        return TransactionDetail::with(['transaction.user'])
            ->where(function($q) {
                $q->where('denda', '>', 0)
                  ->orWhereIn('jenis_denda', ['rusak', 'hilang'])
                  ->orWhereIn('status', ['rusak', 'hilang']);
            })
            ->get();
    }

    public function startCell(): string
    {
        return 'A9';
    }

    /**
     * Mapping data ke kolom Excel
     */
    public function map($detail): array
    {
        $this->rowNumber++;
        
        // Logika hari telat
        $telatHari = ($detail->jumlah_hari_telat ?? 0) == 0 ? '0' : $detail->jumlah_hari_telat;
        
        // Logika Jenis denda agar status 'hilang' muncul meskipun denda uang 0
        $jenisDenda = $detail->jenis_denda ?? $detail->status;

        return [
            $this->rowNumber,
            $detail->transaction->user->name ?? '-',
            $detail->judul_buku,
            $detail->transaction->tanggal_pinjam ? \Carbon\Carbon::parse($detail->transaction->tanggal_pinjam)->format('d/m/Y') : '-',
            $detail->tanggal_kembali ? \Carbon\Carbon::parse($detail->tanggal_kembali)->format('d/m/Y') : '-',
            ucfirst(str_replace('_', ' ', $jenisDenda ?? 'telat')),
            $telatHari,
            $detail->denda ?? 0,
            ucfirst(str_replace('_', ' ', $detail->status_denda)),
            $detail->catatan ?? '-',
        ];
    }

    public function headings(): array
    {
        return [
            'NO',
            'NAMA',
            'JUDUL BUKU',
            'TANGGAL PINJAM',
            'TANGGAL KEMBALI',
            'JENIS DENDA',
            'TELAT (HARI)',
            'DENDA (RP)',
            'STATUS DENDA',
            'CATATAN',
        ];
    }

    public function drawings()
    {
        $drawings = [];
        // Pastikan path logo sesuai dengan folder public Anda
        if (file_exists(public_path('template/img/smk.png'))) {
            $drawingLeft = new Drawing();
            $drawingLeft->setName('Logo Kiri');
            $drawingLeft->setPath(public_path('template/img/smk.png'));
            $drawingLeft->setHeight(65);
            $drawingLeft->setCoordinates('A1');
            $drawingLeft->setOffsetX(10);
            $drawingLeft->setOffsetY(20);
            $drawings[] = $drawingLeft;
        }

        if (file_exists(public_path('template/img/logo.png'))) {
            $drawingRight = new Drawing();
            $drawingRight->setName('Logo Kanan');
            $drawingRight->setPath(public_path('template/img/logo.png'));
            $drawingRight->setHeight(65);
            $drawingRight->setCoordinates('J1'); // Geser ke kolom J (ujung)
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

                // 1. SETTING KERTAS
                $sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_A4);
                $sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);

                // 2. ATUR LEBAR KOLOM (A - J)
                $sheet->getColumnDimension('A')->setWidth(5);
                $sheet->getColumnDimension('B')->setWidth(20);
                $sheet->getColumnDimension('C')->setWidth(30);
                $sheet->getColumnDimension('D')->setWidth(21);
                $sheet->getColumnDimension('E')->setWidth(21);
                $sheet->getColumnDimension('F')->setWidth(15);
                $sheet->getColumnDimension('G')->setWidth(12);
                $sheet->getColumnDimension('H')->setWidth(15);
                $sheet->getColumnDimension('I')->setWidth(15);
                $sheet->getColumnDimension('J')->setWidth(35); // Kolom Catatan lebih lebar

                // 3. MEMBERSIHKAN AREA KOP (A1 sampai J8)
                $sheet->getStyle('A1:J8')->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('FFFFFF');

                // 4. ISI KOP SURAT (Merge sampai kolom J)
                $sheet->mergeCells('B1:I1');
                $sheet->setCellValue('B1', 'PEMERINTAH PROVINSI JAWA TIMUR');
                $sheet->getStyle('B1')->getFont()->setSize(10)->setBold(true);

                $sheet->mergeCells('B2:I2');
                $sheet->setCellValue('B2', 'DINAS PENDIDIKAN');
                $sheet->getStyle('B2')->getFont()->setSize(11)->setBold(true);

                $sheet->mergeCells('B3:I3');
                $sheet->setCellValue('B3', 'SEKOLAH MENENGAH KEJURUAN NEGERI 4 BOJONEGORO');
                $sheet->getStyle('B3')->getFont()->setSize(12)->setBold(true);

                $sheet->mergeCells('B4:I5');
                $sheet->setCellValue('B4', "Jl. Raya Surabaya - Bojonegoro, Desa Sukowati, Kec. Kapas, Bojonegoro, Jawa Timur\nWeb: www.smkn4bojonegoro.sch.id / Email: smkn4bojonegoro@yahoo.co.id");
                $sheet->getStyle('B4')->getAlignment()->setWrapText(true);
                $sheet->getStyle('B4')->getFont()->setSize(8)->setItalic(true);

                $sheet->getStyle('B1:I5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // 6. GARIS DOUBLE & JUDUL (Sampai Kolom J)
                $sheet->getStyle('A6:J6')->getBorders()->getBottom()->setBorderStyle(Border::BORDER_DOUBLE);
                
                $sheet->mergeCells('A8:J8');
                $sheet->setCellValue('A8', 'LAPORAN REKAPITULASI DENDA & KERUSAKAN BUKU');
                $sheet->getStyle('A8')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('A8')->getFont()->setBold(true)->setSize(14);

                // 7. BORDER & ALIGNMENT TABEL (Sampai Kolom J)
                $lastRow = $sheet->getHighestRow();
                $sheet->getStyle('A9:J' . $lastRow)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                
                // Alignment Tengah untuk kolom tertentu
                $sheet->getStyle('A10:A' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('D10:J' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                
                // Khusus Catatan (Kolom J) dibuat rata kiri agar enak dibaca jika panjang
                $sheet->getStyle('J10:J' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                $sheet->getStyle('J10:J' . $lastRow)->getAlignment()->setWrapText(true);
            },
        ];
    }
}
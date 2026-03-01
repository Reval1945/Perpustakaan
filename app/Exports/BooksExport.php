<?php

namespace App\Exports;

use App\Models\Book;
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

class BooksExport implements 
    FromCollection, 
    WithHeadings, 
    WithStyles, 
    WithDrawings, 
    WithCustomStartCell,
    WithMapping,
    WithEvents
{
    protected array $filters;
    private $rowNumber = 0;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Book::with('category')->latest();

        $query->when($this->filters['judul'] ?? null, fn ($q, $v) =>
            $q->where('judul', 'like', "%{$v}%")
        );

        $query->when($this->filters['kode_buku'] ?? null, fn ($q, $v) =>
            $q->where('kode_buku', 'like', "%{$v}%")
        );

        $query->when($this->filters['rak'] ?? null, fn ($q, $v) =>
            $q->where('rak', $v)
        );

        $query->when($this->filters['nomor_rak'] ?? null, fn ($q, $v) =>
            $q->where('nomor_rak', $v)
        );

        $query->when($this->filters['category'] ?? null, function ($q, $v) {
            $q->whereHas('category', function ($qc) use ($v) {
                $qc->where('name', 'like', "%{$v}%");
            });
        });

        return $query->get();
    }

    public function startCell(): string
    {
        return 'A9';
    }

    public function map($book): array
    {
        $this->rowNumber++;
        return [
            $this->rowNumber,
            $book->kode_buku,
            $book->judul,
            $book->category->name ?? '-',
            $book->penulis,
            $book->penerbit,
            $book->tahun,
            $book->rak,
            $book->nomor_rak,
        ];
    }

    public function headings(): array
    {
        return [
            'NO',
            'Kode Buku',
            'Judul',
            'Kategori',
            'Penulis',
            'Penerbit',
            'Tahun Terbit',
            'Rak',
            'Nomor Rak',
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
            $drawingLeft->setOffsetX(5);
            $drawingLeft->setOffsetY(10);
            $drawings[] = $drawingLeft;

            $drawingRight = new Drawing();
            $drawingRight->setName('Logo Kanan');
            $drawingRight->setPath(public_path('template/img/logo.png'));
            $drawingRight->setHeight(65);
            $drawingRight->setCoordinates('I1');
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
                $sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
                $sheet->getPageSetup()->setFitToPage(true);
                $sheet->getPageSetup()->setFitToWidth(1);
                $sheet->getPageSetup()->setFitToHeight(0);

                // 2. ATUR LEBAR KOLOM MANUAL
                $sheet->getColumnDimension('A')->setWidth(5);   // NO
                $sheet->getColumnDimension('B')->setWidth(15);  // Kode Buku
                $sheet->getColumnDimension('C')->setWidth(35);  // Judul
                $sheet->getColumnDimension('D')->setWidth(20);  // Kategori
                $sheet->getColumnDimension('E')->setWidth(25);  // Penulis
                $sheet->getColumnDimension('F')->setWidth(25);  // Penerbit
                $sheet->getColumnDimension('G')->setWidth(20);  // Tahun
                $sheet->getColumnDimension('H')->setWidth(12);  // Rak
                $sheet->getColumnDimension('I')->setWidth(12);  // Nomor Rak

                // 3. MEMBERSIHKAN AREA KOP (Fill Putih)
                $sheet->getStyle('A1:I8')->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('FFFFFF');

                // 4. SET TINGGI BARIS KOP
                $sheet->getRowDimension(1)->setRowHeight(15);
                $sheet->getRowDimension(2)->setRowHeight(15);
                $sheet->getRowDimension(3)->setRowHeight(20);
                $sheet->getRowDimension(6)->setRowHeight(10);
                $sheet->getRowDimension(9)->setRowHeight(25);

                // 5. ISI KOP SURAT
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
                $sheet->getStyle('B1:I5')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

                // 6. GARIS DOUBLE & JUDUL
                $sheet->getStyle('A6:I6')->getBorders()->getBottom()->setBorderStyle(Border::BORDER_DOUBLE);
                $sheet->mergeCells('A8:I8');
                $sheet->setCellValue('A8', 'LAPORAN DATA BUKU');
                $sheet->getStyle('A8')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('A8')->getFont()->setBold(true)->setSize(14);

                // 7. BORDER & ALIGNMENT TABEL
                $lastRow = $sheet->getHighestRow();
                $sheet->getStyle('A9:I' . $lastRow)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                $sheet->getStyle('A10:A' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('G10:H' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Hilangkan baris sisa yang mungkin tinggi karena logo
                for ($i = 10; $i <= $lastRow; $i++) {
                    $sheet->getRowDimension($i)->setRowHeight(-1);
                }
            },
        ];
    }
}

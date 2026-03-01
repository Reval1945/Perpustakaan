<?php

namespace App\Exports;

use App\Models\User;
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

class UsersExport implements 
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

    public function __construct(array $filters)
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = User::query();

        $query->when($this->filters['role'] ?? null,
            fn ($q, $v) => $q->where('role', $v)
        );

        $query->when($this->filters['name'] ?? null,
            fn ($q, $v) => $q->where('name', 'like', "%$v%")
        );

        $query->when($this->filters['email'] ?? null,
            fn ($q, $v) => $q->where('email', 'like', "%$v%")
        );

        return $query->select('kode_user', 'name', 'email', 'role', 'class', 'roll_number', 'nisn', 'phone')->get();
    }

    public function startCell(): string
    {
        return 'A9';
    }

    public function map($user): array
    {
        $this->rowNumber++;
        return [
            $this->rowNumber,
            $user->kode_user ?? '-',
            $user->name ?? '-',
            $user->email ?? '-',
            ucfirst($user->role ?? '-'),
            $user->class ?? '-',
            $user->roll_number ?? '-',
            $user->nisn ?? '-',
            $user->phone ?? '-',
        ];
    }

    public function headings(): array
    {
        return [
            'NO',
            'KODE USER',
            'NAMA',
            'EMAIL',
            'ROLE',
            'KELAS',
            'NOMOR ABSEN',
            'NISN',
            'NOMOR TELEPON',
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

            // Karena kita punya 9 kolom (A sampai I), logo kanan kita taruh di I1
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

                // 1. SETTING KERTAS A4 (Landscape karena kolomnya banyak)
                $sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_A4);
                $sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
                $sheet->getPageSetup()->setFitToPage(true);
                $sheet->getPageSetup()->setFitToWidth(1);
                $sheet->getPageSetup()->setFitToHeight(0);

                // 2. ATUR LEBAR KOLOM MANUAL
                $sheet->getColumnDimension('A')->setWidth(5);   // NO
                $sheet->getColumnDimension('B')->setWidth(15);  // KODE USER
                $sheet->getColumnDimension('C')->setWidth(25);  // NAMA
                $sheet->getColumnDimension('D')->setWidth(30);  // EMAIL
                $sheet->getColumnDimension('E')->setWidth(15);  // ROLE
                $sheet->getColumnDimension('F')->setWidth(12);  // KELAS
                $sheet->getColumnDimension('G')->setWidth(15);  // NO ABSEN
                $sheet->getColumnDimension('H')->setWidth(20);  // NISN
                $sheet->getColumnDimension('I')->setWidth(18);  // NO TELEPON

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

                // 5. ISI KOP SURAT (Diletakkan di tengah antara B dan H)
                $sheet->mergeCells('B1:H1');
                $sheet->setCellValue('B1', 'PEMERINTAH PROVINSI JAWA TIMUR');
                $sheet->getStyle('B1')->getFont()->setSize(10)->setBold(true);

                $sheet->mergeCells('B2:H2');
                $sheet->setCellValue('B2', 'DINAS PENDIDIKAN');
                $sheet->getStyle('B2')->getFont()->setSize(11)->setBold(true);

                $sheet->mergeCells('B3:H3');
                $sheet->setCellValue('B3', 'SEKOLAH MENENGAH KEJURUAN NEGERI 4 BOJONEGORO');
                $sheet->getStyle('B3')->getFont()->setSize(12)->setBold(true);

                $sheet->mergeCells('B4:H5');
                $sheet->setCellValue('B4', "Jl. Raya Surabaya - Bojonegoro, Desa Sukowati, Kec. Kapas, Bojonegoro, Jawa Timur\nWeb: www.smkn4bojonegoro.sch.id / Email: smkn4bojonegoro@yahoo.co.id");
                $sheet->getStyle('B4')->getAlignment()->setWrapText(true);
                $sheet->getStyle('B4')->getFont()->setSize(8)->setItalic(true);

                $sheet->getStyle('B1:H5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('B1:H5')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

                // 6. GARIS DOUBLE & JUDUL
                $sheet->getStyle('A6:I6')->getBorders()->getBottom()->setBorderStyle(Border::BORDER_DOUBLE);
                $sheet->mergeCells('A8:I8');
                $sheet->setCellValue('A8', 'DAFTAR ANGGOTA PERPUSTAKAAN');
                $sheet->getStyle('A8')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('A8')->getFont()->setBold(true)->setSize(14);

                // 7. BORDER & ALIGNMENT TABEL DATA
                $lastRow = $sheet->getHighestRow();
                // Jika tidak ada data, pastikan kita minimal border header
                if ($lastRow < 9) $lastRow = 9; 

                $sheet->getStyle('A9:I' . $lastRow)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                
                // Alignment Rata Tengah untuk kolom tertentu
                $sheet->getStyle('A10:B' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('E10:I' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Hilangkan baris sisa yang mungkin tinggi karena logo
                for ($i = 10; $i <= $lastRow; $i++) {
                    $sheet->getRowDimension($i)->setRowHeight(-1);
                }
            },
        ];
    }
}
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

class SuperAdminExport implements 
    FromCollection, 
    WithHeadings, 
    WithStyles, 
    WithDrawings, 
    WithCustomStartCell,
    WithMapping,
    WithEvents
{
    private $rowNumber = 0;

    public function collection()
    {
        // Mengambil data user dengan role admin
        return User::where('role', 'admin')
            ->select('id', 'kode_user', 'name', 'email', 'role', 'created_at')
            ->get();
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
            $user->created_at ? $user->created_at->format('d-m-Y') : '-',
        ];
    }

    public function headings(): array
    {
        return [
            'NO',
            'KODE USER',
            'NAMA LENGKAP',
            'EMAIL',
            'ROLE',
            'TANGGAL BERGABUNG',
        ];
    }

    public function drawings()
    {
        $drawings = [];
        // Pastikan file gambar tersedia di public/template/img/
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
            $drawingRight->setCoordinates('F1'); // Berakhir di kolom F karena tabel ini lebih ramping
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
                $sheet->getPageSetup()->setFitToWidth(1);

                // 2. ATUR LEBAR KOLOM (Disesuaikan untuk 6 kolom)
                $sheet->getColumnDimension('A')->setWidth(8);   // NO
                $sheet->getColumnDimension('B')->setWidth(20);  // KODE USER
                $sheet->getColumnDimension('C')->setWidth(35);  // NAMA
                $sheet->getColumnDimension('D')->setWidth(35);  // EMAIL
                $sheet->getColumnDimension('E')->setWidth(15);  // ROLE
                $sheet->getColumnDimension('F')->setWidth(25);  // TANGGAL

                // 3. CLEAN UP AREA KOP
                $sheet->getStyle('A1:F8')->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('FFFFFF');

                // 4. ISI KOP SURAT
                $sheet->mergeCells('B1:E1');
                $sheet->setCellValue('B1', 'PEMERINTAH PROVINSI JAWA TIMUR');
                $sheet->getStyle('B1')->getFont()->setSize(10)->setBold(true);

                $sheet->mergeCells('B2:E2');
                $sheet->setCellValue('B2', 'DINAS PENDIDIKAN');
                $sheet->getStyle('B2')->getFont()->setSize(11)->setBold(true);

                $sheet->mergeCells('B3:E3');
                $sheet->setCellValue('B3', 'SEKOLAH MENENGAH KEJURUAN NEGERI 4 BOJONEGORO');
                $sheet->getStyle('B3')->getFont()->setSize(12)->setBold(true);

                $sheet->mergeCells('B4:E5');
                $sheet->setCellValue('B4', "Jl. Raya Surabaya - Bojonegoro, Desa Sukowati, Kec. Kapas, Bojonegoro, Jawa Timur\nWeb: www.smkn4bojonegoro.sch.id / Email: smkn4bojonegoro@yahoo.co.id");
                $sheet->getStyle('B4')->getAlignment()->setWrapText(true);
                $sheet->getStyle('B4')->getFont()->setSize(8)->setItalic(true);

                $sheet->getStyle('B1:E5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('B1:E5')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

                // 5. GARIS PEMISAH & JUDUL
                $sheet->getStyle('A6:F6')->getBorders()->getBottom()->setBorderStyle(Border::BORDER_DOUBLE);
                $sheet->mergeCells('A8:F8');
                $sheet->setCellValue('A8', 'DAFTAR ADMINISTRATOR SISTEM');
                $sheet->getStyle('A8')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('A8')->getFont()->setBold(true)->setSize(14);

                // 6. BORDER DATA
                $lastRow = $sheet->getHighestRow();
                if ($lastRow < 9) $lastRow = 9; 

                $sheet->getStyle('A9:F' . $lastRow)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                
                // Alignment Tengah
                $sheet->getStyle('A10:B' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('E10:F' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            },
        ];
    }
}
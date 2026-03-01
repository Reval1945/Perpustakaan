<?php

namespace App\Exports;

use App\Models\Transactions;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class MyTransactionsExport implements FromView, WithTitle, WithEvents, ShouldAutoSize
{
    protected $userId;

    public function __construct($userId)
    {
        $this->userId = $userId;
    }

    public function title(): string
    {
        return 'Laporan Transaksi';
    }

    public function view(): View
    {
        return view('exports.transactions', [
            'transaksi' => Transactions::with('details')
                ->where('user_id', $this->userId)
                ->get()
        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // --- FIX TATA LETAK KOP (MENCEGAH GAMBAR MENIMPA TEKS) ---
                // Kita atur tinggi baris 1-4 secara manual agar total tingginya >= 75px
                $sheet->getRowDimension(1)->setRowHeight(25);
                $sheet->getRowDimension(2)->setRowHeight(25);
                $sheet->getRowDimension(3)->setRowHeight(30);
                $sheet->getRowDimension(4)->setRowHeight(45); // Baris alamat lebih tinggi untuk spasi

                // --- STYLING TAMBAHAN ---
                // Perataan vertikal untuk seluruh dokumen agar rapi di tengah sel
                $highestRow = $sheet->getHighestRow();
                $sheet->getStyle("A1:E$highestRow")->getAlignment()->setVertical('center');

                // Judul Buku (Kolom B) dibuat Wrap Text jika sangat panjang
                $sheet->getStyle("B1:B$highestRow")->getAlignment()->setWrapText(true);

                // --- PRINT SETTINGS ---
                $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
                $sheet->getPageSetup()->setFitToWidth(1);
                $sheet->getPageSetup()->setHorizontalCentered(true);
            },
        ];
    }
}
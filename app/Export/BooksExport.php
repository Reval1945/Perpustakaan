<?php

namespace App\Export;

use App\Models\Book;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class BooksExport implements FromCollection, WithHeadings, WithColumnWidths
{
    protected array $filters;

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

        return $query->get()->map(function ($book) {
            return [
                $book->kode_buku,
                $book->judul,
                $book->category->name ?? '-',
                $book->penulis,
                $book->penerbit,
                $book->tahun,
                $book->stok,
                $book->rak,
                $book->nomor_rak,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Kode Buku',
            'Judul',
            'Kategori',
            'Penulis',
            'Penerbit',
            'Tahun',
            'Stok',
            'Rak',
            'Nomor Rak',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 15, // kode buku
            'B' => 35, // judul
            'C' => 20, // kategori
            'D' => 25, // penulis
            'E' => 25, // penerbit
            'F' => 10, // tahun
            'G' => 10, // stok
            'H' => 12, // rak
            'I' => 12, // nomor rak
        ];
    }
}

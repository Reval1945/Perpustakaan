<?php

namespace App\Repositories;

use App\Models\Book;
use App\Interfaces\BookInterface;
use Illuminate\Support\Facades\Storage;

class BookRepository implements BookInterface
{
    public function getAll(array $filters = [])
    {
        $query = Book::with('category')
            ->withCount('stocks as available_stock')
            ->latest();

        $query->when($filters['judul'] ?? null, function ($q, $value) {
            $q->where('judul', 'like', "%{$value}%");
        });

        $query->when($filters['kode_buku'] ?? null, function ($q, $value) {
            $q->where('kode_buku', 'like', "%{$value}%");
        });

        $query->when($filters['rak'] ?? null, function ($q, $value) {
            $q->where('rak', $value);
        });

        $query->when($filters['nomor_rak'] ?? null, function ($q, $value) {
            $q->where('nomor_rak', $value);
        });

        $query->when($filters['category'] ?? null, function ($q, $value) {
            $q->whereHas('category', function ($qc) use ($value) {
                $qc->where('name', 'like', "%{$value}%");
            });
        });

        return $query->get();
    }


    public function findById(string $id): ?Book
    {
        return Book::with('category')->find($id);
    }

    public function create(array $data): Book
    {
        if (isset($data['image'])) {
            $data['image'] = $data['image']->store('books', 'public');
        }
        return Book::create($data);
    }

    public function update(Book $book, array $data): Book
    {
        if (isset($data['image']) && $data['image'] instanceof \Illuminate\Http\UploadedFile) {

            if ($book->image && Storage::disk('public')->exists($book->image)) {
                Storage::disk('public')->delete($book->image);
            }

            $data['image'] = $data['image']->store('books', 'public');
        } else {
            unset($data['image']);
        }

        $book->update($data);
        return $book;
    }


    public function delete(Book $book): bool
    {
        return $book->delete();
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookStock;
use Illuminate\Http\Request;

class BookStockController extends Controller
{
    /**
     * [ADMIN] Tampilkan semua stok milik sebuah buku.
     */
    public function index(Book $book)
    {
        return response()->json([
            'data' => $book->stocks()->orderBy('kode_eksemplar')->get()
        ]);
    }

    /**
     * [ADMIN] Tambah eksemplar baru.
     */
    public function store(Request $request, Book $book)
    {
        $request->validate([
            'kode_eksemplar' => ['required', 'string', 'unique:book_stocks,kode_eksemplar'],
        ]);

        $stock = $book->stocks()->create([
            'kode_eksemplar' => $request->kode_eksemplar,
            'status'         => 'tersedia',
        ]);

        return response()->json([
            'message' => 'Eksemplar berhasil ditambahkan',
            'data'    => $stock,
        ], 201);
    }

    /**
     * [ADMIN] Hapus eksemplar.
     */
    public function destroy($id)
    {
        $stock = BookStock::findOrFail($id);

        if ($stock->status !== 'tersedia') {
            return response()->json([
                'message' => 'Eksemplar sedang dipinjam, tidak dapat dihapus.'
            ], 422);
        }

        $stock->delete();

        return response()->json(['message' => 'Eksemplar berhasil dihapus']);
    }

    /**
     * [USER] Ambil daftar eksemplar TERSEDIA dari sebuah buku.
     * GET /api/books/{book}/stok-tersedia
     */
    public function stokTersedia(Book $book)
    {
        $stocks = $book->stocks()
            ->where('status', 'tersedia')
            ->orderBy('kode_eksemplar')
            ->get(['id', 'kode_eksemplar', 'status']);

        return response()->json(['data' => $stocks]);
    }
}
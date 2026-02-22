<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\BookStock;
use Illuminate\Support\Str;

class BookStockController extends Controller
{
    public function store(Request $request, Book $book)
    {
        $request->validate([
            'jumlah'=>'required|integer|min:1'
        ]);

        for($i=1;$i<=$request->jumlah;$i++){
            BookStock::create([
                'book_id'=>$book->id,
                'kode_eksemplar'=>$book->kode_buku.'-'.Str::random(5)
            ]);
        }

        return response()->json(['message'=>'Stok berhasil ditambah']);
    }

}

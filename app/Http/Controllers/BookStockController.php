<?php

namespace App\Http\Controllers;

use App\Models\BookStock;
use Illuminate\Http\Request;

class BookStockController extends Controller
{
    public function index($bookId)
    {
        $stocks = BookStock::where('book_id',$bookId)->get();

        return response()->json([
            'success'=>true,
            'data'=>$stocks
        ]);
    }

    public function store(Request $request,$bookId)
    {
        $request->validate([
            'kode_eksemplar' => 'required|string|max:50|unique:book_stocks,kode_eksemplar'
        ]);

        $stock = BookStock::create([
            'book_id'=>$bookId,
            'kode_eksemplar'=>$request->kode_eksemplar
        ]);

        return response()->json([
            'success'=>true,
            'message'=>'Stok berhasil ditambahkan',
            'data'=>$stock
        ]);
    }

    public function destroy($id)
    {
        $stock = BookStock::findOrFail($id);
        $stock->delete();

        return response()->json([
            'success'=>true,
            'message'=>'Stok dihapus'
        ]);
    }

}
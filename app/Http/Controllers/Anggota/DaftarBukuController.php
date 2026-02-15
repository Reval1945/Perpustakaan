<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DaftarBukuController extends Controller
{
    public function index()
    {
        return view('anggota.buku');
    }
}

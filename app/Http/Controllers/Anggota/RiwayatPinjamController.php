<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RiwayatPinjamController extends Controller
{
    public function index()
    {
        return view('anggota.riwayatpinjam');
    }
}

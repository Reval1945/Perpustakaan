<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RegisterWargaController extends Controller
{
    public function showRegisterWarga()
    {
        return view('registerwarga');
    }
}

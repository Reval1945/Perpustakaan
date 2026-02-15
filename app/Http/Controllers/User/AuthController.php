<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    // Halaman Pilih Role
    public function pilihRolePage()
    {
        return view('pilihrole');
    }

    // Halaman register
    public function registerwargaPage()
    {
        return view('registerwarga');
    }

    // Proses register
    public function registerwarga(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed', // password_confirmation
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('login')->with('success', 'Registrasi berhasil. Silahkan login!');
    }
    // Halaman register
    public function registerPage()
    {
        return view('register');
    }

    // Proses register
    public function register(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|string|min:6',
            'nisn'      => 'required|string',
            'no_telp'   => 'required|string',
            'kelas'     => 'required|string',
            'no_absen'  => 'required|integer',
        ]);

        User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'nisn'      => $request->nisn,
            'no_telp'   => $request->no_telp,
            'kelas'     => $request->kelas,
            'no_absen'  => $request->no_absen,
        ]);

        return redirect()->route('login')
            ->with('success', 'Registrasi berhasil. Silakan login.');
    }

    // Halaman login
    public function loginPage()
    {
        return view('login');
    }

    // Proses login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}

<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Exports\SuperAdminExport;
use Maatwebsite\Excel\Facades\Excel;

class AdminController extends Controller
{
    public function index()
    {
        return view('superadmin.admin');
    }
    public function getAdmins(Request $request)
    {
        // Middleware role.manual sudah cek superadmin
        $admins = User::whereIn('role', ['admin'])->get();

        return response()->json([
            'status' => 'success',
            'data' => $admins
        ]);
    }
    public function exportExcel()
    {
        return Excel::download(new SuperAdminExport, 'daftar_admin.xlsx');
    }
     // Tambah admin
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        $admin = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'admin',
            'kode_user' => User::generateKode('admin')
        ]);

        return response()->json(['status' => 'success', 'data' => $admin]);
    }

    // Edit admin
    public function update(Request $request, $id)
    {
        $admin = User::where('role', 'admin')->findOrFail($id);

        $admin->update($request->only('name', 'email')); // update name & email saja
        return response()->json(['status' => 'success', 'data' => $admin]);
    }

    // Hapus admin
    public function destroy($id)
    {
        $admin = User::where('role', 'admin')->findOrFail($id);
        $admin->delete();
        return response()->json(['status' => 'success', 'message' => 'Admin berhasil dihapus']);
    }
}


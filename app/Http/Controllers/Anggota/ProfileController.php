<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Barryvdh\DomPDF\Facade\Pdf;

class ProfileController extends Controller
{
    public function me(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'name' => $user->name,
            'photo' => $user->photo
                ? asset('storage/profile/'.$user->photo)
                : asset('template/img/undraw_profile.svg')
        ]);
    }

   public function update(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'name' => 'required|max:255',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $data = [
            'name' => $request->name
        ];

        if ($request->hasFile('photo')) {

            // hapus lama
            if ($user->photo && Storage::disk('public')->exists('profile/'.$user->photo)) {
                Storage::disk('public')->delete('profile/'.$user->photo);
            }

            $file = $request->file('photo');

            // auto generate name
            $filename = uniqid().'_'.$file->getClientOriginalName();

            // simpan
            Storage::disk('public')->putFileAs(
                'profile',
                $file,
                $filename
            );

            $data['photo'] = $filename;
        }

        $user->update($data);

        return response()->json([
            'message' => 'Profil berhasil diupdate',
            'photo' => asset('storage/profile/'.$data['photo'] ?? $user->photo)
        ]);
    }

    // ProfileController.php
    public function exportPdf(Request $request)
    {
        $user = $request->user(); // otomatis dari token login

        $pdf = Pdf::loadView('laporan.member_card', [
            'user' => $user
        ])->setPaper([0, 0, 242.65, 153.07], 'landscape');
        // ukuran kartu (ID Card)

        return response($pdf->output(),200)
            ->header('Content-Type','application/pdf')
            ->header('Content-Disposition','inline; filename="kartu.pdf"');
    }
}
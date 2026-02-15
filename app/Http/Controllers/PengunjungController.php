<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Exports\PengunjungExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Interfaces\PengunjungInterface;
use App\Http\Requests\Pengunjung\StorePengunjungRequest;
use App\Http\Requests\Pengunjung\UpdatePengunjungRequest;

class PengunjungController extends Controller
{
    public function __construct(
        protected PengunjungInterface $pengunjung
    ) {}

    // GET ALL
    public function index()
    {
        try {
            return response()->json([
                'data' => $this->pengunjung->getAll()
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal mengambil data pengunjung',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    // CREATE
    public function store(StorePengunjungRequest $request)
    {
        try {
            $user = $request->user();

            $data = [
                'user_id'           => $user->id,
                'nama'              => $user->name,
                'kelas'             => $user->class,
                'nisn'              => $user->nisn,
                'keperluan'         => $request->keperluan,
                'tanggal_kunjungan' => $request->tanggal_kunjungan ?? now()->toDateString(),
            ];

            $pengunjung = $this->pengunjung->create($data);

            return response()->json([
                'message' => 'Pengunjung berhasil dicatat',
                'data'    => $pengunjung
            ], 201);

        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal mencatat pengunjung',
                'error'   => $e->getMessage()
            ], 500);
        }
    }


    // SHOW
    public function show(string $id)
    {
        try {
            return response()->json([
                'data' => $this->pengunjung->getById($id)
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Data pengunjung tidak ditemukan',
                'error'   => $e->getMessage()
            ], 404);
        }
    }

    // UPDATE
    public function update(UpdatePengunjungRequest $request, string $id)
    {
        try {
            $pengunjung = $this->pengunjung->getById($id);

            return response()->json([
                'message' => 'Pengunjung berhasil diperbarui',
                'data'    => $this->pengunjung->update(
                    $pengunjung,
                    $request->validated()
                )
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal memperbarui pengunjung',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    // DELETE
    public function destroy(string $id)
    {
        try {
            $pengunjung = $this->pengunjung->getById($id);
            $this->pengunjung->delete($pengunjung);

            return response()->json([
                'message' => 'Pengunjung berhasil dihapus'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal menghapus pengunjung',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function exportExcel(Request $request)
    {
        try {
            $pengunjung = $this->pengunjung->getForExport([
                'tanggal' => $request->tanggal
            ]);

            return Excel::download(
                new PengunjungExport($pengunjung),
                'data-pengunjung.xlsx'
            );
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Gagal export Excel',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}

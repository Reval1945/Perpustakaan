<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\AturanPeminjaman\StoreAturanPeminjamanRequest;
use App\Http\Requests\AturanPeminjaman\UpdateAturanPeminjamanRequest;
use App\Http\Resources\AturanPeminjamanResource;
use App\Interfaces\AturanPeminjamanInterface;
use Exception;

class AturanPeminjamanController extends Controller
{
    public function __construct(protected AturanPeminjamanInterface $repository) {}

    public function index()
    {
        try {
            return response()->json([
                'data' => AturanPeminjamanResource::collection(
                    $this->repository->getAll()
                )
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal mengambil data aturan peminjaman'
            ], 500);
        }
    }

    public function store(StoreAturanPeminjamanRequest $request)
    {
        try {
            $aturan = $this->repository->create($request->validated());

            return response()->json([
                'message' => 'Aturan peminjaman berhasil ditambahkan',
                'data'    => new AturanPeminjamanResource($aturan)
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal menyimpan aturan peminjaman'
            ], 500);
        }
    }

    public function show(string $id)
    {
        try {
            $aturan = $this->repository->findById($id);

            return response()->json([
                'data' => new AturanPeminjamanResource($aturan)
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Data aturan peminjaman tidak ditemukan'
            ], 404);
        }
    }


    public function update(UpdateAturanPeminjamanRequest $request, string $id)
    {
        try {
            $aturan = \App\Models\AturanPeminjaman::findOrFail($id);

            return response()->json([
                'message' => 'Aturan peminjaman berhasil diperbarui',
                'data'    => new AturanPeminjamanResource(
                    $this->repository->update($aturan, $request->validated())
                )
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal memperbarui aturan peminjaman'
            ], 500);
        }
    }

    public function getAktif()
    {
        try {
            $aturan = $this->repository->getAktif();

            if (!$aturan) {
                return response()->json([
                    'message' => 'Belum ada aturan peminjaman yang aktif'
                ], 404);
            }

            return response()->json([
                'data' => new AturanPeminjamanResource($aturan)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal mengambil aturan aktif',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}




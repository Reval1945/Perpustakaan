<?php

namespace App\Repositories;

use App\Models\AturanPeminjaman;
use App\Interfaces\AturanPeminjamanInterface;

class AturanPeminjamanRepository implements AturanPeminjamanInterface
{
    public function getAll()
    {
        return AturanPeminjaman::latest()->get();
    }

    public function getAktif(): ?AturanPeminjaman
    {
        return AturanPeminjaman::where('aktif', 1)->first();
    }

    public function findById(string $id): AturanPeminjaman
    {
        return AturanPeminjaman::findOrFail($id);
    }

    public function nonaktifkanSemua(): void
    {
        AturanPeminjaman::where('aktif', 1)->update(['aktif' => 0]);
    }

    public function create(array $data): AturanPeminjaman
    {
        if ($data['aktif']) {
            $this->nonaktifkanSemua();
        }

        return AturanPeminjaman::create($data);
    }

    public function update(AturanPeminjaman $aturan, array $data): AturanPeminjaman
    {
        if (isset($data['aktif']) && $data['aktif']) {
            $this->nonaktifkanSemua();
        }

        $aturan->update($data);
        return $aturan;
    }
}

<?php

namespace App\Repositories;

use App\Models\Pengunjung;
use App\Interfaces\PengunjungInterface;
use Illuminate\Support\Collection;

class PengunjungRepository implements PengunjungInterface
{
    public function getAll()
    {
        return Pengunjung::orderBy('tanggal_kunjungan', 'desc')->get();
    }

    public function getById(string $id): Pengunjung
    {
        return Pengunjung::findOrFail($id);
    }

    public function create(array $data): Pengunjung
    {
        return Pengunjung::create($data);
    }

    public function update(Pengunjung $pengunjung, array $data): Pengunjung
    {
        $pengunjung->update($data);
        return $pengunjung;
    }

    public function delete(Pengunjung $pengunjung): void
    {
        $pengunjung->delete();
    }

    public function getForExport(array $filters = []): Collection
    {
        $query = Pengunjung::query();

        if (!empty($filters['tanggal'])) {
            $query->whereDate('tanggal_kunjungan', $filters['tanggal']);
        }

        if (!empty($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        return $query
            ->orderBy('tanggal_kunjungan', 'desc')
            ->get();
    }
}

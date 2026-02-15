<?php

namespace App\Interfaces;

use App\Models\Pengunjung;
use Illuminate\Support\Collection;

interface PengunjungInterface
{
    public function getAll();
    public function getById(string $id): Pengunjung;
    public function create(array $data): Pengunjung;
    public function update(Pengunjung $pengunjung, array $data): Pengunjung;
    public function delete(Pengunjung $pengunjung): void;
    public function getForExport(array $filters = []): Collection;
}

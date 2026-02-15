<?php

namespace App\Interfaces;

use App\Models\AturanPeminjaman;

interface AturanPeminjamanInterface
{
    public function getAll();
    public function getAktif(): ?AturanPeminjaman;
    public function findById(string $id): AturanPeminjaman;
    public function create(array $data): AturanPeminjaman;
    public function update(AturanPeminjaman $aturan, array $data): AturanPeminjaman;
    public function nonaktifkanSemua(): void;
}

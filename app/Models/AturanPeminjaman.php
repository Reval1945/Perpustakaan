<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class AturanPeminjaman extends Model
{
    use HasUuids, SoftDeletes;

    protected $table = 'aturan_peminjaman_tables';

    protected $fillable = [
        'id',
        'maks_hari_pinjam',
        'denda_per_hari',
        'aktif',
        'keterangan'
    ];

    public $incrementing = false;
    protected $keyType = 'string';

    public function transactions()
    {
        return $this->hasMany(Transactions::class);
    }
}


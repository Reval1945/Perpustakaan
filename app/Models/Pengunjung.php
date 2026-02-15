<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Pengunjung extends Model
{
    use HasUuids;

    protected $table = 'pengunjungs';

    protected $fillable = [
        'user_id',
        'nama',
        'kelas',
        'nisn',
        'keperluan',
        'tanggal_kunjungan',
    ];
}

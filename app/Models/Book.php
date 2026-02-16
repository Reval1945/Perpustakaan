<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Book extends Model
{
    use HasUuids, SoftDeletes;
    protected $table = 'books';

    protected $fillable = [
        'id',
        'kode_buku',
        'judul',
        'sinopsis',
        'category_id',
        'penulis',
        'penerbit',
        'tahun',
        'stok',
        'rak',
        'nomor_rak',
        'image'
    ];

    public $incrementing = false;
    protected $keyType = 'string';

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class);
    }
}

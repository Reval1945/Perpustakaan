<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class TransactionDetail extends Model
{
    use HasUuids, SoftDeletes;

    protected $table = 'transaction_details';

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'transaction_id',
        'book_id',
        'kode_buku',
        'judul_buku',
        'tanggal_kembali',
        'tanggal_jatuh_tempo',
        'status',
        'jumlah_hari_telat',
        'denda',
        'jenis_denda',
        'status_denda',
        'book_stock_id',
    ];

    protected $casts = [
        'tanggal_kembali' => 'date',
        'jumlah_hari_telat' => 'integer',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transactions::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id', 'id');
    }
    public function getTanggalPinjamFormatAttribute()
    {
        return \Carbon\Carbon::parse($this->tanggal_pinjam)->format('Y-m-d');
    }

    public function stock()
    {
        return $this->belongsTo(BookStock::class,'book_stock_id');
    }

    public function bookStock()
    {
        return $this->belongsTo(BookStock::class);
    }
}

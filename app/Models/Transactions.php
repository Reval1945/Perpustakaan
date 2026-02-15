<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Transactions extends Model
{
    use HasUuids, SoftDeletes;

    protected $table = 'transactions';
    protected $keyType = 'string';

    protected $fillable = [
        'kode_transaksi',
        'user_id',
        'tanggal_pinjam',
        'tanggal_jatuh_tempo',
        // 'tanggal_kembali',
        'status',
        'lunas'
    ];

    protected $casts = [
        'tanggal_pinjam' => 'date',
        'tanggal_jatuh_tempo' => 'date',
        'lunas' => 'boolean',
        'total_denda' => 'decimal:2',
    ];

    public function book()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function details()
    {
        return $this->hasMany(TransactionDetail::class, 'transaction_id', 'id'); 
    }

    public function getTanggalPinjamFormatAttribute()
    {
        return \Carbon\Carbon::parse($this->tanggal_pinjam)->format('Y-m-d');
    }


    
}

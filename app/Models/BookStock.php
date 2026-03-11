<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class BookStock extends Model
{
    use SoftDeletes;

    protected $table = 'book_stocks';

    protected $fillable = [
        'id',
        'book_id',
        'kode_eksemplar',
        'status',
    ];

    public $incrementing = false;
    protected $keyType   = 'string';

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->id) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    // ── Scopes ────────────────────────────────────────────────────────────
    public function scopeAvailable($query)
    {
        return $query->where('status', 'tersedia');
    }

    public function scopeBorrowed($query)
    {
        return $query->where('status', 'dipinjam');
    }

    public function scopeReserved($query)
    {
        return $query->where('status', 'direservasi');
    }

    // ── Helpers ───────────────────────────────────────────────────────────
    public function isAvailable(): bool
    {
        return $this->status === 'tersedia';
    }

    public function isBorrowed(): bool
    {
        return $this->status === 'dipinjam';
    }

    public function isReserved(): bool
    {
        return $this->status === 'direservasi';
    }
}
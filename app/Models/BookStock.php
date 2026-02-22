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
        'status'
    ];

    public $incrementing = false;
    protected $keyType = 'string';

    protected static function boot()
    {
        parent::boot();

        // auto generate UUID
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

    public function scopeAvailable($query)
    {
        return $query->where('status','tersedia');
    }

    public function scopeBorrowed($query)
    {
        return $query->where('status','dipinjam');
    }

    public function isAvailable()
    {
        return $this->status === 'tersedia';
    }

    public function isBorrowed()
    {
        return $this->status === 'dipinjam';
    }
}

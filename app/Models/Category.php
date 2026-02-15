<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Category extends Model
{
    use HasUuids;

    protected $fillable = [
        'id',
        'name',
        'deskripsi'
    ];

    public $incrementing = false;
    protected $keyType = 'string';

    public function books()
    {
        return $this->hasMany(Book::class);
    }
}


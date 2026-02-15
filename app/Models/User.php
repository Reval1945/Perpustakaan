<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'class',
        'roll_number',
        'phone',
        'nisn',
        'kode_user',
        'role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public static function generateKode(string $role): string
    {
        $prefix = match ($role) {
            'super_admin' => 'SA',
            'admin'       => 'AN',
            default       => 'SI',
        };

        $last = self::where('kode_user', 'like', "$prefix%")
            ->orderBy('kode_user', 'desc')
            ->first();

        $number = $last
            ? intval(substr($last->kode_user, 2)) + 1
            : 1;

        return $prefix . str_pad($number, 9, '0', STR_PAD_LEFT);
    }

    public function pengunjung()
    {
        return $this->hasMany(Pengunjung::class);
    }

    public function transactions(){
        return $this->hasMany(Transactions::class);
    }
}

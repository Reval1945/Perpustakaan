<?php

namespace App\Export;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersExport implements FromCollection, WithHeadings
{
    protected array $filters;

    public function __construct(array $filters)
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = User::query();

        $query->when($this->filters['role'] ?? null,
            fn ($q, $v) => $q->where('role', $v)
        );

        $query->when($this->filters['name'] ?? null,
            fn ($q, $v) => $q->where('name', 'like', "%$v%")
        );

        $query->when($this->filters['email'] ?? null,
            fn ($q, $v) => $q->where('email', 'like', "%$v%")
        );

        return $query->select('kode_user', 'name', 'email', 'role', 'class', 'roll_number', 'nisn', 'phone')->get();
    }

    public function headings(): array
    {
        return ['Kode User', 'Nama', 'Email', 'Role', 'Kelas', 'Nomor Absen', 'Nomor Telepon'];
    }
}

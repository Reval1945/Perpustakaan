<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;

class SuperAdminExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return User::where('role', 'admin')
            ->select('id','kode_user','name','email','role','created_at')
            ->get();
    }
    public function headings(): array
    {
        return ['ID', 'Kode User', 'Nama', 'Email', 'Role', 'Tanggal Dibuat'];
    }
}

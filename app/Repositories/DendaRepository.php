<?php

namespace App\Repositories;

use App\Models\TransactionDetail;
use App\Interfaces\DendaInterface;

class DendaRepository implements DendaInterface
{
    public function getAllWithDenda(?string $status)
    {
        $query = TransactionDetail::with([
            'transaction.user:id,name'
        ])
        ->where('denda', '>', 0);

        if ($status) {
            $query->where('status_denda', $status);
        }

        return $query->orderBy('status_denda')->get();
    }

    public function updateStatus(TransactionDetail $detail, string $status)
    {
        $detail->update([
            'status_denda' => $status
        ]);

        return $detail;
    }
}

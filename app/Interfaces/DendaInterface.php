<?php

namespace App\Interfaces;

use App\Models\TransactionDetail;

interface DendaInterface
{
    public function getAllWithDenda(?string $status);

    public function updateStatus(TransactionDetail $detail, string $status);
}

<?php

namespace App\Services;

use App\Models\TransactionDetail;
use App\Interfaces\DendaInterface;

class DendaService
{
    protected $repository;

    public function __construct(DendaInterface $repository)
    {
        $this->repository = $repository;
    }

    public function listDenda(?string $status)
    {
        return $this->repository->getAllWithDenda($status);
    }

    public function updateStatusDenda(TransactionDetail $detail, string $status)
    {
        return $this->repository->updateStatus($detail, $status);
    }
}

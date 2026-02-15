<?php

namespace App\Interfaces;

use App\Models\Transactions;

interface TransactionInterface
{
    public function getAll();
    public function getDetails();
    public function findById(string $id);
    public function create(array $data);
    public function update(Transactions $transaction, array $data);
    public function delete(Transactions $transaction);
    public function updateStatus(Transactions $transaksi, string $status): Transactions;
    public function getByUserId(string $userId);
}
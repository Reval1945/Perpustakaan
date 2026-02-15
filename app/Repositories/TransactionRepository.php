<?php

namespace App\Repositories;

use App\Interfaces\TransactionInterface;
use App\Models\TransactionDetail;
use App\Models\Transactions;

class TransactionRepository implements TransactionInterface{

    public function getAll()
    {
        return Transactions::with([
            'user',
            'details.book'
        ])->latest()->get();
    }

    public function getDetails(){
        return TransactionDetail::with([
            'transaction.user'
        ])->latest()->get();
    }

    public function create(array $data): Transactions
    {
        return Transactions::create($data);
    }

    public function findById(string $id): Transactions
    {
        return Transactions::findOrFail($id);
    }

    public function update(Transactions $transaction, array $data): Transactions
    {
        $transaction->update($data);
        return $transaction;
    }

    public function delete (Transactions $transaction){
        $transaction->delete();
    }

    public function updateStatus(Transactions $transaksi, string $status): Transactions
    {
        $transaksi->update(['status' => $status]);
        return $transaksi;
    }
    public function getByUserId(string $userId)
    {
        return Transactions::with('details.book')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

}
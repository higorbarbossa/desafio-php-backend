<?php

namespace App\Repositories;

use App\Models\Transaction;
use App\Models\Wallet;
use App\Repositories\Contracts\TransactionRepositoryInterface;

class TransactionRepository implements TransactionRepositoryInterface
{
    public function createNewTransaction(array $data)
    {
        return Transaction::create($data);
    }
}

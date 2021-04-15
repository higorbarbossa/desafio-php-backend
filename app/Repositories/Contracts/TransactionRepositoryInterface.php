<?php

namespace App\Repositories\Contracts;

use App\Models\Transaction;

interface TransactionRepositoryInterface
{
    public function createNewTransaction(array $data);
}

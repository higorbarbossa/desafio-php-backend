<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\TransferTransactionRequest;
use App\Services\TransactionService;

class TransactionsController extends Controller
{
    protected $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function transaction(TransferTransactionRequest $request)
    {
        return $this->transactionService->handleTransaction($request) ;
    }
}

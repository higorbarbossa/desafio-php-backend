<?php

namespace App\Repositories;

use App\Models\Transaction;
use App\Models\Wallet;
use App\Repositories\Contracts\WalletRepositoryInterface;

class WalletRepository implements WalletRepositoryInterface
{
    public function getUserWalletByUserId(int $userId)
    {
        return Wallet::find($userId);
    }


    public function updateAccountBallance(Wallet $wallet, $newAccountBallance)
    {
        return $wallet->update(['account_balance' => $newAccountBallance]);
    }
}

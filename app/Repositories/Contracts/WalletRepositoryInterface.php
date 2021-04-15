<?php

namespace App\Repositories\Contracts;

use App\Models\Wallet;

interface WalletRepositoryInterface
{
    public function getUserWalletByUserId(int $userId);
    public function updateAccountBallance(Wallet $wallet, $newAccountBallance);
}

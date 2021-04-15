<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\TransactionRepositoryInterface;
use App\Repositories\Contracts\WalletRepositoryInterface;
use App\Enums\TransactionType;
use App\Enums\UserType;
use App\Http\Requests\TransferTransactionRequest;
use GuzzleHttp\Client;

class TransactionService
{
    protected $transactionRepository;
    protected $walletRepository;

    public function __construct(TransactionRepositoryInterface $transactionRepository, WalletRepositoryInterface $walletRepository)
    {
        $this->transactionRepository = $transactionRepository;
        $this->walletRepository = $walletRepository;
    }


    public function handleTransaction(TransferTransactionRequest $request)
    {
        $dataValidated = $request->validated();

        $debitTransaction = [
            'user_id' => $dataValidated['payer'],
            'value' => $dataValidated['value'],
            'transaction_type' => TransactionType::DEBIT,
        ];
        $creditTransaction = [
            'user_id' => $dataValidated['payee'],
            'value' => $dataValidated['value'],
            'transaction_type' => TransactionType::CREDIT,
        ];

        try {
            DB::beginTransaction();
            $payerWallet = $this->walletRepository->getUserWalletByUserId($dataValidated['payer']);

            if (! $this->checkPayerUserType($payerWallet)) {
                throw new \Exception("Ação inválida! Lojistas não podem realizar transferências!");
            }

            if (! $this->hasPayerAccountBallance($payerWallet, $dataValidated['value'])) {
                throw new \Exception("Saldo insuficiente!");
            }

            if (! $this->authorizeTransaction()) {
                throw new \Exception("Transação não autorizada!");
            }

            $debit = $this->transactionRepository->createNewTransaction($debitTransaction);

            $creditTransaction = array_merge($creditTransaction, ['transaction_id' => $debit->id]);
            $credit = $this->transactionRepository->createNewTransaction($creditTransaction);

            $debit->update(['transaction_id' => $credit->id]);

            $payeeWallet = $this->walletRepository->getUserWalletByUserId($dataValidated['payee']);

            $newPayerAccountBalance = $payerWallet->account_balance - $dataValidated['value'];
            $newPayeeAccountBalance = $payeeWallet->account_balance + $dataValidated['value'];

            $this->walletRepository->updateAccountBallance($payerWallet, $newPayerAccountBalance);
            $this->walletRepository->updateAccountBallance($payeeWallet, $newPayeeAccountBalance);

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            // dd($exception);
            return response()->json([
                'status' => 500,
                'message' => $exception->getMessage()
            ], 500);
        }

        return response()->json([
            'status' => 200,
            'message' => 'Transferência realizada com sucesso!'
        ], 200);
    }

    public function checkPayerUserType($wallet)
    {
        if ($wallet->user->user_type == UserType::LEGAL_PERSON) {
            return false;
        }
        return true;
    }

    public function hasPayerAccountBallance($wallet, $value)
    {
        return $wallet->account_balance >= $value;
    }

    public function authorizeTransaction()
    {
        $client = new \GuzzleHttp\Client(['base_uri' => 'https://run.mocky.io']);

        $response = $client->request('GET', '/v3/8fafdd68-a090-496f-8c9a-3442cf30dae6', ['allow_redirects' => false]);

        return $response->getStatusCode() == 200;
    }
}

<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Enums\UserType;

class TransactionControllerTest extends TestCase
{
    /**
     * @test sucesso - transferencia usuario comum para lojista
     *
     * @return void
     */
    public function success_transaction_natural_person_to_legal_person()
    {
        $payer = User::factory()->hasWallet(['account_balance' => 250])->create(['user_type' => UserType::NATURAL_PERSON]);
        $payee = User::factory()->hasWallet(['account_balance' => 100])->create(['user_type' => UserType::LEGAL_PERSON]);

        $payload = [
            'value' =>  175,
            'payer' =>  $payer->id,
            'payee' =>  $payee->id,
        ];

        $response = $this->post(route('transaction.transfer'), $payload);

        $response->assertStatus(200);

        $response->assertJson([
            'status' => 200,
            'message' => 'Transferência realizada com sucesso!'
        ]);
    }

    /**
     * @test sucesso - transferencia usuario comum para usuario comum
     *
     * @return void
     */
    public function success_transaction_natural_person_to_natural_person()
    {
        $payer = User::factory()->hasWallet(['account_balance' => 350])->create(['user_type' => UserType::NATURAL_PERSON]);
        $payee = User::factory()->hasWallet(['account_balance' => 70])->create(['user_type' => UserType::NATURAL_PERSON]);

        $payload = [
            'value' =>  250,
            'payer' =>  $payer->id,
            'payee' =>  $payee->id,
        ];

        $response = $this->post(route('transaction.transfer'), $payload);

        $response->assertStatus(200);

        $response->assertJson([
            'status' => 200,
            'message' => 'Transferência realizada com sucesso!'
        ]);
    }

    /**
     * @test falha - transferencia usuario lojista para usuario comum
     *
     * @return void
     */
    public function fail_transaction_legal_person_to_natural_person()
    {
        $payer = User::factory()->hasWallet(['account_balance' => 350])->create(['user_type' => UserType::LEGAL_PERSON]);
        $payee = User::factory()->hasWallet(['account_balance' => 70])->create(['user_type' => UserType::NATURAL_PERSON]);

        $payload = [
            'value' =>  250,
            'payer' =>  $payer->id,
            'payee' =>  $payee->id,
        ];

        $response = $this->post(route('transaction.transfer'), $payload);

        $response->assertStatus(500);

        $response->assertJson([
            'status' => 500,
            'message' => "Ação inválida! Lojistas não podem realizar transferências!"
        ]);
    }

    /**
     * @test falha - saldo insuficiente
     *
     * @return void
     */
    public function fail_transaction_account_balance_insufficient()
    {
        $payer = User::factory()->hasWallet(['account_balance' => 100])->create(['user_type' => UserType::NATURAL_PERSON]);
        $payee = User::factory()->hasWallet(['account_balance' => 70])->create(['user_type' => UserType::NATURAL_PERSON]);

        $payload = [
            'value' =>  250,
            'payer' =>  $payer->id,
            'payee' =>  $payee->id,
        ];

        $response = $this->post(route('transaction.transfer'), $payload);

        $response->assertStatus(500);

        $response->assertJson([
            'status' => 500,
            'message' => 'Saldo insuficiente!'
        ]);
    }
}

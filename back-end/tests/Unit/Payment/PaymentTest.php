<?php

namespace Tests\Unit\Payment;

use Tests\TestCase;
use App\Modules\Payment\Models\Payment;
use InvalidArgumentException;

/**
 * Class PaymentTest
 *
 * Tests unitaires TDD pour les règlements par paiement mobile (Orange Money, MTN MoMo)
 * ou à la livraison à ZeDuc@Space.
 */
final class PaymentTest extends TestCase
{
    /**
     * Teste l'instanciation valide d'un paiement avec un opérateur mobile agréé.
     */
    public function test_payment_can_be_created_with_valid_provider_and_amount(): void
    {
        $payment = new Payment([
            'order_id'        => 42,
            'provider'        => 'orange_money',
            'amount'          => 3500,
            'status'          => 'en_attente',
            'transaction_ref' => 'OM_TXN_987654',
        ]);

        $this->assertSame(3500, $payment->amount);
        $this->assertSame('orange_money', $payment->provider);
        $this->assertSame('en_attente', $payment->status);
    }

    /**
     * Règle métier : le montant d'un paiement doit être strictement supérieur à zéro.
     */
    public function test_payment_amount_must_be_strictly_positive(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Le montant du paiement doit être supérieur à zéro');

        new Payment([
            'order_id' => 42,
            'provider' => 'mtn_momo',
            'amount'   => 0,
        ]);
    }

    /**
     * Règle métier : seul un opérateur pris en charge par l'application peut être utilisé.
     */
    public function test_payment_provider_must_be_supported(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Opérateur de paiement non pris en charge');

        new Payment([
            'order_id' => 42,
            'provider' => 'fournisseur_inconnu',
            'amount'   => 1500,
        ]);
    }

    /**
     * Cycle de vie : validation du succès de la transaction.
     */
    public function test_payment_can_be_marked_as_successful(): void
    {
        $payment = new Payment([
            'order_id' => 42,
            'provider' => 'mtn_momo',
            'amount'   => 2000,
            'status'   => 'en_attente',
        ]);

        $payment->markAsSuccessful('MOMO_CONFIRM_123');

        $this->assertSame('reussi', $payment->status);
        $this->assertSame('MOMO_CONFIRM_123', $payment->transaction_ref);
    }
}

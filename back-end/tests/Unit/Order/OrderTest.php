<?php

namespace Tests\Unit\Order;

use PHPUnit\Framework\TestCase;
use App\Modules\Order\Models\Order;
use App\Modules\Order\Models\OrderItem;
use DomainException;
use InvalidArgumentException;

/**
 * Class OrderTest
 *
 * Valide les règles métier de la commande :
 * - Calcul du montant total avec réduction
 * - Utilisation des points de fidélité convertis en FCFA
 * - Transitions des états (en_attente -> validee -> prete -> livree)
 */
final class OrderTest extends TestCase
{
    /**
     * Vérifie que le total correspond à la somme des lignes de commande.
     */
    public function test_order_total_is_the_sum_of_items(): void
    {
        $order = new Order(['mode' => 'sur_place']);

        $order->addItem(new OrderItem(['unit_price' => 2000, 'quantity' => 1]));
        $order->addItem(new OrderItem(['unit_price' => 500,  'quantity' => 2]));

        $this->assertSame(3000, $order->calculateTotal());
    }

    /**
     * Règle : la réduction ou les points utilisés réduisent le montant final à payer.
     */
    public function test_order_applies_discount_and_loyalty_deduction(): void
    {
        $order = new Order(['mode' => 'a_emporter']);
        $order->addItem(new OrderItem(['unit_price' => 2500, 'quantity' => 2])); // 5000 FCFA

        // Application d'une remise promotionnelle de 500 FCFA
        $order->applyDiscount(500);

        // Déduction de 200 FCFA équivalents à des points fidélité
        $order->applyPointsDeduction(200);

        $this->assertSame(4300, $order->calculateTotal());
    }

    /**
     * Règle de garde : une réduction ne peut pas excéder le montant du panier.
     */
    public function test_discount_cannot_exceed_subtotal(): void
    {
        $order = new Order(['mode' => 'sur_place']);
        $order->addItem(new OrderItem(['unit_price' => 1000, 'quantity' => 1]));

        $this->expectException(InvalidArgumentException::class);
        $order->applyDiscount(1500);
    }

    /**
     * Règle de validation : impossible de valider une commande qui n'a aucun article.
     */
    public function test_empty_order_cannot_be_confirmed(): void
    {
        $order = new Order(['mode' => 'livraison']);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Impossible de valider une commande vide');

        $order->confirm();
    }
}

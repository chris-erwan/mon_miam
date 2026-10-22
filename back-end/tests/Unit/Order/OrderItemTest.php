<?php

namespace Tests\Unit\Order;

use PHPUnit\Framework\TestCase;
use App\Modules\Order\Models\OrderItem;
use InvalidArgumentException;

/**
 * Class OrderItemTest
 *
 * Valide les calculs unitaires par ligne de commande :
 * sous-total = prix unitaire * quantité, et contrôles d'intégrité.
 */
final class OrderItemTest extends TestCase
{
    /**
     * Vérifie le calcul du sous-total pour un article.
     * Exemple : 2 plats de Plantain-Poulet à 1500 FCFA = 3000 FCFA.
     */
    public function test_subtotal_calculation_is_exact(): void
    {
        $item = new OrderItem([
            'menu_item_id' => 10,
            'unit_price'   => 1500,
            'quantity'     => 2,
        ]);

        $this->assertSame(3000, $item->getSubtotal());
    }

    /**
     * Règle : une ligne ne peut pas avoir une quantité égale à 0 ou négative.
     */
    public function test_quantity_must_be_strictly_positive(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('La quantité doit être supérieure à zéro');

        new OrderItem([
            'menu_item_id' => 10,
            'unit_price'   => 1500,
            'quantity'     => 0,
        ]);
    }
}

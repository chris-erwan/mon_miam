<?php

namespace Tests\Unit\Menu;

use Tests\TestCase;
use App\Modules\Menu\Models\MenuItem;
use InvalidArgumentException;

/**
 * Class MenuItemTest
 *
 * Tests unitaires TDD validant le modèle MenuItem dans l'environnement Laravel.
 */
final class MenuItemTest extends TestCase
{
    /**
     * Vérifie que l'article s'initialise fidèlement aux attributs du diagramme.
     */
public function test_menu_item_attributes_are_correctly_initialized(): void
{
    $item = new MenuItem([
        'id_restaurant' => 1,
        'id_categorie'  => 2,
        'nom'           => 'Riz sauté au poulet',
        'description'   => 'Portion généreuse avec sauce Dibamba',
        'prix'          => 1500,
        'disponible'    => true,
        'plat_du_jour'  => true,
    ]);

    $this->assertSame('Riz sauté au poulet', $item->nom);
    $this->assertSame(1500, $item->prix);
    $this->assertTrue($item->disponible);
    $this->assertTrue($item->plat_du_jour);
}

    /**
     * Règle métier : un prix ne peut pas être strictement négatif.
     */
    public function test_item_price_cannot_be_negative(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Le prix ne peut pas être négatif');

        $item = new MenuItem();
        $item->setPrice(-500);
    }

    /**
     * Règle métier : marquage en rupture de stock.
     */
    public function test_item_can_be_marked_unavailable(): void
    {
       $item = new MenuItem(['nom' => 'Poisson braisé', 'disponible' => true]);

$item->markAsUnavailable();

$this->assertFalse($item->disponible);
    }
}

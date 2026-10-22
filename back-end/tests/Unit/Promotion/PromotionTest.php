<?php

namespace Tests\Unit\Promotion;

use Tests\TestCase;
use App\Modules\Promotions\Models\Promotion;
use InvalidArgumentException;

/**
 * Class PromotionTest
 *
 * Teste la validité temporelle des offres de ZeDuc@Space (matchs de foot, jeux...).
 */
final class PromotionTest extends TestCase
{
    /**
     * Règle : la date de fin d'une promotion ne peut pas être antérieure à la date de début.
     */
    public function test_promotion_end_date_cannot_be_before_start_date(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('La date de fin doit être postérieure à la date de début');

        new Promotion([
            'restaurant_id' => 1,
            'title'         => 'Soirée Champions League',
            'type'          => 'boisson_offerte',
            'starts_at'     => '2026-11-10',
            'ends_at'       => '2026-11-05',
        ]);
    }

    /**
     * Règle : statut actif selon la date du jour.
     */
    public function test_promotion_is_active_check(): void
    {
        $promo = new Promotion([
            'restaurant_id' => 1,
            'title'         => 'Remise rentrée',
            'type'          => 'pourcentage',
            'starts_at'     => '2026-01-01',
            'ends_at'       => '2026-12-31',
        ]);

        $this->assertTrue($promo->isActiveAt('2026-10-22'));
        $this->assertFalse($promo->isActiveAt('2027-01-01'));
    }
}

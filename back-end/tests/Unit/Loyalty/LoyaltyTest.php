<?php

namespace Tests\Unit\Loyalty;

use PHPUnit\Framework\TestCase;
use App\Modules\Auth\Models\User;
use App\Modules\Loyalty\Services\LoyaltyService;

/**
 * Class LoyaltyTest
 *
 * Teste le programme de fidélité et parrainage demandé au cahier des charges
 * pour dynamiser la communauté étudiante de l'UCAC-ICAM.
 */
final class LoyaltyTest extends TestCase
{
    /**
     * Règle : une commande validée rapporte 1 point par tranche de 100 FCFA dépensés.
     */
    public function test_customer_earns_points_proportionally_to_order_amount(): void
    {
        $service = new LoyaltyService();
        $orderAmount = 3500; // 3500 FCFA

        $points = $service->calculatePointsForOrder($orderAmount);

        $this->assertSame(35, $points, "3500 FCFA doivent rapporter 35 points de fidélité.");
    }

    /**
     * Règle : un parrainage réussi débloque un bonus pour le parrain et le filleul.
     */
    public function test_successful_referral_rewards_both_referrer_and_referee(): void
    {
        $service = new LoyaltyService();
        $referrer = new User(['points_solde' => 50]);
$referee  = new User(['points_solde' => 0]);

$service->rewardReferral($referrer, $referee);

$this->assertSame(100, $referrer->points_solde, "Le parrain reçoit 50 points bonus.");
$this->assertSame(20, $referee->points_solde, "Le filleul reçoit 20 points de bienvenue.");
    }
}

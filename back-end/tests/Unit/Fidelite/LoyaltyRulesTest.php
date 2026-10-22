<?php

namespace Tests\Unit\Fidelite;

use App\Modules\Fidelite\Services\LoyaltyRules;
use Tests\TestCase;

class LoyaltyRulesTest extends TestCase
{
    private function rules(): LoyaltyRules
    {
        return new LoyaltyRules();
    }

    public function test_1000_francs_donnent_1_point(): void
    {
        $this->assertSame(0, $this->rules()->pointsForAmount(999));
        $this->assertSame(1, $this->rules()->pointsForAmount(1000));
        $this->assertSame(7, $this->rules()->pointsForAmount(7500));
        $this->assertSame(0, $this->rules()->pointsForAmount(0));
        $this->assertSame(0, $this->rules()->pointsForAmount(-500));
    }

    public function test_15_points_donnent_1000_francs_de_reduction(): void
    {
        $this->assertSame(['discount' => 0, 'points_used' => 0], $this->rules()->discountFor(14, 5000));
        $this->assertSame(['discount' => 1000, 'points_used' => 15], $this->rules()->discountFor(15, 5000));
        $this->assertSame(['discount' => 3000, 'points_used' => 45], $this->rules()->discountFor(47, 5000));
    }

    public function test_la_reduction_ne_depasse_jamais_le_total(): void
    {
        $this->assertSame(['discount' => 1000, 'points_used' => 15], $this->rules()->discountFor(60, 1500));
        $this->assertSame(['discount' => 0, 'points_used' => 0], $this->rules()->discountFor(60, 500));
    }

    public function test_les_points_qui_expirent_le_plus_tot_sont_utilises_en_premier(): void
    {
        $lots = [
            ['id' => 1, 'remaining' => 5, 'expires_at' => '2027-06-01 00:00:00'],
            ['id' => 2, 'remaining' => 5, 'expires_at' => '2027-01-01 00:00:00'],
        ];
        $r = $this->rules()->consume($lots, 7);

        $this->assertSame([2 => 5, 1 => 2], $r['consumed']);
        $this->assertSame(0, $r['missing']);
    }

    public function test_les_points_sans_date_d_expiration_sont_utilises_en_dernier(): void
    {
        $lots = [
            ['id' => 1, 'remaining' => 4, 'expires_at' => null],
            ['id' => 2, 'remaining' => 4, 'expires_at' => '2027-01-01 00:00:00'],
        ];
        $r = $this->rules()->consume($lots, 6);

        $this->assertSame([2 => 4, 1 => 2], $r['consumed']);
    }

    public function test_solde_insuffisant_est_signale(): void
    {
        $r = $this->rules()->consume([['id' => 1, 'remaining' => 3, 'expires_at' => null]], 10);

        $this->assertSame(7, $r['missing']);
    }
}
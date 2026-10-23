<?php

declare(strict_types=1);

namespace App\Modules\Fidelite\Services;

/**
 * Règles de calcul de la fidélité, SANS base de données (facile à tester).
 * Valeurs par défaut = cahier des charges : 1000 F = 1 point, 15 points = 1000 F, 12 mois.
 */
final class LoyaltyRules
{
    public function __construct(
        public readonly int $fcfaPerPoint = 1000,
        public readonly int $pointsPerDiscount = 15,
        public readonly int $discountValue = 1000,
        public readonly int $validityMonths = 12,
        public readonly int $referralBonus = 5,
    ) {
    }

    /** Points gagnés pour un montant payé (7500 F → 7 points). */
    public function pointsForAmount(int $amount): int
    {
        return $amount > 0 ? intdiv($amount, $this->fcfaPerPoint) : 0;
    }

    /**
     * Réduction maximale pour un solde et un total de commande, par paliers entiers
     * (15 points = 1000 F) et jamais au-dessus du total.
     *
     * @return array{discount:int, points_used:int}
     */
    public function discountFor(int $balance, int $total): array
    {
        if ($balance <= 0 || $total <= 0) {
            return ['discount' => 0, 'points_used' => 0];
        }

        $blocks = min(intdiv($balance, $this->pointsPerDiscount), intdiv($total, $this->discountValue));

        return [
            'discount'    => $blocks * $this->discountValue,
            'points_used' => $blocks * $this->pointsPerDiscount,
        ];
    }

    /**
     * Utilise les points en commençant par ceux qui expirent le plus tôt.
     *
     * @param array<int, array{id:int, remaining:int, expires_at:?string}> $lots
     * @return array{lots:array, consumed:array<int,int>, missing:int}
     */
    public function consume(array $lots, int $points): array
    {
        usort($lots, function (array $a, array $b): int {
            return self::compareExpiry($a['expires_at'] ?? null, $b['expires_at'] ?? null) ?: ($a['id'] <=> $b['id']);
        });

        $consumed = [];
        $left = max($points, 0);

        foreach ($lots as &$lot) {
            if ($left <= 0) {
                break;
            }
            $take = min($lot['remaining'], $left);
            if ($take > 0) {
                $lot['remaining'] -= $take;
                $consumed[$lot['id']] = $take;
                $left -= $take;
            }
        }
        unset($lot);

        return ['lots' => $lots, 'consumed' => $consumed, 'missing' => $left];
    }

    /** Les dates les plus proches d'abord ; « jamais » (null) en dernier. */
    private static function compareExpiry(?string $a, ?string $b): int
    {
        if ($a === $b) {
            return 0;
        }
        if ($a === null) {
            return 1;
        }
        if ($b === null) {
            return -1;
        }

        return strcmp($a, $b);
    }
}
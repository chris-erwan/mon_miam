<?php

namespace App\Modules\Loyalty\Services;

use App\Modules\Auth\Models\User;

/**
 * Class LoyaltyService
 *
 * Gère le programme de fidélité et le parrainage pour ZeDuc@Space :
 * - Calcul des points gagnés à chaque commande (1 point pour 100 FCFA dépensés)
 * - Attribution des récompenses lors d'un parrainage réussi
 *
 * @package App\Modules\Loyalty\Services
 */
class LoyaltyService
{
    /**
     * Règle ZeDuc@Space : 1 point de fidélité par tranche de 100 FCFA.
     * Exemple : 3500 FCFA -> 35 points.
     *
     * @param int $orderAmount Montant total de la commande en FCFA
     * @return int Nombre de points gagnés
     */
    public function calculatePointsForOrder(int $orderAmount): int
    {
        if ($orderAmount <= 0) {
            return 0;
        }

        return (int) floor($orderAmount / 100);
    }

    /**
     * Récompense le parrain et le filleul lors d'un parrainage validé.
     * Bonus : +50 points pour le parrain, +20 points pour le filleul.
     *
     * @param User $referrer Le parrain (étudiant existant)
     * @param User $referee  Le filleul (nouvel étudiant)
     * @return void
     */
    public function rewardReferral(User $referrer, User $referee): void
    {
        $referrerBonus = 50;
        $refereeBonus = 20;

        $referrer->loyalty_points = (int) ($referrer->loyalty_points ?? 0) + $referrerBonus;
        $referee->loyalty_points = (int) ($referee->loyalty_points ?? 0) + $refereeBonus;
    }
}

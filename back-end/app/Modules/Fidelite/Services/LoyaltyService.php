<?php

namespace App\Modules\Fidelite\Services;

use App\Modules\Auth\Models\Etudiant;
use App\Modules\Fidelite\Exceptions\InsufficientPointsException;
use App\Modules\Fidelite\Models\LoyaltyTransaction;
use App\Modules\Parametres\Models\Setting;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class LoyaltyService
{
    public function rules(): LoyaltyRules
    {
        return new LoyaltyRules(
            fcfaPerPoint:      Setting::int('taux_points_montant'),
            pointsPerDiscount: Setting::int('seuil_points'),
            discountValue:     Setting::int('valeur_reduction'),
            validityMonths:    Setting::int('validite_points_mois'),
            referralBonus:     Setting::int('points_parrainage'),
        );
    }

    public function balance(Etudiant $user): int
    {
        return (int) Etudiant::whereKey($user->getKey())->value('points_solde');
    }

    /** @return array{discount:int, points_used:int} */
    public function discountFor(Etudiant $user, int $total): array
    {
        return $this->rules()->discountFor($this->balance($user), $total);
    }

    public function earn(Etudiant $user, int $amountPaid, ?int $orderId = null): int
    {
        $points = $this->rules()->pointsForAmount($amountPaid);
        if ($points > 0) {
            $this->grant($user, $points, $orderId, LoyaltyTransaction::TYPE_ORDER);
        }

        return $points;
    }

    public function grant(Etudiant $user, int $points, ?int $orderId, string $type): ?LoyaltyTransaction
    {
        if ($points <= 0) {
            return null;
        }

        return DB::transaction(function () use ($user, $points, $orderId, $type) {
            $transaction = LoyaltyTransaction::create([
                'id_utilisateur'   => $user->getKey(),
                'id_commande'      => $orderId,
                'type_transaction' => $type,
                'points'           => $points,
                'points_restants'  => $points,
                'date_transaction' => now(),
                'date_expiration'  => now()->addMonths($this->rules()->validityMonths),
            ]);
            Etudiant::whereKey($user->getKey())->increment('points_solde', $points);
            $user->refresh();

            return $transaction;
        });
    }

    /** @throws InsufficientPointsException */
    public function spend(Etudiant $user, int $points, ?int $orderId = null): void
    {
        if ($points <= 0) {
            return;
        }

        DB::transaction(function () use ($user, $points, $orderId) {
            $fresh = Etudiant::whereKey($user->getKey())->lockForUpdate()->firstOrFail();
            if ($fresh->points_solde < $points) {
                throw new InsufficientPointsException('Solde de points insuffisant.');
            }

            $lots = LoyaltyTransaction::usableEarnings()
                ->where('id_utilisateur', $fresh->getKey())
                ->where(fn ($q) => $q->whereNull('date_expiration')->orWhere('date_expiration', '>', now()))
                ->lockForUpdate()
                ->get()
                ->map(fn ($l) => [
                    'id'         => $l->id_transaction,
                    'remaining'  => $l->points_restants,
                    'expires_at' => $l->date_expiration?->toDateTimeString(),
                ])->all();

            $result = $this->rules()->consume($lots, $points);
            if ($result['missing'] > 0) {
                throw new InsufficientPointsException('Solde de points insuffisant (points expirés).');
            }

            foreach ($result['consumed'] as $lotId => $taken) {
                LoyaltyTransaction::whereKey($lotId)->decrement('points_restants', $taken);
            }

            LoyaltyTransaction::create([
                'id_utilisateur'   => $fresh->getKey(),
                'id_commande'      => $orderId,
                'type_transaction' => LoyaltyTransaction::TYPE_SPENT,
                'points'           => -$points,
                'points_restants'  => 0,
                'date_transaction' => now(),
            ]);
            $fresh->decrement('points_solde', $points);
        });

        $user->refresh();
    }

    public function expireOldPoints(): int
    {
        $total = 0;

        $expiredIds = LoyaltyTransaction::usableEarnings()
            ->whereNotNull('date_expiration')
            ->where('date_expiration', '<=', now())
            ->pluck('id_transaction');

        foreach ($expiredIds as $id) {
            DB::transaction(function () use ($id, &$total) {
                $lot = LoyaltyTransaction::lockForUpdate()->find($id);
                if (! $lot || $lot->points_restants <= 0) {
                    return;
                }
                $expired = $lot->points_restants;
                $lot->update(['points_restants' => 0]);

                LoyaltyTransaction::create([
                    'id_utilisateur'   => $lot->id_utilisateur,
                    'id_commande'      => $lot->id_commande,
                    'type_transaction' => LoyaltyTransaction::TYPE_EXPIRATION,
                    'points'           => -$expired,
                    'points_restants'  => 0,
                    'date_transaction' => now(),
                ]);
                Etudiant::whereKey($lot->id_utilisateur)->decrement('points_solde', $expired);
                $total += $expired;
            });
        }

        return $total;
    }

    public function history(Etudiant $user): Collection
    {
        return LoyaltyTransaction::where('id_utilisateur', $user->getKey())
            ->orderByDesc('id_transaction')
            ->get();
    }
}
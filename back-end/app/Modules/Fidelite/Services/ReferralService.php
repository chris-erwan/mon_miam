<?php

namespace App\Modules\Fidelite\Services;

use App\Modules\Auth\Models\Etudiant;
use App\Modules\Fidelite\Models\LoyaltyTransaction;
use App\Modules\Fidelite\Models\Referral;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ReferralService
{
    public function __construct(private LoyaltyService $loyalty)
    {
    }

    public function codeOf(Etudiant $user): string
    {
        return $user->code_parrainage;
    }

    /** @throws \DomainException */
    public function useCode(Etudiant $referee, string $code): Referral
    {
        $referrer = Etudiant::where('code_parrainage', strtoupper(trim($code)))->first();

        if (! $referrer) {
            throw new \DomainException('Code de parrainage invalide.');
        }
        if ($referrer->getKey() === $referee->getKey()) {
            throw new \DomainException('Vous ne pouvez pas utiliser votre propre code.');
        }
        if (Referral::where('id_filleul', $referee->getKey())->exists()) {
            throw new \DomainException('Un code de parrainage a déjà été utilisé sur ce compte.');
        }
        if ($this->hasAlreadyOrdered($referee)) {
            throw new \DomainException('Le code doit être utilisé avant la première commande.');
        }

        return Referral::create([
            'id_parrain'      => $referrer->getKey(),
            'id_filleul'      => $referee->getKey(),
            'date_parrainage' => now(),
        ]);
    }

    public function rewardOnFirstOrder(Etudiant $referee, ?int $orderId = null): bool
    {
        return DB::transaction(function () use ($referee, $orderId) {
            $referral = Referral::where('id_filleul', $referee->getKey())
                ->where('recompense_attribuee', false)
                ->lockForUpdate()
                ->first();
            if (! $referral) {
                return false;
            }

            $referrer = Etudiant::find($referral->id_parrain);
            if (! $referrer) {
                return false;
            }

            $bonus = $this->loyalty->rules()->referralBonus;
            $this->loyalty->grant($referrer, $bonus, $orderId, LoyaltyTransaction::TYPE_REFERRAL);
            $referral->update([
                'recompense_attribuee' => true,
                'points_recompense'    => $bonus,
                'date_recompense'      => now(),
            ]);

            return true;
        });
    }

    public function listFor(Etudiant $referrer): Collection
    {
        return Referral::with('referee:id_utilisateur,nom')
            ->where('id_parrain', $referrer->getKey())
            ->latest('date_parrainage')
            ->get();
    }

    public function rewardManually(int $refereeId): bool
    {
        $referee = Etudiant::find($refereeId);

        return $referee ? $this->rewardOnFirstOrder($referee, null) : false;
    }

    private function hasAlreadyOrdered(Etudiant $user): bool
    {
        return Schema::hasTable('commandes')
            && DB::table('commandes')->where('id_client', $user->getKey())->exists();
    }
}
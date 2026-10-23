<?php

namespace App\Modules\Fidelite\Listeners;

use App\Modules\Auth\Events\StudentRegistered;
use App\Modules\Fidelite\Services\ReferralService;

class ApplyReferralCode
{
    public function __construct(private ReferralService $referrals)
    {
    }

    public function handle(StudentRegistered $event): void
    {
        if (empty($event->referralCode)) {
            return;
        }

        try {
            $this->referrals->useCode($event->student, $event->referralCode);
        } catch (\DomainException) {
            // Un code invalide ne doit pas empêcher l'inscription.
        }
    }
}
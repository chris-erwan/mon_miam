<?php

namespace App\Modules\Auth\Services;

use App\Modules\Auth\Models\CookieConsent;
use App\Modules\Auth\Models\User;

class CookieConsentService
{
    public function record(?User $user, string $visitorId, bool $accepted, bool $analytics = false): CookieConsent
    {
        return CookieConsent::create([
            'id_utilisateur'       => $user?->getKey(),
            'identifiant_visiteur' => $visitorId,
            'accepte'              => $accepted,
            'cookies_analytiques'  => $accepted && $analytics,
            'date_consentement'    => now(),
        ]);
    }

    public function latestFor(string $visitorId): ?CookieConsent
    {
        return CookieConsent::where('identifiant_visiteur', $visitorId)
            ->latest('date_consentement')
            ->first();
    }
}
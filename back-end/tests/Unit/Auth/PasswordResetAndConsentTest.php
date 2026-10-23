<?php

namespace Tests\Unit\Auth;

use Tests\TestCase;
use App\Modules\Auth\Models\PasswordReset;
use App\Modules\Auth\Models\CookieConsent;

/**
 * Class PasswordResetAndConsentTest
 *
 * Valide l'expiration des tokens de réinitialisation et le consentement RGPD/Cookies.
 */
final class PasswordResetAndConsentTest extends TestCase
{
    public function test_password_reset_token_expiration_detection(): void
    {
        $reset = new PasswordReset([
            'email'      => 'etudiant@2030.ucac-icam.com',
            'token'      => 'TOKEN_SECRET_XYZ',
            'expires_at' => '2026-10-22 12:00:00',
        ]);

        // Teste à 12:30 (après expiration)
        $this->assertTrue($reset->isExpiredAt('2026-10-22 12:30:00'));

        // Teste à 11:30 (avant expiration)
        $this->assertFalse($reset->isExpiredAt('2026-10-22 11:30:00'));
    }

    public function test_cookie_consent_recording(): void
    {
       $consent = new CookieConsent([
    'id_utilisateur' => 15,
    'accepte'        => true,
]);

$this->assertTrue($consent->accepte);
$this->assertSame(15, $consent->id_utilisateur);
    }
}

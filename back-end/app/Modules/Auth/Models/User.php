<?php

namespace App\Modules\Auth\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

/**
 * Classe PARENT du diagramme (module Auth).
 * Une seule table `utilisateurs` ; la colonne `id_role` (table `roles`) distingue
 * Étudiant / Employé / Gérant / Administrateur.
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public const ROLE_ETUDIANT = 1;
    public const ROLE_EMPLOYE  = 2;
    public const ROLE_GERANT   = 3;
    public const ROLE_ADMIN    = 4;
    public const ROLES = [self::ROLE_ETUDIANT, self::ROLE_EMPLOYE, self::ROLE_GERANT, self::ROLE_ADMIN];

    protected $table = 'utilisateurs';

    protected $primaryKey = 'id_utilisateur';

    public $timestamps = false;

    protected $fillable = [
        'id_role', 'id_restaurant', 'nom', 'email', 'mot_de_passe_hash',
        'telephone', 'localisation', 'points_solde', 'code_parrainage', 'statut_compte',
    ];

    protected $hidden = ['mot_de_passe_hash', 'jeton_reinitialisation'];

    protected function casts(): array
    {
        return [
            'id_role'           => 'integer',
            'id_restaurant'     => 'integer',
            'points_solde'      => 'integer',
            'mot_de_passe_hash' => 'hashed',
            'date_inscription'  => 'datetime',
            'expiration_jeton'  => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (User $user) {
            $user->code_parrainage ??= self::newReferralCode();
        });
    }

    private static function newReferralCode(): string
    {
        do {
            $code = strtoupper(Str::random(8));
        } while (self::query()->where('code_parrainage', $code)->exists());

        return $code;
    }

    /** Le mot de passe est dans la colonne `mot_de_passe_hash`. */
    public function getAuthPassword(): string
    {
        return $this->mot_de_passe_hash;
    }

    public function getAuthPasswordName(): string
    {
        return 'mot_de_passe_hash';
    }

    /** Les jetons Sanctum sont toujours rattachés à User, même pour les classes filles. */
    public function getMorphClass(): string
    {
        return self::class;
    }

    protected static function newFactory()
    {
        return \Database\Factories\UserFactory::new();
    }

    // ---------- Relations ----------
    public function cookieConsents(): HasMany
    {
        return $this->hasMany(CookieConsent::class, 'id_utilisateur');
    }

    /** Pour le personnel : le restaurant où il travaille (module Restaurants, membre Arthur). */
    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Restaurants\Models\Restaurant::class, 'id_restaurant');
    }

    // ---------- Aides sur le rôle ----------
    public function hasRole(int ...$roles): bool
    {
        return in_array($this->id_role, $roles, true);
    }

    public function isStaff(): bool
    {
        return $this->hasRole(self::ROLE_EMPLOYE, self::ROLE_GERANT, self::ROLE_ADMIN);
    }
}
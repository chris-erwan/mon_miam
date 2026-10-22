<?php

namespace App\Modules\Auth\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * Classe PARENT du diagramme (module Auth).
 * Une seule table `users` ; la colonne `role` distingue Étudiant / Employé / Gérant / Admin.
 * Les classes filles (Etudiant, Employe, Gerant, Admin) héritent de celle-ci.
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public const ROLE_ETUDIANT = 'etudiant';
    public const ROLE_EMPLOYE  = 'employe';
    public const ROLE_GERANT   = 'gerant';
    public const ROLE_ADMIN    = 'admin';
    public const ROLES = [self::ROLE_ETUDIANT, self::ROLE_EMPLOYE, self::ROLE_GERANT, self::ROLE_ADMIN];

    protected $table = 'users';

    protected $fillable = [
        'name', 'email', 'password', 'phone', 'location', 'role',
        'loyalty_points', 'referral_code', 'referrer_id', 'restaurant_id', 'active',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'password'       => 'hashed',
            'active'         => 'boolean',
            'loyalty_points' => 'integer',
        ];
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
        return $this->hasMany(CookieConsent::class, 'user_id');
    }

    /** Pour le personnel : le restaurant où il travaille (module Restaurants, membre Arthur). */
    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Restaurants\Models\Restaurant::class, 'restaurant_id');
    }

    // ---------- Aides sur le rôle ----------
    public function hasRole(string ...$roles): bool
    {
        return in_array($this->role, $roles, true);
    }

    public function isStaff(): bool
    {
        return $this->hasRole(self::ROLE_EMPLOYE, self::ROLE_GERANT, self::ROLE_ADMIN);
    }
}
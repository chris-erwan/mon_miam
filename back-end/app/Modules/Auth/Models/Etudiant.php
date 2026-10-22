<?php

namespace App\Modules\Auth\Models;

use App\Shared\Enums\Role;
use Illuminate\Database\Eloquent\Builder;

class Etudiant extends User
{
    protected $fillable = [
        'nom',
        'email',
        'mot_de_passe_hash',
        'telephone',
        'localisation',
    ];

    protected static function booted(): void
    {
        parent::booted();

        static::addGlobalScope('roles', function (Builder $query) {
            $query->whereIn('id_role', array_map(fn (Role $role) => $role->value, static::roles()));
        });

        static::creating(function (Etudiant $etudiant) {
            $etudiant->id_role ??= static::defaultRole()->value;
        });
    }

    /** @return array<int, Role> */
    public static function roles(): array
    {
        return [Role::Student];
    }

    public static function defaultRole(): Role
    {
        return Role::Student;
    }
}
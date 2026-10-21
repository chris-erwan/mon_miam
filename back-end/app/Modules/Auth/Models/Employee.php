<?php

namespace App\Modules\Auth\Models;

use App\Shared\Enums\Role;
use Illuminate\Database\Eloquent\Builder;

class Employee extends User
{
    protected $fillable = [
        'nom',
        'email',
        'mot_de_passe_hash',
        'telephone',
        'id_restaurant',
    ];

    protected static function booted(): void
    {
        parent::booted();

        static::addGlobalScope('roles', function (Builder $query) {
            $query->whereIn('id_role', array_map(fn (Role $role) => $role->value, static::roles()));
        });

        static::creating(function (Employee $employee) {
            $employee->id_role ??= static::defaultRole()->value;
        });
    }

    /** @return array<int, Role> */
    public static function roles(): array
    {
        return [Role::Employee];
    }

    public static function defaultRole(): Role
    {
        return Role::Employee;
    }

    public function worksIn(int $restaurantId): bool
    {
        return $this->id_restaurant !== null
            && (int) $this->id_restaurant === $restaurantId;
    }
}
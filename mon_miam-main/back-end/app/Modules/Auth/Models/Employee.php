<?php

namespace App\Modules\Auth\Models;

use App\Shared\Enums\Role;
use Illuminate\Database\Eloquent\Builder;

class Employee extends User
{
    protected $table = 'users';

    protected $fillable = ['name', 'email', 'password', 'phone', 'restaurant_id'];

    protected static function booted(): void
    {
        parent::booted();

        static::addGlobalScope('roles', function (Builder $query) {
            $query->whereIn('role', array_map(fn (Role $role) => $role->value, static::roles()));
        });

        static::creating(function (Employee $employee) {
            $employee->role ??= static::defaultRole()->value;
        });
    }

    /** @return array<int, Role> */
    public static function roles(): array { return [Role::Employee]; }

    public static function defaultRole(): Role { return Role::Employee; }

    public function worksIn(int $restaurantId): bool
    {
        return $this->restaurant_id !== null
            && (int) $this->restaurant_id === $restaurantId;
    }
}

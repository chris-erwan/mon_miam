<?php

namespace App\Modules\Auth\Models;

use App\Shared\Enums\Role;

class Manager extends Employee
{
    /** @return array<int, Role> */
    public static function roles(): array
    {
        return [Role::Manager];
    }

    public static function defaultRole(): Role
    {
        return Role::Manager;
    }
}
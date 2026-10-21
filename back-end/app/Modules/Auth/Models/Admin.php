<?php

namespace App\Modules\Auth\Models;

use App\Shared\Enums\Role;

class Admin extends Manager
{
    /** @return array<int, Role> */
    public static function roles(): array
    {
        return [Role::Admin];
    }

    public static function defaultRole(): Role
    {
        return Role::Admin;
    }

    public function worksIn(int $restaurantId): bool
    {
        return true;
    }
}
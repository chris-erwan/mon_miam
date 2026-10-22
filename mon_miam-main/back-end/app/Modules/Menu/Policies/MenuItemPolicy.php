<?php

namespace App\Modules\Menu\Policies;

use App\Modules\Menu\Models\MenuItem;
use App\Shared\Enums\Role;
use Illuminate\Contracts\Auth\Authenticatable;

class MenuItemPolicy
{
    public function create(Authenticatable $user, int $restaurantId): bool
    {
        return $this->canManage($user, $restaurantId);
    }

    public function update(Authenticatable $user, MenuItem $menuItem): bool
    {
        return $this->canManage($user, $menuItem->restaurant_id);
    }

    public function delete(Authenticatable $user, MenuItem $menuItem): bool
    {
        return $this->canManage($user, $menuItem->restaurant_id);
    }

    private function canManage(Authenticatable $user, int $restaurantId): bool
    {
        if ($user->role === Role::Admin->value) {
            return true;
        }

        return in_array($user->role, [Role::Employee->value, Role::Manager->value], true)
            && $user->restaurant_id !== null
            && (int) $user->restaurant_id === $restaurantId;
        }
}
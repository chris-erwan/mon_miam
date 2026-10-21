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
        return $this->canManage($user, $menuItem->category?->id_restaurant);
    }

    public function delete(Authenticatable $user, MenuItem $menuItem): bool
    {
        return $this->canManage($user, $menuItem->category?->id_restaurant);
    }

    private function canManage(Authenticatable $user, ?int $restaurantId): bool
    {
        if ($user->id_role === Role::Admin->value) {
            return true;
        }

        return in_array($user->id_role, [Role::Employee->value, Role::Manager->value], true)
            && $restaurantId !== null
            && $user->id_restaurant !== null
            && (int) $user->id_restaurant === $restaurantId;
    }
}
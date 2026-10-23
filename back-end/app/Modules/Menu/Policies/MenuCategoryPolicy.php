<?php

namespace App\Modules\Menu\Policies;

use App\Modules\Menu\Models\MenuCategory;
use App\Shared\Enums\Role;
use Illuminate\Contracts\Auth\Authenticatable;

class MenuCategoryPolicy
{
    public function create(Authenticatable $user, int $restaurantId): bool
    {
        return $this->canManage($user, $restaurantId);
    }

    public function update(Authenticatable $user, MenuCategory $menuCategory): bool
    {
        return $this->canManage($user, $menuCategory->id_restaurant);
    }

    public function delete(Authenticatable $user, MenuCategory $menuCategory): bool
    {
        return $this->canManage($user, $menuCategory->id_restaurant);
    }

    private function canManage(Authenticatable $user, int $restaurantId): bool
    {
        if ($user->id_role === Role::Admin->value) {
            return true;
        }

        return $user->id_role === Role::Manager->value
            && $user->id_restaurant !== null
            && (int) $user->id_restaurant === $restaurantId;
    }
}
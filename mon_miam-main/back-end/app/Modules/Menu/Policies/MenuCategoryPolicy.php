<?php

namespace App\Modules\Menu\Policies;

use App\Modules\Menu\Models\MenuCategory;
use App\Shared\Enums\Role;
use Illuminate\Contracts\Auth\Authenticatable;

class MenuCategoryPolicy
{
    public function create(Authenticatable $user): bool
    {
        return $this->isAdmin($user);
    }

    public function update(Authenticatable $user, MenuCategory $menuCategory): bool
    {
        return $this->isAdmin($user);
    }

    public function delete(Authenticatable $user, MenuCategory $menuCategory): bool
    {
        return $this->isAdmin($user);
    }

    private function isAdmin(Authenticatable $user): bool
    {
        return $user->role === Role::Admin->value;
    }
}

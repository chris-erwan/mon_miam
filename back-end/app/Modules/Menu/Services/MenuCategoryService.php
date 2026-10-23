<?php

namespace App\Modules\Menu\Services;

use App\Modules\Menu\Models\MenuCategory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;

class MenuCategoryService
{
    public function list(array $filters): Collection
    {
        return MenuCategory::query()
            ->when(isset($filters['id_restaurant']), fn ($query) => $query->where('id_restaurant', $filters['id_restaurant']))
            ->orderBy('ordre_affichage')
            ->orderBy('libelle')
            ->get();
    }

    public function items(MenuCategory $menuCategory): Collection
    {
        return $menuCategory->menuItems()->orderBy('nom')->get();
    }

    public function create(array $data): MenuCategory
    {
        return MenuCategory::create($data);
    }

    public function update(MenuCategory $menuCategory, array $data): MenuCategory
    {
        $menuCategory->update($data);

        return $menuCategory;
    }

    public function delete(MenuCategory $menuCategory): void
    {
        if ($menuCategory->menuItems()->exists()) {
            throw ValidationException::withMessages([
                'categorie' => 'Cette catégorie contient encore des plats.',
            ]);
        }

        $menuCategory->delete();
    }
}
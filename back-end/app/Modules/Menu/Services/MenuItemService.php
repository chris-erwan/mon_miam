<?php

namespace App\Modules\Menu\Services;

use App\Modules\Menu\Models\MenuItem;
use Illuminate\Pagination\LengthAwarePaginator;

class MenuItemService
{
    public function list(array $filters): LengthAwarePaginator
    {
        return MenuItem::query()
            ->when(
                isset($filters['id_restaurant']),
                fn ($query) => $query->whereHas(
                    'category',
                    fn ($category) => $category->where('id_restaurant', $filters['id_restaurant'])
                )
            )
            ->when(isset($filters['id_categorie']), fn ($query) => $query->where('id_categorie', $filters['id_categorie']))
            ->when(isset($filters['disponible']), fn ($query) => $query->where('disponible', $filters['disponible']))
            ->when(isset($filters['plat_du_jour']), fn ($query) => $query->where('plat_du_jour', $filters['plat_du_jour']))
            ->orderBy('nom')
            ->paginate(15);
    }

    public function create(array $data): MenuItem
    {
        return MenuItem::create($data);
    }

    public function update(MenuItem $menuItem, array $data): MenuItem
    {
        $menuItem->update($data);

        return $menuItem;
    }

    public function delete(MenuItem $menuItem): void
    {
        $menuItem->delete();
    }
}
<?php

namespace App\Modules\Menu\Services;

use App\Modules\Menu\Models\MenuItem;
use Illuminate\Pagination\LengthAwarePaginator;

class MenuItemService
{
    public function list(array $filters): LengthAwarePaginator
    {
        return MenuItem::query()
            ->when(isset($filters['restaurant_id']), fn ($query) => $query->where('restaurant_id', $filters['restaurant_id']))
            ->when(isset($filters['category_id']), fn ($query) => $query->where('category_id', $filters['category_id']))
            ->when(isset($filters['available']), fn ($query) => $query->where('available', $filters['available']))
            ->when(isset($filters['is_daily']), fn ($query) => $query->where('is_daily', $filters['is_daily']))
            ->orderBy('name')
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
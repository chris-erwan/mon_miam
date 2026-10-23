<?php

namespace App\Modules\Restaurants\Services;

use App\Modules\Restaurants\Models\Restaurant;
use Illuminate\Support\Collection;

class RestaurantService
{
    /** @return Collection<int, Restaurant> */
    public function list(): Collection
    {
        return Restaurant::query()->orderBy('nom')->get();
    }

    public function find(int $id): Restaurant
    {
        return Restaurant::findOrFail($id);
    }
}
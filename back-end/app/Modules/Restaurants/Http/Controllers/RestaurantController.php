<?php

namespace App\Modules\Restaurants\Http\Controllers;

use App\Modules\Restaurants\Services\RestaurantService;
use Illuminate\Http\JsonResponse;

class RestaurantController
{
    public function __construct(private readonly RestaurantService $service)
    {
    }

    public function index(): JsonResponse
    {
        return response()->json($this->service->list());
    }

    public function show(int $restaurantId): JsonResponse
    {
        return response()->json($this->service->find($restaurantId));
    }
}
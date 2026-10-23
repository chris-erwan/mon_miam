<?php

use App\Modules\Restaurants\Http\Controllers\RestaurantController;
use Illuminate\Support\Facades\Route;

Route::middleware('api')->prefix('api')->group(function () {
    Route::get('restaurants', [RestaurantController::class, 'index']);
    Route::get('restaurants/{restaurantId}', [RestaurantController::class, 'show']);
});
<?php

use App\Modules\Menu\Http\Controllers\MenuCategoryController;
use App\Modules\Menu\Http\Controllers\MenuItemController;
use Illuminate\Support\Facades\Route;

Route::middleware('api')->prefix('api')->group(function () {
    Route::get('menu-items', [MenuItemController::class, 'index']);
    Route::get('menu-items/{menuItem}', [MenuItemController::class, 'show']);

    Route::get('menu-categories', [MenuCategoryController::class, 'index']);
    Route::get('menu-categories/{menuCategory}', [MenuCategoryController::class, 'show']);
    Route::get('menu-categories/{menuCategory}/items', [MenuCategoryController::class, 'items']);
});

Route::middleware(['api', 'auth:sanctum'])->prefix('api')->group(function () {
    Route::post('menu-items', [MenuItemController::class, 'store']);
    Route::patch('menu-items/{menuItem}', [MenuItemController::class, 'update']);
    Route::delete('menu-items/{menuItem}', [MenuItemController::class, 'destroy']);

    Route::post('menu-categories', [MenuCategoryController::class, 'store']);
    Route::patch('menu-categories/{menuCategory}', [MenuCategoryController::class, 'update']);
    Route::delete('menu-categories/{menuCategory}', [MenuCategoryController::class, 'destroy']);
});
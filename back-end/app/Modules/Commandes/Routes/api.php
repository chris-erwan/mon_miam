<?php

use App\Modules\Commandes\Http\Controllers\OrderController;
use App\Modules\Commandes\Http\Controllers\StaffOrderController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api', 'auth:sanctum'])->prefix('api')->group(function () {

    Route::middleware('role:student')->group(function () {
        Route::post('orders', [OrderController::class, 'store']);
        Route::get('orders', [OrderController::class, 'index']);
        Route::get('orders/{orderId}', [OrderController::class, 'show']);
        Route::post('orders/{orderId}/cancel', [OrderController::class, 'cancel']);
    });

    Route::middleware('role:employee,manager,admin')->group(function () {
        Route::post('employee/orders/{orderId}/validate', [StaffOrderController::class, 'validateOrder']);
        Route::patch('employee/orders/{orderId}/status', [StaffOrderController::class, 'changeStatus']);
    });

    Route::middleware('role:manager,admin')->group(function () {
        Route::get('manager/orders', [StaffOrderController::class, 'index']);
    });
});
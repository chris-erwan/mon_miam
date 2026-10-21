<?php

use App\Modules\Auth\Http\Controllers\EmployeeController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api', 'auth:sanctum'])
    ->prefix('api/employee')
    ->group(function () {
        Route::post('orders/{orderId}/validate', [EmployeeController::class, 'validateOrder']);
        Route::patch('orders/{orderId}/status', [EmployeeController::class, 'changeOrderStatus']);
        Route::patch('menu-items/{menuItemId}', [EmployeeController::class, 'updateMenu']);
        Route::post('complaints/{complaintId}/reply', [EmployeeController::class, 'replyToComplaint']);
        Route::get('stats/weekly', [EmployeeController::class, 'weeklyStats']);
    });
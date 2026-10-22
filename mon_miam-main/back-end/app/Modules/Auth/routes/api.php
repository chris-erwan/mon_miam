<?php

use App\Modules\Auth\Http\Controllers\AdminController;
use App\Modules\Auth\Http\Controllers\EmployeeController;
use App\Modules\Auth\Http\Controllers\ManagerController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api', 'auth:sanctum'])->group(function () {

    Route::prefix('api/employee')->group(function () {
        Route::post('orders/{orderId}/validate', [EmployeeController::class, 'validateOrder']);
        Route::patch('orders/{orderId}/status', [EmployeeController::class, 'changeOrderStatus']);
        Route::patch('menu-items/{menuItemId}', [EmployeeController::class, 'updateMenu']);
        Route::post('complaints/{complaintId}/reply', [EmployeeController::class, 'replyToComplaint']);
        Route::get('stats/weekly', [EmployeeController::class, 'weeklyStats']);
    });

    Route::prefix('api/manager')->group(function () {
        Route::get('orders', [ManagerController::class, 'superviseOrders']);
        Route::post('employees', [ManagerController::class, 'createEmployee']);
        Route::post('complaints/{complaintId}/validate', [ManagerController::class, 'validateComplaint']);
        Route::get('stats/general', [ManagerController::class, 'generalStats']);
    });

    Route::prefix('api/admin')->group(function () {
        Route::get('employees', [AdminController::class, 'employees']);
        Route::post('employees', [AdminController::class, 'createEmployee']);
        Route::delete('employees/{employeeId}', [AdminController::class, 'deleteEmployee']);
        Route::get('stats/realtime', [AdminController::class, 'realTimeStats']);
    });
});
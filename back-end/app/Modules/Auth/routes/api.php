<?php

use App\Modules\Auth\Http\Controllers\AdminController;
use App\Modules\Auth\Http\Controllers\EmployeeController;
use App\Modules\Auth\Http\Controllers\ManagerController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api', 'auth:sanctum'])->group(function () {

    Route::prefix('api/employee')->middleware('role:employee,manager,admin')->group(function () {
        Route::post('complaints/{complaintId}/reply', [EmployeeController::class, 'replyToComplaint']);
        Route::get('stats/weekly', [EmployeeController::class, 'weeklyStats']);
    });

    Route::prefix('api/manager')->middleware('role:manager,admin')->group(function () {
        Route::post('employees', [ManagerController::class, 'createEmployee']);
        Route::post('complaints/{complaintId}/validate', [ManagerController::class, 'validateComplaint']);
        Route::get('stats/general', [ManagerController::class, 'generalStats']);
    });

    Route::prefix('api/admin')->middleware('role:admin')->group(function () {
        Route::get('employees', [AdminController::class, 'employees']);
        Route::post('employees', [AdminController::class, 'createEmployee']);
        Route::delete('employees/{employeeId}', [AdminController::class, 'deleteEmployee']);
        Route::get('stats/realtime', [AdminController::class, 'realTimeStats']);
    });
});
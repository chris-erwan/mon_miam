<?php

use App\Modules\Fidelite\Http\Controllers\LoyaltyController;
use App\Modules\Fidelite\Http\Controllers\ReferralController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api', 'auth:sanctum', 'role:student'])->prefix('api')->group(function () {
    Route::get('loyalty/balance', [LoyaltyController::class, 'balance']);
    Route::get('loyalty/history', [LoyaltyController::class, 'history']);
    Route::get('referrals', [ReferralController::class, 'index']);
    Route::post('referrals/use', [ReferralController::class, 'use']);
});
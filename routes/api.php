<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ZomatoController;
use App\Http\Controllers\TelegramController;

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('verify-2fa', [AuthController::class, 'verify2FA']);
});

Route::middleware(['jwt.auth', '2fa'])->group(function () {
    Route::get('zomato/search', [ZomatoController::class, 'search']);
    Route::get('zomato/menu/{id}', [ZomatoController::class, 'menu']);
    Route::get('zomato/review/{id}', [ZomatoController::class, 'review']);

    Route::get('logs', [AuthController::class, 'logs']);
});

Route::post('telegram/hook', [TelegramController::class, 'handle']);

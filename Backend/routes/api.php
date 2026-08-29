<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Identity\Presentation\UserController;
use App\Modules\Sales\Presentation\ProductController;
use App\Modules\Sales\Presentation\OrderController;

Route::prefix('v1')->group(function () {
    // Identity Module Routes
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index']);
        Route::get('/{id}', [UserController::class, 'show']);
        Route::post('/', [UserController::class, 'store']);
        Route::put('/{id}', [UserController::class, 'update']);
        Route::delete('/{id}', [UserController::class, 'destroy']);
    });

    // Sales Module Routes
    Route::prefix('products')->group(function () {
        Route::get('/', [ProductController::class, 'index']);
        Route::get('/{id}', [ProductController::class, 'show']);
        Route::post('/', [ProductController::class, 'store']);
        Route::put('/{id}', [ProductController::class, 'update']);
    });

    Route::prefix('orders')->group(function () {
        Route::post('/', [OrderController::class, 'store']);
        Route::put('/{id}/status', [OrderController::class, 'updateStatus']);
    });
});

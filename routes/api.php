<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

// Product Crud
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{product}', [ProductController::class, 'show']);

Route::middleware('auth:api')->group(function () {
    Route::post('/products', [ProductController::class, 'store']);
    Route::patch('/products/{product}', [ProductController::class, 'update']);
});

Route::delete('/products/{product}', [ProductController::class, 'destroy'])
    ->middleware(['auth:api', 'role:admin']);

// Category Crud
Route::get('/categories', [CategoryController::class, 'index']);
Route::post('/categories', [CategoryController::class, 'store']);
Route::get('/categories/{category}', [CategoryController::class, 'show']);

Route::middleware('auth:api')->group(function () {
    Route::patch('/categories/{category}', [CategoryController::class, 'update']);
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy']);
});

// User Crud
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
});

// Notification

Route::middleware('auth:api')->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index']);

    Route::get(
        '/notifications/unread',
        [NotificationController::class, 'unread']
    );

    Route::patch(
        '/notifications/{id}/read',
        [NotificationController::class, 'markAsRead']
    );

    Route::patch(
        '/notifications/read-all',
        [NotificationController::class, 'markAllAsRead']
    );

    Route::delete(
        '/notifications/{id}',
        [NotificationController::class, 'destroy']
    );
});

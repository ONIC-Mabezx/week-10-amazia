<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login'])
    ->middleware('throttle:6,1');

Route::apiResource('products', ProductController::class)
    ->only(['index', 'show'])
    ->names('api.products');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('user', fn (Request $request) => $request->user());
    Route::post('logout', [AuthController::class, 'logout']);

    Route::apiResource('products', ProductController::class)
        ->except(['index', 'show'])
        ->names('api.products');
});

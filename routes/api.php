<?php

use App\Http\Controllers\AnalyzeController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::post('analyze', AnalyzeController::class);

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('products', ProductController::class);
});
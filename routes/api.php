<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\SchoolController;


Route::prefix('v1')->group(function () {
    Route::get('/health', fn () => ['status' => 'ok']);
});


Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    Route::apiResource('schools', SchoolController::class);
    Route::patch('schools/{school}/toggle-status', [SchoolController::class, 'toggleStatus']);
});

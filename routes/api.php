<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\StoreDataController;

// keep default user route as-is
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// header data – no throttle middleware
Route::get('/header-data', [StoreDataController::class, 'header'])
    ->withoutMiddleware('throttle:api');

<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [AuthController::class, 'login']);
Route::apiResource('users', UserController::class);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');


// Route::middleware('auth:sanctum')->group(function () {
//     Route::apiResource('users', UserController::class);
// });

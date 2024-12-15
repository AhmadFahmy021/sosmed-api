<?php

use App\Http\Controllers\StoriesController;
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

Route::apiResource('postingan', PostinganController::class);

Route::apiResource('users', UserController::class);

Route::apiResource('stories', StoriesController::class);




Route::get('postingan/like/{postingan}', [PostinganController::class, "liked"]);
Route::get('postingan/unlike/{postingan}', [PostinganController::class, "unliked"]);

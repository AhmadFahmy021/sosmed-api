<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PostinganController;
use App\Http\Controllers\StoriesController;
use App\Http\Controllers\UserController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('postingan', PostinganController::class);

Route::apiResource('users', UserController::class);

Route::apiResource('stories', StoriesController::class);



Route::get('postingan/like/{postingan}', [PostinganController::class, "liked"]);
Route::get('postingan/unlike/{postingan}', [PostinganController::class, "unliked"]);

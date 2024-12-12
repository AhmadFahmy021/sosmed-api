<?php

use App\Http\Controllers\PostinganController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('postingan', PostinganController::class);





Route::get('postingan/like/{postingan}', [PostinganController::class, "liked"]);
Route::get('postingan/unlike/{postingan}', [PostinganController::class, "unliked"]);

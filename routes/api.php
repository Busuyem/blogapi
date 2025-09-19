<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PostController;

Route::post('register', [AuthController::class, 'register']);
Route::post('login',    [AuthController::class, 'login']);

// public list (supports ?q=search&per_page=10&page=2)
Route::get('posts', [PostController::class, 'index']);

 // public show
Route::get('posts/{id}', [PostController::class, 'show']);       

Route::group(['middleware' => ['jwt.auth']], function() {
    Route::post('posts', [PostController::class, 'store']);
    Route::get('posts/{id}', [PostController::class, 'show']);
    Route::put('posts/{id}', [PostController::class, 'update']);
    Route::patch('posts/{id}', [PostController::class, 'update']);
    Route::delete('posts/{id}', [PostController::class, 'destroy']);

    Route::get('user/me', [AuthController::class, 'me']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('user/posts', [PostController::class, 'myPosts']);
});

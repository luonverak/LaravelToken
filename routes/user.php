<?php

use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/user', [UserController::class, 'getUser']);
    Route::post('/logout', [UserController::class, 'logout']);
});

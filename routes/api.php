<?php

use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;
use Illuminate\Auth\Middleware\Authorize;

use App\Http\Controllers\API\RegisterController;

Route::controller(RegisterController::class)->group(function(){
    Route::post('register', 'register');
    Route::post('login', 'login');
});


Route::middleware('auth:sanctum')->prefix('tasks')->group(function () {
    Route::post('/', [TaskController::class, 'store']);
    Route::get('/', [TaskController::class, 'index']);
    Route::get('/{task}', [TaskController::class, 'show']);
    Route::put('/{task}', [TaskController::class, 'update']);
    Route::delete('/{task}', [TaskController::class, 'destroy']);
});
        

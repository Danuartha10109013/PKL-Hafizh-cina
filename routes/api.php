<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\LeavesController;

Route::middleware('api.key')->group(function () {
    Route::get('users', [UserController::class, 'index']); // Get all users
    Route::post('users', [UserController::class, 'store']); // Create a new user
    Route::get('users/{id}', [UserController::class, 'show']); // Get a specific user
    Route::put('users/{id}', [UserController::class, 'update']); // Update a user
    Route::delete('users/{id}', [UserController::class, 'destroy']); // Delete a user
});

Route::get('/qrcode/leaves/print/{id}', [LeavesController::class, 'printall'])->name('leaves.printall');


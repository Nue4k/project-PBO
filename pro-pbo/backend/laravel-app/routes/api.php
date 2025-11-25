<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;
// Ganti ProductController dengan controller yang relevan nanti
// use App\Http\Controllers\Api\ProductController;

// Route Otentikasi
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Route debug
Route::get('/debug-register', function () {
    return response()->json(['message' => 'Debug register endpoint is working']);
});

// Route yang memerlukan otentikasi (gunakan middleware sanctum)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // Route untuk profil mahasiswa
    Route::get('/profile/student', [App\Http\Controllers\Api\Profile\StudentProfileController::class, 'show']);
    Route::put('/profile/student', [App\Http\Controllers\Api\Profile\StudentProfileController::class, 'update']);

    // Tambahkan route lain yang memerlukan login di sini
    // Contoh:
    // Route::apiResource('jobs', JobController::class);
    // Route::apiResource('applications', ApplicationController::class);
    // Route::apiResource('documents', DocumentController::class);
    // Route::get('/profile', [ProfileController::class, 'show']); // Bisa untuk student atau company tergantung role user
});
<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('converter');
    }
    return view('welcome');
});

// Authentication Routes
Route::get('login', [LoginController::class, 'redirectToGoogle'])->name('login');
Route::get('login/google', [LoginController::class, 'redirectToGoogle'])->name('login.google');
Route::get('login/google/callback', [LoginController::class, 'handleGoogleCallback']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// Admin Routes
Route::get('admin/settings', function () {
    return view('admin.settings');
})->name('admin.settings');

// Protected Routes
Route::middleware('auth')->group(function () {
    Route::get('/converter', function () {
        return view('converter');
    })->name('converter');
});

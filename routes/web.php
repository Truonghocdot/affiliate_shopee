<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Authentication Routes
Route::get('login/google', [LoginController::class, 'redirectToGoogle'])->name('login.google');
Route::get('login/google/callback', [LoginController::class, 'handleGoogleCallback']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// Admin Routes
Route::get('admin/settings', function () {
    return view('admin.settings');
})->name('admin.settings');

// Protected Routes
Route::middleware('auth')->group(function () {
    // The Livewire component will be used inside a layout or welcome page.
    // For now, let's keep it simple and just use the welcome page.
});

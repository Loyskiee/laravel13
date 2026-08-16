<?php

use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
    Route::resource('categories', CategoryController::class)->except(['edit']);
});

require __DIR__.'/settings.php';

<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
    Route::resource('categories', CategoryController::class)->except(['edit']);
    Route::resource('tasks', TaskController::class)->except(['edit']);
    Route::resource('products', ProductController::class)->except(['edit']);
    Route::get('inventory-history', fn () => \Inertia\Inertia::render('InventoryHistory'))->name('inventory-history.index');
});

require __DIR__.'/settings.php';

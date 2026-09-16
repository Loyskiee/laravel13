<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('categories', CategoryController::class)->except(['edit']);
    Route::resource('tasks', TaskController::class)->except(['edit']);
    Route::resource('products', ProductController::class)->except(['edit']);
    Route::get('inventories', [InventoryController::class, 'index'])->name('inventories.index');
    Route::post('inventories', [InventoryController::class, 'store'])->name('inventories.store');
    Route::get('inventory-history', fn () => redirect()->route('inventories.index'))->name('inventory-history.index');
});

require __DIR__.'/settings.php';

<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;



Route::get('/', function () {
    return view('welcome');
});





// USER 
Route::middleware(['auth'])->group(function () {
    Route::resource('users', UserController::class);
});

Route::resource('categories', CategoryController::class);
Route::get('categories/{category}/products', [CategoryController::class, 'productsByCategory'])
    ->name('categories.products');


    

// Routes pour les produits
Route::resource('products', ProductController::class);
Route::get('products-search', [ProductController::class, 'index'])
    ->name('products.search');

Route::get('/shop', [ProductController::class, 'getActiveProductsByCategory'])->name('shop.index');




Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});




// ROLES
Route::resource('roles', RoleController::class);


require __DIR__.'/auth.php';

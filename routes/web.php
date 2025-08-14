<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
;



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



//Commandes
// routes/web.php

Route::middleware(['auth'])->group(function() {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{id}', [CartController::class, 'destroy'])->name('cart.remove');
    Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
});

Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
// Liste des commandes
Route::get('/mes-commandes', [OrderController::class, 'index'])->name('orders.index')->middleware('auth');

// Détails d'une commande (fetch via JS)
Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show')->middleware('auth');

require __DIR__.'/auth.php';

<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\MessageController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\DeliveryAddressController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\ChatbotResponseController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoyaltyPointController;
use App\Http\Controllers\NotificationController;



Route::get('/', function () {
    return view('welcome');
});


Route::get('/accueil', [HomeController::class, 'index'])->name('home');



// USER 
Route::middleware(['auth'])->group(function () {
    Route::resource('users', UserController::class);
});

Route::resource('categories', CategoryController::class);
Route::get('categories/{category}/products', [CategoryController::class, 'productsByCategory'])
    ->name('categories.products');

// Routes pour le chatbot
Route::post('/chatbot/send', [ChatbotResponseController::class, 'sendMessage'])->name('chatbot.send');
Route::get('/chatbot', [ChatbotResponseController::class, 'index'])->name('chatbot.index');



// Routes pour les produits
Route::resource('products', ProductController::class);
Route::get('products-search', [ProductController::class, 'index'])
    ->name('products.search');

Route::get('/shop', [ProductController::class, 'getActiveProductsByCategory'])->name('shop.index');




// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/support', function () {
    return view('support');
})->middleware(['auth', 'verified'])->name('support');

use App\Http\Controllers\ClientDashboardController;

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [ClientDashboardController::class, 'index'])->name('client.dashboard');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard/orders', [ClientDashboardController::class, 'order'])->name('client.orders');
    Route::get('/dashboard/panier', [ClientDashboardController::class, 'panier'])->name('client.panier');
    Route::get('/dashboard/recompense', [LoyaltyPointController::class, 'index'])->name('client.loyalty.index');
    Route::get('/dashboard/orders/{id}', [ClientDashboardController::class, 'ordershow'])->name('client.ordershow');
});

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
    Route::put('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{id}', [CartController::class, 'destroy'])->name('cart.remove');
    Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
});

Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
// Liste des commandes
Route::get('/mes-commandes', [OrderController::class, 'index'])->name('orders.index')->middleware('auth');

// Détails d'une commande (fetch via JS)
Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show')->middleware('auth');


Route::middleware('auth')->group(function() {
    Route::post('/addresses/store', [DeliveryAddressController::class, 'store'])->name('addresses.store');
    Route::get('/addresses/map', [DeliveryAddressController::class, 'map'])->name('address.map'); // page choix sur carte
});






Route::get('/delivery/pending', [DeliveryController::class, 'pendingOrders'])->name('delivery.pending');
Route::post('/delivery/mark-delivered/{id}', [DeliveryController::class, 'markDelivered'])->name('delivery.markDelivered');
Route::post('/delivery/start/{order}', [DeliveryController::class, 'startDelivery'])->name('delivery.start');
Route::post('/delive/start/{order}', [DeliveryController::class, 'startDelive'])->name('delive.start');
Route::post('/delivery/fin/{order}', [DeliveryController::class, 'finDelivery'])->name('delivery.fin');
Route::post('/delive/fin/{order}', [DeliveryController::class, 'finDelive'])->name('delive.fin');
Route::post('/delivery/valide/{order}', [DeliveryController::class, 'valideDelivery'])->name('delivery.valide');
Route::get('/delivery/tracking/{order}', [DeliveryController::class, 'tracking'])->name('delivery.tracking');
Route::get('/delivery/tracki/{order}', [DeliveryController::class, 'tracki'])->name('delivery.tracki');
Route::get('/delivery/delivered-orders', [DeliveryController::class, 'deliveredOrders'])
    ->name('delivery.delivered-orders');
Route::get('/delivery/dashboard', [DeliveryController::class, 'dashboard'])
    ->name('delivery.dashboard');


Route::prefix('messages')->middleware('auth')->group(function () {
    Route::get('/{receiver_id}', [MessageController::class, 'index'])->name('messages.index');
    Route::post('/{receiver_id}', [MessageController::class, 'store'])->name('messages.store');
    Route::put('/{message}', [MessageController::class, 'update'])->name('messages.update');
    Route::delete('/{message}', [MessageController::class, 'destroy'])->name('messages.destroy');
    Route::post('/read/{message}', [MessageController::class, 'markAsRead'])->name('messages.read');
    Route::get('/fetch/{receiver}', [MessageController::class, 'fetch'])->name('messages.fetch');
    Route::get('/{receiver}/fetch', [MessageController::class, 'fetch'])->name('messages.fetch');
    Route::post('/{receiver}/read', [MessageController::class, 'markAsRead'])->name('messages.read');
});
Route::prefix('admin/messages')->middleware('auth')->group(function () {
    Route::get('/', [MessageController::class, 'inbox'])->name('admin.messages.inbox');
    Route::get('/conversation/{client}', [MessageController::class, 'conversation'])->name('admin.messages.conversation');
});






// routes/web.php
Route::resource('notifications', NotificationController::class);


require __DIR__.'/auth.php';

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
use App\Http\Controllers\SettingController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoyaltyPointController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ClientDashboardController;
use App\Http\Controllers\SaleDashboardController;
use App\Http\Controllers\DirectSaleController;
use App\Http\Controllers\DirectSaleCartController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\ClientController;



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


use App\Http\Controllers\ShopController;

Route::prefix('shop')->group(function () {
    Route::get('/', [ShopController::class, 'index'])->name('shop.index');
    Route::get('/{id}', [ShopController::class, 'show'])->name('shop.show');
    Route::get('/order/{id}', [ShopController::class, 'order'])->name('shop.order');
});


// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/dashboard/support', function () {
    return view('client/support');
})->middleware(['auth', 'verified'])->name('client.support');


Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [ClientDashboardController::class, 'index'])->name('client.dashboard');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard/orders', [ClientDashboardController::class, 'order'])->name('client.orders');
    Route::get('/dashboard/panier', [ClientDashboardController::class, 'panier'])->name('client.panier');
    Route::get('/dashboard/shop', [ClientDashboardController::class, 'shop'])->name('client.shop');
    Route::get('/dashboard/recompense', [LoyaltyPointController::class, 'index'])->name('client.loyalty.index');
    Route::get('/menu-counts', [ClientDashboardController::class, 'counts'])->name('menu.counts');
    Route::get('/dashboard/orders/{id}', [ClientDashboardController::class, 'ordershow'])->name('client.ordershow');
});

Route::get('/counts', function() {
 
});

Route::prefix('dashboard')->middleware('auth')->group(function () {
    Route::get('settings', [SettingController::class, 'index'])->name('client.settings');
    Route::post('settings/profile', [SettingController::class, 'updateProfile'])->name('client.settings.profile');
    Route::post('settings/password', [SettingController::class, 'updatePassword'])->name('client.settings.password');
    Route::post('settings/preferences', [SettingController::class, 'updatePreferences'])->name('client.settings.preferences');
});
Route::prefix('sales')->middleware('auth')->group(function () {
    Route::get('settings', [SettingController::class, 'indexs'])->name('sale.settings');
    Route::post('settings/profile', [SettingController::class, 'updateProfiles'])->name('sale.settings.profile');
    Route::post('settings/password', [SettingController::class, 'updatePasswords'])->name('sale.settings.password');
    Route::post('settings/preferences', [SettingController::class, 'updatePreferencess'])->name('sale.settings.preferences');
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
    Route::get('/cart/data', [DirectSaleController::class, 'getData']);
    // web.php
Route::get('/cart/status', [DirectSaleController::class, 'status']);


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
Route::post('/delivery/cancel/{order}', [DeliveryController::class, 'cancel'])->name('delivery.cancel');
Route::post('/delivery/valide/{order}', [DeliveryController::class, 'valideDelivery'])->name('delivery.valide');
Route::get('/delivery/tracking/{order}', [DeliveryController::class, 'tracking'])->name('delivery.tracking');
Route::get('/delivery/tracki/{order}', [DeliveryController::class, 'tracki'])->name('delivery.tracki');
Route::get('/delivery/delivered-orders', [DeliveryController::class, 'deliveredOrders'])
    ->name('delivery.delivered-orders');
Route::get('/delivery/dashboard', [DeliveryController::class, 'dashboard'])
    ->name('delivery.dashboard');
Route::prefix('livreur')->middleware(['auth'])->group(function () {
    // ... autres routes existantes ...
    Route::get('settings', [SettingController::class, 'indexl'])->name('livreur.settings');
    Route::post('settings/profile', [SettingController::class, 'updateProfilel'])->name('livreur.settings.profile');
    Route::post('settings/password', [SettingController::class, 'updatePasswordl'])->name('livreur.settings.password');
    Route::post('settings/preferences', [SettingController::class, 'updatePreferencesl'])->name('livreur.settings.preferences');
    Route::get('/support', [DeliveryController::class, 'support'])->name('livreur.support');
    Route::post('/support/ticket', [DeliveryController::class, 'createSupportTicket'])->name('livreur.support.ticket');
    Route::get('/support/faq', [DeliveryController::class, 'faq'])->name('livreur.faq');
});



Route::get('/sales/dashboard', [SaleDashboardController::class, 'index'])
    ->name('sale.dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('admin/direct-sales', [DirectSaleController::class, 'index'])->name('admin.direct_sales.index');
    Route::get('sales/vente', [DirectSaleController::class, 'vente'])->name('sale.direct_sales.vente');
    Route::get('/direct-sales/create', [DirectSaleController::class, 'create'])->name('direct_sales.create');
    Route::post('/direct-sales', [DirectSaleController::class, 'store'])->name('sale.direct_sales');
});

// routes/web.php
Route::middleware(['auth'])->group(function () {
    Route::get('sales/dashboard/products', [ProductController::class, 'index'])->name('sale.products.index');
    Route::get('sales/dashboard/product/search', [ProductController::class, 'search'])->name('sale.products.search');
    Route::get('sales/dashboard/categories', [CategoryController::class, 'index'])->name('sale.categories.index');
    Route::get('sales/dashboard/categories/product/{product}', [CategoryController::class, 'showproduct'])->name('sale.categories.showproduct');
    // Route::get('/dashboard/panier', [SaleDashboardController::class, 'panier'])->name('client.panier');
  
    // Route::get('/dashboard/shop', [SaleDashboardController::class, 'shop'])->name('client.shop');
    Route::get('/dashboard/recompense', [LoyaltyPointController::class, 'index'])->name('client.loyalty.index');
    Route::get('/menu-counts', [SaleDashboardController::class, 'counts'])->name('menu.counts');
    Route::get('/dashboard/orders/{id}', [SaleDashboardController::class, 'ordershow'])->name('client.ordershow');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/sales/mes-ventes', [SaleDashboardController::class, 'sales'])->name('sale.ventes');
});



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






Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])
        ->name('admin.dashboard');
    Route::get('dashboard/sales-orders', [AdminDashboardController::class, 'salesOrders'])
    ->name('admin.sales_orders');
});


Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('/clients', [ClientController::class, 'index'])->name('admin.clients');
    Route::get('/clients/{id}', [ClientController::class, 'show'])->name('admin.clients.show');
    Route::post('/clients/{id}/toggle-status', [ClientController::class, 'toggleStatus'])->name('admin.clients.toggleStatus');
    Route::get('/clients/export/csv', [ClientController::class, 'exportCSV'])->name('admin.clients.export.csv');
    Route::get('/clients/export/pdf', [ClientController::class, 'exportPDF'])->name('admin.clients.export.pdf');
});

Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('/inventory', [AdminDashboardController::class, 'inventaire'])->name('admin.inventory');
    Route::get('/inventory/{id}', [AdminDashboardController::class, 'show'])->name('admin.inventory.show');
    Route::get('/inventory/export/csv', [AdminDashboardController::class, 'exportCSV'])->name('admin.inventory.export.csv');
    Route::get('/inventory/export/pdf', [AdminDashboardController::class, 'exportPDF'])->name('admin.inventory.export.pdf');
});



// routes/web.php
Route::resource('notifications', NotificationController::class);


require __DIR__.'/auth.php';

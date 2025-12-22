<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\BarangMasukController;
use App\Http\Controllers\BarangKeluarController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Routes untuk Stok Barang
    // index route (stok barang)
    Route::get('/stokbarang', [ProductController::class, 'index'])->name('stokbarang');
    // also expose as /barang for existing view links
    Route::get('/barang', [ProductController::class, 'index'])->name('barang');
    Route::get('/barang/create', [ProductController::class, 'create'])->name('barang.create');
    Route::post('/barang', [ProductController::class, 'store'])->name('barang.store');
    Route::get('/barang/{barang}', [ProductController::class, 'show'])->name('barang.show');
    Route::get('/barang/{barang}/edit', [ProductController::class, 'edit'])->name('barang.edit');
    Route::put('/barang/{barang}', [ProductController::class, 'update'])->name('barang.update');
    Route::delete('/barang/{barang}', [ProductController::class, 'destroy'])->name('barang.destroy');
    
    // Routes untuk Barang Masuk (CRUD)
    Route::resource('barang-masuk', BarangMasukController::class);
    
    // Routes untuk Barang Keluar (CRUD)
    Route::resource('barang-keluar', BarangKeluarController::class);

    // Routes untuk Sales / Transaksi
    Route::resource('sales', App\Http\Controllers\SaleController::class)->middleware('auth');

    // Cart / Checkout
    Route::get('/cart', [App\Http\Controllers\CheckoutController::class, 'cart'])->name('cart.index');
    Route::post('/cart/add', [App\Http\Controllers\CheckoutController::class, 'add'])->name('cart.add');
    Route::post('/cart/remove', [App\Http\Controllers\CheckoutController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/update', [App\Http\Controllers\CheckoutController::class, 'update'])->name('cart.update');
    Route::post('/checkout', [App\Http\Controllers\CheckoutController::class, 'checkout'])->name('checkout.process');
    Route::get('/checkout/{sale}/confirm', [App\Http\Controllers\CheckoutController::class, 'confirm'])->name('checkout.confirm');

    // Payments
    Route::post('/payments', [App\Http\Controllers\PaymentController::class, 'store'])->name('payments.store');

    // Mirror API endpoints on web routes for simplicity / local testing
    Route::post('/api/payments', [App\Http\Controllers\Api\PaymentApiController::class, 'store']);
    Route::post('/api/payments/webhook', [App\Http\Controllers\Api\WebhookController::class, 'payment']);
});

require __DIR__.'/auth.php';

<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\BarangMasukController;
use App\Http\Controllers\BarangKeluarController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\PurchaseRequestController;
use App\Http\Controllers\PurchaseApprovalController;
use App\Http\Controllers\GoodsReceiptController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\DailySaleController; // Pastikan ini ada
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\QuarantineController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PaymentController; // Tambahkan import ini
use App\Http\Controllers\Api\WebhookController; // Tambahkan import ini
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingPageController::class, 'index'])->name('landing');

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

// --- GROUP AUTH ---
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Kategori
    Route::resource('kategori', KategoriController::class);

    // Routes untuk Stok Barang
    Route::get('/stokbarang', [ProductController::class, 'index'])->name('stokbarang');
    // Manual routes untuk ProductController (Bisa disederhanakan pakai Route::resource tapi manual juga oke)
    Route::get('/barang', [ProductController::class, 'index'])->name('barang');
    Route::get('/barang/create', [ProductController::class, 'create'])->name('barang.create');
    Route::post('/barang', [ProductController::class, 'store'])->name('barang.store');
    Route::get('/barang/{barang}', [ProductController::class, 'show'])->name('barang.show');
    Route::get('/barang/{barang}/edit', [ProductController::class, 'edit'])->name('barang.edit');
    Route::put('/barang/{barang}', [ProductController::class, 'update'])->name('barang.update');
    Route::delete('/barang/{barang}', [ProductController::class, 'destroy'])->name('barang.destroy');
    
    // Barang Masuk & Keluar (Admin Only)
    Route::middleware('can:isAdmin')->group(function () {
        Route::resource('barang-masuk', BarangMasukController::class)->only(['index', 'show']);
        Route::get('/barang-masuk/export/pdf', [BarangMasukController::class, 'exportPDF'])->name('barang-masuk.export-pdf');
        
        Route::resource('barang-keluar', BarangKeluarController::class)->only(['index', 'show']);
        Route::get('/barang-keluar/export/pdf', [BarangKeluarController::class, 'exportPDF'])->name('barang-keluar.export-pdf');
    });

    // Supplier & Purchase Request
    Route::resource('suppliers', SupplierController::class);
    Route::resource('purchase-requests', PurchaseRequestController::class);
    
    // Purchase Approval
    Route::prefix('purchase-approvals')->name('purchase-approvals.')->group(function () {
        Route::get('/', [PurchaseApprovalController::class, 'index'])->name('index');
        Route::get('/{purchaseRequest}', [PurchaseApprovalController::class, 'show'])->name('show');
        // Terima POST (form) dan PUT (jika ada client yang mengirim PUT)
        Route::match(['post', 'put'], '/{purchaseRequest}/approve', [PurchaseApprovalController::class, 'approve'])->name('approve');
        Route::post('/{purchaseRequest}/reject', [PurchaseApprovalController::class, 'reject'])->name('reject');
    });

    // Goods Receipt
    Route::prefix('goods-receipts')->name('goods-receipts.')->group(function () {
        Route::get('/', [GoodsReceiptController::class, 'index'])->name('index');
        Route::get('/ready-to-receive', [GoodsReceiptController::class, 'purchasesReadyToReceive'])->name('ready');
        Route::get('/{purchase}/receive', [GoodsReceiptController::class, 'receive'])->name('receive');
        Route::post('/{purchase}/receive', [GoodsReceiptController::class, 'store'])->name('store');
        Route::get('/{goodsReceipt}/show', [GoodsReceiptController::class, 'show'])->name('show');
    });

    // Purchases
    Route::resource('purchases', PurchaseController::class);
    Route::post('/purchases/{purchase}/update-payment-status', [PurchaseController::class, 'updatePaymentStatus'])->name('purchases.updatePaymentStatus');
    Route::get('/purchases/{purchase}/pdf', [PurchaseController::class, 'exportPDF'])->name('purchases.exportPDF');
    Route::get('/purchases/{purchase}/record-payment', [PurchaseController::class, 'recordPayment'])->name('purchases.recordPayment');
    Route::post('/purchases/{purchase}/record-payment', [PurchaseController::class, 'storePayment'])->name('purchases.storePayment');
    Route::get('/barang-masuk/{barangMasuk}/create-purchase', [PurchaseController::class, 'create'])->name('purchases.createFromBarangMasuk');

    // Daily Sales (FIX URUTAN ROUTE)
    // Route spesifik harus di atas resource
    Route::get('/daily-sales/recap', [DailySaleController::class, 'recap'])->name('daily-sales.recap'); 
    Route::resource('daily-sales', DailySaleController::class);

    // Payments
    Route::post('/payments', [PaymentController::class, 'store'])->name('payments.store');

    // Admin Management
    Route::prefix('admin')->name('admin.')->middleware('can:isAdmin')->group(function () {
        Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
        Route::resource('quarantines', QuarantineController::class)->only(['index','show','update']);
    });
});

// --- DI LUAR AUTH GROUP ---
// Webhook harus bisa diakses publik (tanpa login session)
// Pastikan URL ini juga dikecualikan di app/Http/Middleware/VerifyCsrfToken.php
Route::post('/api/payments/webhook', [WebhookController::class, 'payment']);

require __DIR__.'/auth.php';
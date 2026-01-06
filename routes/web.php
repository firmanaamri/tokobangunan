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
    
    // Routes untuk Barang Masuk (READ ONLY - Auto-generated from Purchases) - Admin only
    Route::resource('barang-masuk', BarangMasukController::class)->only(['index', 'show'])->middleware('can:isAdmin');
    Route::get('/barang-masuk/export/pdf', [BarangMasukController::class, 'exportPDF'])->name('barang-masuk.export-pdf')->middleware('can:isAdmin');
    
    // Routes untuk Barang Keluar (READ ONLY - Auto-generated from Sales) - Admin only
    Route::resource('barang-keluar', BarangKeluarController::class)->only(['index', 'show'])->middleware('can:isAdmin');
    Route::get('/barang-keluar/export/pdf', [BarangKeluarController::class, 'exportPDF'])->name('barang-keluar.export-pdf')->middleware('can:isAdmin');

    // Routes untuk Supplier (CRUD)
    Route::resource('suppliers', SupplierController::class);

    // Routes untuk Purchase Request (PR) - Pengajuan Pembelian oleh Staff
    Route::resource('purchase-requests', PurchaseRequestController::class)->middleware('auth');

    // Routes untuk Purchase Approval (Admin approval workflow)
    Route::prefix('purchase-approvals')->name('purchase-approvals.')->group(function () {
        Route::get('/', [PurchaseApprovalController::class, 'index'])->name('index');
        Route::get('/{purchaseRequest}', [PurchaseApprovalController::class, 'show'])->name('show');
        Route::post('/{purchaseRequest}/approve', [PurchaseApprovalController::class, 'approve'])->name('approve');
        Route::post('/{purchaseRequest}/reject', [PurchaseApprovalController::class, 'reject'])->name('reject');
    });

    // Routes untuk Goods Receipt (GRN - Penerimaan & Inspeksi Barang)
    Route::prefix('goods-receipts')->name('goods-receipts.')->group(function () {
        Route::get('/', [GoodsReceiptController::class, 'index'])->name('index');
        Route::get('/ready-to-receive', [GoodsReceiptController::class, 'purchasesReadyToReceive'])->name('ready');
        Route::get('/{purchase}/receive', [GoodsReceiptController::class, 'receive'])->name('receive');
        Route::post('/{purchase}/receive', [GoodsReceiptController::class, 'store'])->name('store');
        Route::get('/{goodsReceipt}/show', [GoodsReceiptController::class, 'show'])->name('show');
    });

    // Routes untuk Purchase (Transaksi Pembelian dari Supplier)
    Route::resource('purchases', PurchaseController::class);
    Route::post('/purchases/{purchase}/update-payment-status', [PurchaseController::class, 'updatePaymentStatus'])->name('purchases.updatePaymentStatus');
    Route::get('/purchases/{purchase}/pdf', [PurchaseController::class, 'exportPDF'])->name('purchases.exportPDF');
    Route::get('/purchases/{purchase}/record-payment', [PurchaseController::class, 'recordPayment'])->name('purchases.recordPayment');
    Route::post('/purchases/{purchase}/record-payment', [PurchaseController::class, 'storePayment'])->name('purchases.storePayment');
    Route::get('/barang-masuk/{barangMasuk}/create-purchase', [PurchaseController::class, 'create'])->name('purchases.createFromBarangMasuk');

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

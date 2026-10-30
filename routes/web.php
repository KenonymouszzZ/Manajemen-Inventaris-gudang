<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockTransactionController;
use App\Http\Controllers\ReportController;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->middleware('throttle:5,1')
        ->name('login.store');

    Route::get('/register', [AuthController::class, 'showRegister'])
        ->name('register');

    Route::post('/register', [AuthController::class, 'register'])
        ->name('register.store');
});

Route::middleware('auth')->group(function () {

    Route::middleware('role:admin,staff')->group(function () {
    Route::get('/laporan', [ReportController::class, 'index'])
        ->name('reports.index');

    Route::get('/laporan/export/stok', [ReportController::class, 'exportStock'])
        ->name('reports.export-stock');

    Route::get('/laporan/export/transaksi', [
        ReportController::class,
        'exportTransactions',
    ])->name('reports.export-transactions');

    // Transaksi
     Route::get('/transaksi', [StockTransactionController::class, 'index'])
        ->name('transactions.index');

    Route::get('/transaksi/tambah', [StockTransactionController::class, 'create'])
        ->name('transactions.create');

    Route::post('/transaksi', [StockTransactionController::class, 'store'])
        ->name('transactions.store');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');

    // Halaman khusus admin
    Route::middleware('role:admin')->group(function () {
        Route::get('/admin', function () {
            return 'Halaman khusus Admin';
        })->name('admin.index');
    });

    // Daftar produk dan kategori bisa dilihat admin maupun staff
    Route::middleware('role:admin,staff')->group(function () {
        Route::get('/inventaris', [ProductController::class, 'index'])
            ->name('inventory.index');

        Route::get('/kategori', [CategoryController::class, 'index'])
            ->name('categories.index');
    });

    // Pengelolaan kategori dan produk hanya untuk admin
    Route::middleware('role:admin')->group(function () {
        // CRUD kategori
        Route::get('/kategori/tambah', [CategoryController::class, 'create'])
            ->name('categories.create');

        Route::post('/kategori', [CategoryController::class, 'store'])
            ->name('categories.store');

        Route::get('/kategori/{category}/edit', [CategoryController::class, 'edit'])
            ->name('categories.edit');

        Route::put('/kategori/{category}', [CategoryController::class, 'update'])
            ->name('categories.update');

        Route::delete('/kategori/{category}', [CategoryController::class, 'destroy'])
            ->name('categories.destroy');

        // CRUD produk
        Route::get('/produk/tambah', [ProductController::class, 'create'])
            ->name('products.create');

        Route::post('/produk', [ProductController::class, 'store'])
            ->name('products.store');

        Route::get('/produk/{product}/edit', [ProductController::class, 'edit'])
            ->name('products.edit');

        Route::put('/produk/{product}', [ProductController::class, 'update'])
            ->name('products.update');

        Route::delete('/produk/{product}', [ProductController::class, 'destroy'])
            ->name('products.destroy');
    });
});
});
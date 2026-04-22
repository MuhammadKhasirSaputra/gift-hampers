<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect()->route('login'));

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');
    
    // Produk
    Route::resource('products', ProductController::class);
    Route::get('/products/export/excel', [ExportController::class, 'exportExcel'])->name('products.export.excel');
    Route::get('/products/export/pdf', [ExportController::class, 'exportPdf'])->name('products.export.pdf');
    
    // Orders (Pemesanan)
    Route::resource('orders', OrderController::class);
    
    // ⚠️ TAMBAHKAN ROUTE INI (Update Status)
    Route::post('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    
    // Export Orders
    Route::get('/orders/export/excel', [OrderController::class, 'exportExcel'])->name('orders.export.excel');
    Route::get('/orders/export/pdf', [OrderController::class, 'exportPdf'])->name('orders.export.pdf');
    
    // Reports (Laporan)
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export/excel', [ReportController::class, 'exportExcel'])->name('reports.export.excel');
    Route::get('/reports/export/pdf', [ReportController::class, 'exportPdf'])->name('reports.export.pdf');
});

require __DIR__.'/auth.php';
<?php
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ExportController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect()->route('login'));

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');
    
    Route::resource('products', ProductController::class);
    
    Route::get('/products/export/excel', [ExportController::class, 'exportExcel'])->name('products.export.excel');
    Route::get('/products/export/pdf', [ExportController::class, 'exportPdf'])->name('products.export.pdf');
});

require __DIR__.'/auth.php';
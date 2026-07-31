<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\ReturnController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ChequeController;
use App\Http\Controllers\BankExpenseController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\CashBookController; 
use App\Http\Controllers\SalesController;

use App\Http\Controllers\Admin\UserMonitoringController;
use App\Http\Controllers\Admin\UpdateController;

Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes(['register' => false]); 

Route::middleware(['auth'])->group(function () {

    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');

    Route::post('/update-location', [HomeController::class, 'updateLocation'])->name('update.location');

    Route::prefix('pos')->name('pos.')->group(function () {
        Route::get('/', [PosController::class, 'index'])->name('index');
        Route::post('/store', [PosController::class, 'store'])->name('store');
        Route::post('/sync', [PosController::class, 'sync'])->name('sync');
        Route::get('/print/{id}', [PosController::class, 'print'])->name('print');
    });

    Route::prefix('returns')->name('returns.')->group(function () {
        Route::get('/', [ReturnController::class, 'index'])->name('index');
        Route::post('/search', [ReturnController::class, 'search'])->name('search');
        Route::post('/process', [ReturnController::class, 'process'])->name('process');
    });

    Route::prefix('quotations')->name('quotations.')->group(function () {
        Route::get('/', [QuotationController::class, 'index'])->name('index');
        Route::post('/store', [QuotationController::class, 'store'])->name('store');
        Route::get('/print/{id}', [QuotationController::class, 'print'])->name('print');
        Route::get('/{id}/edit', [QuotationController::class, 'edit'])->name('edit');
        Route::put('/{id}', [QuotationController::class, 'update'])->name('update');
        Route::delete('/{id}', [QuotationController::class, 'destroy'])->name('destroy');
    });

    Route::get('/products/search_ajax', [ProductController::class, 'searchAjax'])->name('products.search_ajax');
    Route::post('/products/barcode', [ProductController::class, 'printBarcode'])->name('products.print_barcode');
    Route::patch('/products/{id}/add-stock', [ProductController::class, 'addStock'])->name('products.add_stock');
    Route::resource('products', ProductController::class);

    Route::resource('categories', CategoryController::class);

    Route::get('/cashier/daily-sales', [SalesController::class, 'dailySales'])->name('cashier.daily_sales');

    Route::get('/bill/{id}/view', [SalesController::class, 'show'])->name('bill.view');
    Route::get('/bill/{id}/print', [SalesController::class, 'print'])->name('bill.print');

    Route::middleware([\App\Http\Middleware\IsAdmin::class])->group(function () {

        Route::resource('brands', BrandController::class);
        Route::resource('suppliers', SupplierController::class);

        Route::prefix('cheques')->name('cheques.')->group(function () {
            Route::get('/', [ChequeController::class, 'index'])->name('index');
            Route::post('/store', [ChequeController::class, 'store'])->name('store');
            Route::post('/realize', [ChequeController::class, 'realize'])->name('realize');
        });

        Route::prefix('finance')->name('finance.')->group(function () {
            Route::get('/', [BankExpenseController::class, 'index'])->name('index');
            Route::post('/bank/store', [BankExpenseController::class, 'storeBank'])->name('bank.store');
            Route::post('/expense/store', [BankExpenseController::class, 'storeExpense'])->name('expense.store');
            Route::put('/bank/{id}', [BankExpenseController::class, 'updateBank'])->name('bank.update');
            Route::post('/deposit', [BankExpenseController::class, 'storeDeposit'])->name('deposit.store');
        });

        Route::prefix('cashbook')->name('cashbook.')->group(function () {
            Route::get('/', [CashBookController::class, 'index'])->name('index');
            Route::post('/store', [CashBookController::class, 'store'])->name('store');
            Route::post('/to-bank', [CashBookController::class, 'transferToBank'])->name('to_bank');
        });

        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

        Route::resource('branches', BranchController::class);
        Route::resource('users', UserController::class);

        Route::get('/settings/general', [SettingController::class, 'index'])->name('settings.index');
        Route::post('/settings/update', [SettingController::class, 'update'])->name('settings.update');

        Route::get('/monitoring', [UserMonitoringController::class, 'index'])->name('admin.monitoring');
        Route::post('/monitoring/toggle-ban/{user}', [UserMonitoringController::class, 'toggleBan'])->name('admin.monitoring.toggle_ban');
        Route::get('/monitoring/history/{user}', [UserMonitoringController::class, 'getUserHistory'])->name('admin.monitoring.history');

        Route::get('/system-update', [UpdateController::class, 'index'])->name('admin.update.index');
        Route::post('/system-update/install', [UpdateController::class, 'installUpdate'])->name('admin.update.install');

    });

});

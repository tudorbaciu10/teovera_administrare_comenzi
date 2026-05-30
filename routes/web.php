<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PrintController;
use App\Http\Controllers\RouteController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Livewire\StoreOrder;
use Illuminate\Support\Facades\Route;

// Comutator limbă (public — funcționează și înainte de login)
Route::get('/lang/{locale}', [LocaleController::class, 'switch'])->name('lang.switch');

// Interfața vânzătoarei: /comanda/{token} (fără autentificare)
Route::get('/comanda/{token}', StoreOrder::class)->name('store.order');

// Autentificare
Route::get('/login',  [LoginController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'login'])->name('login.post')->middleware('guest');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Panou admin/operator (necesită autentificare)
Route::middleware('auth')->group(function () {
    Route::get('/', fn () => redirect()->route('orders.index'));

    // Comenzi
    Route::get('/orders',                          [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/export/csv',               [OrderController::class, 'exportCsv'])->name('orders.export-csv');
    Route::get('/orders/export/pdf',               [OrderController::class, 'exportPdf'])->name('orders.export-pdf');
    Route::get('/orders/poll',                     [OrderController::class, 'poll'])->name('orders.poll');
    Route::get('/orders/{order}',                  [OrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/mark-printata',  [OrderController::class, 'markPrintata'])->name('orders.mark-printata');
    Route::patch('/orders/{order}/mark-livrata',   [OrderController::class, 'markLivrata'])->name('orders.mark-livrata');
    Route::patch('/orders/{order}/mark-trimisa',   [OrderController::class, 'markTrimisa'])->name('orders.mark-trimisa');
    Route::delete('/orders/{order}',               [OrderController::class, 'destroy'])->name('orders.destroy');

    // CRUD Catalog + Administrare (doar admin)
    Route::middleware('role:admin')->group(function () {
        Route::resource('products',   ProductController::class)->except(['show']);
        Route::resource('categories', CategoryController::class)->except(['show']);
        Route::resource('routes',     RouteController::class)->except(['show']);
        Route::resource('stores',     StoreController::class);
        Route::post('stores/{store}/regenerate-token', [StoreController::class, 'regenerateToken'])->name('stores.regenerate-token');
        Route::resource('users',      UserController::class)->except(['show']);
    });

    // Printare
    Route::get('/print/order/{order}', [PrintController::class, 'singleOrder'])->name('print.order');
    Route::get('/print/orders',        [PrintController::class, 'bulkOrders'])->name('print.orders.bulk');
    Route::get('/print/order/{order}/pdf', [PrintController::class, 'singleOrderPdf'])->name('print.order.pdf');
    Route::get('/print/orders/pdf',        [PrintController::class, 'bulkOrdersPdf'])->name('print.orders.pdf');
});

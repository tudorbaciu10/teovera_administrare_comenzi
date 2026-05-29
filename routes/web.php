<?php

use App\Http\Controllers\PrintController;
use App\Livewire\StoreOrder;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Interfața vânzătoarei: /comanda/{token}
Route::get('/comanda/{token}', StoreOrder::class)->name('store.order');

// Printare (protejate — doar utilizatori autentificați în Filament)
Route::middleware('auth')->group(function () {
    Route::get('/print/order/{order}', [PrintController::class, 'singleOrder'])->name('print.order');
    Route::get('/print/orders', [PrintController::class, 'bulkOrders'])->name('print.orders.bulk');
});

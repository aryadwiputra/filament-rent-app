<?php

use App\Http\Controllers\FrontController;
use Illuminate\Support\Facades\Route;

Route::name('front.')->group(function () {
    Route::get('/', [FrontController::class, 'index'])->name('index');

    Route::get('/transactions', [FrontController::class, 'transactions'])->name('transactions');
    Route::post('/transactions/details', [FrontController::class, 'transactions_details'])->name('transactions.details');

    Route::get('/details/{product:slug}', [FrontController::class, 'details'])->name('details');

    Route::get('/booking/{product:slug}', [FrontController::class, 'booking'])->name('booking');
    Route::post('/booking/{product:slug}/save', [FrontController::class, 'booking_save'])->name('booking_save');

    Route::get('/success-booking/{transaction}', [FrontController::class, 'success_booking'])->name('success.booking');

    Route::post('/checkout/finish', [FrontController::class, 'checkout_store'])->name('checkout.store');

    Route::get('/checkout/{product:slug}/payment', [FrontController::class, 'checkout'])->name('checkout');

    Route::get('/category/{category:slug}', [FrontController::class, 'category'])->name('category');

    Route::get('/brand/{brand:slug}/products', [FrontController::class, 'brand'])->name('brand');

    Route::get('/booking/check', [FrontController::class, 'my_booking'])->name('my-booking');
});

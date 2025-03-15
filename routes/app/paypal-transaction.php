<?php
/**
 * @project gdt-cashier
 * @author hoepjhsha
 * @email hiepnguyen3624@gmail.com
 * @date 09/02/2025
 * @time 17:18
 */

use App\Http\Controllers\PaypalTransaction;
use App\Http\Middlewares\Auth;
use App\Http\Middlewares\Role;

Route::controller(PaypalTransaction::class)
    ->prefix('paypal-transaction')
    ->name('app.paypal-transaction.')
    ->middleware([Auth::class])
    ->middleware([Role::class])
    ->group(function() {
        Route::get('/manage', 'index')->name('index');
        Route::get('/show/{id}', 'show')->name('show');

        Route::post('/markclosed/{id}', 'markAsClosed')->name('markclosed');
        Route::post('/export', 'export')->name('export');
        Route::post('/refundPayment/{id}', 'refundPayment')->name('refundPayment');
    });

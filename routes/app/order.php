<?php

use App\Http\Controllers\Order;
use Illuminate\Support\Facades\Route;

Route::controller(Order::class)
    ->prefix('order')
    ->name('app.order.')
    ->group(function () {
        Route::get('/manage', 'index')->name('index');
        Route::get('/show/{id}', 'show')->name('show');

        Route::post('/delete/{id}', 'delete')->name('delete');
    })
    ->middleware([Auth::class]);

<?php

use App\Http\Controllers\Order;
use App\Http\Middlewares\Auth;
use App\Http\Middlewares\Role;
use Illuminate\Support\Facades\Route;

Route::controller(Order::class)
    ->prefix('order')
    ->name('app.order.')
    ->middleware([Auth::class])
    ->middleware([Role::class])
    ->group(function () {
        Route::get('/manage', 'index')->name('index');
        Route::get('/show/{id}', 'show')->name('show');

        Route::post('/delete/{id}', 'delete')->name('delete');
    });

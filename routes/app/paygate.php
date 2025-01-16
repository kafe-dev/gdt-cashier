<?php

use App\Http\Controllers\Paygate;
use App\Http\Middlewares\Auth;
use Illuminate\Support\Facades\Route;

Route::controller(Paygate::class)
    ->prefix('paygate')
    ->name('app.paygate.')
    ->group(function () {
        Route::get('/manage', 'index')->name('index');
        Route::get('/show/{id}', 'show')->name('show');
        Route::get('/create', 'create')->name('create');
        Route::get('/update/{id}', 'update')->name('update');
        Route::post('/delete/{id}', 'delete')->name('delete');
        Route::post('/store', 'store')->name('store');

    })
    ->middleware([Auth::class]);

<?php

use App\Http\Controllers\Transaction;
use App\Http\Middlewares\Auth;
use Illuminate\Support\Facades\Route;

Route::controller(Transaction::class)
    ->prefix('transaction')
    ->name('app.transaction.')
    ->middleware([Auth::class])
    ->group(function () {
        Route::get('/manage', 'index')->name('index');
        Route::get('/show/{id}', 'show')->name('show');
        Route::get('/create', 'create')->name('create');
        Route::get('/update/{id}', 'update')->name('update');
        Route::post('/updated/{id}', 'updated')->name('updated');
        Route::post('/delete/{id}', 'delete')->name('delete');
        Route::post('/store', 'store')->name('store');
        Route::get('/block/{id}', 'block')->name('block');
        Route::get('/unblock/{id}', 'unblock')->name('unblock');
    });

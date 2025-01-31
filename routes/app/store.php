<?php

use App\Http\Controllers\Store;
use App\Http\Middlewares\Auth;
use Illuminate\Support\Facades\Route;

Route::controller(Store::class)
    ->prefix('store')
    ->name('app.store.')
    ->middleware([Auth::class])
    ->group(function () {
        Route::get('/manage', 'index')->name('index');
        Route::get('/show/{id}', 'show')->name('show');
        Route::get('/create', 'create')->name('create');
        Route::get('/update/{id}', 'update')->name('update');
        Route::post('/store/{id?}', 'store')->name('store');
        Route::post('/delete/{id}', 'delete')->name('delete');
        Route::get('/changeStatus/{id}', 'changeStatus')->name('changeStatus');
        Route::get('testConnection/{id}', 'testConnection')->name('testConnection');
    });

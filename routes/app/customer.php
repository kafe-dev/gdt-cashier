<?php

use App\Http\Controllers\Customer;
use Illuminate\Support\Facades\Route;

Route::controller(Customer::class)
    ->prefix('customer')
    ->name('app.customer.')
    ->group(function () {
        Route::get('/manage', 'index')->name('index');
        Route::get('/show/{id}', 'show')->name('show');
        Route::get('/create', 'create')->name('create');
        Route::get('/update/{id}', 'update')->name('update');

        Route::post('/delete/{id}', 'delete')->name('delete');
    })
    ->middleware([Auth::class]);

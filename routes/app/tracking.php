<?php

use App\Http\Controllers\Tracking;
use Illuminate\Support\Facades\Route;
use App\Http\Middlewares\Auth;

Route::controller(Tracking::class)
    ->prefix('tracking')
    ->name('app.tracking.')
    ->middleware([Auth::class])
    ->group(function () {
        Route::get('/manage', 'index')->name('index');
        Route::get('/show/{id}', 'show')->name('show');

        Route::post('/delete/{id}', 'delete')->name('delete');
    });

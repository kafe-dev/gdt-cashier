<?php

use App\Http\Controllers\Tracking;
use App\Http\Middlewares\Auth;
use Illuminate\Support\Facades\Route;

Route::controller(Tracking::class)
    ->prefix('tracking')
    ->name('app.tracking.')
    ->middleware([Auth::class])
    ->group(function () {
        Route::get('/manage', 'index')->name('index');
        Route::get('/show/{id}', 'show')->name('show');

        Route::post('/delete/{id}', 'delete')->name('delete');
        Route::post('/markclosed/{id}', 'markAsClosed')->name('markclosed');
    });

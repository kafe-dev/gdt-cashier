<?php

use App\Http\Controllers\Dispute;
use App\Http\Middlewares\Auth;
use App\Http\Middlewares\Role;
use Illuminate\Support\Facades\Route;

Route::controller(Dispute::class)
    ->prefix('dispute')
    ->name('app.dispute.')
    ->middleware([Auth::class])
    ->middleware([Role::class])
    ->group(function () {
        Route::get('/manage', 'index')->name('index');
        Route::get('/show/{id}', 'show')->name('show');
        Route::post('/{id}/makeOffer', 'makeOffer')->name('makeOffer');

        Route::post('/delete/{id}', 'delete')->name('delete');
    });

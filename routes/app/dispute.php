<?php

use App\Http\Controllers\Dispute;
use App\Http\Middlewares\Auth;
use Illuminate\Support\Facades\Route;

Route::controller(Dispute::class)
    ->prefix('dispute')
    ->name('app.dispute.')
    ->middleware([Auth::class])
    ->group(function () {
        Route::get('/manage', 'index')->name('index');
        Route::get('/show/{id}', 'show')->name('show');
        Route::post('/delete/{id}', 'delete')->name('delete');
        Route::post('/send-message', 'sendMessage')->name('send-message');
        Route::post('/{id}/makeOffer', 'makeOffer')->name('makeOffer');
    });

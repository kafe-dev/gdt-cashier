<?php

use App\Http\Controllers\MailBox;
use App\Http\Middlewares\Auth;
use Illuminate\Support\Facades\Route;

Route::controller(MailBox::class)
    ->prefix('mail-box')
    ->name('app.mail-box.')
    ->middleware([Auth::class])
    ->group(function () {
        Route::get('/manage', 'index')->name('index');
        Route::get('/show/{id}', 'show')->name('show');

        Route::post('/delete/{id}', 'delete')->name('delete');
    });

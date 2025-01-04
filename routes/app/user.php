<?php

use App\Http\Controllers\User;
use Illuminate\Support\Facades\Route;

Route::controller(User::class)
    ->prefix('user')
    ->name('app.user.')
    ->group(function () {
        Route::get('/manage', 'index')->name('index');
    })
    ->middleware([Auth::class]);

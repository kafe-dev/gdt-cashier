<?php

use App\Http\Controllers\Home;
use App\Http\Middlewares\Auth;
use Illuminate\Support\Facades\Route;

Route::controller(Home::class)
    ->name('app.home.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
    })
    ->middleware([Auth::class]);

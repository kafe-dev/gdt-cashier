<?php

use App\Http\Controller\Home;
use App\Http\Middleware\Auth;
use Illuminate\Support\Facades\Route;

Route::controller(Home::class)
    ->name('app.home.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
    })
    ->middleware([Auth::class]);

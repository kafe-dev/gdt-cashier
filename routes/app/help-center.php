<?php

use App\Http\Middlewares\Auth;
use App\Http\Middlewares\Role;
use Illuminate\Support\Facades\Route;

Route::prefix('help-center')
    ->name('app.help-center.')
    ->middleware([Auth::class])
    ->middleware([Role::class])
    ->group(function () {
        Route::get('/faq', function () {
            return view('help-center.faq');
        })->name('faq');
    });

<?php

use App\Http\Middlewares\Auth;
use Illuminate\Support\Facades\Route;

Route::prefix('help-center')
    ->name('app.help-center.')
    ->middleware([Auth::class])
    ->group(function () {
        Route::get('/faq', function () {
            return view('help-center.faq');
        })->name('faq');
    });

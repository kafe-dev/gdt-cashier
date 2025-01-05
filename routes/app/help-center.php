<?php

use Illuminate\Support\Facades\Route;

Route::prefix('help-center')
    ->name('app.help-center.')
    ->group(function () {
        Route::get('/faq', function () {
            return view('help-center.faq');
        })->name('faq');
    })
    ->middleware([Auth::class]);

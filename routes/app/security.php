<?php

use App\Http\Controller\Security;
use Illuminate\Support\Facades\Route;

Route::controller(Security::class)
    ->prefix('security')
    ->name('app.security.')
    ->group(function () {
        Route::get('/login', 'login')->name('login');
    });

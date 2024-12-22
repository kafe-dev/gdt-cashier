<?php

use App\Http\Middlewares\Auth;
use App\Http\Controllers\Home;
use Illuminate\Support\Facades\Route;

require_once realpath(__DIR__.'/security.php');

Route::controller(Home::class)
     ->middleware([Auth::class])
     ->name('app.home.')
     ->group(function () {
         Route::get('/', 'index')->name('home');
     });

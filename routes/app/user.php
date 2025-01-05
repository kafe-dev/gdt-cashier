<?php

use App\Http\Controllers\User;
use Illuminate\Support\Facades\Route;

Route::controller(User::class)
     ->prefix('user')
     ->name('app.user.')
     ->group(function () {
         Route::get('/manage', 'index')->name('index');
         Route::get('/show/{id}', 'show')->name('show');
         Route::get('/create', 'create')->name('create');
         Route::get('/update/{id}', 'update')->name('update');

         Route::post('/delete/{id}', 'delete')->name('delete');
     })
     ->middleware([Auth::class]);

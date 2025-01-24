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
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/update/{id}', 'update')->name('update');
        Route::post('/store', 'store')->name('store');
        Route::post('/delete/{id}', 'delete')->name('delete');
        Route::get('/changeStatus{id}', 'changeStatus')->name('changeStatus');
    })
    ->middleware([Auth::class]);

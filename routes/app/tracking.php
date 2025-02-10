<?php

use App\Http\Controllers\Tracking;
use App\Http\Middlewares\Auth;
use App\Http\Middlewares\Role;
use Illuminate\Support\Facades\Route;

Route::controller(Tracking::class)
    ->prefix('tracking')
    ->name('app.tracking.')
    ->middleware([Auth::class])
    ->middleware([Role::class])
    ->group(function () {
        Route::get('/manage', 'index')->name('index');
        Route::get('/show/{id}', 'show')->name('show');

        Route::post('/delete/{id}', 'delete')->name('delete');
        Route::post('/markclosed/{id}', 'markAsClosed')->name('markclosed');
        Route::post('/export', 'export')->name('export');

        Route::get('/addTrackingInfo/{id}', 'addTrackingInfoView')->name('addTrackingView');
        Route::post('/addTracking/{id}', 'addTrackingInfo')->name('addTracking');
    });

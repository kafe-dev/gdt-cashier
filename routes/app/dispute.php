<?php

use App\Http\Controllers\Dispute;
use App\Http\Middlewares\Auth;
use App\Http\Middlewares\Role;
use Illuminate\Support\Facades\Route;

Route::controller(Dispute::class)
    ->prefix('dispute')
    ->name('app.dispute.')
    ->middleware([Auth::class])
    ->middleware([Role::class])
    ->group(function () {
        Route::get('/manage', 'index')->name('index');
        Route::get('/test', 'test')->name('test');
        Route::get('/show/{id}', 'show')->name('show');
        Route::post('/delete/{id}', 'delete')->name('delete');
        Route::post('/send-message', 'sendMessage')->name('send-message');
        Route::post('/escalate', 'escalate')->name('escalate');
        Route::post('/{id}/makeOffer', 'makeOffer')->name('makeOffer');
        Route::post('/{id}/acknowledgeReturned', 'acknowledgeReturned')->name('acknowledgeReturned');
        Route::post('/{id}/acceptClaim', 'acceptClaim')->name('acceptClaim');
        Route::post('/{id}/provideEvidence', 'provideEvidence')->name('provideEvidence');
        Route::post('/{id}/provideSupportingInfo', 'provideSupportingInfo')->name('provideSupportingInfo');
    });

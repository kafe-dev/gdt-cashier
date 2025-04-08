<?php

use App\Http\Controllers\User;
use App\Http\Middlewares\Auth;
use App\Http\Middlewares\Role;
use Illuminate\Support\Facades\Route;

Route::controller(User::class)
    ->prefix('user')
    ->name('app.user.')
    ->middleware([Auth::class])
    ->middleware([Role::class])
    ->group(function () {
        Route::get('/manage', 'index')->name('index');
        Route::get('/show/{id}', 'show')->name('show');
        Route::get('/create', 'create')->name('create');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/update/{id}', 'update')->name('update');
        Route::post('/store', 'store')->name('store');
        Route::post('/delete/{id}', 'delete')->name('delete');
        Route::get('/changeStatus/{id}', 'changeStatus')->name('changeStatus');
        Route::match(['get', 'post'], '/changePassword' , 'changePassword')->name('changePassword');

        Route::get('/role', 'roleIndex')->name('roleManage.index');
        Route::get('/role/edit/{id}', 'roleEdit')->name('roleManage.edit');
        Route::post('/role/update/{id}', 'roleUpdate')->name('roleManage.update');

        Route::get('/permission', 'permissionIndex')->name('permission.index');
        Route::get('/permission/create', 'permissionCreate')->name('permission.create');
        Route::post('/permission/store', 'permissionStore')->name('permission.store');
        Route::get('/permission/edit/{id}', 'permissionEdit')->name('permission.edit');
        Route::post('/permission/update/{id}', 'permissionUpdate')->name('permission.update');
        Route::post('/permission/delete/{id}', 'permissionDelete')->name('permission.delete');

        Route::get('/role/hierarchy', 'hierarchyIndex')->name('role.hierarchy.index');
        Route::get('/role/hierarchy/create', 'hierarchyCreate')->name('role.hierarchy.create');
        Route::post('/role/hierarchy/store', 'hierarchyStore')->name('role.hierarchy.store');
        Route::get('/role/hierarchy/{id}/edit', 'hierarchyEdit')->name('role.hierarchy.edit');
        Route::post('/role/hierarchy/{id}/update', 'hierarchyUpdate')->name('role.hierarchy.update');
        Route::post('/role/hierarchy/{id}/delete', 'hierarchyDelete')->name('role.hierarchy.delete');
    });

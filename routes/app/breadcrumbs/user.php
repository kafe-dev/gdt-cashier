<?php

use App\Models\User;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

// Dashboard > Manage Users
Breadcrumbs::for('manage-user', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Manage User', route('app.user.index'));
});

// Dashboard > Manage Users > Show User
Breadcrumbs::for('show-user', function (BreadcrumbTrail $trail, User $user) {
    $trail->parent('manage-user');
    $trail->push('Show User', route('app.user.show', ['id' => $user->id]));
});

// Dashboard > Manage Users > Create User
Breadcrumbs::for('create-user', function (BreadcrumbTrail $trail) {
    $trail->parent('manage-user');
    $trail->push('Create User', route('app.user.create'));
});

// Dashboard > Manage Users > Update User
Breadcrumbs::for('update-user', function (BreadcrumbTrail $trail, User $user) {
    $trail->parent('manage-user');
    $trail->push('Update User', route('app.user.update', ['id' => $user->id]));
});

<?php

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

// Default
Breadcrumbs::for('dashboard', function (BreadcrumbTrail $trail) {
    $trail->push('Dashboard', route('app.home.index'));
});

// Dashboard > Manage Users
Breadcrumbs::for('manage-user', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Manage User', route('app.user.index'));
});
